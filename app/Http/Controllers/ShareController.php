<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Inertia\Inertia;
use URL;
use App\Utils\AwsS3V4;

class ShareController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->validate([
            'photo_id' => ['required', 'exists:photos,id'],
            'download' => ['boolean'],
            'info' => ['boolean']
        ]);
        return URL::temporarySignedRoute('share.show', now()->addMinutes(30), [
            'id' => $data['photo_id'],
            'download' => isset($data['download']) ? $data['download'] : false,
            'info' => isset($data['info']) ? $data['info'] : false,
            'key' => bin2hex(random_bytes(64))
        ]);
    }
    public function view(Request $request, $id, $key)
    {
        $data = $request->validate([
            'download' => ['boolean'],
            'info' => ['boolean']

        ]);

        if (isset($data['info']) && $data['info'] == true) {
            $photo = Photo::with('user:id,name')->findOrFail($id);
        } else {
            $photo = Photo::findOrFail($id, ['id', 'file_name', 'height', 'width']);
        }


        $photo->add_temp_url('full_size', [
            'ResponseContentDisposition' => 'attachment' //this will ensure that file starts downloading instead of opening in browser
        ]);
        $downloadUrl  = null;
        $remaining_time = $request->expires - time();
        if ($remaining_time > 240) {
            $remaining_time = 240;
        }
        $awsS3V4 = new AwsS3V4($remaining_time);
        if (array_key_exists('download', $data)) {
            if ($data['download'] == true) {
                $downloadUrl = $awsS3V4->presignGet('/full_size/' . $photo->file_name, config('aws.fullsize_bucket'));
            }
        }

        $photo->src = $awsS3V4->presignGet('/preview_photos/' . $photo->file_name, config('aws.preview_bucket'));
        return Inertia::render('PhotoView/PhotoView', [
            'photo' => $photo,
            'downloadLink' => $downloadUrl,
            'info' => isset($data['info']) ? $data['info'] : false,
        ])->withViewData('public', true);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\SharePhoto;
use Illuminate\Http\Request;
use Inertia\Inertia;
use URL;
use App\Utils\AwsS3V4;
use Carbon\Carbon;

class ShareController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->validate([
            'photo_id' => ['required', 'exists:photos,id'],
            'download' => ['boolean'],
            'info' => ['boolean']
        ]);
        $share_key = bin2hex(random_bytes(16));
        SharePhoto::create([
            'share_key' => $share_key,
            'photo_id' => $data['photo_id'],
            'view_info' => isset($data['info']) ? $data['info'] : false,
            'download' => isset($data['download']) ? $data['download'] : false

        ]);
        return route('share.show', ['key' => $share_key]);
    }
    public function view(Request $request, $key)
    {
        // $data = $request->validate([
        //     'download' => ['boolean'],
        //     'info' => ['boolean']

        // ]);
        $data = $request->validate(['key' => 'string|digits:16']);
        $sharedPhoto = SharePhoto::where('share_key', '=', $key)->first();
        //return $sharedPhoto;
        // add check for expires
        if ($sharedPhoto) {

            if (isset($data['info']) && $data['info'] == true) {
                $photo = Photo::with('user:id,name')->findOrFail($sharedPhoto->photo_id);
            } else {
                $photo = Photo::findOrFail($sharedPhoto->photo_id, ['id', 'file_name', 'height', 'width']);
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
        abort(404);
    }
}

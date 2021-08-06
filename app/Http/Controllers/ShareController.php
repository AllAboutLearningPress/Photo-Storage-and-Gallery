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
            'photo_id' => ['required', 'exists:photos,id']
        ]);
        return URL::temporarySignedRoute('share.show', now()->addMinutes(30), [
            'id' => $data['photo_id'],
            'key' => bin2hex(random_bytes(64))
        ]);
    }
    public function view(Request $request, $id, $key)
    {

        $photo = Photo::with('user', 'tags')->findOrFail($id);
        $photo->add_temp_url('full_size', [
            'ResponseContentDisposition' => 'attachment' //this will ensure that file starts downloading instead of opening in browser
        ]);
        $awsS3V4 = new AwsS3V4();

        $downloadUrl = $awsS3V4->presignGet('/full_size/' . $photo->file_name, config('aws.fullsize_bucket'));
        $photo->src = $awsS3V4->presignGet('/preview_photos/' . $photo->file_name, config('aws.preview_bucket'));
        return Inertia::render('PhotoView/PhotoView', [
            'photo' => $photo,
            'downloadUrl' => $downloadUrl,
        ])->withViewData('public', true);
    }
}

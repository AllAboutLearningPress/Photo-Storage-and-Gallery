<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\SharePhoto;
use Illuminate\Http\Request;
use Inertia\Inertia;
use URL;
use App\Utils\AwsS3V4;
use Carbon\Carbon;
use Validator;

class ShareController extends Controller
{
    /** Generates a publicly shareable secure url for viewing
     * photos with set permissions. Urls are valid for 12 hours*/
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

    /**Returns view of publicly shared secure image
     * If download permission is provided then a download link
     * is generated. If view_info permission is provided then
     * anyone with the url can view all image info
     */
    public function view(Request $request, $key)
    {

        // $data = $request->validate([
        //     'download' => ['boolean'],
        //     'info' => ['boolean']

        // ]);
        $validator = Validator::make(
            [

                'key' => $key,
            ],
            [
                'key' => ['required', 'string', 'min:32', 'max:32'],

            ]
        );
        if ($validator->fails()) {
            //abort(404);
            dd($validator->errors());
        }
        $data = $validator->validated();

        $sharedPhoto = SharePhoto::where('share_key', '=', $data['key'])->firstOrFail();


        // if sharelink was created in the last 12 hours then show the share
        if ($sharedPhoto && $sharedPhoto->created_at >= Carbon::now()->subHour(12)) {

            if ($sharedPhoto->view_info == true) {
                $photo = Photo::with('user:id,name')->findOrFail($sharedPhoto->photo_id);
            } else {
                $photo = Photo::findOrFail($sharedPhoto->photo_id, ['id', 'file_name', 'height', 'width']);
            }


            $downloadUrl  = null;
            // all urls are valid for 12 hours from generating
            // Even though the url is valid. The download link
            // and preview link of s3 stored image is generated
            // for 240 seconds for secuirty reasons

            $remaining_time = time() - $request->expires;
            if ($remaining_time > 240) {
                $remaining_time = 240;
            }
            $awsS3V4 = new AwsS3V4($remaining_time);
            // generate download link if download permission is provided
            if ($sharedPhoto->download == true) {
                $downloadUrl = $awsS3V4->presignGet('full_size', $photo->file_name, config('aws.fullsize_bucket'));
            }

            // presign get request url for preview
            $photo->src = $awsS3V4->presignGet('preview_photos', $photo->file_name, config('aws.preview_bucket'));

            return Inertia::render('PhotoView/PhotoView', [
                'photo' => $photo,
                'downloadLink' => $downloadUrl,
                'info' => $sharedPhoto->view_info,
            ])->withViewData('public', true);
        }
        abort(404);
    }
}

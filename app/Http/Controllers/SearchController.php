<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use App\Utils\AwsS3V4;
use Cache;

class SearchController extends Controller
{
    public function searchTitle(Request $request)
    {
        $data = $request->validate(['search' => 'required|string']);
        $photos = Photo::search($data['search'])->get();
        $bucket = config('aws.fullsize_bucket');

        $awsS3V4 = new AwsS3V4(300);
        for ($x = 0; $x < count($photos); $x++) {
            // $thumbPath = $photos[$x]->genThumbPath();
            $photos[$x]->src = $awsS3V4->presignGet('thumbnails', $photos[$x]->file_name, $bucket);
            // $photos[$x]->src = Cache::remember($thumbPath, 19080, function () use ($awsS3V4, $thumbPath) {
            //     return $awsS3V4->presignGet($thumbPath);
            // });
        }
        return $photos->toArray();
    }
}

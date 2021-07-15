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
        $region = 'ap-southeast-1';
        $bucket = "aalpphotosdev";

        $awsS3V4 = new AwsS3V4($bucket, $region);
        for ($x = 0; $x < count($photos); $x++) {

            // $photos[$x]->add_temp_url('thumbnails');
            // continue;
            $thumbPath = $photos[$x]->genThumbPath();
            $photos[$x]->src = Cache::remember($thumbPath, 19080, function () use ($awsS3V4, $thumbPath) {
                return $awsS3V4->presignGet($thumbPath);
            });
        }
        return $photos->toArray();
    }
}

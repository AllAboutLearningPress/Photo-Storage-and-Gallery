<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use App\Utils\AwsS3V4;


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
            $photos[$x]->src = $awsS3V4->presignGet($photos[$x]->genThumbPath());
        }
        return $photos->toArray();
    }
}

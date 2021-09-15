<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Utils\AwsS3V4;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DuplicateController extends Controller
{
    public function index(Request $request, $left_id, $right_id)
    {
        $left = Photo::find($left_id);
        $right = Photo::find($right_id);
        $bucket = config('aws.fullsize_bucket');
        $awsS3V4 = new AwsS3V4();
        $left->src = $awsS3V4->presignGet($left->genFullPath('preview_photos'), $bucket);
        $right->src = $awsS3V4->presignGet($right->genFullPath('preview_photos'), $bucket);

        return Inertia::render('ComparePhoto', [
            'leftPhoto' => $left,
            'rightPhoto' => $right,
            'height' => max($left->height, $right->height)
        ]);
    }
}

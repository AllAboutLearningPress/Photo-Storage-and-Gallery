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
        return genTempSrc($photos, 'thumbnails');
    }
}

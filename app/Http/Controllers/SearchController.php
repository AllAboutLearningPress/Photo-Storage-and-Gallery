<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Aws\Lambda\LambdaClient;
use Aws\Credentials\Credentials;

class SearchController extends Controller
{
    public function searchTitle(Request $request)
    {
        $data = $request->validate(['search' => 'required|string']);
        $photos = Photo::search($data['search'])->get();
        foreach ($photos as $photo) {
            $photo->add_temp_url();
        }
        return $photos->toArray();
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;

class DownloadController extends Controller
{
    /**Generates a downloadable link for the specified photo id */
    public function generateLink(Request $request)
    {
        $data = $request->validate(['id' => 'required|exists:photos,id']);
        $photo = Photo::select('file_name')->find($data['id']);
        return 'storage/full_size/' . $photo['file_name'];
    }

    public function generateZip(Request $request)
    {
        //
    }
}

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
        // generating full size temp url for download
        $photo->addTempUrl('full_size', [
            'ResponseContentDisposition' => 'attachment' //this will ensure that file starts downloading instead of opening in browser
        ]);
        return $photo->src;
    }

    public function generateZip(Request $request)
    {
        //
    }
}

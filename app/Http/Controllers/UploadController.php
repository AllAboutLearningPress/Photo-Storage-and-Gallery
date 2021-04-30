<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UploadController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render("Upload");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'file' => 'required|mimes:jpg,jpeg,png,zip,psd|max:2048',
            'name' => 'required|string'
        ]);
        // generating a random string and getting the first 8 characters of the random
        // hex string. then adding an underscore to the file name. also all spaces are
        // removed from filename
        $name = bin2hex(random_bytes(32)) . str_replace(" ", "_", $data['file']->getClientOriginalName()); //
        $data['file']->storeAs("public/", $name, 'local');
        $imgsize = getimagesize($data['file']->getPathName());

        // adding the photo entry
        Photo::create([
            'name' => $name,
            'size' => $data['file']->getSize(),
            'height' => $imgsize[1],
            'width' => $imgsize[0],
            'file_type' => $data['file']->getClientMimeType(),
            'user_id' => Auth::user()->id,
        ]);
    }
}

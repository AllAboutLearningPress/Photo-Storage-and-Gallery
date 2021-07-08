<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class IndexController extends Controller
{
    /**
     * Display a listing of photos in /.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $photos = Photo::where('file_name', "!=", null)->cursorPaginate(30);
        //dd($photos->nextPageUrl());
        //dd($this->add_temp_url($photos));
        return Inertia::render('Index', [
            'photos' => $this->generateSrc($photos),
            'title' => 'AALP Photos Index'
        ]);
        //$this->add_temp_url($photos)

    }

    /**
     * Used to load more photos from requested offset
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function fetch_more(Request $request)
    {
        $photos = Photo::where('file_name', "!=", null)->cursorPaginate(30);
        return $this->generateSrc($photos);
    }

    /**
     * @param Array $photos - The array of photos returned by Laravel eloquet
     * @return Array Returns the same array with added url parameter
     */
    public function generateSrc($photos)
    {
        foreach ($photos as $photo) {
            $photo->add_temp_url('thumbnails');
        }
        return $photos;
    }


    public function trash()
    {
        return Inertia::render('Index', [
            'photos' => $this->generateSrc(Photo::onlyTrashed()->where('file_name', "!=", null)->cursorPaginate(30)),
            'title' => 'Trashed Photos',
        ])->withViewData(['title' => 'Trashed Photos']);
    }
}

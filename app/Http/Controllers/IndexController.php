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
        //dd($this->add_temp_url($photos));
        return Inertia::render('Index', [
            'photos' => $this->add_temp_url($photos),
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
        $photos = Photo::orderBy('id')->cursorPaginate(30);
        return $this->add_temp_url($photos);
    }

    /**
     * @param Array $photos - The array of photos returned by Laravel eloquet
     * @return Array Returns the same array with added url parameter
     */
    public function add_temp_url($photos)
    {
        foreach ($photos as $photo) {
            //dd($photo);
            $photo->url = "/storage/full_size/" . $photo->file_name;
            // $photo->url = Storage::disk('s3')->temporaryUrl(
            //     'full_size/' . $photo->file_name,
            //     now()->addMinutes(10)
            // );
        }
        return $photos;
    }


    public function trash()
    {
        return Inertia::render('Index', [
            'photos' => Photo::onlyTrashed()->where('file_name', "!=", null)->get(),
            'title' => 'Trashed Photos',
        ])->withViewData(['title' => 'Trashed Photos']);
    }
}

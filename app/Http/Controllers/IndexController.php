<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $photos =  Photo::limit(2)->get();
        return Inertia::render('Index', [
            'photos' => $this->add_temp_url($photos)
        ]);
    }

    public function fetch_more($requst)
    {
        $data = $requst->validate([
            'offset' => "required|integer"
        ]);

        $photos =  Photo::offset($data['offset'])->limit(20)->get();
        return $this->add_temp_url($photos);
    }

    public function add_temp_url($photos)
    {
        foreach ($photos as $photo) {
            $photo->url = Storage::disk('s3')->temporaryUrl(
                'full_size/' . $photo->file_name,
                now()->addMinutes(5)
            );
        }
        return $photos;
    }
}

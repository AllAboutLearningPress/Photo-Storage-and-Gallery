<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DuplicateController extends Controller
{
    public function index(Request $request, $photo_id1, $photo_id2)
    {
        $photo1 = Photo::find($photo_id1);
        $photo2 = Photo::find($photo_id2);

        return Inertia::render('ComparePhoto', [
            'photo1' => $photo1,
            'photo2' => $photo2,
        ]);
    }
}

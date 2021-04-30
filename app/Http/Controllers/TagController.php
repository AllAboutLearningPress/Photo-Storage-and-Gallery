<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function search($partial_tag)
    {
        return Tag::where('name', 'LIKE', $partial_tag . '%')
            ->select('id', 'name', 'slug')->limit(5)->get();
    }
}

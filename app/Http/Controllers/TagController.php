<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use DB;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Tag', ['tags' => Tag::limit(100)->get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Search Tags by partial tag name. It will be
     * used in the tags search box when adding tags to photos.
     *
     * @param string $partial_name
     */
    public function searchTags($partial_name)
    {
        return Tag::where('name', 'LIKE', $partial_name . '%')
            ->select('id', 'name', 'slug')->limit(5)->get();
    }

    public function getTags(Request $request)
    {
        if ($request->ajax()) {
            return DB::table('tags')->select('id', 'name', 'slug')->orderBy('id')->cursorPaginate(1000)->toArray();
        }
        return redirect(route('home'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Redirect;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('PhotoView/PhotoView');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id, $slug)
    {
        return  Inertia::render('PhotoView/PhotoView', [
            'photo' => Photo::withTrashed()->with('user', 'tags')->findOrFail($id)
        ]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|exists:photos,id',
            'force' => 'required|boolean'
        ]);

        // if force is set to true then the photo will
        // be permamnently deleted
        if ($data['force']) {
            Photo::withTrashed()->findOrFail($data['id'])->forceDelete();
            $flash_msg = 'Photo permanenetly deleted';
            $redirect_route = 'trash';
        } else {
            Photo::findOrFail($data['id'])->delete();
            $flash_msg = 'Photo moved to trash';
            $redirect_route = 'home';
        }
        // add code to delete elastic search data
        $request->session()->flash('success', $flash_msg);
        return Redirect::route($redirect_route);
    }

    public function restore(Request $request)
    {

        $data = $request->validate([
            'id' => 'required|exists:photos,id',
        ]);

        Photo::withTrashed()->find($data['id'])->restore();
        $request->session()->flash('success', 'Photo restored');
        return  Redirect::route('trash');
    }
}

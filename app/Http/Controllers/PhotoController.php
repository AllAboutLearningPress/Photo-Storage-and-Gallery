<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Tag;
use App\Utils\AwsS3V4;
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
    // p8

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id, $slug)
    {

        $photo = Photo::select(['id', 'title', 'file_name', 'height', 'width', 'size', 'time_taken', 'deleted_at', 'slug', 'user_id'])
            ->with('user:id,name', 'tags:id,name,slug')
            ->withTrashed()
            ->findOrFail($id);

        $awsS3V4 = new AwsS3V4();
        $bucket = config('aws.fullsize_bucket');
        //$photo->src = $awsS3V4->presignGet($photo->genFullPath('preview_photos'), $bucket);
        $photo->addTempUrl('preview_photos', $bucket);
        // dd($photo->src);
        $downloadLink = $awsS3V4->presignGet($photo->genFullPath('full_size'), $bucket);
        //$photo->addTempUrl('preview_photos');
        // Inertia::lazy(function () use ($photo) {
        //     return $photo;
        // }
        return  Inertia::render('PhotoView/PhotoView', [
            'photo' => $photo,
            'downloadLink' => $downloadLink,
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
        ///$request->session()->flash('success', $flash_msg);
        return Redirect::route($redirect_route);
    }

    public function restore(Request $request)
    {

        $data = $request->validate([
            'id' => 'required|exists:photos,id',
        ]);

        Photo::withTrashed()->find($data['id'])->restore();
        //$request->session()->flash('success', 'Photo restored');
        return  Redirect::back();
    }

    /**
     * User to get info about a single photo
     * This route is used in PhotoView.vue page when
     * rendered from route('home') page
     */

    // public function getInfo(Request $request)
    // {
    //     $data = $request->validate(['id' => 'integer']);
    //     $photo = Photo::where('id', $data['id'])->with(['tags', 'user'])->withTrashed()->firstOrFail();
    //     // $photo->addTempUrl('preview_photos');
    //     // $photo->addTempUrl
    //     $awsS3V4 = new AwsS3V4();
    //     $bucket = config('aws.fullsize_bucket');
    //     $photo->src = $awsS3V4->presignGet($photo->genFullPath('preview_photos'), $bucket);
    //     $photo->downloadLink = $awsS3V4->presignGet($photo->genFullPath('full_size'), $bucket);
    //     return $photo;
    //     return response($photo, 200);
    // }

    /**
     * Shows the view for trashed photo. It uses the
     * same vue page as index
     * @return \Inertia\Response
     */

    public function trash()
    {
        $photos = Photo::onlyTrashed()->where('height', "!=", null)->cursorPaginate(30);
        return Inertia::render('Index', [
            'photos' => genTempSrc($photos, 'thumbnails'),
            'title' => 'Trashed Photos',
        ])->withViewData(['title' => 'Trashed Photos']);
    }


    /**
     * Adds tag to photo
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addTag(Request $request)
    {
        $data = $request->validate([
            'fileId' => "required|int",
            'tagId' => "nullable|int|exists:tags,id",
            'tagName' => "required|string|max:255",
        ]);
        // withTrashed is required because users can try to
        // add tags while the photo is in trash
        $photo = Photo::withTrashed()->findOrFail($data['fileId']);

        // tagId wont be provided if its a new tag
        if ($data['tagId'] == null) {
            $tag = Tag::create([
                'name' => trim($data['tagName'])
            ]);
            $data['tagId'] = $tag->id;
        }
        $photo->tags()->attach($data['tagId']);

        if (isset($tag)) {
            return response($tag, 200);
        }
        return response('', 200);
    }
    /**
     * Removes tag to photo
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function removeTag(Request $request)
    {
        $data = $request->validate([
            'fileId' => "required|exists:photos,id",
            'tagId' => "nullable|exists:tags,id"
        ]);

        $photo = Photo::find($data['fileId']);
        $photo->tags()->detach($data['tagId']);


        return response('', 200);
    }
}

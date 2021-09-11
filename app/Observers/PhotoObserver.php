<?php

namespace App\Observers;

use App\Models\Photo;
use Cache;
use Storage;

class PhotoObserver
{
    /**
     * Handle the Photo "created" event.
     *
     * @param  \App\Models\Photo  $photo
     * @return void
     */
    public function created(Photo $photo)
    {
        $photo->slug = $photo->generateSlug($photo->title);
        $photo->save();
    }

    /**
     * Handle the Photo "updated" event.
     *
     * @param  \App\Models\Photo  $photo
     * @return void
     */
    public function updated(Photo $photo)
    {
        //
    }

    /**
     * Handle the Photo "deleted" event.
     *
     * @param  \App\Models\Photo  $photo
     * @return void
     */
    public function deleted(Photo $photo)
    {
        //
    }

    /**
     * Handle the Photo "restored" event.
     *
     * @param  \App\Models\Photo  $photo
     * @return void
     */
    public function restored(Photo $photo)
    {
        //
    }
    public function deleting(Photo $photo)
    {
        // deleting pivot table tag entries before the photo is deleted
        $photo->tags()->detach();
        $photo->labels()->detach();
    }
    /**
     * Handle the Photo "force deleted" event.
     *
     * @param  \App\Models\Photo  $photo
     * @return void
     */
    public function forceDeleted(Photo $photo)
    {
        $file_versions = ['full_size', 'thumbnails', 'preview_photos'];
        $full_paths = [];
        foreach ($file_versions as $file_version) {
            $full_path = $photo->genFullPath($file_version);
            Cache::forget($full_path);
            array_push($full_paths, $full_path);
        }

        // add code to remove download links
        Storage::disk('s3_fullsize')->delete($full_paths);
    }
}

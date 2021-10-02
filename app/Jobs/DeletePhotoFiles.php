<?php

namespace App\Jobs;

use App\Models\Photo;
use Cache;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Storage;

class DeletePhotoFiles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $file_name;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($file_name)
    {
        $this->file_name = $file_name;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //$photo = Photo::select(['id', 'file_name'])->withTrashed()->find($this->photoId);
        $file_versions = ['full_size', 'thumbnails', 'preview_photos'];
        $full_paths = [];
        foreach ($file_versions as $file_version) {
            $full_path = $file_version . "/" . $this->file_name;
            Cache::forget($full_path);
            array_push($full_paths, $full_path);
        }

        // // deleting pivot table tag entries before the photo is deleted
        // $photo->tags()->detach();
        // $photo->labels()->detach();

        // add code to remove download links

        Storage::disk('s3_fullsize')->delete($full_paths);
    }
}

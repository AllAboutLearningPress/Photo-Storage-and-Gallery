<?php

namespace Database\Seeders;

use App\Jobs\ProcessPhoto;
use App\Models\LabelPhoto;
use App\Models\Photo;
use App\Models\PhotoTag;
use Illuminate\Database\Seeder;
use Storage;
use Illuminate\Http\File;


class PhotoBatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // if (!Storage::disk('s3_fullsize')->has('full_size/demo.jpg')) {

        //     Storage::disk('s3_fullsize')->putFileAs('full_size', new File('storage/demo_assets/demo.jpg'), 'demo.jpg');
        // }
        // $photo_id = Photo::create([
        //     'title' => 'Demo Photo',
        //     'file_name' => 'demo.jpg',
        //     'dhash' => '1e1e7e0cff00bf10',
        //     'sha256' => '06dd072d850c3b83bfd2eef31852afb7e2ac1a262b59fe1a0e02a08bef848c2f', 'user_id' => 1,
        //     'height' => 1436,
        //     'width' => 1920,
        //     'size' => 209516,
        // ])->id;
        // ProcessPhoto::dispatch($photo_id);
        $assets_dir = 'storage/demo_assets/';

        // scandir returns "." and ".." in the result array. So need this to remove those
        $available_photos = array_values(array_diff(scandir($assets_dir), array('..', '.')));
        if ($available_photos && count($available_photos)) {
            for ($x = 0; $x < 200; $x++) {
                foreach ($available_photos as $file_name) {
                    if ($x == 0 && !Storage::disk('s3_fullsize')->has('full_size/' . $file_name)) {
                        Storage::disk('s3_fullsize')->putFileAs('full_size', new File('storage/demo_assets/' . $file_name), $file_name);
                    }


                    // if the photo entry existed before then we can just copy
                    // the attributes and we dont need to process the photo again
                    $photo = Photo::where('file_name', $file_name)->first();

                    if ($photo) {
                        $new_photo = Photo::create([
                            'title' => $file_name,
                            'file_name' => $file_name,
                            'dhash' => $photo->dhash,
                            'sha256' => $photo->sha256,
                            'user_id' => 1,
                            'height' => $photo->height,
                            'width' => $photo->width,
                            'size' => $photo->size,
                        ]);
                        //$label_ids = $photo->labels;
                        $new_photo_labels = [];
                        foreach ($photo->labels as $label) {
                            $new_photo_labels[$label->id] = ['score' => $label->pivot->score];
                        }
                        //$scores = array_fill(0, count($label_ids), ['score' => true]);
                        $new_photo->labels()->sync($new_photo_labels);
                        $new_photo->tags()->sync($photo->tags()->allRelatedIds());
                        // foreach ($new_photo->labels as $label) {
                        //     LabelPhoto::create(
                        //         [
                        //             'photo_id' => $new_photo->id,
                        //             'label_id' => $label->id,
                        //             'score' => $label->pivot->score
                        //         ]
                        //     );
                        // }

                        // foreach ($new_photo->tags as $tag) {
                        //     PhotoTag::create([
                        //         'photo_id' => $new_photo->id,
                        //         'tag_id' => $tag->id
                        //     ]);
                        // }
                    } else {
                        // no entry for the file name. Need to process photo
                        $new_photo = Photo::create([
                            'title' => $file_name,
                            'file_name' => $file_name,
                            'user_id' => 1
                        ]);
                        ProcessPhoto::dispatch($new_photo->id);
                    }
                }
            }
        }
    }
}

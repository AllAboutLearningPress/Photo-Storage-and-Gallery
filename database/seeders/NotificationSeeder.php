<?php

namespace Database\Seeders;

use App\Models\Photo;
use App\Models\Notification;
use File;
use Illuminate\Database\Seeder;
use PHPUnit\Framework\Error\Notice;
use Storage;

class NotificationSeeder extends Seeder
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
        // Photo::FirstOrCreate([
        //     'title' => 'Demo Photo',
        //     'file_name' => 'demo.jpg',
        //     'dhash' => '1e1e7e0cff00bf10',
        //     'sha256' => '06dd072d850c3b83bfd2eef31852afb7e2ac1a262b59fe1a0e02a08bef848c2f', 'user_id' => 1,
        //     'height' => 1436,
        //     'width' => 1920,
        //     'size' => 209516,
        // ])->id;

        $photoExists = Photo::where('file_name', '=', 'demo.jpg')->count();
        if (!$photoExists) {
            $this->call(PhotoSeeder::class);
        }
        Notification::factory()->count(10)->create();
    }
}

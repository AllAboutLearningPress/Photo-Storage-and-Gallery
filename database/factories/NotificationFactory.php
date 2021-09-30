<?php

namespace Database\Factories;

use App\Models\Notification;
use App\Models\Photo;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Notification::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $photo = Photo::where('file_name', '=', 'demo.jpg')->first();
        return
            [
                'data' => json_encode(['title' => 'Found Duplicate', 'body' => "\"{$photo->title}\" is a duplicate of \"{$photo->title}\" "]),
                'user_id' => $photo->user_id,
                'file_name' => $photo->file_name,
                'route' => json_encode(['name' => 'compare.index', 'options' => ['left' => $photo->id, 'right' => $photo->id]])
            ];
    }
}

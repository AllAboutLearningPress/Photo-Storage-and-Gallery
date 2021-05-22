<?php

namespace Database\Factories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

class TagFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tag::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // this function is not suitable for generating large number
        // of demo tags. But we should be good for 200-300
        $name = $this->faker->unique()->city();
        return [
            'name' => $name,
            'slug' => str_replace(" ", "_", $name) . rand(1, 99999999),
        ];
    }
}

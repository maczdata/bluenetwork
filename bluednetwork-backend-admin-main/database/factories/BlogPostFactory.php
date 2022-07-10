<?php

namespace Database\Factories;

use App\Models\Common\BlogPost;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BlogPostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BlogPost::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'slug' => $this->faker->slug,
            'title' => $this->faker->word,
            'summary' => $this->faker->sentence,
            'body' => $this->faker->realText(),
            'published_at' => today()->toDateTimeString(),
        ];
    }
}

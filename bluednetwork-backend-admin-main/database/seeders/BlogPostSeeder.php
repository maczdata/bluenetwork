<?php

namespace Database\Seeders;

use App\Models\Common\BlogPost;
use App\Models\Users\User;
use Illuminate\Database\Seeder;

class BlogPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BlogPost::factory(10)
            ->for(User::factory())
            ->create();
    }
}

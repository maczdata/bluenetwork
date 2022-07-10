<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CurrencyTableSeeder::class,
            ServiceSeeder::class,
            CategoryOfGiftCardTableSeeder::class,
            GiftCardTableSeeder::class,
            // CustomFieldSeeder::class,
            // UserSeeder::class,
            AdminSeeder::class,
            BlogPostSeeder::class
        ]);
    }
}

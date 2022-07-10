<?php

namespace Database\Seeders;

use App\Models\Common\SettingType;
use Illuminate\Database\Seeder;

class SettingTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $payload = [
            'App Name',
            'Homepage Carousel',
            'Homepage Sidebar Image',
            'Social Media',
        ];
        foreach($payload as $value) {
             SettingType::create(['name' => $value]);
        }
    }
}

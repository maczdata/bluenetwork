<?php

namespace Database\Seeders;

use App\Models\Common\CustomField;
use Illuminate\Database\Seeder;

class CustomFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CustomField::factory(5)->create();
    }
}

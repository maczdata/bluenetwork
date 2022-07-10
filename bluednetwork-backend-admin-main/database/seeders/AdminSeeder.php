<?php

namespace Database\Seeders;

use App\Models\Control\Admin;
use App\Models\Users\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory(2)->create()->assignRole('super_admin');
    }
}

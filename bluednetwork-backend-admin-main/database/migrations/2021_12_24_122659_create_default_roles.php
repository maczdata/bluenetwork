<?php

use App\Models\Common\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;

class CreateDefaultRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // this can be done as separate statements
        DB::statement("SET foreign_key_checks=0");
        Role::truncate();
        DB::statement("SET foreign_key_checks=1");

        Role::create(['name' => 'super_admin', 'guard_name' => 'dashboard']);
        Role::create(['name' => 'admin', 'guard_name' => 'dashboard']);
        Role::create(['name' => 'user', 'guard_name' => 'frontend']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}

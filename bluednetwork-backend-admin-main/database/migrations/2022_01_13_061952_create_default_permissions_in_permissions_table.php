<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class CreateDefaultPermissionsInPermissionsTable extends Migration
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
         Permission::truncate();
         DB::statement("SET foreign_key_checks=1");
 
         Permission::create(['name' => 'allow_withdrawal', 'guard_name' => 'dashboard']);
         Permission::create(['name' => 'create_user', 'guard_name' => 'dashboard']);
         Permission::create(['name' => 'update_user', 'guard_name' => 'dashboard']);
         Permission::create(['name' => 'delete_user', 'guard_name' => 'dashboard']);
         Permission::create(['name' => 'view_user', 'guard_name' => 'dashboard']);
         Permission::create(['name' => 'confirm_order', 'guard_name' => 'dashboard']);
         Permission::create(['name' => 'create_service', 'guard_name' => 'dashboard']);
         Permission::create(['name' => 'update_service', 'guard_name' => 'dashboard']);
         Permission::create(['name' => 'view_service', 'guard_name' => 'dashboard']);
         Permission::create(['name' => 'delete_service', 'guard_name' => 'dashboard']);
         Permission::create(['name' => 'create_transaction', 'guard_name' => 'dashboard']);
         Permission::create(['name' => 'update_transaction', 'guard_name' => 'dashboard']);
         Permission::create(['name' => 'view_transaction', 'guard_name' => 'dashboard']);
         Permission::create(['name' => 'delete_transaction', 'guard_name' => 'dashboard']);
         Permission::create(['name' => 'create_order', 'guard_name' => 'dashboard']);
         Permission::create(['name' => 'view_order', 'guard_name' => 'dashboard']);
         Permission::create(['name' => 'delete_order', 'guard_name' => 'dashboard']);
         Permission::create(['name' => 'update_order', 'guard_name' => 'dashboard']);
         Permission::create(['name' => 'create_service_type', 'guard_name' => 'dashboard']);
         Permission::create(['name' => 'update_service_type', 'guard_name' => 'dashboard']);
         Permission::create(['name' => 'view_service_type', 'guard_name' => 'dashboard']);
         Permission::create(['name' => 'delete_service_type', 'guard_name' => 'dashboard']);
         Permission::create(['name' => 'create_service_variant', 'guard_name' => 'dashboard']);
         Permission::create(['name' => 'update_service_variant', 'guard_name' => 'dashboard']);
         Permission::create(['name' => 'view_service_variant', 'guard_name' => 'dashboard']);
         Permission::create(['name' => 'delete_service_variant', 'guard_name' => 'dashboard']);
         Permission::create(['name' => 'create_custom_field', 'guard_name' => 'dashboard']);
         Permission::create(['name' => 'update_custom_field', 'guard_name' => 'dashboard']);
         Permission::create(['name' => 'view_custom_field', 'guard_name' => 'dashboard']);
         Permission::create(['name' => 'delete_custom_field', 'guard_name' => 'dashboard']);
         Permission::create(['name' => 'create_meta', 'guard_name' => 'dashboard']);
         Permission::create(['name' => 'update_meta', 'guard_name' => 'dashboard']);
         Permission::create(['name' => 'view_meta', 'guard_name' => 'dashboard']);
         Permission::create(['name' => 'delete_meta', 'guard_name' => 'dashboard']);
         Permission::create(['name' => 'create_feature', 'guard_name' => 'dashboard']);
         Permission::create(['name' => 'update_feature', 'guard_name' => 'dashboard']);
         Permission::create(['name' => 'view_feature', 'guard_name' => 'dashboard']);
         Permission::create(['name' => 'delete_feature', 'guard_name' => 'dashboard']);
         Permission::create(['name' => 'create_addon', 'guard_name' => 'dashboard']);
         Permission::create(['name' => 'update_addon', 'guard_name' => 'dashboard']);
         Permission::create(['name' => 'view_addon', 'guard_name' => 'dashboard']);
         Permission::create(['name' => 'delete_addon', 'guard_name' => 'dashboard']);
         Permission::create(['name' => 'create_settings', 'guard_name' => 'dashboard']);
         Permission::create(['name' => 'update_settings', 'guard_name' => 'dashboard']);
         Permission::create(['name' => 'view_settings', 'guard_name' => 'dashboard']);
         Permission::create(['name' => 'delete_settings', 'guard_name' => 'dashboard']);
         Permission::create(['name' => 'block_users', 'guard_name' => 'dashboard']);
         Permission::create(['name' => 'create_order', 'guard_name' => 'frontend']);
         Permission::create(['name' => 'allow_withdrawal', 'guard_name' => 'frontend']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('default_permissions_in_permissions');
    }
}

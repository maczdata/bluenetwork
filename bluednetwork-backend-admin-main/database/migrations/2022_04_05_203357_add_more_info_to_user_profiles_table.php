<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreInfoToUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->string('address')->after('bvn_verified_at')->nullable();
            $table->string('proof_of_address_type')->after('address')->nullable();

            $table->date('date_of_birth')->after('proof_of_address_verified_at')->nullable();
            $table->string('proof_of_identity_type')->after('proof_of_address_verified_at')->nullable();
            $table->string('proof_of_identity_number')->after('proof_of_address_verified_at')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            //
        });
    }
}

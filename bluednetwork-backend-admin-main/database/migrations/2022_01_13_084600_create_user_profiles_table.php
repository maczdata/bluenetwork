<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();

            $table->timestamp('bvn_verified_at')->nullable();
            $table->timestamp('proof_of_address_verified_at')->nullable();
            $table->timestamp('identity_verified_at')->nullable();
            
            $table->string('withdrawal_pin')->nullable();

            $table->integer('proof_of_address_verified_by')->unsigned()->nullable();
            $table->integer('identity_verified_by')->unsigned()->nullable();
            
            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->integer('deleted_by')->unsigned()->nullable();

            $table->timestamps();

            $table->foreign(['user_id'])->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_profiles');
    }
}

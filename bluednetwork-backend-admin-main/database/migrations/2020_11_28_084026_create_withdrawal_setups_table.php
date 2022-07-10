<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  hailatutor
 * @file                           2020_11_28_084026_create_withdrawal_setups_table.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     14/02/2021, 8:33 PM
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawalSetupsTable extends Migration
{
   /**
    * Run the migrations.
    *
    * @return void
    */
   public function up()
   {
      Schema::create('withdrawal_setups', function (Blueprint $table) {
         $table->id();
         $table->morphs('withdrawable');
         $table->boolean('enabled')->default(true);
         $table->string('provider')->nullable();
         $table->string('provider_value')->nullable();
         $table->unsignedBigInteger('bank_id')->nullable();
         $table->string('account_name')->nullable();
         $table->string('account_number')->nullable();
         $table->tinyInteger('is_default')->default(1);
         $table->timestamps();
      });
   }

   /**
    * Reverse the migrations.
    *
    * @return void
    */
   public function down()
   {
      Schema::dropIfExists('withdraw_setups');
   }
}

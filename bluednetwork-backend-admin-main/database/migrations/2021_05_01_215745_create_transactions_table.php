<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  hailatutor
 * @file                           2020_04_15_215745_create_transactions_table.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     07/01/2021, 9:23 AM
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
   /**
    * Run the migrations.
    *
    * @return void
    */
   public function up()
   {
      Schema::create('transactions', function (Blueprint $table) {
         $table->id();
         $table->integer('status')->nullable()->default(null)->comment('0 = Transaction failed\\\\n1 = Transaction successfull');
         $table->nullableMorphs('transactionable');
         $table->nullableMorphs('ownerable');
         $table->dateTime('paid_at')->nullable();
         $table->decimal('amount', 15, 4)->default(0.00);
         $table->string('currency_code', 3)->nullable();
         $table->decimal('currency_rate', 15, 8)->nullable();
         $table->string('payment_method')->nullable();
         $table->string('reference')->nullable();
         $table->text('transaction_note')->nullable();
         $table->tinyInteger('type')->default(1);
         $table->timestamps();
         $table->softDeletes();
      });
   }

   /**
    * Reverse the migrations.
    *
    * @return void
    */
   public function down()
   {
      Schema::dropIfExists('transactions');
   }
}

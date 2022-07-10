<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVirtualAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('virtual_accounts', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('flw_ref', 500)->nullable();
            $table->string('order_ref', 500)->nullable();
            $table->string('account_number', 500)->nullable();
            $table->integer('frequency')->nullable();
            $table->string('bank_name', 500)->nullable();
            $table->string('note', 500)->nullable();
            $table->decimal('amount', 8,2)->nullable();
            $table->dateTime('expiry_date')->nullable();
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
        Schema::dropIfExists('virtual_accounts');
    }
}

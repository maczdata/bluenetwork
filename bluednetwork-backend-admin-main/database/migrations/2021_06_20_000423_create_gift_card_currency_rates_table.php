<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGiftCardCurrencyRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gift_card_currency_rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gift_card_id')->nullable();
            $table->unsignedBigInteger('gift_card_currency_id');
            $table->unsignedBigInteger('gift_card_category_id')->nullable();
            $table->enum('rate_type', ['buying', 'selling']);
            $table->float('face_value_from')->default(0);
            $table->float('face_value_to')->default(0);
            $table->float('rate_value')->default(0);
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
        Schema::dropIfExists('gift_card_currency_rates');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->boolean('enabled')->default(true);
            $table->string('code');
            $table->string('name');
            $table->string('name_plural')->nullable();
            $table->string('symbol')->nullable();
            $table->string('symbol_native')->nullable();
            $table->integer('decimal_digits')->default(0);
            $table->integer('rounding')->default(0);
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
        Schema::dropIfExists('currencies');
    }
}

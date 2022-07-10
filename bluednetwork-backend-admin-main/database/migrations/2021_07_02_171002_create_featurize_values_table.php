<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeaturizeValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('featurize_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('featurize_id');
            $table->string('title');
            $table->string('slug');
            $table->text('description')->nullable();
            //$table->decimal('price',10,4)->default(0.00);
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
        Schema::dropIfExists('featurize_values');
    }
}

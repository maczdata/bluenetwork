<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomFieldResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_field_responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('custom_field_id')->index();
            $table->morphs('responseable');
            $table->string('type')->default('null');
            $table->longtext('value')->nullable();
            //$table->string('value_str')->nullable();
            //$table->longText('value_text')->nullable();
            //$table->integer('value_int')->nullable();
            $table->timestamps();
            $table->foreign('custom_field_id')->references('id')->on('custom_fields');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_field_responses');
    }
}

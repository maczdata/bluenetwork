<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfferFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offer_fields', function (Blueprint $table) {
            $table->id();
            $table->integer('offer_id')->nullable();
            $table->string('field_name', 500)->nullable();
            $table->string('description', 500)->nullable();
            $table->string('type', 500)->nullable();
            $table->integer('required_field')->nullable();
            $table->boolean('enabled')->default(true);
            $table->boolean('has_values')->default(false);
            $table->json('answers')->nullable();
            $table->string('default_value')->nullable();
            $table->text('validation_rules')->nullable();
            $table->json('file_types')->nullable();
            $table->string('max_file_size')->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offer_fields');
    }
}

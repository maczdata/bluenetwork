<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_fields', function (Blueprint $table) {
            $table->id();
            $table->boolean('enabled')->default(true);
            $table->string('title');
            $table->string('key')->nullable();
            $table->string('description')->nullable();
            $table->nullableMorphs('fieldable');
            $table->boolean('has_values')->default(false);
            $table->string('type');
            $table->boolean('required')->default(false);
            $table->json('answers')->nullable();
            $table->string('default_value')->nullable();
            $table->string('order')->nullable();
            $table->text('validation_rules')->nullable();
            $table->json('file_types')->nullable();
            $table->string('max_file_size')->nullable();
            $table->unsignedBigInteger('conditional_custom_field_id')->nullable();
            $table->string('conditional_custom_field_value')->nullable();
            $table->timestamp('archived_at')->nullable();
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
        Schema::dropIfExists('custom_fields');
    }
}

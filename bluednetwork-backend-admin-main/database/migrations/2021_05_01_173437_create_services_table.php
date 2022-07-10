<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->boolean('enabled')->default(true);
            $table->string('title')->nullable();
            $table->string('key')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('service_type_id');
            $table->decimal('price', 10, 3)->nullable();
            $table->enum('discount_type', ['', 'fixed', 'percentage'])->nullable();
            $table->decimal('discount_value',10,3)->nullable();
            $table->nestedSet();
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
        Schema::dropIfExists('services');
    }
}

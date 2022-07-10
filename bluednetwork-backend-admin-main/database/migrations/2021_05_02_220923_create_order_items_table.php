<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->morphs('orderitemable');
            $table->string('name')->nullable();
            $table->integer('quantity')->default(0)->nullable();
            //$table->unsignedBigInteger('service_id')->nullable();
            //$table->unsignedBigInteger('service_variant_id')->nullable();
            $table->decimal('price', 12,4)->default(0);
            $table->decimal('total', 12,4)->default(0);
            $table->unsignedBigInteger('order_id')->nullable();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
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
        Schema::dropIfExists('order_items');
    }
}

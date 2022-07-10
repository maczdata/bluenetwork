<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemAddonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_addons', function (Blueprint $table) {
            $table->id();
            $table->boolean('enabled')->default(true);
            $table->morphs('itemaddonable');
            $table->string('title');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->decimal('price',10,4)->default(0);
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
        Schema::dropIfExists('item_addons');
    }
}

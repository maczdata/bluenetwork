<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAmountToUserOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn("user_offers" , "amount")){
            Schema::table('user_offers', function (Blueprint $table) {
                //
                $table->decimal('amount', 8,2)->nullable()->after("user_id");
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_offers', function (Blueprint $table) {
            //
            if(Schema::hasColumn("user_offers" , "amount")){
                Schema::table('user_offers', function (Blueprint $table) {
                    //
                    $table->dropColumn('amount');
                });
            }
        });
    }
}

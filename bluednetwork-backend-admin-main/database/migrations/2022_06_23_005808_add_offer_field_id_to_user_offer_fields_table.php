<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOfferFieldIdToUserOfferFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn("user_offer_fields" , "offer_field_id")){
            Schema::table('user_offer_fields', function (Blueprint $table) {
                //
                $table->integer('offer_field_id')->nullable()->after("user_offer_id");
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
        if(Schema::hasColumn("user_offer_fields" , "offer_field_id")){
            Schema::table('user_offer_fields', function (Blueprint $table) {
                //
                $table->dropColumn('offer_field_id');
            });
        }
    }
}

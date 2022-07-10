<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDailyLimitToAccountLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_levels', function (Blueprint $table) {
            //
            if(!Schema::hasColumn('account_levels', 'daily_limit')){
                $table->decimal('daily_limit', 12, 4)->default(0.00);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_levels', function (Blueprint $table) {
            //
            if(Schema::hasColumn('account_levels', 'daily_limit')){
                $table->dropColumn('daily_limit');
            }
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRecipientWithdrawalSetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('withdrawal_setups', function (Blueprint $table) {
            //
            if(!Schema::hasColumn('withdrawal_setups', 'recipient')){
                $table->string('recipient', 255);
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
        //
        if(Schema::hasColumn('withdrawal_setups', 'recipient')){
            $table->dropColumn('recipient');
        }
    }
}

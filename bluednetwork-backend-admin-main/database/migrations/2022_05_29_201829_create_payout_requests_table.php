<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayoutRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payout_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('withdrawal_setup_id')->nullable();
            $table->nullableMorphs('ownerable');
            $table->string('status', 255)->nullable();
            $table->decimal('amount', 12, 4);
            $table->string('completed')->nullable();
            $table->decimal('final_amount', 12, 4);
            $table->longtext('decline_reason')->nullable();
            $table->date('completed_at')->nullable();
            $table->date('approved_at')->nullable();
            $table->date('declined_at')->nullable();
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
        Schema::dropIfExists('payout_requests');
    }
}

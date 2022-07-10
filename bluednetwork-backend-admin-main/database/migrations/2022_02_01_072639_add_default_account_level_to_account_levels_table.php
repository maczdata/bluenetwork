<?php

use App\Repositories\Common\AccountLevelRepository;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultAccountLevelToAccountLevelsTable extends Migration
{
    private AccountLevelRepository $accountLevelRepository;
    public function __construct()
    {
        $this->accountLevelRepository = resolve(AccountLevelRepository::class);
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (config('account_levels') as $level) {
            $this->accountLevelRepository->updateOrCreate([
                'name' => $level['name'],
            ], [
                'transaction_limit' => $level['transaction_limit'],
                'withdrawal_limit' => $level['withdrawal_limit'],
                'enabled' => $level['enabled'],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}

<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           Banks.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Console\Commands;

use App\Models\Common\Bank;
use Illuminate\Console\Command;

class Banks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bank:populate_banks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'populate banks from json';


    public function handle()
    {
        $jsonString = file_get_contents(base_path('resources/banks.json'));

        $banks = json_decode($jsonString);
        foreach ($banks->data as $bank) {
            if (Bank::where('code', $bank->code)->first()) {
                continue;
            }
            Bank::create([
                'name' => $bank->name,
                'code' => $bank->code,
                'status' => 1,
            ]);
        }
    }
}

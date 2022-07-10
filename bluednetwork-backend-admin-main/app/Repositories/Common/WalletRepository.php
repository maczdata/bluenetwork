<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           WalletRepository.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 2:40 PM
 */
namespace App\Repositories\Common;

use App\Eloquent\Repository;
use Exception;
use App\Models\Common\Wallet;
use Illuminate\Support\Facades\DB;

/**
 * Class WalletRepository
 * @package App\Repositories\Common
 */
class WalletRepository extends Repository
{

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return Wallet::class;
    }

    /**
     * @param $model
     * @param $amount
     * @param array $meta
     * @return bool
     * @throws Exception
     */
    public function deposit($model, $amount, array $meta = []): bool
    {
        $wallet = $this->walletInstanceOrCreate($model);

        try {
            DB::beginTransaction();
            $transaction = $wallet->transaction()
                ->create([
                    'ownerable_type' => $model->getMorphClass(),
                    'ownerable_id' => $model->getKey(),
                    'amount' => $amount,
                    'type' => 1,
                    'status' => 1
                ]);
            if (count($meta)) {
                foreach ($meta as $metaKey => $metaValue) {
                    $transaction->setMeta($metaKey, $metaValue);
                }
            }
            //$transaction->syncMeta($meta);
            $wallet->balance += $amount;
            $wallet->save();
            DB::commit();
            return true;
        } catch (\Exception $exception) {
            logger()->error('Wallet deposit exception error : ' . $exception);
            DB::rollBack();
            throw new \Exception($exception);
        }
    }

    /**
     * @param $model
     * @param $amount
     * @throws Exception
     */
    public function debit($model, $amount)
    {
        $wallet = $this->walletInstanceOrCreate($model);
        if (!$wallet->canWithdraw($amount)) {
            throw new Exception('Insufficient funds in wallet');
        }
        DB::beginTransaction();
        try {
            $wallet->balance -= $amount;
            $wallet->save();
            DB::commit();
            return true;
        } catch (\Exception $exception) {
            logger()->error('Wallet debt exception error : ' . $exception);
            DB::rollBack();
            throw new Exception($exception);
        }
    }

    /**
     * @param $model
     * @param $amount
     * @param array $meta
     * @return bool
     * @throws Exception
     */
    public function withDraw($model, $amount, array $meta = []): bool
    {
        $wallet = $this->walletInstanceOrCreate($model);
        if (!$wallet->canWithdraw($amount)) {
            throw new Exception('Insufficient funds in wallet');
        }
        DB::beginTransaction();
        try {
            // $transaction = $wallet->transaction()
            //     ->create([
            //         'ownerable_type' => $model->getMorphClass(),
            //         'ownerable_id' => $model->getKey(),
            //         'amount' => $amount,
            //         'type' => 2,
            //         'status' => 1,
            //     ]);
            if (count($meta)) {
                foreach ($meta as $metaKey => $metaValue) {
                    $transaction->setMeta($metaKey, $metaValue);
                }
            }
            $wallet->balance -= $amount;
            $wallet->save();
            DB::commit();
            return true;
        } catch (\Exception $exception) {
            logger()->error('Wallet withdrawal exception error : ' . $exception);
            DB::rollBack();
            throw new Exception($exception);
        }
    }

    /**
     * @param $model
     * @return mixed
     */
    public function walletInstanceOrCreate($model): mixed
    {
        return $this->model->firstOrCreate([
            'owner_type' => $model->getMorphClass(),
            'owner_id' => $model->getKey(),
        ]);
    }
}

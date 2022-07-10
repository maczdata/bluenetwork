<?php

/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           TransactionRepository.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 2:40 PM
 */

namespace App\Repositories\Common;

use App\Eloquent\Repository;
use App\Models\Common\Wallet;
use App\Models\Finance\Transaction;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class TransactionRepository
 * @package App\Repositories\Common
 */
class TransactionRepository extends Repository
{
    /**
     * @return string
     */
    public function model()
    {
        return Transaction::class;
    }

    /**
     * @param $model
     * @param array $request
     * @return mixed
     */
    public function getTransactions($model, array $request = []): mixed
    {
        $request = array_merge([
            'per_page' => 10,
            'transaction_class' => '',
            'transaction_type' => '',
            'sort_column' => '',
            'sort_order' => 'desc',
            'start_date' => '',
            'end_date' => '',
            'use_pagination' => null,
        ], $request);
        $transactions = $this->model;
        if (!is_null($model)) {
            $transactions = $transactions->where(function ($query) use ($model) {
                return $query->where([
                    'ownerable_id' => $model->getKey(),
                    'ownerable_type' => $model->getMorphClass(),
                ]);
            });
        }
        if (!empty($transactionType = $request['transaction_type'])) {
            if ($transactionType == 'incoming') {
                $typeNumeric = 1;
            } elseif ($transactionType == 'outgoing') {
                $typeNumeric = 2;
            }
        }
        
        if (isset($typeNumeric)) {
            $transactions = $transactions->where('type', $typeNumeric);
        }


        if (!empty($request['start_date']) && !empty($request['end_date'])) {
            $transactions = $transactions->whereBetween('created_at', [$request['start_date'], $request['end_date']]);
        } else {
            if (!empty($request['start_date'])) {
                $transactions = $transactions->where('created_at', '=', $request['start_date']);
            }
            if (!empty($request['end_date'])) {
                $transactions = $transactions->where('created_at', '=', $request['end_date']);
            }
        }

        $transactions = $transactions->orderBy('id', "DESC");

        if (!empty($sortColumn = $request['sort_column'])) {
            $transactions = $transactions->orderBy($sortColumn, $request['sort_order']);
        }
        if ($request['use_pagination'] == '0') {
            return $transactions->take($request['per_page'])->get();
        } else {
            return $transactions->paginate($request['per_page']);
        }
    }

    /**
     * @param $field
     * @param $value
     * @param bool $throwException
     * @return mixed
     */
    public function getTransaction($field, $value, bool $throwException = true): mixed
    {
        $transaction = $this->findOneByField($field, $value);
        if (!is_null($transaction)) {
            return $transaction;
        }
        if ($throwException == true) {
            throw new NotFoundHttpException();
        }
        return false;
    }
}

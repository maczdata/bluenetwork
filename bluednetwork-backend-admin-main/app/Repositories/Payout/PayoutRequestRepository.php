<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           PayoutRequestRepository.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/04/2021, 4:31 AM
 */

namespace App\Repositories\Payout;

use App\Eloquent\Repository;
use App\Models\Finance\PayoutRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class PayoutRequestRepository
 * @package App\Repositories\Payout
 */
class PayoutRequestRepository extends Repository
{
   /**
    * @return string
    */
   public function model()
   {
      return PayoutRequest::class;
   }

   /**
    * @param Request $request
    * @param array $owner
    * @return Model
    */
   public function getPayouts(Request $request, array $owner = [])
   {
      $request = $request->merge([
         'per_page' => 10,
         'use_pagination' => '0',
         'filter_status' => '',
         'sort_column' => '',
         'sort_order' => 'desc',
      ]);


      $payouts = $this->model;
      $ownerId = $owner['owner_id'];
      $payouts = $payouts->whereHasMorph('ownerable', $owner['type'], function ($query) use ($ownerId) {
         $query->where(['ownerable_id'=>$ownerId]);
      });
      if (!empty($payoutStatus = $request['filter_status'])) {
         $payouts = $payouts->where('status', '=', $payoutStatus);
      }

      if (!empty($sortColumn = $request['sort_column'])) {
         $payouts = $payouts->orderBy($sortColumn, $request['sort_order']);
      }

      if (!empty($use_pagination = $request['use_pagination']) && $use_pagination == '0') {
         return $payouts->take($request['per_page'])->get();
      } else {
         return $payouts->paginate($request['per_page']);
      }
   }

   /**
    * @param $model
    * @param $amount
    * @param $status
    * @return mixed
    */
   public function makePayout($model, $amount, $status)
   {
      return $model->payouts()->create([
         'amount' => $amount,
         'final_amount' => $amount,
         'status' => $status
      ]);
   }
}

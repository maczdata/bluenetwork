<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           TransactionTransformer.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/08/2021, 11:41 PM
 */

namespace App\Transformers\Common;

use App\Models\Common\Service;
use App\Models\Common\ServiceVariant;
use App\Models\Common\Wallet;
use App\Models\Finance\Transaction;
use App\Models\Offer;
use App\Models\Sales\Order;
use App\Models\Users\UserOffer;
use League\Fractal\TransformerAbstract;

/**
 * Class TransactionTransformer
 *
 * @package App\Transformers\Common
 */
class TransactionTransformer extends TransformerAbstract
{

    /**
     * @param Transaction $transaction
     * @return array
     */
    public function transform(Transaction $transaction)
    {
        return [
            'id' => (int)$transaction->id,
            'transaction_message' => $this->transactionType($transaction),
            'reference' => $transaction->reference,
            'amount_raw' => ($transaction->amount ?? 0),
            'amount_formatted' => core()->formatBasePrice($transaction->amount ?? 0),
            'status' => $transaction->status,
            'type' => (($transaction->type == 1) ? 'incoming' : (($transaction->type == 2) ? 'outgoing' : '')),
            //'meta' => $transaction->getAllMeta(),
            'created_at' => $transaction->created_at->format('Y-m-d H:ia'),
            'updated_at' => $transaction->updated_at->format('Y-m-d H:ia'),
        ];
    }

    /**
     * @param $transaction
     * @return array
     */
    private function transactionType($transaction): array
    {
        $messageArray = [
            'main_message' => '',
            'sub_message' => '',
        ];
        
        if ($transaction->transactionable_type == "Offer") {
                $useroffer = UserOffer::where('id', $transaction->transactionable_id)->first();
                $offername = $useroffer?->offer ? $useroffer->offer->name : "";
                $messageArray = [
                    'main_message' =>  (($transaction->type == '2') ? 'Paid for Offer-'.$offername : ''),
                ];
        }else{
            if (!is_null($transaction->transactionable)) {
            
                if ($transaction->transactionable instanceof Wallet) {
                    $messageArray = [
                        'main_message' => (($transaction->type == '2') ? 'Wallet Deduction' : (($transaction->type == '1') ? 'Wallet topup' : '')),
                    ];
                }
                if ($transaction->transactionable instanceof Order) {
                    if ($transaction->transactionable->orderable instanceof ServiceVariant) {
                        $serviceVariant = $transaction->transactionable->orderable;
                        $variantService = $serviceVariant->service;
                        $messageArray = [
                            'main_message' => ucfirst($variantService->service_type?->title) . ' ' . ucfirst($variantService->parent ?
                                    $variantService->parent?->title : $variantService->title),
                            'sub_message' => ucfirst($serviceVariant->title) . ' ' . ucfirst($variantService->title),
                        ];
                    }

                    if ($transaction->transactionable->orderable instanceof Service) {
                        $childService = $transaction->transactionable->orderable;
                        $parentService = $childService->parent;
                        $messageArray = [
                            'main_message' => (!is_null($childService->service_type) ? ucfirst($childService->service_type?->title) : '') . ' ' . (!is_null($parentService) ? ucfirst($parentService->title) : ''),
                            'sub_message' => ucfirst($childService->title),
                        ];
                    }
                }
            }
        }
        return $messageArray;
    }
}

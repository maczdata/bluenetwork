<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           GiftCardExchange.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/08/2021, 11:13 PM
 */

namespace App\Services\ServiceHandler;

use App\Abstracts\Services;
use App\Repositories\Common\GiftCardCurrencyRateRepository;
use App\Repositories\Common\GiftCardRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class GiftCardExchange extends Services
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return "Gift card exchange";
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return "";
    }

    /**
     * @param array $data
     * @param array $rules
     * @param array $rulesMessages
     * @return bool|\Illuminate\Contracts\Validation\Validator
     */
    public function validate(
        array $data = [],
        array $rules = [],
        array $rulesMessages = []
    ): \Illuminate\Contracts\Validation\Validator|bool {
        $giftCard = ($data['gift_card_id'] ? $this->getGiftCard($data['gift_card_id']) : null);
        $giftCardCategory = $giftCard->categories()->where('id', $data['gift_card_category_id'] ?? '')->first();
        $categoryHasChildren = ($giftCardCategory && $giftCardCategory->categoryOfGiftCard->children->count());
        $rules = [
            'gift_card_id' => 'required|exists:gift_cards,id',
            'gift_card_currency_id' => [
                'required',
                Rule::exists('gift_card_currencies', 'id')->where(function ($query) use ($data) {
                    return $query->where('gift_card_id', $data['gift_card_id'] ?? '');
                }),
            ],
            'gift_card_category_id' => [
                'required',
                Rule::exists('gift_card_categories', 'id')->where(function ($query) use ($data) {
                    return $query->where('gift_card_id', $data['gift_card_id'] ?? '');
                }),
            ],
            'gift_card_sub_category_id' => [
                Rule::requiredIf($categoryHasChildren),
                $categoryHasChildren ? Rule::exists('category_of_gift_cards', 'id')->where(function ($query) use (
                    $data,
                    $giftCardCategory
                ) {
                    return $query->where(['parent_id' => $giftCardCategory ? $giftCardCategory->category_of_gift_card_id : '']);
                }) : '',
            ],
            "gift_card_rates" => "required|array|min:1",
        ];

        $rulesMessages = [
            'gift_card_id.required' => 'Gift card is required',
            'gift_currency_id.required' => 'Currency is required',
            'gift_card_category_id.required' => 'Gift card category is required',
            'gift_card_rates.required' => 'Gift card rate is required',
            'gift_card_rates.*.codes.required' => 'Gift card rate E-code is required',
        ];
        if ($giftCardCategory && $giftCardCategory->categoryOfGiftCard->getMeta('requires_upload')) {
            $rules['gift_card_proof_files'] = "required|min:1";
            $rules['gift_card_proof_files.*'] = "image";
        }

        if (isset($data['gift_card_rates']) && !is_null($data['gift_card_rates']) && is_array($data['gift_card_rates'])) {
            $rules['gift_card_rates'] = 'required';
            $rules['gift_card_rates.*.rate_id'] = 'required';
            $rules['gift_card_rates.*.quantity'] = 'required|integer';

            foreach ($data['gift_card_rates'] as $giftCardRateKey => $giftCardRate) {
                $rules['gift_card_rates.*.codes'] = [
                    Rule::requiredIf(
                        $giftCardCategory && ($giftCardCategory->categoryOfGiftCard->getMeta('requires_code') == 1)
                        && ($data['gift_card_rates'][$giftCardRateKey]['quantity'] > 0)
                    ),
                ];
                //     //$rulesMessages['gift_card_rates.' . $giftCardRateKey . '.date.required_if'] = 'Date is required';
            }
        }
        return parent::validate($data, $rules, $rulesMessages);
    }

    /**
     * @param Request $data
     * @return string
     * @throws Exception
     */
    public function execute(Request $data): string
    {
        $processOtherData = false;
        $giftCardId = $data['gift_card_id'];
        $giftCardCurrencyId = $data['gift_card_currency_id'];
        $giftCardCategoryId = $data['gift_card_category_id'];
        $giftCardChildCategoryId = $data['gift_card_sub_category_id'] ?? null;
        $giftCardRates = $data['gift_card_rates'] ?? [];
        $user = $this->currentUser;
        $giftCard = $this->getGiftCard($giftCardId);
        $giftCardCategory = $giftCard->categories()->where('id', $giftCardCategoryId)->first();
        $categoryHasChildren = ($giftCardCategory && $giftCardCategory->categoryOfGiftCard->children->count());
        DB::beginTransaction();
        try {
            $orderItems = [];
            $orderMeta = [];

            if (!is_null($giftCardRates) && (count($giftCardRates) > 0)) {
                for ($i = 0; $i < count($giftCardRates); $i++) {
                    $validCurrenRate = app(GiftCardCurrencyRateRepository::class)->scopeQuery(function ($query) use (
                        $i,
                        $giftCardRates,
                        $giftCardId
                    ) {
                        return $query->where(['id' => $giftCardRates[$i]['rate_id'], 'gift_card_id' => $giftCardId]);
                    })->first();
                    if (!$validCurrenRate) {
                        continue;
                    }
                    $quantity = $giftCardRates[$i]['quantity'];
                    $codes = $giftCardRates[$i]['codes'] ?? [];

                    // if category requires code, and the number of code entered is not equal to the quantity select,
                    // then reduce the quantity.
                    if ($giftCardCategory && $giftCardCategory->requires_code && (($codeCount = count($codes)) < $quantity)) {
                        $quantity = ($quantity - $codeCount);
                    }
                    $orderMeta['gift_card_currency'] = (int)$giftCardCurrencyId;
                    $orderMeta['gift_card_category'] = $giftCardCategory;
                    if ($categoryHasChildren) {
                        $orderMeta['gift_card_child_category'] = $giftCardCategory->categoryOfGiftCard->children()->find($giftCardChildCategoryId);
                    }

                    $orderItems[$i] = [
                        'item' => $validCurrenRate,
                        'quantity' => $quantity,
                        'price' => $validCurrenRate->rate_value,
                        'total' => ($quantity * $validCurrenRate->rate_value),
                        //'meta' => $meta
                    ];
                    //get the e-code based on the quantity that's being ordered
                    if ($giftCardCategory && ($giftCardCategory->requires_code == 1) && ($quantity > 0) && count($codes)) {
                        for ($qi = 0; $qi <= $quantity; $qi++) {
                            $orderItems[$i]['meta'] = [
                                'codes' => [
                                    $giftCardRates[$i]['rate_id'] => $codes,
                                ],
                            ];
                            // $orderItems[$i]['item_meta']['codes'][$giftCardRates[$i]['rate_id']] = $codes;
                        }
                    }
                }
            }
            $order = $this->saveOrder($giftCard, null, $orderItems);
            $order->syncMeta($orderMeta);
            if ($giftCardCategory->requires_upload && isset($data['gift_card_proof_files'])) {
                $order->addMultipleMediaFromRequest(['gift_card_proof_files'])
                    ->each(function ($fileAdder) {
                        $fileAdder->toMediaCollection('gift_card_proofs');
                    });
            }


            $creditingSuccessful = 'You request for exchange of ' . $giftCard->title . ' was successful, once verified, you\'ll be credited';
            DB::commit();
            return $creditingSuccessful;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new Exception($exception);
        }
    }

    /**
     * @param $giftCardId
     * @return mixed
     */
    private function getGiftCard($giftCardId): mixed
    {
        return app(GiftCardRepository::class)->scopeQuery(function ($query) use ($giftCardId) {
            return $query->where(['id' => $giftCardId]);
        })->first();
    }
}

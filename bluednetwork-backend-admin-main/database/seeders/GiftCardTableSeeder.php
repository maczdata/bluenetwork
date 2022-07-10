<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  hailatutor
 * @file                           CurrencyTableSeeder.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     20/12/2020, 9:23 AM
 */

namespace Database\Seeders;

use App\Models\Common\Currency;
use App\Models\Common\CategoryOfGiftCard;
use App\Models\Common\GiftCard;
use App\Models\Common\GiftCardCurrency;
use App\Models\Common\GiftCardCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class GiftCardTableSeeder extends Seeder
{
    public function run()
    {


        $giftCards = [
            '1_800_flowers' => [
                'label' => '1-800 Flowers',
                'img' => resource_path('assets/img/card/800_flowers.png'),
            ],
            'play' => [
                'label' => 'Play',
                'img' => resource_path('assets/img/card/PvIM0Izvkn_1616853972.jpg')
            ],
            'walmart_moneycard' => [
                'label' => 'Walmart MoneyCard',
                'img' => resource_path('assets/img/card/y3uACfSG5F_1591952854.png')
            ],
            'starbuck' => [
                'label' => 'Starbuck',
                'img' => resource_path('assets/img/card/C5IIstX2OO_1581507217.png')
            ],
            'green_dot ' => [
                'label' => 'Green Dot ',
                'img' => resource_path('assets/img/card/afebsJlT4U_1581507012.png')
            ],
            'lowes' => [
                'label' => 'Lowe\'s',
                'img' => resource_path('assets/img/card/mTPFPrrj12_1581501368.png')
            ],
            'adidas' => [
                'label' => 'Adidas',
                'img' => resource_path('assets/img/card/hrY9mTgmJl_1581501144.png')
            ],
            'walmart_visa' => [
                'label' => 'Walmart Visa',
                'img' => resource_path('assets/img/card/hnGARLZLXc_1576139790.png')
            ],
            'perfect_money_voucher' => [
                'label' => 'Perfect Money Voucher',
                'img' => resource_path('assets/img/card/aazUJ4ASaj_1617013806.png')
            ],
            'visa' => [
                'label' => 'Visa',
                'img' => resource_path('assets/img/card/ZNd3PDPE9t_1565172800.png')
            ],
            'netflix' => [
                'label' => 'Netflix',
                'img' => resource_path('assets/img/card/gQDMmWO76M_1582543777.png')
            ],
            'itunes_25_special' => [
                'label' => 'ITunes 25 Special',
                'img' => resource_path('assets/img/card/kAxBwKuGjV_1561479019.png')
            ],
            'google_play_error_cards' => [
                'label' => 'Google Play Error Cards',
                'img' => resource_path('assets/img/card/1jRDIeeJyc_1583311615.png')
            ],
            'mess' => [
                'label' => 'MESS',
                'img' => resource_path('assets/img/card/d6WUWMzfZt_1606148327.png')
            ],
            'spar' => [
                'label' => 'Spar',
                'img' => resource_path('assets/img/card/hI8uSqVsDe_1557918092.png')
            ],
            'cashapp' => [
                'label' => 'CASHAPP',
                'img' => resource_path('assets/img/card/iscTFTOOhn_1606148694.png')
            ],
            'chime' => [
                'label' => 'CHIME',
                'img' => resource_path('assets/img/card/oaZXXhLite_1606148489.png')
            ],
            'steam_special' => [
                'label' => 'Steam Special',
                'img' => resource_path('assets/img/card/lwLbL1MtP3_1576662014.png')
            ],
            'hotels_com ' => [
                'label' => 'Hotels.com ',
                'img' => resource_path('assets/img/card/f0xfggJNoq_1565169638.png')
            ],
            'g2a' => [
                'label' => 'G2A ',
                'img' => resource_path('assets/img/card/Vrk9hfQMpe_1565171693.png')
            ],
            'happy' => [
                'label' => 'Happy',
                'img' => resource_path('assets/img/card/JQ643EulZP_1576662756.png')
            ],
            'home_depot' => [
                'label' => 'Home Depot',
                'img' => resource_path('assets/img/card/BpKOK2yM5a_1576662870.png')
            ],
            'playstation' => [
                'label' => 'Playstation',
                'img' => resource_path('assets/img/card/oemE4cfuOT_1571997367.png')
            ],
            'target_visa' => [
                'label' => 'Target Visa',
                'img' => resource_path('assets/img/card/uiSPlcujAo_1576139746.png')
            ],
            'offgamers' => [
                'label' => 'Offgamers',
                'img' => resource_path('assets/img/card/oXh9NFtYrX_1565172664.png')
            ],
            'razer_gold' => [
                'label' => 'Razer Gold',
                'img' => resource_path('assets/img/card/HlThlCytPk_1607609185.png')
            ],
            'gamestop' => [
                'label' => 'GameStop',
                'img' => resource_path('assets/img/card/RG9l2AUR8f_1560341183.png')
            ],
            'best_buy' => [
                'label' => 'Best Buy',
                'img' => resource_path('assets/img/card/OStw9h74BE_1557917288.png')
            ]
        ];

        foreach ($giftCards as $key => $giftCard) {
            $randomCurrencies = Currency::inRandomOrder()->take(rand(1, 8))->get();
            $randomFormOfGiftCards = CategoryOfGiftCard::inRandomOrder()->whereNull('parent_id')->take(rand(2, 4))->get();
            if (GiftCard::where('title', $giftCard['label'])->first()) {
                continue;
            }
            $createdGiftCard = GiftCard::create([
                'title' => $giftCard['label']
            ]);
            $createdGiftCard->addMedia($giftCard['img'])
                ->preservingOriginal()->toMediaCollection('giftcard_logos');
            /*$createdGiftCard->currencies()->saveMany([
                new GiftCardCurrency($randomCurrencies),
            ]);*/

            foreach ($randomCurrencies as $randomCurrency) {
                //$randomFormIds = $createdGiftCard->giftCardForms->pluck('id')->toArray();
                //$singleFormType = array_keys($randomFormIds)[rand(0, count($randomFormIds) - 1)];

                $giftCardCurrency = $createdGiftCard->currencies()->save(new GiftCardCurrency([
                    'currency_id' => $randomCurrency->id
                ]));

                //$giftCardCurrency->currencyRates()->createMany($currencyRates);

            }
            foreach ($randomFormOfGiftCards as $randomFormOfGiftCard) {
                $randomCurrencyIds = $createdGiftCard->currencies()->pluck('id')->toArray();
                $singleCurrency = $createdGiftCard->currencies()->inRandomOrder()->first()->id;
                    //array_keys($randomCurrencyIds)[rand(0, count($randomCurrencyIds) - 1)];
                $createdCardCategory = $createdGiftCard->categories()->save(new GiftCardCategory([
                    'category_of_gift_card_id' => $randomFormOfGiftCard->id
                ]));
                $currencyRates = [
                    [
                        'gift_card_id' => $createdGiftCard->id,
                        'gift_card_currency_id' => $singleCurrency,
                        'rate_type' => 'selling',
                        'face_value_from' => 100,
                        'face_value_to' => 100,
                        'rate_value' => 310
                    ],
                    [
                        'gift_card_id' => $createdGiftCard->id,
                        'gift_card_currency_id' => $singleCurrency,
                        'rate_type' => 'selling',
                        'face_value_from' => 10,
                        'face_value_to' => 23,
                        'rate_value' => 140
                    ],
                    [
                        'gift_card_id' => $createdGiftCard->id,
                        'gift_card_currency_id' => $singleCurrency,
                        'rate_type' => 'selling',
                        'face_value_from' => 210,
                        'face_value_to' => 1000,
                        'rate_value' => 160
                    ],
                    [
                        'gift_card_id' => $createdGiftCard->id,
                        'gift_card_currency_id' => $singleCurrency,
                        'rate_type' => 'selling',
                        'face_value_from' => 70,
                        'face_value_to' => 100,
                        'rate_value' => 200
                    ]
                ];
                $createdCardCategory->currencyRates()->createMany($currencyRates);
            }
            //$randomFormOfGiftCardCurrencies = $createdGiftCard->currencies()->inRandomOrder()->get();


        }
    }
}

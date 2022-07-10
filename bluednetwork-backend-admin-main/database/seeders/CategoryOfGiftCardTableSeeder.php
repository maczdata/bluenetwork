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

use App\Models\Common\CategoryOfGiftCard;
use App\Models\Common\GiftCard;
use Illuminate\Database\Seeder;

class CategoryOfGiftCardTableSeeder extends Seeder
{
    public function run()
    {
        $CategoryOfGiftCards = [
            'e-code' => [
                'label' => 'E code',
                'meta' => [
                    'requires_code' => true,
                    'requires_upload' => false
                ]
            ],
            'physical-card' => [
                'label' => 'Physical Card',
                'meta' => [
                    'requires_code' => false,
                    'requires_upload' => true
                ],
                'child_categories' => [
                    'cash-receipt' => [
                        'label' => 'Cash Receipt',
                    ],
                    'credit-receipt' => [
                        'label' => 'Credit Receipt'
                    ],
                    'debit-receipt' => [
                        'label' => 'Debit Receipt',
                    ],
                    'no-receipt' => [
                        'label' => 'No Receipt',
                    ],
                ]
            ]
        ];

        foreach ($CategoryOfGiftCards as $key => $mainCategory) {
            if (CategoryOfGiftCard::where('title', $mainCategory['label'])->first()) {
                continue;
            }
            $createdCategoryOfGiftCard = CategoryOfGiftCard::create([
                'title' => $mainCategory['label']
            ]);
            if (isset($mainCategory['img']) && is_null($mainCategory['img']) && file_exists($mainCategory['img'])) {
                $createdCategoryOfGiftCard->addMedia($mainCategory['img'])
                    ->preservingOriginal()->toMediaCollection('category_of_giftcards');
            }
            if (isset($mainCategory['meta']) && count($mainCategory['meta'])) {
                foreach ($mainCategory['meta'] as $orderMetaKey => $orderMetaValue) {
                    $createdCategoryOfGiftCard->setMeta($orderMetaKey, $orderMetaValue);
                }
            }
            if (array_key_exists('child_categories', $mainCategory)) {
                foreach ($mainCategory['child_categories'] as $childCategoryKey => $childCategory) {
                    $childCategorySaved = $createdCategoryOfGiftCard->children()->updateOrCreate([
                        'title' => $childCategory['label'],
                        'description' => ($childCategory['description']) ?? null,
                    ]);
                }
            }
        }
    }
}

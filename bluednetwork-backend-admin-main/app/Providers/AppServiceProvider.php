<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           AppServiceProvider.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     21/08/2021, 10:44 AM
 */

namespace App\Providers;

use App\Models\Common\AccountLevel;
use App\Models\Common\Bank;
use App\Models\Common\CategoryOfGiftCard;
use App\Models\Common\Currency;
use App\Models\Common\CurrencyExchangeRate;
use App\Models\Common\CustomField;
use App\Models\Common\CustomFieldResponse;
use App\Models\Common\Feature;
use App\Models\Common\Featurize;
use App\Models\Common\FeaturizeValue;
use App\Models\Common\GiftCard;
use App\Models\Common\GiftCardCategory;
use App\Models\Common\GiftCardCurrencyRate;
use App\Models\Common\ItemAddon;
use App\Models\Common\Referral;
use App\Models\Common\Service;
use App\Models\Common\ServiceType;
use App\Models\Common\ServiceVariant;
use App\Models\Common\UserDevice;
use App\Models\Common\Wallet;
use App\Models\Finance\PayoutRequest;
use App\Models\Finance\Transaction;
use App\Models\Offer;
use App\Models\Common\Role;
use App\Models\Sales\Order;
use App\Models\Sales\OrderCacDirector;
use App\Models\Sales\OrderItem;
use App\Models\Common\Setting;
use App\Models\Common\SettingType;
use App\Models\Users\UserOffer;
use App\Models\UserOfferField;
use App\Models\Users\User;
use App\Models\Users\UserCard;
use App\Services\DataType\Registry;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerDataTypeRegistry();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'user' => User::class,
            'wallet' => Wallet::class,
            'transaction' => Transaction::class,
            'bank' => Bank::class,
            'currency' => Currency::class,
            'referral' => Referral::class,
            'currency_exchange_rate' => CurrencyExchangeRate::class,
            'user_device' => UserDevice::class,
            'service' => Service::class,
            'service_variant' => ServiceVariant::class,
            'service_type' => ServiceType::class,
            'custom_field_response' => CustomFieldResponse::class,
            'custom_field' => CustomField::class,
            'payout_request' => PayoutRequest::class,
            'order' => Order::class,
            'order_item' => OrderItem::class,
            'gift_card' => GiftCard::class,
            'user_card' => UserCard::class,
            'feature' => Feature::class,
            'featurize' => Featurize::class,
            'featurize_value' => FeaturizeValue::class,
            'item_addon' => ItemAddon::class,
            'gift_card_currency_rate' => GiftCardCurrencyRate::class,
            'gift_card_category' => GiftCardCategory::class,
            'category_of_gift_card' => CategoryOfGiftCard::class,
            'order_cac_director' => OrderCacDirector::class,
            'offer' => Offer::class,
            'user_offer' => UserOffer::class,
            'user_offer_field' => UserOfferField::class,
            'account_level' => AccountLevel::class,
            'setting' => Setting::class,
            'setting_type' => SettingType::class,
            'role' => Role::class,
        ]);

        Schema::defaultStringLength(191);
    }


    /**
     * Add the DataType Registry to the service container.
     *
     * @return void
     */
    protected function registerDataTypeRegistry(): void
    {
        $this->app->singleton('DataRegistry', function () {
            $registry = new Registry();
            foreach (config('bds.datatypes') as $handler) {
                $registry->addHandler(new $handler());
            }

            return $registry;
        });
        $this->app->alias('DataRegistry', 'bds.datatype.registry');
    }
}

<?php

use App\Http\Controllers\Front\BlogController;
use App\Http\Controllers\Front\GiftCardController;
use App\Http\Controllers\Front\ServiceOrder\BillController;
use App\Http\Controllers\Front\ServiceOrder\BrandingController;
use App\Http\Controllers\Front\ServiceOrder\ExchangeController;
use App\Http\Controllers\Front\ServiceOrder\PreviewController;
use App\Http\Controllers\Front\ServiceOrder\PrintingController;
use App\Http\Controllers\Front\ServiceOrder\WebController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\Auth\LoginController;
use App\Http\Controllers\Front\Auth\AuthenticatedController;
use App\Http\Controllers\Front\Auth\SocialAuthController;
use App\Http\Controllers\Front\Auth\RetrievePasswordController;
use App\Http\Controllers\Front\Auth\RegisterController;
use App\Http\Controllers\Front\Auth\VerificationController;
use App\Http\Controllers\Front\Account\WalletController;
use App\Http\Controllers\Front\Account\PasswordController;
use App\Http\Controllers\Front\Account\BasicController;
use App\Http\Controllers\Front\Account\NotificationsController;
use App\Http\Controllers\Front\Account\BankingController;
use App\Http\Controllers\Front\MiscellaneousController;
use App\Http\Controllers\Front\ServiceController;
use App\Http\Controllers\Front\OfferController;
use App\Http\Controllers\Front\Account\ReferralController;
use App\Http\Controllers\Front\Account\TransactionController;
use App\Http\Controllers\Front\Account\UserProfileController;
use App\Http\Controllers\Front\Account\WithdrawalController;
use App\Http\Controllers\Front\CouponController;
use App\Http\Controllers\Front\ServiceOrder\ServiceOrderController;
use App\Http\Controllers\Front\ServiceTypeController;
use App\Http\Controllers\Front\ServiceVariantController;
use App\Http\Controllers\Front\Settings\SettingController;
use App\Http\Controllers\Front\Settings\SettingTypeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::post('login', [LoginController::class, 'processLogin']);
    Route::post('{provider}/social-login', [SocialAuthController::class, 'socialLogin'])->where('provider', 'facebook|google');
    Route::get('social-login/callback', [SocialAuthController::class, 'callBack'])->where('provider', 'linkedin|github|google');
    Route::get('social-login/callback/react', [SocialAuthController::class, 'callBackReact'])->where('provider', 'linkedin|github|google');
    //Route::get('refresh', [AuthenticatedController::class, 'refresh']);
});

Route::group(['prefix' => 'register', 'as' => 'register'], function () {
    Route::post('/', [RegisterController::class, 'processRegistration']);
});
Route::group(['prefix' => 'verification', 'as' => 'verification.'], function () {
    //verify account
    Route::post('verify', [VerificationController::class, 'processVerification']);
    //resend verification email
    Route::post('resend', [VerificationController::class, 'processResend']);

    Route::group(['middleware' => ['auth:frontend', 'guard-checker:frontend']], function () {
        // Route::post('bvn', [VerificationController::class, 'processBvnVerification']);
        Route::post('proof-of-address', [VerificationController::class, 'processProofOfAddress']);
        Route::post('proof-of-identity', [VerificationController::class, 'processIdentity']);
    });
});
Route::group(['prefix' => 'password', 'as' => 'password.'], function () {
    //verify account
    Route::post('reset_request', [RetrievePasswordController::class, 'processRequest']);
    //resend verification email
    Route::post('reset_save', [RetrievePasswordController::class, 'processReset'])->name('reset');
});

Route::group(['prefix' => 'common', 'as' => 'common.'], function () {
    Route::group(['prefix' => 'bank', 'as' => 'bank.'], function () {
        Route::get('all', [MiscellaneousController::class, 'fetchBanks'])->name('list');
        Route::post('verify_account', [
            MiscellaneousController::class,
            'verifyBankingData',
        ])->middleware(['jwt-auth:user', 'role:user'])->name('verify');
    });
});



Route::group(['prefix' => 'services', 'as' => 'services.'], function () {
    Route::get('/', [ServiceController::class, 'index'])->name('services');
});

Route::group(['prefix' => 'service-types', 'as' => 'service-types.'], function () {
    Route::get('/', [ServiceTypeController::class, 'index'])->name('all_service_features');
});

Route::group(['prefix' => 'service-variants', 'as' => 'service-variants.'], function () {
    Route::get('/', [ServiceVariantController::class, 'index'])->name('all_services_variants');
});

Route::group(['prefix' => 'service', 'as' => 'service.'], function () {
    Route::get('types', [ServiceController::class, 'serviceTypes'])->name('types');
    Route::get('all_service', [ServiceController::class, 'services'])->name('all_service');
    Route::get('features/{featureable_id}/{type}', [ServiceController::class, 'serviceFeatures'])->name('features');
    Route::group(['prefix' => 'single/{service_key}', 'as' => 'single.'], function () {
        Route::get('/', [ServiceController::class, 'serviceDetail'])->name('index');
        Route::get('variants', [ServiceController::class, 'serviceVariations'])->name('variations');
    });

    Route::group(['middleware' => ['jwt-auth:frontend', 'guard-checker:frontend'], 'prefix' => 'order', 'as' => 'payment.'], function () {
        Route::post('/create', [ServiceOrderController::class, 'store'])->name('create-order');
        Route::post('preview', [PreviewController::class, 'index'])->name('preview');
        Route::post('preview2', [PreviewController::class, 'preview'])->name('preview2');
        Route::group(['prefix' => '{service_key}'], function () {
            Route::post('bills', [BillController::class, 'index'])->name('bills');
            Route::post('web', [WebController::class, 'index'])->name('web');
        });
        Route::group(['prefix' => 'exchange', 'as' => 'exchange.'], function () {
            Route::post('giftcard', [ExchangeController::class, 'giftCardExchangeOrder'])->name('giftcard');
            Route::post('airtimetocash', [ExchangeController::class, 'airtimeToCash'])->name('airtimetocash');
        });

        Route::post('branding/{service_type_slug}', [BrandingController::class, 'index'])->name('branding');

        Route::group(['prefix' => 'printing', 'as' => 'printing.'], function () {
            Route::post('/', [PrintingController::class, 'makeOrder'])->name('index');
            //Route::post('preview', [PrintingController::class, 'preview'])->name('preview');
        });
    });
});

Route::group(['prefix' => 'gift_card', 'as' => 'gift_card.'], function () {
    Route::get('list', [GiftCardController::class, 'allGiftCards'])->name('all');
    Route::get('form_of', [GiftCardController::class, 'formOfGiftCards'])->name('forms_of');
    Route::group(['prefix' => 'single/{gift_card_id}', 'as' => 'single.'], function () {
        Route::get('/', [GiftCardController::class, 'serviceGiftCard'])->name('single');
        Route::get('{gift_card_currency_id}/{gift_card_category_id}/currency_rates', [GiftCardController::class, 'giftCurrencyRates'])->name('currency_rates');
    });
});

Route::post('/account/create-virtual-account', [UserProfileController::class, 'createVirtualAccount']);
Route::post('/account/virtual-account-transaction', [UserProfileController::class, 'VirtualAccountTransaction']);


Route::group(['middleware' => ['auth:frontend', 'guard-checker:frontend'], 'as' => 'account.', 'prefix' => 'account'], function () {
    // fetch user data and logout routes
    Route::get('me', [AuthenticatedController::class, 'me']);
    Route::get('refresh', [AuthenticatedController::class, 'refresh']);
    Route::delete('logout', [AuthenticatedController::class, 'logout']);

    Route::post('withdrawal-pin', [UserProfileController::class, 'updateWithdrawalPin']);

    Route::post('update_password', [PasswordController::class, 'processPassword'])->name('update_password');
    Route::post('update_basic', [BasicController::class, 'processBasic'])->name('update_basic');

    Route::post('update_banking_data', [BankingController::class, 'saveBankData'])->name('update_banking');
    Route::post('get_bank_data', [BankingController::class, 'getBankDetails'])->name('get_banking_data');
    Route::get('generate_bank_otp', [BankingController::class, 'generateBankOtp'])->name('generate_bank_otp');
  

    Route::group(['prefix' => 'notification', 'as' => 'notification.'], function () {
        Route::get('all', [NotificationsController::class, 'index'])->name('list');
        Route::post('mark', [NotificationsController::class, 'markNotificationsAsViewed'])->name('mark');
        Route::delete('delete', [NotificationsController::class, 'deleteNotifications'])->name('delete');
    });

    Route::group(['prefix' => 'wallet', 'as' => 'wallet.'], function () {
        Route::post('credit', [WalletController::class, 'credit']);
        Route::post('debit', [WithdrawalController::class, 'processRequest']);
    });

   
    Route::post('coupons/verify', [CouponController::class, 'verifyCoupon']);
    

    Route::group(['prefix' => 'transaction', 'as' => 'transaction.'], function () {
        Route::get('list', [TransactionController::class, 'index'])->name('list');
        Route::get('{transaction_id}/single', [TransactionController::class, 'singleTransaction'])->name('single');
    });

    Route::group(['prefix' => 'referred', 'as' => 'referred.'], function () {
        Route::get('list', [ReferralController::class, 'index'])->name('list');
    });
});

Route::group(['prefix' => 'blog', 'as' => 'blog.'], function () {
    Route::group(['prefix' => 'post', 'as' => 'post.'], function () {
        Route::get('list', [BlogController::class, 'list'])->name('list');
        Route::get('{slug}/single', [BlogController::class, 'singleBlogPost'])->name('single');
    });
});

Route::group(['prefix' => 'setting-types', 'as' => 'setting-types.'], function () {
    Route::get('/', [SettingTypeController::class, 'index'])->name('index');
});

Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
    Route::get('/', [SettingController::class, 'index'])->name('index');
});

Route::group(['prefix' => 'offers', 'as' => 'offers.'], function () {
    Route::get('list', [OfferController::class, 'list'])->name('offer-list');
    Route::post('pay', [OfferController::class, 'PayForOffer'])->name('offer-pay');
    Route::get('users/list', [OfferController::class, 'getUserOffers'])->name('user-offer-list');
    Route::post('users/fill/fields', [OfferController::class, 'fillUsersOfferField'])->name('offer-form-fill');
    Route::post('users/update/fields', [OfferController::class, 'UpdateUsersOfferField'])->name('offer-form-update');   
});

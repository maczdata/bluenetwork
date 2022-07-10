<?php

use App\Http\Controllers\Control\Orders\OrderListController;
use App\Http\Controllers\Control\Services\ManageServiceController;
use App\Http\Controllers\Control\Services\ServiceListController;
use App\Http\Controllers\Control\TransactionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Control\Auth\LoginController;
use App\Http\Controllers\Control\Users\UserListController;
use App\Http\Controllers\Control\Auth\AuthenticatedController;
use App\Http\Controllers\Control\Auth\VerificationController;
use App\Http\Controllers\Control\Auth\RetrievePasswordController;
use App\Http\Controllers\Control\Account\DashboardController;
use App\Http\Controllers\Control\Account\BasicController;
use App\Http\Controllers\Control\Account\ManageController;
use App\Http\Controllers\Control\AccountLevels\AccountLevelController;
use App\Http\Controllers\Control\Coupons\CouponController;
use App\Http\Controllers\Control\Offers\OfferController;
use App\Http\Controllers\Control\Payout\PayoutController;
use App\Http\Controllers\Control\CustomFields\CustomFieldController;
use App\Http\Controllers\Control\Featurizes\FeaturizeController;
use App\Http\Controllers\Control\GiftCards\GiftCardController;
use App\Http\Controllers\Control\Orders\ManageOrderController;
use App\Http\Controllers\Control\Roles\RoleController;
use App\Http\Controllers\Control\ServiceTypes\ManageServiceTypeController;
use App\Http\Controllers\Control\ServiceTypes\ServiceTypeListController;
use App\Http\Controllers\Control\Settings\SettingController;
use App\Http\Controllers\Control\Users\ManageUserController;
use App\Http\Livewire\Control\Dashboard;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['guest:dashboard']], function () {
    Route::group(['as' => 'login.', 'prefix' => 'login'], function () {
        Route::get('/', [LoginController::class, 'loginForm'])->defaults('_config', [
            'view' => 'control.auth.login',
        ])->name('form');
        Route::post('/', [LoginController::class, 'processLogin'])->name('process-login');
    });

    //verification
    Route::group(['prefix' => 'verification', 'as' => 'verification.'], function () {
        //verify account
        Route::get('verify', [VerificationController::class, 'verifyForm'])->defaults('_config', [
            'view' => 'control.auth.verification.verify',
        ])->name('verify');
        Route::post('verify', [VerificationController::class, 'processVerification'])->middleware(['throttle:user,6,8']);

        //resend verification email
        Route::get('resend', [VerificationController::class, 'showResendForm'])->defaults('_config', [
            'view' => 'control.auth.verification.resend_form',
        ])->name('resend');
        Route::post('resend', [VerificationController::class, 'processResend'])->middleware(['throttle:user,6,8']);
    });
    Route::group(['prefix' => 'password', 'as' => 'password.'], function () {
        //reset request
        Route::get('request', [RetrievePasswordController::class, 'requestForm'])->defaults('_config', [
            'view' => 'control.auth.password.request',
        ])->name('reset-request');

        Route::post('reset_request', [RetrievePasswordController::class, 'processRequest'])->name('process-reset-request');
        //complete reset
        Route::get('/reset-password/{token}', [RetrievePasswordController::class, 'resetForm'])->defaults('_config', [
            'view' => 'control.auth.password.reset',
        ])->name('reset');
        Route::post('process_reset', [RetrievePasswordController::class, 'processReset'])->defaults('_config', [
            'redirect' => 'control.login.form',
        ])->name('process-reset');
    });
});

Route::group(['middleware' => ['auth:dashboard', 'guard-checker:dashboard']], function () {
    Route::get('/', [DashboardController::class, 'index'])->defaults('_config', [
        'view' => 'control.dashboard',
    ])->name('dashboard');

    Route::group(['as' => 'account.', 'prefix' => 'account'], function () {
        Route::delete('logout', [AuthenticatedController::class, 'logout'])->name('logout');
        Route::get('manage', [ManageController::class, 'index'])->defaults('_config', [
            'view' => 'control.account.profile.wrapper',
        ])->name('manage-account');
        Route::group(['as' => 'manage.', 'prefix' => 'manage'], function () {
            Route::get('basic', [BasicController::class, 'index'])->defaults('_config', [
                'view' => 'control.account.basic',
            ])->name('basic');
            Route::post('basic', [BasicController::class, 'processBasic'])->defaults('_config', [
                'redirect' => 'portal.account.manage.basic',
            ]);
        });
    });
    Route::group(['as' => 'user.', 'prefix' => 'user'], function () {
        Route::get('/', [UserListController::class, 'index'])->defaults('_config', [
            'view' => 'control.users.list',
        ])->name('list')->middleware('can:view_user');

        Route::get('/manage', [ManageUserController::class, 'viewUserAccesslist'])->name('manage.level')->middleware('can:view_user');
        Route::post('/manage/level/{id}', [ManageUserController::class, 'updateUserlevel'])->name('update.level')->middleware('can:view_user');
        Route::get('/test', [UserListController::class, 'test'])->defaults('_config', [
            'view' => 'control.users.list',
        ])->name('test')->middleware('can:view_user');
        Route::post('/', [ManageUserController::class, 'create'])->name('create-user')->middleware('can:create_user');
        Route::post('/{user_id}/fund-wallet', [ManageUserController::class, 'fundWallet'])
            ->name('fund-wallet')->middleware('can:update_user');
        Route::post('/{user_id}/update-role-permissions', [
            ManageUserController::class, 'updateUserRoleAndPermissions',
        ])->name('update-role-permissions')->middleware('can:update_user');
        Route::put('{user_id}/edit', [ManageUserController::class, 'update'])
            ->name('update-user')->middleware('can:update_user');
        Route::get('{user_id}', [UserListController::class, 'single'])->defaults('_config', [
            'view' => 'control.users.view',
        ])->name('view')->middleware('can:view_user');
    });

    Route::group(['as' => 'order.', 'prefix' => 'order', 'middleware' => ['can:view_order']], function () {
        Route::get('/', [OrderListController::class, 'index'])->defaults('_config', [
            'view' => 'control.orders.list',
        ])->name('list');
        Route::post('/', [ManageOrderController::class, 'create'])->name('create')->middleware('can:create_order');
        Route::put('{order_id}', [ManageOrderController::class, 'update'])->name('update')->middleware('can:update_order');
        Route::get('{order_id}/single', [OrderListController::class, 'single'])->defaults('_config', [
            'view' => 'control.orders.view',
        ])->name('single');
    });

    Route::group(['as' => 'transaction.', 'prefix' => 'transaction', 'middleware' => ['can:view_transaction']], function () {
        Route::get('/', [TransactionController::class, 'index'])->defaults('_config', [
            'view' => 'control.transactions.list',
        ])->name('list');
        Route::get('{transaction_id}/single', [TransactionController::class, 'single'])->defaults('_config', [
            'view' => 'control.transactions.single',
        ])->name('single');
    });

    Route::group(['as' => 'service.', 'prefix' => 'service', 'middleware' => ['can:view_service']], function () {
        Route::get('/create', [ManageServiceController::class, 'create'])->defaults('_config', [
            'view' => 'control.services.create',
        ])->name('create')->middleware('can:create_service');

        Route::post('/', [ManageServiceController::class, 'store'])->name('store')->middleware('can:create_service');
        Route::put('/{service_id}', [ManageServiceController::class, 'update'])->name('update')->middleware('can:update_service');
        Route::get('/', [ServiceListController::class, 'index'])->defaults('_config', [
            'view' => 'control.services.list',
        ])->name('list');

        Route::get(
            '{service_id}/variants/{service_variant_id}/features/{feature_id}/edit',
            [
                ManageServiceController::class, 'editServiceVariantFeature',
            ]
        )->defaults('_config', [
            'view' => 'control.services.variants.features.edit',
        ])->name('edit-variant-feature')->middleware('can:update_service_variant');

        Route::get('{service_id}/variants/{service_variant_id}/edit', [ManageServiceController::class, 'editServiceVariant'])
            ->defaults('_config', [
                'view' => 'control.services.variants.edit',
            ])->name('edit-variant');

        Route::get('{service_id}/giftcards/{giftcard_id}/edit', [
            ManageServiceController::class, 'editGiftCard',
        ])->defaults('_config', [
            'view' => 'control.services.giftcards.edit',
        ])->name('edit-gift-card');

        Route::put('{service_id}/variants/{service_variant_id}', [ManageServiceController::class, 'updateServiceVariant'])
            ->name('update-variant')->middleware('can:update_service_variant');
        Route::get('{service_id}/features/{feature_id}/{type}/edit', [ManageServiceController::class, 'editServiceFeature'])
            ->defaults('_config', [
                'view' => 'control.services.features.edit',
            ])->name('edit-feature')->middleware('can:update_service');

        Route::get('{service_id}', [ManageServiceController::class, 'edit'])->defaults('_config', [
            'view' => 'control.services.edit',
        ])->name('manage')->middleware('can:update_service');
    });

    Route::group(['as' => 'features.', 'prefix' => 'features'], function () {
        Route::post('{feature_id}/metas', [FeaturizeController::class, 'createFeatureMeta'])->name('create-feature-meta');
        Route::post('{featurize_id}/values', [FeaturizeController::class, 'createFeatureValue'])->name('create-featurize-value');
        Route::post('{feature_id}/metas/{meta_id}', [
            FeaturizeController::class, 'updateFeatureMeta',
        ])->name('update-feature-meta');
        Route::post('{feature_id}/values/{value_id}', [
            FeaturizeController::class, 'upateFeatureValue',
        ])->name('update-feature-value');
    });

    Route::group([
        'as' => 'custom-fields.',
        'prefix' => 'custom-fields',
        'middleware' => ['can:view_custom_field'],
    ], function () {
        Route::put('{custom_field_id}', [CustomFieldController::class, 'update'])
            ->name('update')->middleware('can:update_custom_field');
    });

    Route::group(['as' => 'giftCards.', 'prefix' => 'giftCards'], function () {
        Route::post('/', [GiftCardController::class, 'store'])->name('store');
        Route::put('{giftcard_id}', [GiftCardController::class, 'update'])->name('update');
    });


    Route::group(['as' => 'service-type.', 'prefix' => 'service-type', 'middleware' => ['can:view_service_type']], function () {
        Route::get('/', [ServiceTypeListController::class, 'index'])->defaults('_config', [
            'view' => 'control.service-types.list',
        ])->name('list');
        Route::post('/', [ManageServiceTypeController::class, 'create'])->name('create')->middleware('can:create_service_type');
        Route::put('{service_type_id}/edit', [ManageServiceTypeController::class, 'update'])
            ->name('update')->middleware('can:update_service_type');
        Route::get('{service_type_id}/manage', [ManageServiceTypeController::class, 'edit'])->defaults('_config', [
            'view' => 'control.service-types.view',
        ])->name('view');
    });

    Route::group(['as' => 'settings.', 'prefix' => 'settings', 'middleware' => ['can:view_settings']], function () {
        Route::get('/', [SettingController::class, 'index'])->defaults('_config', [
            'view' => 'control.settings.index',
        ])->name('index');
        Route::post('/', [SettingController::class, 'store'])->name('store')->middleware('can:update_settings');

        Route::group(['as' => 'setting-types.', 'prefix' => 'setting-types'], function () {
            Route::post('/', [SettingController::class, 'storeSettingType'])->name('store')->middleware('can:update_settings');
        });
    });

    Route::group(['as' => 'roles.', 'prefix' => 'roles', 'middleware' => ['role:super_admin']], function () {
        Route::get('/', [RoleController::class, 'index'])->defaults('_config', [
            'view' => 'control.roles.list',
        ])->name('list');
        Route::get('/create', [RoleController::class, 'create'])->defaults('_config', [
            'view' => 'control.roles.create',
        ])->name('create');
        Route::get('{role_id}', [RoleController::class, 'edit'])->defaults('_config', [
            'view' => 'control.roles.edit',
        ])->name('view');
        Route::post('/', [RoleController::class, 'store'])->name('store');
        Route::put('{role_id}', [RoleController::class, 'update'])->name('update');
        Route::group(['as' => 'permissions.', 'prefix' => 'permissions'], function () {
            Route::post('/', [RoleController::class, 'createPermission'])->name('store');
        });
    });

    Route::group(['as' => 'account-levels.', 'prefix' => 'account-levels', 'middleware' => ['role:super_admin']], function () {
        Route::get('/', [AccountLevelController::class, 'index'])->defaults('_config', [
            'view' => 'control.account-levels.list',
        ])->name('list');
        Route::get('{role_id}', [AccountLevelController::class, 'edit'])->defaults('_config', [
            'view' => 'control.account-levels.edit',
        ])->name('view');
        Route::get('/create/view', [AccountLevelController::class, 'createAccess']);
        Route::post('/post/create', [AccountLevelController::class, 'postCreateAccess']);
        Route::get('/edit/{id}', [AccountLevelController::class, 'editAccess']);
        Route::post('/update/{id}', [AccountLevelController::class, 'updateAccess']);
        Route::post('/', [AccountLevelController::class, 'store'])->name('store');
        // Route::put('{account_level_id}', [AccountLevelController::class, 'update'])->name('update');
    });

    Route::group(['as' => 'coupons.', 'prefix' => 'coupons', 'middleware' => ['role:super_admin']], function () {
        Route::get('/', [CouponController::class, 'index'])->defaults('_config', [
            'view' => 'control.coupons.list',
        ])->name('index');
        Route::post('/', [CouponController::class, 'store'])->name('store');
        Route::put('{coupon_id}', [CouponController::class, 'update'])->name('update');
    });

    Route::group(['as' => 'offers.', 'prefix' => 'offers', 'middleware' => ['role:super_admin']], function () {
        Route::get('/', [OfferController::class, 'index'])->name('index');
        Route::post('create', [OfferController::class, 'store'])->name('create-offer');
        Route::post('update/{id}', [OfferController::class, 'update'])->name('update-offer');
        Route::get('view-more/{id}', [OfferController::class, 'viewMore'])->name('view-more');

        Route::post('create/service', [OfferController::class, 'storeService'])->name('create-service');
        Route::post('update/service/{id}', [OfferController::class, 'updateService'])->name('update-service');
        Route::get('delete/service/{id}', [OfferController::class, 'deleteService'])->name('delete-service');

        Route::post('create/field', [OfferController::class, 'storeField'])->name('create-fields');
        Route::post('update/field/{id}', [OfferController::class, 'updateField'])->name('update-field');
        Route::get('delete/field/{id}', [OfferController::class, 'deleteField'])->name('delete-field');

        Route::get('/view/users-offer', [OfferController::class, 'viewUsersOffers'])->name('view-users-offer');
        Route::get('mark/user-offer/start-processing/{id}', [OfferController::class, 'markProcessingUsersOffers'])->name('process-users-offer');
        Route::get('mark/user-offer/complete/{id}', [OfferController::class, 'markCompleteUsersOffers'])->name('complete-users-offer');
        Route::get('mark/user-offer/cancelled/{id}', [OfferController::class, 'markCancelUsersOffers'])->name('cancel-users-offer');
        Route::get('delete/user-offer/{id}', [OfferController::class, 'deleteUsersOffers'])->name('delete-users-offer');
    });

    Route::group(['as' => 'payouts.', 'prefix' => 'payouts', 'middleware' => ['role:super_admin']], function () {
        Route::get('/', [PayoutController::class, 'index'])->name('index');
        Route::get('/approve/{id}', [PayoutController::class, 'approve'])->name('approve');
        Route::get('/decline/{id}', [PayoutController::class, 'decline'])->name('decline');
        Route::get('/delete/{id}', [PayoutController::class, 'delete'])->name('delete');
    });

});

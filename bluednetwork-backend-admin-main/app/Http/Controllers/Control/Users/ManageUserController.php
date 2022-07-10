<?php

namespace App\Http\Controllers\Control\Users;

use App\Abstracts\Http\Controllers\ControlController;
use App\Helpers\Utils;
use App\Models\Common\AccountLevel;
use App\Models\Users\UserProfile;
use App\Notifications\WalletCredited;
use App\Repositories\Common\TransactionRepository;
use App\Repositories\Common\WalletRepository;
use App\Repositories\Users\UserRepository;
use App\Traits\Common\FormError;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class ManageUserController extends ControlController
{
    use FormError;

    public function __construct(
        protected UserRepository $userRepository,
        protected TransactionRepository $transactionRepository,
        protected WalletRepository $walletRepository
    ) {
        parent::__construct();
    }

    /**
     * @return Application|Factory|View
     */
    public function editForm(): View|Factory|Application
    {
        return view($this->_config['view']);
    }

    public function create(Request $request)
    {
        $validator = $this->validatePayload($request);
        if ($validator->fails()) {
            flash('Unable to create user')->error();
            return back()->withErrors($validator);
        }
        $formattedRequest = [
            'phone_number' => phone($request->phone_number, 'NG')->formatForMobileDialingInCountry('NG'),
            'intl_phone_number' => phone($request->phone_number, 'NG')->formatE164(),
        ];
        $request->merge($formattedRequest);
        DB::beginTransaction();
        try {
            $user = $this->userRepository->addUser($request);
            $user->assignRole($request->role);

            event(new Registered($user));
            flash('New user created successfully')->success();
            DB::commit();
            return redirect()->route('control.user.list');
        } catch (\Exception $exception) {
            DB::rollBack();
            logger()->error('User reg error : ' . $exception);
            flash('Unable to create user')->error();
            return back()->withErrors($validator);
        }
    }

    public function update(Request $request, string $userHashId)
    {
        $user = $this->userRepository->findByHashidOrFail($userHashId);

        if (is_null($user)) {
            flash('Invalid user')->error();
            return back();
        }
        $rules = [
            'username' => [
                'nullable',
                Rule::unique('users', 'username')->ignore($user->id, 'id'),
            ],
            'email' => [
                'nullable',
                'email',
                Rule::unique('users', 'email')->ignore($user->id, 'id'),
            ],
            'phone_number' => [
                'nullable',
                'phone:NG',
                Rule::unique('users', 'intl_phone_number')->ignore($user->id, 'id'),
            ],
            'password' => 'nullable|min:6',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            flash('Unable to update user')->error();
            return back()->withErrors($this->errorFormRequest($validator));
        }

        $formattedRequest = [
            'phone_number' => phone($request->phone_number, 'NG')->formatForMobileDialingInCountry('NG'),
            'intl_phone_number' => phone($request->phone_number, 'NG')->formatE164(),
        ];
        $request->merge($formattedRequest);
        try {
            $user = $this->userRepository->updateUser($user, $request);

            flash($user->username . ' account has been updated successfully')->success();
            return redirect()->route('control.user.list');
        } catch (\Exception $exception) {
            logger()->error('User update error : ' . $exception);
            
            flash('Unable to update user')->error();
            return back();
        }
    }

    public function fundWallet(Request $request, string $userHashId)
    {
        $user = $this->userRepository->findByHashidOrFail($userHashId);
        $amount = $request->amount;

        if (is_null($user)) {
            flash('Invalid user')->error();
            return back();
        }
        $rules = [
            'amount' => 'required',
            'mode_of_payment' => 'required|in:paystack,system,flutte',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            flash('Unable to fund user\'s wallet')->error();
            return back()->withErrors($validator);
        }
        $refNumber = $request->ref_number ?? "SYS-" . Utils::generateTransactionRef();

        if ($request->mode_of_payment === "system") {
            $respond = $this->creditUserViaSystem($user, $amount, $refNumber);
        } else {
            $respond = $this->creditUserViaPaymentGatway($user, $amount, $refNumber);
        }
        if (!$respond->status) {
            flash($respond->message)->error();
        } else {
            flash($respond->message)->success();
        }
        return redirect()->back();
    }

    public function updateUserRoleAndPermissions(Request $request, string $userHashId)
    {
        $user = $this->userRepository->findByHashidOrFail($userHashId);
        $role = $request->role;
        $permissions = $request->permissions;

        try {
            $roles = Role::get()->pluck('name')->toArray();
            $currentRole = $user?->roles?->pluck('name')?->first();
            if ($currentRole && in_array($currentRole, $roles)) {
                $user->removeRole($currentRole);
            }
            $user->assignRole($role);

            $user->permissions()->detach();
            $user->givePermissionTo($permissions);

            flash($user->username . ' role/permissions has been updated successfully')->success();
            return redirect()->back();
        } catch (\Exception $exception) {
            logger()->error('User update role and permissions error : ' . $exception);
            flash('Unable to update user role')->error();
            return back();
        }
    }

    private function creditUserViaPaymentGatway()
    {
        return $this->userResponseDto('Unknown payment gateway', false, 403);
    }

    private function creditUserViaSystem($user, $amount, $refNumber)
    {
        $transaction = $this->transactionRepository->scopeQuery(function ($query) use ($refNumber) {
            return $query->whereHasMeta('sys_transaction_id')->whereMeta('sys_transaction_id', $refNumber);
        })->count();
        if ($transaction != false) {
            return $this->userResponseDto('Fraudulent activity detected', false, 403);
        }
        try {
            $this->walletRepository->deposit($user, $amount, ['sys_transaction_id' => $refNumber]);
            $user->notify(new WalletCredited($amount, $user->id, 'system'));
            return $this->userResponseDto('Your wallet has been credited', true, 200);
        } catch (\Exception $exception) {
            logger()->error('wallet crediting error : ' . $exception);
            return $this->userResponseDto(
                'Your wallet could not be credited, please try again. if issue persist, contact us',
                true,
                500
            );
        }
    }

    private function validatePayload(Request $request)
    {
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'phone_number' => 'required|phone:NG|unique:users,intl_phone_number',
            'password' => 'required|confirmed|min:6',
        ];

        $rulesMessage = [
            'password.required' => 'Password is required',
            'password.min' => 'Password has to e a minimum of 6 characters',
        ];

        $rulesMessage['last_name.required'] = 'Last name required';
        $rulesMessage['username.required'] = 'Username is required';
        $rulesMessage['username.unique'] = 'Username is already taken';
        $rulesMessage['email.email'] = 'Email address is invalid';
        $rulesMessage['email.unique'] = 'Email address not available';
        $rulesMessage['phone_number.required'] = 'Phone number is required';
        $rulesMessage['phone_number.unique'] = 'Phone number already in use';

        return Validator::make($request->all(), $rules);
    }

    public function viewUserAccesslist()
    {
        $users = $this->userRepository->orderBy('id', 'DESC')->get();
        $accountLevel = AccountLevel::get();
        return view('control.account-levels.view-user-accounts')->with(compact('users', 'accountLevel'));
    }

    public function updateUserlevel(request $request, $id)
    {
        try {
            Userprofile::where('user_id', $id)->update([
                'account_level_id' => $request->level_id,
            ]);

            flash('Users Level has been updated')->success();
            return back();
        } catch (\Exception $exception) {
            logger()->error('User update level : ' . $exception);
            flash('Unable to update user level')->error();
            return back();
        }
    }

    private function userResponseDto(
        string $message,
        bool $status,
        int $statusCode,
        array $data = []
    ) {
        return (object)[
            'message' => $message,
            'status' => $status,
            'data' => $data,
            'statusCode' => $statusCode,
        ];
    }
}

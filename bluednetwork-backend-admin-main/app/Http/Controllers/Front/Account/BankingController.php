<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           BankingController.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Http\Controllers\Front\Account;

use App\Abstracts\Http\Controllers\FrontController;
use App\Services\Virtual\VirtualAccountService;
use App\Models\Users\User;
use App\Repositories\Common\BankRepository;
use App\Repositories\Payout\PayoutRequestRepository;
use App\Repositories\Users\UserRepository;
use App\Traits\Common\Paystack;
use App\Services\Account\BankOtpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Prettus\Repository\Exceptions\RepositoryException;

/**
 * Class BankingController
 *
 * @package App\Http\Controllers\Front\Account
 */
class BankingController extends FrontController
{
    use Paystack;

    /**
     * BankingController constructor.
     *
     * @param BankRepository $bankRepository
     * @param UserRepository $userRepository
     * @param PayoutRequestRepository $payoutRequestRepository
     */
    public function __construct(
        protected BankRepository $bankRepository,
        protected UserRepository $userRepository,
        protected PayoutRequestRepository $payoutRequestRepository
    ) {
        parent::__construct();
    }

    /**
     * @param Request $request
     */
    public function index(Request $request)
    {
        $user = $request->user('frontend');
        $withdrawalSetupsToModify = $user->withdrawal_setups()->get();
        $withdrawalSetups = (object)[];
        if ($withdrawalSetupsToModify) {
            foreach ($withdrawalSetupsToModify as $withdrawalSetupToModify) {
                $setupMetaBankId = $withdrawalSetupToModify->getMeta('bank_id');
                $setupMetaAccountName = $withdrawalSetupToModify->getMeta('account_name');
                $setupMetaAccountNumber = $withdrawalSetupToModify->getMeta('account_number');
                $setupBank = $this->bankRepository->where('id', $setupMetaBankId)->first();
                $withdrawalSetups[] = [
                    'id' => $withdrawalSetups->id,
                    //$withdrawalSetupToModify->hashid(),
                    'is_default' => $withdrawalSetupToModify->is_default,
                    'bank_name' => $setupBank->name,
                    'account_name' => $setupMetaAccountName,
                    'account_number' => $setupMetaAccountNumber,
                ];
            }
        }
    }

    /**
     * @OA\Post(
     *      path="/account/update_banking_data",
     *       operationId="user_bank",
     *       tags={"Account"},
     *       summary="bank update",
     *       security={{"bearerAuth":{}}},
     *       description="update users banking data",
     *       @OA\Parameter(
     *          name="otp",
     *          description="Generated Otp",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *    @OA\Parameter(
     *          name="account_name",
     *          description="account name",
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *    @OA\Parameter(
     *          name="account_number",
     *          description="account number",
     *          in="query",
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *    @OA\Parameter(
     *          name="bank_id",
     *          description="bank_id",
     *          in="query",
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *   @OA\Parameter(
     *          name="token",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *           type="string"
     *          )
     *       ),
     *     @OA\Response(response=200,description="banking detail successfully updated"),
     *     @OA\Response(response=403,description="banking data already in use"),
     *     @OA\Response(response=500, description="Bad request"),
     *     )
     *
     * @param Request $request
     * @return array|JsonResponse
     * @throws RepositoryException
     */
    public function saveBankData(Request $request)
    {
        $user = Auth::guard('frontend')->user();

        $validator = Validator::make($request->all(), [
            'account_name' => ['required', 'min:3'],
            'account_number' => ['required', 'string', 'max:255'],
            'bank_id' => ['required', 'exists:banks,id'],
            // 'bvn' => ['required'],
           // 'password' => ["required", "password:user"],
        ], [
            'account_name.required' => 'Account name is required',
            'account_name.min' => 'Account name must be a minimum of 3 characters',
            'account_number.required' => 'Account number is required',
            'account_number.unique' => 'Account number already in use',
            'account_number.max' => 'Account number cannot exceed 15 characters',
            'bank_id.required' => 'Bank is required',
            'bank_id.exists' => 'Selected bank does not exist',
            // 'bvn.required' => 'BVN is required',
        ]);

        if ($validator->fails()) {
            return response()->json($this->failedValidation($validator), 422);
        }
        
        DB::beginTransaction();
        try {
            $bankOtpService = new BankOtpService();
            $verify = $bankOtpService->verifyOtp($user, $request->otp);

            if($verify == false)
            {
                return api()->status(400)->message('Invalid Otp')->respond();
            }

            $expired = $bankOtpService->expired($user, $request->otp);
           
            if($expired == true)
            {
                return api()->status(400)->message('Otp has expired, please generate a new one')->respond();
            }

            $bankCode = $this->bankRepository->find($request->bank_id)->code;
            $bank = $this->bankRepository->find($request->bank_id);
            $bankId = $request->bank_id;
            $accountName = $request->account_name;
            $accountNumber = $request->account_number;
            $isDefault = $request->is_default ?? true;
            $checkExistingSetups = $user->withdrawal_setups;
            $proceed = true;
            if ($checkExistingSetups) {
                foreach ($checkExistingSetups as $checkExistingSetup) {
                    $setupMetaBankId = $checkExistingSetup->getMeta('bank_id');
                    $setupMetaAccountName = $checkExistingSetup->getMeta('account_name');
                    $setupMetaAccountNumber = $checkExistingSetup->getMeta('account_number');
                    if (($setupMetaBankId == $bankId) && ($setupMetaAccountNumber == $accountNumber)) {
                        $proceed = false;
                        break;
                    }
                }
            }
            $uniqueAccount = User::whereMeta('account_number', '=', $accountName)->where('id', '!=',
                $user->id)->count();
            if ($uniqueAccount > 0) {
                return api()->status(403)->message('Account already in use')->respond();
            }

            $recipient = $user->withdrawal_setups;

            if ($recipient) {
                // $accountVerification = $this->verifyRecipientAccount([
                //     'account_number' => $accountNumber,
                //     'account_bank' => $bank->name,
                //     'bank_code' => $bankCode,
                // ]);
                // if (!$accountVerification) {
                //     return response()->json([
                //         'success' => false,
                //         'message' => 'Banking data could not be verified',
                //     ]);
                // }

                // $recipientData = $this->createRecipient([
                //     "type" => "nuban",
                //     'bank_code' => $bankCode,
                //     'account_bank' => $bank->name,
                //     'account_number' => $accountNumber,
                //     'beneficiary_name' => $accountName,
                // ]);

                // if (!$recipientData) {
                //     return response()->json([
                //         'success' => false,
                //         'message' => 'Banking data could not be registered',
                //     ]);
                // }
            }

            $existingWithdrawalSetup = $user->withdrawal_setups()
                ->where('account_number', '!=', $accountNumber)
                ->where('bank_id', $bankId)
                ->enabled()->first();

            $existingWithdrawalSetup?->update('enabled', false);

            $withdrawalSetup = $user->withdrawal_setups()->updateOrCreate([
                'provider' => 'paystack',
                'provider_value' => $user->id,
                'bank_id' => $bankId,
                'account_name' => $accountName,
                'account_number' => $accountNumber,
                'recipient' => 123,
               // 'recipient' => $recipientData->recipient_code,             //RCP_o7ff3nt6bd6gdlt
            ], [
                'is_default' => $isDefault,
            ]);

            if ($isDefault == 1) {
                $user->withdrawal_setups()->where('id', '!=', $withdrawalSetup->id)->update(['is_default' => 0]);
            }

            DB::commit();

            return api()->status(200)->message('Banking detail updated')->respond();
        } catch (\Exception $exception) {
            DB::rollBack();
            logger()->error('Bank Account update error : ' . $exception);

            return api()->status(500)->message('Banking detail could not be updated: ' . $exception->getMessage())->respond();
        }
    }

    /**
     * @OA\Post(
     *      path="/account/get_bank_data",
     *       operationId="get_bank_data",
     *       tags={"Account"},
     *       summary="get bank data",
     *       security={{"bearerAuth":{}}},
     *       description="getting bank account name based on monnify api",
     * 
     *       @OA\Parameter(
     *          name="account_number",
     *          description="account number",
     *          in="query",
     *          @OA\Schema(
     *              type="integer",
     *          )
     *       ),
     *       @OA\Parameter(
     *          name="bank_id",
     *          description="bank_id",
     *          in="query",
     *          @OA\Schema(
     *              type="integer",
     *          )
     *       ),
     *       @OA\Parameter(
     *          name="token",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *           type="string"
     *          )
     *       ),
     *     @OA\Response(response=200,description="banking detail successfully updated"),
     *     @OA\Response(response=403,description="banking data already in use"),
     *     @OA\Response(response=500, description="Bad request"),
     *     )
     *
     */
    public function getBankDetails(Request $request)
    {
        $user = Auth::guard('frontend')->user();

        $validator = Validator::make($request->all(), [
            'account_number' => ['required', 'string', 'max:255'],
            'bank_id' => ['required', 'exists:banks,id'],
            // 'bvn' => ['required'],
           // 'password' => ["required", "password:user"],
        ], [
           
            'account_number.required' => 'Account number is required',
            'account_number.unique' => 'Account number already in use',
            'account_number.max' => 'Account number cannot exceed 15 characters',
            'bank_id.required' => 'Bank is required',
            'bank_id.exists' => 'Selected bank does not exist',
            // 'bvn.required' => 'BVN is required',
        ]);

        if ($validator->fails()) {
            return response()->json($this->failedValidation($validator), 422);
        }

        try{
            $bank = $this->bankRepository->find($request->bank_id);
            $Virtual  = new VirtualAccountService();
            $response = $Virtual->getBankAccountData($request->all(), $bank);
            $monify_response = json_decode($response);
           
            if(optional($monify_response)->responseMessage == "success"){
                $data_response = $monify_response->responseBody;
                    $data = [
                        'accountNumber' => $data_response->accountNumber,
                        'accountName' => $data_response->accountName,
                        'bankCode' => $data_response->bankCode
                    ];
                    
                    return api()->status(200)->data($data)->respond();
            }else{
                    logger()->error('Unable to get bank details : ' . $monify_response->responseMessage);
                    return api()->status(400)->message("Unable to  get bank details : $monify_response->responseMessage")->respond();
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            logger()->error('Bank details error: ' . $exception);
            return api()->status(500)->message('Bank Details error '.$exception->getMessage())->respond();
        }
    }

      /**
     * @OA\Get(
     ** path="/account/generate_bank_otp",
     *   tags={"Account"},
     *   summary="generate bank otp to update bank details",
     *   operationId="generate_bank_otp",
     *
     *   @OA\Parameter(
     *          name="token",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *           type="string"
     *          )
     *    ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="Not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    public function generateBankOtp(Request $request)
    {
        try {
            $user = Auth::guard('frontend')->user();
            $bankOtpService = new BankOtpService();

            $otp = $bankOtpService->generateOtp($user);
            $bankOtpService->sendOtp($user, $otp);

            return api()->status(200)->message('Bank otp generated')->respond();
        } catch (\Exception $ex) {
            logger()->error('Error generating bank otp : ' . $ex);
            return api()->status(500)->message('Error generating bank otp: '. $ex->getMessage())->respond();
        }
    }
}

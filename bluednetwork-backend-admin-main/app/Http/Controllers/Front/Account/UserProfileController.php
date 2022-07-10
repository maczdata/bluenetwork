<?php

namespace App\Http\Controllers\Front\Account;


use App\Services\Virtual\VirtualAccountService;
use App\Models\Users\User;
use App\Models\Common\Wallet;
use App\Models\Finance\Transaction;
use App\Transformers\Users\UserTransformer;

use App\Repositories\Common\WalletRepository;
use App\Abstracts\Http\Controllers\FrontController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserProfileController extends FrontController
{
   
    public function __construct(
        protected WalletRepository $walletRepository
    ) {
        parent::__construct();
    }

    /**
     * @OA\Post(
     *      path="/account/withdrawal-pin",
     *      operationId="withdrawal-pin",
     *      tags={"Account"},
     *      summary="Withdrawal pin",
     *      description="Update withdrawal pin",
     *      security={{"bearerAuth":{}}},
     *       @OA\Parameter(
     *          name="withdrawal_pin",
     *          description="Withdrawal Pin",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="token",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *           type="string"
     *          )
     *       ),
     *     @OA\Response(response=200,description="withdrawal pin updated successfully"),
     *     @OA\Response(response=400, description="Bad request"),
     *     )
     *
     * credit wallet
     *
     * @param Request $request
     * @return JsonResponse
     * @throws RepositoryException
     */
    public function updateWithdrawalPin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'withdrawal_pin' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json($this->failedValidation($validator), 422);
        }

        $user = auth('frontend')->user();
        $withdrawal_pin = $request->withdrawal_pin;

        $user->profile->withdrawal_pin = $withdrawal_pin;
        $user->profile->save();
        return api()->status(200)->message('Withdrawal pin successfully added')->respond();
    }

    /**
     * @OA\Post(
     ** path="/account/create-virtual-account",
     *   tags={"Virtual Account"},
     *   summary="create virtual account",
     *   operationId="create-virtual-account",
     *
     * @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     * 
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
    public function createVirtualAccount(Request $request)
    {
        ini_set('max_execution_time', 180);
        $validator = Validator::make($request->all(), [
            'email' => "required|string|exists:users,email",
        ]);
        DB::beginTransaction();
        try{
            $user = User::where(['email' => $request->email])->first();

            if(is_null($user))
            {
                return api()->status(400)->message('No account found')->respond();
            }

            if($user->virtual != null)
            {
                return api()->status(400)->message('virtual account already exist')->respond();
            }
            
            $Virtual  = new VirtualAccountService();
            $response = $Virtual->createVirtualAccount($request);
            $monify_response = json_decode($response);
        
            if(optional($monify_response)->responseMessage == "success"){
                    $pay_data = ['user_id'=>   $user->id,
                                 "order_ref"=> $monify_response->responseBody->accountReference,
                    ];
                    $Virtual->saveAccount($pay_data); 
                    DB::commit();
                    return api()->status(200)->data(fractal($user, UserTransformer::class)->toArray())->message($monify_response->responseMessage)->respond();
            }else{
                    logger()->error('virtual account creation error: ' . $monify_response->responseMessage);
                    return api()->status(400)->message("Unable to create a Virtual Account for this User: $monify_response->responseMessage")->respond();
            }

        } catch (\Exception $exception) {
            DB::rollBack();
            logger()->error('virtual account creation error: ' . $exception);
            return api()->status(500)->message('There was an error making payout '.$exception->getMessage())->respond();
        }
      
    }

    public function VirtualAccountTransaction(Request $request)
    {
        DB::beginTransaction();
        try{
            $email = $request->input('eventData.customer.email');
            $user = User::where('email', $request->input('eventData.customer.email'))->first();
            $wallet = Wallet::where('owner_id', $user->id )->first();
            if($user != null)
            {
                if($request->input('eventType') == "SUCCESSFUL_TRANSACTION")
                {
                    $transaction_exist = Transaction::where('reference',  $request->input('eventData.transactionReference'))->first();
                    if($transaction_exist == null)
                    {
                        $this->walletRepository->deposit($user, $request->input('eventData.amountPaid') , [   
                                                                            'transactionable_type' => 'wallet',
                                                                            'transactionable_id' => optional($wallet)->id,
                                                                            'paid_at'=>  $request->input('eventData.paidOn'),
                                                                            'payment_method' => 'Monnify',
                                                                            'reference' =>  $request->input('eventData.transactionReference')
                                                                        ]);
                        DB::commit();
                        return api()->status(200)->message('Successfully credited the account')->respond();
                    }
                }else{
                    DB::rollBack();
                    logger()->error('monnify webhook error: transaction already exist');
                    return api()->status(500)->message('monnify webhook error: transaction already exist')->respond();
                }
            }else{
                DB::rollBack();
                logger()->error('monnify webhook error: User email ' .$email. ' not found');
                return api()->status(500)->message('monnify webhook error: User email ' .$email. ' not found')->respond();
            }

        } catch (\Exception $exception) {
            DB::rollBack();
            logger()->error('monnify  transfer complete webhook error: ' . $exception);
            return api()->status(500)->message('monnify transfer complete webhook error '.$exception->getMessage())->respond();
        }
    }


}

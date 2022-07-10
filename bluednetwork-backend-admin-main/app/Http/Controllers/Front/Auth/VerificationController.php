<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           VerificationController.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Http\Controllers\Front\Auth;

use App\Abstracts\Http\Controllers\FrontController;
use App\Models\Users\User;
use App\Models\Users\UserProfile;
use App\Transformers\Users\UserTransformer;
use App\Repositories\Common\TokenRepository;
use App\Repositories\Users\UserRepository;
use App\Traits\Common\Paystack;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

/**
 * Class VerificationController
 * @package App\Http\Controllers\Front\Auth
 */
class VerificationController extends FrontController
{
    use Paystack;

    /**
     * @var TokenRepository
     */
    protected TokenRepository $tokenRepository;

    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * RegisterController constructor.
     * @param UserRepository $userRepository
     * @param TokenRepository $tokenRepository
     */
    public function __construct(UserRepository $userRepository, TokenRepository $tokenRepository)
    {
        parent::__construct();

        $this->tokenRepository = $tokenRepository;

        $this->userRepository = $userRepository;
    }

    /**
     * @OA\Post(
     *      path="/verification/verify",
     *      operationId="user-verification",
     *      tags={"Verification"},
     *      summary="Verify user email or phone number",
     * 
     *      @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         required=false,
     *         description="required only when verifying phone number",
     *         @OA\Schema(
     *           type="string"
     *         )
     *      ),
     *      @OA\Parameter(
     *          name="source_value",
     *          description="the value for the verification source which can be either an eamil address or a phone number",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *       @OA\Parameter(
     *          name="verification_token",
     *          description="Verification token",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="verification_source",
     *          description="verification source is required, it can be either email or phone_number",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Response(response=200,description="token verfied"),
     *     @OA\Response(response=202,description="Token expired"),
     *     @OA\Response(response=400,description="Token is invalid"),
     *     @OA\Response(response=422,description="Form/data validation error"),
     *       @OA\Response(response=500, description="Bad request"),
     *     )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function processVerification(Request $request): JsonResponse
    {
        
        $verificationToken = $request->verification_token;
        $verificationSource = $request->verification_source;
        $payload = [
            'type' => 1,
            'source' => $verificationSource,
            'token' => $verificationToken,
        ];
       
        $typeToLabel = (($verificationSource == 'email') ? 'Email' : (($verificationSource == 'phone_number') ? 'Phone number' : ''));
        //default verify any email or phone
        // if(isset($request->source_value)){

        //     $user = $this->userRepository->where([$verificationSource => $request->source_value])->first();
        //     if(is_null($user)){
        //         return api()->status(404)->message($typeToLabel . ' is not found')->respond();
        //     }

        //     if ($verificationSource == 'email') {
        //         $user->markEmailAsVerified();
        //     }
        //     if ($verificationSource == 'phone_number') {
        //         $user->markPhoneAsVerified();
        //     }

        //     return api()->status(200)->message( $typeToLabel. ' has been verified')->respond();
        // }
       

        $token = $this->tokenRepository->where($payload)->whereHasMorph('tokenable', User::class)->first();
        
        if (is_null($token)) {
            return api()->status(400)->message($typeToLabel . ' verification token is invalid')->respond();
        }

        // if ($token->tokenExpired()) {
        //     // $token->delete();
        //     return api()->status(202)->message($typeToLabel . ' verification token has expired')->respond();
        // }


        if ($verificationSource == 'email' && !is_null($token->tokenable->email_verified_at)) {
            return api()->status(202)->message($typeToLabel . ' has been verified')->respond();
        }
        
        if ($verificationSource == 'phone_number') {
            if(!isset($request->user_id))
            {
                return api()->status(400)->message('Please provide a user id')->respond();
            }
            $user = User::where([
                                    'id'=> $request->user_id,
                                    'phone_number' => $request->source_value
                                ])->first();

            if($user == null)
            {
                return api()->status(400)->message('No such user found')->respond();
            }

            if(optional($user)->phone_verified_at != null)
            {
                return api()->status(202)->message($typeToLabel . ' has been verified')->respond();
            }
        }
    
      
      
        DB::beginTransaction();
        try {
            $data = [];

            $token = $this->tokenRepository->validateToken(collect($data), $token);
            
            $user = $this->userRepository->where([$verificationSource => $request->source_value])->first();
         
            if(is_null($user)){
                 return api()->status(404)->message($typeToLabel . ' is not found')->respond();
            }

            if ($verificationSource == 'email') {
                $token->tokenable->markEmailAsVerified();
                if(optional(optional($user)->profile)->acount_level_id < 3)
                {
                    UserProfile::where(['user_id' => $user->id])->update([
                        'account_level_id' => 2,
                    ]);
                }
            }

            if ($verificationSource == 'phone_number') {
               
                User::where([
                    'id'=> $request->user_id,
                    'phone_number' => $request->source_value
                ])->update([
                    'phone_verified_at' => now()
                ]);
                if(optional(optional($user)->profile)->acount_level_id < 3)
                {
                    UserProfile::where(['user_id' => $user->id])->update([
                        'account_level_id' => 2,
                    ]);
                }
            }
            
            DB::commit();
            //auth('frontend')->login($user);
            return api()->status(200)->message($typeToLabel . ' has been verified')->respond();
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error("User Verification error : " . $e);
            return api()->status(500)->message('There was an error verifying ' . $typeToLabel . ', please try again')->respond();
        }
    }


    /**
     * Resend the user verification notification.
     * @OA\Post(
     *      path="/verification/resend",
     *      operationId="user-verification-resend",
     *      tags={"Verification"},
     *      summary="Resend user email or phone number verification notification",
     *      @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         required=true,
     *         description="user_id from the user object",
     *         @OA\Schema(
     *           type="string"
     *         )
     *      ),
     *       @OA\Parameter(
     *          name="source",
     *          description="Verification source, which can be email or phone_number",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="source_value",
     *          description="the value for the verification source which can be either an eamil address or a phone number",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Response(response=200,description="token sent"),
     *     @OA\Response(response=202,description="Token expired"),
     *     @OA\Response(response=400,description="Token already verified"),
     *     @OA\Response(response=422,description="Form/data validation error"),
     *       @OA\Response(response=500, description="Bad request"),
     *     )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function processResend(Request $request)
    {
        $otp = null;
        $source = $request->source;
        
        $request->validate(
            [
                'source' => 'required|in:email,phone_number',
                'source_value' => 'required',
                'user_id' => "required|exists:users,id"
            ],
            [
                'source.required' => 'Source is required',
                'source_value.required' => 'Source value is required',
            ]
        );
     
        $sourceTolabel = (($source == 'email') ? 'Email' : (($source == 'phone_number') ? 'Phone number' : ''));
        $user = $this->userRepository->where(['id'=> $request->user_id, $source => $request->source_value])->first();
       
        if (is_null($user)) {
            return api()->status(404)->message('User not found')->respond();
        }
        if ($request->source == 'email' && $user->hasVerifiedEmail()) {
            return api()->status(400)->message('Email address already verified')->respond();
        }
        if ($request->source == 'phone_number' && $user->hasVerifiedPhone()) {
            return api()->status(400)->message('Phone number already verified')->respond();
        }
        
        DB::beginTransaction();
        try {
            $user->tokens()->where('type', 1)->delete();
           
            $this->tokenRepository->sendToken($user, 1, $request->source, ($source == 'phone_number') ? generateOtp(5) : null);

            DB::commit();
            return api()->status(200)->message($sourceTolabel . ' verification is being sent')->respond();
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error("User verification resend error : " . $e);
            return api()->status(500)->message('There was an error sending ' . $sourceTolabel . ', please try again')->respond();
        }
    }


      /**
     * Proof of Address Verification.
     * @OA\Post(
     *      path="/verification/proof-of-address",
     *      operationId="user-verification-address",
     *      tags={"Verification"},
     *      summary="Upload a proof of address file for verification",
     *      @OA\RequestBody(
     *         required=false,
     *         @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *             @OA\Property(
     *                property="proof_of_address",
     *                description="Acceptable formats: jpeg,png,jpg,svg,pdf,doc, max size of 2048",
     *                type="file",
     *                format="binary"
     *             ),
     *           ),
     *         ),
     *       ),
     *      @OA\Parameter(
     *          name="token",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *           type="string"
     *          )
     *       ),
     *       @OA\Parameter(
     *          name="address",
     *          description="Address",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     * 
     *       @OA\Parameter(
     *          name="proof_of_address_type",
     *          description="Proof of Address Type e.g LATEST ELECTRICITY BILL, LAST 3 MONTHS BANK STATEMENT",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Response(response=200,description="Proof of Address uploaded successfully"),
     *     @OA\Response(response=202,description="Proof of Address has been verified before"),
     *     @OA\Response(response=400,description="Proof of Address has been verified before"),
     *     @OA\Response(response=422,description="Form/data validation error"),
     *       @OA\Response(response=500, description="Bad request"),
     *     )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function processProofOfAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address' => ['required', 'string'],
            'proof_of_address_type' => ['required', 'string'],
            'proof_of_address' => ['required', 'file', 'mimes:jpeg,png,jpg,svg,pdf,doc|max:2048'],
        ]);

        if ($validator->fails()) {
            return response()->json($this->failedValidation($validator), 422);
        }
        $user = $this->userRepository->where(['id' => auth('frontend')->user()->id])->first();
      
        if ($user?->profile?->proof_of_address_verified_at) {
            return api()->status(400)->message('Proof of address already verified')->respond();
        }

        if (is_null($user->profile)) {
            UserProfile::create([
                'user_id' => $user->id,
                'address' => $request->address,
                'proof_of_address_type' => $request->proof_of_address_type,
            ]);
        } else {
            $user->profile->address = $request->address;
            $user->profile->proof_of_address_type = $request->proof_of_address_type;
            $user->profile->save();
        }
        if (isset($request->proof_of_address) && file_exists($request->proof_of_address)) {
           
            $user->clearMediaCollection('proof_of_address');
            $user->addMedia($request->file('proof_of_address'))
                ->preservingOriginal()->toMediaCollection('proof_of_address');
             //update status   
            // $user->profile->update(['proof_of_address_verified_at' => now()]);
            // dd($user->getMedia('proof_of_address'));
        }

        $user = $this->userRepository->where(['id' => auth('frontend')->user()->id])->first();
        return api()->status(200)->data(fractal($user, UserTransformer::class)->toArray())->message('Your proof of address has being uploaded and awaiting verification')->respond();

    }

    /**
     * Proof of Address Verification.
     * @OA\Post(
     *      path="/verification/proof-of-identity",
     *      operationId="user-verification-identity",
     *      tags={"Verification"},
     *      summary="Upload a proof of identity file for verification",
     * 
     *       @OA\Parameter(
     *          name="token",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *           type="string"
     *          )
     *       ),
     *       @OA\Parameter(
     *          name="date_of_birth",
     *          description="Date of birth",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *       @OA\Parameter(
     *          name="proof_of_identity_type",
     *          description="Proof of Indentity type e.g national id/card/slip, permanent voter's card",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *       @OA\Parameter(
     *          name="proof_of_identity_number",
     *          description="Proof of Identity Number",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     * 
     *   @OA\RequestBody(
     *     required=false,
     *     @OA\MediaType(
     *     mediaType="multipart/form-data",
     *     @OA\Schema(
     *       @OA\Property(
     *         property="proof_of_identity_front",
     *         description="File can be any of the following formats jpeg,png,jpg,svg,pdf,doc, max size of 2048",
     *         type="file",
     *         format="binary"
     *        ),
     *       @OA\Property(
     *         property="proof_of_identity_back",
     *         description="File can be any of the following formats jpeg,png,jpg,svg,pdf,doc, max size of 2048",
     *         type="file",
     *         format="binary"
     *        ),
     *        @OA\Property(
     *         property="passport_photograph",
     *         description="File can be any of the following formats jpeg,png,jpg,svg,pdf,doc, max size of 2048",
     *         type="file",
     *         format="binary"
     *        ),
     *      ),
     *    ),
     *  ),
     *     @OA\Response(response=200,description="Proof of Identity uploaded successfully"),
     *     @OA\Response(response=202,description="Proof of Identity has been verified before"),
     *     @OA\Response(response=400,description="Proof of Identity has been verified before"),
     *     @OA\Response(response=422,description="Form/data validation error"),
     *       @OA\Response(response=500, description="Bad request"),
     *     )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function processIdentity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date_of_birth' => ['required', 'string'],
            'proof_of_identity_type' => ['required', 'string'],
            'proof_of_identity_number' => ['required', 'string'],
            'proof_of_identity_front' => ['required', 'file', 'mimes:jpeg,png,jpg,svg|max:2048'],
            'proof_of_identity_back' => ['required', 'file', 'mimes:jpeg,png,jpg,svg|max:2048'],
            'passport_photograph' => ['required', 'file', 'mimes:jpeg,png,jpg,svg,pdf,doc|max:2048'],
        ]);
        if ($validator->fails()) {
            return response()->json($this->failedValidation($validator), 422);
        }

        DB::beginTransaction();
        try {
            $user = $this->userRepository->where(['id' => auth('frontend')->user()->id])->first();
        
            if ($user?->profile?->identity_verified_at) {
                return api()->status(400)->message('Your proof of identity is already verified')->respond();
            }
            
            ini_set('max_execution_time', 60);
            if (is_null($user->profile)) {
                UserProfile::create([
                    'user_id' => $user->id,
                    'date_of_birth' => Carbon::parse($request->date_of_birth),
                    'proof_of_identity_type' => $request->proof_of_identity_type,
                    'proof_of_identity_number' => $request->proof_of_identity_number,
                ]);
            } else {

                UserProfile::where(['user_id' => $user->id])->update([
                
                    'date_of_birth' => Carbon::parse($request->date_of_birth),
                    'proof_of_identity_type' => $request->proof_of_identity_type,
                    'proof_of_identity_number' => $request->proof_of_identity_number,
                ]);
            }
           
            if (isset($request->proof_of_identity_front) && file_exists($request->proof_of_identity_front)) {
                $user->clearMediaCollection('proof_of_identity_front');
                $user->addMedia($request->file('proof_of_identity_front'))
                    ->toMediaCollection('proof_of_identity_front');
            
            }
            
            if (isset($request->proof_of_identity_back) && file_exists($request->proof_of_identity_back)) {
                $user->clearMediaCollection('proof_of_identity_back');
                $user->addMedia($request->file('proof_of_identity_back'))
                    ->toMediaCollection('proof_of_identity_back');

            }
           
            if (isset($request->passport_photograph) && file_exists($request->passport_photograph)) {
                $user->clearMediaCollection('passport_photograph');
                $user->addMedia($request->file('passport_photograph'))
                    ->toMediaCollection('passport_photograph');
            }
           
            //update status
            // $user->profile->update(['identity_verified_at ' => now()]);
            DB::commit();

            $user = $this->userRepository->where(['id' => auth('frontend')->user()->id])->first();
            return api()->status(200)->data(fractal($user, UserTransformer::class)->toArray())->message('Your proof of identity has being uploaded and awaiting verification')->respond();
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error("Identity verification : " . $e);
            return api()->status(500)->message('There was an error uploading:  ' .$e->getMessage())->respond();
        }
    }
}

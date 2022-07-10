<?php

/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           MiscellaneousController.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Http\Controllers\Front;

use App\Abstracts\Http\Controllers\FrontController;
use App\Repositories\Users\UserRepository;
use App\Traits\Common\Flutterwave;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Repositories\Common\BankRepository;
use App\Traits\Common\Paystack;
use App\Transformers\Common\BankTransformer;
use Illuminate\Support\Facades\Validator;
use Prettus\Repository\Exceptions\RepositoryException;
use Throwable;

class MiscellaneousController extends FrontController
{
    use Paystack;
    /**
     * @var BankRepository
     */
    private BankRepository $bankRepository;

    /**
     * MiscellaneousController constructor.
     * @param BankRepository $bankRepository
     */
    public function __construct(BankRepository $bankRepository)
    {
        parent::__construct();
        $this->bankRepository = $bankRepository;
    }

    /**
     * @OA\Get(
     *      path="/common/bank/all",
     *      operationId="bank_all",
     *      tags={"Common"},
     *      summary="fetch all banks",
     *      description="return all banks",
     *     @OA\Response(response=200,description="fetched all banks"),
     *     @OA\Response(response=500, description="Bad request"),
     *     )
     * @param Request $request
     * @return JsonResponse
     */

    public function fetchBanks(Request $request): JsonResponse
    {
        $newRequest = array_merge([], $request->toArray());
        try {
            $banks = $this->bankRepository->getBanks($newRequest);
            return api()->status(200)->data(fractal()->collection($banks, BankTransformer::class)->toArray())->respond();

        } catch (\Exception $ex) {
            logger()->error('Error fetching banks : ' . $ex);
            return api()->status(500)->message('Error fetching banks')->respond();
        }
    }

    /**
     * @OA\Post(
     *      path="/common/bank/verify_account",
     *      operationId="bank_verify_account",
     *      tags={"Common"},
     *      summary="verify bank data",
     *       security={{"bearerAuth":{}}},
     *      @OA\Parameter(
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
     *     @OA\Response(response=200,description="fetched data"),
     *     @OA\Response(response=500, description="Bad request"),
     *     )
     * @param Request $request
     * @return JsonResponse
     * @throws RepositoryException
     */
    public function verifyBankingData(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'account_number' => ['required', 'string', 'max:255'],
            'bank_id' => ['required', 'exists:banks,id'],
        ], [
            'account_number.required' => 'Account number is required',
            'account_number.unique' => 'Account number already in use',
            'account_number.max' => 'Account number cannot exceed 15 characters',
            'bank_id.required' => 'Bank is required',
            'bank_id.exists' => 'Selected bank does not exist',
        ]);

        if ($validator->fails()) {
            return response()->json($this->failedValidation($validator), 422);
        }
        $bankCode = $this->bankRepository->find($request->bank_id)->code;
        try {
            $accountData = $this->verifyRecipientAccount([
                'account_number' => $request->account_number,
                'account_bank' => $bankCode
            ]);
            return api()->status(200)->data(['status' => $accountData])->respond();

        } catch (\Exception $ex) {
            logger()->error('Error verifying banking data: ' . $ex);
            return api()->status(500)->message('Error verifying banking data')->respond();
        }
    }
}

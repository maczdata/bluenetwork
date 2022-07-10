<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           TransactionController.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Http\Controllers\Front\Account;

use App\Abstracts\Http\Controllers\FrontController;
use App\Repositories\Common\TransactionRepository;
use App\Transformers\Common\TransactionTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class TransactionController
 * @package App\Http\Controllers\Front\Account
 */
class TransactionController extends FrontController
{
    /**
     * TransactionController constructor.
     * @param TransactionRepository $transactionRepository
     */
    public function __construct(protected TransactionRepository $transactionRepository)
    {
        parent::__construct();
    }

    /**
     * @OA\Get(
     *      path="/account/transaction/list",
     *      operationId="account_transaction_list",
     *      tags={"Account"},
     *      summary="User's transaction list",
     *      description="All user's transactions",
     *      security={{"bearerAuth":{}}},
     *       @OA\Parameter(
     *          name="per_page",
     *          description="No of transaction per page",
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *       ),
     *       @OA\Parameter(
     *          name="page",
     *          description="Set Current Page",
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *       ),
     *       @OA\Parameter(
     *          name="start_date",
     *          description="Set filter start date",
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *       ),
     *       @OA\Parameter(
     *          name="end_date",
     *          description="Set filter end date",
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *       ),
     *    @OA\Parameter(
     *          name="transaction_type",
     *          description="incoming or outgoing transaction",
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *              enum={"incoming","outgoing","all"}
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="token",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *           type="string"
     *          )
     *        ),
     *     @OA\Response(response=200,description="fetched all transactions"),
     *     @OA\Response(response=500, description="Bad request"),
     *     )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        ini_set('max_execution_time', 180);
        $validator = Validator::make($request->all(), [
            'start_date' => 'nullable|date|date_format:Y-m-d',
            'end_date' => 'nullable|date|date_format:Y-m-d'
        ]);

        if ($validator->fails()) {
            return response()->json($this->failedValidation($validator), 422);
        }
       
        try {
            $modifiedRequest = array_merge([], $request->toArray());
            $transactions = $this->transactionRepository->getTransactions($request->user('frontend'), $modifiedRequest);
        
            return api()->status(200)->data(fractal($transactions, TransactionTransformer::class)->toArray())->respond();
        } catch (\Exception $ex) {
            logger()->error('Error fetching transactions: ' . $ex);
            return api()->status(500)->message('Hupz there was a technical error, please reach out to us')->respond();
        }
    }

    /**
     * @OA\Get(
     *      path="/account/transaction/{transaction_id}/single",
     *      operationId="transaction_single",
     *      tags={"Account"},
     *      summary="user single transaction",
     *      description="User ingle transaction",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="transaction_id",
     *          description="transaction id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Response(response=200,description="fetched transaction"),
     *     @OA\Response(response=500, description="Bad request"),
     *     )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function singleTransaction(Request $request): JsonResponse
    {
        
        $transactionId = $request->transaction_id;
        try {
            $transaction = $this->transactionRepository->getTransaction('id', $transactionId);
            return api()->status(200)->data(fractal($transaction, new TransactionTransformer())->toArray())->respond();
        } catch (\Exception $ex) {
            logger()->error('Fetching single transaction error : ' . $ex);
            return api()->status(500)->message('Hupz there was a technical error, please reach out to us')->respond();
        }
    }
}

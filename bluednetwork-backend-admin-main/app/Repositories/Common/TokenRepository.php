<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           TokenRepository.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 2:40 PM
 */

namespace App\Repositories\Common;

use App\Eloquent\Repository;
use App\Events\Common\SendToken;
use App\Events\Common\VerifyToken;
use App\Models\Common\Token;
use App\Traits\Common\TokenTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class TokenRepository
 * @package App\Repositories\Common
 */
class TokenRepository extends Repository
{
    use TokenTrait;

    /**
     * @return string
     */
    public function model()
    {
        return Token::class;
    }

    /**
     * Execute the job.
     *
     * @param $entity
     * @param $type
     * @param string|null $source
     * @param string|null $otp
     * @return mixed
     */

    public function sendToken($entity, $type, ?string $source, ?string $otp): mixed
    {
        return DB::transaction(function () use ($entity, $type, $source, $otp) {
            $token = $this->createToken($entity, $type, $source, $otp);
            event(new SendToken($token));
            
        });
    }

    /**
     * @param $request
     * @param $token
     * @return mixed
     */
    public function validateToken($request, $token): mixed
    {
        return DB::transaction(function () use ($request, $token) {
            $token->tokenable()->update($request->toArray());
            $token->delete();
            event(new VerifyToken($token->tokenable));

            return $token;
        });
    }
}

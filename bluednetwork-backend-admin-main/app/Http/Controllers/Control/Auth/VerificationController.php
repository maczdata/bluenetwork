<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           VerificationController.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     20/08/2021, 7:23 PM
 */
namespace App\Http\Controllers\Control\Auth;

use App\Abstracts\Http\Controllers\FrontController;
use App\Models\Users\User;
use App\Repositories\Common\TokenRepository;
use App\Repositories\Users\UserRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * Class VerificationController
 * @package App\Http\Controllers\Front\Portal\Auth
 */
class VerificationController extends FrontController
{
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
     * @param Request $request
     * @return Application|Factory|\Illuminate\Contracts\View\View
     */
    public function verifyForm(Request $request)
    {
        $verificationType = $request->verification_source;
        if ($verificationType == 'email') {
            return $this->processVerification($request);
        }
        return view($this->_config['view']);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function processVerification(Request $request): RedirectResponse
    {
        $verificationToken = $request->verification_token;
        $verificationSource = $request->verification_source;
        $token = $this->tokenRepository->where([
            'token' => $verificationToken,
            'type' => 1,
            'source' => $verificationSource,
        ])->whereHasMorph('tokenable', User::class)->first();
        $typeToLabel = (($verificationSource == 'email') ? 'Email' : (($verificationSource == 'phone_number') ? 'Phone number' : ''));
        if (is_null($token)) {
            flash($typeToLabel . ' verification token is invalid')->error();
            return redirect()->route('portal.login.form');
        }
        if ($token->tokenExpired()) {
            $token->delete();
            flash($typeToLabel . ' verification token has expired')->error();
            return redirect()->route('portal.login.form');
        }


        if ($verificationSource == 'email' && !is_null($token->tokenable->email_verified_at)) {
            flash($typeToLabel . ' has already being verified')->error();
            return redirect()->route('portal.login.form');
        }
        if ($verificationSource == 'phone_number' && $token->tokenable->hasVerifiedPhone()) {
            flash($typeToLabel . ' has already being verified')->error();
            return redirect()->route('portal.login.form');
        }

        DB::beginTransaction();
        try {
            $data = [];

            $token = $this->tokenRepository->validateToken(collect($data), $token);
            if ($verificationSource == 'email') {
                $token->tokenable->markEmailAsVerified();
            }
            if ($verificationSource == 'phone_number') {
                $token->tokenable->markPhoneAsVerified();
            }
            DB::commit();
            auth('frontend')->login($token->tokenable);
            flash($typeToLabel . ' has being verified')->success();
            return redirect()->route('portal.account.dashboard');
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error("User Verification error : " . $e);
            flash('There was an error verifying ' . $typeToLabel . ', please try again')->error();
            return redirect()->route('portal.login.form');
        }
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function showResendForm()
    {
        return view($this->_config['view']);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function processResend(Request $request): RedirectResponse
    {
        $otp = null;
        $source = $request->source;
        $request->validate(
            [
                'source' => 'required|in:email,phone_number',
                'source_value' => 'required',
            ],
            [
                'source.required' => 'Source is required',
                'source_value.required' => 'Source value is required',
            ]
        );
        session()->forget('resent');
        $sourceTolabel = (($source == 'email') ? 'Email' : (($source == 'phone_number') ? 'Phone number' : ''));
        $user = $this->userRepository->where([$source => $request->source_value])->first();
        if (is_null($user)) {
            flash('User not found')->error();
            return redirect()->route('portal.login.form');
        }
        if ($request->source == 'email' && $user->hasVerifiedEmail()) {
            flash('Email address already verified')->error();
            return redirect()->route('portal.login.form');
        }
        if ($request->source == 'phone_number' && $user->hasVerifiedPhone()) {
            flash('Phone number already verified')->error();
            return redirect()->route('portal.login.form');
        }
        DB::beginTransaction();
        try {
            $user->tokens()->where('type', 1)->delete();
            $this->tokenRepository->sendToken($user, 1, $request->source, ($source == 'phone_number') ? generateOtp(5) : null);
            DB::commit();
            flash($sourceTolabel . ' verification sent, Please activate your account with the link in
            this
            mail. If you cannot find the mail, please also check the Junk/Spam folder!')->success();
            return redirect()->route('portal.verification.resend')->with('resent', true);
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error("User verification resend error : " . $e);
            flash('There was an error sending ' . $sourceTolabel . ', please try again')->error();
            return redirect()->back();
        }
    }
}

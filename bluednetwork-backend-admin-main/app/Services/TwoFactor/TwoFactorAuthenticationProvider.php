<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           TwoFactorAuthenticationProvider.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/04/2021, 4:31 AM
 */

namespace App\Services\TwoFactor;

use App\Contracts\Common\TwoFactorAuthenticationProvider as TwoFactorAuthenticationProviderContract;
use PragmaRX\Google2FA\Exceptions\IncompatibleWithGoogleAuthenticatorException;
use PragmaRX\Google2FA\Exceptions\InvalidCharactersException;
use PragmaRX\Google2FA\Exceptions\SecretKeyTooShortException;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorAuthenticationProvider //implements TwoFactorAuthenticationProviderContract
{
	/**
	 * The underlying library providing two factor authentication helper services.
	 *
	 * @var Google2FA
	 */
	protected $engine;

	/**
	 * Create a new two factor authentication provider instance.
	 *
	 * @param Google2FA $engine
	 * @return void
	 */
	public function __construct(Google2FA $engine)
	{
		$this->engine = $engine;
	}

	/**
	 * Generate a new secret key.
	 *
	 * @return string
	 * @throws IncompatibleWithGoogleAuthenticatorException
	 * @throws InvalidCharactersException
	 * @throws SecretKeyTooShortException
	 */
	public function generateSecretKey()
	{
		return $this->engine->generateSecretKey();
	}

	/**
	 * Get the two factor authentication QR code URL.
	 *
	 * @param string $companyName
	 * @param string $companyEmail
	 * @param string $secret
	 * @return string
	 */
	public function qrCodeUrl(string $companyName, string $companyEmail, string $secret)
	{
		return $this->engine->getQRCodeUrl($companyName, $companyEmail, $secret);
	}

	/**
	 * Verify the given code.
	 *
	 * @param string $secret
	 * @param string $code
	 * @return bool|int
	 * @throws IncompatibleWithGoogleAuthenticatorException
	 * @throws InvalidCharactersException
	 * @throws SecretKeyTooShortException
	 */
	public function verify(string $secret, string $code)
	{
		return $this->engine->verifyKey($secret, $code);
	}
}

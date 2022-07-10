<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           HasConnectedAccounts.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Traits\Common;

use App\Models\Common\ConnectedAccount;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Trait HasConnectedAccounts
 * @package App\Traits\Common
 */
trait HasConnectedAccounts
{
    /**
     * Determine if the given connected account is the current connected account.
     *
     * @param $connectedAccount
     * @return bool
     */
    public function isCurrentConnectedAccount($connectedAccount): bool
    {
        return $connectedAccount->id === $this->currentConnectedAccount->id;
    }

	/**
	 * Get the current external account of the user's context.
     * @return mixed
     */
	public function currentConnectedAccount(): mixed
    {
        if (is_null($this->current_connected_account_id) && $this->id) {
            $this->switchConnectedAccount(
                $this->connectedAccounts()->orderBy('created_at')->first()
            );
        }

		return $this->belongsTo(ConnectedAccount::class, 'current_connected_account_id');
	}

	/**
	 * Switch the user's context to the given external account.
	 *
     * @param $connectedAccount
     * @return bool
     */
	public function switchConnectedAccount($connectedAccount): bool
    {
		if (!$this->ownsConnectedAccount($connectedAccount)) {
			return false;
		}

		$this->forceFill([
			'current_connected_account_id' => $connectedAccount->id,
		])->save();

		$this->setRelation('currentConnectedAccount', $connectedAccount);

		return true;
	}

	/**
	 * Determine if the user owns the given external account.
	 *
     * @param $connectedAccount
     * @return bool
     */
	public function ownsConnectedAccount($connectedAccount): bool
    {
		return $this->getKey() == $connectedAccount->connectable->getKey();
	}

	/**
	 * Determine if the user has a specific account type.
	 *
	 * @param string $provider
	 * @return bool
	 */
	public function hasTokenFor(string $provider): bool
    {
		return $this->connectedAccounts->contains('provider_name', Str::lower($provider));
	}

    /**
     * Attempt to retrieve the token for a given provider.
     *
     * @param string $provider
     * @param null $default
     * @return mixed
     */
	public function getTokenFor(string $provider, $default = null): mixed
    {
		if ($this->hasTokenFor($provider)) {
			return $this->connectedAccounts
				->where('provider_name', Str::lower($provider))
				->first()
				->token;
		}

		return $default;
	}

	/**
	 * Attempt to find a external account that belongs to the user,
	 * for the given provider and ID.
	 *
     * @param string $provider
     * @param string $id
     * @return mixed
     */
	public function getConnectedAccountFor(string $provider, string $id): mixed
    {
		return $this->connectedAccounts
			->where('provider_name', $provider)
			->firstWhere('provider_id', $id);
	}

	/**
	 * Get all of the external accounts belonging to the user.
	 *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
	public function connectedAccounts(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(ConnectedAccount::class, 'connectable');
	}
}

<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           Credentials.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Contracts\ConnectedAccounts;

use DateTimeInterface;

interface Credentials
{
    /**
     * Get the ID for the credentials.
     *
     * @return string
     */
    public function getId(): string;

    /**
     * Get token for the credentials.
     *
     * @return string
     */
    public function getToken(): string;

    /**
     * Get the token secret for the credentials.
     *
     * @return string|null
     */
    public function getTokenSecret(): ?string;

    /**
     * Get the refresh token for the credentials.
     *
     * @return string|null
     */
    public function getRefreshToken(): ?string;

    /**
     * Get the expiry date for the credentials.
     *
     * @return DateTimeInterface|null
     */
    public function getExpiry(): ?DateTimeInterface;
}

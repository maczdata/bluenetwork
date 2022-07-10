<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           Providers.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Services\Connected;

class Providers
{
    /**
     * Determine if the given provider is enabled.
     *
     * @param  string  $provider
     * @return bool
     */
    public static function enabled(string $provider): bool
    {
        return in_array($provider, config('bds.connected_providers', []));
    }

    /**
     * Determine if the application has support for the Bitbucket provider.
     *
     * @return bool
     */
    public static function hasBitbucketSupport(): bool
    {
        return static::enabled(static::bitbucket());
    }

    /**
     * Determine if the application has support for the Facebook provider.
     *
     * @return bool
     */
    public static function hasFacebookSupport(): bool
    {
        return static::enabled(static::facebook());
    }

    /**
     * Determine if the application has support for the GitLab provider.
     *
     * @return bool
     */
    public static function hasGitlabSupport(): bool
    {
        return static::enabled(static::gitlab());
    }

    /**
     * Determine if the application has support for the GitHub provider.
     *
     * @return bool
     */
    public static function hasGithubSupport(): bool
    {
        return static::enabled(static::github());
    }

    /**
     * Determine if the application has support for the Google provider.
     *
     * @return bool
     */
    public static function hasGoogleSupport(): bool
    {
        return static::enabled(static::google());
    }

    /**
     * Determine if the application has support for the LinkedIn provider.
     *
     * @return bool
     */
    public static function hasLinkedInSupport()
    {
        return static::enabled(static::linkedin());
    }

    /**
     * Determine if the application has support for the LinkedIn provider.
     *
     * @return bool
     */
    public static function hasTwitterSupport(): bool
    {
        return static::enabled(static::twitter());
    }

    /**
     * Enable the bitbucket provider.
     *
     * @return string
     */
    public static function bitbucket(): string
    {
        return 'bitbucket';
    }

    /**
     * Enable the Facebook provider.
     *
     * @return string
     */
    public static function facebook()
    {
        return 'facebook';
    }

    /**
     * Enable the github provider.
     *
     * @return string
     */
    public static function github()
    {
        return 'github';
    }

    /**
     * Enable the gitlab provider.
     *
     * @return string
     */
    public static function gitlab()
    {
        return 'gitlab';
    }

    /**
     * Enable the google provider.
     *
     * @return string
     */
    public static function google()
    {
        return 'google';
    }

    /**
     * Enable the linkedin provider.
     *
     * @return string
     */
    public static function linkedin()
    {
        return 'linkedin';
    }

    /**
     * Enable the twitter provider.
     *
     * @return string
     */
    public static function twitter()
    {
        return 'twitter';
    }
}

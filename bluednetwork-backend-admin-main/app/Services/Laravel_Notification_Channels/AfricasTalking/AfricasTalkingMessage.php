<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           AfricasTalkingMessage.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Services\Laravel_Notification_Channels\AfricasTalking;

/**
 * Class AfricasTalkingMessage
 * @package App\Services\Laravel_Notification_Channels\AfricasTalking
 */
class AfricasTalkingMessage
{
    /** @var string */
    protected string $content;

    /** @var string|null */
    protected ?string $from;

    /**
     * Set content for this message.
     *
     * @param string $content
     * @return $this
     */
    public function content(string $content): self
    {
        $this->content = trim($content);

        return $this;
    }

    /**
     * Set sender for this message.
     *
     * @param string $from
     * @return self
     */
    public function from(string $from): self
    {
        $this->from = trim($from);

        return $this;
    }

    /**
     * Get message content.
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Get sender info.
     *
     * @return string
     */
    public function getSender(): string
    {
        return $this->from ?? config('services.africastalking.from');
    }
}

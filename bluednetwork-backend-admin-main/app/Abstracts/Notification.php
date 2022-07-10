<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           Notification.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/04/2021, 4:31 AM
 */

namespace App\Abstracts;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use App\Models\Common\EmailTemplate;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification as BaseNotification;

/**
 * Class Notification
 * @package App\Abstracts
 */
abstract class Notification extends BaseNotification
{
    /**
     * @var string
     */
    protected string $queue;

    /**
     * @var Repository|Application|mixed
     */
    protected $delay;

    /**
     * Create a notification instance.
     */
    public function __construct()
    {
        $this->queue = 'high';
        $this->delay = config('queue.connections.database.delay');
    }

    /**
     * Get the notification's channels.
     *
     * @param mixed $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Initialise the mail representation of the notification.
     *
     * @return MailMessage
     */
    //   public function initMessage()
    //   {
    //      $template = EmailTemplate::alias($this->template)->first();
    //
    //      return (new MailMessage)
    //         ->from(config('mail.from.address'), config('mail.from.name'))
    //         ->subject($this->getSubject($template))
    //         ->view('partials.email.body', ['body' => $this->getBody($template)]);
    //   }

    /**
     * @param $template
     * @return string|string[]
     */
    public function getSubject($template)
    {
        return $this->replaceTags($template->subject);
    }

    /**
     * @param $content
     * @return string|string[]
     */
    public function replaceTags($content)
    {
        $pattern = $this->getTagsPattern();
        $replacement = $this->applyQuote($this->getTagsReplacement());

        return $this->revertQuote(preg_replace($pattern, $replacement, $content));
    }

    /**
     * @return array
     */
    public function getTagsPattern()
    {
        $pattern = [];

        foreach ($this->getTags() as $tag) {
            $pattern[] = "/" . $tag . "/";
        }

        return $pattern;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return [];
    }

    /**
     * @param $vars
     * @return array
     */
    public function applyQuote($vars)
    {
        $new_vars = [];

        foreach ($vars as $var) {
            $new_vars[] = preg_quote($var);
        }

        return $new_vars;
    }

    /**
     * @return array
     */
    public function getTagsReplacement()
    {
        return [];
    }

    /**
     * @param $content
     * @return string|string[]
     */
    public function revertQuote($content)
    {
        return str_replace('\\', '', $content);
    }

    /**
     * @param $template
     * @return string|string[]
     */
    public function getBody($template)
    {
        return $this->replaceTags($template->body);
    }
}

<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           Body.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Services\Response;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Database\Eloquent\JsonEncodingException;
use Illuminate\Support\Collection;

class Body implements Arrayable, Jsonable
{
    /**
     * @var bool
     */
    private $attachData;

    /**
     * Body constructor.
     * @param array $data
     * @param array $meta
     * @param array $messages
     * @param int $status
     */
    public function __construct(protected array $data = [], protected array $meta = [], protected array $messages = [], private int $status = 200)
    {
        $this->setData($data);
        $this->setMeta($meta);
        $this->setMessages($messages);
        $this->setStatus($status);
    }

    /**
     * @return mixed
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param $data
     * @param bool $attachData
     * @return $this
     */
    public function setData($data, bool $attachData = false): Body
    {
        $this->data = $data;
        $this->attachData = $attachData;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getMessages(): Collection
    {
        return collect($this->messages);
    }

    /**
     * @param mixed $messages
     *
     * @return Body
     */
    public function setMessages(array $messages): Body
    {
        $this->messages = $messages;

        return $this;
    }

    /**
     * @param $message
     * @return $this
     */
    public function addMessage($message): Body
    {
        $this->messages[] = $message;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMeta(): array
    {
        return $this->meta;
    }

    /**
     * @param mixed $meta
     *
     * @return Body
     */
    public function setMeta($meta): Body
    {
        $this->meta = $meta;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     *
     * @return Body
     */
    public function setStatus(int $status): Body
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param int $options
     * @return string
     */
    public function toJson($options = 0): string
    {
        $json = json_encode($this->toArray(), $options);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new JsonEncodingException;
        }

        return $json;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        $response = [
            'status_code' => $this->getStatus(),
        ];
        if (count($this->getData())){
            $response['data'] = $this->getData();
        }
        if (count($this->getMeta())){
            $response['meta'] =  $this->getMeta();
        }
        if ($this->getMessages()->count() > 0){
            if ($this->getMessages()->count() > 1 ){
                $response['messages'] =  $this->getMessages()->toArray();
            }else{
                $response['message'] =  $this->getMessages()->first();
            }
        }
        return $response;
    }
}

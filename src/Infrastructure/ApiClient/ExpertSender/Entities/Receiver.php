<?php

namespace App\Infrastructure\ApiClient\ExpertSender\Entities;

use App\Infrastructure\ApiClient\ExpertSender\Exception\ExpertSenderException;

class Receiver
{
    protected $email;
    protected $id;

    public function __construct($email = null, $id = null)
    {
        if ($email === null && $id === null) {
            throw new ExpertSenderException('Email or id parameter required');
        }

        $this->email = $email;
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}

<?php

namespace App\Infrastructure\ApiClient\ExpertSender\Exception;

class ExpertSenderException extends \Exception
{
    public function __construct(string $message = '')
    {
        parent::__construct($message);
    }
}

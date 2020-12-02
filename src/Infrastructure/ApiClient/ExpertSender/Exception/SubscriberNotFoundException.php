<?php

namespace App\Infrastructure\ApiClient\ExpertSender\Exception;

class SubscriberNotFoundException extends \Exception
{
    public function __construct(string $value)
    {
        parent::__construct(sprintf('Subscriber %s not found', $value));
    }
}

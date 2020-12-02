<?php

declare(strict_types=1);

namespace App\Infrastructure\Security\Exception;

final class InvalidCredentialsException extends \Exception
{
    public function __construct()
    {
        parent::__construct('The login details weren’t correct. Please try again.');
    }
}

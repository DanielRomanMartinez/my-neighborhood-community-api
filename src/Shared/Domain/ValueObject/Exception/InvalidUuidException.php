<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject\Exception;

use App\Shared\Domain\DomainError;

final class InvalidUuidException extends DomainError
{
    private string $id;

    public function __construct(string $id)
    {
        $this->id = $id;

        parent::__construct();
    }

    public function errorCode(): string
    {
        return 'invalid_uuid';
    }

    public function errorMessage(): string
    {
        return sprintf('<%s> isn\'t a valid UUID.', $this->id);
    }
}

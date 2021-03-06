<?php

declare(strict_types=1);

namespace App\Infrastructure\Storage\Exception;

use Exception;
use Throwable;

final class StorageException extends Exception
{
    public function __construct(string $message, Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}

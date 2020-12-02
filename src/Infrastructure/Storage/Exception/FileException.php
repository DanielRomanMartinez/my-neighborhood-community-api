<?php

declare(strict_types=1);

namespace App\Infrastructure\Storage\Exception;

use RuntimeException;
use Throwable;

final class FileException extends RuntimeException
{
    public function __construct(string $message, Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}

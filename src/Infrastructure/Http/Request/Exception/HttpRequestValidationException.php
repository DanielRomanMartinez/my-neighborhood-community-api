<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Request\Exception;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class HttpRequestValidationException extends BadRequestHttpException
{
    private array $errors;

    public function __construct(string $message = null, array $errors = [], \Throwable $previous = null, $code = 0, array $headers = [])
    {
        parent::__construct($message, $previous, $code, $headers);

        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}

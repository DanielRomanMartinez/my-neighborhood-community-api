<?php

declare(strict_types=1);

namespace App\Infrastructure\Pdf\Pdftk\Exception;

use RuntimeException;

final class PdftkCommandFailed extends RuntimeException
{
    public function __construct(string $message)
    {
        parent::__construct(sprintf('Error: %s', $message));
    }
}

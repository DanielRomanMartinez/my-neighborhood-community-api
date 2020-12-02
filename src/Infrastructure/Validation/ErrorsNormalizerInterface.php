<?php

declare(strict_types=1);

namespace App\Infrastructure\Validation;

interface ErrorsNormalizerInterface
{
    public function normalize($errorsList);
}

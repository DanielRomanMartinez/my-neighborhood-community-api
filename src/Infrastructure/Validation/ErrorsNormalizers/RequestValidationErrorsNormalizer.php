<?php

declare(strict_types=1);

namespace App\Infrastructure\Validation\ErrorsNormalizers;

use App\Infrastructure\Validation\ErrorsNormalizerInterface;

final class RequestValidationErrorsNormalizer implements ErrorsNormalizerInterface
{
    public function normalize($violationList)
    {
        return $violationList->get(0)->getMessage();
    }
}

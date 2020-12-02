<?php

declare(strict_types=1);

namespace App\Infrastructure\Validation\ValidationRules;

use App\Infrastructure\Validation\AbstractValidationRules;
use Symfony\Component\Validator\Constraints as Assert;

final class UpdateApplicationDataRules extends AbstractValidationRules
{
    public function rules(): array
    {
        return [
            'step' => [
                new Assert\NotBlank(),
            ],
            'data' => [
                new Assert\NotBlank(),
            ],
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Infrastructure\Validation\ValidationRules;

use App\Infrastructure\Validation\AbstractValidationRules;
use Symfony\Component\Validator\Constraints as Assert;

final class UpdateApplicationNameRules extends AbstractValidationRules
{
    public function rules(): array
    {
        return [
            'name' => [
                new Assert\NotBlank(),
            ],
        ];
    }
}

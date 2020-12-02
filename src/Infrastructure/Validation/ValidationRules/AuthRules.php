<?php

declare(strict_types=1);

namespace App\Infrastructure\Validation\ValidationRules;

use App\Infrastructure\Validation\AbstractValidationRules;
use Symfony\Component\Validator\Constraints as Assert;

final class AuthRules extends AbstractValidationRules
{
    public function rules(): array
    {
        return [
            'username' => [
                new Assert\NotBlank,
            ],
            'password' => [
                new Assert\NotBlank,
            ],
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Infrastructure\Validation\ValidationRules;

use App\Infrastructure\Validation\AbstractValidationRules;
use Symfony\Component\Validator\Constraints as Assert;

final class UpdatePasswordRules extends AbstractValidationRules
{
    public function rules(): array
    {
        return [
            'token'    => [
                new Assert\NotBlank,
            ],
            'password' => [
                new Assert\NotBlank,
                new Assert\Length(['min' => 8]),
            ],
        ];
    }
}

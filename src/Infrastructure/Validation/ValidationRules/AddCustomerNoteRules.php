<?php

declare(strict_types=1);

namespace App\Infrastructure\Validation\ValidationRules;

use App\Infrastructure\Validation\AbstractValidationRules;
use Symfony\Component\Validator\Constraints\NotBlank;

final class AddCustomerNoteRules extends AbstractValidationRules
{
    public function rules(): array
    {
        return [
            'content' => [
                new NotBlank,
            ],
        ];
    }
}

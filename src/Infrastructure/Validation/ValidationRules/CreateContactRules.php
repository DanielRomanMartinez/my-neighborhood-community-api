<?php

declare(strict_types=1);

namespace App\Infrastructure\Validation\ValidationRules;

use App\Infrastructure\Validation\AbstractValidationRules;
use Symfony\Component\Validator\Constraints as Assert;

final class CreateContactRules extends AbstractValidationRules
{
    public function rules(): array
    {
        return [
            'requestType' => [
                new Assert\NotBlank,
            ],
            'name'         => [
                new Assert\NotBlank,
            ],
            'email'        => [
                new Assert\NotBlank,
            ],
            'message'      => [
                new Assert\NotBlank,
            ],
        ];
    }
}

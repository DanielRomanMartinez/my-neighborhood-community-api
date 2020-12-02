<?php

declare(strict_types=1);

namespace App\Infrastructure\Validation\ValidationRules;

use App\Domain\Subscription\Subscription;
use App\Infrastructure\Validation\AbstractValidationRules;
use Symfony\Component\Validator\Constraints as Assert;

final class CancelSubscriptionRules extends AbstractValidationRules
{
    public function rules(): array
    {
        return [
            'unsubscribeReason' => [
                new Assert\NotBlank,
                new Assert\Choice([
                    Subscription::REASON_UNNEEDED_SERVICE,
                    Subscription::REASON_NOT_ENOUGH_CONTRACTS,
                    Subscription::REASON_UNHAPPY,
                    Subscription::REASON_OTHER,
                    Subscription::REASON_CHARGEBACK,
                    Subscription::REASON_REFUND,
                    Subscription::REASON_PAYMENT_ERROR,
                ]),
            ],
        ];
    }
}

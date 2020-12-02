<?php

declare(strict_types=1);

namespace App\Domain\Customer\Event;

use App\Domain\Event\AbstractDomainEvent;

final class CustomerWasCreated extends AbstractDomainEvent
{
    protected const EVENT_TYPE = 'myneighborhood.customer.1.registered';

    private string $customerId;

    public function __construct(string $customerId, string $occurredOn = null)
    {
        parent::__construct($occurredOn);
        $this->customerId = $customerId;
    }

    protected function attributes(): array
    {
        return ['customerId' => $this->customerId];
    }
}

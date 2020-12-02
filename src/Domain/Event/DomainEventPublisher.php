<?php

declare(strict_types=1);

namespace App\Domain\Event;

interface DomainEventPublisher
{
    public function notify(DomainEvent ...$domainEvents): void;

    public function notifyWithUser(string $userId, DomainEvent ...$domainEvents): void;
}

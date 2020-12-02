<?php

declare(strict_types=1);

namespace App\Shared\Domain;

use App\Domain\Event\DomainEvent;

abstract class AggregateRoot
{
    private array $events = [];

    final public function recordEvent(DomainEvent $event): void
    {
        $this->events[] = $event;
    }

    final public function pullEvents(): array
    {
        $events = $this->events;
        $this->events = [];

        return $events;
    }
}

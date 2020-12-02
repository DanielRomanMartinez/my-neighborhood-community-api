<?php

declare(strict_types=1);

namespace App\Domain\Event;

interface DomainEvent
{
    public function eventType(): string;

    public function toArray(): array;
}

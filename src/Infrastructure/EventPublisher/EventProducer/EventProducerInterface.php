<?php

declare(strict_types=1);

namespace App\Infrastructure\EventPublisher\EventProducer;

use App\Domain\Event\DomainEvent;

interface EventProducerInterface
{
    public function publish(DomainEvent $event): void;
}

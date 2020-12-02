<?php

declare(strict_types=1);

namespace App\Infrastructure\EventPublisher\EventProducer;

use App\Domain\Event\DomainEvent;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Transport\AmqpExt\AmqpStamp;

final class RabbitMQEventProducer implements EventProducerInterface
{
    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function publish(DomainEvent $event): void
    {
        $this->messageBus->dispatch($event, [
            new AmqpStamp($event->eventType(), AMQP_NOPARAM, []),
        ]);
    }
}

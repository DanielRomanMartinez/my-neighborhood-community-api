<?php

declare(strict_types=1);

namespace App\Infrastructure\EventPublisher;

use App\Domain\Event\AbstractDomainEvent;
use App\Domain\Event\DomainEvent;
use App\Domain\Event\DomainEventPublisher as DomainEventPublisherInterface;
use App\Infrastructure\EventPublisher\EventProducer\EventProducerInterface;
use Symfony\Component\Security\Core\Security;

final class DomainEventPublisher implements DomainEventPublisherInterface
{
    private Security $security;
    private EventProducerInterface $eventProducer;
    private array $events = [];
    private array $publishedEvents = [];
    private ?string $userId = null;

    public function __construct(Security $security, EventProducerInterface $eventProducer)
    {
        $this->security = $security;
        $this->eventProducer = $eventProducer;
    }

    public function notify(DomainEvent ...$domainEvents): void
    {
        $this->record(...$domainEvents);
        $this->publishRecordedEvents();
    }

    public function notifyWithUser(string $userId, DomainEvent ...$domainEvents): void
    {
        $this->userId = $userId;
        $this->record(...$domainEvents);
        $this->publishRecordedEvents();
    }

    private function record(DomainEvent ...$domainEvents): void
    {
        $this->events = array_merge($this->events, array_values($domainEvents));
    }

    private function publishRecordedEvents(): void
    {
        array_map(function (AbstractDomainEvent $event) {
            $this->registerUserInEvent($event);
            $this->publish($event);
        }, $this->popEvents());
    }

    private function popEvents(): array
    {
        $events = $this->events;
        $this->events = [];

        return $events;
    }

    private function publish(DomainEvent $event): void
    {
        $this->eventProducer->publish($event);
        $this->publishedEvents[] = $event;
    }

    private function registerUserInEvent(AbstractDomainEvent $event): void
    {
        if ($this->userId) {
            $event->setUserId($this->userId);

            return;
        }

        $userLoggedIn = $this->security->getUser();

        if (null != $userLoggedIn) {
            $event->setUserId($userLoggedIn->id());
        }
    }
}

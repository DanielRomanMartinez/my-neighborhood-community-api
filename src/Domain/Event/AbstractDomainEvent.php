<?php

declare(strict_types=1);

namespace App\Domain\Event;

use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use Ramsey\Uuid\Uuid;

abstract class AbstractDomainEvent implements DomainEvent
{
    private string $occurredOn;
    private ?string $userId = null;

    public function __construct(string $occurredOn = null)
    {
        $this->occurredOn = $occurredOn ?: (new DateTimeImmutable())->format(DateTimeInterface::ATOM);

        if (!defined('static::EVENT_TYPE')) {
            throw new Exception('Constant EVENT_TYPE is not defined on subclass ' . get_class($this));
        }
    }

    public function eventType(): string
    {
        return static::EVENT_TYPE;
    }

    public function toArray(): array
    {
        return [
            'id'          => Uuid::uuid4()->toString(),
            'type'        => $this->eventType(),
            'occurred_on' => $this->occurredOn,
            'user_id'     => $this->userId(),
            'attributes'  => $this->attributes(),
        ];
    }

    public function occurredOn(): string
    {
        return $this->occurredOn;
    }

    public function setOccurredOn(string $occurredOn): void
    {
        $this->occurredOn = $occurredOn;
    }

    public function userId(): ?string
    {
        return $this->userId;
    }

    public function setUserId(?string $userId): void
    {
        $this->userId = $userId;
    }

    abstract protected function attributes(): array;
}

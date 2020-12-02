<?php

declare(strict_types=1);

namespace App\Application\NotificationLog\Create;

use DateTime;

final class CreateNotificationLogCommand
{
    private string $id;
    private ?string $gatewayTransactionId;
    private string $elasticSearchIndex;
    private string $action;
    private string $message;
    private bool $isProcessed;
    private DateTime $occurredOn;

    public function __construct(
        string $id,
        ?string $gatewayTransactionId,
        string $elasticSearchIndex,
        string $action,
        string $message,
        bool $isProcessed,
        DateTime $occurredOn
    ) {
        $this->id = $id;
        $this->gatewayTransactionId = $gatewayTransactionId;
        $this->elasticSearchIndex = $elasticSearchIndex;
        $this->action = $action;
        $this->message = $message;
        $this->isProcessed = $isProcessed;
        $this->occurredOn = $occurredOn;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function gatewayTransactionId(): ?string
    {
        return $this->gatewayTransactionId;
    }

    public function elasticSearchIndex(): string
    {
        return $this->elasticSearchIndex;
    }

    public function action(): string
    {
        return $this->action;
    }

    public function message(): string
    {
        return $this->message;
    }

    public function isProcessed(): bool
    {
        return $this->isProcessed;
    }

    public function occurredOn(): DateTime
    {
        return $this->occurredOn;
    }
}

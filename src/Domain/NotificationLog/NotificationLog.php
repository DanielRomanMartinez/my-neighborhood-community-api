<?php

declare(strict_types=1);

namespace App\Domain\NotificationLog;

use App\Shared\Domain\ValueObject\Uuid;
use DateTime;

final class NotificationLog
{
    private Uuid $id;
    private string $gatewayTransactionId;
    private string $elasticSearchIndex;
    private string $action;
    private string $message;
    private bool $isProcessed;
    private DateTime $occurredOn;

    public function __construct(
        Uuid $id,
        string $gatewayTransactionId,
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

    public function id(): Uuid
    {
        return $this->id;
    }

    public function gatewayTransactionId(): string
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

    public function updateIsProcessed(bool $isProcessed): void
    {
        $this->isProcessed = $isProcessed;
    }

    public function occurredOn(): DateTime
    {
        return $this->occurredOn;
    }

    public function toArray(): array
    {
        return [
            'id'                      => $this->id()->value(),
            'gateway_transaction_id'  => $this->gatewayTransactionId(),
            'elastic_search_index'    => $this->elasticSearchIndex(),
            'action'                  => $this->action(),
            'message'                 => $this->message(),
            'is_processed'            => $this->isProcessed(),
            'occurred_on'             => $this->occurredOn()->format('Y-m-d H:i:sO'),
        ];
    }

    public static function fromArray(array $notificationLog): NotificationLog
    {
        return new self(
            new Uuid($notificationLog['id']),
            $notificationLog['gateway_transaction_id'],
            $notificationLog['elastic_search_index'],
            $notificationLog['action'],
            $notificationLog['message'],
            $notificationLog['is_processed'],
            new DateTime($notificationLog['occurred_on'])
        );
    }
}

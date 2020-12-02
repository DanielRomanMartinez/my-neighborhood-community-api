<?php

declare(strict_types=1);

namespace App\Application\NotificationLog\UpdateNotificationLogIsProcessed;

final class UpdateNotificationLogIsProcessedCommand
{
    private string $id;
    private string $elasticSearchIndex;
    private bool $isProcessed;

    public function __construct(
        string $id,
        string $elasticSearchIndex,
        bool $isProcessed
    ) {
        $this->id = $id;
        $this->elasticSearchIndex = $elasticSearchIndex;
        $this->isProcessed = $isProcessed;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function elasticSearchIndex(): string
    {
        return $this->elasticSearchIndex;
    }

    public function isProcessed(): bool
    {
        return $this->isProcessed;
    }
}

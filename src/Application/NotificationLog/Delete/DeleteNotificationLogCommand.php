<?php

declare(strict_types=1);

namespace App\Application\NotificationLog\Delete;

final class DeleteNotificationLogCommand
{
    private string $id;
    private string $elasticSearchIndex;

    public function __construct(
        string $id,
        string $elasticSearchIndex
    ) {
        $this->id = $id;
        $this->elasticSearchIndex = $elasticSearchIndex;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function elasticSearchIndex(): string
    {
        return $this->elasticSearchIndex;
    }
}

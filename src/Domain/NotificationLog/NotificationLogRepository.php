<?php

namespace App\Domain\NotificationLog;

use App\Shared\Domain\ValueObject\Uuid;

interface NotificationLogRepository
{
    public function create(NotificationLog $notificationLog): void;

    public function update(NotificationLog $notificationLog): void;

    public function find(Uuid $id, string $elasticSearchIndex): ?array;

    public function delete(NotificationLog $notificationLog): void;
}

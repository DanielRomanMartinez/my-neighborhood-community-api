<?php

declare(strict_types=1);

namespace App\Application\NotificationLog\UpdateNotificationLogIsProcessed;

use App\Domain\NotificationLog\NotificationLog;
use App\Domain\NotificationLog\NotificationLogRepository;

final class NotificationLogIsProcessedUpdater
{
    private NotificationLogRepository $repository;

    public function __construct(NotificationLogRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(NotificationLog $notificationLog, bool $isProcessed): void
    {
        $notificationLog->updateIsProcessed($isProcessed);
        $this->repository->update($notificationLog);
    }
}

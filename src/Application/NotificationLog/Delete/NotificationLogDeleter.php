<?php

declare(strict_types=1);

namespace App\Application\NotificationLog\Delete;

use App\Domain\NotificationLog\NotificationLog;
use App\Domain\NotificationLog\NotificationLogRepository;

final class NotificationLogDeleter
{
    private NotificationLogRepository $repository;

    public function __construct(NotificationLogRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(NotificationLog $notificationLog): void
    {
        $this->repository->delete($notificationLog);
    }
}

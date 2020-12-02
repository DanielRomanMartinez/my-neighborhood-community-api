<?php

declare(strict_types=1);

namespace App\Domain\NotificationLog\Service;

use App\Domain\NotificationLog\NotificationLog;
use App\Domain\NotificationLog\NotificationLogRepository;
use App\Domain\NotificationLog\Exception\NotificationLogNotFoundException;
use App\Shared\Domain\ValueObject\Uuid;

final class NotificationLogFinder
{
    private NotificationLogRepository $repository;

    public function __construct(NotificationLogRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(Uuid $id, string $elasticSearchIndex): NotificationLog
    {
        $notificationLog = $this->repository->find($id, $elasticSearchIndex);

        if (empty($notificationLog)) {
            throw new NotificationLogNotFoundException($id->value(), $elasticSearchIndex);
        }

        return NotificationLog::fromArray($notificationLog);
    }
}

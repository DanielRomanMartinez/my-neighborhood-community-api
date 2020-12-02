<?php

declare(strict_types=1);

namespace App\Domain\NotificationLog\Service;

use App\Domain\NotificationLog\NotificationLog;
use App\Domain\NotificationLog\NotificationLogRepository;
use App\Shared\Domain\ValueObject\Uuid;
use DateTime;

final class NotificationLogCreator
{
    private NotificationLogRepository $repository;

    public function __construct(NotificationLogRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(
        Uuid $id,
        string $gatewayTransactionId,
        string $elasticSearchIndex,
        string $action,
        string $message,
        bool $isProcessed,
        DateTime $occurredOn
    ): void {
        $notificationLog = new NotificationLog(
            $id,
            $gatewayTransactionId,
            $elasticSearchIndex,
            $action,
            $message,
            $isProcessed,
            $occurredOn
        );

        $this->repository->create($notificationLog);
    }
}

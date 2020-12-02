<?php

declare(strict_types=1);

namespace App\Application\NotificationLog\Delete;

use App\Domain\NotificationLog\Service\NotificationLogFinder;
use App\Shared\Domain\ValueObject\Uuid;

final class DeleteNotificationLogCommandHandler
{
    private NotificationLogFinder $finder;
    private NotificationLogDeleter $remover;

    public function __construct(
        NotificationLogFinder $finder,
        NotificationLogDeleter $remover
    ) {
        $this->finder = $finder;
        $this->remover = $remover;
    }

    public function __invoke(DeleteNotificationLogCommand $command): void
    {
        $notificationLog = $this->finder->__invoke(new Uuid($command->id()), $command->elasticSearchIndex());
        $this->remover->__invoke($notificationLog);
    }
}

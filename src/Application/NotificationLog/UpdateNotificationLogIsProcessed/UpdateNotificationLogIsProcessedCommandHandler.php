<?php

declare(strict_types=1);

namespace App\Application\NotificationLog\UpdateNotificationLogIsProcessed;

use App\Domain\NotificationLog\Service\NotificationLogFinder;
use App\Shared\Domain\ValueObject\Uuid;

final class UpdateNotificationLogIsProcessedCommandHandler
{
    private NotificationLogFinder $finder;
    private NotificationLogIsProcessedUpdater $updater;

    public function __construct(
        NotificationLogFinder $finder,
        NotificationLogIsProcessedUpdater $updater
    ) {
        $this->finder = $finder;
        $this->updater = $updater;
    }

    public function __invoke(UpdateNotificationLogIsProcessedCommand $command): void
    {
        $notification = $this->finder->__invoke(new Uuid($command->id()), $command->elasticSearchIndex());
        $this->updater->__invoke(
            $notification,
            $command->isProcessed()
        );
    }
}

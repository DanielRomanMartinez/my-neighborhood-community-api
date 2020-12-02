<?php

declare(strict_types=1);

namespace App\Application\NotificationLog\Find;

use App\Domain\NotificationLog\Service\NotificationLogFinder;
use App\Shared\Domain\ValueObject\Uuid;

final class FindNotificationLogQueryHandler
{
    private NotificationLogFinder $finder;

    public function __construct(NotificationLogFinder $finder)
    {
        $this->finder = $finder;
    }

    public function __invoke(FindNotificationLogQuery $query): FindNotificationLogResponse
    {
        $notificationLog = $this->finder->__invoke(new Uuid($query->id()), $query->elasticSearchIndex());

        return (new FindNotificationLogResponseConverter())->__invoke($notificationLog);
    }
}

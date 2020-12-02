<?php

declare(strict_types=1);

namespace App\Application\NotificationLog\Find;

use App\Domain\NotificationLog\NotificationLog;

final class FindNotificationLogResponseConverter
{
    public function __invoke(NotificationLog $notificationLog): FindNotificationLogResponse
    {
        return new FindNotificationLogResponse($notificationLog->toArray());
    }
}

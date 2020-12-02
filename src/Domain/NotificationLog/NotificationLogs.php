<?php

declare(strict_types=1);

namespace App\Domain\NotificationLog;

use App\Shared\Domain\Collection;

final class NotificationLogs extends Collection
{
    protected function type(): string
    {
        return NotificationLog::class;
    }
}

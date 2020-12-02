<?php

declare(strict_types=1);

namespace App\Infrastructure\Notification\Notifier;

use App\Infrastructure\Notification\Notification;

interface NotifierInterface
{
    public function notify(Notification $notification): void;
}

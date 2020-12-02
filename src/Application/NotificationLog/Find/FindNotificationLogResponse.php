<?php

declare(strict_types=1);

namespace App\Application\NotificationLog\Find;

use App\Shared\Domain\Bus\Query\Response;
use App\Shared\Domain\ValueObject\ArrayValueObject;

final class FindNotificationLogResponse extends ArrayValueObject implements Response
{
}

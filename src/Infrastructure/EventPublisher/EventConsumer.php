<?php

declare(strict_types=1);

namespace App\Infrastructure\EventPublisher;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

abstract class EventConsumer implements MessageHandlerInterface
{
}

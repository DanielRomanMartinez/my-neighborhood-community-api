<?php

declare(strict_types=1);

namespace App\Infrastructure\Logger\Processor;

use App\Domain\Event\DomainEvent;

final class MessengerLogProcessor
{
    public function __invoke(array $record)
    {
        if (isset($record['context']) && isset($record['context']['message']) && $record['context']['message'] instanceof DomainEvent) {
            $record['context']['message'] = $record['context']['message']->toArray();
        }

        return $record;
    }
}

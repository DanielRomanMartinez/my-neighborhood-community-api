<?php

declare(strict_types=1);

namespace App\Application\NotificationLog\Create;

use App\Domain\NotificationLog\Service\NotificationLogCreator;
use App\Shared\Domain\ValueObject\Uuid;

final class CreateNotificationLogCommandHandler
{
    private NotificationLogCreator $creator;

    public function __construct(NotificationLogCreator $creator)
    {
        $this->creator = $creator;
    }

    public function __invoke(CreateNotificationLogCommand $command): void
    {
        $this->creator->__invoke(
            new Uuid($command->id()),
            $command->gatewayTransactionId(),
            $command->elasticSearchIndex(),
            $command->action(),
            $command->message(),
            $command->isProcessed(),
            $command->occurredOn()
        );
    }
}

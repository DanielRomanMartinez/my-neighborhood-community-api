<?php

declare(strict_types=1);

namespace App\Domain\NotificationLog\Exception;

use App\Shared\Domain\DomainError;

final class NotificationLogNotFoundException extends DomainError
{
    private string $gatewayTransactionId;
    private string $elasticSearchIndex;

    public function __construct(string $gatewayTransactionId, string $elasticSearchIndex)
    {
        $this->gatewayTransactionId = $gatewayTransactionId;
        $this->elasticSearchIndex = $elasticSearchIndex;
        parent::__construct();
    }

    public function errorCode(): string
    {
        return 'notification_log_not_found';
    }

    public function errorMessage(): string
    {
        return sprintf('Notification with id "%s" not found in the next index: "%s"', $this->gatewayTransactionId, $this->elasticSearchIndex);
    }
}

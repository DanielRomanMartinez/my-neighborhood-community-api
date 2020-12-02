<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\ElasticSearch;

use App\Domain\NotificationLog\NotificationLog;
use App\Domain\NotificationLog\NotificationLogRepository;
use App\Shared\Domain\ValueObject\Uuid;

final class ElasticSearchNotificationRepository extends ElasticSearchRepository implements NotificationLogRepository
{
    public function create(NotificationLog $notificationLog): void
    {
        $this->persist(
            $notificationLog->id()->value(),
            $notificationLog->elasticSearchIndex(),
            $notificationLog->toArray()
        );
    }

    public function update(NotificationLog $notificationLog): void
    {
        $this->modify(
            $notificationLog->id()->value(),
            $notificationLog->elasticSearchIndex(),
            $notificationLog->toArray()
        );
    }

    public function find(Uuid $id, string $elasticSearchIndex): ?array
    {
        return $this->get($id->value(), $elasticSearchIndex);
    }

    public function delete(NotificationLog $notificationLog): void
    {
        $this->remove(
            $notificationLog->id()->value(),
            $notificationLog->elasticSearchIndex()
        );
    }
}

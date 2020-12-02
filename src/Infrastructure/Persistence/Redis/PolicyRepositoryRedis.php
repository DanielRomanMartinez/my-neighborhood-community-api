<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Redis;

use App\Domain\Policy\Policy;
use App\Domain\Policy\PolicyItem;
use App\Domain\Policy\PolicyItems;
use App\Domain\Policy\PolicyRepository;

final class PolicyRepositoryRedis extends RedisRepository implements PolicyRepository
{
    public function create(Policy $policy): void
    {
        $key = $this->buildKey($policy->customerId());

        $this->client->set($key, json_encode($policy->toArray()));
    }

    public function findByCustomerId(string $customerId): ?Policy
    {
        $key = $this->buildKey($customerId);

        $result = $this->client->get($key);

        if (!$result) {
            return null;
        }

        $result = json_decode($result);

        $policyItems = [];

        foreach ($result->policyItems as $item) {
            $policyItem = new PolicyItem(
                $item->access,
                $item->subscriptionId,
                $item->subscriptionIsRecurring,
                $item->subscriptionUnsubscribeAt,
                $item->subscriptionTypeName,
                $item->subscriptionTypeNameLong,
                $item->formTypeId,
                $item->formTypeName,
                $item->applicationId
            );
            array_push($policyItems, $policyItem);
        }

        return new Policy($customerId, new PolicyItems($policyItems));
    }

    public function delete(string $customerId): void
    {
        $key = $this->buildKey($customerId);

        $this->client->del([$key]);
    }

    private function buildKey(string $customerId): string
    {
        return sprintf(
            'customer:policy:%s',
            $customerId
        );
    }
}

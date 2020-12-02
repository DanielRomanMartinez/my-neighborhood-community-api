<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Redis;

use Predis\Client;

abstract class RedisRepository
{
    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    protected function getKeyTtl(string $key): ?int
    {
        $ttl = $this->client->ttl($key);

        return ($ttl !== -1) ? $ttl : null;
    }
}

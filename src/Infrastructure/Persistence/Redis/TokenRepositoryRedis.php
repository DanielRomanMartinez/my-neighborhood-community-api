<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Redis;

use App\Domain\Token\Token;
use App\Domain\Token\TokenRepository;

final class TokenRepositoryRedis extends RedisRepository implements TokenRepository
{
    public function create(Token $token): void
    {
        $key = $this->buildKey($token->token(), $token->domain());

        $data = [
            'user_id' => $token->userId(),
        ];

        $this->client->setex($key, $token->ttl(), json_encode($data));
    }

    public function findByTokenAndDomain(string $token, string $domain): ?Token
    {
        $key = $this->buildKey($token, $domain);

        $result = $this->client->get($key);

        if (!$result) {
            return null;
        }

        $ttl = $this->getKeyTtl($key);
        $result = json_decode($result, true);

        return new Token($domain, $token, $result['user_id'], $ttl);
    }

    public function delete(string $token, string $domain): void
    {
        $key = $this->buildKey($token, $domain);
        $this->client->del([$key]);
    }

    private function buildKey(string $token, string $domain): string
    {
        return sprintf(
            'password_recovery_token:%s:%s',
            str_replace(':', '_', $domain),
            $token
        );
    }
}

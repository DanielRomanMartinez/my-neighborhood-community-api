<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Redis;

use App\Domain\Form\FormTypeCacheRepository;

final class FormTypeRepositoryRedis extends RedisRepository implements FormTypeCacheRepository
{
    public function findFormTypeParent(string $formTypeId): string
    {
        $key = sprintf('form_type:parent_name:%s', $formTypeId);

        return $this->client->get($key);
    }

    public function setFormTypeParent(
        string $formTypeId,
        string $formTypeParentId,
        string $formTypeParentName
    ): void {
        $key = sprintf('form_type:parent_name:%s', $formTypeId);

        $this->client->set($key, json_encode([
            'id'   => $formTypeParentId,
            'name' => $formTypeParentName,
        ]));
    }
}

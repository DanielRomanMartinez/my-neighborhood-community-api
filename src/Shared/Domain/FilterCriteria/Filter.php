<?php

declare(strict_types=1);

namespace App\Shared\Domain\FilterCriteria;

final class Filter
{
    private array $filter;
    private int $offset;
    private ?int $limit;

    public function __construct(array $filter, int $offset = 0, ?int $limit = null)
    {
        $this->filter = $filter;
        $this->offset = $offset;
        $this->limit = $limit;
    }

    public static function create(array $filter, int $offset = 0, ?int $limit = null): Filter
    {
        return new self($filter, $offset, $limit);
    }

    public static function createFromArray(array $params): self
    {
        $offset = !empty($params['offset']) ? (int) $params['offset'] : 0;
        $limit = !empty($params['limit']) ? (int) $params['limit'] : null;

        unset($params['limit'], $params['offset']);

        $params = array_filter($params, function ($value) {
            return $value !== null;
        });

        return self::create($params, $offset, $limit);
    }

    public function filter(): array
    {
        return $this->filter;
    }

    public function offset(): int
    {
        return $this->offset;
    }

    public function limit(): ?int
    {
        return $this->limit;
    }

    public function get($key, $default = null): ?string
    {
        return $this->has($key) ? $this->filter[$key] : $default;
    }

    public function has($key): bool
    {
        return array_key_exists($key, $this->filter);
    }
}

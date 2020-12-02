<?php

declare(strict_types=1);

namespace App\Shared\Domain\FilterCriteria;

use App\Shared\Domain\FilterCriteria\OrderBy\OrderByValue;

class FilterCriteria
{
    private array $conditions;
    private array $orderBy;
    private ?OrderByValue $orderByValue;
    private int $offset;
    private ?int $limit;

    public function __construct(
        array $conditions = [],
        array $orderBy = [],
        ?OrderByValue $orderByValue = null,
        int $offset = 0,
        ?int $limit = null
    ) {
        $this->conditions = $conditions;
        $this->orderBy = $orderBy;
        $this->orderByValue = $orderByValue;
        $this->offset = $offset;
        $this->limit = $limit;
    }

    public static function create(
        array $conditions = [],
        array $orderBy = [],
        ?OrderByValue $orderByValue = null,
        int $offset = 0,
        ?int $limit = null
    ): FilterCriteria {
        return new self(
            $conditions,
            $orderBy,
            $orderByValue,
            $offset,
            $limit
        );
    }

    public function conditions(): array
    {
        return $this->conditions;
    }

    public function orderBy(): array
    {
        return $this->orderBy;
    }

    public function orderByValue(): ?OrderByValue
    {
        return $this->orderByValue;
    }

    public function offset(): int
    {
        return $this->offset;
    }

    public function limit(): ?int
    {
        return $this->limit;
    }
}

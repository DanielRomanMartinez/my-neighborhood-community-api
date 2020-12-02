<?php

namespace App\Shared\Domain\FilterCriteria;

use App\Shared\Domain\Collection;
use ArrayIterator;
use IteratorAggregate;
use JsonSerializable;

final class FilterCriteriaResult implements IteratorAggregate, JsonSerializable
{
    private Collection $collection;
    private int $total;

    public function __construct(Collection $collection, int $total = 0)
    {
        $this->collection = $collection;
        $this->total = $total;
    }

    public static function create(Collection $collection, int $total): FilterCriteriaResult
    {
        return new self($collection, $total);
    }

    public function collection(): Collection
    {
        return $this->collection;
    }

    public function total(): int
    {
        return $this->total;
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->collection->items());
    }

    public function toArray(): array
    {
        return [
            'total' => $this->total,
            'items' => $this->collection->items(),
        ];
    }

    public function jsonSerialize(): string
    {
        return json_encode($this->toArray());
    }
}

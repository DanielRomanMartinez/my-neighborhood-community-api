<?php

declare(strict_types=1);

namespace App\Shared\Domain\FilterCriteria\Condition;

final class FilterConditionJson extends AbstractFilterCondition
{
    const JSON_EXTRACT = 'JSON_EXTRACT';

    private string $column;

    public function __construct(string $column, string $field, $value)
    {
        $this->column = $column;

        parent::__construct($field, $value, self::JSON_EXTRACT);
    }

    public static function create(string $column, string $field, $value): FilterConditionJson
    {
        return new self($column, $field, $value);
    }

    public function column(): string
    {
        return $this->column;
    }
}

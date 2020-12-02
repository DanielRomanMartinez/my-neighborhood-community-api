<?php

declare(strict_types=1);

namespace App\Shared\Domain\FilterCriteria\Condition;

final class FilterCondition extends AbstractFilterCondition
{
    const EQUAL = '=';
    const NOT_EQUAL = '!=';
    const GREATER = '>';
    const GREATER_OR_EQUAL = '>=';
    const LESS = '<';
    const LESS_OR_EQUAL = '<=';
    const LIKE = 'LIKE';
    const NOT_LIKE = 'NOT LIKE';
    const IS_NULL = 'IS NULL';
    const IS_NOT_NULL = 'IS NOT NULL';
    const IN = 'IN';
    const NOT_IN = 'NOT IN';

    public function __construct(string $field, $value, string $operator = self::EQUAL)
    {
        parent::__construct($field, $value, $operator);
    }

    public static function create(string $field, $value, string $operator = self::EQUAL): FilterCondition
    {
        return new self($field, $value, $operator);
    }
}

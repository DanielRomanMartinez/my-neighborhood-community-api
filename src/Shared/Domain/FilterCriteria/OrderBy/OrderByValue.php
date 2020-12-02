<?php

declare(strict_types=1);

namespace App\Shared\Domain\FilterCriteria\OrderBy;

final class OrderByValue
{
    const ASC = 'ASC';
    const DESC = 'DESC';

    private string $field;
    private $value;

    public function __construct(string $field, $value)
    {
        $this->field = $field;
        $this->value = $value;
    }

    public static function create(string $field, $value): OrderByValue
    {
        return new self($field, $value);
    }

    public function field(): string
    {
        return $this->field;
    }

    public function value()
    {
        return $this->value;
    }
}

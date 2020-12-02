<?php

declare(strict_types=1);

namespace App\Shared\Domain\FilterCriteria\OrderBy;

final class OrderBy
{
    const ASC = 'ASC';
    const DESC = 'DESC';

    private string $field;
    private string $dir;

    public function __construct(string $field, string $dir = self::ASC)
    {
        $this->field = $field;
        $this->dir = $dir;
    }

    public static function create(string $field, string $dir = self::ASC): OrderBy
    {
        return new self($field, $dir);
    }

    public function field(): string
    {
        return $this->field;
    }

    public function dir(): string
    {
        return $this->dir;
    }
}

<?php

namespace App\Shared\Domain\FilterCriteria;

interface FilterCriteriaBuilderInterface
{
    public function buildCriteria(Filter $filter): FilterCriteria;
}

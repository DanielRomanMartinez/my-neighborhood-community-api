<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\FilterCriteria;

use App\Shared\Domain\FilterCriteria\Condition\FilterCondition;
use App\Shared\Domain\FilterCriteria\Filter;
use App\Shared\Domain\FilterCriteria\FilterCriteria;
use App\Shared\Domain\FilterCriteria\OrderBy\OrderBy;

final class CustomerFilterCriteriaBuilder extends AbstractDoctrineFilterCriteria
{
    private const ORDER_BY_MAPPING = [
        'createdAt'               => 'c.createdAt',
        'email'                   => 'c.email',
        'subscriptionStatus'      => 's.status',
        'subscriptionTypeId'      => 's.type',
        'subscriptionActivatedAt' => 's.activatedAt',
    ];

    private const DEFAULT_ORDER_BY = [
        [
            'field' => 'c.createdAt',
            'dir'   => OrderBy::DESC,
        ],
    ];

    public function buildCriteria(Filter $filter): FilterCriteria
    {
        if ($filter->has('id')) {
            $this->addCondition(FilterCondition::create('c.id', $this->parseUuid($filter->get('id'))));
        }

        if ($filter->has('email')) {
            $value = '%' . $filter->get('email') . '%';
            $this->addCondition(FilterCondition::create('c.email', $value, FilterCondition::LIKE));
        }

        if ($filter->has('subscriptionStatus')) {
            $this->addCondition(FilterCondition::create('s.status', $filter->get('subscriptionStatus')));
        }

        if ($filter->has('subscriptionTypeId')) {
            $subscriptionTypeId = $this->parseUuid($filter->get('subscriptionTypeId'));
            $this->addCondition(FilterCondition::create('s.type', $subscriptionTypeId));
        }

        $this->addDateRangeCriteria($filter, 'subscriptionActivatedAt', 's.activatedAt');

        $orderBy = $filter->get('orderBy', null);
        if (($orderBy !== null && json_decode($orderBy)) || $orderBy === null) {
            $this->addOrderByJson(
                $filter->get('orderBy', null),
                self::ORDER_BY_MAPPING,
                self::DEFAULT_ORDER_BY
            );
        } else {
            $this->addOrderByString(
                $filter->get('orderBy', null),
                'c',
                'c.createdAt',
                $filter->get('orderDir', OrderBy::DESC)
            );
        }

        return FilterCriteria::create(
            $this->conditions(),
            $this->orderBy(),
            $this->getOrderByValue($filter),
            $filter->offset(),
            $filter->limit() ?? self::DEFAULT_LIMIT
        );
    }
}

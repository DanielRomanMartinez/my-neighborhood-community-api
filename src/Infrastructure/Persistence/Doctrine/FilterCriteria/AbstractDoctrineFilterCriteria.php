<?php

namespace App\Infrastructure\Persistence\Doctrine\FilterCriteria;

use App\Shared\Domain\FilterCriteria\Condition\AbstractFilterCondition;
use App\Shared\Domain\FilterCriteria\Condition\FilterCondition;
use App\Shared\Domain\FilterCriteria\Filter;
use App\Shared\Domain\FilterCriteria\FilterCriteriaBuilderInterface;
use App\Shared\Domain\FilterCriteria\OrderBy\OrderBy;
use App\Shared\Domain\FilterCriteria\OrderBy\OrderByValue;
use App\Shared\Domain\ValueObject\Uuid;
use DateTime;

abstract class AbstractDoctrineFilterCriteria implements FilterCriteriaBuilderInterface
{
    private const INPUT_DATE_FORMAT = 'Y-m-d';
    private const OUTPUT_DATE_FORMAT = 'Y-m-d H:i:s';

    public const DEFAULT_LIMIT = 20;

    private array $conditions;
    private array $orderBy;

    public function __construct(array $conditions = [], array $orderBy = [])
    {
        $this->conditions = $conditions;
        $this->orderBy = $orderBy;
    }

    public function conditions(): array
    {
        return $this->conditions;
    }

    public function orderBy(): array
    {
        return $this->orderBy;
    }

    protected function parseDate(string $dateString, int $hour = 0, int $minute = 0, int $second = 0): ?string
    {
        $date = DateTime::createFromFormat(self::INPUT_DATE_FORMAT, $dateString);

        if (!$date) {
            return null;
        }

        $date->setTime($hour, $minute, $second);

        return $date->format(self::OUTPUT_DATE_FORMAT);
    }

    protected function addCondition(AbstractFilterCondition $condition): void
    {
        array_push($this->conditions, $condition);
    }

    protected function addOrderByJson(
        ?string $orderBy,
        array $mapping,
        array $defaultOrder
    ): void {
        $orders = $this->mapOrderBy($orderBy, $mapping, $defaultOrder);
        foreach ($orders as $order) {
            array_push($this->orderBy, OrderBy::create($order['field'], $order['dir']));
        }
    }

    protected function addOrderByString(
        ?string $orderBy,
        string $orderByAlias,
        string $orderByDefault,
        string $orderDir
    ): void {
        $orderBy = null !== $orderBy ? "{$orderByAlias}.{$orderBy}" : $orderByDefault;
        array_push($this->orderBy, OrderBy::create($orderBy, $orderDir));
    }

    protected function parseUuid(string $readableId): string
    {
        return (new Uuid($readableId))->getBytes();
    }

    protected function addDateRangeCriteria(Filter $filter, string $param, string $field): void
    {
        $paramFrom = $param . 'From';
        $paramTo = $param . 'To';

        if ($filter->has($paramFrom)) {
            $formattedDate = $this->parseDate($filter->get($paramFrom), 0, 0, 0);

            if ($formattedDate) {
                $this->addCondition(
                    FilterCondition::create($field, $formattedDate, FilterCondition::GREATER_OR_EQUAL));
            }
        }

        if ($filter->has($paramTo)) {
            $formattedDate = $this->parseDate($filter->get($paramTo), 23, 59, 59);

            if ($formattedDate) {
                $this->addCondition(
                    FilterCondition::create($field, $formattedDate, FilterCondition::LESS_OR_EQUAL));
            }
        }
    }

    protected function getOrderByValue(Filter $filter): ?OrderByValue
    {
        $orderByValue = $filter->get('orderValue', null);

        if (null !== $orderByValue) {
            $orderValue = json_decode($orderByValue, true);
            $field = array_key_first($orderValue);
            $orderByValue = OrderByValue::create($field, $orderValue[$field]);
        }

        return $orderByValue;
    }

    protected function mapOrderBy(?string $orderBy, array $mapping, array $defaultOrder): array
    {
        if (!$orderBy) {
            return $defaultOrder;
        }

        $orderFields = json_decode($orderBy);
        $order = [];

        foreach ($orderFields as $nameField => $field) {
            array_push($order, [
                'field' => $mapping[$nameField],
                'dir'   => $field->order,
            ]);
        }

        return $order;
    }
}

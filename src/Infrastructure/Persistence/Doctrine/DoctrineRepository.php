<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine;

use App\Shared\Domain\Collection;
use App\Shared\Domain\FilterCriteria\Condition\FilterCondition;
use App\Shared\Domain\FilterCriteria\Condition\FilterConditionJson;
use App\Shared\Domain\FilterCriteria\FilterCriteria;
use App\Shared\Domain\FilterCriteria\FilterCriteriaResult;
use App\Shared\Domain\FilterCriteria\OrderBy\OrderBy;
use App\Shared\Domain\FilterCriteria\OrderBy\OrderByValue;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ObjectRepository;

abstract class DoctrineRepository
{
    protected EntityManagerInterface $entityManager;
    protected ObjectRepository $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository($this->getEntityClassName());
    }

    public function entityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    public function repository($entity): ObjectRepository
    {
        return $this->entityManager->getRepository($entity);
    }

    protected function addQueryCriteria(QueryBuilder $query, FilterCriteria $criteria): QueryBuilder
    {
        $parameters = [];
        $conditions = $criteria->conditions();

        foreach ($conditions as $condition) {
            $exp = '';
            $parameterKey = $this->getParameterKey($condition->field(), $parameters);

            switch ($condition->operator()) {
                case FilterCondition::EQUAL:
                    $exp = $query->expr()->eq($condition->field(), ':' . $parameterKey);
                    $parameters[$parameterKey] = $condition->value();

                    break;
                case FilterCondition::NOT_EQUAL:
                    $exp = $query->expr()->neq($condition->field(), ':' . $parameterKey);
                    $parameters[$parameterKey] = $condition->value();

                    break;
                case FilterCondition::GREATER:
                    $exp = $query->expr()->gt($condition->field(), ':' . $parameterKey);
                    $parameters[$parameterKey] = $condition->value();

                    break;
                case FilterCondition::GREATER_OR_EQUAL:
                    $exp = $query->expr()->gte($condition->field(), ':' . $parameterKey);
                    $parameters[$parameterKey] = $condition->value();

                    break;
                case FilterCondition::LESS:
                    $exp = $query->expr()->lt($condition->field(), ':' . $parameterKey);
                    $parameters[$parameterKey] = $condition->value();

                    break;
                case FilterCondition::LESS_OR_EQUAL:
                    $exp = $query->expr()->lte($condition->field(), ':' . $parameterKey);
                    $parameters[$parameterKey] = $condition->value();

                    break;
                case FilterCondition::LIKE:
                    $exp = $query->expr()->like($condition->field(), ':' . $parameterKey);
                    $parameters[$parameterKey] = $condition->value();

                    break;
                case FilterCondition::NOT_LIKE:
                    $exp = $query->expr()->notLike($condition->field(), ':' . $parameterKey);
                    $parameters[$parameterKey] = $condition->value();

                    break;
                case FilterCondition::IS_NULL:
                    $exp = $query->expr()->isNull($condition->field());

                    break;
                case FilterCondition::IS_NOT_NULL:
                    $exp = $query->expr()->isNotNull($condition->field());

                    break;
                case FilterConditionJson::JSON_EXTRACT:
                    $exp = sprintf('%s(%s, \'$.%s\') = \'%s\'',
                        $condition->operator(),
                        $condition->column(),
                        $parameterKey,
                        $condition->value()
                    );

                    break;
                case FilterCondition::IN:
                    $exp = $query->expr()->in($condition->field(), $condition->value());

                    break;
                case FilterCondition::NOT_IN:
                    $exp = $query->expr()->notIn($condition->field(), $condition->value());

                    break;
            }

            $where = $query->expr()->andX($exp);

            $query->andWhere($where)->setParameters($parameters);
        }

        if (null !== $criteria->orderByValue()) {
            $orderByValue = $criteria->orderByValue();
            $valueToOrder = $orderByValue->value();
            $valueToOrder = is_string($valueToOrder) ? "'{$valueToOrder}'" : $valueToOrder;
            $fieldQuery = sprintf('FIELD(a.%s, %s) as HIDDEN value_field', $orderByValue->field(), $valueToOrder);

            $query
                ->addSelect($fieldQuery)
                ->addOrderBy('value_field', OrderByValue::DESC);
        }

        foreach ($criteria->orderBy() as $orderBy) {
            $orderBy = $this->orderBy($orderBy);
            $query->addOrderBy($orderBy->field(), $orderBy->dir());
        }

        if ($criteria->offset()) {
            $query->setFirstResult($criteria->offset());
        }

        if ($criteria->limit()) {
            $query->setMaxResults($criteria->limit());
        }

        return $query;
    }

    protected function getFilterCriteriaResult(Collection $collection, int $total): FilterCriteriaResult
    {
        return FilterCriteriaResult::create($collection, $total);
    }

    private function getParameterKey(string $fieldName, array $parameters): string
    {
        if (strpos($fieldName, '.')) {
            [$void, $fieldName] = explode('.', $fieldName);
        }

        // Prevent overwriting parameters if we have more than one condition in the same field
        $index = 0;
        $key = $fieldName;

        while (isset($parameters[$key])) {
            $key = $fieldName . $index;
            $index++;
        }

        return $key;
    }

    private function orderBy(OrderBy $orderBy): OrderBy
    {
        return $orderBy;
    }

    protected function persist($entity): void
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    protected function remove($entity): void
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
        $this->entityManager->clear();
    }

    abstract protected function getEntityClassName(): string;
}

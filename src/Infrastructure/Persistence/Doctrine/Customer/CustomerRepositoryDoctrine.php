<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Customer;

use App\Domain\Customer\Customer;
use App\Domain\Customer\CustomerRepository;
use App\Domain\Site\SiteDomain;
use App\Infrastructure\Persistence\Doctrine\DoctrineRepository;
use App\Infrastructure\Persistence\Doctrine\FilterCriteria\CustomerFilterCriteriaBuilder;
use App\Shared\Domain\FilterCriteria\FilterCriteriaBuilderInterface;
use App\Shared\Domain\ValueObject\Uuid;

final class CustomerRepositoryDoctrine extends DoctrineRepository implements CustomerRepository
{
    protected function getEntityClassName(): string
    {
        return Customer::class;
    }

    public function create(Customer $customer): void
    {
        $this->persist($customer);
    }

    public function update(Customer $customer): void
    {
        $this->entityManager->flush();
    }

    public function find(Uuid $id): ?Customer
    {
        return $this->repository->find($id);
    }

    public function findByEmail(string $email): ?Customer
    {
        $queryBuilder = $this->repository->createQueryBuilder('c');

        return $queryBuilder
            ->where('c.email=:email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getFilterCriteriaBuilder(): FilterCriteriaBuilderInterface
    {
        return new CustomerFilterCriteriaBuilder();
    }
}

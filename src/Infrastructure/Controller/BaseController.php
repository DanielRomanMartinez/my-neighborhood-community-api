<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Domain\Shared\AuthenticatedCustomer;
use App\Domain\Shared\AuthenticatedUser;
use App\Domain\Token\Exception\InvalidTokenException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class BaseController extends AbstractController
{
    protected function getCustomerOrNull(): ?AuthenticatedCustomer
    {
        $customer = $this->getUser();

        if (null === $customer || AuthenticatedCustomer::class !== get_class($customer)) {
            return null;
        }

        return $customer;
    }

    protected function getCustomerOrThrowException(): AuthenticatedCustomer
    {
        $customer = $this->getUser();

        if (null === $customer || AuthenticatedCustomer::class !== get_class($customer)) {
            throw new InvalidTokenException();
        }

        return $customer;
    }

    protected function getUserOrThrowException(): AuthenticatedUser
    {
        $user = $this->getUser();

        if (null === $user || AuthenticatedUser::class !== get_class($user)) {
            throw new InvalidTokenException();
        }

        return $user;
    }
}

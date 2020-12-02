<?php

declare(strict_types=1);

namespace App\Application\Customer\FindByEmail;

final class FindByEmailQuery
{
    private string $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public function email(): string
    {
        return $this->email;
    }
}

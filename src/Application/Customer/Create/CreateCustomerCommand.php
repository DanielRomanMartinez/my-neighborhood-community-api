<?php

declare(strict_types=1);

namespace App\Application\Customer\Create;

use DateTime;

final class CreateCustomerCommand
{
    private string $id;
    private string $email;
    private string $password;
    private string $firstName;
    private string $middleName;
    private string $lastName;
    private DateTime $createdAt;
    private DateTime $updatedAt;

    public function __construct(
        string $id,
        string $email,
        string $password,
        string $firstName,
        string $middleName,
        string $lastName,
        DateTime $createdAt,
        DateTime $updatedAt
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->firstName = $firstName;
        $this->middleName = $middleName;
        $this->lastName = $lastName;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function firstName(): string
    {
        return $this->firstName;
    }

    public function middleName(): string
    {
        return $this->middleName;
    }

    public function lastName(): string
    {
        return $this->lastName;
    }

    public function createdAt(): DateTime
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTime
    {
        return $this->updatedAt;
    }
}

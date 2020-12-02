<?php

declare(strict_types=1);

namespace App\Domain\Customer;

use App\Domain\Customer\Event\CustomerWasCreated;
use App\Shared\Domain\AggregateRoot;
use App\Shared\Domain\ValueObject\Uuid;
use DateTime;

class Customer extends AggregateRoot
{
    private Uuid $id;
    private string $readableId;
    private string $email;
    private string $password;
    private ?string $firstName;
    private ?string $middleName;
    private ?string $lastName;
    private ?DateTime $lastLoginAt;
    private DateTime $createdAt;
    private DateTime $updatedAt;

    public function __construct(
        Uuid $id,
        string $email,
        string $password,
        ?string $firstName,
        ?string $middleName,
        ?string $lastName,
        ?DateTime $lastLoginAt,
        DateTime $createdAt,
        DateTime $updatedAt
    ) {
        $this->id = $id;
        $this->readableId = $id->value();
        $this->email = $email;
        $this->password = $password;
        $this->firstName = $firstName;
        $this->middleName = $middleName;
        $this->lastName = $lastName;
        $this->lastLoginAt = $lastLoginAt;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public static function create(
        Uuid $id,
        string $email,
        string $password,
        ?string $firstName,
        ?string $middleName,
        ?string $lastName,
        ?DateTime $lastLoginAt,
        DateTime $createdAt,
        DateTime $updatedAt
    ): self {
        $customer = new self(
            $id,
            $email,
            $password,
            $firstName,
            $middleName,
            $lastName,
            $lastLoginAt,
            $createdAt,
            $updatedAt
        );

        $customer->recordEvent(new CustomerWasCreated($id->value()));

        return $customer;
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function readableId(): string
    {
        return $this->readableId;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function firstName(): ?string
    {
        return $this->firstName;
    }

    public function middleName(): ?string
    {
        return $this->middleName;
    }

    public function lastName(): ?string
    {
        return $this->lastName;
    }

    public function lastLoginAt(): ?DateTime
    {
        return $this->lastLoginAt;
    }

    public function createdAt(): DateTime
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    private function touchTimestamps(): void
    {
        $this->updatedAt = new DateTime();
    }

    public function updateEmail(string $email): void
    {
        $this->email = $email;
        $this->touchTimestamps();
    }

    public function updateFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
        $this->touchTimestamps();
    }
    public function updateMiddleName(?string $middleName): void
    {
        $this->middleName = $middleName;
        $this->touchTimestamps();
    }
    public function updateLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
        $this->touchTimestamps();
    }

    public function updatePassword(string $password): void
    {
        $this->password = $password;
        $this->touchTimestamps();
    }

    public function updateLastLoginAt(DateTime $lastLoginAt): void
    {
        $this->lastLoginAt = $lastLoginAt;
        $this->touchTimestamps();
    }

    public function toArray(): array
    {
        return [
            'id'            => $this->readableId(),
            'email'         => $this->email(),
            'firstName'     => $this->firstName(),
            'middleName'    => $this->middleName(),
            'lastName'      => $this->lastName(),
            'lastLoginAt'   => $this->lastLoginAt(),
            'createdAt'     => $this->createdAt(),
            'updatedAt'     => $this->updatedAt(),
        ];
    }
}

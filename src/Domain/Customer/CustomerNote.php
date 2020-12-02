<?php

declare(strict_types=1);

namespace App\Domain\Customer;

use App\Domain\User\User;
use App\Shared\Domain\ValueObject\Uuid;
use DateTime;

class CustomerNote
{
    private Uuid $id;
    private string $readableId;
    private string $content;
    private DateTime $createdAt;
    private DateTime $updatedAt;
    private Customer $customer;
    private User $user;

    public function __construct(
        Uuid $id,
        string $content,
        DateTime $createdAt,
        DateTime $updatedAt,
        Customer $customer,
        User $user
    ) {
        $this->id = $id;
        $this->readableId = $id->value();
        $this->content = $content;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->customer = $customer;
        $this->user = $user;
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function readableId(): string
    {
        return $this->readableId;
    }

    public function content(): string
    {
        return $this->content;
    }

    public function createdAt(): DateTime
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function customer(): Customer
    {
        return $this->customer;
    }

    public function user(): User
    {
        return $this->user;
    }
}

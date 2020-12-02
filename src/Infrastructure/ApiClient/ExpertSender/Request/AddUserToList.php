<?php

namespace App\Infrastructure\ApiClient\ExpertSender\Request;

use BadMethodCallException;
use App\Infrastructure\ApiClient\ExpertSender\Entities\Property;
use App\Infrastructure\ApiClient\ExpertSender\ExpertSenderEnum;

/**
 * Represents adding subscriber to list request attributes.
 *
 * https://sites.google.com/a/expertsender.com/api-documentation/methods/subscribers/add-subscriber#TOC-Request-data-format
 */
class AddUserToList
{
    private bool $frozen = false;

    private ?int $listId;

    private ?int $id;

    private ?string $email;

    private ?string $firstName;

    private ?string $lastName;

    private ?string $name;

    private ?string $ip;

    private ?string $trackingCode;

    private ?string $vendor;

    private bool $force = false;

    private string $mode = ExpertSenderEnum::MODE_ADD_AND_UPDATE;

    private array $properties = [];

    private ?string $phone;

    private ?string $customSubscriberId;

    public function isValid(): bool
    {
        return null !== $this->email && null !== $this->listId;
    }

    public function isFrozen(): bool
    {
        return $this->frozen;
    }

    public function freeze(): AddUserToList
    {
        if (!$this->isValid()) {
            throw new BadMethodCallException('AddUserToListRequest cannot be frozen when is invalid.');
        }

        $this->frozen = true;

        return $this;
    }

    public function setListId(?int $listId = null): AddUserToList
    {
        $this->exceptionIfFrozen();

        $this->listId = null === $listId ? null : (int) $listId;

        return $this;
    }

    public function getListId(): ?int
    {
        return $this->listId ?? null;
    }

    public function setId(?int $id = null): AddUserToList
    {
        $this->exceptionIfFrozen();

        $this->id = null === $id ? null : (int) $id;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id ?? null;
    }

    public function setEmail(?string $email = null): AddUserToList
    {
        $this->exceptionIfFrozen();

        $this->email = $email;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email ?? null;
    }

    public function setFirstName(?string $firstName = null): AddUserToList
    {
        $this->exceptionIfFrozen();

        $this->firstName = $firstName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName ?? null;
    }

    public function setLastName(?string $lastName = null): AddUserToList
    {
        $this->exceptionIfFrozen();

        $this->lastName = $lastName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName ?? null;
    }

    public function setName(?string $name = null): AddUserToList
    {
        $this->exceptionIfFrozen();

        $this->name = $name;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name ?? null;
    }

    public function setIp(?string $ip = null): AddUserToList
    {
        $this->exceptionIfFrozen();

        $this->ip = $ip;

        return $this;
    }

    public function getIp(): ?string
    {
        return $this->ip ?? null;
    }

    public function setTrackingCode(?string $trackingCode = null): AddUserToList
    {
        $this->exceptionIfFrozen();

        if (strlen($trackingCode) > 20) {
            throw new \InvalidArgumentException('Tracking code is too long, max is 20 characters');
        }

        $this->trackingCode = $trackingCode;

        return $this;
    }

    public function getTrackingCode(): ?string
    {
        return $this->trackingCode ?? null;
    }

    public function setVendor(?string $vendor = null): AddUserToList
    {
        $this->exceptionIfFrozen();

        $this->vendor = $vendor;

        return $this;
    }

    public function getVendor(): ?string
    {
        return $this->vendor ?? null;
    }

    public function setForce(bool $force): AddUserToList
    {
        $this->exceptionIfFrozen();

        if (!is_bool($force)) {
            throw new \InvalidArgumentException();
        }

        $this->force = $force;

        return $this;
    }

    public function getForce(): bool
    {
        return $this->force;
    }

    public function setMode(string $mode): AddUserToList
    {
        $this->exceptionIfFrozen();

        if (!in_array($mode, ExpertSenderEnum::getModes(), true)) {
            throw new \InvalidArgumentException('Invalid mode: ' . $mode);
        }

        $this->mode = $mode;

        return $this;
    }

    public function getMode(): string
    {
        return $this->mode;
    }

    public function setPhone(?string $phone = null): AddUserToList
    {
        $this->exceptionIfFrozen();
        $this->phone = $phone;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone ?? null;
    }

    public function setCustomSubscriberId(?string $customSubscriberId = null): AddUserToList
    {
        $this->exceptionIfFrozen();
        $this->customSubscriberId = $customSubscriberId;

        return $this;
    }

    public function getCustomSubscriberId(): ?string
    {
        return $this->customSubscriberId ?? null;
    }

    public function addProperty(Property $property): AddUserToList
    {
        $this->exceptionIfFrozen();

        $this->properties[] = $property;

        return $this;
    }

    public function setProperties(array $properties): AddUserToList
    {
        $this->exceptionIfFrozen();

        $this->properties = [];

        foreach ($properties as $property) {
            $this->addProperty($property);
        }

        return $this;
    }

    public function getProperties(): array
    {
        return $this->properties;
    }

    private function exceptionIfFrozen()
    {
        if ($this->frozen) {
            throw new BadMethodCallException('Attributes cannot be set when AddUserToListRequest is frozen.');
        }
    }
}

<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use App\Shared\Domain\ValueObject\Exception\InvalidUuidException;
use Ramsey\Uuid\Uuid as RamseyUuid;

final class Uuid
{
    private string $value;

    public function __construct(string $value)
    {
        $this->ensureIsValidUuid($value);

        $this->value = $value;
    }

    public static function random(): self
    {
        return new self(RamseyUuid::uuid4()->toString());
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(Uuid $other): bool
    {
        return $this->value() === $other->value();
    }

    public function __toString(): string
    {
        return $this->value();
    }

    public function getBytes(): string
    {
        return RamseyUuid::fromString($this->value())->getBytes();
    }

    private function ensureIsValidUuid($id): void
    {
        if (!RamseyUuid::isValid($id)) {
            throw new InvalidUuidException($id);
        }
    }
}

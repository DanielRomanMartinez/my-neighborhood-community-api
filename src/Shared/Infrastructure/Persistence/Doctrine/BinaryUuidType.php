<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Doctrine;

use App\Shared\Domain\ValueObject\Uuid;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\BinaryType;
use Ramsey\Uuid\Uuid as RamseyUuid;

final class BinaryUuidType extends BinaryType
{
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return $platform->getBinaryTypeDeclarationSQL(
            [
                'length' => '16',
                'fixed'  => true,
            ]
        );
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Uuid
    {
        if (empty($value)) {
            return null;
        }

        if ($value instanceof Uuid) {
            return $value;
        }

        return new Uuid(RamseyUuid::fromBytes($value)->toString());
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (empty($value)) {
            return null;
        }

        if ($value instanceof Uuid) {
            $bytes = RamseyUuid::fromString($value->value());

            return $bytes->getBytes();
        }

        if (is_string($value) || method_exists($value, 'value')) {
            $bytes = RamseyUuid::fromString((string) $value);

            return $bytes->getBytes();
        }
    }
}

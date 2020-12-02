<?php

declare(strict_types=1);

namespace App\Shared\Domain;

use Assert\Assertion;
use InvalidArgumentException;

final class Assert extends Assertion
{
    public static function arrayOf(string $class, array $items)
    {
        foreach ($items as $item) {
            self::instanceOf($class, $item);
        }
    }

    public static function instanceOf($class, $item)
    {
        if (!$item instanceof $class) {
            throw new InvalidArgumentException(
                sprintf('The object <%s> is not an instance of <%s>', $class, get_class($item))
            );
        }
    }
}

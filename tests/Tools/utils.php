<?php

namespace App\Tests\Tools;

use ReflectionClass;

function getClassConstant(string $className, string $constant)
{
    $reflection = new ReflectionClass($className);

    return $reflection->getConstant($constant);
}

function invokeMethod(&$object, string $methodName, array $parameters = [])
{
    $reflection = new ReflectionClass(get_class($object));
    $method = $reflection->getMethod($methodName);
    $method->setAccessible(true);

    return $method->invokeArgs($object, $parameters);
}

function setObjectPropertiesValues(&$object, array $properties)
{
    $reflection = new ReflectionClass(get_class($object));

    foreach ($properties as $propertyName => $value) {
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);
        $property->setValue($object, $value);
    }
}

function getObjectPropertyValue(&$object, string $propertyName)
{
    $reflection = new ReflectionClass(get_class($object));
    $property = $reflection->getProperty($propertyName);
    $property->setAccessible(true);

    return $property->getValue($object);
}

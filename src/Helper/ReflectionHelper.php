<?php

declare(strict_types=1);

namespace Zenigata\Utility\Helper;

use ReflectionClass;
use RuntimeException;

use function class_exists;
use function sprintf;

/**
 * Provides lightweight instantiation of classes with empty constructors
 * using reflection, typically used when no container is available.
 */
class ReflectionHelper
{
    /**
     * Prevent instantiation.
     */
    private function __construct() {}

    /**
     * Attempts to instantiate a class with an empty constructor.
     *
     * @template T of object
     * @param class-string<T> $class Fully-qualified class name to instantiate.
     *
     * @return T The class instance.
     * @throws RuntimeException If the class cannot be instantiated or requires constructor parameters.
     */
    public static function instantiate(string $class): object
    {
        if (!class_exists($class)) {
            throw new RuntimeException(sprintf(
                "Cannot instantiate '%s': class not found.",
                $class
            ));
        }

        $reflection = new ReflectionClass($class);

        if (!$reflection->isInstantiable()) {
            throw new RuntimeException(sprintf(
                "Cannot instantiate '%s': class is not instantiable. Ensure the target is a concrete class.",
                $class
            ));
        }

        $constructor = $reflection->getConstructor();
        $requiredParameters = $constructor->getNumberOfRequiredParameters();

        if ($constructor === null || $requiredParameters === 0) {
            return new $class();
        }

        throw new RuntimeException(sprintf(
            "Cannot instantiate '%s': constructor defines %d required parameter(s).",
            $class,
            $requiredParameters
        ));
    }
}

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
 * 
 * Example:
 * 
 * ```php
 * use Zenigata\Utility\Helper\ReflectionResolver;
 * 
 * final class MyService
 * {
 *     public function __construct() {}
 * }
 * 
 * $instance = ReflectionResolver::resolve(MyService::class);
 * 
 * var_dump($instance instanceof MyService); // true
 * ```
 */
class ReflectionResolver
{
    /**
     * Prevent instantiation.
     */
    private function __construct() {}

    /**
     * Attempts to instantiate a class with an empty constructor.
     *
     * @template T of object
     * @param class-string<T> $class Fully-qualified class name to resolve.
     *
     * @return T The class instance.
     * @throws RuntimeException If the class cannot be instantiated or requires constructor parameters.
     */
    public static function resolve(string $class): object
    {
        if (!class_exists($class)) {
            throw new RuntimeException(sprintf(
                "Cannot resolve '%s': class not found.",
                $class
            ));
        }

        $reflection = new ReflectionClass($class);

        if (!$reflection->isInstantiable()) {
            throw new RuntimeException(sprintf(
                "Cannot resolve '%s': class is not instantiable. Ensure the target is a concrete class.",
                $class
            ));
        }

        $constructor = $reflection->getConstructor();
        $parameters  = $constructor->getNumberOfRequiredParameters();

        if ($constructor === null || $parameters === 0) {
            return new $class();
        }

        throw new RuntimeException(sprintf(
            "Cannot resolve '%s': constructor defines %d required parameter(s).",
            $class,
            $parameters
        ));
    }
}

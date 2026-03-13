<?php

declare(strict_types=1);

namespace Zenigata\Utility\Test\Unit\Helper;

use RuntimeException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Zenigata\Utility\Helper\ReflectionResolver;
use Zenigata\Utility\Test\AbstractClass;
use Zenigata\Utility\Test\InstantiableClass;
use Zenigata\Utility\Test\NonInstantiableClass;

/**
 * Unit test for {@see Zenigata\Utility\Helper\ReflectionResolver} utility.
 *
 * Covered cases:
 *
 * - Instantiates a simple class with no constructor.
 * - Instantiates a class with an empty constructor.
 * - Throws an exception when the class does not exist.
 * - Throws an exception when the class is abstract or not instantiable.
 * - Throws an exception when the class has required constructor parameters.
 */
#[CoversClass(ReflectionResolver::class)]
final class ReflectionResolverTest extends TestCase
{
    public function testResolveClassWithoutConstructor(): void
    {
        $instance = ReflectionResolver::resolve(InstantiableClass::class);

        $this->assertInstanceOf(InstantiableClass::class, $instance);
    }

    public function testResolveClassWithEmptyConstructor(): void
    {
        $instance = ReflectionResolver::resolve(InstantiableClass::class);

        $this->assertInstanceOf(InstantiableClass::class, $instance);
    }

    public function testThrowIfClassDoesNotExist(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("Cannot resolve 'NonExistentClass': class not found.");

        ReflectionResolver::resolve('NonExistentClass');
    }

    public function testThrowIfClassIsAbstract(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageMatches("/is not instantiable/");

        ReflectionResolver::resolve(AbstractClass::class);
    }

    public function testThrowIfConstructorRequiresParameters(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('constructor defines 1 required parameter(s).');

        ReflectionResolver::resolve(NonInstantiableClass::class);
    }
}

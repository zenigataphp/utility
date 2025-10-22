<?php

declare(strict_types=1);

namespace Zenigata\Utility\Test\Unit;

use RuntimeException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Zenigata\Utility\ReflectionHelper;
use Zenigata\Utility\Test\AbstractClass;
use Zenigata\Utility\Test\InstantiableClass;
use Zenigata\Utility\Test\NonInstantiableClass;

/**
 * Unit test for {@see ReflectionHelper} utility.
 *
 * Covered cases:
 *
 * - Instantiates a simple class with no constructor.
 * - Instantiates a class with an empty constructor.
 * - Throws an exception when the class does not exist.
 * - Throws an exception when the class is abstract or not instantiable.
 * - Throws an exception when the class has required constructor parameters.
 */
#[CoversClass(ReflectionHelper::class)]
final class ReflectionHelperTest extends TestCase
{
    public function testInstantiatesClassWithoutConstructor(): void
    {
        $instance = ReflectionHelper::instantiate(InstantiableClass::class);

        $this->assertInstanceOf(InstantiableClass::class, $instance);
    }

    public function testInstantiatesClassWithEmptyConstructor(): void
    {
        $instance = ReflectionHelper::instantiate(InstantiableClass::class);

        $this->assertInstanceOf(InstantiableClass::class, $instance);
    }

    public function testThrowsIfClassDoesNotExist(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("Cannot instantiate 'NonExistentClass': class not found.");

        ReflectionHelper::instantiate('NonExistentClass');
    }

    public function testThrowsIfClassIsAbstract(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageMatches("/is not instantiable/");

        ReflectionHelper::instantiate(AbstractClass::class);
    }

    public function testThrowsIfConstructorRequiresParameters(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('constructor defines 1 required parameter(s).');

        ReflectionHelper::instantiate(NonInstantiableClass::class);
    }
}

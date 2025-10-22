<?php

declare(strict_types=1);

namespace Zenigata\Utility\Test\Unit\Psr;

use LogicException;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Psr\Container\NotFoundExceptionInterface;
use Zenigata\Utility\Psr\FakeContainer;

/**
 * Unit test for {@see FakeContainer}.
 * 
 * Covered cases:
 *
 * - Default state.
 * - Return stored services when present.
 * - Throw a {@see NotFoundExceptionInterface} when a service is missing.
 * - Throw if initialized injecting a non associative array of entries.
 * - Correctly handle `null` as a stored value.
 * - Has and set methods.
 */
#[CoversClass(FakeContainer::class)]
final class FakeContainerTest extends TestCase
{
    public function testDefaults(): void
    {
        $container = new FakeContainer();

        $this->assertEmpty($container->all());
    }

    public function testReturnServiceIfExists(): void
    {
        $container = new FakeContainer(['foo' => 'bar']);

        $this->assertTrue($container->has('foo'));
        $this->assertSame('bar', $container->get('foo'));
    }

    public function testThrowIfServiceIsMissing(): void
    {
        $this->expectException(NotFoundExceptionInterface::class);
        $this->expectExceptionMessage("Service 'missing' not found.");

        $container = new FakeContainer();
        $container->get('missing');
    }

    public function testThrowIfEntriesIsNotAssociativeArray(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage("requires an associative array of entries");

        new FakeContainer(['foo', 'bar']);
    }

    public function testNullServiceIsValid(): void
    {
        $this->expectException(NotFoundExceptionInterface::class);

        $container = new FakeContainer(['nullable' => null]);

        $this->assertFalse($container->has('nullable'));

        $container->get('nullable');
    }

    public function testHasService(): void
    {
        $container = new FakeContainer();

        $this->assertFalse($container->has('service'));
    }

    public function testSetService(): void
    {
        $container = new FakeContainer();
        $container->set('service', 42);

        $this->assertTrue($container->has('service'));
        $this->assertCount(1, $container->all());
    }
}
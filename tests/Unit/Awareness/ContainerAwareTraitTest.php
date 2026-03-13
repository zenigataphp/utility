<?php

declare(strict_types=1);

namespace Zenigata\Utility\Test\Unit\Awareness;

use LogicException;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversTrait;
use Zenigata\Utility\Awareness\ContainerAwareInterface;
use Zenigata\Utility\Awareness\ContainerAwareTrait;
use Zenigata\Utility\Testing\FakeContainer;

/**
 * Unit test for {@see Zenigata\Utility\Awareness\ContainerAwareTraitTest} utility trait.
 *
 * Covered cases:
 * 
 * - Verify that the container is accessible, if available.
 * - Throw when trying to access the container without setting it. 
 */
#[CoversTrait(ContainerAwareTrait::class)]
final class ContainerAwareTraitTest extends TestCase
{
    private ContainerAwareInterface $instance;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->instance = new class implements ContainerAwareInterface {
            use ContainerAwareTrait;
        };
    }

    public function testSetContainer(): void
    {
        $container = new FakeContainer();

        $this->instance->setContainer($container);

        $this->assertSame($container, $this->instance->getContainer());
    }

    public function testThrowIfContainerNotSet(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Container not set');

        $this->instance->getContainer();
    }
}
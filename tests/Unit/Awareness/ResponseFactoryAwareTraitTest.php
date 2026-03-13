<?php

declare(strict_types=1);

namespace Zenigata\Utility\Test\Unit\Awareness;

use LogicException;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversTrait;
use Zenigata\Utility\Awareness\ResponseFactoryAwareInterface;
use Zenigata\Utility\Awareness\ResponseFactoryAwareTrait;

/**
 * Unit test for {@see Zenigata\Utility\Awareness\ResponseFactoryAwareTrait} utility trait.
 *
 * Covered cases:
 * 
 * - Verify that the response factory is accessible, if available
 * - Throw when trying to access the response factory without setting it.
 */
#[CoversTrait(ResponseFactoryAwareTrait::class)]
final class ResponseFactoryAwareTraitTest extends TestCase
{
    private ResponseFactoryAwareInterface $instance;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->instance = new class implements ResponseFactoryAwareInterface {
            use ResponseFactoryAwareTrait;
        };
    }

    public function testSetResponseFactory(): void
    {
        $factory = new Psr17Factory();

        $this->instance->setResponseFactory($factory);

        $this->assertSame($factory, $this->instance->getResponseFactory());
    }

    public function testThrowIfResponseFactoryNotSet(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Response factory not set');

        $this->instance->getResponseFactory();
    }
}
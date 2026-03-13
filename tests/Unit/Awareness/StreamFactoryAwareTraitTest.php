<?php

declare(strict_types=1);

namespace Zenigata\Utility\Test\Unit\Awareness;

use LogicException;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversTrait;
use Zenigata\Utility\Awareness\StreamFactoryAwareInterface;
use Zenigata\Utility\Awareness\StreamFactoryAwareTrait;

/**
 * Unit test for {@see Zenigata\Utility\Awareness\StreamFactoryAwareTrait} utility trait.
 *
 * Covered cases:
 * 
 * - Verify that the stream factory is accessible, if available
 * - Throw when trying to access the stream factory without setting it.
 */
#[CoversTrait(StreamFactoryAwareTrait::class)]
final class StreamFactoryAwareTraitTest extends TestCase
{
    private StreamFactoryAwareInterface $instance;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->instance = new class implements StreamFactoryAwareInterface {
            use StreamFactoryAwareTrait;
        };
    }

    public function testSetStreamFactory(): void
    {
        $factory = new Psr17Factory();

        $this->instance->setStreamFactory($factory);

        $this->assertSame($factory, $this->instance->getStreamFactory());
    }

    public function testThrowIfStreamFactoryNotSet(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Stream factory not set');

        $this->instance->getStreamFactory();
    }
}
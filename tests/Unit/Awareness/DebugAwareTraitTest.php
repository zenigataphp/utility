<?php

declare(strict_types=1);

namespace Zenigata\Utility\Test\Unit\Awareness;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversTrait;
use Zenigata\Utility\Awareness\DebugAwareInterface;
use Zenigata\Utility\Awareness\DebugAwareTrait;

/**
 * Unit test for {@see Zenigata\Utility\Awareness\DebugAwareTrait} utility trait.
 *
 * Covered cases:
 * 
 * - Default state has debug disabled.
 * - Verify that the status updates correctly.
 */
#[CoversTrait(DebugAwareTrait::class)]
final class DebugAwareTraitTest extends TestCase
{
    private DebugAwareInterface $instance;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->instance = new class implements DebugAwareInterface {
            use DebugAwareTrait;
        };
    }

    public function testDefaults(): void
    {
        $this->assertSame(false, $this->instance->isDebugEnabled());
    }

    public function testSetDebug(): void
    {
        $this->instance->setDebug(true);

        $this->assertSame(true, $this->instance->isDebugEnabled());
    }
}
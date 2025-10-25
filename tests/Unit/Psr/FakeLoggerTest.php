<?php

declare(strict_types=1);

namespace Zenigata\Utility\Test\Unit\Psr;

use Stringable;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Zenigata\Utility\Psr\FakeLogger;

/**
 * Unit test for {@see FakeLogger}.
 * 
 * Covered cases:
 *
 * - Default state.
 * - Log messages at various levels with string and {@see Stringable} inputs.
 * - Format log entries with level, message, and context data.
 * - Handle empty context arrays.
 * - Track recorded messages.
 */
#[CoversClass(FakeLogger::class)]
final class FakeLoggerTest extends TestCase
{
    public function testDefaults(): void
    {
        $logger = new FakeLogger();

        $this->assertEmpty($logger->all());
    }

    public function testLogMessageToOutput(): void
    {
        $logger = new FakeLogger();
        $logger->info('info message', ['foo' => 'bar']);

        $logs = $logger->all();

        $this->assertCount(1, $logs);
        $this->assertStringContainsString('[INFO]', $logs[0]);
        $this->assertStringContainsString('info message', $logs[0]);
        $this->assertStringContainsString('"foo":"bar"', $logs[0]);
    }

    public function testLogMultipleLevels(): void
    {
        $logger = new FakeLogger();
        $logger->debug('debug message');
        $logger->warning('warning message');

        $logs = $logger->all();

        $this->assertStringContainsString('[DEBUG]', $logs[0]);
        $this->assertStringContainsString('[WARNING]', $logs[1]);
    }

    public function testLogStringableObject(): void
    {
        $message = new class() implements Stringable {
            public function __toString(): string
            {
                return 'stringable message';
            }
        };
        
        $logger = new FakeLogger();
        $logger->error($message);

        $this->assertStringContainsString('stringable message', $logger->all()[0]);
    }

    public function testLogWithEmptyContext(): void
    {
        $logger = new FakeLogger();
        $logger->alert('alert with no context', []);

        $logs = $logger->all();

        $this->assertStringContainsString('[ALERT]', $logs[0]);
        $this->assertStringContainsString('[]', $logs[0]);
    }
}
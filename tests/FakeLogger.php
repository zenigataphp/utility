<?php

declare(strict_types=1);

namespace Zenigata\Utility\Test;

use Stringable;
use Psr\Log\AbstractLogger;

use function json_encode;
use function sprintf;
use function strtoupper;

/**
 * Fake implementation of {@see LoggerInterface} (PSR-3).
 *
 * Provides an in-memory simulation of a PSR-3 logging backend.
 */
class FakeLogger extends AbstractLogger
{
    /**
     * Stack of collected logs, stored as formatted strings.
     *
     * @var string[]
     */
    public array $output = [];

    /**
     * Simulates logging a message.
     *
     * @param mixed             $level   Log level (e.g "info", "error").
     * @param string|Stringable $message The log message.
     * @param array             $context Additional context data for the log entry.
     * 
     * @return void
     */
    public function log($level, string|Stringable $message, array $context = []): void
    {
        $this->output[] = sprintf(
            "[%s] %s %s",
            strtoupper($level),
            (string) $message,
            json_encode($context)
        );
    }
}
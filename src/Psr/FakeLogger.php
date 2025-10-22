<?php

declare(strict_types=1);

namespace Zenigata\Utility\Psr;

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
    public array $messages = [];

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
        $this->messages[] = sprintf(
            "[%s] %s %s",
            strtoupper($level),
            (string) $message,
            json_encode($context)
        );
    }

    /**
     * Returns the logged messages.
     * 
     * @var string[]
     */
    public function all(): array
    {
        return $this->messages;
    }
}
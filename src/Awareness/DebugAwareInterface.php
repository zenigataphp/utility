<?php

declare(strict_types=1);

namespace Zenigata\Utility\Awareness;

/**
 * Provides debug state awareness.
 */
interface DebugAwareInterface
{
    /**
     * Enable or disable debug mode.
     * 
     * @param bool $debug
     */
    public function setDebug(bool $debug): void;

    /**
     * Indicates if debug mode is enabled.
     * 
     * @return bool
     */
    public function isDebugEnabled(): bool;
}
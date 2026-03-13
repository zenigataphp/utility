<?php

declare(strict_types=1);

namespace Zenigata\Utility\Awareness;

/**
 * Implementation of {@see Zenigata\Utility\Awareness\DebugAwareInterface}.
 */
trait DebugAwareTrait
{
    /**
     * Determines if debug mode is enabled.
     */
    protected bool $debug = false;

    /**
     * @inheritDoc
     */
    public function setDebug(bool $debug): void
    {
        $this->debug = $debug;
    }

    /**
     * @inheritDoc
     */
    public function isDebugEnabled(): bool
    {
        return $this->debug;
    }
}
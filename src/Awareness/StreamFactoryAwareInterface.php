<?php

declare(strict_types=1);

namespace Zenigata\Utility\Awareness;

use LogicException;
use Psr\Http\Message\StreamFactoryInterface;

/**
 * Provides stream factory awareness.
 */
interface StreamFactoryAwareInterface
{
    /**
     * Sets the stream factory instance.
     * 
     * @param StreamFactoryInterface $factory The stream factory instance.
     */
    public function setStreamFactory(StreamFactoryInterface $factory): void;

    /**
     * Returns the stream factory instance.
     *
     * @return StreamFactoryInterface The stream factory instance.
     * @throws LogicException If the stream factory has not been set.
     */
    public function getStreamFactory(): StreamFactoryInterface;
}
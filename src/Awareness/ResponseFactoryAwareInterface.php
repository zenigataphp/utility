<?php

declare(strict_types=1);

namespace Zenigata\Utility\Awareness;

use LogicException;
use Psr\Http\Message\ResponseFactoryInterface;

/**
 * Provides response factory awareness.
 */
interface ResponseFactoryAwareInterface
{
    /**
     * Sets the response factory instance.
     * 
     * @param ResponseFactoryInterface $factory The response factory instance.
     */
    public function setResponseFactory(ResponseFactoryInterface $factory): void;

    /**
     * Returns the response factory instance.
     *
     * @return ResponseFactoryInterface The response factory instance.
     * @throws LogicException If the response factory has not been set.
     */
    public function getResponseFactory(): ResponseFactoryInterface;
}
<?php

declare(strict_types=1);

namespace Zenigata\Utility\Awareness;

use LogicException;
use Psr\Container\ContainerInterface;

/**
 * Provides container awareness.
 */
interface ContainerAwareInterface
{
    /**
     * Sets the container instance.
     * 
     * @param ContainerInterface $container The container instance.
     */
    public function setContainer(ContainerInterface $container): void;

    /**
     * Returns the container instance.
     *
     * @return ContainerInterface The container instance.
     * @throws LogicException If the container has not been set.
     */
    public function getContainer(): ContainerInterface;
}
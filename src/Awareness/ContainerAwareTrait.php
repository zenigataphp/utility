<?php

declare(strict_types=1);

namespace Zenigata\Utility\Awareness;

use LogicException;
use Psr\Container\ContainerInterface;

use function sprintf;

/**
 * Implementation of {@see Zenigata\Utility\Awareness\ContainerAwareInterface}.
 */
trait ContainerAwareTrait
{
    /**
     * The container instance.
     */
    protected ?ContainerInterface $container = null;

    /**
     * @inheritDoc
     */
    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    /**
     * @inheritDoc
     */
    public function getContainer(): ContainerInterface
    {
        if ($this->container === null) {
            throw new LogicException(sprintf(
                'Container not set in "%s".',
                static::class
            ));
        }

        return $this->container;
    }
}
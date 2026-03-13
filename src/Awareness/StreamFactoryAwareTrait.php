<?php

declare(strict_types=1);

namespace Zenigata\Utility\Awareness;

use LogicException;
use Psr\Http\Message\StreamFactoryInterface;

use function sprintf;

/**
 * Implementation of {@see Zenigata\Utility\Awareness\StreamFactoryAwareInterface}.
 */
trait StreamFactoryAwareTrait
{
    /**
     * The stream factory instance.
     */
    protected ?StreamFactoryInterface $streamFactory = null;

    /**
     * @inheritDoc
     */
    public function setStreamFactory(StreamFactoryInterface $factory): void
    {
        $this->streamFactory = $factory;
    }

    /**
     * @inheritDoc
     */
    public function getStreamFactory(): StreamFactoryInterface
    {
        if ($this->streamFactory === null) {
            throw new LogicException(sprintf(
                'Stream factory not set in "%s".',
                static::class
            ));
        }

        return $this->streamFactory;
    }
}
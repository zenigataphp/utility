<?php

declare(strict_types=1);

namespace Zenigata\Utility\Awareness;

use LogicException;
use Psr\Http\Message\ResponseFactoryInterface;

use function sprintf;

/**
 * Implementation of {@see Zenigata\Utility\Awareness\ResponseFactoryAwareInterface}.
 */
trait ResponseFactoryAwareTrait
{
    /**
     * The response factory instance.
     */
    protected ?ResponseFactoryInterface $responseFactory = null;

    /**
     * @inheritDoc
     */
    public function setResponseFactory(ResponseFactoryInterface $factory): void
    {
        $this->responseFactory = $factory;
    }

    /**
     * @inheritDoc
     */
    public function getResponseFactory(): ResponseFactoryInterface
    {
        if ($this->responseFactory === null) {
            throw new LogicException(sprintf(
                'Response factory not set in "%s".',
                static::class
            ));
        }

        return $this->responseFactory;
    }
}
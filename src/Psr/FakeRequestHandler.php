<?php

declare(strict_types=1);

namespace Zenigata\Utility\Psr;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Fake implementation of {@see Psr\Http\Server\RequestHandlerInterface} (PSR-15).
 *
 * Configurable request handler for testing.
 */
class FakeRequestHandler implements RequestHandlerInterface
{
    /**
     * User-defined callback invoked when the handler is processed.
     *
     * @var callable|null
     */
    private $callable = null;

    /**
     * Creates a new fake request handler instance.
     *
     * @param ResponseInterface $response  The response to return when handling a request.
     * @param callable|null     $callable  Optional callback invoked during processing.
     */
    public function __construct(
        private ResponseInterface $response,
        ?callable $callable = null,
    ) {
        $this->callable = $callable;
    }

    /**
     * @inheritDoc
     * 
     * Invokes the callback during the process, if provided.
     *
     * @return ResponseInterface The response configured in the constructor.
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if ($this->callable !== null) {
            ($this->callable)($request);
        }

        return $this->response;
    }
}

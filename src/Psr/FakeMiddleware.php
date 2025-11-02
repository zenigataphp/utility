<?php

declare(strict_types=1);

namespace Zenigata\Utility\Psr;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Fake implementation of {@see Psr\Http\Server\MiddlewareInterface} (PSR-15).
 *
 * Configurable middleware for testing.
 */
class FakeMiddleware implements MiddlewareInterface
{
    /**
     * User-defined callback invoked when the middleware is processed.
     *
     * @var callable|null
     */
    private $callable = null;

    /**
     * Creates a new fake middleware instance.
     *
     * @param ResponseInterface|null $response  Optional response to return instead of delegating to the handler.
     * @param callable|null          $callable  Optional callback invoked during processing.
     */
    public function __construct(
        private ?ResponseInterface $response = null,
        ?callable $callable = null,
    ) {
        $this->callable = $callable;
    }

    /**
     * @inheritDoc
     * 
     * Invokes the callback during the process, if provided.
     *
     * @return ResponseInterface The response from the next handler or the configured response.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->callable !== null) {
            ($this->callable)($request, $handler);
        }

        return $this->response !== null
            ? $this->response
            : $handler->handle($request);
    }
}

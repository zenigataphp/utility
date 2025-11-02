<?php

declare(strict_types=1);

namespace Zenigata\Utility\Psr;

use Throwable;
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
     * @param Throwable|null         $exception Optional exception to throw instead of returning a response.
     */
    public function __construct(
        private ?ResponseInterface $response = null,
        ?callable $callable = null,
        private ?Throwable $exception = null,
    ) {
        $this->callable = $callable;
    }

    /**
     * @inheritDoc
     * 
     * Invokes the callback if provided, and optionally returns or throws
     * the configured response/exception.
     *
     * @return ResponseInterface The response from the next handler or the configured response.
     * @throws Throwable If a throwable was configured in the constructor.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->callable !== null) {
            ($this->callable)($request, $handler);
        }

        if ($this->exception !== null) {
            throw $this->exception;
        }

        return $this->response !== null
            ? $this->response
            : $handler->handle($request);
    }
}

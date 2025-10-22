<?php

declare(strict_types=1);

namespace Zenigata\Utility\Psr;

use SplStack;
use Throwable;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Fake implementation of {@see MiddlewareInterface} (PSR-15).
 *
 * Provides a configurable middleware for testing, supporting preset responses,
 * exceptions, and overridable hooks for custom behavior.
 */
class FakeMiddleware implements MiddlewareInterface
{
    /**
     * Last request received by this instance, or null if not executed yet.
     */
    private ?ServerRequestInterface $request = null;

    /**
     * Last handler received by this instance, or null if not executed yet.
     */
    private ?RequestHandlerInterface $handler = null;

    /**
     * Creates a new fake middleware instance.
     *
     * @param ResponseInterface|null $response    Optional response to return instead of delegating to the handler.
     * @param Throwable|null         $exception   Optional exception to throw when invoked.
     * @param SplStack|null          $invokeStack Shared stack to record invocation order of middleware.
     * @param string                 $name        Human-readable label identifying this middleware in the stack.
     */
    public function __construct(
        private ?ResponseInterface $response = null,
        private ?Throwable $exception = null,
        private ?SplStack $invokeStack = null,
        public string $name = 'middleware',
    ) {}

    /**
     * {@inheritDoc}
     * 
     * Invokes the middleware, pushing its name into the provided invocation stack
     * and optionally returning or throwing the configured response/exception.
     *
     * @return ResponseInterface The response from the next handler or the configured response.
     * @throws Throwable If a throwable was configured in the constructor.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->invokeStack?->push($this->name);

        $this->request = $request;
        $this->handler = $handler;

        if ($this->exception !== null) {
            throw $this->exception;
        }

        return $this->response !== null
            ? $this->response
            : $handler->handle($request);
    }

    /**
     * Returns the name assigned to this middleware instance.
     *
     * @return string The middleware name.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns the last request received by this middleware.
     *
     * @return ServerRequestInterface|null The captured request or null if never invoked.
     */
    public function getRequest(): ?ServerRequestInterface
    {
        return $this->request;
    }

    /**
     * Returns the last request handler passed to this middleware.
     *
     * @return RequestHandlerInterface|null The captured handler or null if never invoked.
     */
    public function getHandler(): ?RequestHandlerInterface
    {
        return $this->handler;
    }
}

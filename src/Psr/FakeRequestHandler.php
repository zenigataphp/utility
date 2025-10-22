<?php

declare(strict_types=1);

namespace Zenigata\Utility\Psr;

use SplStack;
use Throwable;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Fake implementation of {@see RequestHandlerInterface} (PSR-15).
 *
 * Provides a configurable request handler for testing, supporting preset responses,
 * exceptions, and overridable hooks for custom behavior.
 */
class FakeRequestHandler implements RequestHandlerInterface
{
    /**
     * Last request handled by this instance, or null if not executed yet.
     */
    private ?ServerRequestInterface $request = null;

    /**
     * Creates a new fake request handler instance.
     *
     * @param ResponseInterface $response    The response to return when handling a request.
     * @param Throwable|null    $exception   Optional exception to throw instead of returning a response.
     * @param SplStack|null     $invokeStack Shared stack to record invocation order of handlers.
     * @param string            $name        Human-readable label identifying this handler in the stack.
     */
    public function __construct(
        private ResponseInterface $response,
        private ?Throwable $exception = null,
        private ?SplStack $invokeStack = null,
        private string $name = 'handler',
    ) {}

    /**
     * {@inheritDoc}
     * 
     * Handles the request, pushing its name into the provided invocation stack
     * and optionally returning or throwing the configured response/exception.
     *
     * @return ResponseInterface The response configured in the constructor.
     * @throws Throwable If a throwable was configured in the constructor.
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->invokeStack?->push($this->name);

        $this->request = $request;

        if ($this->exception !== null) {
            throw $this->exception;
        }

        return $this->response;
    }

    /**
     * Returns the name assigned to this handler instance.
     *
     * @return string The handler name.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns the last request handled by this instance.
     *
     * @return ServerRequestInterface|null The captured request or null if never invoked.
     */
    public function getRequest(): ?ServerRequestInterface
    {
        return $this->request;
    }
}

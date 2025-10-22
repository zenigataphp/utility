<?php

declare(strict_types=1);

namespace Zenigata\Utility\Test\Unit\Psr;

use Nyholm\Psr7\Response;
use Nyholm\Psr7\ServerRequest;
use RuntimeException;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Zenigata\Utility\Psr\FakeMiddleware;
use Zenigata\Utility\Psr\FakeRequestHandler;

/**
 * Unit test for {@see FakeMiddleware}.
 * 
 * Covered cases:
 *
 * - Default state.
 * - Delegation of request processing to the provided request handler.
 * - Return a default fake response when no custom response is provided.
 * - Return a custom response when injected via the constructor.
 * - Throw a preconfigured exception instead of returning a response.
 */
#[CoversClass(FakeMiddleware::class)]
final class FakeMiddlewareTest extends TestCase
{
    private ServerRequestInterface $request;

    private ResponseInterface $response;

    private FakeRequestHandler $handler;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $this->request = new ServerRequest('GET', '/');
        $this->response = new Response();
        $this->handler = new FakeRequestHandler($this->response);
    }

    public function testDefaults(): void
    {
        $middleware = new FakeMiddleware();

        $this->assertInstanceOf(MiddlewareInterface::class, $middleware);
        $this->assertSame('middleware', $middleware->getName());
        $this->assertNull($middleware->getRequest());
        $this->assertNull($middleware->getHandler());
    }

    public function testPassRequestToNextHandler(): void
    {
        $middleware = new FakeMiddleware();
        $middleware->process($this->request, $this->handler);

        $this->assertSame($this->request, $this->handler->getRequest());
    }

    public function testReturnResponseInterface(): void
    {
        $middleware = new FakeMiddleware();
        $response = $middleware->process($this->request, $this->handler);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testDelegateResponseToHandler(): void
    {
        $middleware = new FakeMiddleware();
        $expectedResponse = new Response(204);

        $response = $middleware->process(
            request: $this->request,
            handler: new FakeRequestHandler($expectedResponse)
        );

        $this->assertSame($expectedResponse, $response);
    }

    public function testReturnCustomResponseIfProvided(): void
    {
        $initialResponse = new Response(204);
        $middleware = new FakeMiddleware($initialResponse);

        $response = $middleware->process($this->request, $this->handler);

        $this->assertSame($initialResponse, $response);
    }

    public function testThrowExceptionIfProvided(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Custom exception');

        $handler = new FakeMiddleware(exception: new RuntimeException('Custom exception'));

        $handler->process($this->request, $this->handler);
    }
}
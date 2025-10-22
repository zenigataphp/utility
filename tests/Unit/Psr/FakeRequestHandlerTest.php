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
use Psr\Http\Server\RequestHandlerInterface;
use Zenigata\Utility\Psr\FakeRequestHandler;

/**
 * Unit test for {@see FakeRequestHandler}.
 * 
 * Covered cases:
 *
 * - Default state.
 * - Return a default fake response when no custom response is provided.
 * - Return a custom response when injected via the constructor.
 * - Throw a preconfigured exception instead of returning a response.
 */
#[CoversClass(FakeRequestHandler::class)]
final class FakeRequestHandlerTest extends TestCase
{
    private ServerRequestInterface $request;

    private ResponseInterface $response;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $this->request = new ServerRequest('GET', '/');
        $this->response = new Response();
    }

    public function testDefaults(): void
    {
        $handler = new FakeRequestHandler($this->response);

        $this->assertInstanceOf(RequestHandlerInterface::class, $handler);
        $this->assertSame('handler', $handler->getName());
        $this->assertNull($handler->getRequest());
    }

    public function testReturnResponse(): void
    {
        $handler = new FakeRequestHandler($this->response);

        $response = $handler->handle($this->request);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame(200, $response->getStatusCode());
    }

    public function testReturnCustomResponseIfProvided(): void
    {
        $initialResponse = new Response();
        $handler = new FakeRequestHandler($initialResponse);

        $response = $handler->handle($this->request);

        $this->assertSame($initialResponse, $response);
    }

    public function testThrowExceptionIfProvided(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Custom exception');

        $handler = new FakeRequestHandler(
            response:  $this->response,
            exception: new RuntimeException('Custom exception')
        );

        $handler->handle($this->request);
    }
}
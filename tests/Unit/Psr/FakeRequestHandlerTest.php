<?php

declare(strict_types=1);

namespace Zenigata\Utility\Test\Unit\Psr;

use Nyholm\Psr7\Response;
use Nyholm\Psr7\ServerRequest;
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
 * - Return a preconfigured response.
 * - Invoke a custom callback during the process.
 */
#[CoversClass(FakeRequestHandler::class)]
final class FakeRequestHandlerTest extends TestCase
{
    private ServerRequestInterface $request;

    private ResponseInterface $response;

    /**
     * @inheritDoc
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
    }

    public function testReturnResponse(): void
    {
        $handler = new FakeRequestHandler($this->response);

        $response = $handler->handle($this->request);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame(200, $response->getStatusCode());
    }

    public function testInvokeCallback(): void
    {
        $requests = [];

        $handler = new FakeRequestHandler(
            response: $this->response,
            callable: function ($request) use (&$requests) {
                $requests[] = $request;
            }
        );
        
        $handler->handle($this->request);

        $this->assertNotEmpty($requests);
        $this->assertSame($this->request, $requests[0]);
    }
}
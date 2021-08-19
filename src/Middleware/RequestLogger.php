<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

final class RequestLogger implements MiddlewareInterface
{
    public function __construct(
        private LoggerInterface $logger
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        $this->logger->info(sprintf(
            "Received request %s %s returned %d",
            $request->getMethod(),
            $request->getUri(),
            $response->getStatusCode()
        ));

        return $response;
    }
}

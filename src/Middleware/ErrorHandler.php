<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Exception\Http\NotFoundException;
use App\Responder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class ErrorHandler implements MiddlewareInterface
{
    public function __construct(
        private Responder $responder
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (NotFoundException $exception) {
            return $this->responder->buildErrorResponse(404, $exception->getMessage());
        } catch (\Throwable $throwable) {
            return $this->responder->buildErrorResponse(500, "Something went wrong");
        }
    }
}

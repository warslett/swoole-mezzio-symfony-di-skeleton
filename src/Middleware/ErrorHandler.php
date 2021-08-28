<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Exception\Http\NotFoundException;
use App\Responder;
use Fig\Http\Message\StatusCodeInterface;
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
            return $this->responder->buildErrorResponse(
                StatusCodeInterface::STATUS_NOT_FOUND,
                $exception->getMessage()
            );
        } catch (\Throwable $throwable) {
            return $this->responder->buildErrorResponse(
                StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR,
                "Something went wrong"
            );
        }
    }
}

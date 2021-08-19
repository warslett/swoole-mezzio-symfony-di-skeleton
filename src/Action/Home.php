<?php

declare(strict_types=1);

namespace App\Action;

use App\Responder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class Home implements RequestHandlerInterface
{
    public function __construct(
        private Responder $responder
    ) {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->responder->buildResponse("Hello World");
    }
}

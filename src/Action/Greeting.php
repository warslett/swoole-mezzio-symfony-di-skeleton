<?php

declare(strict_types=1);

namespace App\Action;

use App\Responder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class Greeting implements RequestHandlerInterface
{
    public function __construct(
        private Responder $responder
    ) {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /** @var string $name */
        $name = $request->getAttribute('name');

        return $this->responder->buildResponse(sprintf("Welcome %s", $name));
    }
}

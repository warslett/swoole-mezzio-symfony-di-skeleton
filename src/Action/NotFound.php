<?php

declare(strict_types=1);

namespace App\Action;

use App\Exception\Http\NotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class NotFound implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        throw new NotFoundException(sprintf("The route %s was not found", $request->getUri()->getPath()));
    }
}

<?php

declare(strict_types=1);

namespace App\Http;

use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;

class ResponseFactory
{

    public function __invoke(): ResponseInterface
    {
        return new Response();
    }
}

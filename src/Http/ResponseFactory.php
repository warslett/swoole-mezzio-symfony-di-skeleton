<?php

declare(strict_types=1);

namespace App\Http;

use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseFactoryInterface;

class ResponseFactory
{

    public function __invoke(): ResponseFactoryInterface
    {
        return new Response();
    }
}

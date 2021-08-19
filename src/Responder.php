<?php

declare(strict_types=1);

namespace App;

use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;

class Responder
{

    public function buildResponse(string $content): ResponseInterface
    {
        $response = new Response();
        $response->getBody()->write($content);
        return $response;
    }

    public function buildErrorResponse(int $status, string $message): ResponseInterface
    {
        $response = new Response();
        $response = $response->withStatus($status);
        $response->getBody()->write($message);
        return $response;
    }
}

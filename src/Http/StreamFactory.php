<?php

declare(strict_types=1);

namespace App\Http;

use Laminas\Diactoros\Stream;
use Psr\Http\Message\StreamInterface;

class StreamFactory
{

    public function __invoke(): StreamInterface
    {
        return new Stream('php://temp', 'wb+');
    }
}

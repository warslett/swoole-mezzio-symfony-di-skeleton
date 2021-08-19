<?php

declare(strict_types=1);

namespace App\Http;

use Laminas\Diactoros\ServerRequest;
use Laminas\HttpHandlerRunner\RequestHandlerRunner as LaminasRequestHandlerRunner;
use Laminas\Stratigility\MiddlewarePipeInterface;
use Mezzio\Swoole\SwooleEmitter;
use Mezzio\Swoole\SwooleStream;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server as SwooleHttpServer;
use function Laminas\Diactoros\marshalMethodFromSapi;
use function Laminas\Diactoros\marshalProtocolVersionFromSapi;
use function Laminas\Diactoros\marshalUriFromSapi;
use function Laminas\Diactoros\normalizeUploadedFiles;

final class RequestHandlerRunner extends LaminasRequestHandlerRunner
{
    public function __construct(
        private SwooleHttpServer $httpServer,
        private MiddlewarePipeInterface $pipeline
    ) {}

    public function run(): void
    {
        $this->httpServer->on('request', function(Request $swooleRequest, Response $swooleResponse) {
            // Aggregate values from Swoole request object
            $get     = $swooleRequest->get ?? [];
            $post    = $swooleRequest->post ?? [];
            $cookie  = $swooleRequest->cookie ?? [];
            $files   = $swooleRequest->files ?? [];
            $server  = $swooleRequest->server ?? [];
            $headers = $swooleRequest->header ?? [];

            // Normalize SAPI params
            $server = array_change_key_case($server, CASE_UPPER);

            $psr7Request = new ServerRequest(
                $server,
                normalizeUploadedFiles($files),
                marshalUriFromSapi($server, $headers),
                marshalMethodFromSapi($server),
                new SwooleStream($swooleRequest),
                $headers,
                $cookie,
                $get,
                $post,
                marshalProtocolVersionFromSapi($server)
            );

            $emitter = new SwooleEmitter($swooleResponse);
            $emitter->emit($this->pipeline->handle($psr7Request));
        });

        $this->httpServer->start();
    }
}

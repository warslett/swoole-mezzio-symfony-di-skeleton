<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Http\ResponseFactory;
use App\Http\StreamFactory;
use Laminas\HttpHandlerRunner\RequestHandlerRunner;
use Laminas\Stratigility\MiddlewarePipe;
use Laminas\Stratigility\MiddlewarePipeInterface;
use Mezzio\Application;
use Mezzio\Helper\ServerUrlHelper;
use Mezzio\Helper\ServerUrlMiddleware;
use Mezzio\Helper\UrlHelper;
use Mezzio\Helper\UrlHelperMiddleware;
use Mezzio\MiddlewareContainer;
use Mezzio\MiddlewareFactory;
use Mezzio\Response\ServerRequestErrorResponseGenerator;
use Mezzio\Router\LaminasRouter;
use Mezzio\Router\Middleware\ImplicitHeadMiddleware;
use Mezzio\Router\Middleware\ImplicitOptionsMiddleware;
use Mezzio\Router\Middleware\MethodNotAllowedMiddleware;
use Mezzio\Router\Middleware\RouteMiddleware;
use Mezzio\Router\RouteCollector;
use Mezzio\Router\RouterInterface;
use Mezzio\Swoole\Event\RequestEvent;
use Mezzio\Swoole\Event\RequestHandlerRequestListener;
use Mezzio\Swoole\Log\AccessLogFormatter;
use Mezzio\Swoole\Log\AccessLogFormatterInterface;
use Mezzio\Swoole\Log\AccessLogInterface;
use Mezzio\Swoole\Log\Psr3AccessLogDecorator;
use Mezzio\Swoole\ServerRequestSwooleFactory;
use Mezzio\Swoole\SwooleRequestHandlerRunner;
use Swoole\Http\Server;

return function(ContainerConfigurator $configurator) {
    $services = $configurator->services();

    $services->defaults()
        ->autowire();

    $services->set(MiddlewareContainer::class);

    $services->set(MiddlewareFactory::class)
        ->public();

    $services->set(MiddlewarePipe::class);

    $services->alias(MiddlewarePipeInterface::class, MiddlewarePipe::class);

    $services->set(LaminasRouter::class);

    $services->alias(RouterInterface::class, LaminasRouter::class);

    $services->set(RouteCollector::class);

    $services->set(Server::class)
        ->arg('$host', '0.0.0.0')
        ->arg('$port', 30000)
        ->call('set', [['log_level' => 0, 'log_file' => '/var/log/swoole.log']]);

    $services->set(ServerRequestSwooleFactory::class);

    $services->set('mezzio.swoole.server_request_factory')
        ->class(\Closure::class)
        ->factory([service(ServerRequestSwooleFactory::class), '__invoke'])
        ->arg('$container', service('service_container'));

    $services->set(ServerRequestErrorResponseGenerator::class)
        ->arg('$responseFactory', service(ResponseFactory::class));

    $services->set(AccessLogFormatter::class);

    $services->alias(AccessLogFormatterInterface::class, AccessLogFormatter::class);

    $services->set(Psr3AccessLogDecorator::class)
        ->arg('$logger', service('logger.access'));

    $services->alias(AccessLogInterface::class, Psr3AccessLogDecorator::class);

    $services->set(RequestHandlerRequestListener::class)
        ->arg('$requestHandler', service(MiddlewarePipeInterface::class))
        ->arg('$serverRequestFactory', service('mezzio.swoole.server_request_factory'))
        ->arg('$serverRequestErrorResponseGenerator', service(ServerRequestErrorResponseGenerator::class))
        ->tag('listener', ['event' => RequestEvent::class]);

    $services->set(SwooleRequestHandlerRunner::class);

    $services->alias(RequestHandlerRunner::class, SwooleRequestHandlerRunner::class);

    $services->set(ServerUrlHelper::class);

    $services->set(ServerUrlMiddleware::class)
        ->public();

    $services->set(RouteMiddleware::class)
        ->public();

    $services->set(ImplicitHeadMiddleware::class)
        ->arg('$streamFactory', service(StreamFactory::class))
        ->public();

    $services->set(ImplicitOptionsMiddleware::class)
        ->arg('$responseFactory', service(ResponseFactory::class))
        ->public();

    $services->set(MethodNotAllowedMiddleware::class)
        ->arg('$responseFactory', service(ResponseFactory::class))
        ->public();

    $services->set(UrlHelper::class);

    $services->set(UrlHelperMiddleware::class)
        ->public();

    $services->set(Application::class)
        ->public();
};

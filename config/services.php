<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Http\RequestHandlerRunner;
use App\Http\ResponseFactory;
use App\Http\StreamFactory;
use Laminas\HttpHandlerRunner\RequestHandlerRunner as LaminasRequestHandlerRunner;
use Laminas\I18n\Translator\Translator;
use Laminas\Router\Http\TranslatorAwareTreeRouteStack;
use Laminas\Router\Http\TreeRouteStack;
use Laminas\Stratigility\MiddlewarePipe;
use Laminas\Stratigility\MiddlewarePipeInterface;
use Mezzio\Application;
use Mezzio\Helper\ServerUrlHelper;
use Mezzio\Helper\ServerUrlMiddleware;
use Mezzio\Helper\UrlHelper;
use Mezzio\Helper\UrlHelperMiddleware;
use Mezzio\MiddlewareContainer;
use Mezzio\MiddlewareFactory;
use Mezzio\Router\LaminasRouter;
use Mezzio\Router\Middleware\ImplicitHeadMiddleware;
use Mezzio\Router\Middleware\ImplicitOptionsMiddleware;
use Mezzio\Router\Middleware\MethodNotAllowedMiddleware;
use Mezzio\Router\Middleware\RouteMiddleware;
use Mezzio\Router\RouteCollector;
use Mezzio\Router\RouterInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Swoole\Http\Server;
use Symfony\Component\EventDispatcher\EventDispatcher;

return function(ContainerConfigurator $configurator) {
    $parameters = $configurator->parameters();
    $services = $configurator->services();

    $parameters->set('project_dir', dirname(__DIR__));

    $services->defaults()
        ->autowire();

    $services->set(MiddlewareContainer::class);

    $services->set(MiddlewareFactory::class)
        ->public();

    $services->set(MiddlewarePipeInterface::class)
        ->class(MiddlewarePipe::class);

    $services->set(RouterInterface::class)
        ->class(LaminasRouter::class);

    $services->set(RouteCollector::class);

    $services->set(Server::class)
        ->arg('$host', '0.0.0.0')
        ->arg('$port', 30000);

    $services->set(EventDispatcherInterface::class)
        ->class(EventDispatcher::class);

    $services->alias(LaminasRequestHandlerRunner::class, RequestHandlerRunner::class);

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

    $services->set('logger.handler.stdout')
        ->class(StreamHandler::class)
        ->arg('$stream', 'php://stdout');

    $services->set(LoggerInterface::class)
        ->class(Logger::class)
        ->arg('$name', 'app')
        ->call('pushHandler', [service('logger.handler.stdout')]);

    $services->load('App\\', '%project_dir%/src/');

    $services->load('App\\Action\\', '%project_dir%/src/Action/')
        ->public();

    $services->load('App\\Middleware\\', '%project_dir%/src/Middleware/')
        ->public();
};

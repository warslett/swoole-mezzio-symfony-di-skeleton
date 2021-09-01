<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

return function(ContainerConfigurator $configurator) {
    $services = $configurator->services();

    $services->defaults()
        ->autowire();

    $services->set('logger.handler.stdout')
        ->class(StreamHandler::class)
        ->arg('$stream', 'php://stdout');

    $services->set('logger.app')
        ->class(Logger::class)
        ->arg('$name', 'app')
        ->call('pushHandler', [service('logger.handler.stdout')]);

    $services->set('logger.access')
        ->class(Logger::class)
        ->arg('$name', 'access')
        ->call('pushHandler', [service('logger.handler.stdout')]);

    $services->set('logger.swoole')
        ->class(Logger::class)
        ->arg('$name', 'swoole')
        ->call('pushHandler', [service('logger.handler.stdout')]);

    $services->alias(LoggerInterface::class, 'logger.app');
};

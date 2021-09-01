<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;

return function(ContainerConfigurator $configurator) {
    $services = $configurator->services();

    $services->defaults()
        ->autowire();

    $services->alias(CommandLoaderInterface::class, ContainerCommandLoader::class);

    $services->set(Application::class)
        ->call('setDispatcher', [service(EventDispatcherInterface::class)])
        ->call('setCommandLoader', [service(CommandLoaderInterface::class)])
        ->public();
};

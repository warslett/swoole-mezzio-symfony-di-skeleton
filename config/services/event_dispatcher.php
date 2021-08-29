<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

return function(ContainerConfigurator $configurator) {
    $services = $configurator->services();

    $services->defaults()
        ->autowire();

    $services->set(EventDispatcher::class);

    $services->alias(EventDispatcherInterface::class, EventDispatcher::class);
};

<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return function(ContainerConfigurator $configurator) {
    $parameters = $configurator->parameters();
    $services = $configurator->services();

    $parameters->set('project_dir', dirname(__DIR__, 2));

    $services->defaults()
        ->autowire();

    $services->load('App\\', '%project_dir%/src/');

    $services->load('App\\Action\\', '%project_dir%/src/Action/')
        ->public();

    $services->load('App\\Middleware\\', '%project_dir%/src/Middleware/')
        ->public();
};

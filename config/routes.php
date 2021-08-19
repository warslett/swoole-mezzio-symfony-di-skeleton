<?php

declare(strict_types=1);

use Mezzio\Application;
use Mezzio\MiddlewareFactory;
use Psr\Container\ContainerInterface;

return static function (Application $app, MiddlewareFactory $factory, ContainerInterface $container): void {
    $app->get('/', App\Action\Home::class, 'home');
    $app->get('/greeting/:name', App\Action\Greeting::class, 'greeting');
};

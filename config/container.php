<?php

declare(strict_types=1);

namespace App;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass;
use Symfony\Component\EventDispatcher\EventDispatcher;

$containerBuilder = new ContainerBuilder();
$containerBuilder->addCompilerPass(new RegisterListenersPass(
    EventDispatcher::class,
    'listener',
    'subscriber'
));

$loader = new PhpFileLoader($containerBuilder, new FileLocator(__DIR__));
$loader->import('services/*.php');
$loader->import('services/' . $_ENV['ENV'] . '/*.php', null, true);

$containerBuilder->compile();
return $containerBuilder;

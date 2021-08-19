<?php

declare(strict_types=1);

namespace App;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

$containerBuilder = new ContainerBuilder();

$loader = new PhpFileLoader($containerBuilder, new FileLocator(__DIR__));
$loader->load('services.php');

$containerBuilder->compile();
return $containerBuilder;

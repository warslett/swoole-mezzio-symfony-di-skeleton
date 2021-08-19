<?php

declare(strict_types=1);

namespace App;

use Mezzio\Application;
use Mezzio\MiddlewareFactory;
use Psr\Container\ContainerInterface;

require_once 'vendor/autoload.php';

/** @var ContainerInterface $container */
$container = require 'config/container.php';

/** @var Application $application */
$application = $container->get(Application::class);

/** @var MiddlewareFactory $middlewareFactory */
$middlewareFactory = $container->get(MiddlewareFactory::class);

(require 'config/pipeline.php')($application, $middlewareFactory, $container);
(require 'config/routes.php')($application, $middlewareFactory, $container);

$application->run();

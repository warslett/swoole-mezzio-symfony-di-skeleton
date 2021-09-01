<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Mezzio\Swoole\Event\HotCodeReloaderWorkerStartListener;
use Mezzio\Swoole\Event\WorkerStartEvent;
use Mezzio\Swoole\HotCodeReload\FileWatcher\InotifyFileWatcher;
use Mezzio\Swoole\HotCodeReload\FileWatcherInterface;

return function(ContainerConfigurator $configurator) {
    $services = $configurator->services();

    $services->defaults()
        ->autowire();

    $services->set(InotifyFileWatcher::class)
        ->call('addFilePath', ['%project_dir%/config', '%project_dir%/src']);

    $services->alias(FileWatcherInterface::class, InotifyFileWatcher::class);

    $services->set(HotCodeReloaderWorkerStartListener::class)
        ->arg('$logger', service('logger.swoole'))
        ->arg('$interval', 500)
        ->tag('listener', ['event' => WorkerStartEvent::class]);
};

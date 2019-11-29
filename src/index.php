<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Symfony\Component\Console\Application;

require __DIR__ . '/../vendor/autoload.php';

(new ContainerBuilder)
    ->useAnnotations(false)
    ->useAutowiring(false)
    ->addDefinitions(__DIR__ . '/DependencyInjection/configuration.php')
    ->build()
    ->get(Application::class)
    ->run();

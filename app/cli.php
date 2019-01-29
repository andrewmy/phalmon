#!/usr/bin/env php
<?php

use Phalcon\Cli\Console;
use Phalcon\Di\FactoryDefault\Cli as CliDI;
use Phalcon\Loader;

\defined('APP_ENV') || \define(
    'APP_ENV', \getenv('APP_ENV') ? \getenv('APP_ENV') : 'prod'
);

\error_reporting(E_ALL);

require_once __DIR__.'/../vendor/autoload.php';

$loader = new Loader();
$loader->registerDirs([__DIR__.'/Task'])->register();

$container = new CliDI();
$container = require __DIR__.'/config/container.php';
$app = new Console($container);

$arguments = [];
foreach ($argv as $k => $arg) {
    if (1 === $k) {
        $arguments['task'] = $arg;
    } elseif (2 === $k) {
        $arguments['action'] = $arg;
    } elseif ($k >= 3) {
        $arguments['params'][] = $arg;
    }
}

try {
    $app->handle($arguments);
} catch (\Phalcon\Exception $e) {
    \fwrite(STDERR, $e->getMessage().PHP_EOL);
    exit(1);
} catch (\Throwable $throwable) {
    \fwrite(STDERR, $throwable->getMessage().PHP_EOL);
    exit(1);
}

<?php

declare(strict_types=1);

use App\Factory\UserFactory;
use App\Service\MessageHandler;
use Phalcon\Db\Adapter\MongoDB\Client;
use Phalcon\Di\FactoryDefault;
use Phalcon\Logger\Adapter\File;
use Phalcon\Mvc\Collection\Manager;
use Phalcon\Queue\Beanstalk;

/** @var FactoryDefault $container */
if ($container->has('config')) {
    return $container;
}

$container->setShared('config', function () {
    return require __DIR__.'/config.php';
});

$container->setShared('mongo', function () use ($container) {
    /** @var \Phalcon\Config $config */
    $config = $container->get('config');
    $db = new Client($config->path('database.dsn'));

    return $db->selectDatabase($config->path('database.dbname'));
});

$container->setShared('collectionManager', function () {
    return new Manager();
});

$container->setShared('logger', function () {
    return new File(__DIR__.'/../../var/log/app.'.APP_ENV.'.log');
});

$container->setShared('userFactory', function () use ($container) {
    return new UserFactory($container->get('security'));
});

$container->setShared('queue', function () use ($container) {
    /** @var \Phalcon\Config $config */
    $config = $container->get('config');

    return new Beanstalk([
        'host' => $config->path('queue.host'),
        'port' => $config->path('queue.port'),
    ]);
});

$container->setShared('messageHandler', function () use ($container) {
    return new MessageHandler($container->get('logger'));
});

return $container;

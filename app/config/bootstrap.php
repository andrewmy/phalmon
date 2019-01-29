<?php

declare(strict_types=1);

use App\Controller\MessageController;
use App\Controller\SecurityController;
use App\Exceptions\Unauthorized;
use App\Service\Utility;
use Dmkit\Phalcon\Auth\Middleware\Micro as Auth;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Micro\Collection;

require_once __DIR__.'/../../vendor/autoload.php';

if (!isset($container)) {
    $container = new FactoryDefault();
}
$container = require __DIR__.'/container.php';

if (!isset($app)) {
    $app = new Micro();
}
$app->setDI($container);

$app->get('/', function () {
    return 'Nothing of particular interest here.';
});

$app->options('/api', function () {
    return [
        'login' => '/api/login',
        'messages' => '/api/messages',
    ];
});

$app->mount(
    (new Collection())
        ->setPrefix('/api')
        ->setHandler(SecurityController::class, true)
        ->post('/login', 'login')
);

$app->mount(
    (new Collection())
        ->setPrefix('/api/messages')
        ->setHandler(MessageController::class, true)
        ->get('', 'list')
        ->post('', 'create')
);

$app->notFound(function () use ($app): void {
    Utility::returnError($app->response, 404, 'Not found', '¯\_(ツ)_/¯');
});

$app->error(function ($exception) use ($app, $container) {
    if ($exception instanceof Unauthorized) {
        Utility::returnError(
            $app->response,
            $exception->getCode(),
            $exception->getMessage() ?: 'Unauthorized'
        );

        return false;
    } elseif ($container->get('config')->get('debug')) {
        throw $exception;
    } elseif ($exception instanceof \Exception) {
        $container->get('logger')->error(
            \sprintf(
                'Exception: %s, %s, %s',
                $exception->getCode(),
                $exception->getMessage(),
                $exception->getTraceAsString()
            )
        );

        Utility::returnError($app->response, 500, 'Something happened');

        return false;
    }
    $container->get('logger')->error(\sprintf('Exception: %s', $exception));
    Utility::returnError($app->response, 500, 'Something happened');

    return false;
});

$app->after(function () use ($app): void {
    $app->response
        ->setHeader(
            'Access-Control-Allow-Origin',
            $app->getDI()->get('config')->get('cors_origin')
        )
        ->setHeader('Access-Control-Allow-Methods', 'GET,HEAD,POST,OPTIONS')
        ->setHeader(
            'Access-Control-Allow-Headers',
            'Origin, X-Requested-With, Content-Range, Content-Disposition, '
            .'Content-Type, Authorization'
        )
        ->setHeader('Access-Control-Allow-Credentials', true);

    $content = $app->getReturnedValue();

    if (\is_array($content)) {
        $app->response->setJsonContent($content);

        return;
    }
    if (\is_string($content)) {
        $app->response->setContent($content);
    }
});

$auth = new Auth($app);

return $app;

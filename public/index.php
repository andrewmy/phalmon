<?php

declare(strict_types=1);

\defined('APP_ENV') || \define(
    'APP_ENV', \getenv('APP_ENV') ? \getenv('APP_ENV') : 'prod'
);

\error_reporting(APP_ENV === 'prod' ? 0 : E_ALL);

$app = new \Phalcon\Mvc\Micro();
$app = require __DIR__.'/../app/config/bootstrap.php';

$app->handle();

/** @var \Phalcon\Http\Response $response */
$response = $app->di->getShared('response');
$returnedValue = $app->getReturnedValue();
if (null !== $returnedValue) {
    if (\is_string($returnedValue)) {
        $response->setContent($returnedValue);
    } else {
        $response->setJsonContent($returnedValue);
    }
}

if (!$response->isSent()) {
    $response->send();
}

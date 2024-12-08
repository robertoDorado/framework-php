<?php

use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy as Group;
use Source\Controllers\Error;
use Source\Controllers\Home;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpMethodNotAllowedException;
use Source\Middleware\MiddlewareExample;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpException;

require __DIR__ . '/vendor/autoload.php';

setlocale(LC_ALL, 'en_US.UTF-8');
date_default_timezone_set("America/Sao_Paulo");

$path = "./Logs/error.log";

if (!file_exists($path)) {
    mkdir("Logs");
    file_put_contents($path, '');
}

error_reporting(E_ALL & (~E_NOTICE | ~E_USER_NOTICE));
ini_set('error_log', $path);
ini_set('log_errors', true);

$app = AppFactory::create();
$app->addRoutingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$dispatchError = function (Throwable $exception) {
    $errorHandler = new Error();
    $statusCode = $exception instanceof HttpException ? $exception->getCode() : 500;
    $errorMessage = empty($exception->xdebug_message) ? $exception->getMessage() : $exception->xdebug_message;
    return $errorHandler->process($statusCode, $errorMessage);
};

$errorMiddleware->setErrorHandler(HttpNotFoundException::class, function (
    Request $request,
    Throwable $exception,
    bool $displayErrorDetails,
    bool $logErrors,
    bool $logErrorDetails
) use ($dispatchError) {
    return $dispatchError($exception);
});

$errorMiddleware->setErrorHandler(HttpMethodNotAllowedException::class, function (
    Request $request,
    Throwable $exception,
    bool $displayErrorDetails,
    bool $logErrors,
    bool $logErrorDetails
) use ($dispatchError) {
    return $dispatchError($exception);
});

$errorMiddleware->setDefaultErrorHandler(function (
    Request $request,
    Throwable $exception,
    bool $displayErrorDetails,
    bool $logErrors,
    bool $logErrorDetails
) use ($dispatchError) {
    return $dispatchError($exception);
});

$app->group("/", function(Group $group) {
    $group->get("", [Home::class, 'index']);
    $group->get("form", [Home::class, 'form']);
    $group->post("form", [Home::class, 'form']);
    $group->get("form-ajax", [Home::class, 'formAjax']);
})->add(new MiddlewareExample());

$app->post("/form-ajax", [Home::class, 'formAjax']);
$app->group("/ops", function(Group $group) {
    $group->get("/error/{status_code}", [Error::class, 'index']);
});

$app->run();
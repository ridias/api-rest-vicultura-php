<?php

use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';


/**
 * Instantiate App
 *
 * In order for the factory to work you need to ensure you have installed
 * a supported PSR-7 implementation of your choice e.g.: Slim PSR-7 and a supported
 * ServerRequest creator (included with Slim PSR-7)
 */
$app = AppFactory::create();
$app->setBasePath("/api-rest-vicultura-php/api");


/**
  * The routing middleware should be added earlier than the ErrorMiddleware
  * Otherwise exceptions thrown from it will not be handled by the middleware
  */
$app->addRoutingMiddleware();

$materialRoutes = require __DIR__ . '/config/routes/MaterialRoutes.php';
$groupRoutes = require __DIR__ . '/config/routes/GroupRoutes.php';
$userRoutes = require __DIR__ . '/config/routes/UserRoutes.php';
$objectiveRoutes = require __DIR__ . '/config/routes/ObjectiveRoutes.php';

$materialRoutes($app);
$groupRoutes($app);
$userRoutes($app);
$objectiveRoutes($app);

/**
 * Add Error Middleware
 *
 * @param bool                  $displayErrorDetails -> Should be set to false in production
 * @param bool                  $logErrors -> Parameter is passed to the default ErrorHandler
 * @param bool                  $logErrorDetails -> Display error details in error log
 * @param LoggerInterface|null  $logger -> Optional PSR-3 Logger  
 *
 * Note: This middleware should be added last. It will not handle any exceptions/errors
 * for middleware added after it.
 */
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// Run app
$app->run();

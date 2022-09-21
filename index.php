<?php
use Slim\Factory\AppFactory;
use App\Exception\Handler;

require __DIR__ . '/vendor/autoload.php';

//Create container
$container = new DI\Container();
// Create slim application
AppFactory::setContainer($container);
$app = AppFactory::create();

// Routes
require __DIR__.'/App/Routes/api.php';
require __DIR__.'/App/Routes/web.php';

// Set up dependencies
require __DIR__.'/App/dependencies.php';

// Add Routing Middleware
$app->addRoutingMiddleware();

// Add error handler
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler(new Handler());

// Run the app
$app->run();

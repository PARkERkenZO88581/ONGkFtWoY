<?php
// 代码生成时间: 2025-08-16 05:45:11
 * which listens to GET and POST requests and returns a response.
 */

require 'vendor/autoload.php';

// Create a Slim app
$app = \Slim\Factory\AppFactory::create();

// Register middleware for error handling
$app->addErrorMiddleware(true, true, true, '/errors/error');

// Define GET route
$app->get('/api/hello', function ($request, $response, $args) {
    $response->getBody()->write('Hello, World!');
    return $response;
});

// Define POST route
$app->post('/api/hello', function ($request, $response, $args) {
    $body = $request->getParsedBody();
    if (empty($body['name'])) {
        $response->getBody()->write('Please provide a name.');
    } else {
        $response->getBody()->write(sprintf('Hello, %s!', $body['name']));
    }
    return $response;
});

// Define PUT route
$app->put('/api/hello/{name}', function ($request, $response, $args) {
    $response->getBody()->write(sprintf('Hello, %s!', $args['name']));
    return $response;
});

// Define DELETE route
$app->delete('/api/hello/{name}', function ($request, $response, $args) {
    $response->getBody()->write(sprintf('Goodbye, %s!', $args['name']));
    return $response;
});

// Run the app
$app->run();
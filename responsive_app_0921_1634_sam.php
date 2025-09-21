<?php
// 代码生成时间: 2025-09-21 16:34:05
require 'vendor/autoload.php';

// Initialize the application
$app = new \Slim\Slim(array(
    'debug' => true, // Enable or disable debug mode
    'template' => new \Slim\Extras\Views\Twig(), // Initialize Twig view
    'view' => new \Slim\Extras\Views\Twig()
));

// Register routes and middleware
$app->get('/', 'index');
$app->get('/about', 'about');

// Define middleware for error handling
$app->error(function (Exception $e) use ($app) {
    $app->response()->status(500);
    $app->response()->body("We're sorry, but something went wrong.");
});

// Define routes
$app->map('GET', '/', function() use ($app) {
    $app->render('index.twig', array(
        'title' => 'Home Page'
    ));
});

$app->map('GET', '/about', function() use ($app) {
    $app->render('about.twig', array(
        'title' => 'About Us',
        'message' => 'This is the about page.'
    ));
});

// Run the application
$app->run();

// Twig templates
/**
 * index.twig
 *
 * @var string title
 */
{% extends 'layout.twig' %}
{% block content %}
<h1>{{ title }}</h1>
<p>Welcome to the responsive app!</p>
{% endblock %}

/**
 * about.twig
 *
 * @var string title
 * @var string message
 */
{% extends 'layout.twig' %}
{% block content %}
<h1>{{ title }}</h1>
<p>{{ message }}</p>
{% endblock %}

/**
 * layout.twig
 *
 * @var string title
 */
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ title }}</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>{{ title }}</h1>
    </header>
    <div id="content">
    {% block content %}
    {% endblock %}
    </div>
    <footer>
        <p>&copy; 2023 Responsive App</p>
    </footer>
</body>
</html>

// styles.css
body {
    font-family: Arial, sans-serif;
}

header, footer {
    background-color: #333;
    color: white;
    text-align: center;
    padding: 1rem 0;
}

#content {
    margin: 0 auto;
    max-width: 1200px;
    padding: 2rem;
}

@media (max-width: 768px) {
    #content {
        padding: 1rem;
    }
}

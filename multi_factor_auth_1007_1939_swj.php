<?php
// 代码生成时间: 2025-10-07 19:39:58
require 'vendor/autoload.php';

use Slim\Factory\AppFactory';
use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// Define the secret key for TOTP generation
define('TWO_FACTOR_AUTH_SECRET', 'YOUR_SECRET_KEY_HERE');

$app = AppFactory::create();

// Middleware to verify one-time token
$app->add(function (Request $request, Response $response, callable $next) {
    $token = $request->getParam('token');
    if (empty($token)) {
        $response->getBody()->write('Two-factor authentication token is required.');
        return $response->withStatus(400);
    }
    if (!$this->verifyToken($token)) {
        $response->getBody()->write('Invalid two-factor authentication token.');
        return $response->withStatus(401);
    }
    $response = $next($request, $response);
    return $response;
});

// Generate TOTP for multi-factor authentication
$app->get('/generate-totp', function (Request $request, Response $response, $args) {
    $totp = $this->generateTOTP();
    $response->getBody()->write('Your one-time token is: ' . $totp);
    return $response;
});

// Verify the provided token against the generated TOTP
$app->post('/verify-token', function (Request $request, Response $response, $args) {
    $token = $request->getParsedBody()['token'];
    if ($this->verifyToken($token)) {
        $response->getBody()->write('Token verified successfully.');
        return $response->withStatus(200);
    } else {
        $response->getBody()->write('Token verification failed.');
        return $response->withStatus(401);
    }
});

// Function to generate a TOTP
function generateTOTP() {
    $time = time();
    $totp = hash_hmac('sha1', $time, TWO_FACTOR_AUTH_SECRET);
    $totp = strtr(substr($totp, 0, 6), 'abcdef', '0123456');
    return $totp;
}

// Function to verify a TOTP
function verifyToken($token) {
    $time = time();
    $window = 30; // Verification window in seconds
    $totp = hash_hmac('sha1', $time, TWO_FACTOR_AUTH_SECRET);
    $totp = strtr(substr($totp, 0, 6), 'abcdef', '0123456');
    if ($totp == $token) return true;
    for ($i = -1; $i <= 1; $i++) {
        if (hash_hmac('sha1', ($time + $i * 30), TWO_FACTOR_AUTH_SECRET) == $token) {
            return true;
        }
    }
    return false;
}

$app->run();

// NOTE: This is a basic example and does not include all necessary security considerations.
// In a real-world application, you should use a library for TOTP generation and verification,
// and you should manage the secret key securely.

// Also, this example assumes that the 'slim/slim' package is installed via Composer.
// You should adjust the require statement to match your project's autoloader configuration.

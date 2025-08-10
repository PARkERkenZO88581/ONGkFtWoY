<?php
// 代码生成时间: 2025-08-11 03:48:40
// performance_test.php

require 'vendor/autoload.php'; // 引入Slim框架和其他依赖

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\ServerRequestCreatorFactory;
use Slim\Factory\Psr17\Psr17Factory;
use Slim\Factory\Psr17\Psr17FactoryFactory;
use Slim\Middleware\ErrorMiddleware;
use Slim\Factory\ServerFactory;
use Slim\Psr7\Response;
use Nyholm\Psr7\Response as Psr7Response;

// 定义一个性能测试中间件
class PerformanceTestMiddleware {
    public function __invoke(Request $request, Response $response, $next) {
        // 开始时间
        $startTime = microtime(true);

        $response = $next($request, $response);

        // 结束时间
        $endTime = microtime(true);

        // 计算响应时间
        $timeTaken = $endTime - $startTime;

        // 将响应时间添加到响应头中
        $response = $response->withHeader('X-Response-Time', sprintf('%.2f ms', $timeTaken * 1000));

        return $response;
    }
}

// 创建一个Slim应用
$app = new \Slim\App(['settings' => [
    'displayErrorDetails' => true,
    'addContentLengthHeader' => false,
    'responseChunkSize' => 4096,
]]);

// 添加性能测试中间件
$app->add(new PerformanceTestMiddleware());

// 定义一个GET路由，用于测试性能
$app->get('/test', function (Request $request, Response $response, $args) {
    // 模拟一些操作，例如数据库查询或计算
    sleep(1); // 模拟耗时操作

    // 设置响应体
    $response->getBody()->write('Hello, World!');

    // 返回响应
    return $response;
});

// 错误处理
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// 运行应用
$app->run();
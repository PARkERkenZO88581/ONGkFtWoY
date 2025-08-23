<?php
// 代码生成时间: 2025-08-23 22:23:19
// 设置Slim框架的自动加载
require 'vendor/autoload.php';

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Slim\Factory\AppFactory;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// 初始化Slim应用
AppFactory::setConfig(["displayErrorDetails" => true]);
$app = AppFactory::create();

// 创建一个日志记录器实例
$log = new Logger('name');
$log->pushHandler(new StreamHandler("logs/app.log", Logger::WARNING));

// 注册错误处理中间件
$app->add(function ($request, $handler) use ($log) {
    $response = $handler($request);
    $error = $request->getAttribute('error');
    if ($error) {
        $log->error($error['message'], ['exception', $error['exception']]);
    }
    return $response;
});

// 捕获404错误
$app->addErrorMiddleware(true, true, true);

// 路由定义
$app->get('/', function ($request, $response, $args) use ($log) {
    $log->info('Home page accessed');
    return $response->write("Welcome to the Error Logger App");
});

// 启动应用
$app->run();

<?php
// 代码生成时间: 2025-09-14 02:11:36
require 'vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\ServerRequestCreatorFactory;
use Slim\Psr7\Response;

// 实例化请求创建器
$requestCreator = ServerRequestCreatorFactory::create();

// 创建请求对象
$request = $requestCreator->createServerRequest();

// 从请求中获取 JSON 数据
$json = $request->getParsedBody();

// 定义路由和处理函数
$app = new Slim\App();

$app->post('/convert', function (Request $request, Response $response, $args) {
    // 获取 JSON 数据
    $json = $request->getParsedBody();

    // 检查 JSON 数据是否有效
    if (!is_array($json)) {
        return $response->withJson(
            ['error' => 'Invalid JSON data provided.'],
            Response::HTTP_BAD_REQUEST
        );
    }

    // 转换 JSON 数据（这里可以添加具体的转换逻辑）
    // 假设我们简单地返回相同的 JSON 数据
    $convertedJson = $json;

    // 返回转换后的 JSON 数据
    return $response->withJson($convertedJson);
});

// 运行应用
$app->run();
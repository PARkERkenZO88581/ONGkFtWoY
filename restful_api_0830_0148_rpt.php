<?php
// 代码生成时间: 2025-08-30 01:48:41
// 使用composer安装Slim框架
// composer require slim/slim

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require __DIR__ . '/../vendor/autoload.php';

// 定义路由和中间件
$app = new \Slim\App();

// 获取资源列表
$app->get('/api/users', function (Request $request, Response $response, array $args) {
    // 模拟数据库查询
    $users = [
        ['id' => 1, 'name' => 'John'],
        ['id' => 2, 'name' => 'Jane'],
    ];

    // 设置响应内容类型
    $response->getBody()->write(json_encode($users));

    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(200);
});

// 获取单个资源
$app->get('/api/users/{id}', function (Request $request, Response $response, array $args) {
    // 获取用户ID
    $id = $args['id'];

    // 模拟数据库查询
    $user = [
        'id' => 1,
        'name' => 'John',
    ];

    // 检查用户是否存在
    if ($id != $user['id']) {
        return $response
            ->withJson(
                ['error' => 'User not found'],
                404
            );
    }

    // 设置响应内容类型
    $response->getBody()->write(json_encode($user));

    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(200);
});

// 添加新资源
$app->post('/api/users', function (Request $request, Response $response, array $args) {
    // 获取请求体中的数据
    $data = json_decode($request->getBody()->getContents(), true);

    // 检查数据是否有效
    if (empty($data['name'])) {
        return $response
            ->withJson(
                ['error' => 'Name is required'],
                400
            );
    }

    // 模拟数据库插入
    $newUser = [
        'id' => 3,
        'name' => $data['name'],
    ];

    // 设置响应内容类型
    $response->getBody()->write(json_encode($newUser));

    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(201);
});

// 更新资源
$app->put('/api/users/{id}', function (Request $request, Response $response, array $args) {
    // 获取用户ID
    $id = $args['id'];

    // 获取请求体中的数据
    $data = json_decode($request->getBody()->getContents(), true);

    // 模拟数据库查询
    $user = [
        'id' => 1,
        'name' => 'John',
    ];

    // 检查用户是否存在
    if ($id != $user['id']) {
        return $response
            ->withJson(
                ['error' => 'User not found'],
                404
            );
    }

    // 更新用户信息
    $user['name'] = $data['name'];

    // 设置响应内容类型
    $response->getBody()->write(json_encode($user));

    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(200);
});

// 删除资源
$app->delete('/api/users/{id}', function (Request $request, Response $response, array $args) {
    // 获取用户ID
    $id = $args['id'];

    // 模拟数据库查询
    $user = [
        'id' => 1,
        'name' => 'John',
    ];

    // 检查用户是否存在
    if ($id != $user['id']) {
        return $response
            ->withJson(
                ['error' => 'User not found'],
                404
            );
    }

    // 删除用户
    // ...

    return $response
        ->withStatus(204);
});

// 运行应用
$app->run();

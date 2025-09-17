<?php
// 代码生成时间: 2025-09-18 03:27:19
// 引入Slim框架
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * 使用Slim框架创建一个简单的RESTful API
 */
require 'vendor/autoload.php';

$app = new \Slim\App();

// 定义GET请求处理
$app->get('/users/{id}', function (Request $request, Response $response, array $args) {
    // 获取用户ID
    $userId = $args['id'];
    
    // 模拟数据库查询
    $user = getUserById($userId);
    
    if ($user) {
        $response->getBody()->write(json_encode($user));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    } else {
        return $response
            ->withJson(['error' => 'User not found'], 404);
    }
});

// 定义POST请求处理
$app->post('/users', function (Request $request, Response $response, array $args) {
    // 解析请求体
    $body = $request->getParsedBody();
    
    // 验证请求数据
    if (!isset($body['name']) || !isset($body['email'])) {
        return $response
            ->withJson(['error' => 'Missing name or email'], 400);
    }
    
    // 模拟数据库插入
    $userId = createUser($body['name'], $body['email']);
    
    if ($userId) {
        return $response
            ->withJson(['id' => $userId], 201)
            ->withHeader('Content-Type', 'application/json');
    } else {
        return $response
            ->withJson(['error' => 'Failed to create user'], 500)
            ->withHeader('Content-Type', 'application/json');
    }
});

// 运行应用
$app->run();

/**
 * 模拟从数据库中根据ID获取用户信息
 * @param int $id 用户ID
 * @return array|null 用户信息或null
 */
function getUserById($id) {
    // 这里只是一个示例，实际应用中需要替换为数据库查询
    $users = [
        1 => ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com'],
        2 => ['id' => 2, 'name' => 'Jane Doe', 'email' => 'jane@example.com'],
    ];
    return $users[$id] ?? null;
}

/**
 * 模拟向数据库中插入用户信息
 * @param string $name 用户名
 * @param string $email 邮箱
 * @return int 新增用户ID
 */
function createUser($name, $email) {
    // 这里只是一个示例，实际应用中需要替换为数据库插入操作
    static $nextId = 3;
    $users = [
        1 => ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com'],
        2 => ['id' => 2, 'name' => 'Jane Doe', 'email' => 'jane@example.com'],
    ];
    $users[$nextId] = ['id' => $nextId, 'name' => $name, 'email' => $email];
    return $nextId++;
}
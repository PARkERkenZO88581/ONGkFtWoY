<?php
// 代码生成时间: 2025-09-03 11:06:45
// 使用Slim框架防止SQL注入的示例程序
require 'vendor/autoload.php';

$app = new \Slim\App();

// 数据库配置
$dbConfig = [
    'host' => 'localhost',
    'user' => 'root',
    'password' => 'password',
    'dbname' => 'your_database_name'
];

// 创建数据库连接
$db = new PDO('mysql:host=' . $dbConfig['host'] . ';dbname=' . $dbConfig['dbname'], 
    $dbConfig['user'], 
    $dbConfig['password'], 
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

// 定义一个GET请求来查询用户信息
$app->get('/users', function ($request, $response, $args) use ($db) {
    // 从请求中获取用户ID
    $userId = $request->getQueryParams()['id'];
    
    // 确保用户ID是数字
    if (!is_numeric($userId)) {
        return $response->withJson(['error' => 'Invalid user ID'], 400);
    }
    
    try {
        // 使用预处理语句防止SQL注入
        $stmt = $db->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            return $response->withJson($user);
        } else {
            return $response->withJson(['error' => 'User not found'], 404);
        }
    } catch (PDOException $e) {
        return $response->withJson(['error' => 'Database error: ' . $e->getMessage()], 500);
    }
});

// 运行应用程序
$app->run();

// 注意：
// 1. 确保已经安装了Slim框架和PDO扩展。
// 2. 在实际部署之前，需要替换数据库配置中的用户名和密码。
// 3. 请确保你的数据库和表已经创建好，并且表名为'users'。
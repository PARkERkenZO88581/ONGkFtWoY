<?php
// 代码生成时间: 2025-09-01 01:27:14
// 使用Slim框架创建一个简单的测试数据生成器
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

// 创建Slim应用
$app = AppFactory::create();

// 定义路由，生成测试数据
$app->get('/generate-test-data', function ($request, $response, $args) {
    // 调用测试数据生成函数
    $data = generateTestData();

    // 设置响应内容和状态码
    return $response->withJson($data, 200);
});

// 测试数据生成函数
function generateTestData() {
    // 这里可以添加实际逻辑来生成测试数据
    // 例如，生成随机用户数据
    $testData = [];
    for ($i = 0; $i < 10; $i++) {
        $testData[] = [
            'id' => $i + 1,
            'name' => 'User ' . ($i + 1),
            'email' => 'user' . ($i + 1) . '@example.com',
            'created_at' => date('Y-m-d H:i:s'),
        ];
    }
    return $testData;
}

// 运行应用
$app->run();

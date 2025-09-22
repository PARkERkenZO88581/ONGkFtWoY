<?php
// 代码生成时间: 2025-09-22 08:02:05
require 'vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

// 消息通知系统
class NotificationSystem {
    public function sendMessage($email, $message) {
        try {
            // 假设我们有一个发送电子邮件的方法
            // 这里使用mail函数作为示例
            if (mail($email, 'Notification', $message)) {
                return 'Message sent successfully';
            } else {
                return 'Failed to send message';
            }
        } catch (Exception $e) {
            // 错误处理
            return 'Error: ' . $e->getMessage();
        }
    }
}

// 创建Slim应用
$app = AppFactory::create();

// POST路由处理消息发送
$app->post('/notify', function (Request $request, Response $response, $args) {
    // 获取请求数据
    $data = $request->getParsedBody();
    $email = $data['email'] ?? '';
    $message = $data['message'] ?? '';

    // 检查数据完整性
    if (empty($email) || empty($message)) {
        return $response->withJson(['error' => 'Missing email or message'], 400);
    }

    // 创建通知系统实例
    $notificationSystem = new NotificationSystem();

    // 发送消息并返回结果
    $result = $notificationSystem->sendMessage($email, $message);
    return $response->withJson(['message' => $result], 200);
});

// 运行应用
$app->run();

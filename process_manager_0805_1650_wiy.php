<?php
// 代码生成时间: 2025-08-05 16:50:51
// 使用Slim框架创建进程管理器
require 'vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

// 定义进程管理器类
class ProcessManager {
    public function startProcess($command) {
        // 启动进程
        $process = proc_open($command, [], $pipes);
        if (!is_resource($process)) {
            throw new Exception('Failed to start process.');
        }
        return $process;
    }

    public function stopProcess($process) {
        // 停止进程
        if (is_resource($process)) {
            proc_close($process);
        } else {
            throw new Exception('Invalid process resource.');
        }
    }
}

// 创建Slim应用
$app = AppFactory::create();

// 路由：启动进程
$app->post('/start', function (Request $request, Response $response, $args) {
    $processManager = new ProcessManager();
    $command = $request->getParsedBody()['command'] ?? '';
    try {
        $process = $processManager->startProcess($command);
        $response->getBody()->write('Process started with PID: ' . proc_get_status($process)['pid']);
    } catch (Exception $e) {
        $response->getBody()->write('Error: ' . $e->getMessage());
        $response->withStatus(500);
    }
    return $response;
});

// 路由：停止进程
$app->post('/stop', function (Request $request, Response $response, $args) {
    $processManager = new ProcessManager();
    $processId = $request->getParsedBody()['pid'] ?? null;
    try {
        $process = proc_open('ps -p ' . $processId, [], $pipes);
        if (!is_resource($process)) {
            throw new Exception('Failed to open process.');
        }
        $processManager->stopProcess($process);
        $response->getBody()->write('Process stopped successfully.');
    } catch (Exception $e) {
        $response->getBody()->write('Error: ' . $e->getMessage());
        $response->withStatus(500);
    }
    return $response;
});

// 运行应用
$app->run();

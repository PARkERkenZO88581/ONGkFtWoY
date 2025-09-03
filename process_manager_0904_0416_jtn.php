<?php
// 代码生成时间: 2025-09-04 04:16:37
// 进程管理器
// 使用SLIM框架创建一个简单的进程管理器
// 这个进程管理器可以启动和停止进程

require 'vendor/autoload.php';

// 引入Slim框架的依赖
use Slim\Factory\AppFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

// 进程管理类
class ProcessManager {
    private $process;

    // 启动进程
    public function startProcess($command) {
        try {
            $this->process = new Symfony\Component\Process\Process($command);
            $this->process->start();
            return '进程启动成功';
        } catch (Exception $e) {
            return '进程启动失败: ' . $e->getMessage();
        }
    }

    // 停止进程
    public function stopProcess() {
        try {
            if ($this->process) {
                $this->process->stop();
                return '进程停止成功';
            } else {
                return '没有正在运行的进程';
            }
        } catch (Exception $e) {
            return '进程停止失败: ' . $e->getMessage();
        }
    }
}

// 创建Slim应用
AppFactory::setCodingStandard(new \Slim\Psr7\CodingStandard());
$app = AppFactory::create();

// 定义启动进程的路由
$app->post('/start-process', function (Request $request, Response $response, $args) {
    $processManager = new ProcessManager();
    $command = $request->getParsedBody()['command'];
    $result = $processManager->startProcess($command);
    return $response->getBody()->write($result);
});

// 定义停止进程的路由
$app->post('/stop-process', function (Request $request, Response $response, $args) {
    $processManager = new ProcessManager();
    $result = $processManager->stopProcess();
    return $response->getBody()->write($result);
});

// 运行应用
$app->run();

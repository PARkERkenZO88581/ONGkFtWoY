<?php
// 代码生成时间: 2025-09-11 08:11:58
// bulk_file_renamer.php
// 使用Slim框架实现的批量文件重命名工具

require 'vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

// 定义批量文件重命名工具
class BulkFileRenamer {
    private $sourceDirectory;
    private $targetDirectory;
    private $filePattern;
    private $replacement;

    public function __construct($sourceDirectory, $targetDirectory, $filePattern, $replacement) {
        $this->sourceDirectory = $sourceDirectory;
        $this->targetDirectory = $targetDirectory;
        $this->filePattern = $filePattern;
        $this->replacement = $replacement;
    }

    // 执行批量文件重命名操作
    public function renameFiles() {
        if (!file_exists($this->sourceDirectory) || !is_dir($this->sourceDirectory)) {
            throw new Exception("Source directory does not exist.");
        }

        if (!file_exists($this->targetDirectory) || !is_dir($this->targetDirectory)) {
            throw new Exception("Target directory does not exist.");
        }

        $files = scandir($this->sourceDirectory);
        foreach ($files as $file) {
            if (preg_match($this->filePattern, $file)) {
                $newName = preg_replace($this->filePattern, $this->replacement, $file);
                if (!rename($this->sourceDirectory . '/' . $file, $this->targetDirectory . '/' . $newName)) {
                    throw new Exception("Failed to rename file: " . $file . " to " . $newName);
                }
            }
        }
    }
}

// 设置Slim应用
$app = AppFactory::create();

// POST路由，接收重命名参数
$app->post('/rename', function (Request $request, Response $response, array $args) {
    try {
        $data = $request->getParsedBody();
        $sourceDir = $data['source'] ?? '';
        $targetDir = $data['target'] ?? '';
        $filePattern = $data['pattern'] ?? '';
        $replacement = $data['replacement'] ?? '';

        $renamer = new BulkFileRenamer($sourceDir, $targetDir, $filePattern, $replacement);
        $renamer->renameFiles();

        $response->getBody()->write("Files renamed successfully.");

        return $response->withHeader('Content-Type', 'text/plain')->withStatus(200);
    } catch (Exception $e) {
        $response->getBody()->write("Error: " . $e->getMessage());
        return $response->withHeader('Content-Type', 'text/plain')->withStatus(500);
    }
});

// 运行应用
$app->run();
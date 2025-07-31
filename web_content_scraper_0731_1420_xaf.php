<?php
// 代码生成时间: 2025-07-31 14:20:48
// WebContentScraper.php
// 使用Slim框架实现的网页内容抓取工具

require 'vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

// 创建Slim应用
AppFactory::setCodingStylePsr4();
AppFactory::define(function (): void {
    $app = AppFactory::create();

    // 抓取网页内容的路由
    $app->get('/scrape/{url}', function (Request $request, Response $response, $args) {
        // 获取URL参数
        $url = $args['url'];

        // 错误处理
        if (empty($url)) {
            return $response->withJson([
                'error' => 'URL parameter is missing.'
            ], 400);
        }

        try {
            // 使用cURL抓取网页内容
            $content = file_get_contents($url);
            if ($content === false) {
                throw new Exception('Failed to retrieve content from URL.');
            }

            // 返回网页内容
            return $response->withJson([
                'url' => $url,
                'content' => $content
            ]);
        } catch (Exception $e) {
            // 错误处理
            return $response->withJson([
                'error' => $e->getMessage()
            ], 500);
        }
    });

    // 运行应用
    $app->run();
});

// 注意：确保你已经安装了Slim框架和它的依赖项。
// 使用composer require slim/slim来安装Slim框架。

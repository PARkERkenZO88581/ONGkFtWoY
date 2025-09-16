<?php
// 代码生成时间: 2025-09-16 18:11:20
// 使用Slim框架创建的网页内容抓取工具
require 'vendor/autoload.php';

// 引入GuzzleHttp客户端库
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Slim\Factory\ServerRequestCreator;
use Slim\Factory\ServerRequestFactory;
use Slim\Factory\ResponseFactory;
use Slim\Factory\StreamFactory;

// 创建请求、响应和流工厂
$requestFactory = new ServerRequestFactory();
$responseFactory = new ResponseFactory();
$streamFactory = new StreamFactory();

// 创建Slim应用
$app = \$app = \$c = new \Slim\App(["settings" => ["displayErrorDetails" => true]]);

// 路由配置
$app->get('/scrape/{url}', function (\$request, \$response, \$args) {
    // 获取传入的URL参数
    \$url = \$args['url'];

    // 定义GuzzleHttp客户端
    \$client = new Client();

    try {
        // 发送GET请求到指定的URL
        \$response = \$client->request('GET', \$url);

        // 检查响应状态
        if (\$response->getStatusCode() === 200) {
            // 获取响应体内容
            \$content = \$response->getBody()->getContents();

            // 设置响应体和状态码
            return \$response->write(\$content)->withStatus(200);
        } else {
            // 如果响应状态码不是200，则返回错误信息
            return \$response->withStatus(\$response->getStatusCode())->withBody(\$streamFactory->createStream("Failed to fetch content. Status code: " . \$response->getStatusCode()));
        }
    } catch (Exception \$e) {
        // 错误处理
        return \$response->withStatus(500)->withBody(\$streamFactory->createStream("Error: " . \$e->getMessage()));
    }
});

// 运行应用
\$app->run();

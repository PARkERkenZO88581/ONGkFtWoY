<?php
// 代码生成时间: 2025-08-17 12:31:40
// 引入Slim框架的依赖
use Slim\Slim;
use Slim\Http\Request;
use Slim\Http\Response;

// 定义XSS防护的类
class XssProtection extends Slim {

    // 构造函数
    public function __construct() {
        // 调用父类的构造函数
        parent::__construct();

        // 设置路由
        $this->get('/xss', 'self::xssHandler');
    }

    // XSS处理函数
    public static function xssHandler(Request $request, Response $response) {
        // 获取用户输入
        $userInput = $request->getQueryParam('input', '');

        // 对用户输入进行XSS过滤
        $cleanInput = self::filterXss($userInput);

        // 将过滤后的输入作为响应内容
        $response->write($cleanInput);

        // 返回响应
        return $response;
    }

    // XSS过滤函数
    public static function filterXss($input) {
        // 使用strip_tags函数移除所有HTML标签
        $filteredInput = strip_tags($input);

        // 使用htmlspecialchars函数对特殊字符进行转义，防止XSS攻击
        $safeInput = htmlspecialchars($filteredInput, ENT_QUOTES, 'UTF-8');

        // 返回过滤后的字符串
        return $safeInput;
    }

}

// 创建XssProtection的实例并运行
$xssProtection = new XssProtection();
$xssProtection->run();
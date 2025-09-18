<?php
// 代码生成时间: 2025-09-18 23:27:45
// 引入Slim框架
use Slim\Factory\AppFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use PDO;

// 使用预定义的异常类来处理错误
use PDOException;
use InvalidArgumentException;

// 初始化Slim框架
AppFactory::setCodingStylePreset(AppFactory::CODING_STYLE_PSR_12);
$app = AppFactory::create();

// 创建数据库连接
function getDb(): PDO {
    $host = 'localhost';
    $db   = 'your_database';
    $user = 'your_username';
    $pass = 'your_password';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
    } catch (PDOException $e) {
        throw new InvalidArgumentException("Database connection error: " . $e->getMessage());
    }

    return $pdo;
}

// 实现防止SQL注入的函数
function safeQuery(PDO $db, string $query, array $params): array {
    try {
        $stmt = $db->prepare($query);
        foreach ($params as $key => &$value) {
            $stmt->bindParam($key, $value);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        // 处理SQL错误
        return ["error" => "SQL error: " . $e->getMessage()];
    }
}

// 定义SQL注入防护的路由
$app->get('/search', function (Request $request, Response $response, $args) {
    $db = getDb();
    $searchTerm = $request->getQueryParams()['search'] ?? '';
    if (empty($searchTerm)) {
        return $response->withJson(["error" => "Search term is required."]);
    }
    // 使用预处理语句防止SQL注入
    $query = "SELECT * FROM users WHERE name LIKE :searchTerm";
    $params = [":searchTerm" => "$searchTerm%"];

    $results = safeQuery($db, $query, $params);
    return $response->withJson($results);
});

// 运行应用
$app->run();
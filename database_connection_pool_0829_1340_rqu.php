<?php
// 代码生成时间: 2025-08-29 13:40:04
// database_connection_pool.php

// 使用Slim框架的依赖注入容器来管理数据库连接池
use Psr\Container\ContainerInterface;
use PDO;
use PDOException;

class DatabaseConnectionPool {
    /**
     * 构造函数
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    /**
     * 获取数据库连接
     * @return PDO
     */
    public function getConnection(): PDO {
        try {
            // 检查容器中是否已有连接
            if (!$this->container->has(PDO::class)) {
                // 没有连接则创建新的连接并存储在容器中
                $dsn = 'mysql:host=' . $this->container->get('settings')['db_host'] . ';dbname=' . $this->container->get('settings')['db_name'];
                $username = $this->container->get('settings')['db_user'];
                $password = $this->container->get('settings')['db_password'];

                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ];

                $pdo = new PDO($dsn, $username, $password, $options);
                $this->container->set(PDO::class, $pdo);
            }

            return $this->container->get(PDO::class);
        } catch (PDOException $e) {
            // 错误处理
            throw new PDOException($e->getMessage(), (int) $e->getCode());
        }
    }
}

// 在Slim应用中注册DatabaseConnectionPool服务
$app->getContainer()['dbConnectionPool'] = function ($c) {
    return new DatabaseConnectionPool($c);
};

<?php
// 代码生成时间: 2025-08-12 20:57:44
// 使用Slim框架创建用户权限管理系统
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

// 定义用户权限的类
class PermissionService {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // 添加用户权限
    public function addUserPermission($userId, $permission) {
        $stmt = $this->db->prepare('INSERT INTO user_permissions (user_id, permission) VALUES (?, ?)');
        $stmt->execute([$userId, $permission]);
    }

    // 删除用户权限
    public function deleteUserPermission($userId, $permission) {
        $stmt = $this->db->prepare('DELETE FROM user_permissions WHERE user_id = ? AND permission = ?');
        $stmt->execute([$userId, $permission]);
    }

    // 检查用户是否具有特定权限
    public function hasPermission($userId, $permission) {
        $stmt = $this->db->prepare('SELECT * FROM user_permissions WHERE user_id = ? AND permission = ?');
        $stmt->execute([$userId, $permission]);
        $result = $stmt->fetch();
        return $result ? true : false;
    }
}

// 创建Slim应用
$app = AppFactory::create();

// 数据库连接（示例使用PDO）
$db = new PDO('mysql:host=localhost;dbname=user_permission_db', 'username', 'password');
$permissionService = new PermissionService($db);

// 添加用户权限路由
$app->post('/add-permission', function ($request, $response, $args) use ($permissionService) {
    $userId = $request->getParam('user_id');
    $permission = $request->getParam('permission');
    try {
        $permissionService->addUserPermission($userId, $permission);
        $response->getBody()->write('Permission added successfully');
    } catch (Exception $e) {
        $response->getBody()->write('Error: ' . $e->getMessage());
    }
    return $response;
});

// 删除用户权限路由
$app->post('/delete-permission', function ($request, $response, $args) use ($permissionService) {
    $userId = $request->getParam('user_id');
    $permission = $request->getParam('permission');
    try {
        $permissionService->deleteUserPermission($userId, $permission);
        $response->getBody()->write('Permission deleted successfully');
    } catch (Exception $e) {
        $response->getBody()->write('Error: ' . $e->getMessage());
    }
    return $response;
});

// 检查用户权限路由
$app->get('/has-permission', function ($request, $response, $args) use ($permissionService) {
    $userId = $request->getParam('user_id');
    $permission = $request->getParam('permission');
    try {
        $hasPermission = $permissionService->hasPermission($userId, $permission);
        $response->getBody()->write($hasPermission ? 'User has permission' : 'User does not have permission');
    } catch (Exception $e) {
        $response->getBody()->write('Error: ' . $e->getMessage());
    }
    return $response;
});

// 运行Slim应用
$app->run();
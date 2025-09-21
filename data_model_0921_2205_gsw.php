<?php
// 代码生成时间: 2025-09-21 22:05:39
// data_model.php
// 使用Slim框架的数据模型设计

/**
 * 数据模型基类
 * 提供基本的数据访问和错误处理功能
 */
class BaseModel {
    protected \$db;

    public function __construct(\$db) {
        \$this->db = \$db;
    }

    // 获取数据
    public function get(\$query, \$params = []) {
        try {
            \$stmt = \$this->db->prepare(\$query);
            \$stmt->execute(\$params);
            return \$stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException \$e) {
            // 错误处理
            error_log(\$e->getMessage());
            return null;
        }
    }

    // 插入数据
    public function insert(\$query, \$params = []) {
        try {
            \$stmt = \$this->db->prepare(\$query);
            \$stmt->execute(\$params);
            return \$this->db->lastInsertId();
        } catch (PDOException \$e) {
            // 错误处理
            error_log(\$e->getMessage());
            return null;
        }
    }

    // 更新数据
    public function update(\$query, \$params) {
        try {
            \$stmt = \$this->db->prepare(\$query);
            \$stmt->execute(\$params);
            return \$stmt->rowCount();
        } catch (PDOException \$e) {
            // 错误处理
            error_log(\$e->getMessage());
            return null;
        }
    }

    // 删除数据
    public function delete(\$query, \$params) {
        try {
            \$stmt = \$this->db->prepare(\$query);
            \$stmt->execute(\$params);
            return \$stmt->rowCount();
        } catch (PDOException \$e) {
            // 错误处理
            error_log(\$e->getMessage());
            return null;
        }
    }
}

/**
 * 用户数据模型
 * 继承自BaseModel，提供用户相关的数据操作
 */
class UserModel extends BaseModel {

    // 获取用户列表
    public function getUserList() {
        \$query = "SELECT * FROM users";
        return \$this->get(\$query);
    }

    // 获取单个用户信息
    public function getUserById(\$id) {
        \$query = "SELECT * FROM users WHERE id = ?";
        return \$this->get(\$query, [\$id]);
    }

    // 添加新用户
    public function addUser(\$username, \$email) {
        \$query = "INSERT INTO users (username, email) VALUES (?, ?)";
        return \$this->insert(\$query, [\$username, \$email]);
    }

    // 更新用户信息
    public function updateUser(\$id, \$username, \$email) {
        \$query = "UPDATE users SET username = ?, email = ? WHERE id = ?";
        return \$this->update(\$query, [\$username, \$email, \$id]);
    }

    // 删除用户
    public function deleteUser(\$id) {
        \$query = "DELETE FROM users WHERE id = ?";
        return \$this->delete(\$query, [\$id]);
    }
}

// 注意：在实际使用中，需要确保数据库连接已经正确建立并传递给模型类

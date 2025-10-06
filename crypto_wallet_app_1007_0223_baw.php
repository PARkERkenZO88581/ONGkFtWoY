<?php
// 代码生成时间: 2025-10-07 02:23:29
// crypto_wallet_app.php
require 'vendor/autoload.php';

// 使用SLIM框架创建一个应用
$app = new \Slim\App();

// 钱包服务类
class CryptoWalletService {
    private $wallets = [];

    // 添加钱包
    public function addWallet($id, $balance = 0) {
        $this->wallets[$id] = $balance;
        return true;
    }

    // 获取钱包余额
    public function getBalance($id) {
        if (!array_key_exists($id, $this->wallets)) {
            throw new Exception('Wallet not found');
        }
        return $this->wallets[$id];
    }

    // 存入金额
    public function deposit($id, $amount) {
        if (!array_key_exists($id, $this->wallets)) {
            throw new Exception('Wallet not found');
        }
        $this->wallets[$id] += $amount;
        return $this->wallets[$id];
    }

    // 提取金额
    public function withdraw($id, $amount) {
        if (!array_key_exists($id, $this->wallets)) {
            throw new Exception('Wallet not found');
        }
        if ($this->wallets[$id] < $amount) {
            throw new Exception('Insufficient funds');
        }
        $this->wallets[$id] -= $amount;
        return $this->wallets[$id];
    }
}

// 钱包服务实例
$walletService = new CryptoWalletService();

// 添加钱包路由
$app->post('/wallet/add', function ($request, $response, $args) use ($walletService) {
    $id = $request->getParsedBody()['id'];
    $balance = $request->getParsedBody()['balance'] ?? 0;
    try {
        $walletService->addWallet($id, $balance);
        return $response->withJson(['status' => 'success', 'message' => 'Wallet added'], 201);
    } catch (Exception $e) {
        return $response->withJson(['status' => 'error', 'message' => $e->getMessage()], 400);
    }
});

// 获取钱包余额路由
$app->get('/wallet/{id}/balance', function ($request, $response, $args) use ($walletService) {
    $id = $args['id'];
    try {
        $balance = $walletService->getBalance($id);
        return $response->withJson(['status' => 'success', 'balance' => $balance]);
    } catch (Exception $e) {
        return $response->withJson(['status' => 'error', 'message' => $e->getMessage()], 404);
    }
});

// 存入金额路由
$app->post('/wallet/{id}/deposit', function ($request, $response, $args) use ($walletService) {
    $id = $args['id'];
    $amount = $request->getParsedBody()['amount'];
    try {
        $balance = $walletService->deposit($id, $amount);
        return $response->withJson(['status' => 'success', 'balance' => $balance]);
    } catch (Exception $e) {
        return $response->withJson(['status' => 'error', 'message' => $e->getMessage()], 400);
    }
});

// 提取金额路由
$app->post('/wallet/{id}/withdraw', function ($request, $response, $args) use ($walletService) {
    $id = $args['id'];
    $amount = $request->getParsedBody()['amount'];
    try {
        $balance = $walletService->withdraw($id, $amount);
        return $response->withJson(['status' => 'success', 'balance' => $balance]);
    } catch (Exception $e) {
        return $response->withJson(['status' => 'error', 'message' => $e->getMessage()], 400);
    }
});

// 运行应用
$app->run();

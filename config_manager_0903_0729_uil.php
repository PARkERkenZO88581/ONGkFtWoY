<?php
// 代码生成时间: 2025-09-03 07:29:15
// config_manager.php
// 配置文件管理器

use Psr\Container\ContainerInterface as Container;
use Slim\Factory\AppFactory;
use DI\Container;
use DI\Bridge\Slim\Bridge;

// 配置管理器类
class ConfigManager {
    /**
     * @var Container 依赖注入容器
     */
    private $container;

    public function __construct(Container $container) {
        $this->container = $container;
    }

    /**
     * 读取配置文件
     *
     * @param string $filename 配置文件名
     * @return mixed 配置数据
     * @throws Exception 如果文件不存在或读取失败
     */
    public function readConfig(string $filename) {
        $filePath = __DIR__ . "/../../config/{$filename}.php";
        if (!file_exists($filePath)) {
            throw new Exception("Config file not found: {$filename}");
        }

        return include $filePath;
    }

    /**
     * 写入配置文件
     *
     * @param string $filename 配置文件名
     * @param array $data 要写入的数据
     * @return bool 写入成功或失败
     * @throws Exception 如果写入失败
     */
    public function writeConfig(string $filename, array $data): bool {
        $filePath = __DIR__ . "/../../config/{$filename}.php";
        $content = '<?php
return ' . var_export($data, true) . ';';

        if (file_put_contents($filePath, $content) === false) {
            throw new Exception("Failed to write config file: {$filename}");
        }

        return true;
    }
}

// 设置自动加载
require __DIR__ . '/../vendor/autoload.php';

// 创建容器
$container = new Container();

// 创建并配置应用
$app = AppFactory::create();
$app->add(Container::class, $container);
$app->add(ConfigManager::class);

// 定义路由
$app->get('/config/{filename}', function (Request $request, Response $response, $args) {
    $filename = $args['filename'];
    $configManager = $this->getContainer()->get(ConfigManager::class);
    try {
        $configData = $configManager->readConfig($filename);
        $response->getBody()->write(json_encode($configData));
    } catch (Exception $e) {
        $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
    }
    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(200);
});

// 运行应用
$app->run();
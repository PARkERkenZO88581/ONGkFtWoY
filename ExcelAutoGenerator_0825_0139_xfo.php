<?php
// 代码生成时间: 2025-08-25 01:39:48
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Psr\Container\ContainerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

// ExcelAutoGenerator.php
// 该SLIM框架程序用于自动生成Excel表格

// 引入依赖库
require 'vendor/autoload.php';

// 定义Excel表格自动生成器类
class ExcelAutoGenerator {
    private $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    // 生成Excel表格
    public function generateExcel(array $data): string {
        try {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            foreach ($data as $row => $rowData) {
                $column = 1;
                foreach ($rowData as $cellValue) {
                    $sheet->setCellValueByColumnAndRow($column, $row + 1, $cellValue);
                    $column++;
                }
            }
            $writer = new Xlsx($spreadsheet);
            $filename = 'GeneratedExcel.xlsx';
            $writer->save($filename);
            return $filename;
        } catch (\PhpOffice\PhpSpreadsheet\Writer\Exception $e) {
            // 错误处理
            return "There was an error generating the Excel file: " . $e->getMessage();
        }
    }
}

// 设置路由以响应生成Excel表格的请求
$app = AppFactory::create();

$app->get('/generate-excel', function (Request $request, Response $response, array $args) {
    // 示例数据，实际使用时应从数据库或用户输入获取
    $sampleData = [
        [1, 'John Doe', 'john@example.com'],
        [2, 'Jane Doe', 'jane@example.com']
    ];

    // 获取Excel表格自动生成器实例
    $excelGenerator = $this->get(ExcelAutoGenerator::class);
    $filename = $excelGenerator->generateExcel($sampleData);

    // 返回生成的Excel文件名
    return $response->getBody()->write($filename);
});

// 运行应用
$app->run();

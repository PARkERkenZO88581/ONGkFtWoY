<?php
// 代码生成时间: 2025-09-20 22:28:10
// File: file_backup_sync.php
// Description: A file backup and synchronization tool using PHP and SLIM framework.

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
require 'vendor/autoload.php';

$app = new \Slim\App();

// Define the source and destination directories
const SOURCE_DIR = "/path/to/source/";
const DESTINATION_DIR = "/path/to/destination/";

// Backup and sync files from source to destination
$app->get('/backup-sync', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Starting backup and synchronization...\
");
    try {
        // Check if the source and destination directories exist
        if (!is_dir(SOURCE_DIR) || !is_dir(DESTINATION_DIR)) {
            throw new \Exception('Source or destination directory does not exist.');
        }

        // Perform the backup and synchronization
        $backupResult = backupSyncFiles(SOURCE_DIR, DESTINATION_DIR);

        // Return the result
        $response->getBody()->write("Backup and synchronization completed.\
" . $backupResult);
    } catch (Exception $e) {
        // Handle any exceptions and return the error message
        $response->getBody()->write("Error: " . $e->getMessage() . "\
");
    }
    return $response;
});

// Define the backupSyncFiles function
function backupSyncFiles($sourceDir, $destinationDir) {
    // Initialize the result message
    $result = "\
";

    // Get all files in the source directory
    $files = scandir($sourceDir);
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            $sourceFile = $sourceDir . $file;
            $destinationFile = $destinationDir . $file;

            // Check if the file already exists in the destination directory
            if (file_exists($destinationFile)) {
                // If the file exists, check if it's different from the source file
                if (file_get_contents($sourceFile) != file_get_contents($destinationFile)) {
                    // If different, update the file in the destination directory
                    copy($sourceFile, $destinationFile);
                    $result .= "Updated file: $file\
";
                }
            } else {
                // If the file does not exist, copy it from the source directory
                copy($sourceFile, $destinationFile);
                $result .= "Copied file: $file\
";
            }
        }
    }
    return $result;
}

$app->run();
<?php
session_start();

date_default_timezone_set('Europe/Kiev');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/core/autoload.php';
require_once '../app/core/helpers.php';

try {
    $app = new Core\App();
} catch (Exception $e) {
    echo "Application Error: " . $e->getMessage();
    echo "<br><br>Debug info:<br>";
    echo "Current working directory: " . getcwd() . "<br>";
    echo "Script directory: " . __DIR__ . "<br>";
    echo "Parent directory: " . dirname(__DIR__) . "<br>";

    echo "<br>Controller files check:<br>";
    echo "HomeController.php exists: " . (file_exists(__DIR__ . '/../app/controllers/HomeController.php') ? 'YES' : 'NO') . "<br>";
    echo "UserController.php exists: " . (file_exists(__DIR__ . '/../app/controllers/UserController.php') ? 'YES' : 'NO') . "<br>";
    echo "AuthController.php exists: " . (file_exists(__DIR__ . '/../app/controllers/AuthController.php') ? 'YES' : 'NO') . "<br>";

    exit;
}
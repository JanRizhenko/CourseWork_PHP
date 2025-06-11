<?php

require_once '../app/core/autoload.php';

try {
    $app = new Core\App();
} catch (Exception $e) {
    echo "Application Error: " . $e->getMessage();
    echo "<br><br>Debug info:<br>";
    echo "Current working directory: " . getcwd() . "<br>";
    echo "Script directory: " . __DIR__ . "<br>";
    echo "Parent directory: " . dirname(__DIR__) . "<br>";

    echo "<br>Controller files check:<br>";
    echo "HomeController.php exists: " . (file_exists('../app/controllers/HomeController.php') ? 'YES' : 'NO') . "<br>";
    echo "UserController.php exists: " . (file_exists('../app/controllers/UserController.php') ? 'YES' : 'NO') . "<br>";

    exit;
}
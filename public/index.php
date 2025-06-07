<?php

ob_start();

require_once __DIR__ . '/../app/core/autoload.php';

use Core\App;

$app = new App();

echo ob_get_clean();

<?php

spl_autoload_register(function ($class) {
    $baseDir = dirname(__DIR__) . '/';

    $classPath = str_replace('\\', '/', $class);

    if (strpos($class, 'App\\Controllers\\') === 0) {
        $className = str_replace('App\\Controllers\\', '', $class);
        $file = $baseDir . 'controllers/' . $className . '.php';
    } elseif (strpos($class, 'App\\Models\\') === 0) {
        $className = str_replace('App\\Models\\', '', $class);
        $file = $baseDir . 'models/' . $className . '.php';
    } elseif (strpos($class, 'Core\\') === 0) {
        $className = str_replace('Core\\', '', $class);
        $file = $baseDir . 'core/' . $className . '.php';
    } else {
        $file = $baseDir . '../' . $classPath . '.php';
    }

    error_log("Autoloader: Trying to load class: $class");
    error_log("Autoloader: File path: $file");
    error_log("Autoloader: File exists: " . (file_exists($file) ? 'YES' : 'NO'));

    if (file_exists($file)) {
        require_once $file;
        error_log("Autoloader: Successfully loaded: $file");
        return true;
    }

    return false;
});
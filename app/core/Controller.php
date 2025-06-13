<?php

namespace Core;

class Controller
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    protected function view(string $view, array $data = []): void
    {
        $baseDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR;
        $viewPath = $baseDir . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $view) . '.php';

        if (!file_exists($viewPath)) {
            $debugInfo = [
                'Requested View' => $view,
                'Full Path Attempted' => $viewPath,
                'Base Directory' => $baseDir,
                'Directory Exists' => is_dir($baseDir) ? 'Yes' : 'No',
                'Available Views' => is_dir($baseDir) ? scandir($baseDir) : []
            ];

            throw new \RuntimeException("View file not found. Debug info: " . print_r($debugInfo, true));
        }

        extract($data, EXTR_SKIP);
        require $viewPath;
    }

    protected function model(string $model): object
    {
        $modelClass = "App\\Models\\" . ucfirst($model);

        if (!class_exists($modelClass)) {
            throw new \RuntimeException("Model class {$modelClass} not found");
        }

        return new $modelClass();
    }

    protected function redirect(string $url, int $statusCode = 302): void
    {
        header("Location: {$url}", true, $statusCode);
        exit;
    }

    protected function jsonResponse(array $data, int $statusCode = 200): void
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }
}
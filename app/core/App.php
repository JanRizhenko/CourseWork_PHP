<?php

namespace Core;

use Exception;

class App
{
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected array $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();

        if (!empty($url[0]) && file_exists('../app/controllers/' . ucfirst($url[0]) . 'Controller.php')) {
            $this->controller = ucfirst($url[0]) . 'Controller';
            unset($url[0]);
        }

        $controllerClass = 'App\\Controllers\\' . $this->controller;

        if (!class_exists($controllerClass)) {
            throw new Exception("Controller class {$controllerClass} not found. Make sure the file exists and has the correct namespace.");
        }

        $this->controller = new $controllerClass;

        if (!empty($url[1])) {
            $methodName = $this->convertToCamelCase($url[1]);

            if (method_exists($this->controller, $methodName)) {
                $this->method = $methodName;
                unset($url[1]);
            } else {
                if (method_exists($this->controller, $url[1])) {
                    $this->method = $url[1];
                    unset($url[1]);
                } else {
                    if (!method_exists($this->controller, 'index')) {
                        throw new Exception("Method '{$url[1]}' not found in controller and no default 'index' method available.");
                    }
                    array_unshift($url, $url[1]);
                    unset($url[1]);
                }
            }
        } else {
            if (!method_exists($this->controller, $this->method)) {
                throw new Exception("Default method 'index' not found in controller " . get_class($this->controller));
            }
        }

        $this->params = array_values($url ?? []);

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    private function parseUrl(): array
    {
        $url = $_GET['url'] ?? '';
        return explode('/', filter_var(rtrim($url, '/'), FILTER_SANITIZE_URL));
    }

    private function convertToCamelCase(string $string): string
    {
        return lcfirst(str_replace('-', '', ucwords($string, '-')));
    }
}
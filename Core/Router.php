<?php

use App\TwigConfig;

class Router
{
    private $routes = [];

    public function with($prefix, $callback)
    {
        call_user_func($callback, $this, $prefix);
    }

    public function respondWithController($args)
    {
        list($method, $route, $controller) = $args;

        // Rozdzielanie kontrolera i metody
        list($controllerName, $controllerMethod) = explode('@', $controller);

        // Dodawanie ścieżki do routera
        $this->routes[] = [
            'method' => $method,
            'route' => $route,
            'controller' => $controllerName,
            'method_name' => $controllerMethod
        ];

    }

    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        foreach ($this->routes as $route) {
            // Sprawdzanie, czy metoda i ścieżka pasują
            if ($route['method'] === $method && preg_match($this->compileRouteRegex($route['route']), $uri, $matches)) {
                // Wywołanie kontrolera i metody
                $controllerName = $route['controller'];
                $controllerMethod = $route['method_name'];

                // Sprawdzenie, czy kontroler istnieje
                if (class_exists($controllerName)) {
                    $controllerInstance = new $controllerName();
                    // Sprawdzenie, czy metoda kontrolera istnieje
                    if (method_exists($controllerInstance, $controllerMethod)) {
                        $controllerInstance->$controllerMethod($matches);
                        return;
                    } else {
                        echo TwigConfig::getTwig()->render('404.twig');
                        return;
                    }
                } else {
                    echo TwigConfig::getTwig()->render('404.twig');
                    return;
                }
            }
        }

        echo TwigConfig::getTwig()->render('404.twig');
    }

    private function compileRouteRegex($route)
    {
        // Zamiana parametrów na regex
        $regex = preg_replace_callback('#\{(\w+)\}#', function ($matches) {
            return "(?P<{$matches[1]}>[\w-]+)";
        }, $route);

        // Dodanie anchorów dla całego ciągu
        $regex = "#^" . $regex . "$#";

        return $regex;
    }
    
}


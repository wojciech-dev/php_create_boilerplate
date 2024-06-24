<?php

use App\TwigConfig;


class Router
{
    private $routes = [];
    private $originalParamNames = [];

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

    public function respondWithControllerMultiple($method, $baseRoute, $controller, $depth)
    {
        if ($depth === 0) {
            $this->respondWithController([$method, $baseRoute, $controller . '@index']);
            $this->respondWithController([$method, $baseRoute . ',more-{id}', $controller . '@show']);
        } else {
            $this->addNestedRoutes($method, $baseRoute, $controller, $depth);
        }
    }

    private function addNestedRoutes($method, $baseRoute, $controller, $depth)
    {
        for ($i = 0; $i <= $depth; $i++) {
            $route = $baseRoute;
            for ($j = 1; $j <= $i; $j++) {
                $route .= '/{param' . $j . '}';
            }
            $this->respondWithController([$method, $route, $controller . '@index']);
            $this->respondWithController([$method, $route . ',more-{id}', $controller . '@show']);
        }
    }

    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($this->compileRouteRegex($route['route']), $uri, $matches)) {
                $controllerName = $route['controller'];
                $controllerMethod = $route['method_name'];
                if (class_exists($controllerName)) {
                    $controllerInstance = new $controllerName();
                    if (method_exists($controllerInstance, $controllerMethod)) {
                        $params = [];
                        foreach ($matches as $key => $value) {
                            if (!is_numeric($key)) {
                                // Mapowanie unikalnych nazw parametrów na oryginalne nazwy
                                $originalKey = $this->getOriginalParamName($key);
                                $params[$originalKey] = $value;
                            }
                        }

                        $controllerInstance->$controllerMethod($params);
                        return;
                    } else {
                        $this->renderNotFound();
                        return;
                    }
                } else {
                    $this->renderNotFound();
                    return;
                }
            }
        }

        $this->renderNotFound();
    }

    private function renderNotFound()
    {
        echo TwigConfig::getTwig()->render('404.twig');
    }

    private function compileRouteRegex($route)
    {
        // Generowanie unikalnych nazw parametrów
        static $paramCount = 0;
        $regex = preg_replace_callback('#\{(\w+)\}#', function ($matches) use (&$paramCount) {
            $paramName = $matches[1] . $paramCount++;
            $this->originalParamNames[$paramName] = $matches[1]; // Zapisywanie oryginalnej nazwy parametru
            return "(?P<{$paramName}>[\w-]+)";
        }, $route);
        $regex = "#^" . $regex . "$#";
        return $regex;
    }

    private function getOriginalParamName($paramName)
    {
        return $this->originalParamNames[$paramName] ?? $paramName;
    }
}

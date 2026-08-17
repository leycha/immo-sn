<?php
namespace Core;

class Router {
    private array $routes = [];

    public function add(string $method, string $path, array $controllerAction): void {
        $this->routes[strtoupper($method)][$path] = $controllerAction;
    }

    public function dispatch(string $uri, string $method): void {
        // Nettoyage de l'URL
        $uri = parse_url($uri, PHP_URL_PATH);
        
        if (isset($this->routes[$method][$uri])) {
            [$controllerName, $action] = $this->routes[$method][$uri];
            $controllerName = "App\\Controllers\\" . $controllerName;
            
            if (class_exists($controllerName)) {
                $controller = new $controllerName();
                if (method_exists($controller, $action)) {
                    $controller->$action();
                    return;
                }
            }
        }
        
        // Gestion de l'erreur 404
        http_response_code(404);
        echo "Page non trouvée (404)";
    }
}
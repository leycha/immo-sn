<?php

namespace Core;

class Router
{
    private array $routes = [];
    private array $dynamicRoutes = [];

    /**
     * Enregistrer une route statique.
     */
    public function add(
        string $method,
        string $path,
        array $controllerAction
    ): void {
        $method = strtoupper($method);
        $path = $this->normalizePath($path);

        $this->routes[$method][$path] = $controllerAction;
    }

    /**
     * Enregistrer une route dynamique.
     * Exemple : /biens/{id}
     */
    public function addDynamic(
        string $method,
        string $pattern,
        array $controllerAction
    ): void {
        $method = strtoupper($method);
        $pattern = $this->normalizePath($pattern);

        $regex = preg_quote($pattern, '#');

        $regex = preg_replace(
            '/\\\\\{([a-zA-Z_][a-zA-Z0-9_]*)\\\\\}/',
            '(?P<$1>[^/]+)',
            $regex
        );

        $this->dynamicRoutes[$method][] = [
            'regex' => '#^' . $regex . '$#',
            'action' => $controllerAction,
        ];
    }

    /**
     * Normaliser un chemin.
     */
    private function normalizePath(string $path): string
    {
        $path = '/' . trim($path, '/');

        return $path === '//' ? '/' : $path;
    }

    /**
     * Exécuter le middleware correspondant au chemin.
     */
    private function executeMiddleware(string $uri): void
    {
        $middlewareClass = null;

        if ($uri === '/admin' || str_starts_with($uri, '/admin/')) {
            $middlewareClass = \App\Middleware\AdminMiddleware::class;
        } elseif (
            $uri === '/moderateur'
            || str_starts_with($uri, '/moderateur/')
        ) {
            $middlewareClass = \App\Middleware\ModeratorMiddleware::class;
        } elseif (
            $uri === '/agence'
            || str_starts_with($uri, '/agence/')
        ) {
            $middlewareClass = \App\Middleware\AgencyMiddleware::class;
        } elseif (
            $uri === '/member'
            || str_starts_with($uri, '/member/')
        ) {
            $middlewareClass = \App\Middleware\MemberMiddleware::class;
        }

        if ($middlewareClass !== null) {
            $middlewareClass::handle();
        }
    }

    /**
     * Exécuter une action de contrôleur.
     */
    private function executeAction(
        array $controllerAction,
        array $params = []
    ): bool {
        [$controllerName, $action] = $controllerAction;

        // Construction correcte du nom complet du contrôleur.
        $controllerClass = 'App\\Controllers\\' . $controllerName;

        if (!class_exists($controllerClass)) {
            error_log(
                'Routeur ImmoSN : contrôleur introuvable : '
                . $controllerClass
            );

            return false;
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $action)) {
            error_log(
                'Routeur ImmoSN : méthode introuvable : '
                . $controllerClass . '::' . $action
            );

            return false;
        }

        $controller->$action(...$params);

        return true;
    }

    /**
     * Exécuter la route demandée.
     */
    public function dispatch(
        string $uri,
        string $method
    ): void {
        $uri = parse_url($uri, PHP_URL_PATH) ?? '/';

        // Retirer le préfixe du projet.
        $uri = preg_replace(
            '#^/immo-sn(?:/public)?(?=/|$)#',
            '',
            $uri
        );

        if ($uri === '' || $uri === null) {
            $uri = '/';
        }

        if ($uri !== '/') {
            $uri = rtrim($uri, '/');
        }

        $method = strtoupper($method);

        // ROUTES STATIQUES
        if (isset($this->routes[$method][$uri])) {
            $this->executeMiddleware($uri);

            $executed = $this->executeAction(
                $this->routes[$method][$uri]
            );

            if ($executed) {
                return;
            }

            http_response_code(500);
            echo 'Erreur interne : contrôleur ou méthode introuvable.';
            return;
        }

        // ROUTES DYNAMIQUES
        if (isset($this->dynamicRoutes[$method])) {
            foreach ($this->dynamicRoutes[$method] as $route) {
                if (preg_match($route['regex'], $uri, $matches)) {
                    $params = [];

                    foreach ($matches as $key => $value) {
                        if (is_string($key)) {
                            $params[] = $value;
                        }
                    }

                    $this->executeMiddleware($uri);

                    $executed = $this->executeAction(
                        $route['action'],
                        $params
                    );

                    if ($executed) {
                        return;
                    }

                    http_response_code(500);
                    echo 'Erreur interne : contrôleur ou méthode introuvable.';
                    return;
                }
            }
        }

        // ROUTE NON TROUVÉE
        http_response_code(404);
        echo 'Page non trouvée (404)';
    }
}

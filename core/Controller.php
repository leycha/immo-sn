<?php
namespace Core;

class Controller
{
    protected function view(string $view, array $data = [], string $layout = 'app'): void
    {
        extract($data);

        $viewPath = BASE_PATH . "/app/Views/{$view}.php";
        if (!file_exists($viewPath)) {
            http_response_code(500);
            echo "Vue introuvable : {$view} ({$viewPath})";
            return;
        }

        ob_start();
        require $viewPath;
        $content = ob_get_clean();

        $layoutPath = BASE_PATH . "/app/Views/layouts/{$layout}.php";
        if (!file_exists($layoutPath)) {
            echo $content;
            return;
        }

        require $layoutPath;
    }

    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }

    protected function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
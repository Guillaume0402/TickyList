<?php
namespace App\Core;

abstract class AbstractController
{
    protected function render(string $view, array $params = [], string $layout = 'layouts/main'): string
    {
        $viewFile = ROOT . '/src/Views/' . $view . '.php';
        $layoutFile = ROOT . '/src/Views/' . $layout . '.php';

        if (!is_file($viewFile)) {
            http_response_code(500);
            return "Vue introuvable: " . htmlspecialchars($viewFile, ENT_QUOTES, 'UTF-8');
        }

        if (!is_file($layoutFile)) {
            http_response_code(500);
            return "Layout introuvable: " . htmlspecialchars($layoutFile, ENT_QUOTES, 'UTF-8');
        }

        // 1) Render de la vue -> $content
        extract($params, EXTR_SKIP);
        ob_start();
        require $viewFile;
        $content = (string) ob_get_clean();

        // 2) Render du layout -> output final
        ob_start();
        require $layoutFile;
        return (string) ob_get_clean();
    }
}

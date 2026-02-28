<?php

declare(strict_types=1);
session_start();
ob_start();

ini_set('display_errors', '0');
ini_set('log_errors', '1');

define('ROOT', dirname(__DIR__));
ini_set('error_log', ROOT . '/var/log/php-error.log');

error_reporting(E_ALL);

// 1) Convertit warnings/notices en exceptions -> une seule sortie propre
set_error_handler(function (int $severity, string $message, string $file, int $line): bool {
    if (!(error_reporting() & $severity)) {
        return false; // erreur masquée par error_reporting
    }
    throw new ErrorException($message, 0, $severity, $file, $line);
});

// 2) Page d'erreur lisible
set_exception_handler(function (Throwable $e): void {
    http_response_code(500);

    // ✅ vide tout le buffer pour ne pas avoir du HTML “cassé” avant l’erreur
    while (ob_get_level() > 0) {
        ob_end_clean();
    }

    error_log((string)$e);
    
    $severityName = null;

    if ($e instanceof ErrorException) {
        $map = [
            E_ERROR => 'E_ERROR',
            E_WARNING => 'E_WARNING',
            E_PARSE => 'E_PARSE',
            E_NOTICE => 'E_NOTICE',
            E_DEPRECATED => 'E_DEPRECATED',
            E_USER_ERROR => 'E_USER_ERROR',
            E_USER_WARNING => 'E_USER_WARNING',
            E_USER_NOTICE => 'E_USER_NOTICE',
        ];
        $sev = $e->getSeverity();
        $severityName = $map[$sev] ?? ('E_' . $sev);
    }

    // Affichage dev propre
    echo '<!doctype html><meta charset="utf-8">';
    echo '<title>Erreur</title>';
    echo '<div style="font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; padding:16px;">';
    echo '<h2 style="margin:0 0 12px;">Erreur PHP</h2>';
    echo '<div style="margin:0 0 12px;"><strong>' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</strong></div>';
    echo '<div style="margin:0 0 12px;">' . htmlspecialchars($e->getFile(), ENT_QUOTES, 'UTF-8') . ':' . $e->getLine() . '</div>';
    if ($e instanceof ErrorException) {
        $sev = $e->getSeverity();
        echo '<div style="margin:0 0 12px;">Severity: ' . htmlspecialchars($severityName ?? 'UNKNOWN', ENT_QUOTES, 'UTF-8') . ' (' . (int)$sev . ')</div>';
    }
    echo '<pre style="background:#111; color:#eee; padding:12px; border-radius:8px; overflow:auto;">'
        . htmlspecialchars($e->getTraceAsString(), ENT_QUOTES, 'UTF-8')
        . '</pre>';
    echo '</div>';


    exit;
});

require ROOT . '/src/Services/env.php';
loadEnv(ROOT . '/.env');

require ROOT . '/src/Services/db.php';

spl_autoload_register(function (string $class) {
    $prefix = 'App\\';
    $baseDir = ROOT . '/src/';

    if (strncmp($prefix, $class, strlen($prefix)) !== 0) return;

    $relative = substr($class, strlen($prefix));
    $file = $baseDir . str_replace('\\', '/', $relative) . '.php';

    if (is_file($file)) require $file;
});

use App\Http\Router;

$router = new Router();

// Déclare tes routes ici (ou via un fichier séparé)
require ROOT . '/routes/web.php';

$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);

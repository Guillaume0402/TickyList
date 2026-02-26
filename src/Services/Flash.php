<?php
namespace App\Services;

final class Flash
{
    public static function add(string $message, string $type = 'success'): void
    {
        $_SESSION['flash'][] = ['message' => $message, 'type' => $type];
    }

    public static function pull(): array
    {
        $out = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);
        return $out;
    }
}
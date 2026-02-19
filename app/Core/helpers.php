<?php

/**
 * View Helper Functions
 * Utility functions for views
 */

function escape($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

function old($field, $default = '') {
    return $_SESSION['old'][$field] ?? $default;
}

function setOld($data) {
    $_SESSION['old'] = $data;
}

function clearOld() {
    unset($_SESSION['old']);
}

function formatDate($date, $format = 'M d, Y') {
    if (empty($date)) return '';
    return date($format, strtotime($date));
}

function formatDateTime($datetime, $format = 'M d, Y g:i A') {
    if (empty($datetime)) return '';
    return date($format, strtotime($datetime));
}

function timeAgo($datetime) {
    if (empty($datetime)) return '';
    
    $timestamp = strtotime($datetime);
    $diff = time() - $timestamp;
    
    if ($diff < 60) return 'just now';
    if ($diff < 3600) return floor($diff / 60) . ' minutes ago';
    if ($diff < 86400) return floor($diff / 3600) . ' hours ago';
    if ($diff < 604800) return floor($diff / 86400) . ' days ago';
    
    return formatDateTime($datetime);
}

function statusBadge($status) {
    $badges = [
        'todo' => '<span class="badge bg-secondary">To Do</span>',
        'doing' => '<span class="badge bg-primary">In Progress</span>',
        'done' => '<span class="badge bg-success">Done</span>',
    ];
    return $badges[$status] ?? '';
}

function priorityBadge($priority) {
    $badges = [
        1 => '<span class="badge bg-danger">High</span>',
        2 => '<span class="badge bg-warning text-dark">Medium</span>',
        3 => '<span class="badge bg-info text-dark">Low</span>',
    ];
    return $badges[$priority] ?? '';
}

function isActive($path) {
    $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    return $currentPath === $path ? 'active' : '';
}

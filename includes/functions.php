<?php
if (session_status() === PHP_SESSION_NONE) session_start();

function e($value): string {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}
function money($value): string {
    return '$' . number_format((float)$value, 2);
}
function require_login(): void {
    if (empty($_SESSION['user'])) {
        header('Location: login.php');
        exit;
    }
}

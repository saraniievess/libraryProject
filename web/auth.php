<?php

declare(strict_types=1);

session_start();

function require_login(): void
{
    if (!isset($_SESSION['role'])) {
        header('Location: index.php');
        exit(0);
    }
}

function is_admin(): bool
{
    return isset($_SESSION['role'])
        && $_SESSION['role'] === 'admin';
}

function is_user(): bool
{
    return isset($_SESSION['role'])
        && $_SESSION['role'] === 'user';
}

function is_visitor(): bool
{
    return isset($_SESSION['role'])
        && $_SESSION['role'] === 'visitor';
}

function can_add_reviews(): bool
{
    return is_admin() || is_user();
}

function can_manage_everything(): bool
{
    return is_admin();
}

function can_edit_user(string $username): bool
{
    if (is_admin()) {
        return true;
    }

    return isset($_SESSION['username'])
        && $_SESSION['username'] === $username;
}

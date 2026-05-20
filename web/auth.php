<?php

declare(strict_types=1);

session_start();

require_once("src/autoload.php");

use \app\config_factory;
use \app\pdo_factory;
use \resena\database\user_repository;
use \resena\model\user;
use \session\session_manager;

function get_session_manager(): session_manager
{
    $config_factory = new config_factory();
    $pdo_factory = new pdo_factory();
    $database_connection = $pdo_factory->create(
        $config_factory->create_production()
    );
    return new session_manager(
        $database_connection,
        session_id()
    );
}

function get_logged_user(): ?user
{
    return get_session_manager()->get_logged_in_user();
}

function require_login(): void
{
    $session_manager = get_session_manager();
    if (
        !$session_manager->is_user_logged_in()
        && !is_visitor()
    ) {
        header('Location: index.php');
        exit(0);
    }
    $session_manager->commit_last_activity();
}

function is_visitor(): bool
{
    return get_session_manager()->is_visitor();
}

function is_admin(): bool
{
    return get_session_manager()->is_admin();
}

function is_user(): bool
{
    return get_session_manager()->is_user();
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
    $user = get_logged_user();
    return $user !== null
        && $user->get_name() === $username;
}

function logout(): void
{
    get_session_manager()->logout();
}

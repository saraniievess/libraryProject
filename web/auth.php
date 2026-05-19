<?php

declare(strict_types=1);

session_start();

require_once("src/autoload.php");

use \app\config_factory;
use \app\pdo_factory;
use \resena\database\user_repository;
use \resena\model\user;

function get_logged_user(): ?user
{
    if (!isset($_SESSION['logged_in_user_id'])) {
        return null;
    }
    $config_factory = new config_factory();
    $pdo_factory = new pdo_factory();
    $database_connection = $pdo_factory->create(
        $config_factory->create_production()
    );
    $user_repository = new user_repository(
        $database_connection
    );
    return $user_repository->findUserById(
        (int)$_SESSION['logged_in_user_id']
    );
}

function require_login(): void
{
    if (
        !isset($_SESSION['logged_in_user_id'])
        && !isset($_SESSION['is_visitor'])
    ) {
        header('Location: index.php');
        exit(0);
    }

    $_SESSION['last_activity'] = time();
    if (isset($_SESSION['logged_in_user_id'])) {
        $config_factory = new config_factory();
        $pdo_factory = new pdo_factory();
        $database_connection = $pdo_factory->create(
            $config_factory->create_production()
        );
        $statement = $database_connection->prepare(
            "UPDATE sessions
            SET last_activity = NOW()
            WHERE session_hash = :session_hash"
        );
        $statement->execute([
            ":session_hash" => session_id()
        ]);
    }
}

function is_admin(): bool
{
    $user = get_logged_user();

    return $user !== null
        && $user->get_role() === 'admin';
}

function is_user(): bool
{
    $user = get_logged_user();

    return $user !== null
        && $user->get_role() === 'user';
}

function is_visitor(): bool
{
    return isset($_SESSION['is_visitor'])
        && $_SESSION['is_visitor'] === true;
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

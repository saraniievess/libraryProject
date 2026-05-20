<?php

namespace session;

use \resena\model\user;
use \resena\database\user_repository;

class session_manager
{
    public function __construct(
        private \PDO $pdo,
        private string $session_id
    ) {}

    public function is_user_logged_in(): bool
    {
        $statement = $this->pdo->prepare(
            "SELECT * FROM sessions WHERE session_hash = :session_hash
            AND last_activity > DATE_SUB(NOW(), INTERVAL 10 MINUTE)"
        );
        $statement->execute([
            ":session_hash" => $this->session_id
        ]);
        $session = $statement->fetch();
        return $session !== false;
    }

    public function get_logged_in_user(): ?user
    {
        $statement = $this->pdo->prepare(
            "SELECT * FROM sessions WHERE session_hash = :session_hash"
        );
        $statement->execute([
            ":session_hash" => $this->session_id
        ]);
        $session = $statement->fetch(\PDO::FETCH_ASSOC);
        if ($session === false) {
            return null;
        }
        $user_repository = new user_repository(
            $this->pdo
        );
        return $user_repository->findUserById(
            (int)$session['user_id']
        );
    }

    public function commit_last_activity(): void
    {
        $statement = $this->pdo->prepare(
            "UPDATE sessions
            SET last_activity = NOW()
            WHERE session_hash = :session_hash"
        );
        $statement->execute([
            ":session_hash" => $this->session_id
        ]);
    }

    public function is_admin(): bool
    {
        $user = $this->get_logged_in_user();

        return $user !== null
            && $user->get_role() === 'admin';
    }

    public function is_user(): bool
    {
        $user = $this->get_logged_in_user();

        return $user !== null
            && $user->get_role() === 'user';
    }

    public function is_visitor(): bool
    {
        return isset($_SESSION['is_visitor'])
            && $_SESSION['is_visitor'] === true;
    }

    public function logout(): void
    {
        $statement = $this->pdo->prepare(
            "DELETE FROM sessions
            WHERE session_hash = :session_hash"
        );
        $statement->execute([
            ":session_hash" => $this->session_id
        ]);
        session_unset();
        session_destroy();
    }
}

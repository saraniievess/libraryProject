<?php

namespace resena\database;

use resena\model\user;

class user_repository {
    private \PDO $pdo;
    private ?\PDOStatement $insertStatement = null;
    private ?\PDOStatement $deleteStatement = null;
    private ?\PDOStatement $updateStatement = null;
    private ?\PDOStatement $findUsersBynameStatement = null;

    public function __construct(\PDO $_pdo) {
        $this->pdo = $_pdo;
    }

    public function insertUser (user $user) : void {
        if ($this->insertStatement === null) {
            $this->insertStatement = $this->pdo->prepare(
                "INSERT INTO users (name, date_birthday)
                VALUES (:name, :date)");
        }
        $this->insertStatement->execute([
            ":name" => $user->get_name(),
            ":date" => $user->get_birthdate()->format('Y-m-d')
        ]);
        $statement = $this->pdo->query(
            "SELECT id FROM users ORDER BY id DESC LIMIT 1"
        );
        if ($statement === false) {
            throw new \Exception("database query failed");
        }
        $id = (int)$statement->fetchColumn();
        $user->set_id($id);
    }

    /**
     * @return user[]
     */
    public function getAllUsers() : array {
        $statement = $this->pdo->query("SELECT * FROM users");
        if ($statement === false) {
            throw new \Exception("database query failed");
        }
        $users = [];

        while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $user = new user(
                $row['name'],
                new \DateTime($row['date_birthday'])
            );
            $user->set_id((int)$row['id']);
            $users[] = $user;
        }
        return $users;
    }

    /**
     * @return user[]
     */
    public function findUserByName (string $name) : array {
        if ($this->findUsersBynameStatement === null) {
            $this->findUsersBynameStatement = $this->pdo->prepare("SELECT * FROM users WHERE name LIKE :name");
        }
        $this->findUsersBynameStatement->execute([
            ":name" => "%{$name}%"
        ]);
        $rows = $this->findUsersBynameStatement->fetchAll();

        $users = [];

        foreach ($rows as $row) {
            $user = new user(
                $row['name'],
                new \DateTime($row['date_birthday'])
            );
            $user->set_id((int)$row['id']);
            $users[] = $user;
        }

        return $users;
    }

    public function updateUser (user $user) : void {
        if ($this->updateStatement === null) {
            $this->updateStatement = $this->pdo->prepare
            ("UPDATE users SET
                name = :name,
                date_birthday = :date_birthday
            WHERE id = :id");
        }
        $this->updateStatement->execute([
            ":name" => $user->get_name(),
            ":date_birthday" => $user->get_birthdate()->format('Y-m-d'),
            ":id" => $user->get_id()
        ]);

        if ($this->updateStatement->rowCount() === 0) {
            throw new \Exception("user not found");
        }
    }

    public function deleteUser (user $user) : void {
        if ($this->deleteStatement === null) {
            $this->deleteStatement = $this->pdo->prepare("DELETE FROM users WHERE id = :id");
        }
        $this->deleteStatement->execute([":id" => $user->get_id()]);

        if ($this->deleteStatement->rowCount() === 0) {
            throw new \Exception("user not found");
        }
    }
}
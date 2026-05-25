<?php

namespace resena\database;

use resena\model\user;
use database\repository;

class user_repository extends repository
{
    private \PDO $pdo;
    private ?\PDOStatement $findUsersBynameStatement = null;
    private ?\PDOStatement $findUsersByIdStatement = null;
    private string $table = user::table;


    public function __construct(\PDO $_pdo)
    {
        parent::__construct($_pdo);
        $this->pdo = $_pdo;
    }


    /**
     * @return list<user>
     */
    public function getAllUsers(): array
    {
        $statement = $this->pdo->query("SELECT * FROM {$this->table}");
        if ($statement === false) {
            throw new \Exception("database query failed");
        }
        $users = [];

        while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $user = new user(
                $row['name'],
                new \DateTime($row['date_birthday']),
                $row['password'],
                $row['role']
            );
            $user->set_id((int)$row['id']);
            $users[] = $user;
        }
        return $users;
    }

    /**
     * @return list<user>
     */
    public function findUserByName(string $name): array
    {
        if ($this->findUsersBynameStatement === null) {
            $this->findUsersBynameStatement = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE name LIKE :name");
        }
        $this->findUsersBynameStatement->execute([
            ":name" => "%{$name}%"
        ]);
        $rows = $this->findUsersBynameStatement->fetchAll();

        $users = [];

        foreach ($rows as $row) {
            $user = new user(
                $row['name'],
                new \DateTime($row['date_birthday']),
                $row['password'],
                $row['role']
            );
            $user->set_id((int)$row['id']);
            $users[] = $user;
        }

        return $users;
    }

    public function findExactUserByName(string $name): ?user
    {
        $statement = $this->pdo->prepare(
            "SELECT * FROM {$this->table} WHERE LOWER(name) = LOWER(:name)"
        );
        $statement->execute([
            ":name" => $name
        ]);
        $row = $statement->fetch();
        if ($row === false) {
            return null;
        }
        $user = new user(
            $row['name'],
            new \DateTime($row['date_birthday']),
            $row['password'],
            $row['role']
        );
        $user->set_id((int)$row['id']);
        return $user;
    }

    public function findUserById(int $id): ?user
    {
        if ($this->findUsersByIdStatement === null) {
            $this->findUsersByIdStatement = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        }

        $this->findUsersByIdStatement->execute([
            ":id" => $id
        ]);

        $row = $this->findUsersByIdStatement->fetch(\PDO::FETCH_ASSOC);

        if ($row === false) {
            return null;
        }

        $user = new user(
            $row['name'],
            new \DateTime($row['date_birthday']),
            $row['password'],
            $row['role']
        );
        $user->set_id((int)$row['id']);

        return $user;
    }
}

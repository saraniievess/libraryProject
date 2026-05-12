<?php

namespace resena\database;

use resena\model\user;
use database\repository;

class user_repository extends repository {
    private \PDO $pdo;
    private ?\PDOStatement $findUsersBynameStatement = null;
    private string $table = user::table;


    public function __construct(\PDO $_pdo) {
        parent::__construct($_pdo);
        $this->pdo = $_pdo;
    }


    /**
     * @return user[]
     */
    public function getAllUsers() : array {
        $statement = $this->pdo->query("SELECT * FROM {$this->table}");
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
                new \DateTime($row['date_birthday'])
            );
            $user->set_id((int)$row['id']);
            $users[] = $user;
        }

        return $users;
    }
}
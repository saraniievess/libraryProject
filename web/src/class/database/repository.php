<?php

namespace database;

class repository
{
    private \PDO $pdo;

    /**
     * @var array<string, ?\PDOStatement>
     */
    private array $insertStatements = [];

    /**
     * @var array<string, ?\PDOStatement>
     */
    private array $updateStatements = [];

    /**
     * @var array<string, ?\PDOStatement>
     */
    private array $deleteStatements = [];


    public function __construct(\PDO $_pdo)
    {
        $this->pdo = $_pdo;
    }


    public function insert(model $model): void
    {
        $tablename = $model->get_tablename();

        if (!isset($this->insertStatements[$tablename])) {
            $field_list = implode(",", $model->get_field_list());
            $field_values_list = array_map(
                function (string $_val) {
                    return ":{$_val}";
                },
                $model->get_field_list()
            );
            $field_values = implode(",", $field_values_list);
            $statement = $this->pdo->prepare("INSERT INTO {$tablename} ({$field_list}) VALUES ({$field_values})");
            if ($statement === false) {
                throw new \Exception("database prepare failed");
            }
            $this->insertStatements[$tablename] = $statement;
        }
        $statement_args = $model->get_statement_args();
        $this->insertStatements[$tablename]->execute($statement_args);
        $id = (int)$this->pdo->lastInsertId();
        $model->set_id($id);
    }

    public function update(model $model): void
    {
        $tablename = $model->get_tablename();

        if (!isset($this->updateStatements[$tablename])) {
            $field_list = array_map(
                function (string $_val) {
                    return "{$_val} = :{$_val}";
                },
                $model->get_field_list()
            );
            $fields = implode(",", $field_list);
            $statement = $this->pdo->prepare("UPDATE {$tablename} SET {$fields} WHERE id = :id");
            if ($statement === false) {
                throw new \Exception("database prepare failed");
            }
            $this->updateStatements[$tablename] = $statement;
        }
        $statement_args = $model->get_statement_args();
        $statement_args[":id"] = $model->get_id();
        $this->updateStatements[$tablename]->execute($statement_args);
    }

    public function delete(model $model): void
    {
        $tablename = $model->get_tablename();

        if (!isset($this->deleteStatements[$tablename])) {
            $statement = $this->pdo->prepare(
                "DELETE FROM {$tablename} WHERE id = :id"
            );
            if ($statement === false) {
                throw new \Exception("database prepare failed");
            }
            $this->deleteStatements[$tablename] = $statement;
        }
        $this->deleteStatements[$tablename]->execute([":id" => $model->get_id()]);
    }
}

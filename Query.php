<?php
class Query {
    private $sqltable;
    private $fields = '*';
    private $where = null;
    private $args = [];
    private $sql = '';

    public static function table($table) {
        $query = new Query;
        $query->sqltable = $table;

        return $query;
    }

    public function where($array = array(array())) {
        $this->where = "WHERE ";
        $this->args = [];
        foreach ($array as $elements) {
            $this->where .= "$elements[0] $elements[1] ? AND ";
            $this->args[] = $elements[2];
        }
        $this->where = rtrim($this->where, 'AND ');

        return $this;
    }

    public function get() {
        $this->sql .= "SELECT $this->fields FROM $this->sqltable $this->where";

        $pdo = ConnectionFactory::getConnection();
        $req = $pdo->prepare($this->sql);
        $req->execute($this->args);

        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function select($fields = array()) {
        if ($fields !== []) {
            $this->fields = implode(',', $fields);
        }

        return $this;
    }

    public function delete() {
        $this->sql .= "DELETE FROM $this->sqltable $this->where";

        $pdo = ConnectionFactory::getConnection();
        $req = $pdo->prepare($this->sql);
        $req->execute($this->args);

        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function insert($array = array()) {
        $columns = '';
        $values = '';

        foreach ($array as $key => $value) {
            $columns .= "$key,";
            $values .= "?,";
            $this->args[] = $value;
        }
        $columns = rtrim($columns, ',');
        $values = rtrim($values, ',');

        $this->sql .= "INSERT INTO $this->sqltable ($columns) VALUES ($values)";

        $pdo = ConnectionFactory::getConnection();
        $req = $pdo->prepare($this->sql);
        $req->execute($this->args);

        return $pdo->lastInsertId($this->sqltable);
    }
}
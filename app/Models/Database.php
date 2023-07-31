<?php

class Database {
    private string $host = 'localhost';
    private string $databaseName = 'mvc_2023';
    private string $charset = 'utf8mb4';
    private string $username = 'root';
    private string $password = ''; //macos/mamp -> 'root'

    private PDO $pdo;
    private PDOStatement $statement;

    public function __construct()
    {
        try {
            $this->pdo = new PDO(
                "mysql:host={$this->host};dbname={$this->databaseName};charset={$this->charset}",
                $this->username,
                $this->password,
                [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ]
            );
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    // Executes a prepared statement (prevents SQL Injection attacks)
    public function query(string $sql, array $values = [])
    {
        $this->statement = $this->pdo->prepare($sql);
        $this->statement->execute($values);

        return $this;
    }

    public function rowCount(): int
    {
        return $this->statement->rowCount();
    }

    public function results(): array
    {
        return $this->statement->fetchAll(PDO::FETCH_ASSOC);
    }
}

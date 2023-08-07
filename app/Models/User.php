<?php

namespace App\Models;

use App\Helpers\Str;
use Exception;

class User {
    private string $id;
    private string $username;
    private string $email;
    private string $password;
    private string $joinedAt;

    public function __construct(private Database $db)
    {}

    public function find(int|string $identifier = null): bool
    {
        $identifier = $identifier ?? $_SESSION['userId'];
        $column = is_int($identifier) ? 'id' : 'username';
        $sql = "SELECT * FROM `users` WHERE `{$column}` = :identifier";
        $userQuery = $this->db->query($sql, [ 'identifier' => $identifier ]);

        if ($userQuery->rowCount() === 0) {
            return false;
        }

        $userData = $userQuery->results()[0];
        $this->setColumnsAsProperties($userData);

        return true;
    }

    private function setColumnsAsProperties(array $data)
    {
        foreach ($data as $column => $value) {
            $column = Str::toCamelCase($column);
            $this->{$column} = $value;
        }
    }

    public function register(string $username, string $email, string $password): void
    {
        // Prüfen, ob user schon existiert
        $sql = "SELECT 1 FROM `users` WHERE `username` = :username OR `email` = :email";
        // Prepared Statement als Schutz gegen SQL Injection
        $statement = $this->db->query($sql, [ 'username' => $username, 'email' => $email ]);

        if ($statement->rowCount() > 0) {
            throw new Exception("The account you tried to create already exists.");
        }

        // User registrieren
        $sql = "
            INSERT INTO `users`
            (`username`, `email`, `password`, `joined_at`)
            VALUES (:username, :email, :password, :joined_at)
        ";

        $passwordHash = password_hash($password, PASSWORD_DEFAULT, [
            'cost' => 11
        ]);

        $statement = $this->db->query($sql, [
            'username' => $username,
            'email' => $email,
            'password' => $passwordHash,
            'joined_at' => time()
        ]);
    }

    public function login(string $username, string $password)
    {
        // Versuchen, den User zu finden
        if (!$this->find($username)) {
            throw new Exception('The username could not be found.');
        }

        // Passwort-Hash aus den Userdaten
        $passwordHash = $this->password;

        // Passwort aus dem Formular mit Hash aus der DB abgleichen
        if (!password_verify($password, $passwordHash)) {
            throw new Exception("Your password did not match.");
        }

        // Die Session für den User erstellen = einloggen
        $_SESSION['userId'] = (int) $this->id;
    }

    public function isLoggedIn(): bool
    {
        return isset($_SESSION['userId']);
    }

    public function logout(): void
    {
        unset($_SESSION['userId']);
    }

    public function getId(): int
    {
        return (int) $this->id;
    }

    public function getPosts(): array
    {
        $sql = "SELECT * FROM `posts` WHERE `user_id` = :userId";
        $postsQuery = $this->db->query($sql, [ 'userId' => $this->getId() ]);

        $posts = [];

        foreach ($postsQuery->results() as $result) {
            $posts[] = new Post($this->db, $result);
        }

        return $posts;
    }

    public function owns(Post $model): bool
    {
        return $this->getId() === $model->getUserId();
    }
}

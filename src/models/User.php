<?php

class User {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function createUser($username, $password, $role) {
        $stmt = $this->db->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
        return $stmt->execute(['username' => $username, 'password' => $password, 'role' => $role]);
    }

    public function findByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

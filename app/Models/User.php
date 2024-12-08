<?php

namespace App\Models;

use App\DBConnection;

class User
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db->getPDO();
    }

    public function all()
    {
        $stmt = $this->db->query("SELECT * FROM users");
        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function findByName($username)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch();
    }

    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)");
        $stmt->execute(['username' => $data['name'], 'email' => $data['email'], 'password' => password_hash($data['password'], PASSWORD_BCRYPT), 'role' => $data['role']]);
    }

    public function getUsers($limit, $offset, $sortField = 'name', $sortOrder = 'ASC')
    {
        $query = $this->db->prepare(
            "SELECT * FROM users ORDER BY {$sortField} {$sortOrder} LIMIT :limit OFFSET :offset"
        );
        $query->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $query->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function countUsers()
    {
        $query = $this->db->query("SELECT COUNT(*) as total FROM users");
        return $query->fetch(\PDO::FETCH_ASSOC)['total'];
    }

    public function update($data)
    {
        $stmt = $this->db->prepare("UPDATE users SET username = :username, email = :email, role = :role WHERE id = :id");
        $stmt->execute(['id' => $data['id'], 'username' => $data['name'], 'email' => $data['email'], 'role' => $data['role']]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

}

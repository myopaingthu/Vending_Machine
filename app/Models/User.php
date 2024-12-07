<?php

namespace App\Models;

use App\DBConnection;

class User
{
    public static function all()
    {
        $db = DBConnection::getInstance()->getPDO();
        $stmt = $db->query("SELECT * FROM users");
        return $stmt->fetchAll();
    }

    public static function find($id)
    {
        $db = DBConnection::getInstance()->getPDO();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public static function findByEmail($email)
    {
        $db = DBConnection::getInstance()->getPDO();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    public static function create($data)
    {
        $db = DBConnection::getInstance()->getPDO();
        $stmt = $db->prepare("INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)");
        $stmt->execute(['username' => $data['name'], 'email' => $data['email'], 'password' => password_hash($data['password'], PASSWORD_BCRYPT), 'role' => $data['role']]);
    }

    public static function getUsers($limit, $offset, $sortField = 'name', $sortOrder = 'ASC')
    {
        $db = DBConnection::getInstance()->getPDO();
        $query = $db->prepare(
            "SELECT * FROM users ORDER BY {$sortField} {$sortOrder} LIMIT :limit OFFSET :offset"
        );
        $query->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $query->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function countUsers()
    {
        $db = DBConnection::getInstance()->getPDO();
        $query = $db->query("SELECT COUNT(*) as total FROM users");
        return $query->fetch(\PDO::FETCH_ASSOC)['total'];
    }

    public static function update($data)
    {
        $db = DBConnection::getInstance()->getPDO();
        $stmt = $db->prepare("UPDATE users SET username = :username, email = :email, role = :role WHERE id = :id");
        $stmt->execute(['id' => $data['id'], 'username' => $data['name'], 'email' => $data['email'], 'role' => $data['role']]);
    }

    public static function delete($id)
    {
        $db = DBConnection::getInstance()->getPDO();
        $stmt = $db->prepare("DELETE FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

}

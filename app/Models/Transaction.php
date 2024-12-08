<?php

namespace App\Models;

use App\DBConnection;

class Transaction
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db->getPDO();
    }

    public function all()
    {
        $stmt = $this->db->query("SELECT * FROM transactions");
        return $stmt->fetchAll();
    }

    public function getAllByUser($user_id)
    {
        $query = $this->db->prepare(
            "SELECT t.*, u.username as user_name, p.name as product_name FROM transactions as t
            inner join users as u on u.id = t.user_id
            inner join products as p on p.id = t.product_id 
            where user_id = :user_id"
        );
        $query->bindValue(':user_id', $user_id, \PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getTransactions($limit, $offset, $sortField = 'name', $sortOrder = 'ASC')
    {
        $query = $this->db->prepare(
            "SELECT t.*, u.username as user_name, p.name as product_name FROM transactions as t
            inner join users as u on u.id = t.user_id
            inner join products as p on p.id = t.product_id ORDER BY {$sortField} {$sortOrder} LIMIT :limit OFFSET :offset"
        );
        $query->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $query->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getTransactionsByUser($limit, $offset, $user_id, $sortField = 'name', $sortOrder = 'ASC')
    {
        $query = $this->db->prepare(
            "SELECT t.*, u.username as user_name, p.name as product_name FROM transactions as t
            inner join users as u on u.id = t.user_id
            inner join products as p on p.id = t.product_id 
            where user_id = :user_id ORDER BY {$sortField} {$sortOrder} LIMIT :limit OFFSET :offset"
        );
        $query->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $query->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $query->bindValue(':user_id', $user_id, \PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function countTransactions()
    {
        $query = $this->db->query("SELECT COUNT(*) as total FROM transactions");
        return $query->fetch(\PDO::FETCH_ASSOC)['total'];
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM transactions WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO transactions (user_id, product_id, quantity, total_price) VALUES (:user_id, :product_id, :quantity, :total_price)");
        $stmt->execute(['user_id' => $data['user_id'], 'product_id' => $data['product_id'], 'quantity' => $data['quantity'], 'total_price' => $data['total_price']]);
    }

    public function update($id, $name, $price, $quantity)
    {
        $stmt = $this->db->prepare("UPDATE transactions SET name = :name, price = :price, quantity_available = :quantity WHERE id = :id");
        $stmt->execute(['id' => $id, 'name' => $name, 'price' => $price, 'quantity' => $quantity]);
    }
}

<?php

namespace App\Models;

use App\DBConnection;

class Product
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db->getPDO();
    }

    public function all()
    {
        $stmt = $this->db->query("SELECT * FROM products");
        return $stmt->fetchAll();
    }

    public function getProducts($limit, $offset, $sortField = 'name', $sortOrder = 'ASC')
    {
        $query = $this->db->prepare(
            "SELECT * FROM products ORDER BY {$sortField} {$sortOrder} LIMIT :limit OFFSET :offset"
        );
        $query->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $query->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function countProducts()
    {
        $query = $this->db->query("SELECT COUNT(*) as total FROM products");
        return $query->fetch(\PDO::FETCH_ASSOC)['total'];
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function create($name, $price, $quantity)
    {
        $stmt = $this->db->prepare("INSERT INTO products (name, price, quantity_available) VALUES (:name, :price, :quantity)");
        $stmt->execute(['name' => $name, 'price' => $price, 'quantity' => $quantity]);
    }

    public function update($id, $name, $price, $quantity)
    {
        $stmt = $this->db->prepare("UPDATE products SET name = :name, price = :price, quantity_available = :quantity WHERE id = :id");
        $stmt->execute(['id' => $id, 'name' => $name, 'price' => $price, 'quantity' => $quantity]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    public function purchase($id, $quantity)
    {
        $stmt = $this->db->prepare("UPDATE products SET quantity_available = quantity_available - :quantity WHERE id = :id");
        $stmt->execute(['id' => $id, 'quantity' => $quantity]);
    }

    public function getForUpdate($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = :id FOR UPDATE");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
}

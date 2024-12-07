<?php

namespace App\Models;

use App\DBConnection;

class Product
{
    public static function all()
    {
        $db = DBConnection::getInstance()->getPDO();
        $stmt = $db->query("SELECT * FROM products");
        return $stmt->fetchAll();
    }

    public static function getProducts($limit, $offset, $sortField = 'name', $sortOrder = 'ASC')
    {
        $db = DBConnection::getInstance()->getPDO();
        $query = $db->prepare(
            "SELECT * FROM products ORDER BY {$sortField} {$sortOrder} LIMIT :limit OFFSET :offset"
        );
        $query->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $query->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function countProducts()
    {
        $db = DBConnection::getInstance()->getPDO();
        $query = $db->query("SELECT COUNT(*) as total FROM products");
        return $query->fetch(\PDO::FETCH_ASSOC)['total'];
    }

    public static function find($id)
    {
        $db = DBConnection::getInstance()->getPDO();
        $stmt = $db->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public static function create($name, $price, $quantity)
    {
        $db = DBConnection::getInstance()->getPDO();
        $stmt = $db->prepare("INSERT INTO products (name, price, quantity_available) VALUES (:name, :price, :quantity)");
        $stmt->execute(['name' => $name, 'price' => $price, 'quantity' => $quantity]);
    }

    public static function update($id, $name, $price, $quantity)
    {
        $db = DBConnection::getInstance()->getPDO();
        $stmt = $db->prepare("UPDATE products SET name = :name, price = :price, quantity_available = :quantity WHERE id = :id");
        $stmt->execute(['id' => $id, 'name' => $name, 'price' => $price, 'quantity' => $quantity]);
    }

    public static function delete($id)
    {
        $db = DBConnection::getInstance()->getPDO();
        $stmt = $db->prepare("DELETE FROM products WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    public static function purchase($id, $quantity)
    {
        $db = DBConnection::getInstance()->getPDO();
        $stmt = $db->prepare("UPDATE products SET quantity_available = quantity_available - :quantity WHERE id = :id");
        $stmt->execute(['id' => $id, 'quantity' => $quantity]);
    }
}

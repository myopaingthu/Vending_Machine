<?php

namespace App\Controllers;

use Exception;
use App\DBConnection;
use App\Helpers\View;
use App\Models\Product;
use App\Models\Transaction;

class ProductsController extends BaseController
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function index()
    {
        $this->checkRole('admin');

        $limit = 5;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;
        $sortField = $_GET['sort'] ?? 'name';
        $sortOrder = $_GET['order'] ?? 'ASC';

        $products = Product::getProducts($limit, $offset, $sortField, $sortOrder);
        $totalProducts = Product::countProducts();
        $totalPages = ceil($totalProducts / $limit);

        View::render('products/index', compact('products', 'totalProducts', 'totalPages', 'page', 'sortField', 'sortOrder'));
    }

    public function create()
    {
        $this->checkRole('admin');
        View::render('products/create');
    }

    public function store()
    {
        $this->checkRole('admin');
        $errors = [];

        $name = filter_input(INPUT_POST, 'name');
        $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_NUMBER_INT);

        if (empty($name)) {
            $errors[] = "Product name is required.";
        }

        if (empty($price)) {
            $errors[] = "Price is required.";
        } else if (!is_numeric($price)) {
            $errors[] = "Price must be a number.";
        } else if ($price <= 0) {
            $errors[] = "Price must be at least $0.01.";
        }

        if (!isset($quantity)) {
            $errors[] = "Quantity is required.";
        } else if (!is_numeric($quantity)) {
            $errors[] = "Quantity must be a number.";
        } else if ($quantity <= 0) {
            $errors[] = "Quantity must be 0 or more.";
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: /admin/products/create');
            exit;
        }

        Product::create($name, $price, $quantity);
        header('Location: /admin/products');
        exit;
    }

    public function edit($id)
    {
        $this->checkRole('admin');
        $product = Product::find($id);
        View::render('products/edit', compact('product'));
    }

    public function update($id)
    {
        $this->checkRole('admin');
        $errors = [];

        $name = filter_input(INPUT_POST, 'name');
        $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_NUMBER_INT);

        if (empty($name)) {
            $errors[] = "Product name is required.";
        }

        if (empty($price)) {
            $errors[] = "Price is required.";
        } else if (!is_numeric($price)) {
            $errors[] = "Price must be a number.";
        } else if ($price <= 0) {
            $errors[] = "Price must be at least $0.01.";
        }

        if (!isset($quantity)) {
            $errors[] = "Quantity is required.";
        } else if (!is_numeric($quantity)) {
            $errors[] = "Quantity must be a number.";
        } else if ($quantity <= 0) {
            $errors[] = "Quantity must be 0 or more.";
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: /admin/products/' . $id . '/edit');
            exit;
        }

        Product::update($id, $name, $price, $quantity);
        header("Location: /admin/products");
    }

    public function destroy($id)
    {
        $this->checkRole('admin');
        Product::delete($id);
        header("Location: /admin/products");
    }

    public function purchase($id)
    {
        $this->checkRole('user');
        $quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_NUMBER_INT);

        if (!isset($quantity)) {
            $errors[] = "Quantity is required.";
        } else if (!is_numeric($quantity)) {
            $errors[] = "Quantity must be a number.";
        } else if ($quantity <= 0) {
            $errors[] = "Quantity must be 0 or more.";
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: /products');
            exit;
        }


        $db = $this->db->getInstance()->getPDO();
        $user_id = $_SESSION['user_id'];
        try {
            $db->beginTransaction();

            $stmt = $db->prepare("SELECT * FROM products WHERE id = :product_id FOR UPDATE");
            $stmt->execute(['product_id' => $id]);
            $product = $stmt->fetch();

            if (!$product) {
                throw new Exception("Product not found.");
            }

            if ($product['quantity_available'] < $quantity) {
                throw new Exception("Insufficient stock available.");
            }

            $toal_price = $quantity * $product['price'];
            Product::purchase($id, $quantity);

            Transaction::create([
                'user_id' => $user_id,
                'product_id' => $id,
                'quantity' => $quantity,
                'total_price' => $toal_price
            ]);
            $db->commit();

            $_SESSION['success'] = 'Purchase Successfully.';
            header('Location: /products');
        } catch (Exception $e) {
            $db->rollBack();
            $errors[] = $e->getMessage();
            $_SESSION['errors'] = $errors;
            header('Location: /products');
        }
        header("Location: /products");
    }

    public function userIndex()
    {
        $this->checkRole('user');

        $limit = 9;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;
        $sortField = $_GET['sort'] ?? 'name';
        $sortOrder = $_GET['order'] ?? 'ASC';

        $products = Product::getProducts($limit, $offset, $sortField, $sortOrder);
        $totalProducts = Product::countProducts();
        $totalPages = ceil($totalProducts / $limit);

        View::render('user_products/index', compact('products', 'totalProducts', 'totalPages', 'page', 'sortField', 'sortOrder'));
    }

    public function apiIndex()
    {
        $user = $this->verifyToken();
        $products = Product::all();
        echo json_encode([
            'status' => true,
            'data' => $products,
            'code' => 200
        ]);
    }

    public function apiPurchase($id)
    {
        $user = $this->verifyToken();
        $quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_NUMBER_INT);

        if (!isset($quantity)) {
            $errors[] = "Quantity is required.";
        } else if (!is_numeric($quantity)) {
            $errors[] = "Quantity must be a number.";
        } else if ($quantity <= 0) {
            $errors[] = "Quantity must be 0 or more.";
        }

        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode([
                'status' => false,
                'errors' => $errors,
                'code' => 400
            ]);
            exit;
        }


        $db = $this->db->getInstance()->getPDO();
        $user_id = $user->sub;
        try {
            $db->beginTransaction();

            $stmt = $db->prepare("SELECT * FROM products WHERE id = :product_id FOR UPDATE");
            $stmt->execute(['product_id' => $id]);
            $product = $stmt->fetch();

            if (!$product) {
                throw new Exception("Product not found.");
            }

            if ($product['quantity_available'] < $quantity) {
                throw new Exception("Insufficient stock available.");
            }

            $toal_price = $quantity * $product['price'];
            Product::purchase($id, $quantity);

            Transaction::create([
                'user_id' => $user_id,
                'product_id' => $id,
                'quantity' => $quantity,
                'total_price' => $toal_price
            ]);
            $db->commit();

            echo json_encode([
                'status' => true,
                'message' => 'Purchase Successfully',
                'data' => null,
                'code' => 200
            ]);
        } catch (Exception $e) {
            $db->rollBack();
            $errors[] = $e->getMessage();
            http_response_code(400);
            echo json_encode([
                'status' => false,
                'errors' => $errors,
                'code' => 400
            ]);
        }
    }
}

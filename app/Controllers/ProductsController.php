<?php

namespace App\Controllers;

use App\Helpers\View;
use App\Models\Product;

class ProductsController extends BaseController
{
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
        $quantity = $_POST['quantity'];
        Product::purchase($id, $quantity);
        header("Location: /admin/products");
    }
}

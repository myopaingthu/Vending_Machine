<?php

namespace App\Controllers;

use App\Helpers\View;
use App\Models\Transaction;

class TransactionController extends BaseController
{
    public function index()
    {
        $this->checkRole('admin');

        $limit = 5;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;
        $sortField = $_GET['sort'] ?? 'product_name';
        $sortOrder = $_GET['order'] ?? 'ASC';

        $transactions = Transaction::getTransactions($limit, $offset, $sortField, $sortOrder);
        $totalTransactions = Transaction::countTransactions();
        $totalPages = ceil($totalTransactions / $limit);

        View::render('transactions/index', compact('transactions', 'totalTransactions', 'totalPages', 'page', 'sortField', 'sortOrder'));
    }

    public function userIndex()
    {
        $this->checkRole('user');

        $user_id = $_SESSION['user_id'];
        $limit = 5;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;
        $sortField = $_GET['sort'] ?? 'product_name';
        $sortOrder = $_GET['order'] ?? 'ASC';

        $transactions = Transaction::getTransactionsByUser($limit, $offset, $user_id, $sortField, $sortOrder);
        $totalTransactions = Transaction::countTransactions();
        $totalPages = ceil($totalTransactions / $limit);

        View::render('transactions/index', compact('transactions', 'totalTransactions', 'totalPages', 'page', 'sortField', 'sortOrder'));
    }
}

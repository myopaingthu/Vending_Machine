<?php

namespace App\Controllers;

use App\Helpers\View;
use App\Models\Transaction;

class TransactionController extends BaseController
{
    private $db;
    private $transaction;

    public function __construct($db)
    {
        $this->db = $db;
        $this->transaction = new Transaction($db);
    }

    public function index()
    {
        $this->checkRole('admin');

        $limit = 5;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;
        $sortField = $_GET['sort'] ?? 'product_name';
        $sortOrder = $_GET['order'] ?? 'ASC';

        $transactions = $this->transaction->getTransactions($limit, $offset, $sortField, $sortOrder);
        $totalTransactions = $this->transaction->countTransactions();
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

        $transactions = $this->transaction->getTransactionsByUser($limit, $offset, $user_id, $sortField, $sortOrder);
        $totalTransactions = $this->transaction->countTransactions();
        $totalPages = ceil($totalTransactions / $limit);

        View::render('transactions/index', compact('transactions', 'totalTransactions', 'totalPages', 'page', 'sortField', 'sortOrder'));
    }

    public function apiIndex()
    {
        $user = $this->verifyToken();
        $user_id = $user->sub;

        $transactions = $this->transaction->getAllByUser($user_id);
        echo json_encode([
            'status' => true,
            'data' => $transactions,
            'code' => 200
        ]);
    }
}

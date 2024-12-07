<?php
namespace App\Controllers;

class BaseController
{
    protected function checkRole($requiredRole)
    {
        if (!isset($_SESSION['role'])) {
            header('Location: /login');
            exit();
        }

        if ($_SESSION['role'] !== $requiredRole) {
            echo "You do not have permission to access this page.";
            exit();
        }
    }
}

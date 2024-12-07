<?php

namespace App\Controllers;

use App\DBConnection;
use App\Helpers\View; // Include the View helper
use App\Models\User;

class AuthController
{
    private $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function showLoginForm()
    {
        // Display the login view
        View::render('auth/login');
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize inputs
            $email = trim($_POST['email']);
            $password = $_POST['password'];

            // Fetch user from DB
            $user = $this->user->findByEmail($email);
            

            // Validate credentials
            if ($user && password_verify($password, $user['password'])) {
                // Start the session and store user details
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role']; // Store role

                // Redirect based on role
                if ($_SESSION['role'] === 'admin') {
                    header('Location: /admin/products');
                } else {
                    header('Location: /admin/products');
                }
                exit;
            } else {
                $_SESSION['errors'] = ["Invalid username or password."];
                header('Location: /login');
                exit;
            }
        }
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: /login');
        exit;
    }
}

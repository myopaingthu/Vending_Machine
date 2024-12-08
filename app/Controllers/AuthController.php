<?php

namespace App\Controllers;

use App\Models\User;
use Firebase\JWT\JWT;
use App\Helpers\View; // Include the View helper

class AuthController
{
    private $user;
    private $key = "test_key";

    public function __construct()
    {
        $this->user = new User();
    }

    public function showLoginForm()
    {
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
                    header('Location: /products');
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

    public function apiLogin()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = $this->user->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid credentials']);
            return;
        }

        $payload = [
            'iss' => "vending_machine_api",
            'sub' => $user['id'],
            'role' => $user['role'],
            'iat' => time(),
            'exp' => time() + 3600
        ];

        $jwt = JWT::encode($payload, $this->key, 'HS256');
        echo json_encode([
            'status' => true,
            'token' => $jwt,
            'code' => 200
        ]);
    }

}

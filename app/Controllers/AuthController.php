<?php

namespace App\Controllers;

use App\DBConnection;
use App\Models\User;
use Firebase\JWT\JWT;
use App\Helpers\View;

class AuthController
{
    private $key = "test_key";
    private $db;
    private $user;

    /**
     * Constructor to initialize the database and user model.
     *
     * @param object $db Database connection instance.
     */
    public function __construct($db)
    {
        $this->db = $db;
        $this->user = new User(new DBConnection());
    }

    /**
     * Redirect the user based on their login status and role.
     *
     * @return void
     */
    public function redirect()
    {
        if (isset($_SESSION['user_id'])) {
            if ($_SESSION['role'] === 'admin') header('Location: /admin/products');
            else header('Location: /products');
        } else {
            header('Location: /login');
        }
    }

    /**
     * Display the login form view.
     *
     * @return void
     */
    public function showLoginForm()
    {
        View::render('auth/login');
    }

    /**
     * Handle the user login process, validating credentials and setting session variables.
     *
     * @return void
     */
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $password = $_POST['password'];

            $user = $this->user->findByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

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

    /**
     * Log out the user by clearing session data.
     *
     * @return void
     */
    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: /login');
        exit;
    }

    /**
     * Handle API-based login and return a JWT token upon successful authentication.
     *
     * @return void
     */
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

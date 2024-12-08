<?php

namespace App\Controllers;

use Exception;
use App\Helpers\View;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class BaseController
{
    protected function checkRole($requiredRole)
    {
        if (!isset($_SESSION['role'])) {
            header('Location: /login');
            exit();
        }

        if ($_SESSION['role'] !== $requiredRole) {
            $response_code = 401;
            $message = 'You do not have permission to access this page.';
            View::render('utilities/error', compact('response_code', 'message'));
            exit;
        }
    }

    protected function verifyToken()
    {
        $headers = getallheaders();
        $key = "test_key";

        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode([
                'status' => false,
                'error' => 'Authorization header not found',
                'code' => 401,
            ]);
            exit;
        }

        $token = str_replace('Bearer ', '', $headers['Authorization']);

        try {
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            return $decoded; 
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode([
                'status' => false,
                'error' => 'Invalid token',
                'code' => 401,
            ]);
            exit;
        }
    }
}

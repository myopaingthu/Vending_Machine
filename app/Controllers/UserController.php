<?php

namespace App\Controllers;

use App\Helpers\View;
use App\Models\User;

class UserController extends BaseController
{
    public function index()
    {
        $this->checkRole('admin');

        $limit = 1;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;
        $sortField = $_GET['sort'] ?? 'username';
        $sortOrder = $_GET['order'] ?? 'ASC';

        $users = User::getUsers($limit, $offset, $sortField, $sortOrder);
        $totalUsers = User::countUsers();
        $totalPages = ceil($totalUsers / $limit);

        View::render('users/index', compact('users', 'totalUsers', 'totalPages', 'page', 'sortField', 'sortOrder'));
    }

    public function create()
    {
        $this->checkRole('admin');
        View::render('users/create');
    }

    public function store()
    {
        $this->checkRole('admin');
        $errors = [];

        $name = filter_input(INPUT_POST, 'name');
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = filter_input(INPUT_POST, 'password');
        $password_confirm = filter_input(INPUT_POST, 'password_confirm');
        $role = filter_input(INPUT_POST, 'role');

        if (empty($name)) {
            $errors[] = "User name is required.";
        }

        if (empty($email)) {
            $errors[] = "Email is required.";
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }

        if (!isset($password)) {
            $errors[] = "Password is required.";
        }

        if (!isset($password_confirm)) {
            $errors[] = "Password Confirm is required.";
        } else if ($password != $password_confirm) {
            $errors[] = "Password must be confirmed.";
        }

        if (!isset($role)) {
            $errors[] = "Role is required.";
        } else if (!in_array($role, ['admin', 'user'])) {
            $errors[] = "Invalid role.";
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: /admin/users/create');
            exit;
        }

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => $role
        ]);
        header('Location: /admin/users');
        exit;
    }

    public function edit($id)
    {
        $this->checkRole('admin');
        $user = User::find($id);
        View::render('users/edit', compact('user'));
    }

    public function update($id)
    {
        $this->checkRole('admin');
        $errors = [];

        $name = filter_input(INPUT_POST, 'name');
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $role = filter_input(INPUT_POST, 'role');

        if (empty($name)) {
            $errors[] = "User name is required.";
        }

        if (empty($email)) {
            $errors[] = "Email is required.";
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }

        if (isset($password) &&($password != $password_confirm)) {
            $errors[] = "Password must be confirmed.";
        }

        if (!isset($role)) {
            $errors[] = "Role is required.";
        } else if (!in_array($role, ['admin', 'user'])) {
            $errors[] = "Invalid role.";
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: /admin/users/' . $id . '/edit');
            exit;
        }

        User::update([
            'id' => $id,
            'name' => $name,
            'role' => $role,
            'email' => $email
        ]);
        header("Location: /admin/users");
    }

    public function destroy($id)
    {
        if ($_SESSION['user_id'] == $user['id']) {
            header("Location: /admin/users");
            exit;
        }
        $this->checkRole('admin');
        User::delete($id);
        header("Location: /admin/users");
    }
}

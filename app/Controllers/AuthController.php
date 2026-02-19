<?php

/**
 * Auth Controller
 * Handles authentication: login, register, logout
 */

class AuthController extends Controller {

    public function showLogin() {
        $this->requireGuest();
        $this->view('auth/login');
    }

    public function login() {
        $this->requireGuest();
        CSRF::verify();

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validation
        if (empty($email) || empty($password)) {
            Flash::set('error', 'Email and password are required');
            setOld($_POST);
            $this->redirect('/login');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Flash::set('error', 'Invalid email format');
            setOld($_POST);
            $this->redirect('/login');
        }

        // Attempt login
        if (Auth::attempt($email, $password)) {
            clearOld();
            Flash::set('success', 'Welcome back!');
            $this->redirect('/dashboard');
        } else {
            Flash::set('error', 'Invalid credentials');
            setOld(['email' => $email]);
            $this->redirect('/login');
        }
    }

    public function showRegister() {
        $this->requireGuest();
        $this->view('auth/register');
    }

    public function register() {
        $this->requireGuest();
        CSRF::verify();

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        // Validation
        $errors = [];

        if (empty($name)) {
            $errors[] = 'Name is required';
        }

        if (empty($email)) {
            $errors[] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        } else {
            $userModel = new User();
            if ($userModel->emailExists($email)) {
                $errors[] = 'Email already registered';
            }
        }

        if (empty($password)) {
            $errors[] = 'Password is required';
        } elseif (strlen($password) < 6) {
            $errors[] = 'Password must be at least 6 characters';
        } elseif ($password !== $passwordConfirm) {
            $errors[] = 'Passwords do not match';
        }

        if (!empty($errors)) {
            Flash::set('error', implode('<br>', $errors));
            setOld($_POST);
            $this->redirect('/register');
        }

        // Create user
        $userModel = new User();
        $userId = $userModel->create($email, $password, $name);

        if ($userId) {
            Auth::login($userId);
            clearOld();
            Flash::set('success', 'Account created successfully!');
            $this->redirect('/dashboard');
        } else {
            Flash::set('error', 'Registration failed. Please try again.');
            setOld($_POST);
            $this->redirect('/register');
        }
    }

    public function logout() {
        Auth::logout();
        Flash::set('success', 'Logged out successfully');
        $this->redirect('/login');
    }
}

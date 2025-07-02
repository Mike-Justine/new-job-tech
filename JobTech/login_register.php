<?php
session_start();
require_once 'config.php';

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    
    if ($role === 'admin') {
        $secret_code = $_POST['secret_code'] ?? '';
        $correct_code = "MYSECRET123"; 

        if ($secret_code !== $correct_code) {
            $_SESSION['register_error'] = 'Invalid secret code for admin registration!';
            $_SESSION['active_form'] = 'register';
            header("Location: index.php");
            exit();
        }
    }

    
    $checkEmail = $conn->query("SELECT email FROM users WHERE email = '$email' ");
    if ($checkEmail->num_rows > 0) {
        $_SESSION['register_error'] = 'Email is already registered!';
        $_SESSION['active_form'] = 'register';
    } else {
        $conn->query("INSERT INTO users (name, email, phone_number, password, role) 
            VALUES('$name', '$email', '$phone_number', '$password', '$role')");
    }

    header("Location: index.php");
    exit();
}

if (isset($_POST['login']))  {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM `users` WHERE email = '$email'");
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($password === $user['password']) {
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];

            if ($user['role'] === 'admin') {
                $_SESSION['admin'] = $user['email'];
                header("Location: admin_page.php");
            } else {
                header("Location: user_page.php");
            }
            exit();
        }
    }
    $_SESSION['login_error'] = 'Incorrect email or password';
    $_SESSION['active_form'] = 'login';
    header("Location: index.php");
    exit();
}
?>

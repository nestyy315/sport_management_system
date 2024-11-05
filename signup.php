<?php
require_once 'user.class.php';
session_start();

$signupErr = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $role = $_POST['role']; // Ensure this is included in the form

    $user = new User();
    if ($user->fetch($username)) {
        $signupErr = 'Username already exists. Please choose another.';
    } else {
        if ($user->signup($username, $password, $role, $first_name, $last_name)) {
            // Fetch user data including the newly inserted datetime_sign_up
            $userData = $user->fetch($username);
            $_SESSION['user_id'] = $userData['user_id'];
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;
            $_SESSION['first_name'] = $first_name;
            $_SESSION['last_name'] = $last_name;
            $_SESSION['datetime_sign_up'] = $userData['datetime_sign_up'];
            $_SESSION['datetime_last_online'] = $userData['datetime_last_online'];
            header('Location: dashboard.php');
            exit();
        } else {
            $signupErr = 'Error during registration. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="account/account_styles.css">
</head>
<body>
    <form action="signup.php" method="post">
        <h1>Sign Up</h1>
        <label for="first_name">First Name</label>
        <input type="text" id="first_name" name="first_name" required>
        <label for="last_name">Last Name</label>
        <input type="text" id="last_name" name="last_name" required>
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
        <label for="role">Role</label>
        <select id="role" name="role">
            <option value="student">Student</option>
            <option value="teacher">Teacher</option>
            <option value="admin">Admin</option>
        </select>
        <p class="text-danger"><?= $signupErr ?></p>
        <button type="submit">Sign Up</button>
    </form>
    <a href="login.php">
    <button type="submit">Log in</button>
    </a>
</body>
</html>

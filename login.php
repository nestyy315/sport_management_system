<?php
require_once 'user.class.php';
session_start();

$loginErr = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = new User();
    if ($user->login($username, $password)) {
        // Update datetime_last_online
        $conn = (new Database())->connect();
        $query = $conn->prepare("UPDATE users SET datetime_last_online = NOW() WHERE username = :username");
        $query->bindParam(':username', $username);
        $query->execute();

        // Fetch user data and set session variables
        $userData = $user->fetch($username);
        $_SESSION['user_id'] = $userData['user_id'];
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $userData['role'];
        $_SESSION['first_name'] = $userData['first_name'];
        $_SESSION['last_name'] = $userData['last_name'];
        $_SESSION['datetime_sign_up'] = $userData['datetime_sign_up'];
        $_SESSION['datetime_last_online'] = $userData['datetime_last_online'];

        header('Location: dashboard.php');
        exit();
    } else {
        $loginErr = 'Invalid username/password.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="account/account_styles.css">
</head>
<body>
    <div class="main_container">
        <form action="login.php" method="post">
            <h1>Login</h1>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <p class="text-danger"><?= $loginErr ?></p>
            <button type="submit">Login</button>
        </form>
        <a href="signup.php">
            <button type="submit">Sign Up</button>
        </a>
    </div>
</body>
</html>

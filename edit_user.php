<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

require_once 'database.class.php';
$conn = (new Database())->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $role = $_POST['role'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];

    $query = $conn->prepare("UPDATE users SET username = :username, role = :role, first_name = :first_name, last_name = :last_name WHERE user_id = :user_id");
    $query->bindParam(':username', $username);
    $query->bindParam(':role', $role);
    $query->bindParam(':first_name', $first_name);
    $query->bindParam(':last_name', $last_name);
    $query->bindParam(':user_id', $user_id);
    $query->execute();

    header('Location: success_page.php?message=User updated successfully!');
        exit();
} else {
    if (isset($_GET['user_id'])) {
        $user_id = $_GET['user_id'];

        $query = $conn->prepare("SELECT * FROM users WHERE user_id = :user_id");
        $query->bindParam(':user_id', $user_id);
        $query->execute();
        $user = $query->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $username = $user['username'];
            $role = $user['role'];
            $first_name = $user['first_name'];
            $last_name = $user['last_name'];
        } else {
            echo "User not found.";
            exit();
        }
    } else {
        echo "Invalid request.";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php require_once 'includes/header.php'; ?>
    <h1>Edit User</h1>
    <form action="edit_user.php" method="post">
        <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id) ?>">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" value="<?= htmlspecialchars($username) ?>" required>
        <label for="role">Role</label>
        <input type="text" id="role" name="role" value="<?= htmlspecialchars($role) ?>" required>
        <label for="first_name">First Name</label>
        <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($first_name) ?>" required>
        <label for="last_name">Last Name</label>
        <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($last_name) ?>" required>
        <button type="submit">Save Changes</button>
        <button type="button" onclick="window.history.back()">Back</button>
    </form>
    <?php require_once 'includes/footer.php'; ?>
</body>
</html>

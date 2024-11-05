<?php
session_start();

if (!isset($_SESSION['role'])) {
    header('Location: login.php');
    exit();
}

switch ($_SESSION['role']) {
    case 'student':
        header('Location: student_dashboard.php');
        break;
    case 'teacher':
        header('Location: teacher_dashboard.php');
        break;
    case 'admin':
        header('Location: admin_dashboard.php');
        break;
    default:
        header('Location: login.php');
}
exit();

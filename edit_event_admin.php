<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

require_once 'database.class.php';
$conn = (new Database())->connect();

$event_name = '';
$event_date = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_event'])) {
        $event_id = $_POST['event_id'];
        $event_name = $_POST['event_name'];
        $event_date = $_POST['event_date'];

        $query = $conn->prepare("UPDATE events SET event_name = :event_name, event_date = :event_date WHERE event_id = :event_id");
        $query->bindParam(':event_name', $event_name);
        $query->bindParam(':event_date', $event_date);
        $query->bindParam(':event_id', $event_id);
        $query->execute();

        header('Location: success_page.php?message=Event updated successfully!');
        exit();
    } elseif (isset($_POST['add_event'])) {
        $event_name = $_POST['event_name'];
        $event_date = $_POST['event_date'];

        $query = $conn->prepare("INSERT INTO events (event_name, event_date) VALUES (:event_name, :event_date)");
        $query->bindParam(':event_name', $event_name);
        $query->bindParam(':event_date', $event_date);
        $query->execute();

        header('Location: success_page.php?message=Event added successfully!');
        exit();
    }
} else {
    if (isset($_GET['event_id'])) {
        $event_id = $_GET['event_id'];

        $query = $conn->prepare("SELECT * FROM events WHERE event_id = :event_id");
        $query->bindParam(':event_id', $event_id);
        $query->execute();
        $event = $query->fetch(PDO::FETCH_ASSOC);

        if ($event) {
            $event_name = $event['event_name'];
            $event_date = $event['event_date'];
        } else {
            echo "Event not found.";
            exit();
        }
    } elseif (isset($_GET['add_event'])) {
        $event_name = '';
        $event_date = '';
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
    <title>Edit/Add Event</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php require_once 'includes/header.php'; ?>
    <h1><?= isset($_GET['event_id']) ? 'Edit Event' : 'Add Event' ?></h1>
    <form action="edit_event_admin.php" method="post">
        <?php if (isset($_GET['event_id'])): ?>
            <input type="hidden" name="event_id" value="<?= htmlspecialchars($event_id) ?>">
        <?php endif; ?>
        <label for="event_name">Event Name</label>
        <input type="text" id="event_name" name="event_name" value="<?= htmlspecialchars($event_name) ?>" required>
        <label for="event_date">Event Date</label>
        <input type="date" id="event_date" name="event_date" value="<?= htmlspecialchars($event_date) ?>" required>
        <button type="submit" name="<?= isset($_GET['event_id']) ? 'update_event' : 'add_event' ?>">
            <?= isset($_GET['event_id']) ? 'Save Changes' : 'Add Event' ?>
        </button>
        <button type="button" onclick="window.history.back()">Back</button>
    </form>
    <?php require_once 'includes/footer.php'; ?>
</body>
</html>

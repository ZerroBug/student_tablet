<?php
session_start();
include_once('../includes/db_connection.php');

// if (!isset($_SESSION['user'])) {
//     header("Location: ../index.php");
//     exit;
// }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $message = trim($_POST['message']);
    $icon = trim($_POST['icon']);
    $type = $_POST['type'];

    if (!empty($title) && !empty($type)) {
        $stmt = $pdo->prepare("INSERT INTO notifications (title, message, icon, type) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $message, $icon, $type]);
        header("Location: notifications.php");
        exit;
    } else {
        $error = "Title and Type are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Add Notification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-5">
        <h3>Add Notification</h3>

        <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label>Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Message (optional)</label>
                <textarea name="message" class="form-control" rows="3"></textarea>
            </div>

            <div class="mb-3">
                <label>Icon (FontAwesome class)</label>
                <input type="text" name="icon" class="form-control" value="fa-bell">
            </div>

            <div class="mb-3">
                <label>Type</label>
                <select name="type" class="form-select" required>
                    <option value="info">Info</option>
                    <option value="success">Success</option>
                    <option value="warning">Warning</option>
                    <option value="danger">Danger</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Add Notification</button>
        </form>
    </div>
</body>

</html>
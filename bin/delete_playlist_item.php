<?php
session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] != 'Manager' && $_SESSION['role'] != 'AdminRemote' && $_SESSION['role'] != 'Admin')) {
    header("Location: ../login.html");
    exit();
}

include(__DIR__ . '/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $playlist_id = $_POST['playlist_id'];

    $stmt = $conn->prepare("DELETE FROM playlists WHERE id = ?");
    $stmt->bind_param("i", $playlist_id);
    $stmt->execute();
    $stmt->close();
}
?>

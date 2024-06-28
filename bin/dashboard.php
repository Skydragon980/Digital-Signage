<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.html");
    exit();
}

include(__DIR__ . '/db.php');

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
$station_id = $_SESSION['station_id'];

echo "<h1>Welcome, $role</h1>";

if ($role == 'Admin') {
    echo "<a href='manage_users.php'>Manage Users</a>";
    echo "<a href='manage_stations.php'>Manage Stations</a>";
}

if ($role == 'AdminRemote' || $role == 'Admin') {
    echo "<a href='station.php?station_id=$station_id'>Manage This Station</a>";
}

if ($role == 'Manager' || $role == 'AdminRemote' || $role == 'Admin') {
    echo "<a href='upload.php'>Upload Files</a>";
}
?>

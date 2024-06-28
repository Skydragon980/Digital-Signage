<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Admin') {
    header("Location: ../login.html");
    exit();
}

include(__DIR__ . '/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $station_id = $_POST['station_id'];

    $stmt = $conn->prepare("INSERT INTO users (username, password, role, station_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $username, $password, $role, $station_id);
    $stmt->execute();
    $stmt->close();
}

$result = $conn->query("SELECT * FROM users");
$users = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
</head>
<body>
    <h1>Manage Users</h1>
    <form method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <select name="role">
            <option value="Admin">Admin</option>
            <option value="AdminRemote">AdminRemote</option>
            <option value="Manager">Manager</option>
        </select>
        <input type="number" name="station_id" placeholder="Station ID">
        <input type="submit" value="Add User">
    </form>
    <h2>User List</h2>
    <ul>
        <?php foreach ($users as $user): ?>
            <li><?php echo $user['username']; ?> - <?php echo $user['role']; ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>

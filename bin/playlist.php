<?php
session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] != 'Manager' && $_SESSION['role'] != 'AdminRemote' && $_SESSION['role'] != 'Admin')) {
    header("Location: ../login.html");
    exit();
}

include(__DIR__ . '/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $file_id = $_POST['file_id'];
    $position = $_POST['position'];
    $playlist_id = $_POST['playlist_id'];

    $stmt = $conn->prepare("INSERT INTO playlists (file_id, position, active) VALUES (?, ?, 0)");
    $stmt->bind_param("ii", $file_id, $position);
    $stmt->execute();
    $stmt->close();
}

$result = $conn->query("SELECT * FROM files");
$files = $result->fetch_all(MYSQLI_ASSOC);
$playlists = $conn->query("SELECT * FROM playlists")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Playlist</title>
</head>
<body>
    <h1>Create Playlist</h1>
    <form method="post">
        <select name="file_id">
            <?php foreach ($files as $file): ?>
                <option value="<?php echo $file['id']; ?>"><?php echo $file['filename']; ?></option>
            <?php endforeach; ?>
        </select>
        <input type="number" name="position" placeholder="Position" required>
        <input type="submit" value="Add to Playlist">
    </form>
    <h2>Current Playlists</h2>
    <ul>
        <?php foreach ($playlists as $playlist): ?>
            <li><?php echo "File ID: " . $playlist['file_id'] . " - Position: " . $playlist['position']; ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>

<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Admin') {
    header("Location: ../login.html");
    exit();
}

include(__DIR__ . '/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $station_name = $_POST['station_name'];
    $playlist_id = $_POST['playlist_id'];
    $action = $_POST['action'];

    if ($action == 'add_station') {
        $stmt = $conn->prepare("INSERT INTO stations (station_name, playlist_id) VALUES (?, ?)");
        $stmt->bind_param("si", $station_name, $playlist_id);
        $stmt->execute();
        $stmt->close();
    } elseif ($action == 'activate_playlist') {
        $stmt = $conn->prepare("UPDATE playlists SET active = 0 WHERE id IN (SELECT playlist_id FROM stations WHERE id = ?)");
        $stmt->bind_param("i", $_POST['station_id']);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("UPDATE playlists SET active = 1 WHERE id = ?");
        $stmt->bind_param("i", $playlist_id);
        $stmt->execute();
        $stmt->close();
    }
}

$stations = $conn->query("SELECT * FROM stations")->fetch_all(MYSQLI_ASSOC);
$playlists = $conn->query("SELECT * FROM playlists")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Stations</title>
</head>
<body>
    <h1>Manage Stations</h1>
    <form method="post">
        <input type="hidden" name="action" value="add_station">
        <input type="text" name="station_name" placeholder="Station Name" required>
        <select name="playlist_id" required>
            <?php foreach ($playlists as $playlist): ?>
                <option value="<?php echo $playlist['id']; ?>"><?php echo "Playlist ID: " . $playlist['id']; ?></option>
            <?php endforeach; ?>
        </select>
        <input type="submit" value="Add Station">
    </form>
    <h2>Stations List</h2>
    <ul>
        <?php foreach ($stations as $station): ?>
            <li>
                <?php echo $station['station_name']; ?>
                <form method="post" style="display: inline;">
                    <input type="hidden" name="action" value="activate_playlist">
                    <input type="hidden" name="station_id" value="<?php echo $station['id']; ?>">
                    <select name="playlist_id" required>
                        <?php foreach ($playlists as $playlist): ?>
                            <option value="<?php echo $playlist['id']; ?>" <?php if ($playlist['id'] == $station['playlist_id']) echo 'selected'; ?>>
                                <?php echo "Playlist ID: " . $playlist['id']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="submit" value="Activate Playlist">
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>

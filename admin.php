<?php
session_start();

// Verifica se l'utente è loggato come Admin e gestione della sessione
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
} else {
    // Verifica del timeout della sessione (60 minuti)
    $inactive = 3600; // 60 minuti di inattività
    $session_expire = $_SESSION['timeout'] ?? time(); // Ottieni il timestamp dell'ultimo accesso
    $session_diff = time() - $session_expire;

    if ($session_diff > $inactive) {
        session_unset();     // Unset delle variabili di sessione
        session_destroy();   // Distruzione della sessione
        header("Location: login.php");
        exit();
    }
    $_SESSION['timeout'] = time(); // Aggiorna il timestamp dell'ultimo accesso
}

// Includi il file di connessione al database
include('bin/db.php');

// Esegui la query per ottenere tutte le stations attive
$sql = "SELECT * FROM stations WHERE active = 1";
$result = $conn->query($sql);

// Array per memorizzare le stations
$stations = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $stations[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h2>Navigation Menu</h2>
            <ul>
                <li><a href="#">Dashboard</a></li>
                <li><a href="#">Users Management</a></li>
                <li><a href="#">Stations Management</a></li>
                <li><a href="#">Playlists Management</a></li>
            </ul>
            <h2>Active Stations</h2>
            <ul>
                <?php foreach ($stations as $station): ?>
                    <li><?php echo $station['name']; ?></li>
                <?php endforeach; ?>
            </ul>
            <p><a href="logout.php">Logout</a></p>
        </div>
        <div class="main">
            <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
            <p>This is the Admin Panel.</p>
        </div>
    </div>
</body>
</html>

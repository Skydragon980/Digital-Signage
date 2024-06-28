<?php
include(__DIR__ . '/db.php'); // Include il file di connessione al database dalla nuova posizione

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, role, station_id FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $stmt->bind_result($id, $role, $station_id);
    $stmt->fetch();
    $stmt->close();

    if ($id) {
        $_SESSION['user_id'] = $id;
        $_SESSION['role'] = $role;
        $_SESSION['station_id'] = $station_id;
        header("Location: ../dashboard.php"); // Percorso relativo alla dashboard
    } else {
        echo "Invalid credentials";
    }
}
?>

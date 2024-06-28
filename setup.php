<?php
// Recupero dei dati dal form
$servername = $_POST['servername'];
$username = $_POST['username'];
$password = $_POST['password'];

$admin_username = $_POST['admin_username'];
$admin_password = $_POST['admin_password'];

// Codifica la password dell'amministratore con MD5
$hashed_admin_password = md5($admin_password);

// Connessione al server MySQL
$conn = new mysqli($servername, $username, $password);

// Verifica della connessione
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Creazione del database digital_signage
$sql_create_db = "CREATE DATABASE IF NOT EXISTS digital_signage";
if ($conn->query($sql_create_db) === TRUE) {
    echo "Database 'digital_signage' created successfully.<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

// Selezione del database digital_signage
$conn->select_db("digital_signage");

// Creazione della tabella users se non esiste già
$sql_create_users_table = "
    CREATE TABLE IF NOT EXISTS users (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL,
        password VARCHAR(32) NOT NULL,
        role ENUM('Admin', 'AdminRemote', 'Manager') NOT NULL,
        station_id INT(6),
        all_stations_access TINYINT(1) DEFAULT 0,
        UNIQUE KEY unique_username (username)
    )
";
if ($conn->query($sql_create_users_table) === TRUE) {
    echo "Table 'users' created successfully.<br>";
} else {
    echo "Error creating table 'users': " . $conn->error . "<br>";
}

// Creazione della tabella stations se non esiste già
$sql_create_stations_table = "
    CREATE TABLE IF NOT EXISTS stations (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50) NOT NULL,
        location VARCHAR(50),
        active TINYINT(1) DEFAULT 0
    )
";
if ($conn->query($sql_create_stations_table) === TRUE) {
    echo "Table 'stations' created successfully.<br>";
} else {
    echo "Error creating table 'stations': " . $conn->error . "<br>";
}

// Creazione della tabella playlists se non esiste già
$sql_create_playlists_table = "
    CREATE TABLE IF NOT EXISTS playlists (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50) NOT NULL,
        description TEXT,
        active TINYINT(1) DEFAULT 0
    )
";
if ($conn->query($sql_create_playlists_table) === TRUE) {
    echo "Table 'playlists' created successfully.<br>";
} else {
    echo "Error creating table 'playlists': " . $conn->error . "<br>";
}

// Creazione della tabella playlist_content se non esiste già
$sql_create_playlist_content_table = "
    CREATE TABLE IF NOT EXISTS playlist_content (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        playlist_id INT(6) UNSIGNED,
        content_url VARCHAR(255) NOT NULL,
        order_index INT(6) UNSIGNED DEFAULT 0,
        FOREIGN KEY (playlist_id) REFERENCES playlists(id)
    )
";
if ($conn->query($sql_create_playlist_content_table) === TRUE) {
    echo "Table 'playlist_content' created successfully.<br>";
} else {
    echo "Error creating table 'playlist_content': " . $conn->error . "<br>";
}

// Inserimento dell'utente amministratore nel database
$sql_insert_admin = "
    INSERT INTO users (username, password, role)
    VALUES ('$admin_username', '$hashed_admin_password', 'Admin')
";
if ($conn->query($sql_insert_admin) === TRUE) {
    echo "Admin user '$admin_username' created successfully.<br>";
} else {
    echo "Error creating admin user: " . $conn->error . "<br>";
}

// Chiudi la connessione al database
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuration Complete</title>
</head>
<body>
    <h1>Configuration Complete</h1>
    <p>Database and tables have been set up successfully.</p>
    <p><a href="admin.php">Go to Admin Panel</a></p>
</body>
</html>


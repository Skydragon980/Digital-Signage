<?php
session_start();

// Verifica se l'utente è già loggato, reindirizza alla dashboard
if (isset($_SESSION['username'])) {
    header("Location: admin.php");
    exit();
}

// Includi il file di connessione al database
include('bin/db.php');

// Inizializzazione delle variabili di errore
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera i dati dal form di login
    $username = $_POST['username'];
    $password = md5($_POST['password']); // Decripta la password inserita con MD5

    // Query per selezionare l'utente dal database
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Utente trovato, esegui l'accesso
        $_SESSION['username'] = $username;
        $_SESSION['timeout'] = time(); // Imposta il timestamp dell'ultimo accesso
        // Redirect alla dashboard dopo il login
        header("Location: admin.php");
    } else {
        // Utente non trovato, gestire l'errore o mostrare un messaggio di errore
        $error_message = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="login-container">
        <form class="login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <h2>Login</h2>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <button type="submit">Login</button>
            </div>
            <p class="error-message"><?php echo $error_message; ?></p>
        </form>
    </div>
</body>
</html>

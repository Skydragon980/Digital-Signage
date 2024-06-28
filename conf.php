<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuration Form</title>
</head>
<body>
    <h1>Initial Configuration</h1>
    <form action="setup.php" method="post">
        <h2>Database Configuration</h2>
        <label for="servername">Server Name:</label>
        <input type="text" id="servername" name="servername" required><br><br>
        
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password"><br><br>
        
        <h2>Admin User Configuration</h2>
        <label for="admin_username">Admin Username:</label>
        <input type="text" id="admin_username" name="admin_username" required><br><br>
        
        <label for="admin_password">Admin Password:</label>
        <input type="password" id="admin_password" name="admin_password" required><br><br>
        
        <input type="submit" value="Setup">
    </form>
</body>
</html>


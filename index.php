<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);
    $md5_password = md5($password);

    $sql = "SELECT * FROM sec_users WHERE login='$username' AND pswd='$md5_password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $user_id = (int)$row['id'];

        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username;
        header("Location: main.php");
        exit;
    } else {
        $error = "Nombre de usuario o contraseña incorrectos";
        header("Location: index.php?error=" . urlencode($error));
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="png" href="images/favicon.png">
    <link rel="stylesheet" href="./styles/styles.css">
    <title>Iniciar sesión</title>
</head>
<body>
    <div class="contenedor">
        <h1>Sistema de Gestión de Expedientes</h1>
        <div class="center">
            <h2>Bienvenido</h2>
            <form action="index.php" method="post">
                <div class="input-container">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required />
                </div>
                <div class="input-container">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required />
                </div>
                <input type="submit" value="Iniciar sesión" />
            </form>
        </div>
    </div>
    <?php if (isset($_GET['error'])): ?>
        <p><?php echo htmlspecialchars($_GET['error']); ?></p>
    <?php endif; ?>
</body>
</html>

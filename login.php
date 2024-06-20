<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = md5($_POST['password']); 

    $sql = "SELECT id, login FROM sec_users WHERE login = ? AND pswd = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $username, $password);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($user_id, $username);
        $stmt->fetch();
        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username;
        header("Location: index.php");
    } else {
        header("Location: login.php?error=Usuario o contraseña incorrectos.");
        exit;
    }

    $stmt->close();
}
$conn->close();
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
            <form action="validate_login.php" method="post">
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























<!-- <!DOCTYPE html>
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
            <form action="validate_login.php" method="post">
                <div class="txt_field">
                    <input type="text" id="username" name="username" required />
                    <span></span>
                    <label>Username</label>
                </div>
                <div class="txt_field">
                    <input type="password" id="password" name="password" required />
                    <span></span>
                    <label>Password</label>
                </div>
                <input type="submit" value="Iniciar sesión" />
            </form>
        </div>
    </div>
    <?php if (isset($_GET['error'])): ?>
        <p><?php echo htmlspecialchars($_GET['error']); ?></p>
    <?php endif; ?>
</body>
</html> -->
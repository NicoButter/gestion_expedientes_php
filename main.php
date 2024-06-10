<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/styles.css">
    <link rel="icon" type="png" href="images/favicon.png">
    <title>Gestión de Expedientes</title>
</head>

<body>
    <div class="contenedor">
        <h1>Bienvenido, <?php echo $_SESSION['username']; ?></h1>
        <h2>Buscar expediente para editar:</h2>
        <div class="center">
            <form action="edit_expediente.php" method="get">
                <label for="numero_expediente">Ingrese el número del expediente:</label>
                <input type="number" id="numero_expediente" name="numero_expediente" required>
                <input type="submit" value="Buscar">
            </form>

        </div>
        <a href="logout.php">Cerrar sesión</a>
    </div>
</body>

</html>
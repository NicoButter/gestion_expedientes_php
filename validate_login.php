<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);
    $md5_password = md5($password);

    // Verificamos credenciales
    $sql = "SELECT * FROM sec_users WHERE login='$username' AND pswd='$md5_password'";
    $result = $conn->query($sql);

    // Depuracion Nico
    echo "Consulta SQL: " . $sql . "<br>";
    echo "Número de filas devueltas: " . $result->num_rows . "<br>";

    if ($result->num_rows == 1) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        header("Location: index.php");
    } else {
        $error = "Nombre de usuario o contraseña incorrectos";
        header("Location: login.php?error=" . urlencode($error));
    }
}


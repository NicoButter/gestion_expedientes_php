<?php
$servername = "localhost";
$username = "tu_usuario";
$password = "tu_pass";
$database = "tu_BBDD";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("No se puede conectar a la base de datos: " . $conn->connect_error);
}


<?php
$servername = "localhost";
$username = "nicolas";
$password = "nicolas010203";
$database = "sse";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("No se puede conectar a la base de datos: " . $conn->connect_error);
}


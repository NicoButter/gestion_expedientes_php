<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

function log_message($message)
{
    $file = 'log.txt';
    $current_content = file_get_contents($file);
    $current_content .= date('Y-m-d H:i:s') . ' - ' . $message . PHP_EOL;
    file_put_contents($file, $current_content);
}

function registrar_cambio_auditoria($conn, $id_expediente, $modificado_por, $campo_modificado, $valor_anterior, $valor_nuevo)
{
    $campo_modificado = strval($campo_modificado);
    $valor_anterior = strval($valor_anterior);
    $valor_nuevo = strval($valor_nuevo);

    $auditoria_sql = "INSERT INTO auditoria_expedientes (id_expediente, modificado_por, campo_modificado, valor_anterior, valor_nuevo, fecha_modificacion) VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt_auditoria = $conn->prepare($auditoria_sql);
    $stmt_auditoria->bind_param("iisss", $id_expediente, $modificado_por, $campo_modificado, $valor_anterior, $valor_nuevo);
    if ($stmt_auditoria->execute()) {
        log_message("Registro de auditoría guardado correctamente para el expediente $id_expediente.");
    } else {
        log_message("Error al guardar el registro de auditoría: " . $stmt_auditoria->error);
    }
}

$id_expediente = intval($_POST['id_expediente']);
$fecha_de_creacion = $_POST['fecha_de_creacion'];
$id_iniciador = intval($_POST['id_iniciador']);
$extracto = $_POST['extracto'];
$fojas = intval($_POST['cantidad_fojas']);
$modificado_por = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;

$sql = "SELECT fecha_de_creacion, id_iniciador, extracto, fojas FROM expediente WHERE id_expediente = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id_expediente);
$stmt->execute();
$result = $stmt->get_result();
$expediente_actual = $result->fetch_assoc();
$stmt->close();

$sql_update = "UPDATE expediente SET fecha_de_creacion = ?, id_iniciador = ?, extracto = ?, fojas = ? WHERE id_expediente = ?";
$stmt_update = $conn->prepare($sql_update);
if (!$stmt_update) {
    log_message("Error en la preparación del statement de actualización: " . $conn->error);
    die("Ha ocurrido un error. Por favor, contacta al administrador.");
}
$stmt_update->bind_param('sissi', $fecha_de_creacion, $id_iniciador, $extracto, $fojas, $id_expediente);

if ($stmt_update->execute()) {
    if ($modificado_por !== null) {
        registrar_cambio_auditoria($conn, $id_expediente, $modificado_por, 'fecha_de_creacion', $expediente_actual['fecha_de_creacion'], $fecha_de_creacion);
        registrar_cambio_auditoria($conn, $id_expediente, $modificado_por, 'id_iniciador', $expediente_actual['id_iniciador'], $id_iniciador);
        registrar_cambio_auditoria($conn, $id_expediente, $modificado_por, 'extracto', $expediente_actual['extracto'], $extracto);
        registrar_cambio_auditoria($conn, $id_expediente, $modificado_por, 'fojas', $expediente_actual['fojas'], $fojas);
    } else {
        echo "Error: El usuario que modificó el expediente no está definido.";
    }
    echo "<script>
        alert('Expediente actualizado correctamente.');
        window.location.href = 'main.php';
        </script>";
} else {
    echo "Error al actualizar el expediente: " . $stmt_update->error;
}



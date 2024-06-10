<?php
session_start();
require_once 'db_connection.php';

if (!isset($_GET['id_expediente'])) {
    echo "No se ha proporcionado el ID del expediente.";
    exit();
}

$id_expediente = $_GET['id_expediente'];

$query = "SELECT a.*, u.username FROM auditoria_expedientes a JOIN sec_users u ON a.modificado_por = u.user_id WHERE a.id_expediente = ? ORDER BY a.fecha_modificacion DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_expediente);
$stmt->execute();
$result = $stmt->get_result();
$auditoria = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/styles.css">
    <link rel="icon" type="image/png" href="images/favicon.png">
    <title>Historial de Modificaciones</title>
</head>
<body>
    <div class="contenedor">
        <h1>Historial de Modificaciones - Expediente <?php echo htmlspecialchars($id_expediente); ?></h1>
        <?php if ($auditoria): ?>
            <table>
                <thead>
                    <tr>
                        <th>Campo Modificado</th>
                        <th>Valor Anterior</th>
                        <th>Valor Nuevo</th>
                        <th>Modificado Por</th>
                        <th>Fecha de Modificación</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($auditoria as $registro): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($registro['campo_modificado']); ?></td>
                            <td><?php echo htmlspecialchars($registro['valor_anterior']); ?></td>
                            <td><?php echo htmlspecialchars($registro['valor_nuevo']); ?></td>
                            <td><?php echo htmlspecialchars($registro['username']); ?></td>
                            <td><?php echo htmlspecialchars($registro['fecha_modificacion']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No hay registros de modificaciones para este expediente.</p>
        <?php endif; ?>
        <a href="index.php">Regresar</a>
    </div>
</body>
</html>

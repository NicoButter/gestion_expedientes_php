<?php

session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
     header("Location: login.php");
     exit;
}

if (!isset($_GET['numero_expediente'])) {
    die("Número de expediente no especificado.");
}

$numero_expediente = intval($_GET['numero_expediente']);

$sql = "SELECT id_expediente, fecha_de_creacion, id_iniciador, extracto, fojas 
        FROM expediente
        WHERE numero = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $numero_expediente);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo " <script>
                alert('Expediente no encontrado.');
                window.location.href = 'index.php';
            </script>";
    exit;
}

$expediente = $result->fetch_assoc();

$sql_iniciadores = "SELECT group_id, description FROM sec_groups WHERE activo = 'S'";
$result_iniciadores = $conn->query($sql_iniciadores);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Expediente</title>
    <link rel="stylesheet" href="./styles/styles.css">
    <link rel="icon" href="png" type="images/favicon.png">
</head>

<body>
    <div class="contenedor">
        <h1>Editar Expediente</h1>
        
        <?php if ($result->num_rows > 0): ?>
            <h2>Número de Expediente: <span style="color: red;"><?php echo htmlspecialchars($numero_expediente); ?></span></h2>
        <?php endif; ?>
        
        <form action="update_expediente.php" method="post">
            <input type="hidden" name="id_expediente"
                value="<?php echo htmlspecialchars($expediente['id_expediente']); ?>">

            <label for="fecha_de_creacion">Fecha de Creación:</label>
            <input type="date" id="fecha_de_creacion" name="fecha_de_creacion"
                value="<?php echo htmlspecialchars($expediente['fecha_de_creacion']); ?>" required><br>

            <label for="id_iniciador">Iniciador:</label>
            <select id="id_iniciador" name="id_iniciador" required>
                <?php while ($iniciador = $result_iniciadores->fetch_assoc()): ?>
                    <option value="<?php echo $iniciador['group_id']; ?>" <?php if ($expediente['id_iniciador'] == $iniciador['group_id'])
                           echo 'selected'; ?>>
                        <?php echo htmlspecialchars($iniciador['description']); ?>
                    </option>
                <?php endwhile; ?>
            </select><br>

            <label for="extracto">Extracto:</label>
            <textarea id="extracto" name="extracto"
                required><?php echo htmlspecialchars($expediente['extracto']); ?></textarea><br>

            <label for="cantidad_fojas">Cantidad de Fojas:</label>
            <input type="number" id="cantidad_fojas" name="cantidad_fojas"
                value="<?php echo htmlspecialchars($expediente['fojas']); ?>" required><br>

            <input type="submit" value="Actualizar">
        </form>

        <form action="generar_caratula.php" method="get" target="_blank">
            <input type="hidden" name="id_expediente"
                value="<?php echo htmlspecialchars($expediente['id_expediente']); ?>">
            <button type="submit">Ver Carátula</button>
        </form>

        <a href="main.php">Volver a la página principal</a>
    </div>
</body>

</html>

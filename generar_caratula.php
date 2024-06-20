<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('fpdf186/fpdf.php');
require('db_connect.php');

// Configurar la conexión a la base de datos para usar UTF-8
$conn->set_charset("utf8");

$id_expediente = isset($_GET['id_expediente']) ? intval($_GET['id_expediente']) : 0;

if ($id_expediente === 0) {
    die("ID del expediente no proporcionado o no válido.");
}

$sql = "SELECT * FROM expediente WHERE id_expediente = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Error en la preparación de la consulta: " . $conn->error);
}

$stmt->bind_param("i", $id_expediente);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $expediente = $result->fetch_assoc();

    class PDF extends FPDF
    {
        function Header()
        {
            $this->SetX(100);
            $this->Image('images/escudo_Santa_Cruz.gif', 80, 10, 32); 
            $this->SetX(120); 
            $this->SetFont('Arial', 'B', 16);
            $this->Cell(0, 0, mb_convert_encoding('Honorable Cámara de Diputados de Santa Cruz', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
            $this->SetX(30); 
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(0, 10, 'Mesa de Entradas', 0, 1, 'C');
            $this->Ln(10);
        }
        
        function Body($expediente, $conn)
        {
            $this->SetFont('Arial', '', 12);

            $id_iniciador = $expediente['id_iniciador'];
            $sql = "SELECT description FROM sec_groups WHERE group_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id_iniciador);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($iniciador_nombre);
                $stmt->fetch();
            } else {
                $iniciador_nombre = 'Iniciador no encontrado';
            }

            $x = $this->GetX();
            $y = $this->GetY();

            $ancho = $this->GetPageWidth() - $this->lMargin - $this->rMargin;
            $this->Rect($x, $y, $ancho, 80); 

            $this->SetFont('Arial', 'B', 12); 
            $this->Cell(50, 10, mb_convert_encoding('Número de Expediente:','ISO-8859-1', 'UTF-8'), 0, 0, 'L');
            $this->SetFont('Arial', '', 12); 
            $this->Cell(0, 10, mb_convert_encoding($expediente['numero'], 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');

            $this->SetFont('Arial', 'B', 12);
            $this->Cell(50, 10, mb_convert_encoding('Fecha de Creación:', 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
            $this->SetFont('Arial', '', 12);
            $this->Cell(0, 10, mb_convert_encoding($expediente['fecha_de_creacion'], 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');

            $this->SetFont('Arial', 'B', 12);
            $this->Cell(50, 10, 'Iniciador:', 0, 0, 'L');
            $this->SetFont('Arial', '', 12);
            $this->Cell(0, 10, mb_convert_encoding($iniciador_nombre, 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');

            $this->SetFont('Arial', 'B', 12);
            $this->Cell(50, 10, 'Extracto:', 0, 0, 'L');
            $this->SetFont('Arial', '', 12);
            $this->MultiCell(0, 10, mb_convert_encoding($expediente['extracto'], 'ISO-8859-1', 'UTF-8'), 0, 'L');
        }
    }

    $pdf = new PDF('L', 'mm', array(187, 267));
    $pdf->SetMargins(80, 30, 10);
    $pdf->SetAutoPageBreak(true, 5);
    $pdf->AddPage();
    $pdf->Body($expediente, $conn);
    $pdf->Output();
} else {
    die("Expediente no encontrado");
}

$conn->close();

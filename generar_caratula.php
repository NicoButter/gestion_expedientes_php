<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('fpdf186/fpdf.php');
require('db_connect.php');

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

            // $this->AddFont('ArialUnicodeMS', '', 'arialuni.php');
            // $this->SetFont('ArialUnicodeMS', '', 12);

            $this->SetX(100);
            
            $this->Image('images/escudo_Santa_Cruz.gif', 80, 10, 32); 
        
            
            $this->SetX(120); 
        
            $this->SetFont('Arial', 'B', 16);
            $this->Cell(0, 0, utf8_decode('Honorable Camara de Diputados de Santa Cruz'), 0, 1, 'C');
        
            
            $this->SetX(30); 
        
            
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(0, 10, 'Mesa de Entradas', 0, 1, 'C');
        
            
            $this->Ln(10);
        }
        

        function Body($expediente, $conn)
        {
            // $this->AddFont('ArialUnicodeMS', '', 'arialuni.php');
            // $this->SetFont('ArialUnicodeMS', '', 12);
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

            // Rectángulo alrededor del contenido del cuerpo
            $this->Rect($x, $y, $ancho, 80); 

            // Contenido del cuerpo
            $this->SetFont('Arial', 'B', 12); 
            $this->Cell(50, 10, 'Número de Expediente:', 0, 0, 'L');
            $this->SetFont('Arial', '', 12); 
            $this->Cell(0, 10, $expediente['numero'], 0, 1, 'L');

            $this->SetFont('Arial', 'B', 12);
            $this->Cell(50, 10, 'Fecha de Creacion:', 0, 0, 'L');
            $this->SetFont('Arial', '', 12);
            $this->Cell(0, 10, $expediente['fecha_de_creacion'], 0, 1, 'L');

            $this->SetFont('Arial', 'B', 12);
            $this->Cell(50, 10, 'Iniciador:', 0, 0, 'L');
            $this->SetFont('Arial', '', 12);
            $this->Cell(0, 10, $iniciador_nombre, 0, 1, 'L');

            $this->SetFont('Arial', 'B', 12);
            $this->Cell(50, 10, 'Extracto:', 0, 0, 'L');
            $this->SetFont('Arial', '', 12);
            $this->MultiCell(0, 10, $expediente['extracto'], 0, 'L');
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


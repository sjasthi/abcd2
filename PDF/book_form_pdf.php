<?php
require('fpdf.php');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "abcd_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM dresses";
$result = $conn->query($sql);

$pdf = new FPDF();

$pdf->SetFont('Arial', 'B', 16);

while ($row = $result->fetch_assoc()) {
	$pdf->AddPage();
    $pdf->Cell(80, 10, 'Name: ' . $row['name'], 0, 1);
    $pdf->Cell(80, 10, 'id: ' . $row['id'], 0, 1);
	$pdf->MultiCell(0, 10, 'Description: ' . $row['description'], 0, 1);
	$pdf->MultiCell(0, 10, 'Did You Know? ' . $row['did_you_know'], 0, 1);
	
	$imageURL = $row['image_url'];

    if (file_exists($imageURL)) {
        $pdf->Image($imageURL, 10, 10, 80, 0); // (URL, X, Y, Width, Height)
    } else {
        $pdf->Cell(0, 10, 'Image not found', 0, 1);
    }
	
	
}

$pdf->Output();

$conn->close();
?>
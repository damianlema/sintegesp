<?php
require('fpdf.php');
require('mc_table.php');
require('mc_table2.php');
require('mc_table3.php');
require('mc_table4.php');
require('mc_table5.php');
require('mc_table6.php');
require('mc_table7.php');
require('mc_table8.php');
define('FPDF_FONTPATH','font/');
$pdf=new PDF_MC_Table('P', 'mm', 'Letter');
		$pdf->Open();
//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	
	$pdf->Ln(8);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(190, 10, 'Sector', 0, 1, 'C');
	//Colores de los bordes, fondo y texto
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(20, 5, 'CODIGO', 1, 0, 'C', 1);
	$pdf->Cell(175, 5, 'DENOMINACION', 1, 0, 'C', 1);
	$pdf->Output();
?>

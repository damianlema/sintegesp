<?php
//	Anexo Orden de Pago...
function emitir_pagos_orden($pdf, $id_emision_pago){
	//	--------------------
	$sql="SELECT 
				pagos_financieros.idorden_pago, 
				pagos_financieros.beneficiario, 
				pagos_financieros.monto_cheque, 
				pagos_financieros.numero_cheque, 
				beneficiarios.nombre,
				pagos_financieros.beneficiario as nombre_beneficiario,  
				cuentas_bancarias.numero_cuenta, 
				banco.denominacion, 
				pagos_financieros.fecha_cheque 
			FROM 
				pagos_financieros, 				
				cuentas_bancarias, 
				banco,
				beneficiarios,
				orden_pago 
			WHERE 
				pagos_financieros.idpagos_financieros   = '".$id_emision_pago."'
				AND pagos_financieros.estado            != 'anulado'
				AND pagos_financieros.idcuenta_bancaria = cuentas_bancarias.idcuentas_bancarias 
				AND cuentas_bancarias.idbanco           = banco.idbanco
				and orden_pago.idorden_pago             = pagos_financieros.idorden_pago
				and beneficiarios.idbeneficiarios       = orden_pago.idbeneficiarios";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	if ($rows!=0) $field=mysql_fetch_array($query);
	//	--------------------
	$monto_letras=$pdf->ValorEnLetras($field["monto_cheque"], "");
	$monto="Bs. ".number_format($field['monto_cheque'], 2, ',', '.');
	list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_cheque']); $fecha=$d."/".$m."/".$a;
	if($m == 1){$m = "Enero";}
	if($m == 2){$m = "Febrero";}
	if($m == 3){$m = "Marzo";}
	if($m == 4){$m = "Abril";}
	if($m == 5){$m = "Mayo";}
	if($m == 6){$m = "Junio";}
	if($m == 7){$m = "Julio";}
	if($m == 8){$m = "Agosto";}
	if($m == 9){$m = "Septiembre";}
	if($m == 10){$m = "Octubre";}
	if($m == 11){$m = "Noviembre";}
	if($m == 12){$m = "Diciembre";}
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->SetXY(128, 23.4); $pdf->Cell(20, 10); $pdf->Cell(20, 10, $monto);
	$pdf->SetXY(25, 38); $pdf->Cell(20, 10); $pdf->Cell(150, 10, utf8_decode($field['nombre_beneficiario']));
	$pdf->SetXY(2, 46); $pdf->Cell(20, 10); $pdf->MultiCell(150, 6, '                    '.$monto_letras);
	$sql_consulta = mysql_query("select * from configuracion");
	$bus_consulta = mysql_fetch_array($sql_consulta);
	$pdf->SetXY(5, 59); $pdf->Cell(20, 10); $pdf->Cell(130, 5, $bus_consulta["ciudad"].' '.$d.' de '.$m);
	$pdf->SetXY(73, 59); $pdf->Cell(20, 10); $pdf->Cell(130, 5, $a);
	
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->SetXY(120, 246); $pdf->Cell(25, 9); $pdf->Cell(130, 5, utf8_decode($field['denominacion']));
	$pdf->SetXY(120, 255); $pdf->Cell(25, 9); $pdf->Cell(130, 5, $field['numero_cuenta']);
	$pdf->SetXY(120, 264); $pdf->Cell(25, 9); $pdf->Cell(130, 5, $field['numero_cheque']);
	
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetXY(145, 249); $pdf->Cell(50, 9, 'Banco');
	$pdf->SetXY(145, 258); $pdf->Cell(50, 9, 'Numero de Cuenta');
	$pdf->SetXY(145, 267); $pdf->Cell(50, 9, 'Numero de Cheque');
	
	$pdf->Rect(145, 251, 60, 0.1);
	$pdf->Rect(145, 260, 60, 0.1);
	$pdf->Rect(145, 269, 60, 0.1);
}

function ordenpago($pdf, $numero, $fecha, $tipo_documento, $pag, $idtipo_documento) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->Cell(15, 4); $pdf->Cell(100, 5, '', 0, 0, 'L');
	$pdf->SetFont('Arial', '', 11);
	$pdf->Cell(50, 4, utf8_decode('Número: '), 0, 0, 'R');
	$pdf->SetFont('Arial', 'B', 11);
	$pdf->Cell(35, 4, $numero, 0, 1, 'L');	
	$pdf->SetFont('Arial', 'B', 11);
	$pdf->Cell(15, 4); $pdf->Cell(100, 5, '', 0, 0, 'L'); 
	$pdf->SetFont('Arial', '', 11);
	$pdf->Cell(50, 4, 'Fecha: ', 0, 0, 'R'); 
	$pdf->SetFont('Arial', 'B', 11);
	list($a, $m, $d)=SPLIT( '[/.-]', $fecha); $fecha=$d."/".$m."/".$a;
	$pdf->Cell(35, 4, $fecha, 0, 1, 'L');	 
	$pdf->SetFont('Arial', '', 11);
	$pdf->Cell(165, 4, utf8_decode('Página: '), 0, 0, 'R'); 
	$pdf->SetFont('Arial', 'B', 11);
	$pdf->Cell(35, 4, $pag, 0, 1, 'L'); 
	/////////////////////////////
	$pdf->Ln(10);
	$pdf->SetFont('Arial', 'B', 16);
	$pdf->Cell(200, 5, 'Orden de Pago', 0, 1, 'C');
	$pdf->Cell(200, 7, '', 0, 1, 'C');
	/////////////////////////////
	getFootOrdenPagoTesoreria($pdf, $modulo, $tipo, $idtipo_documento);
}

function ordenpago_anexo($pdf, $numero, $fecha, $pag, $idtipo_documento) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	setLogo($pdf);
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->Cell(15, 4); $pdf->Cell(100, 5, '', 0, 0, 'L');
	$pdf->SetFont('Arial', '', 11);
	$pdf->Cell(50, 4, utf8_decode('Número: '), 0, 0, 'R');
	$pdf->SetFont('Arial', 'B', 11);
	$pdf->Cell(35, 4, $numero, 0, 1, 'L');	
	$pdf->SetFont('Arial', 'B', 11);
	$pdf->Cell(15, 4); $pdf->Cell(100, 5, '', 0, 0, 'L'); 
	$pdf->SetFont('Arial', '', 11);
	$pdf->Cell(50, 4, 'Fecha: ', 0, 0, 'R'); 
	$pdf->SetFont('Arial', 'B', 11);
	list($a, $m, $d)=SPLIT( '[/.-]', $fecha); $fecha=$d."/".$m."/".$a;
	$pdf->Cell(35, 4, $fecha, 0, 1, 'L');	 
	$pdf->SetFont('Arial', '', 11);
	$pdf->Cell(165, 4, utf8_decode('Página: '), 0, 0, 'R'); 
	$pdf->SetFont('Arial', 'B', 11);
	$pdf->Cell(35, 4, $pag, 0, 1, 'L'); 
	/////////////////////////////
	$pdf->Ln(10);
	$pdf->SetFont('Arial', 'B', 16);
	$pdf->Cell(200, 5, 'Orden de Pago', 0, 1, 'C');
	$pdf->Cell(200, 7, '', 0, 1, 'C');
}




function comprobante_contable($pdf, $fecha, $pag) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->Cell(15, 6); $pdf->Cell(100, 5, '', 0, 0, 'L');
	$pdf->SetFont('Arial', '', 11);
	$pdf->Cell(49, 6, utf8_decode('Código:'), 0, 0, 'R');
	$pdf->SetFont('Arial', 'B', 11);
	$pdf->Cell(35, 6, 'FOR-DGA-008', 0, 1, 'L');	
	$pdf->SetFont('Arial', 'B', 11);
	$pdf->Cell(15, 6); $pdf->Cell(100, 5, '', 0, 0, 'L');
	$pdf->SetFont('Arial', '', 11);
	$pdf->Cell(50, 6, utf8_decode('Página: '), 0, 0, 'R'); 
	$pdf->SetFont('Arial', 'B', 11);
	$pdf->Cell(35, 6, $pag, 0, 1, 'L');
	/*$pdf->SetFont('Arial', '', 11);
	$pdf->Cell(165, 6, 'Fecha Contable: ', 0, 0, 'R'); 
	$pdf->SetFont('Arial', 'B', 11);
	list($a, $m, $d)=SPLIT( '[/.-]', $fecha); $fecha=$d."/".$m."/".$a;
	$pdf->Cell(35, 6, $fecha, 0, 1, 'L');
*/
	 
	/////////////////////////////
	$pdf->Ln(11);
	$pdf->SetFont('Arial', 'B', 11);
	$pdf->Cell(200, 5, 'Comprobante Contable', 0, 1, 'C');
	$pdf->Cell(200, 7, '', 0, 1, 'C');
	/////////////////////////////
	
}



function ordenpago_compromisos_cancelados($pdf, $numero, $fecha, $pag, $idtipo_documento, $modulo) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	setLogo($pdf);
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->Cell(15, 4); $pdf->Cell(100, 5, '', 0, 0, 'L');
	$pdf->SetFont('Arial', '', 11);
	$pdf->Cell(50, 4, utf8_decode('Número: '), 0, 0, 'R');
	$pdf->SetFont('Arial', 'B', 11);
	$pdf->Cell(35, 4, $numero, 0, 1, 'L');	
	$pdf->SetFont('Arial', 'B', 11);
	$pdf->Cell(15, 4); $pdf->Cell(100, 5, '', 0, 0, 'L'); 
	$pdf->SetFont('Arial', '', 11);
	$pdf->Cell(50, 4, 'Fecha: ', 0, 0, 'R'); 
	$pdf->SetFont('Arial', 'B', 11);
	list($a, $m, $d)=SPLIT( '[/.-]', $fecha); $fecha=$d."/".$m."/".$a;
	$pdf->Cell(35, 4, $fecha, 0, 1, 'L');	 
	$pdf->SetFont('Arial', '', 11);
	$pdf->Cell(165, 4, utf8_decode('Página: '), 0, 0, 'R'); 
	$pdf->SetFont('Arial', 'B', 11);
	$pdf->Cell(35, 4, $pag, 0, 1, 'L'); 
	/////////////////////////////
	$pdf->Ln(10);
	$pdf->SetFont('Arial', 'B', 14);
	$pdf->Cell(200, 5, utf8_decode('Relación Compromisos Cancelados'), 0, 1, 'C');
	$pdf->Cell(200, 7, '', 0, 1, 'C');
	$pdf->Ln(5);		
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->Cell(20, 5, 'Nro.Orden', 1, 0, 'C', 1);
	$pdf->Cell(16, 5, 'Fecha', 1, 0, 'C', 1);
	$pdf->Cell(15, 5, 'Factura', 1, 0, 'C', 1);
	$pdf->Cell(15, 5, 'Control', 1, 0, 'C', 1);
	$pdf->Cell(15, 5, 'F.Factura', 1, 0, 'C', 1);
	if (in_array(1,$modulo)==true) {
		$pdf->Cell(21, 5, 'Asignaciones', 1, 0, 'C', 1);
		$pdf->Cell(21, 5, 'Deducciones', 1, 0, 'C', 1);
	} else {
		$pdf->Cell(21, 5, 'Sub-Total', 1, 0, 'C', 1);
		$pdf->Cell(21, 5, 'Exento', 1, 0, 'C', 1);
	}
	$pdf->Cell(21, 5, 'Impuesto', 1, 0, 'C', 1);
	$pdf->Cell(21, 5, 'Total', 1, 0, 'C', 1);
	$pdf->Cell(21, 5, utf8_decode('Retención'), 1, 0, 'C', 1);
	$pdf->Cell(21, 5, 'Total a Pagar', 1, 1, 'C', 1);
	$pdf->Ln(2);
}

//	Relacion de Ingresos y Egresos...
function relacion_ingresos_egresos($pdf, $fdesde, $fhasta, $tipo, $movimiento, $banco, $cuenta, $head) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	//	--------------------
	if ($tipo=="ingreso") $tipo="Ingresos"; else $tipo="Egresos";
	
	$pdf->Ln(14);
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(195, 10, utf8_decode('Relación de Ingresos y Egresos'), 0, 1, 'C');
	$pdf->Ln(1);	
	//	-----------------------------------------------------------
	if ($head==1) {
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(10, 6, 'Desde: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(25, 6, $fdesde, 0, 0, 'L');
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(10, 6, 'Hasta: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(25, 6, $fhasta, 0, 1, 'L');
		$pdf->Ln(1);
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 8);	
		$pdf->Cell(65, 4, 'Banco', 1, 0, 'C', 1);	
		$pdf->Cell(40, 4, 'Cuenta', 1, 0, 'C', 1);	
		$pdf->Cell(25, 4, 'Documento', 1, 0, 'C', 1);	
		$pdf->Cell(20, 4, 'Fecha', 1, 0, 'C', 1);	
		$pdf->Cell(10, 4, 'Tipo', 1, 0, 'C', 1);	
		$pdf->Cell(10, 4, 'Mov.', 1, 0, 'C', 1);	
		$pdf->Cell(35, 4, 'Monto', 1, 0, 'C', 1);	
		$pdf->Ln(1);
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetAligns(array('L', 'L', 'L', 'C', 'C', 'C', 'R'));
		$pdf->SetWidths(array(65, 40, 25, 20, 10, 10, 35));	
		$pdf->Ln(4);
	}
	//	-----------------------------------------------------------
	elseif ($head==2) {
		$pdf->Cell(65, 4); 
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(8, 6, 'Tipo: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(32, 6, $tipo, 0, 0, 'L');
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(17, 6, 'Movimiento: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(23, 6, $movimiento, 0, 1, 'L');
		$pdf->Cell(65, 4); 
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(10, 6, 'Banco: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(30, 6, $banco, 0, 0, 'L');
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(12, 6, 'Cuenta: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(27, 6, $cuenta, 0, 1, 'L');
		$pdf->Cell(65, 4); 
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(10, 6, 'Desde: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(30, 6, $fdesde, 0, 0, 'L');
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(10, 6, 'Hasta: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(30, 6, $fhasta, 0, 1, 'L');
		$pdf->Ln(1);
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 8);	
		$pdf->Cell(65, 4); 
		$pdf->Cell(25, 4, 'Documento', 1, 0, 'C', 1);	
		$pdf->Cell(20, 4, 'Fecha', 1, 0, 'C', 1);	
		$pdf->Cell(35, 4, 'Monto', 1, 0, 'C', 1);	
		$pdf->Ln(1);
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', '', 8);
		$pdf->Cell(65, 4); 
		$pdf->SetAligns(array('L', 'C', 'R'));
		$pdf->SetWidths(array(25, 20, 35));	
		$pdf->Ln(4);
	}
	//	-----------------------------------------------------------
	elseif ($head==3) {
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(8, 6, 'Tipo: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(32, 6, $tipo, 0, 1, 'L');		
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(10, 6, 'Desde: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(25, 6, $fdesde, 0, 0, 'L');
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(10, 6, 'Hasta: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(25, 6, $fhasta, 0, 1, 'L');
		$pdf->Ln(1);
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 8);	
		$pdf->Cell(75, 4, 'Banco', 1, 0, 'C', 1);	
		$pdf->Cell(40, 4, 'Cuenta', 1, 0, 'C', 1);	
		$pdf->Cell(25, 4, 'Documento', 1, 0, 'C', 1);	
		$pdf->Cell(20, 4, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(10, 4, 'Mov.', 1, 0, 'C', 1);	
		$pdf->Cell(35, 4, 'Monto', 1, 0, 'C', 1);	
		$pdf->Ln(1);
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetAligns(array('L', 'L', 'L', 'C', 'C', 'R'));
		$pdf->SetWidths(array(75, 40, 25, 20, 10, 35));	
		$pdf->Ln(4);
	}
	//	-----------------------------------------------------------
	elseif ($head==4) {
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(8, 6, 'Tipo: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(32, 6, $tipo, 0, 0, 'L');
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(17, 6, 'Movimiento: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(23, 6, $movimiento, 0, 1, 'L');
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(10, 6, 'Desde: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(30, 6, $fdesde, 0, 0, 'L');
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(10, 6, 'Hasta: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(30, 6, $fhasta, 0, 1, 'L');
		$pdf->Ln(1);
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 8);	
		$pdf->Cell(60, 4, utf8_decode('Descripción'), 1, 0, 'C', 1);
		$pdf->Cell(25, 4, 'Banco', 1, 0, 'C', 1);	
		$pdf->Cell(40, 4, 'Cuenta', 1, 0, 'C', 1);	
		$pdf->Cell(25, 4, 'Documento', 1, 0, 'C', 1);	
		$pdf->Cell(20, 4, 'Fecha', 1, 0, 'C', 1);	
		$pdf->Cell(35, 4, 'Monto', 1, 0, 'C', 1);	
		$pdf->Ln(1);
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetAligns(array('L', 'L', 'L', 'L', 'C', 'R'));
		$pdf->SetWidths(array(60, 25, 40, 25, 20, 35));	
		$pdf->Ln(4);
	}
	//	-----------------------------------------------------------
	elseif ($head==5) {
		$pdf->Cell(40, 4); 
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(8, 6, 'Tipo: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(32, 6, $tipo, 0, 0, 'L');
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(17, 6, 'Movimiento: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(23, 6, $movimiento, 0, 1, 'L');
		$pdf->Cell(40, 4); 
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(10, 6, 'Banco: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(30, 6, $banco, 0, 1, 'L');
		$pdf->Cell(40, 4); 
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(10, 6, 'Desde: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(30, 6, $fdesde, 0, 0, 'L');
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(10, 6, 'Hasta: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(30, 6, $fhasta, 0, 1, 'L');
		$pdf->Ln(1);
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 8);	
		$pdf->Cell(40, 4); 
		$pdf->Cell(45, 4, 'Cuenta', 1, 0, 'C', 1);	
		$pdf->Cell(25, 4, 'Documento', 1, 0, 'C', 1);	
		$pdf->Cell(20, 4, 'Fecha', 1, 0, 'C', 1);	
		$pdf->Cell(35, 4, 'Monto', 1, 0, 'C', 1);	
		$pdf->Ln(1);
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetAligns(array('L', 'L', 'C', 'R'));
		$pdf->Cell(40, 4); $pdf->SetWidths(array(45, 25, 20, 35));	
		$pdf->Ln(4);
	}
	//	-----------------------------------------------------------
	elseif ($head==6) {
		$pdf->Cell(40, 4); 
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(10, 6, 'Banco: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(30, 6, $banco, 0, 1, 'L');
		$pdf->Cell(40, 4); 
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(10, 6, 'Desde: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(30, 6, $fdesde, 0, 0, 'L');
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(10, 6, 'Hasta: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(30, 6, $fhasta, 0, 1, 'L');
		$pdf->Ln(1);
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 8);	
		$pdf->Cell(40, 4); 
		$pdf->Cell(45, 4, 'Cuenta', 1, 0, 'C', 1);	
		$pdf->Cell(25, 4, 'Documento', 1, 0, 'C', 1);	
		$pdf->Cell(20, 4, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(10, 4, 'Tipo', 1, 0, 'C', 1);	
		$pdf->Cell(10, 4, 'Mov.', 1, 0, 'C', 1);
		$pdf->Cell(35, 4, 'Monto', 1, 0, 'C', 1);	
		$pdf->Ln(1);
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetAligns(array('L', 'L', 'C', 'C', 'C', 'R'));
		$pdf->Cell(40, 4); $pdf->SetWidths(array(45, 25, 20, 10, 10, 35));	
		$pdf->Ln(4);
	}
	//	-----------------------------------------------------------
	elseif ($head==7) {
		$pdf->Cell(40, 4); 
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(10, 6, 'Banco: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(30, 6, $banco, 0, 1, 'L');
		$pdf->Cell(40, 4); 
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(10, 6, 'Cuenta: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(30, 6, $cuenta, 0, 1, 'L');
		$pdf->Cell(40, 4); 
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(10, 6, 'Desde: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(30, 6, $fdesde, 0, 0, 'L');
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(10, 6, 'Hasta: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(30, 6, $fhasta, 0, 1, 'L');
		$pdf->Ln(1);
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 8);	
		$pdf->Cell(40, 4); 
		$pdf->Cell(25, 4, 'Documento', 1, 0, 'C', 1);	
		$pdf->Cell(20, 4, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(10, 4, 'Tipo', 1, 0, 'C', 1);	
		$pdf->Cell(10, 4, 'Mov.', 1, 0, 'C', 1);
		$pdf->Cell(35, 4, 'Monto', 1, 0, 'C', 1);	
		$pdf->Ln(1);
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetAligns(array('L', 'C', 'C', 'C', 'R'));
		$pdf->Cell(40, 4); $pdf->SetWidths(array(25, 20, 10, 10, 35));	
		$pdf->Ln(4);
	}
	//	-----------------------------------------------------------
	elseif ($head==8) {
		$pdf->Cell(65, 4); 
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(8, 6, 'Tipo: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(32, 6, $tipo, 0, 0, 'L');
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(17, 6, '', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(23, 6, '', 0, 1, 'L');
		$pdf->Cell(65, 4); 
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(10, 6, 'Banco: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(30, 6, $banco, 0, 0, 'L');
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(17, 6, '', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(23, 6, '', 0, 1, 'L');
		$pdf->Cell(65, 4);
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(10, 6, 'Cuenta: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(27, 6, $cuenta, 0, 1, 'L');
		$pdf->Cell(65, 4); 
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(10, 6, 'Desde: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(30, 6, $fdesde, 0, 0, 'L');
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(10, 6, 'Hasta: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(30, 6, $fhasta, 0, 1, 'L');
		$pdf->Ln(1);
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 8);	
		$pdf->Cell(65, 4); 
		$pdf->Cell(25, 4, 'Documento', 1, 0, 'C', 1);	
		$pdf->Cell(20, 4, 'Fecha', 1, 0, 'C', 1);	
		$pdf->Cell(35, 4, 'Monto', 1, 0, 'C', 1);	
		$pdf->Ln(1);
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', '', 8);
		$pdf->Cell(65, 4); 
		$pdf->SetAligns(array('L', 'C', 'R'));
		$pdf->SetWidths(array(25, 20, 35));	
		$pdf->Ln(4);
	
	}
}

//	Relacion de Cheques...
function relacion_cheques($pdf, $fdesde, $fhasta, $banco, $cuenta, $estado, $beneficiario, $head) {
	if ($tipo=="ingreso") $tipo="Ingresos"; else $tipo="Egresos";
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	//	--------------------
	$pdf->Ln(10);
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(195, 10, utf8_decode('Relación de Cheques'), 0, 1, 'C');
	$pdf->Ln(1);	
	//	-----------------------------------------------------------
	if ($head==1) {
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(10, 4, 'Banco: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(50, 4, $banco, 0, 0, 'L');
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(11, 4, 'Cuenta: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(50, 4, $cuenta, 0, 1, 'L');
		$pdf->Ln(1);
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 8);	
		$pdf->Cell(18, 4, 'Nro. Cheque', 1, 0, 'C', 1);	
		$pdf->Cell(17, 4, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(25, 4, 'O/P', 1, 0, 'C', 1);	
		$pdf->Cell(110, 4, 'Beneficiario', 1, 0, 'C', 1);
		$pdf->Cell(5, 4, 'Est', 1, 0, 'C', 1);
		$pdf->Cell(30, 4, 'Monto', 1, 0, 'C', 1);
		$pdf->Ln(1);
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetAligns(array('C', 'C', 'L', 'L', 'C', 'R'));
		$pdf->SetWidths(array(18, 17, 25, 110, 5, 30));	
		$pdf->Ln(4);
	}
	//	-----------------------------------------------------------
	elseif ($head==2) {
		$pdf->Cell(55, 4); 
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(10, 4, 'Banco: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(50, 4, $banco, 0, 1, 'L');
		$pdf->Cell(55, 4); 
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(11, 4, 'Cuenta: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(49, 4, $cuenta, 0, 1, 'L');
		$pdf->Cell(55, 4); 
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(18, 4, 'Beneficiario: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(100, 4, $beneficiario, 0, 1, 'L');
		$pdf->Cell(55, 4);
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(12, 4, 'Estado: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(26, 4, $estado, 0, 0, 'L');
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(10, 4, 'Desde: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(30, 4, $fdesde, 0, 0, 'L');
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(10, 4, 'Hasta: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(30, 4, $fhasta, 0, 1, 'L');
		$pdf->Ln(1);
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 8);	
		$pdf->Cell(55, 4); 
		$pdf->Cell(30, 4, 'Nro. Cheque', 1, 0, 'C', 1);	
		$pdf->Cell(30, 4, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(30, 4, 'O/P', 1, 0, 'C', 1);	
		$pdf->Cell(35, 4, 'Monto', 1, 0, 'C', 1);
		$pdf->Ln(1);
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', '', 8);
		$pdf->Cell(55, 4); 
		$pdf->SetAligns(array('C', 'C', 'L', 'R'));
		$pdf->SetWidths(array(30, 30, 30, 35));	
		$pdf->Ln(4);
	}
	//	-----------------------------------------------------------
	elseif ($head==3) {
		$pdf->Cell(10, 4); 
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(10, 4, 'Banco: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(50, 4, $banco, 0, 1, 'L');
		$pdf->Cell(10, 4); 
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(11, 4, 'Cuenta: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(49, 4, $cuenta, 0, 1, 'L');
		$pdf->Cell(10, 4); 
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(12, 4, 'Estado: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(26, 4, $estado, 0, 1, 'L');
		$pdf->Ln(1);
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 8);	
		$pdf->Cell(10, 4); 
		$pdf->Cell(18, 4, 'Nro. Cheque', 1, 0, 'C', 1);	
		$pdf->Cell(17, 4, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(25, 4, 'O/P', 1, 0, 'C', 1);		
		$pdf->Cell(110, 4, 'Beneficiario', 1, 0, 'C', 1);
		$pdf->Cell(30, 4, 'Monto', 1, 0, 'C', 1);
		$pdf->Ln(1);
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetAligns(array('C', 'C', 'L', 'L', 'R'));
		$pdf->SetWidths(array(18, 17, 25, 110, 30));	
		$pdf->Ln(4);
	}
	//	-----------------------------------------------------------
	elseif ($head==4) {
		$pdf->Cell(45, 4); 
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(10, 4, 'Banco: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(50, 4, $banco, 0, 0, 'L');
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(11, 4, 'Cuenta: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(50, 4, $cuenta, 0, 1, 'L');
		$pdf->Cell(45, 4); 
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(18, 4, 'Beneficiario: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(100, 4, $beneficiario, 0, 1, 'L');
		$pdf->Ln(1);
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 8);	
		$pdf->Cell(45, 4); 
		$pdf->Cell(18, 4, 'Nro. Cheque', 1, 0, 'C', 1);	
		$pdf->Cell(17, 4, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(25, 4, 'O/P', 1, 0, 'C', 1);	
		$pdf->Cell(5, 4, 'Est', 1, 0, 'C', 1);
		$pdf->Cell(35, 4, 'Monto', 1, 0, 'C', 1);
		$pdf->Ln(1);
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetAligns(array('C', 'C', 'L', 'L', 'C', 'R'));
		$pdf->SetWidths(array(18, 17, 25, 5, 35));	
		$pdf->Ln(4);
	}
	//	-----------------------------------------------------------
	elseif ($head==5) {
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(10, 4, 'Banco: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(50, 4, $banco, 0, 0, 'L');
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(11, 4, 'Cuenta: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(50, 4, $cuenta, 0, 1, 'L');
		
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(10, 4, 'Desde: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(50, 4, $fdesde, 0, 0, 'L');
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(10, 4, 'Hasta: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(30, 4, $fhasta, 0, 1, 'L');
		$pdf->Ln(1);
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 8);	
		$pdf->Cell(20, 4, 'Nro. Cheque', 1, 0, 'C', 1);	
		$pdf->Cell(15, 4, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(25, 4, 'O/P', 1, 0, 'C', 1);	
		$pdf->Cell(110, 4, 'Beneficiario', 1, 0, 'C', 1);
		$pdf->Cell(5, 4, 'Est', 1, 0, 'C', 1);
		$pdf->Cell(30, 4, 'Monto', 1, 0, 'C', 1);
		$pdf->Ln(1);
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetAligns(array('C', 'C', 'L', 'L', 'C', 'R'));
		$pdf->SetWidths(array(18, 17, 25, 110, 5, 30));	
		$pdf->Ln(4);
	}
	//	-----------------------------------------------------------
	elseif ($head==6) {
		$pdf->Cell(55, 4);
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(10, 4, 'Banco: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(50, 4, $banco, 0, 0, 'L');
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(11, 4, 'Cuenta: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(50, 4, $cuenta, 0, 1, 'L');
		$pdf->Cell(55, 4);
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(18, 4, 'Beneficiario: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(100, 4, $beneficiario, 0, 1, 'L');
		$pdf->Cell(55, 4);
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(12, 4, 'Estado: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(26, 4, $estado, 0, 1, 'L');
		$pdf->Ln(1);
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(55, 4);	
		$pdf->Cell(18, 4, 'Nro. Cheque', 1, 0, 'C', 1);	
		$pdf->Cell(17, 4, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(25, 4, 'O/P', 1, 0, 'C', 1);	
		$pdf->Cell(35, 4, 'Monto', 1, 0, 'C', 1);
		$pdf->Ln(1);
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetAligns(array('C', 'C', 'L', 'R'));
		$pdf->SetWidths(array(18, 17, 25, 35));	
		$pdf->Ln(4);
	}
	//	-----------------------------------------------------------
	elseif ($head==7) {
		$pdf->Cell(10, 4);
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(10, 4, 'Banco: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(50, 4, $banco, 0, 0, 'L');
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(11, 4, 'Cuenta: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(50, 4, $cuenta, 0, 1, 'L');
		$pdf->Cell(10, 4);
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(12, 4, 'Estado: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(48, 4, $estado, 0, 0, 'L');
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(10, 4, 'Desde: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(20, 4, $fdesde, 0, 0, 'L');
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(10, 4, 'Hasta: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(20, 4, $fhasta, 0, 1, 'L');
		$pdf->Ln(1);
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(10, 4);	
		$pdf->Cell(18, 4, 'Nro. Cheque', 1, 0, 'C', 1);	
		$pdf->Cell(17, 4, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(25, 4, 'O/P', 1, 0, 'C', 1);	
		$pdf->Cell(110, 4, 'Beneficiario', 1, 0, 'C', 1);
		$pdf->Cell(30, 4, 'Monto', 1, 0, 'C', 1);
		$pdf->Ln(1);
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetAligns(array('C', 'C', 'L', 'L', 'R'));
		$pdf->SetWidths(array(18, 17, 25, 110, 30));	
		$pdf->Ln(4);
	}
	//	-----------------------------------------------------------
	elseif ($head==8) {
		$pdf->Cell(45, 4);
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(10, 4, 'Banco: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(50, 4, $banco, 0, 0, 'L');
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(11, 4, 'Cuenta: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(50, 4, $cuenta, 0, 1, 'L');
		$pdf->Cell(45, 4);
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(18, 4, 'Beneficiario: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(100, 4, $beneficiario, 0, 1, 'L');
		$pdf->Cell(45, 4);
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(10, 4, 'Desde: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(20, 4, $fdesde, 0, 0, 'L');
		$pdf->SetFont('Arial', '', 8);	$pdf->Cell(10, 4, 'Hasta: ', 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(20, 4, $fhasta, 0, 1, 'L');
		$pdf->Ln(1);
		$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(45, 4);	
		$pdf->Cell(18, 4, 'Nro. Cheque', 1, 0, 'C', 1);	
		$pdf->Cell(17, 4, 'Fecha', 1, 0, 'C', 1);
		$pdf->Cell(25, 4, 'O/P', 1, 0, 'C', 1);	
		$pdf->Cell(5, 4, 'Est', 1, 0, 'C', 1);
		$pdf->Cell(30, 4, 'Monto', 1, 0, 'C', 1);
		$pdf->Ln(1);
		$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetAligns(array('C', 'C', 'L', 'C', 'R'));
		$pdf->SetWidths(array(18, 17, 25, 5, 30));	
		$pdf->Ln(4);
	}
}

//	Relacion Cheques-OP...
function relacion_cheques_op($pdf, $orden) { 
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	//	--------------------
	$pdf->Ln(20);
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(185, 10, utf8_decode('Relación de Cheques - Orden de Pago'), 0, 1, 'C');
	$pdf->Ln(1);	
	//	-----------------------------------------------------------
	$pdf->SetFont('Arial', '', 8);	$pdf->Cell(10, 5); $pdf->Cell(30, 4, 'Nro. Orden de Pago: ', 0, 0, 'L');
	$pdf->SetFont('Arial', 'B', 8); $pdf->Cell(50, 4, $orden, 0, 1, 'L');
	$pdf->Ln(1);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(250, 250, 250); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);	
	$pdf->Cell(10, 5); 
	$pdf->Cell(30, 4, 'Nro. Cheque', 1, 0, 'C', 1);	
	$pdf->Cell(30, 4, 'Fecha', 1, 0, 'C', 1);	
	$pdf->Cell(95, 4, 'Beneficiario', 1, 0, 'C', 1);
	$pdf->Cell(30, 4, 'Monto', 1, 0, 'C', 1);
	$pdf->Ln(1);
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetAligns(array('C', 'C', 'L', 'R'));
	$pdf->Cell(10, 5); $pdf->SetWidths(array(30, 30, 95, 30));	
	$pdf->Ln(4);
}

//	Estado de Cuenta...
function estado_cuenta($pdf, $banco, $tcta, $ncta, $ucta, $periodo) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Ln(14);
	$pdf->Cell(195, 10, 'ESTADO DE CUENTA', 0, 1, 'C');
	$pdf->SetDrawColor(230, 230, 230); $pdf->SetFillColor(240, 240, 240); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(195, 5, utf8_decode('BANCO '.$banco.' CTA. '.$tcta.' Nº. '.$ncta), 0, 1, 'L', 1);
	$pdf->Cell(195, 5, 'DENOMINACION '.$ucta, 0, 1, 'L', 1);
	$pdf->Cell(195, 5, $periodo, 0, 1, 'L', 1);
	//
	$pdf->Ln(2);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
	$y=$pdf->GetY();
	$x=$pdf->GetX();
	$pdf->Rect($x, $y, 195, 0.1);
	$pdf->Ln(2);
	//
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetAligns(array('C', 'C', 'L','L', 'R', 'R', 'R', 'R'));
	$pdf->SetWidths(array(14, 7 , 20, 55, 25, 25, 25, 25));
	$pdf->Row(array('Fecha', 'T', 'Refer.', 'Denominacion', 'Transito', 'Debe', 'Haber', 'Saldo'));
	//$pdf->SetAligns(array('L', 'C', 'R'));
	//$pdf->SetWidths(array(150, 20, 25));
	//$pdf->Row(array('Denominacion', 'Fecha', 'Monto'));
	$pdf->Ln(2);
}

//	Conciliacion...
function conciliacion($pdf, $banco, $tcta, $ncta, $ucta, $periodo) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Ln(14);
	$pdf->Cell(195, 10, 'CONCILIACION BANCARIA', 0, 1, 'C');
	$pdf->SetDrawColor(230, 230, 230); $pdf->SetFillColor(240, 240, 240); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(195, 5, utf8_decode('BANCO '.$banco.' CTA. '.$tcta.' Nº. '.$ncta), 0, 1, 'L', 1);
	$pdf->Cell(195, 5, 'DENOMINACION '.$ucta, 0, 1, 'L', 1);
	$pdf->Cell(195, 5, $periodo, 0, 1, 'L', 1);
	//
	$pdf->Ln(2);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
	$y=$pdf->GetY();
	$x=$pdf->GetX();
	$pdf->Rect($x, $y, 195, 0.1);
	$pdf->Ln(2);
	//
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Ln(2);
}

//	Libro Diario Banco...
function libro_diario_banco($pdf, $banco, $tcta, $ncta, $ucta, $periodo) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Ln(14);
	$pdf->Cell(195, 10, 'LIBRO DIARIO BANCO', 0, 1, 'C');
	$pdf->SetDrawColor(230, 230, 230); $pdf->SetFillColor(240, 240, 240); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(195, 5, utf8_decode('BANCO '.$banco.' CTA. '.$tcta.' Nº. '.$ncta), 0, 1, 'L', 1);
	$pdf->Cell(195, 5, 'DENOMINACION '.$ucta, 0, 1, 'L', 1);
	$pdf->Cell(195, 5, $periodo, 0, 1, 'L', 1);
	//
	$pdf->Ln(2);
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0);
	$y=$pdf->GetY();
	$x=$pdf->GetX();
	$pdf->Rect($x, $y, 195, 0.1);
	$pdf->Ln(2);
	//
	$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetAligns(array('C', 'T', 'L','L', 'R', 'R', 'R'));
	$pdf->SetWidths(array(16, 8 , 20, 76, 25, 25, 25));
	$pdf->Row(array('Fecha', 'T', 'Refer.', 'Denominacion', 'Debe', 'Haber', 'Saldo'));
	$pdf->Ln(2);
}

//	(Modulo) Remision de Documentos...
function remitirdoc($pdf, $numero, $fecha, $para, $de, $asunto, $justificacion, $ndocs, $cuenta, $tipocomp, $pag) {
	//	NUEVA PAGINA CABECERA
	$pdf->AddPage();
	//	--------------------
	setLogo($pdf);
	//	--------------------
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->Cell(27, 17); $pdf->Cell(40, 5, '', 0, 0, 'C');
	$pdf->Cell(27, 17, '', 0, 0, 'C'); 
	//
	$pdf->Cell(15, 5); $pdf->Cell(25, 5, '', 0, 0, 'L');
	$pdf->SetFont('Arial', '', 11);
	$pdf->Cell(35, 5, utf8_decode('Número: '), 0, 0, 'R');
	$pdf->SetFont('Arial', 'B', 11);
	$pdf->Cell(35, 5, $numero, 0, 1, 'L');	
	$pdf->SetFont('Arial', 'B', 11);
	$pdf->Cell(15, 5); $pdf->Cell(120, 5, '', 0, 0, 'L'); 
	$pdf->SetFont('Arial', '', 11);
	$pdf->Cell(34, 5, 'Fecha: ', 0, 0, 'R'); 
	$pdf->SetFont('Arial', 'B', 11);
	list($a, $m, $d)=SPLIT( '[/.-]', $fecha); $fecha=$d."/".$m."/".$a;
	$pdf->Cell(35, 5, $fecha, 0, 1, 'L');	 
	$pdf->SetFont('Arial', '',11);
	$pdf->Cell(169, 5, utf8_decode('Página: '), 0, 0, 'R'); 
	$pdf->SetFont('Arial', 'B', 11);
	$pdf->Cell(35, 5, $pag, 0, 1, 'L'); 
	/////////////////////////////
	$pdf->Ln(3);
	$pdf->SetFont('Arial', 'B', 16);
	$pdf->Cell(205, 16, utf8_decode('Relación de Ordenes de Pago a Generar Comprobante'), 0, 1, 'C');
	$pdf->Ln(2);
	/////////////////////////////
	$sql_cuenta = mysql_query("select * from cuentas_bancarias where idcuentas_bancarias = '".$cuenta."'");
	$bus_cuenta = mysql_fetch_array($sql_cuenta);
	$nrocuenta = $bus_cuenta["numero_cuenta"];
	
	$sql_banco = mysql_query("select * from banco where idbanco = '".$bus_cuenta["idbanco"]."'");
	$bus_banco = mysql_fetch_array($sql_banco);
	$banco = $bus_banco["denominacion"];
	/////////////////////////////
	$sql="SELECT denominacion FROM dependencias WHERE iddependencia='".$para."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	if ($rows!=0) { $field=mysql_fetch_array($query); $para=$field[0]; }
	$sql="SELECT denominacion FROM dependencias WHERE iddependencia='".$de."'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$rows=mysql_num_rows($query);
	if ($rows!=0) { $field=mysql_fetch_array($query); $de=$field[0]; }
	/////////////////////////////
	$pdf->SetFont('Arial', '', 10); $pdf->Cell(30, 7, 'Para:', 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 10); $pdf->MultiCell(175, 7, utf8_decode($para), 0, 1, 'L');
	$pdf->SetFont('Arial', '', 10); $pdf->Cell(30, 7, 'De:', 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 10); $pdf->MultiCell(175, 7, utf8_decode($de), 0, 1, 'L');
	//$pdf->SetFont('Arial', '', 10); $pdf->Cell(30, 7, 'Asunto:', 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 10); $pdf->MultiCell(175, 7, utf8_decode($asunto), 0, 1, 'L');
	$pdf->SetFont('Arial', '', 10); $pdf->Cell(30, 7, 'Banco:', 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 10); $pdf->MultiCell(175, 7, utf8_decode($banco)." - Cuenta: ".utf8_decode($nrocuenta), 0, 1, 'L');
	//$pdf->SetFont('Arial', '', 10); $pdf->Cell(30, 7, 'Nro. Cuenta:', 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 10); $pdf->MultiCell(75, 7, utf8_decode($nrocuenta), 0, 1, 'L');
	//$pdf->SetFont('Arial', '', 10); $pdf->Cell(30, 7, 'Nro. Doc. Anexos:', 0, 0, 'L'); $pdf->SetFont('Arial', 'B', 10); $pdf->MultiCell(175, 7, $ndocs, 0, 1, 'L');
	$pdf->SetFont('Arial', '', 10); $pdf->Cell(30, 7, 'Concepto:', 0, 0, 'L'); $pdf->SetFont('Arial', '', 10); $pdf->MultiCell(175, 7, utf8_decode($justificacion), 0, 1, 'L');
	$pdf->Ln(5);
	/////////////////////////////
	//$pdf->SetFont('Arial', 'BU', 14);
	//$pdf->Cell(205, 16, 'Documentos Anexos', 0, 1, 'C');
	/////////////////////////////	
	$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(30, 5, 'Nro. OP.', 1, 0, 'C', 1);	
	$pdf->Cell(18, 5, 'Fecha', 1, 0, 'C', 1);	
	$pdf->Cell(107, 5, 'Beneficiario', 1, 0, 'C', 1);
	$pdf->Cell(25, 5, 'Monto', 1, 0, 'C', 1);
	//$pdf->Cell(15, 5, '% Ret.', 1, 0, 'C', 1);
	$pdf->Cell(25, 5, 'Monto Ret.', 1, 0, 'C', 1);
	$pdf->Ln(6);
	
	
	/*$pdf->Cell(25, 5, 'Nro. OP.', 1, 0, 'C', 1);
	$pdf->Cell(15, 5, 'Fecha', 1, 0, 'C', 1);
	$pdf->Cell(95, 5, 'Beneficiario', 1, 0, 'C', 1);
	$pdf->Cell(25, 5, 'Monto', 1, 1, 'C', 1);
	$pdf->Cell(15, 5, '% Ret.', 1, 1, 'C', 1);
	$pdf->Cell(25, 5, 'Monto Ret.', 1, 1, 'C', 1);*/
	
}
?>

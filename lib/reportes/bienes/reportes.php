<?php
session_start();
set_time_limit(-1);
ini_set("memory_limit", "200M");
extract($_GET);
extract($_POST);
//	----------------------------------------
$MAXLINEA=30;
$MAXLINEALEG=15;
//	----------------------------------------
require('../../../conf/conex.php');
require('../../mc_table.php');
require('../../mc_table2.php');
require('../../mc_table3.php');
require('../../mc_table4.php');
require('../../mc_table5.php');
require('../../mc_table6.php');
require('../../mc_table7.php');
require('../../mc_table8.php');
require('../../mc_table8a.php');
require('../firmas.php');
require('head.php');
//	----------------------------------------
Conectarse();
$ahora=date("d-m-Y H:i:s");
//	----------------------------------------

//	----------------------------------------
//	CUERPO DE LOS REPORTES
//	----------------------------------------
switch ($nombre) {
	//	Inventario de Inmuebles...
	case "inmuebles_inventario":
		$pdf = new PDF_MC_Table5('L', 'mm', 'Letter');
		$pdf->SetTopMargin(5);
		$pdf->SetLeftMargin(5);
		$pdf->SetAutoPageBreak(1, 2.5);
		
		//	Imprimo una nueva página...
		inmuebles_inventario($pdf);
		
		
		$filtro = "";
		//	Verifico el filtro...
		if ($organizacion != 0) $filtro = "WHERE organizacion = '".$organizacion."'";
		if ($catalogo != 0) {
			if ($filtro != "") $filtro .= " AND iddetalle_catalogo_bienes = '".$catalogo."'";
			else $filtro = "WHERE iddetalle_catalogo_bienes = '".$catalogo."'";
		}
		
		//	Consulto e imprimo el inventario...
		$sql = "(SELECT 
						codigo_bien,
						denominacion_inmueble,
						valor_contabilidad_fecha AS fecha_compra,
						valor_contabilidad_monto AS costo
				FROM 
						edificios
				$filtro)
						
				UNION
				
				(SELECT
						codigo_bien,
						denominacion_inmueble,
						contabilidad_fecha AS fecha_compra,
						contabilidad_valor AS costo
				FROM 
						terrenos
				$filtro)
				
				ORDER BY codigo_bien";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		while ($field = mysql_fetch_array($query)) {
			list($a, $m, $d) = split('[/.-]', $field['fecha_compra']); $fecha_compra = "$d/$m/$a";
			
			$pdf->Row(array($field['codigo_bien'], utf8_decode($field['denominacion_inmueble']), $fecha_compra, number_format($field['costo'], 2, ',', '.')));
		}
		break;
	
	//	Hoja de Trabajo 1...
	case "inmuebles_hoja1":
		$pdf = new PDF_MC_Table4('P', 'mm', 'A4');
		$pdf->SetTopMargin(5);
		$pdf->SetLeftMargin(15);
		$pdf->SetAutoPageBreak(1, 2.5);
		$hoja = 1;
		//	--------------------------------------
		//	Imprimo edificios...
		//	--------------------------------------
		$sql = "SELECT 
						e.*,
						tmb.denominacion AS tipo_movimiento,
						dcb.codigo AS codigo_catalogo, 
						dcb.denominacion AS nombre_catalogo, 
						edo.denominacion AS estado
				FROM 
						edificios e
						INNER JOIN tipo_movimiento_bienes tmb ON (e.idtipo_movimiento = tmb.idtipo_movimiento_bienes)
						INNER JOIN detalle_catalogo_bienes dcb ON (e.iddetalle_catalogo_bienes = dcb.iddetalle_catalogo_bienes)
						INNER JOIN estado edo ON (e.estado_municipio_propietario = edo.idestado)
				WHERE 
						e.codigo_bien = '".$codigo."'
				ORDER BY e.idedificios";
		$query = mysql_query($sql) or die ($sql.mysql_error());	
		while ($field = mysql_fetch_array($query)) {
			//	Añado una nueva hoja e imprimo la cabecera...
			inmuebles_hoja($pdf);		
			$pdf->SetFont('Arial', 'BU', 10);
			$pdf->Cell(110, 10, utf8_decode('HOJA DE TRABAJO Nº 1'), 0, 0, 'L');
			$pdf->Cell(80, 10, 'PARA DESCRIPCION DE EDIFICIOS', 0, 1, 'R');
			$pdf->Ln(5);
			
			//	Imprimo el cuerpo de la hoja....
			$pdf->SetFont('Arial', '', 10);
			$pdf->Cell(190, 5, utf8_decode('Del expediente Nº ________________  (Utilícese un formulario para cada construcción)'), 0, 1, 'L');		
			$pdf->Cell(190, 5, 'Tipo de Movimiento: '.utf8_decode($field['tipo_movimiento']), 0, 1, 'L');
			$pdf->Cell(190, 5, utf8_decode('Código de Catálogo:'.$field['codigo_catalogo'].' - '.$field['nombre_catalogo']), 0, 1, 'L');
			$pdf->Ln(3);
			
			$pdf->Cell(8, 10, '1.', 0, 0, 'L'); 
			$pdf->Cell(182, 10, 'Estado (o Municipio) propietario: __________________________________________________________________', 0, 1, 'L');
			
			$pdf->Cell(8, 10, '2.', 0, 0, 'L'); 
			$pdf->Cell(182, 10, utf8_decode('Denominación del inmueble: _____________________________________________________________________'), 0, 1, 'L');
			
			$pdf->Cell(8, 10, '3.', 0, 0, 'L'); 
			$pdf->Cell(182, 10, utf8_decode('Clasificación funcional del Inmueble (Uso principal al que esta destinado):'), 0, 1, 'L');
			$pdf->Cell(8, 10, '', 0, 0, 'L');
			$pdf->Cell(182, 10, '____________________________________________________________________________________________', 0, 1, 'L');
			
			$pdf->Cell(8, 10, '4.', 0, 0, 'L'); 
			$pdf->Cell(182, 10, utf8_decode('Ubicación geográfica:  Estado,  Territorio ___________________________________________________________'), 0, 1, 'L');		
			$pdf->Cell(8, 10, '', 0, 0, 'L'); 
			$pdf->Cell(182, 10, utf8_decode('Municipio: ___________________________________  Dirección: _______________________________________'), 0, 1, 'L');
			$pdf->Cell(8, 10, '', 0, 0, 'L');
			$pdf->Cell(182, 10, '____________________________________________________________________________________________', 0, 1, 'L');
			
			$pdf->Cell(8, 10, '5.', 0, 0, 'L'); 
			$pdf->Cell(182, 10, 'Area total del terreno: __________________________________________________________________________', 0, 1, 'L');
			
			$pdf->Cell(8, 10, '6.', 0, 0, 'L'); 
			$pdf->Cell(182, 10, utf8_decode('Area de la Construcción:  Area cubierta (ocupada por el edificio sobre el terreno)'), 0, 1, 'L');
			$pdf->Cell(8, 10, '', 0, 0, 'L');
			$pdf->Cell(182, 10, '____________________________________________________________________________________________', 0, 1, 'L');		
			$pdf->Cell(8, 10, '', 0, 0, 'L'); 
			$pdf->Cell(182, 10, utf8_decode('Número de pisos: _____________________________________________________________________________'), 0, 1, 'L');	
			$pdf->Cell(8, 10, '', 0, 0, 'L'); 
			$pdf->Cell(182, 10, utf8_decode('Area total de la construcción (total de los pisos): _____________________________________________________'), 0, 1, 'L');
			$pdf->Cell(8, 10, '', 0, 0, 'L');
			$pdf->Cell(182, 10, '____________________________________________________________________________________________', 0, 1, 'L');		
			$pdf->Cell(8, 10, '', 0, 0, 'L'); 
			$pdf->Cell(182, 10, 'Area de las anexidades (jardines, patios, etc.): ______________________________________________________', 0, 1, 'L');
			$pdf->Cell(8, 10, '', 0, 0, 'L');
			$pdf->Cell(182, 10, '____________________________________________________________________________________________', 0, 1, 'L');	
			$pdf->Ln(5);
			
			$pdf->Cell(8, 10, '7.', 0, 0, 'L'); 
			$pdf->Cell(182, 10, utf8_decode('Descripción del Inmueble: (Márquense con una X las estructuras y materiales predominantes)'), 0, 1, 'L');
			
			$pdf->SetFont('Arial', '', 10); $pdf->Cell(8, 10, '', 0, 0, 'L'); $pdf->Cell(8, 10, 'a)', 0, 0, 'L');
			$pdf->SetFont('Arial', 'U', 10); $pdf->Cell(85, 10, 'Tipo de estructura:', 0, 0, 'L');
			
			$pdf->SetFont('Arial', '', 10); $pdf->Cell(8, 10, '', 0, 0, 'L'); $pdf->Cell(8, 10, 'b)', 0, 0, 'L');
			$pdf->SetFont('Arial', 'U', 10); $pdf->Cell(75, 10, 'Pisos:', 0, 1, 'L');
			
			$pdf->SetFont('Arial', '', 10);
			$pdf->Cell(24, 10, '', 0, 0, 'L');
			$pdf->Cell(40, 10, 'Paredes de carga', 0, 0, 'L'); $pdf->Cell(60, 10, '_____', 0, 0, 'L'); $pdf->Cell(40, 10, 'Tierra', 0, 0, 'L'); $pdf->Cell(60, 10, '_____', 0, 1, 'L');
			$pdf->Cell(24, 10, '', 0, 0, 'L');
			$pdf->Cell(40, 10, 'Madera', 0, 0, 'L'); $pdf->Cell(60, 10, '_____', 0, 0, 'L'); $pdf->Cell(40, 10, 'Cemento', 0, 0, 'L'); $pdf->Cell(60, 10, '_____', 0, 1, 'L');
			$pdf->Cell(24, 10, '', 0, 0, 'L');
			$pdf->Cell(40, 10, utf8_decode('Metálica'), 0, 0, 'L'); $pdf->Cell(60, 10, '_____', 0, 0, 'L'); $pdf->Cell(40, 10, 'Ladrillo', 0, 0, 'L'); $pdf->Cell(60, 10, '_____', 0, 1, 'L');
			$pdf->Cell(24, 10, '', 0, 0, 'L');
			$pdf->Cell(40, 10, 'Concreto armado', 0, 0, 'L'); $pdf->Cell(60, 10, '_____', 0, 0, 'L'); $pdf->Cell(40, 10, 'Mosaico', 0, 0, 'L'); $pdf->Cell(60, 10, '_____', 0, 1, 'L');
			$pdf->Cell(24, 10, '', 0, 0, 'L');
			$pdf->Cell(12, 10, 'Otras', 0, 0, 'L'); $pdf->Cell(88, 10, '___________________', 0, 0, 'L'); $pdf->Cell(40, 10, 'Granito', 0, 0, 'L'); $pdf->Cell(60, 10, '_____', 0, 1, 'L');
			$pdf->Cell(24, 4, '', 0, 0, 'L');
			$pdf->Cell(12, 4, '', 0, 0, 'L'); $pdf->Cell(88, 5, '(Especificar)', 0, 0, 'L'); $pdf->Cell(12, 10, 'Otras', 0, 0, 'L'); $pdf->Cell(88, 10, '___________________', 0, 1, 'L');
			$pdf->Cell(24, 4, '', 0, 0, 'L');
			$pdf->Cell(12, 4, '', 0, 0, 'L'); $pdf->Cell(88, 5, '', 0, 0, 'L'); $pdf->Cell(12, 4, '', 0, 0, 'L'); $pdf->Cell(88, 5, '(Especificar)', 0, 0, 'L');
			
			//	Imprimo los datos de la tabla...
			$pdf->SetFont('Arial', '', 10);
			$pdf->SetXY(75, 50); $pdf->Cell(115, 5, utf8_decode($field['estado']), 0, 1, 'L'); 
			$pdf->SetXY(70, 60); $pdf->Cell(115, 5, utf8_decode($field['denominacion_inmueble']), 0, 1, 'L'); 
			$pdf->SetXY(23, 80); $pdf->Cell(115, 5, utf8_decode($field['clasificacion_funcional_inmueble']), 0, 1, 'L'); 
			$pdf->SetXY(90, 90); $pdf->Cell(115, 5, utf8_decode($field['ubicacion_geografica_estado']), 0, 1, 'L'); 
			$pdf->SetXY(40, 100); $pdf->Cell(115, 5, utf8_decode($field['ubicacion_geografica_municipio']), 0, 1, 'L');
			
			$direccion1 = substr($field['ubicacion_geografica_direccion'], 0, 37);
			$direccion2 = substr($field['ubicacion_geografica_direccion'], 37, 150);		
			$pdf->SetXY(128, 100); $pdf->Cell(115, 5, utf8_decode($direccion1), 0, 1, 'L');
			$pdf->SetXY(23, 110); $pdf->Cell(115, 5, utf8_decode($direccion2), 0, 1, 'L');
			
			$pdf->SetXY(58, 120); $pdf->Cell(115, 5, utf8_decode($field['area_terreno']), 0, 1, 'L'); 
			
			$pdf->SetXY(23, 140); $pdf->Cell(115, 5, utf8_decode($field['area_construccion']), 0, 1, 'L'); 
			$pdf->SetXY(53, 150); $pdf->Cell(115, 5, utf8_decode($field['numero_pisos']), 0, 1, 'L');
			
			$area1 = substr($field['area_total_construccion'], 0, 46);
			$area2 = substr($field['area_total_construccion'], 46, 150);		
			$pdf->SetXY(100, 160); $pdf->Cell(115, 5, utf8_decode($area1), 0, 1, 'L');
			$pdf->SetXY(23, 170); $pdf->Cell(115, 5, utf8_decode($area2), 0, 1, 'L');
			
			$area1 = substr($field['area_anexidades'], 0, 48);
			$area2 = substr($field['area_anexidades'], 48, 150);
			$pdf->SetXY(98, 180); $pdf->Cell(115, 5, utf8_decode($area1), 0, 1, 'L');
			$pdf->SetXY(23, 190); $pdf->Cell(115, 5, utf8_decode($area2), 0, 1, 'L');
			
			list($paredes, $madera, $metalica, $concreto, $otras) = split('[,.,]', $field['tipo_estructura']);
			if ($paredes != "") { $pdf->SetXY(83, 225); $pdf->Cell(115, 5, 'X', 0, 1, 'L'); }
			if ($madera != "") { $pdf->SetXY(83, 235); $pdf->Cell(115, 5, 'X', 0, 1, 'L'); }
			if ($metalica != "") { $pdf->SetXY(83, 245); $pdf->Cell(115, 5, 'X', 0, 1, 'L'); }
			if ($concreto != "") { $pdf->SetXY(83, 255); $pdf->Cell(115, 5, 'X', 0, 1, 'L'); }
			if ($otras != "") { $pdf->SetXY(83, 265); $pdf->Cell(115, 5, utf8_decode($otras), 0, 1, 'L'); }
			
			list($tierra, $cemento, $ladrillo, $mosaico, $granito, $otros) = split('[,.,]', $field['pisos']);
			if ($tierra != "") { $pdf->SetXY(182, 225); $pdf->Cell(115, 5, 'X', 0, 1, 'L'); }
			if ($cemento != "") { $pdf->SetXY(182, 235); $pdf->Cell(115, 5, 'X', 0, 1, 'L'); }
			if ($ladrillo != "") { $pdf->SetXY(182, 245); $pdf->Cell(115, 5, 'X', 0, 1, 'L'); }
			if ($mosaico != "") { $pdf->SetXY(182, 255); $pdf->Cell(115, 5, 'X', 0, 1, 'L'); }
			if ($granito != "") { $pdf->SetXY(182, 265); $pdf->Cell(115, 5, 'X', 0, 1, 'L'); }
			if ($otros != "") { $pdf->SetXY(151, 275); $pdf->Cell(115, 5, utf8_decode($otros), 0, 1, 'L'); }
			//	-----------------------------------------
					
			//	Añado una nueva hoja...
			$pdf->AddPage();
			
			//	Imprimo el cuerpo de la hoja....		
			$pdf->SetFont('Arial', '', 10); $pdf->Cell(8, 10, '', 0, 0, 'L'); $pdf->Cell(8, 10, 'c)', 0, 0, 'L');
			$pdf->SetFont('Arial', 'U', 10); $pdf->Cell(85, 10, 'Paredes:', 0, 0, 'L');
			
			$pdf->SetFont('Arial', '', 10); $pdf->Cell(8, 10, '', 0, 0, 'L'); $pdf->Cell(8, 10, 'd)', 0, 0, 'L');
			$pdf->SetFont('Arial', 'U', 10); $pdf->Cell(75, 10, 'Techos:', 0, 1, 'L');
			
			$pdf->SetFont('Arial', '', 10);
			$pdf->SetXY(40, 15); $pdf->MultiCell(40, 4, 'Bloques de arcilla', 0, 'L'); 
			$pdf->SetXY(80, 15); $pdf->MultiCell(60, 4, '_____', 0, 'L');
			$pdf->SetXY(140, 15); $pdf->MultiCell(40, 4, utf8_decode('Metálicos (zinc o aluminio)'), 0, 'L'); 
			$pdf->SetXY(180, 15); $pdf->MultiCell(60, 4, '_____', 0, 'L');
			
			$pdf->SetXY(40, 25); $pdf->MultiCell(40, 4, 'Bloques de concreto', 0, 'L'); 
			$pdf->SetXY(80, 25); $pdf->MultiCell(60, 4, '_____', 0, 'L');
			$pdf->SetXY(140, 25); $pdf->MultiCell(40, 4, 'Asbesto', 0, 'L'); 
			$pdf->SetXY(180, 25); $pdf->MultiCell(60, 4, '_____', 0, 'L');
			
			$pdf->SetXY(40, 35); $pdf->MultiCell(40, 4, 'Ladrillos', 0, 'L'); 
			$pdf->SetXY(80, 35); $pdf->MultiCell(60, 4, '_____', 0, 'L');
			$pdf->SetXY(140, 35); $pdf->MultiCell(40, 4, 'Tejas de arcilla sobre losa de concreto', 0, 'L'); 
			$pdf->SetXY(180, 35); $pdf->MultiCell(60, 4, '_____', 0, 'L');
			
			$pdf->SetXY(40, 45); $pdf->MultiCell(40, 4, 'Madera', 0, 'L'); 
			$pdf->SetXY(80, 45); $pdf->MultiCell(60, 4, '_____', 0, 'L');
			$pdf->SetXY(140, 45); $pdf->MultiCell(40, 4, 'Tejas de arcilla sobre caña amarga o similar', 0, 'L'); 
			$pdf->SetXY(180, 45); $pdf->MultiCell(60, 4, '_____', 0, 'L');
			
			$pdf->SetXY(40, 55); $pdf->MultiCell(40, 4, utf8_decode('Metálicas'), 0, 'L'); 
			$pdf->SetXY(80, 55); $pdf->MultiCell(60, 4, '_____', 0, 'L');
			$pdf->SetXY(140, 55); $pdf->MultiCell(40, 4, 'Platabanda', 0, 'L'); 
			$pdf->SetXY(180, 55); $pdf->MultiCell(60, 4, '_____', 0, 'L');
			
			$pdf->SetXY(40, 65); $pdf->MultiCell(40, 4, 'Otras', 0, 'L'); 
			$pdf->SetXY(50, 65); $pdf->MultiCell(60, 4, '____________________', 0, 'L');
			$pdf->SetXY(140, 65); $pdf->MultiCell(40, 4, 'Otros', 0, 'L'); 
			$pdf->SetXY(150, 65); $pdf->MultiCell(60, 4, '____________________', 0, 'L');		
			$pdf->SetXY(50, 70); $pdf->MultiCell(40, 4, '(Especificar)', 0, 'C'); 
			$pdf->SetXY(150, 70); $pdf->MultiCell(40, 4, '(Especificar)', 0, 'C'); 
			
			$pdf->Ln(15);
			$pdf->SetFont('Arial', '', 10); $pdf->Cell(8, 10, '', 0, 0, 'L'); $pdf->Cell(8, 10, 'e)', 0, 0, 'L');
			$pdf->SetFont('Arial', 'U', 10); $pdf->Cell(85, 10, 'Puertas y ventanas:', 0, 0, 'L');
			
			$pdf->SetFont('Arial', '', 10);
			$pdf->SetXY(40, 100); $pdf->MultiCell(40, 4, 'De madera', 0, 'L'); 
			$pdf->SetXY(80, 100); $pdf->MultiCell(60, 4, '_____', 0, 'L');
			$pdf->SetXY(140, 100); $pdf->MultiCell(40, 4, utf8_decode('Metálicas'), 0, 'L'); 
			$pdf->SetXY(180, 100); $pdf->MultiCell(60, 4, '_____', 0, 'L');
			
	
			$pdf->Ln(15);
			$pdf->SetFont('Arial', '', 10); $pdf->Cell(8, 10, '', 0, 0, 'L'); $pdf->Cell(8, 10, 'f)', 0, 0, 'L');
			$pdf->SetFont('Arial', 'U', 10); $pdf->Cell(85, 10, 'Servicios:', 0, 0, 'L');
			
			$pdf->SetFont('Arial', '', 10);
			$pdf->SetXY(40, 130); $pdf->MultiCell(40, 4, 'Sanitarios', 0, 'L'); 
			$pdf->SetXY(80, 130); $pdf->MultiCell(60, 4, '_____', 0, 'L');
			$pdf->SetXY(140, 130); $pdf->MultiCell(40, 4, utf8_decode('Teléfonos'), 0, 'L'); 
			$pdf->SetXY(180, 130); $pdf->MultiCell(60, 4, '_____', 0, 'L');
			
			$pdf->SetXY(40, 140); $pdf->MultiCell(40, 4, 'Cocinas', 0, 'L'); 
			$pdf->SetXY(80, 140); $pdf->MultiCell(60, 4, '_____', 0, 'L');
			$pdf->SetXY(140, 140); $pdf->MultiCell(40, 4, 'Aire acondicionado', 0, 'L'); 
			$pdf->SetXY(180, 140); $pdf->MultiCell(60, 4, '_____', 0, 'L');
			
			$pdf->SetXY(40, 150); $pdf->MultiCell(40, 4, 'Agua corriente', 0, 'L'); 
			$pdf->SetXY(80, 150); $pdf->MultiCell(60, 4, '_____', 0, 'L');
			$pdf->SetXY(140, 150); $pdf->MultiCell(40, 4, 'Ascensores', 0, 'L'); 
			$pdf->SetXY(180, 150); $pdf->MultiCell(60, 4, '_____', 0, 'L');
			
			$pdf->SetXY(40, 160); $pdf->MultiCell(40, 4, 'Electricidad', 0, 'L'); 
			$pdf->SetXY(80, 160); $pdf->MultiCell(60, 4, '_____', 0, 'L');
			$pdf->SetXY(140, 160); $pdf->MultiCell(40, 4, 'Otros', 0, 'L'); 
			$pdf->SetXY(150, 160); $pdf->MultiCell(60, 4, '____________________', 0, 'L');
			$pdf->SetXY(150, 165); $pdf->MultiCell(40, 4, '(Especificar)', 0, 'C'); 
			
			$pdf->Ln(15);
			$pdf->SetFont('Arial', '', 10); $pdf->Cell(8, 10, '', 0, 0, 'L'); $pdf->Cell(8, 10, 'f)', 0, 0, 'L');
			$pdf->SetFont('Arial', 'U', 10); $pdf->Cell(85, 10, 'Otras anexidades del edificio:', 0, 0, 'L');
			
			$pdf->SetFont('Arial', '', 10);
			$pdf->SetXY(40, 195); $pdf->MultiCell(40, 4, 'Patios', 0, 'L'); 
			$pdf->SetXY(80, 195); $pdf->MultiCell(60, 4, '_____', 0, 'L');
			$pdf->SetXY(140, 195); $pdf->MultiCell(40, 4, 'Estacionamientos', 0, 'L'); 
			$pdf->SetXY(180, 195); $pdf->MultiCell(60, 4, '_____', 0, 'L');
			
			$pdf->SetXY(40, 205); $pdf->MultiCell(40, 4, 'Jardines', 0, 'L'); 
			$pdf->SetXY(80, 205); $pdf->MultiCell(60, 4, '_____', 0, 'L');
			$pdf->SetXY(140, 205); $pdf->MultiCell(40, 4, 'Otros', 0, 'L'); 
			$pdf->SetXY(150, 205); $pdf->MultiCell(60, 4, '____________________', 0, 'L');
			$pdf->SetXY(150, 210); $pdf->MultiCell(40, 4, '(Especificar)', 0, 'C'); 
			
			$pdf->Ln(15);		
			$pdf->Cell(8, 10, '7.', 0, 0, 'L'); 
			$pdf->MultiCell(170, 10, 'Linderos: _____________________________________________________________________________ _____________________________________________________________________________________ _____________________________________________________________________________________', 0, 1, 'L');
			
			//	Imprimo los datos de la tabla...
			list($arcilla, $concreto, $ladrillos, $madera, $metalicas, $otras) = split('[,.,]', $field['paredes']);
			if ($arcilla != "") { $pdf->SetXY(83, 14); $pdf->Cell(115, 5, 'X', 0, 1, 'L'); }
			if ($concreto != "") { $pdf->SetXY(83, 24); $pdf->Cell(115, 5, 'X', 0, 1, 'L'); }
			if ($ladrillos != "") { $pdf->SetXY(83, 34); $pdf->Cell(115, 5, 'X', 0, 1, 'L'); }
			if ($madera != "") { $pdf->SetXY(83, 44); $pdf->Cell(115, 5, 'X', 0, 1, 'L'); }
			if ($metalicas != "") { $pdf->SetXY(83, 54); $pdf->Cell(115, 5, 'X', 0, 1, 'L'); }
			if ($otras != "") { $pdf->SetXY(50, 64); $pdf->Cell(115, 5, utf8_decode($otras), 0, 1, 'L'); }
			
			list($metalicos, $asbesto, $arcilla, $tejas, $platabanda, $otros) = split('[,.,]', $field['techos']);
			if ($metalicos != "") { $pdf->SetXY(182, 14); $pdf->Cell(115, 5, 'X', 0, 1, 'L'); }
			if ($asbesto != "") { $pdf->SetXY(182, 24); $pdf->Cell(115, 5, 'X', 0, 1, 'L'); }
			if ($arcilla != "") { $pdf->SetXY(182, 34); $pdf->Cell(115, 5, 'X', 0, 1, 'L'); }
			if ($tejas != "") { $pdf->SetXY(182, 44); $pdf->Cell(115, 5, 'X', 0, 1, 'L'); }
			if ($platabanda != "") { $pdf->SetXY(182, 54); $pdf->Cell(115, 5, 'X', 0, 1, 'L'); }
			if ($otros != "") { $pdf->SetXY(150, 64); $pdf->Cell(115, 5, utf8_decode($otros), 0, 1, 'L'); }
			
			list($madera, $metalicas) = split('[,.,]', $field['puertas_ventanas']);
			if ($madera != "") { $pdf->SetXY(83, 99); $pdf->Cell(115, 5, 'X', 0, 1, 'L'); }
			if ($metalicas != "") { $pdf->SetXY(182, 99); $pdf->Cell(115, 5, 'X', 0, 1, 'L'); }
			
			list($sanitarios, $cocinas, $agua, $electricidad, $telefonos, $aire, $ascensores, $otros) = split('[,.,]', $field['servicios']);
			if ($sanitarios != "") { $pdf->SetXY(83, 129); $pdf->Cell(115, 5, 'X', 0, 1, 'L'); }
			if ($cocinas != "") { $pdf->SetXY(83, 139); $pdf->Cell(115, 5, 'X', 0, 1, 'L'); }
			if ($agua != "") { $pdf->SetXY(83, 149); $pdf->Cell(115, 5, 'X', 0, 1, 'L'); }
			if ($electricidad != "") { $pdf->SetXY(83, 159); $pdf->Cell(115, 5, 'X', 0, 1, 'L'); }
			if ($telefonos != "") { $pdf->SetXY(182, 129); $pdf->Cell(115, 5, 'X', 0, 1, 'L'); }
			if ($aire != "") { $pdf->SetXY(182, 139); $pdf->Cell(115, 5, 'X', 0, 1, 'L'); }
			if ($ascensores != "") { $pdf->SetXY(182, 149); $pdf->Cell(115, 5, 'X', 0, 1, 'L'); }
			if ($otros != "") { $pdf->SetXY(150, 159); $pdf->Cell(115, 5, utf8_decode($otros), 0, 1, 'L'); }
			
			list($patios, $jardines, $estacionamientos, $otros) = split('[,.,]', $field['otras_anexidades']);
			if ($patios != "") { $pdf->SetXY(83, 194); $pdf->Cell(115, 5, 'X', 0, 1, 'L'); }
			if ($jardines != "") { $pdf->SetXY(83, 204); $pdf->Cell(115, 5, 'X', 0, 1, 'L'); }
			if ($estacionamientos != "") { $pdf->SetXY(182, 194); $pdf->Cell(115, 5, 'X', 0, 1, 'L'); }
			if ($otros != "") { $pdf->SetXY(150, 204); $pdf->Cell(115, 5, utf8_decode($otros), 0, 1, 'L'); }
			
			$linderos1 = substr($field['linderos'], 0, 313);
			$linderos2 = substr($field['linderos'], 300, 1000);
			$pdf->SetXY(23, 228); $pdf->MultiCell(170, 10, '                   '.utf8_decode($linderos1), 0, 'L');
			
			$pdf->SetXY(175, 270); $pdf->MultiCell(20, 10, 'continua...', 0, 'L');
			
			//	Añado una nueva hoja...
			$pdf->AddPage();
			
			$pdf->SetXY(150, 10); $pdf->MultiCell(50, 10, utf8_decode('(continuación linderos)'), 0, 'L');
			$pdf->SetXY(23, 20); $pdf->MultiCell(170, 10, '_____________________________________________________________________________________ _____________________________________________________________________________________ _____________________________________________________________________________________', 0, 1, 'L');
			$pdf->SetXY(23, 19); $pdf->MultiCell(170, 10, utf8_decode($linderos2), 0, 'L');
			
			$pdf->SetXY(15, 55); $pdf->MultiCell(8, 5, '9.', 0, 'L'); 
			$pdf->SetXY(23, 55); $pdf->MultiCell(170, 5, 'Estado legal de la propiedad: (Obtener dictamen del Procurador del Estado o del Sindico Procurador Municipal)', 0, 'L');
			$pdf->SetXY(23, 65); $pdf->Cell(182, 10, '_____________________________________________________________________________________', 0, 0, 'L');
			$pdf->SetXY(23, 75); $pdf->Cell(182, 10, '_____________________________________________________________________________________', 0, 0, 'L');
			$pdf->SetXY(23, 85); $pdf->Cell(182, 10, '_____________________________________________________________________________________', 0, 0, 'L');
			$pdf->SetXY(23, 65); $pdf->MultiCell(170, 10, utf8_decode($field['estado_legal']), 0, 'L');
			
			$pdf->SetXY(15, 100); $pdf->MultiCell(8, 5, '10.', 0, 'L'); 
			$pdf->SetXY(23, 100); $pdf->MultiCell(170, 5, 'Valor con que figura en la Contabilidad:', 0, 'L');
			$pdf->SetXY(23, 106); $pdf->MultiCell(170, 5, 'Fecha: ____________________', 0, 'L');
			$pdf->SetXY(23, 112); $pdf->MultiCell(170, 5, utf8_decode('Más mejoras adicionales'), 0, 'L');
			$pdf->SetXY(23, 118); $pdf->MultiCell(170, 5, 'Fecha: ____________________', 0, 'L');
			$pdf->SetXY(23, 124); $pdf->MultiCell(170, 5, '            ____________________', 0, 'L');	
			$pdf->SetXY(23, 130); $pdf->MultiCell(170, 5, '            ____________________', 0, 'L');	
			$pdf->SetXY(23, 136); $pdf->MultiCell(170, 5, '            ____________________', 0, 'L');	
			$pdf->SetXY(23, 142); $pdf->MultiCell(170, 5, '            ____________________', 0, 'L');	
				
			$pdf->SetXY(122, 106); $pdf->MultiCell(70, 5, utf8_decode('Valor de adquisición Bs. __________'), 0, 'R');
			$pdf->SetXY(122, 118); $pdf->MultiCell(70, 5, 'Bs. __________', 0, 'R');
			$pdf->SetXY(122, 124); $pdf->MultiCell(70, 5, 'Bs. __________', 0, 'R');
			$pdf->SetXY(122, 130); $pdf->MultiCell(70, 5, 'Bs. __________', 0, 'R');
			$pdf->SetXY(122, 136); $pdf->MultiCell(70, 5, 'Bs. __________', 0, 'R');
			$pdf->SetXY(122, 142); $pdf->MultiCell(70, 5, 'Bs. __________', 0, 'R');
			$pdf->SetXY(122, 148); $pdf->MultiCell(70, 5, 'Total ..... Bs. __________', 0, 'R');
			
			list($a, $m, $d) = split('[-./]', $field['valor_contabilidad_fecha']); $contabilidad_fecha = "$d-$m-$a";
			list($a, $m, $d) = split('[-./]', $field['mejoras_fecha']); $mejoras_fecha1 = "$d-$m-$a";
			list($a, $m, $d) = split('[-./]', $field['mejoras_fecha2']); $mejoras_fecha2 = "$d-$m-$a";
			list($a, $m, $d) = split('[-./]', $field['mejoras_fecha3']); $mejoras_fecha3 = "$d-$m-$a";
			list($a, $m, $d) = split('[-./]', $field['mejoras_fecha4']); $mejoras_fecha4 = "$d-$m-$a";
			list($a, $m, $d) = split('[-./]', $field['mejoras_fecha5']); $mejoras_fecha5 = "$d-$m-$a";
			$contabilidad_monto = number_format($field['valor_contabilidad_monto'], 2, ',', '.');
			$mejoras_valor1 = number_format($field['mejoras_valor'], 2, ',', '.');
			$mejoras_valor2 = number_format($field['mejoras_valor2'], 2, ',', '.');
			$mejoras_valor3 = number_format($field['mejoras_valor3'], 2, ',', '.');
			$mejoras_valor4 = number_format($field['mejoras_valor4'], 2, ',', '.');
			$mejoras_valor5 = number_format($field['mejoras_valor5'], 2, ',', '.');
			
			$pdf->SetXY(38, 106); $pdf->MultiCell(170, 5, $contabilidad_fecha, 0, 'L');
			$pdf->SetXY(38, 118); $pdf->MultiCell(170, 5, $mejoras_fecha1, 0, 'L');
			$pdf->SetXY(38, 124); $pdf->MultiCell(170, 5, $mejoras_fecha2, 0, 'L');
			$pdf->SetXY(38, 130); $pdf->MultiCell(170, 5, $mejoras_fecha3, 0, 'L');
			$pdf->SetXY(38, 136); $pdf->MultiCell(170, 5, $mejoras_fecha4, 0, 'L');
			$pdf->SetXY(38, 142); $pdf->MultiCell(170, 5, $mejoras_fecha5, 0, 'L');
			
			$pdf->SetXY(122, 106); $pdf->MultiCell(70, 5, $mejoras_valor1, 0, 'R');
			$pdf->SetXY(122, 118); $pdf->MultiCell(70, 5, $mejoras_valor1, 0, 'R');
			$pdf->SetXY(122, 124); $pdf->MultiCell(70, 5, $mejoras_valor2, 0, 'R');
			$pdf->SetXY(122, 130); $pdf->MultiCell(70, 5, $mejoras_valor3, 0, 'R');
			$pdf->SetXY(122, 136); $pdf->MultiCell(70, 5, $mejoras_valor4, 0, 'R');
			$pdf->SetXY(122, 142); $pdf->MultiCell(70, 5, $mejoras_valor5, 0, 'R');
			
			
			$pdf->SetXY(15, 160); $pdf->MultiCell(8, 5, '11.', 0, 'L'); 
			$pdf->SetXY(23, 160); $pdf->MultiCell(170, 5, utf8_decode('Avalúo Provisional de la Comisión: (Para la construcción y el área de terreno ocupada por la misma)'), 0, 'L');
			$pdf->SetXY(23, 167); $pdf->Cell(182, 10, '_____________________________________________________________________________________', 0, 0, 'L');
			$pdf->SetXY(23, 177); $pdf->Cell(182, 10, '_____________________________________________________________________________________', 0, 0, 'L');
			$pdf->SetXY(23, 187); $pdf->Cell(182, 10, '_____________________________________________________________________________________', 0, 0, 'L');
			$pdf->SetXY(23, 160); $pdf->MultiCell(170, 10, utf8_decode($field['avaluo_provicional']), 0, 'L');
			
			$pdf->SetXY(15, 205); $pdf->MultiCell(8, 5, '12.', 0, 'L'); 
			$pdf->SetXY(23, 205); $pdf->MultiCell(170, 5, utf8_decode('Planos, esquemas y fotografías: (Los que se acompañen, con mención de la oficina en donde se encuentran los restantes)'), 0, 'L');
			$pdf->SetXY(23, 212); $pdf->Cell(182, 10, '_____________________________________________________________________________________', 0, 0, 'L');
			$pdf->SetXY(23, 222); $pdf->Cell(182, 10, '_____________________________________________________________________________________', 0, 0, 'L');
			$pdf->SetXY(23, 232); $pdf->Cell(182, 10, '_____________________________________________________________________________________', 0, 0, 'L');
			$pdf->SetXY(23, 242); $pdf->Cell(182, 10, '_____________________________________________________________________________________', 0, 0, 'L');
			$pdf->SetXY(23, 212); $pdf->MultiCell(170, 10, utf8_decode($field['planos_esquemas_fotocopias']), 0, 'L');
			
			list($a, $m, $d) = split('[-./]', $field['fecha']); $fecha = "$d-$m-$a";
			$lugar_y_fecha = utf8_decode($field['lugar']).' '.$fecha;
			
			$pdf->SetXY(90, 270); $pdf->MultiCell(100, 5, 'Preparado por: ______________________________', 0, 'R'); 
			$pdf->SetXY(145, 275); $pdf->MultiCell(50, 5, 'Nombre y Firma', 0, 'L'); 
			$pdf->SetXY(90, 285); $pdf->MultiCell(100, 5, 'Lugar y Fecha: ______________________________', 0, 'R'); 
			$pdf->SetXY(135, 269); $pdf->MultiCell(100, 5, utf8_decode($field['preparado_por']), 0, 'L');
			$pdf->SetXY(135, 284); $pdf->MultiCell(100, 5, $lugar_y_fecha, 0, 'L');
		}
		break;
	
	//	Hoja de Trabajo 2...
	case "inmuebles_hoja2":
		$pdf = new PDF_MC_Table4('P', 'mm', 'A4');
		$pdf->SetTopMargin(5);
		$pdf->SetLeftMargin(15);
		$pdf->SetAutoPageBreak(1, 2.5);
		$hoja = 1;
		//	--------------------------------------
		//	Imprimo edificios...
		//	--------------------------------------
		$sql = "SELECT 
						e.*,
						tmb.denominacion AS tipo_movimiento,
						dcb.codigo AS codigo_catalogo, 
						dcb.denominacion AS nombre_catalogo, 
						edo.denominacion AS estado
				FROM 
						terrenos e
						INNER JOIN tipo_movimiento_bienes tmb ON (e.idtipo_movimiento = tmb.idtipo_movimiento_bienes)
						INNER JOIN detalle_catalogo_bienes dcb ON (e.iddetalle_catalogo_bienes = dcb.iddetalle_catalogo_bienes)
						INNER JOIN estado edo ON (e.estado_municipio = edo.idestado)
				WHERE 
						e.codigo_bien = '".$codigo."'
				ORDER BY e.idterrenos";
		$query = mysql_query($sql) or die ($sql.mysql_error());	
		while ($field = mysql_fetch_array($query)) {
			//	Añado una nueva hoja e imprimo la cabecera...
			inmuebles_hoja($pdf);		
			$pdf->SetFont('Arial', 'BU', 10);
			$pdf->Cell(110, 10, utf8_decode('HOJA DE TRABAJO Nº 2'), 0, 0, 'L');
			$pdf->Cell(80, 10, 'PARA DESCRIPCION DE TERRENOS', 0, 1, 'R');
			$pdf->Ln(5);
			
			//	Imprimo el cuerpo de la hoja....
			$pdf->SetFont('Arial', '', 10);
			$pdf->Cell(190, 5, utf8_decode('Del expediente Nº ________________  (Utilícese un formulario para cada terreno)'), 0, 1, 'L');		
			$pdf->Cell(190, 5, 'Tipo de Movimiento: '.utf8_decode($field['tipo_movimiento']), 0, 1, 'L');
			$pdf->Cell(190, 5, utf8_decode('Código de Catálogo:'.$field['codigo_catalogo'].' - '.$field['nombre_catalogo']), 0, 1, 'L');
			$pdf->Ln(3);
			
			//	Imprimo 1.
			$pdf->SetXY(15, 50); $pdf->MultiCell(8, 10, '1.', 0, 'L');
			$pdf->SetXY(22, 50); $pdf->MultiCell(182, 10, 'Estado (o Municipio): __________________________________________________________________________', 0, 'L');
			$pdf->SetXY(60, 50); $pdf->MultiCell(140, 10, utf8_decode($field['estado']), 0, 'L');
			
			//	Imprimo 2.
			$pdf->SetXY(15, 60); $pdf->MultiCell(8, 10, '2.', 0, 'L');
			$pdf->SetXY(22, 60); $pdf->MultiCell(182, 10, utf8_decode('Denominación del Inmueble: ____________________________________________________________________ ___________________________________________________________________________________________'), 0, 'L');
			$pdf->SetXY(70, 60); $pdf->Cell(140, 10, utf8_decode($field['denominacion_inmueble']), 0, 'L');
			
			//	Imprimo 3.
			$pdf->SetXY(15, 85); $pdf->MultiCell(8, 10, '3.', 0, 'L');
			$pdf->SetXY(22, 85); $pdf->MultiCell(182, 10, utf8_decode('Clasificación Funcional del Inmueble:'), 0, 'L');
			$pdf->SetXY(22, 95); $pdf->MultiCell(182, 10, 'Agricultura: ________', 0, 'L');
			$pdf->SetXY(90, 95); $pdf->MultiCell(182, 10, 'Ganadería: ________', 0, 'L');
			$pdf->SetXY(154, 95); $pdf->MultiCell(182, 10, 'Mixto agropecuario: ________', 0, 'L');
			$pdf->SetXY(22, 105); $pdf->MultiCell(182, 10, 'Otros usos: __________________________________________________________________________________', 0, 'L');		
			if ($field['clasificacion_agricultura'] == "no") $agricultura = "X";
			if ($field['clasificacion_ganaderia'] == "no") $ganaderia = "X";
			if ($field['clasificacion_mixto_agropecuario'] == "no") $mixto = "X";
			$pdf->SetXY(47, 95); $pdf->MultiCell(140, 10, $agricultura, 0, 'L');
			$pdf->SetXY(115, 95); $pdf->MultiCell(140, 10, $ganaderia, 0, 'L');
			$pdf->SetXY(192, 95); $pdf->MultiCell(140, 10, $mixto, 0, 'L');
			$pdf->SetXY(45, 105); $pdf->MultiCell(140, 10, utf8_decode($field['clasificacion_otros']), 0, 'L');
			
			//	Imprimo 4.
			$pdf->SetXY(15, 120); $pdf->MultiCell(8, 10, '4.', 0, 'L');
			$pdf->SetXY(22, 120); $pdf->MultiCell(182, 10, utf8_decode('Ubicación geográfica: Estado o Territorio: __________________________________________________________'), 0, 'L');		
			$pdf->SetXY(22, 130); $pdf->MultiCell(182, 10, utf8_decode('Municipio: _________________________________    Lugar y Dirección: _________________________________'), 0, 'L');
			$pdf->SetXY(22, 140); $pdf->MultiCell(182, 10, '___________________________________________________________________________________________', 0, 'L');
			$pdf->SetXY(100, 120); $pdf->MultiCell(140, 10, utf8_decode($field['ubicacion_territorio']), 0, 'L');
			$pdf->SetXY(100, 130); $pdf->MultiCell(140, 10, utf8_decode($field['ubicacion_municipio']), 0, 'L');
			//	Falta lugar y direccion....
			
			//	Imprimo 5.
			$pdf->SetXY(15, 155); $pdf->MultiCell(8, 10, '5.', 0, 'L');
			$pdf->SetXY(22, 155); $pdf->MultiCell(182, 10, utf8_decode('Area Total del Terreno: Hectáreas: _____________________________  m2  _____________________________'), 0, 'L');
			$pdf->SetXY(80, 155); $pdf->MultiCell(140, 10, utf8_decode($field['area_total_terreno_hectarias']), 0, 'L');
			$pdf->SetXY(145, 155); $pdf->MultiCell(140, 10, utf8_decode($field['area_total_terreno_metros']), 0, 'L');
			
			//	Imprimo 6.
			$pdf->SetXY(15, 170); $pdf->MultiCell(8, 7, '6.', 0, 'L');
			$pdf->SetXY(22, 170); $pdf->MultiCell(182, 7, utf8_decode('Area de las construcciones:  m2  _____________________________ (Para la descripción individual de las construcciones utilícese la HOJA DE TRABAJO Nº 1)'), 0, 'L');
			$pdf->SetXY(80, 170); $pdf->MultiCell(140, 7, utf8_decode($field['area_construccion_metros']), 0, 'L');
			
			//	Imprimo 7.
			$pdf->SetXY(15, 190); $pdf->MultiCell(8, 7, '7.', 0, 'L');
			$pdf->SetXY(22, 190); $pdf->MultiCell(182, 7, utf8_decode('Descripción del terreno:'), 0, 'L');
			$pdf->SetXY(22, 200);
			$pdf->Cell(40, 7, utf8_decode('a)  Topografía:'), 0, 0, 'L');
			$pdf->Cell(28, 7, 'Plana', 1, 0, 'C');
			$pdf->Cell(28, 7, 'Semi - plana', 1, 0, 'C');
			$pdf->Cell(28, 7, 'Pendiente', 1, 0, 'C');
			$pdf->Cell(28, 7, 'Muy pendiente', 1, 0, 'C');
			$pdf->Cell(28, 7, 'Totales', 1, 0, 'C');
			$pdf->SetXY(22, 207);
			$pdf->Cell(40, 7, utf8_decode('      Hectáreas:'), 0, 0, 'L');
			$pdf->Cell(28, 7, '', 1, 0, 'C');
			$pdf->Cell(28, 7, '', 1, 0, 'C');
			$pdf->Cell(28, 7, '', 1, 0, 'C');
			$pdf->Cell(28, 7, '', 1, 0, 'C');
			$pdf->Cell(28, 7, '', 1, 0, 'C');
			$pdf->SetXY(22, 214);
			$pdf->Cell(40, 7, '      %', 0, 0, 'L');
			$pdf->Cell(28, 7, '', 1, 0, 'C');
			$pdf->Cell(28, 7, '', 1, 0, 'C');
			$pdf->Cell(28, 7, '', 1, 0, 'C');
			$pdf->Cell(28, 7, '', 1, 0, 'C');
			$pdf->Cell(28, 7, '', 1, 0, 'C');
			$pdf->SetXY(62, 207); 
			$pdf->Cell(28, 7, number_format($field['tipografia_plana'], 2, ',', '.'), 0, 0, 'R'); 
			$pdf->Cell(28, 7, number_format($field['tipografia_semiplana'], 2, ',', '.'), 0, 0, 'R'); 
			$pdf->Cell(28, 7, number_format($field['tipografia_pendiente'], 2, ',', '.'), 0, 0, 'R'); 
			$pdf->Cell(28, 7, number_format($field['tipografia_muypendiente'], 2, ',', '.'), 0, 0, 'R');
			$tipografia_total = $field['tipografia_plana'] + $field['tipografia_semiplana'] + $field['tipografia_pendiente'] + $field['tipografia_muypendiente'];
			$pdf->Cell(28, 7, number_format($tipografia_total, 2, ',', '.'), 0, 0, 'R');
			if ($tipografia_total != 0) {
				$ptipografia_plana = $field['tipografia_plana'] * 100 / $tipografia_total;
				$ptipografia_semiplana = $field['tipografia_semiplana'] * 100 / $tipografia_total;
				$ptipografia_pendiente = $field['tipografia_pendiente'] * 100 / $tipografia_total;
				$ptipografia_muypendiente = $field['tipografia_muypendiente'] * 100 / $tipografia_total;
				$ptotal = $ptipografia_plana + $ptipografia_semiplana + $ptipografia_pendiente + $ptipografia_muypendiente;
			}
			$pdf->SetXY(62, 214); 
			$pdf->Cell(28, 7, number_format($ptipografia_plana, 2, ',', '.'), 0, 0, 'R'); 
			$pdf->Cell(28, 7, number_format($ptipografia_semiplana, 2, ',', '.'), 0, 0, 'R'); 
			$pdf->Cell(28, 7, number_format($ptipografia_pendiente, 2, ',', '.'), 0, 0, 'R'); 
			$pdf->Cell(28, 7, number_format($ptipografia_muypendiente, 2, ',', '.'), 0, 0, 'R');
			$pdf->Cell(28, 7, number_format($ptotal, 2, ',', '.'), 0, 0, 'R');
			
			$pdf->SetXY(22, 225);
			$pdf->Cell(40, 7, utf8_decode('b)  Cultivos Agrícolas:'), 0, 0, 'L');
			$pdf->Cell(80, 7, 'Permanentes: frutales y maderables', 1, 0, 'C');
			$pdf->Cell(60, 7, 'Area deforestada', 1, 0, 'C');
			$pdf->SetXY(22, 232);
			$pdf->Cell(40, 7, utf8_decode('      Hectáreas:'), 0, 0, 'L');
			$pdf->Cell(80, 7, '', 1, 0, 'C');
			$pdf->Cell(60, 7, '', 1, 0, 'C');
			$pdf->SetXY(22, 239);
			$pdf->Cell(40, 7, '      %', 0, 0, 'L');
			$pdf->Cell(80, 7, '', 1, 0, 'C');
			$pdf->Cell(60, 7, '', 1, 0, 'C');
			$pdf->SetXY(62, 232); 
			$pdf->Cell(80, 7, number_format($field['cultivos_permanentes'], 2, ',', '.'), 0, 0, 'R');
			$pdf->Cell(60, 7, number_format($field['cultivos_deforestados'], 2, ',', '.'), 0, 0, 'R');
			$cultivos_total = $field['cultivos_permanentes'] + $field['cultivos_deforestados'];
			if ($cultivos_total != 0) {
				$pcultivos_permanentes = $field['cultivos_permanentes'] * 100 / $cultivos_total;
				$pcultivos_deforestados = $field['cultivos_deforestados'] * 100 / $cultivos_total;
			}
			$pdf->SetXY(62, 239); 
			$pdf->Cell(80, 7, number_format($pcultivos_permanentes, 2, ',', '.'), 0, 0, 'R'); 
			$pdf->Cell(60, 7, number_format($pcultivos_deforestados, 2, ',', '.'), 0, 0, 'R'); 
			
			$pdf->SetXY(22, 250);
			$pdf->Cell(40, 7, 'c)  Otros Terrenos:', 0, 0, 'L');
			$pdf->Cell(46, 7, 'Bosques', 1, 0, 'C');
			$pdf->Cell(47, 7, 'Tierras incultas', 1, 0, 'C');
			$pdf->Cell(47, 7, 'No aprovechables', 1, 0, 'C');
			$pdf->SetXY(22, 257);
			$pdf->Cell(40, 7, utf8_decode('      Hectáreas:'), 0, 0, 'L');
			$pdf->Cell(46, 7, '', 1, 0, 'C');
			$pdf->Cell(47, 7, '', 1, 0, 'C');
			$pdf->Cell(47, 7, '', 1, 0, 'C');
			$pdf->SetXY(22, 264);
			$pdf->Cell(40, 7, '      %', 0, 0, 'L');
			$pdf->Cell(46, 7, '', 1, 0, 'C');
			$pdf->Cell(47, 7, '', 1, 0, 'C');
			$pdf->Cell(47, 7, '', 1, 0, 'C');
			$pdf->SetXY(62, 257); 
			$pdf->Cell(46, 7, number_format($field['otros_bosques'], 2, ',', '.'), 0, 0, 'R');
			$pdf->Cell(47, 7, number_format($field['otros_tierras_incultas'], 2, ',', '.'), 0, 0, 'R');
			$pdf->Cell(47, 7, number_format($field['otros_noaprovechables'], 2, ',', '.'), 0, 0, 'R');
			$otros_total = $field['otros_bosques'] + $field['cultivos_deforestados'];
			if ($otros_total != 0) {
				$potros_bosques = $field['otros_bosques'] * 100 / $otros_total;
				$potros_tierras_incultas = $field['otros_tierras_incultas'] * 100 / $otros_total;
				$potros_noaprovechables = $field['otros_noaprovechables'] * 100 / $otros_total;
			}
			$pdf->SetXY(62, 264); 
			$pdf->Cell(46, 7, number_format($potros_bosques, 2, ',', '.'), 0, 0, 'R'); 
			$pdf->Cell(47, 7, number_format($potros_tierras_incultas, 2, ',', '.'), 0, 0, 'R'); 
			$pdf->Cell(47, 7, number_format($potros_noaprovechables, 2, ',', '.'), 0, 0, 'R'); 
			
			//	Añado una nueva hoja...
			$pdf->AddPage();
			
			//	Imprimo 7 (Continuación).
			$pdf->SetXY(22, 15);
			$pdf->Cell(40, 7, 'd)  Potreros:', 0, 0, 'L');
			$pdf->Cell(46, 7, 'Naturales', 1, 0, 'C');
			$pdf->Cell(47, 7, 'Cultivados', 1, 0, 'C');
			$pdf->Cell(47, 7, 'Total', 1, 0, 'C');
			$pdf->SetXY(22, 22);
			$pdf->Cell(40, 7, utf8_decode('      Hectáreas:'), 0, 0, 'L');
			$pdf->Cell(46, 7, '', 1, 0, 'C');
			$pdf->Cell(47, 7, '', 1, 0, 'C');
			$pdf->Cell(47, 7, '', 1, 0, 'C');
			$pdf->SetXY(22, 29);
			$pdf->Cell(40, 7, '      %', 0, 0, 'L');
			$pdf->Cell(46, 7, '', 1, 0, 'C');
			$pdf->Cell(47, 7, '', 1, 0, 'C');
			$pdf->Cell(47, 7, '', 1, 0, 'C');
			$pdf->SetXY(62, 22); 
			$pdf->Cell(46, 7, number_format($field['potreros_naturales'], 2, ',', '.'), 0, 0, 'R');
			$pdf->Cell(47, 7, number_format($field['potreros_cultivados'], 2, ',', '.'), 0, 0, 'R');
			$potreros_total = $field['potreros_naturales'] + $field['potreros_cultivados'];
			$pdf->Cell(47, 7, number_format($potreros_total, 2, ',', '.'), 0, 0, 'R');
			if ($potreros_total != 0) {
				$ppotreros_naturales = $field['potreros_naturales'] * 100 / $potreros_total;
				$ppotreros_cultivados = $field['potreros_cultivados'] * 100 / $potreros_total;
			}
			$pdf->SetXY(62, 29); 
			$pdf->Cell(46, 7, number_format($ppotreros_naturales, 2, ',', '.'), 0, 0, 'R'); 
			$pdf->Cell(47, 7, number_format($ppotreros_cultivados, 2, ',', '.'), 0, 0, 'R'); 
			$pdf->Cell(47, 7, number_format($potreros_total, 2, ',', '.'), 0, 0, 'R'); 
			
			$pdf->SetXY(22, 45); $pdf->Cell(168, 10, 'e)  Recursos de agua (naturales y artificiales):', 0, 0, 'L');
			$pdf->SetXY(27, 55); $pdf->Cell(163, 10, 'Cursos de agua:  (rios, quebradas):  ___________________________________________________________', 0, 0, 'L');
			$pdf->SetXY(87, 55); $pdf->Cell(163, 10, utf8_decode($field['recursos_cursos']), 0, 0, 'L');
			$pdf->SetXY(27, 65); $pdf->Cell(163, 10, 'Manantiales:  _____________________________________________________________________________', 0, 0, 'L');
			$pdf->SetXY(57, 65); $pdf->Cell(163, 10, utf8_decode($field['recursos_manantiales']), 0, 0, 'L');
			$pdf->SetXY(27, 75); $pdf->Cell(163, 10, 'Canales y acequias:  _______________________________________________________________________', 0, 0, 'L');
			$pdf->SetXY(65, 75); $pdf->Cell(163, 10, utf8_decode($field['recursos_canales']), 0, 0, 'L');
			$pdf->SetXY(27, 85); $pdf->Cell(163, 10, 'Embalses y lagunas:  _______________________________________________________________________', 0, 0, 'L');
			$pdf->SetXY(65, 85); $pdf->Cell(163, 10, utf8_decode($field['recursos_embalses']), 0, 0, 'L');
			$pdf->SetXY(27, 95); $pdf->Cell(163, 10, 'Pozos y aljibes:  ___________________________________________________________________________', 0, 0, 'L');
			$pdf->SetXY(57, 95); $pdf->Cell(163, 10, utf8_decode($field['recursos_pozos']), 0, 0, 'L');
			$pdf->SetXY(27, 105); $pdf->Cell(163, 10, 'Acueductos:  _____________________________________________________________________________', 0, 0, 'L');
			$pdf->SetXY(52, 105); $pdf->Cell(163, 10, utf8_decode($field['recursos_acuaductos']), 0, 0, 'L');
			$pdf->SetXY(27, 115); $pdf->MultiCell(180, 10, 'Otros recursos de agua:  ____________________________________________________________________ _________________________________________________________________________________________', 0, 'L');
			$pdf->SetXY(67, 115); $pdf->Cell(163, 10, substr(utf8_decode($field['recursos_otros']), 0, 75), 0, 0, 'L');
			$pdf->SetXY(27, 125); $pdf->Cell(163, 10, substr(utf8_decode($field['recursos_otros']), 75, 100), 0, 0, 'L');
			
			$pdf->SetXY(22, 140);
			$pdf->Cell(25, 7, 'f)  Cercas:', 0, 0, 'L');
			$pdf->Cell(90, 7, 'Longitud:  ____________________________', 0, 0, 'L');
			$pdf->Cell(90, 7, 'Estantes de  ______________________', 0, 0, 'L');
			$pdf->SetXY(47, 146); $pdf->Cell(90, 7, 'Material:  ____________________________', 0, 0, 'L');
			$pdf->SetXY(158, 146); $pdf->Cell(90, 7, '______________________', 0, 0, 'L');
			$pdf->SetXY(65, 140); $pdf->Cell(90, 7, utf8_decode($field['cercas_longitud']), 0, 0, 'L');
			$pdf->SetXY(160, 140); $pdf->Cell(90, 7, utf8_decode($field['cercas_estantes']), 0, 0, 'L');
			$pdf->SetXY(65, 146); $pdf->Cell(90, 7, utf8_decode($field['cercas_material']), 0, 0, 'L');
			
			$pdf->SetXY(22, 160);
			$pdf->MultiCell(190, 8, 'g)  Vias interiores:   Longitud y especificaciones:   ___________________________________________________ ___________________________________________________________________________________________ ___________________________________________________________________________________________ ___________________________________________________________________________________________', 0, 'L');
			$pdf->SetXY(100, 160); $pdf->Cell(105, 8, substr(utf8_decode($field['vias_interiores']), 0, 45), 0, 0, 'L');
			$pdf->SetXY(22, 168); $pdf->MultiCell(180, 8, substr(utf8_decode($field['vias_interiores']), 45, 240), 0, 'L');
			
			$pdf->SetXY(22, 195);
			$pdf->MultiCell(180, 8, utf8_decode('h)  Otras bienhechurías:  (En resumen.  El detalle de los edificios se anotará en la HOJA DE TRABAJO Nº 1, y el de las instalaciones fijas en la HOJA DE TRABAJO Nº 3)  ____________________________________________ __________________________________________________________________________________________ __________________________________________________________________________________________ __________________________________________________________________________________________ __________________________________________________________________________________________'), 0, 'L');
			$pdf->SetXY(115, 203); $pdf->Cell(105, 8, substr(utf8_decode($field['otras_bienhechurias']), 0, 40), 0, 0, 'L');
			$pdf->SetXY(22, 211); $pdf->MultiCell(180, 8, substr(utf8_decode($field['otras_bienhechurias']), 40, 260), 0, 'L');
			
			//	Imprimo 8.
			$pdf->SetXY(15, 250); $pdf->MultiCell(8, 8, '8.', 0, 'L');
			$pdf->SetXY(22, 250); $pdf->MultiCell(182, 8, 'Linderos:   __________________________________________________________________________________ ___________________________________________________________________________________________ ___________________________________________________________________________________________', 0, 'L');
			$pdf->SetXY(40, 250); $pdf->Cell(105, 8, substr(utf8_decode($field['linceros']), 0, 75), 0, 0, 'L');
			$pdf->SetXY(22, 258); $pdf->MultiCell(180, 8, substr(utf8_decode($field['linceros']), 75, 160), 0, 'L');
			
			$pdf->SetXY(180, 280); $pdf->MultiCell(20, 10, 'continua...', 0, 'L');
			
			
			//	Añado una nueva hoja...
			$pdf->AddPage();
			
			$pdf->SetXY(160, 7); $pdf->MultiCell(50, 10, utf8_decode('(continuación linderos)'), 0, 'L');
			
			//	Imprimo 8 (Continuacion).
			$pdf->SetXY(22, 20); $pdf->MultiCell(182, 8, '___________________________________________________________________________________________ ___________________________________________________________________________________________ ___________________________________________________________________________________________', 0, 'L');
			$pdf->SetXY(22, 20); $pdf->MultiCell(180, 8, substr(utf8_decode($field['linceros']), 235, 240), 0, 'L');
			
			
			//	Imprimo 9.
			$pdf->SetXY(15, 50); $pdf->MultiCell(8, 8, '9.', 0, 'L');
			$pdf->SetXY(22, 50); $pdf->MultiCell(182, 6, 'Estudio Legal de la Propiedad:  (Será llenado por el Procurador del Estado o por el Síndico - Procurador Municipal, según el caso.)', 0, 'L');
			$pdf->SetXY(22, 65); $pdf->MultiCell(182, 8, '___________________________________________________________________________________________ ___________________________________________________________________________________________ ___________________________________________________________________________________________ ___________________________________________________________________________________________', 0, 'L');
			$pdf->SetXY(22, 65); $pdf->MultiCell(180, 8, substr(utf8_decode($field['estudio_legal']), 0, 325), 0, 'L');
			
					
			//	Imprimo 10.
			$pdf->SetXY(15, 105); $pdf->MultiCell(8, 5, '10.', 0, 'L'); 
			$pdf->SetXY(22, 105); $pdf->MultiCell(170, 5, 'Valor con que figura en la Contabilidad:', 0, 'L');
			$pdf->SetXY(22, 111); $pdf->MultiCell(170, 5, 'Fecha: ____________________', 0, 'L');
			$pdf->SetXY(22, 117); $pdf->MultiCell(170, 5, utf8_decode('Más mejoras adicionales'), 0, 'L');
			$pdf->SetXY(22, 123); $pdf->MultiCell(170, 5, 'Fecha: ____________________', 0, 'L');
			$pdf->SetXY(22, 129); $pdf->MultiCell(170, 5, '            ____________________', 0, 'L');	
			$pdf->SetXY(22, 135); $pdf->MultiCell(170, 5, '            ____________________', 0, 'L');	
			$pdf->SetXY(22, 141); $pdf->MultiCell(170, 5, '            ____________________', 0, 'L');	
			$pdf->SetXY(22, 147); $pdf->MultiCell(170, 5, '            ____________________', 0, 'L');	
				
			$pdf->SetXY(122, 111); $pdf->MultiCell(70, 5, utf8_decode('Valor de adquisición Bs. __________'), 0, 'R');
			$pdf->SetXY(122, 123); $pdf->MultiCell(70, 5, 'Bs. __________', 0, 'R');
			$pdf->SetXY(122, 129); $pdf->MultiCell(70, 5, 'Bs. __________', 0, 'R');
			$pdf->SetXY(122, 135); $pdf->MultiCell(70, 5, 'Bs. __________', 0, 'R');
			$pdf->SetXY(122, 141); $pdf->MultiCell(70, 5, 'Bs. __________', 0, 'R');
			$pdf->SetXY(122, 147); $pdf->MultiCell(70, 5, 'Bs. __________', 0, 'R');
			$pdf->SetXY(122, 153); $pdf->MultiCell(70, 5, 'Total ..... Bs. __________', 0, 'R');
			
			list($a, $m, $d) = split('[-./]', $field['contabilidad_fecha']); $contabilidad_fecha = "$d-$m-$a";
			list($a, $m, $d) = split('[-./]', $field['adicionales_fecha']); $mejoras_fecha1 = "$d-$m-$a";
			list($a, $m, $d) = split('[-./]', $field['adicionales_fecha2']); $mejoras_fecha2 = "$d-$m-$a";
			list($a, $m, $d) = split('[-./]', $field['adicionales_fecha3']); $mejoras_fecha3 = "$d-$m-$a";
			list($a, $m, $d) = split('[-./]', $field['adicionales_fecha4']); $mejoras_fecha4 = "$d-$m-$a";
			list($a, $m, $d) = split('[-./]', $field['adicionales_fecha5']); $mejoras_fecha5 = "$d-$m-$a";
			$contabilidad_monto = number_format($field['contabilidad_valor'], 2, ',', '.');
			$mejoras_valor1 = number_format($field['adicionales_valor'], 2, ',', '.');
			$mejoras_valor2 = number_format($field['adicionales_valor2'], 2, ',', '.');
			$mejoras_valor3 = number_format($field['adicionales_valor3'], 2, ',', '.');
			$mejoras_valor4 = number_format($field['adicionales_valor4'], 2, ',', '.');
			$mejoras_valor5 = number_format($field['adicionales_valor5'], 2, ',', '.');
			
			$pdf->SetXY(38, 111); $pdf->MultiCell(170, 5, $contabilidad_fecha, 0, 'L');
			$pdf->SetXY(38, 123); $pdf->MultiCell(170, 5, $mejoras_fecha1, 0, 'L');
			$pdf->SetXY(38, 129); $pdf->MultiCell(170, 5, $mejoras_fecha2, 0, 'L');
			$pdf->SetXY(38, 135); $pdf->MultiCell(170, 5, $mejoras_fecha3, 0, 'L');
			$pdf->SetXY(38, 141); $pdf->MultiCell(170, 5, $mejoras_fecha4, 0, 'L');
			$pdf->SetXY(38, 147); $pdf->MultiCell(170, 5, $mejoras_fecha5, 0, 'L');
			
			$pdf->SetXY(122, 111); $pdf->MultiCell(70, 5, $mejoras_valor1, 0, 'R');
			$pdf->SetXY(122, 123); $pdf->MultiCell(70, 5, $mejoras_valor1, 0, 'R');
			$pdf->SetXY(122, 129); $pdf->MultiCell(70, 5, $mejoras_valor2, 0, 'R');
			$pdf->SetXY(122, 135); $pdf->MultiCell(70, 5, $mejoras_valor3, 0, 'R');
			$pdf->SetXY(122, 141); $pdf->MultiCell(70, 5, $mejoras_valor4, 0, 'R');
			$pdf->SetXY(122, 147); $pdf->MultiCell(70, 5, $mejoras_valor5, 0, 'R');
			
			
			//	Imprimo 11.
			$pdf->SetXY(15, 165); $pdf->MultiCell(8, 5, '11.', 0, 'L'); 
			$pdf->SetXY(22, 165); $pdf->MultiCell(170, 5, utf8_decode('Avalúo de la Comisión (Para los terrenos solamente):'), 0, 'L');
			$pdf->SetXY(22, 175); $pdf->Cell(180, 5, utf8_decode('____________  Hectáreas a Bs. c/u.  __________________________   Bs.   __________________________'), 0, 0, 'L');
			$pdf->SetXY(22, 182); $pdf->Cell(180, 5, utf8_decode('____________  Hectáreas a Bs. c/u.  __________________________   Bs.   __________________________'), 0, 0, 'L');
			$pdf->SetXY(22, 189); $pdf->Cell(180, 5, utf8_decode('____________  Hectáreas a Bs. c/u.  __________________________   Bs.   __________________________'), 0, 0, 'L');
			$pdf->SetXY(22, 175); $pdf->Cell(25, 5, number_format($field['avaluo_hectarias'], 2, ',', '.'), 0, 0, 'R');
			$pdf->SetXY(82, 175); $pdf->Cell(50, 5, number_format($field['avaluo_bs'], 2, ',', '.'), 0, 0, 'R');
			$pdf->SetXY(22, 182); $pdf->Cell(25, 5, number_format($field['avaluo_hectarias2'], 2, ',', '.'), 0, 0, 'R');
			$pdf->SetXY(82, 182); $pdf->Cell(50, 5, number_format($field['avaluo_bs2'], 2, ',', '.'), 0, 0, 'R');
			$pdf->SetXY(22, 189); $pdf->Cell(25, 5, number_format($field['avaluo_hectarias3'], 2, ',', '.'), 0, 0, 'R');
			$pdf->SetXY(82, 189); $pdf->Cell(50, 5, number_format($field['avaluo_bs3'], 2, ',', '.'), 0, 0, 'R');
			
			
			//	Imprimo 12.
			$pdf->SetXY(15, 200); $pdf->MultiCell(8, 8, '12.', 0, 'L');
			$pdf->SetXY(22, 200); $pdf->MultiCell(182, 8, utf8_decode('Planos, Esquemas y Fotografías: (Los que se acompañen, con mensión de la Oficina en donde se encuentren los restantes): __________________________________________________________________________________ ___________________________________________________________________________________________ ___________________________________________________________________________________________ ___________________________________________________________________________________________ ___________________________________________________________________________________________'), 0, 'L');
			$pdf->SetXY(40, 208); $pdf->Cell(180, 8, substr(utf8_decode($field['planos_esquemas_fotografias']), 0, 75), 0, 0, 'L');
			$pdf->SetXY(22, 216); $pdf->MultiCell(180, 8, substr(utf8_decode($field['planos_esquemas_fotografias']), 75, 300), 0, 'L');
			
			
			
			list($a, $m, $d) = split('[-./]', $field['fecha']); $fecha = "$d-$m-$a";
			$lugar_y_fecha = utf8_decode($field['lugar']).' '.$fecha;
			
			$pdf->SetXY(90, 270); $pdf->MultiCell(100, 5, 'Preparado por: ______________________________', 0, 'R'); 
			$pdf->SetXY(145, 275); $pdf->MultiCell(50, 5, 'Nombre y Firma', 0, 'L'); 
			$pdf->SetXY(90, 285); $pdf->MultiCell(100, 5, 'Lugar y Fecha: ______________________________', 0, 'R'); 
			$pdf->SetXY(135, 269); $pdf->MultiCell(100, 5, utf8_decode($field['preparado_por']), 0, 'L');
			$pdf->SetXY(135, 284); $pdf->MultiCell(100, 5, $lugar_y_fecha, 0, 'L');
		}
		break;
	
	//	Hoja de Trabajo 3...
	case "inmuebles_hoja3":
		$pdf = new PDF_MC_Table4('P', 'mm', 'A4');
		$pdf->SetTopMargin(5);
		$pdf->SetLeftMargin(15);
		$pdf->SetAutoPageBreak(1, 2.5);
		$hoja = 1;
		$y = 90;
		//	--------------------------------------
		//	Imprimo edificios...
		//	--------------------------------------
		$sql = "(SELECT
					  i.*,
					  rif.descripcion,
					  rif.valor,
					  rif.fecha,
					  e.denominacion_inmueble
				FROM
					  instalaciones_fijas i
					  INNER JOIN relacion_instalaciones_fijas rif ON (i.idinstalaciones_fijas = rif.idinstalaciones_fijas)
					  INNER JOIN edificios e ON (i.idinmueble = e.idedificios) 
				WHERE
					  i.tipo_inmueble = 'edificio' AND e.codigo_bien = '".$codigo."' 
				ORDER BY i.tipo_inmueble)
				
				UNION
				
				(SELECT
					  i.*,
					  rif.descripcion,
					  rif.valor,
					  rif.fecha,
					  e.denominacion_inmueble
				FROM
					  instalaciones_fijas i
					  INNER JOIN relacion_instalaciones_fijas rif ON (i.idinstalaciones_fijas = rif.idinstalaciones_fijas)
					  INNER JOIN terrenos e ON (i.idinmueble = e.idterrenos) 
				WHERE
					  i.tipo_inmueble = 'terreno' AND e.codigo_bien = '".$codigo."' 
				ORDER BY i.tipo_inmueble)";
		$query = mysql_query($sql) or die ($sql.mysql_error());	
		while ($field = mysql_fetch_array($query)) {
			if ($hoja == 1) {			
				//	Añado una nueva hoja e imprimo la cabecera...
				inmuebles_hoja($pdf);		
				$pdf->SetFont('Arial', 'BU', 10);
				$pdf->Cell(100, 10, utf8_decode('HOJA DE TRABAJO Nº 3'), 0, 0, 'L');
				$pdf->Cell(80, 10, 'PARA DESCRIPCION DE INSTALACIONES FIJAS', 0, 1, 'R');
				$pdf->Ln(5);
				
				//	Imprimo el cuerpo de la hoja....
				$pdf->SetFont('Arial', '', 10);
				$pdf->Cell(180, 5, 'Del expediente Nº ________________  ', 0, 0, 'C'); 
				$pdf->Ln(10);
				
				$pdf->MultiCell(180, 7, utf8_decode('               Deben describirse en forma compendiada las instalaciones de carácter fijo, es deir, adheridas al terreno de la edificaciones, que aumenten el valor de los mismos, en orden de importancia, distintas de los edificios propiamente dichos, para los cuales se utilizará la HOJA DE TRABAJO Nº 1. Así se mencionarán las maquinarias fijas e instalaciones de valor, de uso agropecuario, tales como bañaderos, tanques, estación meteorológica; torres y antenas de telecomunicaciones; equipos portuarios; instalaciones mineras; de fábricas, escuelas insdustriales, laboratorios y de otros servicios públicos, debidamente valorizadas. (Si no fuere suficiente el espacio, se utilizará papel corriente para continuar la descripción, que debe ser breve y concisa.)'), 0, 'J'); 
						
				$pdf->SetXY(15, 90); $pdf->Cell(175, 10, '____________________________________________________________________________________________', 0, 1, 'L');
				$pdf->SetXY(15, 98); $pdf->Cell(175, 10, '____________________________________________________________________________________________', 0, 1, 'L');
				$pdf->SetXY(15, 106); $pdf->Cell(175, 10, '____________________________________________________________________________________________', 0, 1, 'L');
				$pdf->SetXY(15, 114); $pdf->Cell(175, 10, '____________________________________________________________________________________________', 0, 1, 'L');
				$pdf->SetXY(15, 122); $pdf->Cell(175, 10, '____________________________________________________________________________________________', 0, 1, 'L');
				$pdf->SetXY(15, 130); $pdf->Cell(175, 10, '____________________________________________________________________________________________', 0, 1, 'L');
				$pdf->SetXY(15, 138); $pdf->Cell(175, 10, '____________________________________________________________________________________________', 0, 1, 'L');
				$pdf->SetXY(15, 146); $pdf->Cell(175, 10, '____________________________________________________________________________________________', 0, 1, 'L');
				$pdf->SetXY(15, 154); $pdf->Cell(175, 10, '____________________________________________________________________________________________', 0, 1, 'L');
				$pdf->SetXY(15, 162); $pdf->Cell(175, 10, '____________________________________________________________________________________________', 0, 1, 'L');
				$pdf->SetXY(15, 170); $pdf->Cell(175, 10, '____________________________________________________________________________________________', 0, 1, 'L');
				$pdf->SetXY(15, 178); $pdf->Cell(175, 10, '____________________________________________________________________________________________', 0, 1, 'L');
				$pdf->SetXY(15, 186); $pdf->Cell(175, 10, '____________________________________________________________________________________________', 0, 1, 'L');
				$pdf->SetXY(15, 194); $pdf->Cell(175, 10, '____________________________________________________________________________________________', 0, 1, 'L');
				$pdf->SetXY(15, 202); $pdf->Cell(175, 10, '____________________________________________________________________________________________', 0, 1, 'L');
				$pdf->SetXY(15, 210); $pdf->Cell(175, 10, '____________________________________________________________________________________________', 0, 1, 'L');
				$pdf->SetXY(15, 218); $pdf->Cell(175, 10, '____________________________________________________________________________________________', 0, 1, 'L');
				$pdf->SetXY(15, 226); $pdf->Cell(175, 10, '____________________________________________________________________________________________', 0, 1, 'L');
				$pdf->SetXY(15, 234); $pdf->Cell(175, 10, '____________________________________________________________________________________________', 0, 1, 'L');
				$pdf->SetXY(15, 242); $pdf->Cell(175, 10, '____________________________________________________________________________________________', 0, 1, 'L');
				$pdf->SetXY(15, 250); $pdf->Cell(175, 10, '____________________________________________________________________________________________', 0, 1, 'L');
				$pdf->SetXY(15, 258); $pdf->Cell(175, 10, '____________________________________________________________________________________________', 0, 1, 'L');
				$pdf->SetXY(15, 266); $pdf->Cell(175, 10, '____________________________________________________________________________________________', 0, 1, 'L');
				$pdf->SetXY(15, 274); $pdf->Cell(175, 10, '____________________________________________________________________________________________', 0, 1, 'L');
			}
			
			list($a, $m, $d) = split('[/.-]', $field['fecha']); $fecha = "$d/$m/$a";
			$valor = number_format($field['valor'], 2, ',', '.');
			$descripcion = substr(utf8_decode($field['descripcion']), 0, 100);
			
			$pdf->SetXY(15, $y); $pdf->Cell(130, 10, $descripcion, 0, 1, 'L');
			$pdf->SetXY(145, $y); $pdf->Cell(15, 10, $fecha, 0, 1, 'C');
			$pdf->SetXY(160, $y); $pdf->Cell(35, 10, $valor, 0, 1, 'R');
			
			
			$y += 8;
			$hoja++;
		}
		break;
	
	//	Bienes Muebles Inventario...
	case "muebles_inventario":
		$pdf = new PDF_MC_Table5('L', 'mm', 'Letter');
		$pdf->SetTopMargin(5);
		$pdf->SetLeftMargin(5);
		$pdf->SetAutoPageBreak(1, 2.5);
			
		//	Imprimo una nueva página...
		muebles_inventario($pdf, $costo, $ubicacion, $seriales, $especificaciones, $codigo_anterior, $codigo_catalogo, $descripcion_catalogo, $fecha_compra, $valor_actual, $marca, $organizacion, $nivel_organizacion);
		
		$filtro = "";
		//	Verifico el filtro...
		if ($organizacion != 0) $filtro = "WHERE m.idorganizacion = '".$organizacion."'";
		if ($nivel_organizacion != 0) {
			if ($filtro != "") $filtro .= " AND m.idnivel_organizacion = '".$nivel_organizacion."'";
			else $filtro = "WHERE m.idnivel_organizacion = '".$nivel_organizacion."'";
		}
		
		//	Consulto e imprimo el inventario...
		$sql = "SELECT 
						m.codigo_bien, 
						m.especificaciones, 
						m.codigo_anterior_bien,
						m.serial,
						m.marca,
						m.fecha_compra, 
						m.costo, 
						m.depreciacion_acumulada, 
						d.codigo AS codigo_catalogo, 
						d.denominacion AS denominacion_catalogo, 
						u.denominacion AS ubicacion, 
						n.denominacion as nivel_organizacion,
						o.denominacion as organizacion
						
				FROM 
						muebles m 
						INNER JOIN detalle_catalogo_bienes d ON (m.idcatalogo_bienes = d.iddetalle_catalogo_bienes) 
						INNER JOIN ubicacion u ON (m.idubicacion = u.idubicacion) 
						INNER JOIN niveles_organizacionales n ON (m.idnivel_organizacion = n.idniveles_organizacionales)
						INNER JOIN organizacion o ON (m.idorganizacion = o.idorganizacion)
				$filtro
				ORDER BY 
						m.idorganizacion, m.idnivel_organizacion, m.codigo_bien";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		$numero_registros = mysql_num_rows($query);
		$total_costo = 0;
		$total_valor = 0;
		$total_costo_nivel = 0;
		$total_valor_nivel = 0;
		$registros_nivel = 0;
		$idnivel_organizacion = '0';
		$idorganizacion = '0';
		$registros_seleccion = 0;
		$rn=0;
		$registros_seleccion=0;
		$registros_organizacion=0;
		$registros_totales=0;
		// NO SELECCIONO NINGUN NIVEL ORGANIZACIONAL, SE IMPRIMEN TODOS
		if ($nivel_organizacion == 0) {
			
			
			while ($field = mysql_fetch_array($query)) {
				//	SI CAMBIA DE ORGANIZACIONAL LO IMPRIMO
					if ($field["organizacion"]!=$idorganizacion) {
						$pdf->Ln(5);
						$idorganizacion=$field["organizacion"];
						if ($registros_organizacion >= 1){
							//	IMPRIMO LOS TOTALES
							$rn = $registros_nivel-1;
							$y=$pdf->GetY();
							$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
							$h=0.1; $x=5; $w=345; $pdf->Rect($x, $y+2, $w, $h);
							$pdf->SetXY($x, $y+5);
							$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
							$pdf->SetFont('Arial', 'B', 9);
							$y=$pdf->GetY();
							$x=5;
							$pdf->SetXY($x, $y); $pdf->Cell(23, 5, utf8_decode("Bienes en la Organización: ").$registros_organizacion, 0, 1, 'L', 1); $x+=23;
							if ($total_costo_organizacion>0){
								$pdf->SetXY(170, $y); $pdf->Cell(40, 5, "Total Bs.: ".number_format($total_costo_organizacion, 2, ',', '.'), 0, 1, 'R', 1); 
							}
							if ($total_valor_organizacion>0){
								$pdf->SetXY(210, $y); $pdf->Cell(25, 5, number_format($total_valor_organizacion, 2, ',', '.'), 0, 1, 'R', 1); 
							}
							$total_costo_organizacion = 0;
							$total_valor_organizacion = 0;
							$registros_organizacion = 0;
							$rn=0;
							$pdf->Ln(5);
						}
						$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
						$pdf->SetFont('Arial', 'B', 10);
						$pdf->Cell(205, 7, utf8_decode("ORGANIZACIÓN: ".$field["organizacion"]), 1, 1, 'L', 1); 
						$pdf->SetFont('Arial', '', 9);
						
						$pdf->Ln(5);
					}
				//	SI CAMBIA DE NIVEL ORGANIZACIONAL LO IMPRIMO
					if ($field["nivel_organizacion"]!=$idnivel_organizacion) {
						$pdf->Ln(5);
						$idnivel_anterior=$idnivel_organizacion;
						$idnivel_organizacion=$field["nivel_organizacion"];
						if ($registros_seleccion >= 1){
							//	IMPRIMO LOS TOTALES
							$rn = $registros_nivel-1;
							$y=$pdf->GetY();
							$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
							$h=0.1; $x=5; $w=345; $pdf->Rect($x, $y+2, $w, $h);
							$pdf->SetXY($x, $y+5);
							$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
							$pdf->SetFont('Arial', 'B', 9);
							$y=$pdf->GetY();
							$x=5;
							$pdf->SetXY($x, $y); $pdf->Cell(23, 5, utf8_decode("Bienes en la Unidad: ".$idnivel_anterior." ").$registros_seleccion, 0, 1, 'L', 1); $x+=23;
							if ($total_costo_seleccion>0){
								$pdf->SetXY(170, $y); $pdf->Cell(40, 5, "Total Bs.: ".number_format($total_costo_seleccion, 2, ',', '.'), 0, 1, 'R', 1); 
							}
							if ($total_valor_seleccion>0){
								$pdf->SetXY(210, $y); $pdf->Cell(25, 5, number_format($total_valor_seleccion, 2, ',', '.'), 0, 1, 'R', 1); 
							}
							$total_costo_seleccion = 0;
							$total_valor_seleccion = 0;
							$registros_seleccion = 0;
							$rn=0;
							$pdf->Ln(5);
						}
						$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
						$pdf->SetFont('Arial', 'B', 10);
						$pdf->Cell(205, 7, utf8_decode($field["nivel_organizacion"]), 1, 1, 'L', 1); 
						$pdf->SetFont('Arial', '', 9);
						
						$pdf->Ln(5);
					}
				$h++;
				list($a, $m, $d) = split('[/.-]', $field['fecha_compra']); $fecha_compra_mostrar = "$d/$m/$a";
				$valor_actual_mostrar = $field['costo'] - $field['depreciacion_acumulada'];
				
				$campo0=$field['codigo_bien'];
				$campo1='';$campo2='';$campo3='';$campo4='';$campo5='';$campo6='';
				if ($codigo_anterior){ $campo1=$field['codigo_anterior_bien']; }
				if ($especificaciones){ $campo2=$field['especificaciones']; }
				if ($fecha_compra){ $campo3=$fecha_compra_mostrar; }
				if ($costo){ 
					$campo4=number_format($field['costo'], 2, ',', '.'); 
					$total_costo_seleccion = $total_costo_seleccion + $field['costo']; 
					$total_costo_organizacion = $total_costo_organizacion + $field['costo']; 
					$total_costo_total = $total_costo_total + $field['costo']; }
				if ($valor_actual){ 
					$campo5=number_format($valor_actual_mostrar, 2, ',', '.');
					$total_valor_seleccion = $total_valor_seleccion + $valor_actual_mostrar; 
					$total_valor_organizacion = $total_valor_organizacion + $valor_actual_mostrar; 
					$total_valor_total = $total_valor_total + $valor_actual_mostrar; }
				if ($seriales){ $campo6=$field['serial']; }
				
				
				$registros_seleccion=$registros_seleccion+1;
				$registros_organizacion=$registros_organizacion+1;
				$registros_totales=$registros_totales+1;
					if ($h%2==0) { $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); }
					else { $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(235, 235, 235); $pdf->SetTextColor(0, 0, 0); }
				
				$pdf->Row(array($campo0, $campo1, utf8_decode($campo2), $campo3, $campo4, $campo5, utf8_decode($campo6)));
				$entro=0;
				$campo7='';$campo8='';$campo9='';$campo10='';
				$ancho7='';$ancho8='';$ancho9='';$ancho10='';
				$alineacion7='';$alineacion8='';$alineacion9='';$alineacion10='';
				if($codigo_catalogo){
					$campo7= $field['codigo_catalogo'];
					$ancho7="25";
					$alineacion7="L";
					$entro=1;
				}
				if($descripcion_catalogo){
					$campo8= $field['denominacion_catalogo'];
					$ancho8="95";
					$alineacion8="L";
					$entro=1;
				}
				if($ubicacion){
					$campo9= $field['ubicacion'];
					$ancho9="85";
					$alineacion9="L";
					$entro=1;
				}
				
				if($marca){
					$campo10= $field['marca'];
					$ancho10="40";
					$alineacion10="L";
					$entro=1;
				}
					
				if($entro==1){		
					$pdf->SetWidths(array(25, $ancho7, $ancho8, $ancho9, $ancho10));
					$pdf->SetAligns(array('L', $alineacion7, $alineacion8, $alineacion9, $alineacion10));
					//$pdf->SetFont('Arial', '', 9);
					
					$pdf->Row(array(" ", $campo7, utf8_decode($campo8), utf8_decode($campo9),utf8_decode($campo10)));
					
					$ancho="25";
	
					if ($codigo_anterior) {
						$alineacion_inferior1="L";
						$ancho1="25";
					}
					if ($especificaciones) {
						$alineacion_inferior2="L";
						$ancho2="110";
					}
					if ($fecha_compra) {
						$alineacion_inferior3="C";
						$ancho3="20";
					}
					if ($costo) {
						$alineacion_inferior4="R";
						$ancho4="25";
					}
					if ($valor_actual) {
						$alineacion_inferior5="R";
						$ancho5="25";
					}
					if ($seriales) {
						$alineacion_inferior6="L";
						$ancho6="40";
					}
					$pdf->SetWidths(array($ancho,$ancho1,$ancho2,$ancho3,$ancho4,$ancho5,$ancho6));
					$pdf->SetAligns(array($alineacion_inferior,$alineacion_inferior1,$alineacion_inferior2,$alineacion_inferior3,$alineacion_inferior4,$alineacion_inferior5,$alineacion_inferior6));
				}
				$linea=$pdf->GetY(); if ($linea>185) { muebles_inventario($pdf, $costo, $ubicacion, $seriales, $especificaciones, $codigo_anterior, $codigo_catalogo, $descripcion_catalogo, $fecha_compra, $valor_actual, $marca, $organizacion, $nivel_organizacion); }
			}
			if ($registros_seleccion >= 1){
				//	IMPRIMO LOS TOTALES
				$rn = $registros_nivel-1;
				$y=$pdf->GetY();
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
				$h=0.1; $x=5; $w=345; $pdf->Rect($x, $y+2, $w, $h);
				$pdf->SetXY($x, $y+5);
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 9);
				$y=$pdf->GetY();
				$x=5;
				$pdf->SetXY($x, $y); $pdf->Cell(23, 5, utf8_decode("Bienes en la Unidad: ".$idnivel_organizacion." ").$registros_seleccion, 0, 1, 'L', 1); $x+=23;
				if ($total_costo_seleccion>0){
					$pdf->SetXY(170, $y); $pdf->Cell(40, 5, "Total Bs.: ".number_format($total_costo_seleccion, 2, ',', '.'), 0, 1, 'R', 1); 
				}
				if ($total_valor_seleccion>0){
					$pdf->SetXY(210, $y); $pdf->Cell(25, 5, number_format($total_valor_seleccion, 2, ',', '.'), 0, 1, 'R', 1); 
				}
				$total_costo_seleccion = 0;
				$total_valor_seleccion = 0;
				$registros_seleccion = 0;
				$rn=0;
				$pdf->Ln(5);
			}
			
			if ($registros_organizacion >= 1){
				//	IMPRIMO LOS TOTALES
				$rn = $registros_nivel-1;
				$y=$pdf->GetY();
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
				$h=0.1; $x=5; $w=345; $pdf->Rect($x, $y+2, $w, $h);
				$pdf->SetXY($x, $y+5);
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 9);
				$y=$pdf->GetY();
				$x=5;
				$pdf->SetXY($x, $y); $pdf->Cell(23, 5, utf8_decode("Bienes en la Organización: ".$idorganizacion." ").$registros_organizacion, 0, 1, 'L', 1); $x+=23;
				if ($total_costo_organizacion>0){
					$pdf->SetXY(170, $y); $pdf->Cell(40, 5, "Total Bs.: ".number_format($total_costo_organizacion, 2, ',', '.'), 0, 1, 'R', 1); 
				}
				if ($total_valor_organizacion>0){
					$pdf->SetXY(210, $y); $pdf->Cell(25, 5, number_format($total_valor_organizacion, 2, ',', '.'), 0, 1, 'R', 1); 
				}
				$total_costo_organizacion = 0;
				$total_valor_organizacion = 0;
				$registros_organizacion = 0;
				$rn=0;
				$pdf->Ln(5);
			}
			
			//	IMPRIMO LOS TOTALES
			$y=$pdf->GetY();
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
			$h=0.1; $x=5; $w=345; $pdf->Rect($x, $y+2, $w, $h);
			$pdf->SetXY($x, $y+5);
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 9);
			$y=$pdf->GetY();
			$x=5;
			$pdf->SetXY($x, $y); $pdf->Cell(23, 5, "Numero de bienes listados: ".$registros_totales, 0, 1, 'L', 1); $x+=23;
			if ($total_costo_total>0){
				$pdf->SetXY(170, $y); $pdf->Cell(40, 5, "Total Bs.: ".number_format($total_costo_total, 2, ',', '.'), 0, 1, 'R', 1); 
			}
			if ($total_valor_total>0){
				$pdf->SetXY(210, $y); $pdf->Cell(25, 5, number_format($total_valor_total, 2, ',', '.'), 0, 1, 'R', 1); 
			}		
		}
		// EN CASO DE HABER SELECCIONADO UN NIVEL ORGANIZACIONAL	
		 else {
			
			
			while ($field = mysql_fetch_array($query)) {
				$h++;
				list($a, $m, $d) = split('[/.-]', $field['fecha_compra']); $fecha_compra_mostrar = "$d/$m/$a";
				$valor_actual_mostrar = $field['costo'] - $field['depreciacion_acumulada'];
				
				$campo0=$field['codigo_bien'];
				$campo1='';$campo2='';$campo3='';$campo4='';$campo5='';$campo6='';
				if ($codigo_anterior){ $campo1=$field['codigo_anterior_bien']; }
				if ($especificaciones){ $campo2=$field['especificaciones']; }
				if ($fecha_compra){ $campo3=$fecha_compra_mostrar; }
				if ($costo){ 
					$campo4=number_format($field['costo'], 2, ',', '.'); 
					$total_costo = $total_costo + $field['costo']; }
				if ($valor_actual){ 
					$campo5=number_format($valor_actual_mostrar, 2, ',', '.');
					$total_valor = $total_valor + $valor_actual_mostrar; }
				if ($seriales){ $campo6=$field['serial']; }
				
				
				$registros_seleccion=$numero_registros;
					if ($h%2==0) { $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); }
					else { $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(235, 235, 235); $pdf->SetTextColor(0, 0, 0); }
				
				$pdf->Row(array($campo0, $campo1, utf8_decode($campo2), $campo3, $campo4, $campo5, utf8_decode($campo6)));
				$entro=0;
				$campo7='';$campo8='';$campo9='';$campo10='';
				$ancho7='';$ancho8='';$ancho9='';$ancho10='';
				$alineacion7='';$alineacion8='';$alineacion9='';$alineacion10='';
				if($codigo_catalogo){
					$campo7= $field['codigo_catalogo'];
					$ancho7="25";
					$alineacion7="L";
					$entro=1;
				}
				if($descripcion_catalogo){
					$campo8= $field['denominacion_catalogo'];
					$ancho8="95";
					$alineacion8="L";
					$entro=1;
				}
				if($ubicacion){
					$campo9= $field['ubicacion'];
					$ancho9="85";
					$alineacion9="L";
					$entro=1;
				}
				
				if($marca){
					$campo10= $field['marca'];
					$ancho10="40";
					$alineacion10="L";
					$entro=1;
				}
					
				if($entro==1){		
					$pdf->SetWidths(array(25, $ancho7, $ancho8, $ancho9, $ancho10));
					$pdf->SetAligns(array('L', $alineacion7, $alineacion8, $alineacion9, $alineacion10));
					//$pdf->SetFont('Arial', '', 9);
					
					$pdf->Row(array(" ", $campo7, utf8_decode($campo8), utf8_decode($campo9),utf8_decode($campo10)));
					
					$ancho="25";
	
					if ($codigo_anterior) {
						$alineacion_inferior1="L";
						$ancho1="25";
					}
					if ($especificaciones) {
						$alineacion_inferior2="L";
						$ancho2="110";
					}
					if ($fecha_compra) {
						$alineacion_inferior3="C";
						$ancho3="20";
					}
					if ($costo) {
						$alineacion_inferior4="R";
						$ancho4="25";
					}
					if ($valor_actual) {
						$alineacion_inferior5="R";
						$ancho5="25";
					}
					if ($seriales) {
						$alineacion_inferior6="L";
						$ancho6="40";
					}
					$pdf->SetWidths(array($ancho,$ancho1,$ancho2,$ancho3,$ancho4,$ancho5,$ancho6));
					$pdf->SetAligns(array($alineacion_inferior,$alineacion_inferior1,$alineacion_inferior2,$alineacion_inferior3,$alineacion_inferior4,$alineacion_inferior5,$alineacion_inferior6));
				}
				$linea=$pdf->GetY(); if ($linea>185) { muebles_inventario($pdf, $costo, $ubicacion, $seriales, $especificaciones, $codigo_anterior, $codigo_catalogo, $descripcion_catalogo, $fecha_compra, $valor_actual, $marca, $organizacion, $nivel_organizacion); }
			}
			//	IMPRIMO LOS TOTALES
			$y=$pdf->GetY();
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
			$h=0.1; $x=5; $w=345; $pdf->Rect($x, $y+2, $w, $h);
			$pdf->SetXY($x, $y+5);
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 9);
			$y=$pdf->GetY();
			$x=5;
			$pdf->SetXY($x, $y); $pdf->Cell(23, 5, "Numero de bienes listados: ".$registros_seleccion, 0, 1, 'L', 1); $x+=23;
			if ($total_costo>0){
				$pdf->SetXY(170, $y); $pdf->Cell(40, 5, "Total Bs.: ".number_format($total_costo, 2, ',', '.'), 0, 1, 'R', 1); 
			}
			if ($total_valor>0){
				$pdf->SetXY(210, $y); $pdf->Cell(25, 5, number_format($total_valor, 2, ',', '.'), 0, 1, 'R', 1); 
			}
			
		}
		
		
		
		break;
	
	//	Bienes Muebles Por Catalogo...
	case "muebles_por_catalogo":
		$pdf = new PDF_MC_Table5('L', 'mm', 'Letter');
		$pdf->SetTopMargin(5);
		$pdf->SetLeftMargin(5);
		$pdf->SetAutoPageBreak(1, 2.5);
			
		//	Imprimo una nueva página...
		//	Imprimo una nueva página...
		muebles_por_catalogo($pdf, $costo, $ubicacion, $seriales, $especificaciones, $codigo_anterior, $codigo_catalogo, $descripcion_catalogo, $fecha_compra, $valor_actual, $marca, $catalogo);
		
		$filtro = "";
		//	Verifico el filtro...
		if ($catalogo != 0) $filtro = "WHERE m.idcatalogo_bienes = '".$catalogo."'";
		
		//	Consulto e imprimo el inventario...
		$sql = "SELECT 
						m.codigo_bien, 
						m.especificaciones, 
						m.codigo_anterior_bien,
						m.serial,
						m.marca,
						m.fecha_compra, 
						m.costo, 
						m.depreciacion_acumulada, 
						d.codigo AS codigo_catalogo, 
						d.denominacion AS denominacion_catalogo, 
						u.denominacion AS ubicacion, 
						n.denominacion as nivel_organizacion,
						o.denominacion as organizacion
				FROM 
						muebles m 
						INNER JOIN detalle_catalogo_bienes d ON (m.idcatalogo_bienes = d.iddetalle_catalogo_bienes) 
						INNER JOIN ubicacion u ON (m.idubicacion = u.idubicacion) 
						INNER JOIN niveles_organizacionales n ON (m.idnivel_organizacion = n.idniveles_organizacionales)
						INNER JOIN organizacion o ON (m.idorganizacion = o.idorganizacion)
				$filtro 
				ORDER BY 
						m.idorganizacion, m.idnivel_organizacion, m.codigo_bien";
						
		$query = mysql_query($sql) or die ($sql.mysql_error());
		
		$numero_registros = mysql_num_rows($query);
		
		$idnivel_organizacion = '0';
		$idorganizacion = '0';
		$registros_seleccion = 0;
		$rn=0;
		$registros_seleccion=0;
		$registros_organizacion=0;
		$registros_totales=0;
		
		
		
		
		while ($field = mysql_fetch_array($query)) {
				//	SI CAMBIA DE ORGANIZACIONAL LO IMPRIMO
					if ($field["organizacion"]!=$idorganizacion) {
						$pdf->Ln(5);
						$idorganizacion=$field["organizacion"];
						if ($registros_organizacion >= 1){
							//	IMPRIMO LOS TOTALES
							$rn = $registros_nivel-1;
							$y=$pdf->GetY();
							$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
							$h=0.1; $x=5; $w=345; $pdf->Rect($x, $y+2, $w, $h);
							$pdf->SetXY($x, $y+5);
							$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
							$pdf->SetFont('Arial', 'B', 9);
							$y=$pdf->GetY();
							$x=5;
							$pdf->SetXY($x, $y); $pdf->Cell(23, 5, utf8_decode("Bienes en la Organización: ").$registros_organizacion, 0, 1, 'L', 1); $x+=23;
							if ($total_costo_organizacion>0){
								$pdf->SetXY(170, $y); $pdf->Cell(40, 5, "Total Bs.: ".number_format($total_costo_organizacion, 2, ',', '.'), 0, 1, 'R', 1); 
							}
							if ($total_valor_organizacion>0){
								$pdf->SetXY(210, $y); $pdf->Cell(25, 5, number_format($total_valor_organizacion, 2, ',', '.'), 0, 1, 'R', 1); 
							}
							$total_costo_organizacion = 0;
							$total_valor_organizacion = 0;
							$registros_organizacion = 0;
							$rn=0;
							$pdf->Ln(5);
						}
						$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
						$pdf->SetFont('Arial', 'B', 10);
						$pdf->Cell(205, 7, utf8_decode("ORGANIZACIÓN: ".$field["organizacion"]), 1, 1, 'L', 1); 
						$pdf->SetFont('Arial', '', 9);
						
						$pdf->Ln(5);
					}
				//	SI CAMBIA DE NIVEL ORGANIZACIONAL LO IMPRIMO
					if ($field["nivel_organizacion"]!=$idnivel_organizacion) {
						$pdf->Ln(5);
						$idnivel_anterior=$idnivel_organizacion;
						$idnivel_organizacion=$field["nivel_organizacion"];
						if ($registros_seleccion >= 1){
							//	IMPRIMO LOS TOTALES
							$rn = $registros_nivel-1;
							$y=$pdf->GetY();
							$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
							$h=0.1; $x=5; $w=345; $pdf->Rect($x, $y+2, $w, $h);
							$pdf->SetXY($x, $y+5);
							$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
							$pdf->SetFont('Arial', 'B', 9);
							$y=$pdf->GetY();
							$x=5;
							$pdf->SetXY($x, $y); $pdf->Cell(23, 5, utf8_decode("Bienes en la Unidad: ".$idnivel_anterior." ").$registros_seleccion, 0, 1, 'L', 1); $x+=23;
							if ($total_costo_seleccion>0){
								$pdf->SetXY(170, $y); $pdf->Cell(40, 5, "Total Bs.: ".number_format($total_costo_seleccion, 2, ',', '.'), 0, 1, 'R', 1); 
							}
							if ($total_valor_seleccion>0){
								$pdf->SetXY(210, $y); $pdf->Cell(25, 5, number_format($total_valor_seleccion, 2, ',', '.'), 0, 1, 'R', 1); 
							}
							$total_costo_seleccion = 0;
							$total_valor_seleccion = 0;
							$registros_seleccion = 0;
							$rn=0;
							$pdf->Ln(5);
						}
						$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
						$pdf->SetFont('Arial', 'B', 10);
						$pdf->Cell(205, 7, utf8_decode($field["nivel_organizacion"]), 1, 1, 'L', 1); 
						$pdf->SetFont('Arial', '', 9);
						
						$pdf->Ln(5);
					}
				$h++;
				list($a, $m, $d) = split('[/.-]', $field['fecha_compra']); $fecha_compra_mostrar = "$d/$m/$a";
				$valor_actual_mostrar = $field['costo'] - $field['depreciacion_acumulada'];
				
				$campo0=$field['codigo_bien'];
				$campo1='';$campo2='';$campo3='';$campo4='';$campo5='';$campo6='';
				if ($codigo_anterior){ $campo1=$field['codigo_anterior_bien']; }
				if ($especificaciones){ $campo2=$field['especificaciones']; }
				if ($fecha_compra){ $campo3=$fecha_compra_mostrar; }
				if ($costo){ 
					$campo4=number_format($field['costo'], 2, ',', '.'); 
					$total_costo_seleccion = $total_costo_seleccion + $field['costo']; 
					$total_costo_organizacion = $total_costo_organizacion + $field['costo']; 
					$total_costo_total = $total_costo_total + $field['costo']; }
				if ($valor_actual){ 
					$campo5=number_format($valor_actual_mostrar, 2, ',', '.');
					$total_valor_seleccion = $total_valor_seleccion + $valor_actual_mostrar; 
					$total_valor_organizacion = $total_valor_organizacion + $valor_actual_mostrar; 
					$total_valor_total = $total_valor_total + $valor_actual_mostrar; }
				if ($seriales){ $campo6=$field['serial']; }
				
				
				$registros_seleccion=$registros_seleccion+1;
				$registros_organizacion=$registros_organizacion+1;
				$registros_totales=$registros_totales+1;
					if ($h%2==0) { $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); }
					else { $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(235, 235, 235); $pdf->SetTextColor(0, 0, 0); }
				
				$pdf->Row(array($campo0, $campo1, utf8_decode($campo2), $campo3, $campo4, $campo5, utf8_decode($campo6)));
				$entro=0;
				$campo7='';$campo8='';$campo9='';$campo10='';
				$ancho7='';$ancho8='';$ancho9='';$ancho10='';
				$alineacion7='';$alineacion8='';$alineacion9='';$alineacion10='';
				
				if($ubicacion){
					$campo9= $field['ubicacion'];
					$ancho9="85";
					$alineacion9="L";
					$entro=1;
				}
				
				if($marca){
					$campo10= $field['marca'];
					$ancho10="40";
					$alineacion10="L";
					$entro=1;
				}
					
				if($entro==1){		
					$pdf->SetWidths(array(25, $ancho7, $ancho8, $ancho9, $ancho10));
					$pdf->SetAligns(array('L', $alineacion7, $alineacion8, $alineacion9, $alineacion10));
					//$pdf->SetFont('Arial', '', 9);
					
					$pdf->Row(array(" ", $campo7, utf8_decode($campo8), utf8_decode($campo9),utf8_decode($campo10)));
					
					$ancho="25";
	
					if ($codigo_anterior) {
						$alineacion_inferior1="L";
						$ancho1="25";
					}
					if ($especificaciones) {
						$alineacion_inferior2="L";
						$ancho2="110";
					}
					if ($fecha_compra) {
						$alineacion_inferior3="C";
						$ancho3="20";
					}
					if ($costo) {
						$alineacion_inferior4="R";
						$ancho4="25";
					}
					if ($valor_actual) {
						$alineacion_inferior5="R";
						$ancho5="25";
					}
					if ($seriales) {
						$alineacion_inferior6="L";
						$ancho6="40";
					}
					$pdf->SetWidths(array($ancho,$ancho1,$ancho2,$ancho3,$ancho4,$ancho5,$ancho6));
					$pdf->SetAligns(array($alineacion_inferior,$alineacion_inferior1,$alineacion_inferior2,$alineacion_inferior3,$alineacion_inferior4,$alineacion_inferior5,$alineacion_inferior6));
				}
				$linea=$pdf->GetY(); if ($linea>185) { muebles_por_catalogo($pdf, $costo, $ubicacion, $seriales, $especificaciones, $codigo_anterior, $codigo_catalogo, $descripcion_catalogo, $fecha_compra, $valor_actual, $marca, $catalogo); }
			}
			if ($registros_seleccion >= 1){
				//	IMPRIMO LOS TOTALES
				$rn = $registros_nivel-1;
				$y=$pdf->GetY();
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
				$h=0.1; $x=5; $w=345; $pdf->Rect($x, $y+2, $w, $h);
				$pdf->SetXY($x, $y+5);
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 9);
				$y=$pdf->GetY();
				$x=5;
				$pdf->SetXY($x, $y); $pdf->Cell(23, 5, utf8_decode("Bienes en la Unidad: ".$idnivel_organizacion." ").$registros_seleccion, 0, 1, 'L', 1); $x+=23;
				if ($total_costo_seleccion>0){
					$pdf->SetXY(170, $y); $pdf->Cell(40, 5, "Total Bs.: ".number_format($total_costo_seleccion, 2, ',', '.'), 0, 1, 'R', 1); 
				}
				if ($total_valor_seleccion>0){
					$pdf->SetXY(210, $y); $pdf->Cell(25, 5, number_format($total_valor_seleccion, 2, ',', '.'), 0, 1, 'R', 1); 
				}
				$total_costo_seleccion = 0;
				$total_valor_seleccion = 0;
				$registros_seleccion = 0;
				$rn=0;
				$pdf->Ln(5);
			}
			
			if ($registros_organizacion >= 1){
				//	IMPRIMO LOS TOTALES
				$rn = $registros_nivel-1;
				$y=$pdf->GetY();
				$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
				$h=0.1; $x=5; $w=345; $pdf->Rect($x, $y+2, $w, $h);
				$pdf->SetXY($x, $y+5);
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', 'B', 9);
				$y=$pdf->GetY();
				$x=5;
				$pdf->SetXY($x, $y); $pdf->Cell(23, 5, utf8_decode("Bienes en la Organización: ".$idorganizacion." ").$registros_organizacion, 0, 1, 'L', 1); $x+=23;
				if ($total_costo_organizacion>0){
					$pdf->SetXY(170, $y); $pdf->Cell(40, 5, "Total Bs.: ".number_format($total_costo_organizacion, 2, ',', '.'), 0, 1, 'R', 1); 
				}
				if ($total_valor_organizacion>0){
					$pdf->SetXY(210, $y); $pdf->Cell(25, 5, number_format($total_valor_organizacion, 2, ',', '.'), 0, 1, 'R', 1); 
				}
				$total_costo_organizacion = 0;
				$total_valor_organizacion = 0;
				$registros_organizacion = 0;
				$rn=0;
				$pdf->Ln(5);
			}
			
			//	IMPRIMO LOS TOTALES
			$y=$pdf->GetY();
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
			$h=0.1; $x=5; $w=345; $pdf->Rect($x, $y+2, $w, $h);
			$pdf->SetXY($x, $y+5);
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 9);
			$y=$pdf->GetY();
			$x=5;
			$pdf->SetXY($x, $y); $pdf->Cell(23, 5, "Numero de bienes listados: ".$registros_totales, 0, 1, 'L', 1); $x+=23;
			if ($total_costo_total>0){
				$pdf->SetXY(170, $y); $pdf->Cell(40, 5, "Total Bs.: ".number_format($total_costo_total, 2, ',', '.'), 0, 1, 'R', 1); 
			}
			if ($total_valor_total>0){
				$pdf->SetXY(210, $y); $pdf->Cell(25, 5, number_format($total_valor_total, 2, ',', '.'), 0, 1, 'R', 1); 
			}
		break;
	
	
	//	FORMULARIO BM1...
	case "bm1":
		$pdf = new PDF_MC_Table8('L', 'mm', 'Letter');
		$pdf->SetTopMargin(5);
		$pdf->SetLeftMargin(5);
		$pdf->SetAutoPageBreak(1, 2.5);
			
		//	Imprimo una nueva página...
		bm1($pdf, $organizacion, $nivel_organizacion);
		
		$filtro = "";
		//	Verifico el filtro...
		if ($organizacion != 0) $filtro = "WHERE m.idorganizacion = '".$organizacion."'";
		if ($nivel_organizacion != 0) {
			if ($filtro != "") $filtro .= " AND m.idnivel_organizacion = '".$nivel_organizacion."'";
			else $filtro = "WHERE m.idnivel_organizacion = '".$nivel_organizacion."'";
		}
		
		//	Consulto e imprimo el inventario...
		$sql = "SELECT 
						m.codigo_bien, 
						m.especificaciones, 
						m.serial,
						m.marca, 
						m.costo, 
						m.depreciacion_acumulada, 
						d.codigo AS codigo_catalogo,
						d.denominacion AS denominacion_catalogo, 
						n.denominacion as nivel_organizacion,
						o.denominacion as organizacion
						
				FROM 
						muebles m 
						INNER JOIN detalle_catalogo_bienes d ON (m.idcatalogo_bienes = d.iddetalle_catalogo_bienes) 
						INNER JOIN ubicacion u ON (m.idubicacion = u.idubicacion) 
						INNER JOIN niveles_organizacionales n ON (m.idnivel_organizacion = n.idniveles_organizacionales)
						INNER JOIN organizacion o ON (m.idorganizacion = o.idorganizacion)
				$filtro
				ORDER BY 
						m.codigo_bien";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		$numero_registros = mysql_num_rows($query);
		$total_costo = 0;
		$total_valor = 0;
		$total_costo_nivel = 0;
		$total_valor_nivel = 0;
		$registros_nivel = 0;
		$idnivel_organizacion = '0';
		$idorganizacion = '0';
		$registros_seleccion = 0;
		$rn=0;
		$registros_seleccion=0;
		$registros_organizacion=0;
		$registros_totales=0;
					
		while ($field = mysql_fetch_array($query)) {
			
			$h++;
		
			$campo1='';$campo2='';$campo3='';$campo4='';$campo5='';$campo6='';
			
			$campo0=substr($field['codigo_catalogo'],0,1);
			$campo1=substr($field['codigo_catalogo'],2,2);
			$campo2=substr($field['codigo_catalogo'],5,4);
			
			$campo3='1';
			$campo4=$field['codigo_bien'];
			$campo5=$field['especificaciones'];
			if($field['marca']<>''){ $campo5=$campo5.', Marca: '.$field['marca'];}
			if($field['serial']<>''){ $campo5=$campo5.', Serial No.: '.$field['serial'];}
			
			$campo6=number_format($field['costo'], 2, ',', '.'); 
			$total_costo_total = $total_costo_total + $field['costo']; 
					
			$registros_seleccion=$registros_seleccion+1;
			$registros_organizacion=$registros_organizacion+1;
			$registros_totales=$registros_totales+1;
			if ($h%2==0) { $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); }
			else { $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(235, 235, 235); $pdf->SetTextColor(0, 0, 0); }
			
			$pdf->Row(array($campo0, $campo1, $campo2, $campo3, $campo4, utf8_decode($campo5), $campo6, $campo6));
			
			$linea=$pdf->GetY(); if ($linea>185) { bm1($pdf, $organizacion, $nivel_organizacion); }
		}
			
				
			
			//	IMPRIMO LOS TOTALES
			$y=$pdf->GetY();
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
			$h=0.1; $x=5; $w=345; $pdf->Rect($x, $y+2, $w, $h);
			$pdf->SetXY($x, $y+5);
			$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', 'B', 9);
			$y=$pdf->GetY();
			$x=5;
			$pdf->SetXY($x, $y); $pdf->Cell(23, 5, "Numero de bienes listados: ".$registros_totales, 0, 1, 'L', 1); $x+=23;
			if ($total_costo_total>0){
				$pdf->SetXY(140, $y); $pdf->Cell(40, 5, "Total Bs.: ", 0, 1, 'R', 1); 
				$pdf->SetXY(220, $y); $pdf->Cell(25, 5, number_format($total_costo_total, 2, ',', '.'), 0, 1, 'R', 1); 
				$pdf->SetXY(250, $y); $pdf->Cell(25, 5, number_format($total_costo_total, 2, ',', '.'), 0, 1, 'R', 1); 
			}
					
		
			//	IMPRIMO LAS FIRMAS
			$sql_configuracion_bienes = mysql_query("select * from configuracion_bienes");
			$reg_configuracion_bienes = mysql_fetch_array($sql_configuracion_bienes);
			
			$sql_dependencia = mysql_query("select * from dependencias where iddependencia = '".$reg_configuracion_bienes["iddependencia"]."'");
			$reg_dependencia=mysql_fetch_array($sql_dependencia);
			
			$sql = "SELECT * FROM niveles_organizacionales WHERE idniveles_organizacionales = '".$nivel_organizacion."'";
			$query_nivel = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_nivel) != 0) $field_nivel = mysql_fetch_array($query_nivel);
			
			
			$pdf->Ln(15);
			
			$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
			$y=$pdf->GetY();
			$pdf->Line(30, $y, 120, $y);
			$pdf->Line(155, $y, 245, $y);
			//$pdf->Ln(5);
			$pdf->SetAligns(array('C', 'C','C', 'C', 'C'));
			$pdf->SetWidths(array(20, 105, 20, 105, 20));
			$pdf->SetFont('Arial', 'B', 9);
			$pdf->Cell(20, 5, utf8_decode(''), 0, 0, 'C');
			$pdf->Cell(105, 5, utf8_decode($reg_dependencia["denominacion"]), 0, 0, 'C');
			$pdf->Cell(20, 5, utf8_decode(''), 0, 0, 'C');
			$pdf->Cell(105, 5, utf8_decode($field_nivel['denominacion']), 0, 0, 'C');
			$pdf->Cell(20, 5, utf8_decode(''), 0, 1, 'C');
			$pdf->Cell(20, 5, utf8_decode(''), 0, 0, 'C');
			$pdf->Cell(105, 5, utf8_decode($reg_configuracion_bienes["primero_bienes"]), 0, 0, 'C');
			$pdf->Cell(20, 5, utf8_decode(''), 0, 0, 'C');
			$pdf->Cell(105, 5, utf8_decode($field_nivel['responsable']), 0, 0, 'C');
			$pdf->Cell(20, 5, utf8_decode(''), 0, 1, 'C');
			$pdf->Cell(20, 5, utf8_decode(''), 0, 0, 'C');
			$pdf->Cell(105, 5, utf8_decode($reg_configuracion_bienes["ci_primero_bienes"]), 0, 0, 'C');
			$pdf->Cell(20, 5, utf8_decode(''), 0, 0, 'C');
			$pdf->Cell(105, 5, utf8_decode($field_nivel['ci_responsable']), 0, 0, 'C');
			$pdf->Cell(20, 5, utf8_decode(''), 0, 1, 'C');
			
		
		break;
	
	
	//	Catalogo...
	case "bienes_catalogo":
		$pdf = new PDF_MC_Table5('P', 'mm', 'Letter');
		$pdf->SetTopMargin(5);
		$pdf->SetLeftMargin(15);
		$pdf->SetAutoPageBreak(1, 2.5);
		
		//	Imprimo una nueva página...
		bienes_catalogo($pdf);
		
		//	Verifico los filtros...
		if ($grupo != 0) { 
			$fgrupo = "WHERE gcb.idgrupo_catalogo_bienes = '".$grupo."'"; 
			$igrupo = "AND gcb.idgrupo_catalogo_bienes = '".$grupo."'"; 
		}
		if ($sub_grupo != 0) {
			$fsub_grupo = "WHERE sgcb.idsubgrupo_catalogo_bienes = '".$sub_grupo."'";
			$isub_grupo = "AND sgcb.idsubgrupo_catalogo_bienes = '".$sub_grupo."'";
		}
		if ($seccion != 0) {
			$fseccion = "WHERE scb.idsecciones_catalogo_bienes = '".$seccion."'";
			$iseccion = "AND scb.idsecciones_catalogo_bienes = '".$seccion."'";
		}
		
		//	Consulto para imprimir el cuerpo del PDF...
		$sql = "(SELECT 
					gcb.codigo, 
					gcb.denominacion, 
					'grupo' AS nivel 
				FROM 
					grupo_catalogo_bienes gcb 
				".$fgrupo."
				) 
					
				UNION 
				
				(SELECT 
					sgcb.codigo, 
					sgcb.denominacion, 
					'subgrupo' AS nivel 
				FROM 
					subgrupo_catalogo_bienes sgcb
					INNER JOIN grupo_catalogo_bienes gcb ON (sgcb.idgrupo_catalogo_bienes = gcb.idgrupo_catalogo_bienes ".$igrupo.") 
				".$fsub_grupo."
				) 
				
				UNION
				
				(SELECT 
					scb.codigo, 
					scb.denominacion, 
					'seccion' AS nivel 
				FROM 
					secciones_catalogo_bienes scb
					INNER JOIN subgrupo_catalogo_bienes sgcb ON (scb.idsubgrupo_catalogo_bienes = sgcb.idsubgrupo_catalogo_bienes ".$isub_grupo.") 
					INNER JOIN grupo_catalogo_bienes gcb ON (sgcb.idgrupo_catalogo_bienes = gcb.idgrupo_catalogo_bienes ".$igrupo.") 
				".$fseccion."
				) 
				
				UNION
				
				(SELECT 
					dcb.codigo, 
					dcb.denominacion, 
					'detalle' AS nivel 
				FROM 
					detalle_catalogo_bienes dcb
					INNER JOIN secciones_catalogo_bienes scb ON (dcb.idsecciones_catalogo_bienes = scb.idsecciones_catalogo_bienes ".$iseccion.") 
					INNER JOIN subgrupo_catalogo_bienes sgcb ON (scb.idsubgrupo_catalogo_bienes = sgcb.idsubgrupo_catalogo_bienes ".$isub_grupo.") 
					INNER JOIN grupo_catalogo_bienes gcb ON (sgcb.idgrupo_catalogo_bienes = gcb.idgrupo_catalogo_bienes ".$igrupo.") 
				)				
				ORDER BY codigo";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		while ($field = mysql_fetch_array($query)) {
			if ($field['nivel'] == "grupo") { $x = 15; $w = 175; $c = 5; }
			if ($field['nivel'] == "subgrupo") { $x = 20; $w = 170; $c = 10; }
			if ($field['nivel'] == "seccion") { $x = 25; $w = 165; $c = 15; }
			if ($field['nivel'] == "detalle") { $x = 30; $w = 160; $c = 20; }
			
			$pdf->SetX($x);
			
			$pdf->Cell($c, 4, $field['codigo'], 0, 0, 'L');
			$pdf->MultiCell($w, 4, $field['denominacion'], 0, 'L');
			$pdf->Ln(3);
		}
		break;
	
	//	Estructura Organizativa...
	case "bienes_estructura_organizativa":
		$pdf = new PDF_MC_Table5('P', 'mm', 'Letter');
		$pdf->SetTopMargin(5);
		$pdf->SetLeftMargin(15);
		$pdf->SetAutoPageBreak(1, 2.5);
		
		//	Imprimo una nueva página...
		bienes_estructura_organizativa($pdf);
		
		//	Verifico el filtro...
		if ($organizacion != 0)	$filtro = "WHERE idorganizacion = '".$organizacion."'";
		
		//	Consulto las organizaciones a imprimir....
		$sql = "SELECT * FROM organizacion $filtro ORDER BY codigo";
		$query_organizacion = mysql_query($sql) or die ($sql.mysql_error());
		while ($field_organizacion = mysql_fetch_array($query_organizacion)) {
			//	Imprimo la organizacion....
			$pdf->SetX(15);		
			$pdf->MultiCell(185, 4, utf8_decode($field_organizacion['codigo'].' - '.$field_organizacion['denominacion']), 0, 'L');
			$pdf->Ln(3);
			
			//	Consulto e Imprimo los sub_niveles de la organizacion....
			$foros = array();
			$sql = "SELECT idniveles_organizacionales, 
							denominacion, 
							sub_nivel,
							codigo 
					FROM 
							niveles_organizacionales 
					WHERE 
							organizacion = '".$field_organizacion['idorganizacion']."'";
			$result = mysql_query($sql) or die($sql.mysql_error());
			while($row = mysql_fetch_assoc($result)) {
				$foro = $row['idniveles_organizacionales'];
				$padre = $row['sub_nivel'];
				if(!isset($foros[$padre]))
				$foros[$padre] = array();
				$foros[$padre][$foro] = $row;
			}
			
			if (mysql_num_rows($result) != 0) listar_foros($pdf, 0, '', 15, 185);
		}
		break;
	
	//	Acta de Incorporacion...
	case "acta_incorporacion_bienes":
		$pdf = new PDF_MC_Table8('P', 'mm', 'Letter');
		$pdf->SetTopMargin(5);
		$pdf->SetLeftMargin(5);
		$pdf->SetAutoPageBreak(1, 2.5);
		$pag = 1; 
		
		//	Imprimo una nueva página...
		acta_incorporacion_bienes($pdf, $pag, $desde, $hasta);
		
		//	Consulto e imprimo...
		$sql = "SELECT 
						m.codigo_bien, 
						m.fecha_compra, 
						m.costo, 
						m.depreciacion_acumulada, 
						m.serial, 
						d.codigo AS codigo_catalogo, 
						d.denominacion AS denominacion_catalogo, 
						u.denominacion AS ubicacion, 
						n.denominacion as nivel_organizacion 
				FROM 
						muebles m 
						INNER JOIN detalle_catalogo_bienes d ON (m.idcatalogo_bienes = d.iddetalle_catalogo_bienes) 
						INNER JOIN ubicacion u ON (m.idubicacion = u.idubicacion) 
						INNER JOIN niveles_organizacionales n ON (m.idnivel_organizacion = n.idniveles_organizacionales)
				WHERE m.fecha_compra >= '".$desde."' AND m.fecha_compra <= '".$hasta."'
				ORDER BY 
						m.codigo_bien";
		$query = mysql_query($sql) or die ($sql.mysql_error());
		while ($field = mysql_fetch_array($query)) {
			list($a, $m, $d) = split('[/.-]', $field['fecha_compra']); $fecha_compra = "$d/$m/$a";
			$valor_actual = $field['costo'] - $field['depreciacion_acumulada'];
			
			$pdf->Row(array($field['codigo_bien'], $fecha_compra, number_format($field['costo'], 2, ',', '.'), $valor_actual, $field['serial'], utf8_decode($field['ubicacion'])));
		}
		break;
	
	//	Desincorporacion...
	case "desincorporacion":
		
		break;
	
	
	
	//	Bienes Muebles Inventario...
	case "movimiento":
		$pdf = new PDF_MC_Table4('P', 'mm', 'Letter');
		$pdf->SetTopMargin(5);
		$pdf->SetLeftMargin(5);
		$pdf->SetAutoPageBreak(1, 2.5);
		$pag=1;	
		//	Imprimo una nueva página...
		movimiento_bienes($pdf, $idmovimiento, $pag);
		$sql = "SELECT * FROM movimientos_bienes WHERE idmovimientos_bienes = '".$idmovimiento."'";
		$query_movimiento = mysql_query($sql) or die ($sql.mysql_error());
		$field_movimiento = mysql_fetch_array($query_movimiento);
		
		
		if ($field_movimiento["idtipo_movimiento"] != 0){
		//	-----------------------
	//	Obtengo el Tipo de Movimiento...
		$sql = "SELECT * FROM tipo_movimiento_bienes WHERE idtipo_movimiento_bienes = '".$field_movimiento["idtipo_movimiento"]."'";
		$query_tipo = mysql_query($sql) or die ($sql.mysql_error());
		if (mysql_num_rows($query_tipo) != 0) $field_tipo = mysql_fetch_array($query_tipo);
		
		if ($field_tipo["origen_bien"] == 'nuevo') {
			//	Consulto e imprimo el inventario...
			$sql = "SELECT
							mbn.codigo_bien,
							mbn.costo_ajustado,
							mbn.depresiacion_acumulada,
							mbn.seriales,
							mbn.especificaciones,
							mbn.idorganizacion,
							mbn.idnivel_organizacional,
							d.codigo AS codigo_catalogo,
							d.denominacion AS denominacion_catalogo,
							u.denominacion AS ubicacion,
              mbn.idmovimientos_bienes
					FROM
							movimientos_bienes_nuevos mbn
							INNER JOIN detalle_catalogo_bienes d ON (mbn.codigo_catalogo = d.iddetalle_catalogo_bienes)
							INNER JOIN ubicacion u ON (mbn.idubicacion = u.idubicacion)
					where
							mbn.idmovimientos_bienes = '".$idmovimiento."'
					ORDER BY 
							mbn.codigo_bien";
			$query = mysql_query($sql) or die ($sql.mysql_error());
			$idorganizacion = 0;
			while ($field = mysql_fetch_array($query)) {
				list($a, $m, $d) = split('[/.-]', $field['fecha_compra']); $fecha_compra = "$d/$m/$a";
				$valor_actual = $field['costo_ajustado'];
				if ($idorganizacion != $field['idorganizacion']){
					$sql_organizacion = mysql_query("select * from organizacion where idorganizacion = '".$field['idorganizacion']."'");
					$bus_organizacion = mysql_fetch_array($sql_organizacion);
					$idorganizacion = $field['idorganizacion'];
					$pdf->SetFont('Arial', 'B', 11);
		  			$pdf->Cell(35, 5, utf8_decode($bus_organizacion["denominacion"]), 0, 1, 'L');
					$pdf->SetAligns(array('L', 'L', 'L', 'L', 'R'));
					$pdf->SetWidths(array(20, 20, 60, 70, 35));
					$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial', '', 8);
					$pdf->Ln(3);
				}
				$sql_nivel_organizacional = mysql_query("select * from niveles_organizacionales 
																		where idniveles_organizacionales = '".$field['idnivel_organizacional']."'");
				$bus_nivel_organizacional = mysql_fetch_array($sql_nivel_organizacional);
				$nivel_organizacion=$field['idnivel_organizacional'];
				$pdf->Row(array($field['codigo_bien'], $field['codigo_catalogo'], utf8_decode($field['denominacion_catalogo']), utf8_decode($bus_nivel_organizacional['denominacion']), number_format($field['costo_ajustado'], 2, ',', '.')));
				$pdf->SetAligns(array('L', 'L', 'L', 'L', 'R'));
				$pdf->SetWidths(array(20, 20, 130));
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', '', 8);
				$pdf->Row(array('', '', utf8_decode($field['especificaciones']), ''));
				
				$pdf->SetAligns(array('L', 'L', 'L', 'L', 'R'));
				$pdf->SetWidths(array(20, 20, 60, 70, 35));
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', '', 8);	
				
			}
				/**************************************************************************************************************
				// FORMATO BM 2 INCORPORACION
				***************************************************************************************************************/
				
				$pdf = new PDF_MC_Table8a('L', 'mm', 'Letter');
				$pdf->SetTopMargin(5);
				$pdf->SetLeftMargin(5);
				$pdf->SetAutoPageBreak(1, 2.5);
					
				//	Imprimo una nueva página...
				bm2i($pdf, $idmovimiento, $idorganizacion,$nivel_organizacion);

				//	Consulto e imprimo el inventario...
				$sql = "SELECT
								mbn.codigo_bien,
								mbn.costo_ajustado,
								mbn.depresiacion_acumulada,
								mbn.seriales,
								mbn.especificaciones,
								mbn.costo,
								mbn.idorganizacion,
								mbn.idnivel_organizacional,
								d.codigo AS codigo_catalogo,
								d.denominacion AS denominacion_catalogo,
								u.denominacion AS ubicacion,
				  				mbn.idmovimientos_bienes
						FROM
								movimientos_bienes_nuevos mbn
								INNER JOIN detalle_catalogo_bienes d ON (mbn.codigo_catalogo = d.iddetalle_catalogo_bienes)
								INNER JOIN ubicacion u ON (mbn.idubicacion = u.idubicacion)
						where
								mbn.idmovimientos_bienes = '".$idmovimiento."'
						ORDER BY 
								mbn.codigo_bien";
				
				
				$query = mysql_query($sql) or die ($sql.mysql_error());
				$numero_registros = mysql_num_rows($query);
				
							
				while ($field = mysql_fetch_array($query)) {
					
					$h++;
				
					$campo1='';$campo2='';$campo3='';$campo4='';$campo5='';$campo6='';
					
					$campo0=substr($field['codigo_catalogo'],0,1);
					$campo1=substr($field['codigo_catalogo'],2,2);
					$campo2=substr($field['codigo_catalogo'],5,4);
					$campo33=$field_tipo["codigo"];
					$campo3='1';
					$campo4=$field['codigo_bien'];
					$campo5=$field['especificaciones'];
					if($field['marca']<>''){ $campo5=$campo5.', Marca: '.$field['marca'];}
					if($field['serial']<>''){ $campo5=$campo5.', Serial No.: '.$field['serial'];}
					
					$campo6=number_format($field['costo'], 2, ',', '.'); 
					$total_costo_total = $total_costo_total + $field['costo']; 
							
					
					$registros_totales=$registros_totales+1;
					if ($h%2==0) { $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0); }
					else { $pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(235, 235, 235); $pdf->SetTextColor(0, 0, 0); }
					
					$pdf->Row(array($campo0, $campo1, $campo2, $campo33,$campo3, $campo4, utf8_decode($campo5), $campo6));
					
					$linea=$pdf->GetY(); if ($linea>185) { bm2i($pdf, $idmovimiento, $field['idorganizacion'],$field['idnivel_organizacional']); }
				}
					
						
					
					//	IMPRIMO LOS TOTALES
					$y=$pdf->GetY();
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
					$h=0.1; $x=5; $w=270; $pdf->Rect($x, $y+2, $w, $h);
					$pdf->SetXY($x, $y+5);
					$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial', 'B', 9);
					$y=$pdf->GetY();
					$x=5;
					$pdf->SetXY($x, $y); $pdf->Cell(23, 5, "Numero de bienes listados: ".$registros_totales, 0, 1, 'L', 1); $x+=23;
					if ($total_costo_total>0){
						$pdf->SetXY(190, $y); $pdf->Cell(40, 5, "Total Bs.: ", 0, 1, 'R', 1); 
						$pdf->SetXY(250, $y); $pdf->Cell(25, 5, number_format($total_costo_total, 2, ',', '.'), 0, 1, 'R', 1); 
					}
							
				
					//	IMPRIMO LAS FIRMAS
					$sql_configuracion_bienes = mysql_query("select * from configuracion_bienes");
					$reg_configuracion_bienes = mysql_fetch_array($sql_configuracion_bienes);
					
					$sql_dependencia = mysql_query("select * from dependencias where iddependencia = '".$reg_configuracion_bienes["iddependencia"]."'");
					$reg_dependencia=mysql_fetch_array($sql_dependencia);
					
					$sql = "SELECT * FROM niveles_organizacionales WHERE idniveles_organizacionales = '".$nivel_organizacion."'";
					$query_nivel = mysql_query($sql) or die ($sql.mysql_error());
					if (mysql_num_rows($query_nivel) != 0) $field_nivel = mysql_fetch_array($query_nivel);
					
					
					$pdf->Ln(15);
					
					$pdf->SetDrawColor(0, 0, 0); $pdf->SetFillColor(0, 0, 0); $pdf->SetTextColor(0, 0, 0);
					$y=$pdf->GetY();
					$pdf->Line(30, $y, 120, $y);
					$pdf->Line(155, $y, 245, $y);
					//$pdf->Ln(5);
					$pdf->SetAligns(array('C', 'C','C', 'C', 'C'));
					$pdf->SetWidths(array(20, 105, 20, 105, 20));
					$pdf->SetFont('Arial', 'B', 9);
					$pdf->Cell(20, 5, utf8_decode(''), 0, 0, 'C');
					$pdf->Cell(105, 5, utf8_decode($reg_dependencia["denominacion"]), 0, 0, 'C');
					$pdf->Cell(20, 5, utf8_decode(''), 0, 0, 'C');
					$pdf->Cell(105, 5, utf8_decode($field_nivel['denominacion']), 0, 0, 'C');
					$pdf->Cell(20, 5, utf8_decode(''), 0, 1, 'C');
					$pdf->Cell(20, 5, utf8_decode(''), 0, 0, 'C');
					$pdf->Cell(105, 5, utf8_decode($reg_configuracion_bienes["primero_bienes"]), 0, 0, 'C');
					$pdf->Cell(20, 5, utf8_decode(''), 0, 0, 'C');
					$pdf->Cell(105, 5, utf8_decode($field_nivel['responsable']), 0, 0, 'C');
					$pdf->Cell(20, 5, utf8_decode(''), 0, 1, 'C');
					$pdf->Cell(20, 5, utf8_decode(''), 0, 0, 'C');
					$pdf->Cell(105, 5, utf8_decode($reg_configuracion_bienes["ci_primero_bienes"]), 0, 0, 'C');
					$pdf->Cell(20, 5, utf8_decode(''), 0, 0, 'C');
					$pdf->Cell(105, 5, utf8_decode($field_nivel['ci_responsable']), 0, 0, 'C');
					$pdf->Cell(20, 5, utf8_decode(''), 0, 1, 'C');
			
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			// AQUI VAN LAS FIRMAS DEL ACTA DE INCORPORACION BIENES NUEVOS
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}
		
		if ($field_tipo["afecta"] == '1' and $field_tipo["origen_bien"] == 'existente' and $field_tipo["cambia_ubicacion"] == 'no') {
			//	Consulto e imprimo el inventario...
			$sql = "SELECT
							mbn.codigo_bien as idbien,
							mbn.mejoras,
							mbn.descripcion,
							mbn.idorganizacion,
							mbn.idnivel_organizacional,
							d.codigo AS codigo_catalogo,
							d.denominacion AS denominacion_catalogo,
              				mbn.idmovimientos_bienes,
							m.codigo_bien as codigo_bien,
							m.especificaciones
					FROM
							movimientos_existentes_incorporacion mbn
							INNER JOIN detalle_catalogo_bienes d ON (mbn.codigo_catalogo = d.iddetalle_catalogo_bienes)
							INNER JOIN muebles m ON (mbn.codigo_bien = m.idmuebles)
					where
							mbn.idmovimientos_bienes = '".$idmovimiento."'
					ORDER BY 
							m.codigo_bien";
			$query = mysql_query($sql) or die ($sql.mysql_error());
			$idorganizacion = 0;
			while ($field = mysql_fetch_array($query)) {
				$valor_actual = $field['mejoras'];
				if ($idorganizacion != $field['idorganizacion']){
					$sql_organizacion = mysql_query("select * from organizacion where idorganizacion = '".$field['idorganizacion']."'");
					$bus_organizacion = mysql_fetch_array($sql_organizacion);
					$idorganizacion = $field['idorganizacion'];
					$pdf->SetFont('Arial', 'B', 11);
		  			$pdf->Cell(35, 5, utf8_decode($bus_organizacion["denominacion"]), 0, 1, 'L');
					$pdf->SetAligns(array('L', 'L', 'L', 'L', 'R'));
					$pdf->SetWidths(array(20, 20, 60, 70, 35));
					$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial', '', 8);
					$pdf->Ln(3);
				}
				$sql_nivel_organizacional = mysql_query("select * from niveles_organizacionales 
																		where idniveles_organizacionales = '".$field['idnivel_organizacional']."'");
				$bus_nivel_organizacional = mysql_fetch_array($sql_nivel_organizacional);
				$pdf->Row(array($field['codigo_bien'], $field['codigo_catalogo'], utf8_decode($field['denominacion_catalogo']), utf8_decode($bus_nivel_organizacional['denominacion']), number_format($field['mejoras'], 2, ',', '.')));
				$pdf->SetAligns(array('L', 'L', 'L', 'L', 'R'));
				$pdf->SetWidths(array(20, 20, 130));
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', '', 8);
				$pdf->Row(array('', '', utf8_decode($field['especificaciones']), ''));
				
				$pdf->SetAligns(array('L', 'L', 'L', 'L', 'R'));
				$pdf->SetWidths(array(20, 20, 60, 70, 35));
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', '', 8);	
			}
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			// AQUI VAN LAS FIRMAS DEL ACTA DE INCORPORACION 
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}
		
		
		if ($field_tipo["afecta"] == '2' and $field_tipo["origen_bien"] == 'existente' and $field_tipo["cambia_ubicacion"] == 'no') {
			//	Consulto e imprimo el inventario...
			$sql = "SELECT
							mbn.motivos,
							m.idorganizacion,
							m.idnivel_organizacion,
							d.codigo AS codigo_catalogo,
							d.denominacion AS denominacion_catalogo,
              				mbn.idmovimientos_bienes,
							m.codigo_bien as codigo_bien,
							m.especificaciones
					FROM
							movimientos_existentes_desincorporacion mbn
							INNER JOIN detalle_catalogo_bienes d ON (mbn.codigo_catalogo = d.iddetalle_catalogo_bienes)
							INNER JOIN muebles m ON (mbn.codigo_bien = m.idmuebles)
					where
							mbn.idmovimientos_bienes = '".$idmovimiento."'
					ORDER BY 
							m.codigo_bien";
			$query = mysql_query($sql) or die ($sql.mysql_error());
			$idorganizacion = 0;
			while ($field = mysql_fetch_array($query)) {
				$valor_actual = $field['mejoras'];
				if ($idorganizacion != $field['idorganizacion']){
					$sql_organizacion = mysql_query("select * from organizacion where idorganizacion = '".$field['idorganizacion']."'");
					$bus_organizacion = mysql_fetch_array($sql_organizacion);
					$idorganizacion = $field['idorganizacion'];
					$pdf->SetFont('Arial', 'B', 11);
		  			$pdf->Cell(35, 5, utf8_decode($bus_organizacion["denominacion"]), 0, 1, 'L');
					$pdf->SetAligns(array('L', 'L', 'L', 'L'));
					$pdf->SetWidths(array(20, 20, 60, 105));
					$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial', '', 8);
					$pdf->Ln(3);
				}
				$sql_nivel_organizacional = mysql_query("select * from niveles_organizacionales 
																		where idniveles_organizacionales = '".$field['idnivel_organizacion']."'");
				$bus_nivel_organizacional = mysql_fetch_array($sql_nivel_organizacional);
				$pdf->Row(array($field['codigo_bien'], $field['codigo_catalogo'], utf8_decode($field['denominacion_catalogo']), utf8_decode($bus_nivel_organizacional['denominacion'])));
				
				$pdf->SetAligns(array('L', 'L', 'L', 'L'));
				$pdf->SetWidths(array(20, 20, 130));
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', '', 8);
				$pdf->Row(array('', '', utf8_decode($field['motivos']), ''));
				
				$pdf->SetAligns(array('L', 'L', 'L', 'L'));
				$pdf->SetWidths(array(20, 20, 60, 105));
				$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Arial', '', 8);	
			}
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			// AQUI VAN LAS FIRMAS DEL ACTA DE DESINCORPORACION
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}
		}else{
		
		
			// IMPRIMO LAS DESINCORPORACIONES
			$sql_desincorpora = "SELECT * FROM movimientos_existentes_desincorporacion WHERE idmovimientos_bienes = '".$idmovimiento."'";
			$query_desincorpora = mysql_query($sql_desincorpora) or die ($sql_desincorpora.mysql_error());
			if (mysql_num_rows($query_desincorpora) != 0) $field_desincorpora = mysql_fetch_array($query_desincorpora);
			
			$sql = "SELECT * FROM tipo_movimiento_bienes WHERE idtipo_movimiento_bienes = '".$field_desincorpora["idtipo_movimiento"]."'";
			$query_tipo = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_tipo) != 0) $field_tipo = mysql_fetch_array($query_tipo);
			
			$pdf->SetFont('Arial', 'B', 11);
			$pdf->Cell(30, 5, utf8_decode('Movimiento: '), 0, 0, 'L'); 
			$pdf->SetFont('Arial', '', 11);
			$pdf->Cell(35, 5, $field_tipo["codigo"]." - ".utf8_decode($field_tipo["denominacion"]), 0, 1, 'L');
		
			
			$sql = "SELECT
							mbn.motivos,
							m.idorganizacion,
							mbn.idnivel_organizacional,
							d.codigo AS codigo_catalogo,
							d.denominacion AS denominacion_catalogo,
              				mbn.idmovimientos_bienes,
							m.codigo_bien as codigo_bien,
							m.especificaciones
					FROM
							movimientos_existentes_desincorporacion mbn
							INNER JOIN detalle_catalogo_bienes d ON (mbn.codigo_catalogo = d.iddetalle_catalogo_bienes)
							INNER JOIN muebles m ON (mbn.codigo_bien = m.idmuebles)
					where
							mbn.idmovimientos_bienes = '".$idmovimiento."'
					ORDER BY 
							m.codigo_bien";
			$query = mysql_query($sql) or die ($sql.mysql_error());
			$idorganizacion = 0;
			while ($field = mysql_fetch_array($query)) {
				$valor_actual = $field['mejoras'];
				if ($idorganizacion != $field['idorganizacion']){
					$sql_organizacion = mysql_query("select * from organizacion where idorganizacion = '".$field['idorganizacion']."'");
					$bus_organizacion = mysql_fetch_array($sql_organizacion);
					$idorganizacion = $field['idorganizacion'];
					$pdf->SetFont('Arial', 'B', 11);
		  			$pdf->Cell(35, 10, utf8_decode($bus_organizacion["denominacion"]), 0, 1, 'L');
					$pdf->SetAligns(array('L', 'L', 'L', 'L'));
					$pdf->SetWidths(array(20, 20, 60, 105));
					$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial', '', 8);
					$pdf->Ln(2);
				}
				$sql_nivel_organizacional = mysql_query("select * from niveles_organizacionales 
																		where idniveles_organizacionales = '".$field['idnivel_organizacional']."'");
				$bus_nivel_organizacional = mysql_fetch_array($sql_nivel_organizacional);
				$pdf->Row(array($field['codigo_bien'], $field['codigo_catalogo'], utf8_decode($field['denominacion_catalogo']), utf8_decode($bus_nivel_organizacional['denominacion'])));
				
					
			}
		
			$pdf->Ln(6);
		
			// IMPRIMO LAS INCORPORACIONES
			$sql_incorpora = "SELECT * FROM movimientos_existentes_incorporacion WHERE idmovimientos_bienes = '".$idmovimiento."'";
			$query_incorpora = mysql_query($sql_incorpora) or die ($sql_incorpora.mysql_error());
			if (mysql_num_rows($query_incorpora) != 0) $field_incorpora = mysql_fetch_array($query_incorpora);
			
			$sql = "SELECT * FROM tipo_movimiento_bienes WHERE idtipo_movimiento_bienes = '".$field_incorpora["idtipo_movimiento"]."'";
			$query_tipo = mysql_query($sql) or die ($sql.mysql_error());
			if (mysql_num_rows($query_tipo) != 0) $field_tipo = mysql_fetch_array($query_tipo);
			
			$pdf->SetFont('Arial', 'B', 11);
			$pdf->Cell(30, 5, utf8_decode('Movimiento: '), 0, 0, 'L'); 
			$pdf->SetFont('Arial', '', 11);
			$pdf->Cell(35, 5, $field_tipo["codigo"]." - ".utf8_decode($field_tipo["denominacion"]), 0, 1, 'L');
			
			$sql = "SELECT
							mbn.codigo_bien as idbien,
							mbn.mejoras,
							mbn.descripcion,
							mbn.idorganizacion,
							mbn.idnivel_organizacional,
							d.codigo AS codigo_catalogo,
							d.denominacion AS denominacion_catalogo,
              				mbn.idmovimientos_bienes,
							m.codigo_bien as codigo_bien,
							m.especificaciones
					FROM
							movimientos_existentes_incorporacion mbn
							INNER JOIN detalle_catalogo_bienes d ON (mbn.codigo_catalogo = d.iddetalle_catalogo_bienes)
							INNER JOIN muebles m ON (mbn.codigo_bien = m.idmuebles)
					where
							mbn.idmovimientos_bienes = '".$idmovimiento."'
					ORDER BY 
							m.codigo_bien";
			$query = mysql_query($sql) or die ($sql.mysql_error());
			$idorganizacion = 0;
			while ($field = mysql_fetch_array($query)) {
				$valor_actual = $field['mejoras'];
				if ($idorganizacion != $field['idorganizacion']){
					$sql_organizacion = mysql_query("select * from organizacion where idorganizacion = '".$field['idorganizacion']."'");
					$bus_organizacion = mysql_fetch_array($sql_organizacion);
					$idorganizacion = $field['idorganizacion'];
					$pdf->SetFont('Arial', 'B', 11);
		  			$pdf->Cell(35, 10, utf8_decode($bus_organizacion["denominacion"]), 0, 1, 'L');
					$pdf->SetAligns(array('L', 'L', 'L', 'L', 'R'));
					$pdf->SetWidths(array(20, 20, 60, 70, 35));
					$pdf->SetDrawColor(255, 255, 255); $pdf->SetFillColor(255, 255, 255); $pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial', '', 8);
					$pdf->Ln(2);
				}
				$sql_nivel_organizacional = mysql_query("select * from niveles_organizacionales 
																		where idniveles_organizacionales = '".$field['idnivel_organizacional']."'");
				$bus_nivel_organizacional = mysql_fetch_array($sql_nivel_organizacional);
				$pdf->Row(array($field['codigo_bien'], $field['codigo_catalogo'], utf8_decode($field['denominacion_catalogo']), utf8_decode($bus_nivel_organizacional['denominacion'])));
				
			}
			
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			// AQUI VAN LAS FIRMAS DEL ACTA DE TRASLADO
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			
		}
		break;
}
//	----------------------------------------
//	FIN CUERPO DE LOS REPORTES
//	----------------------------------------

$pdf->Output();
?>

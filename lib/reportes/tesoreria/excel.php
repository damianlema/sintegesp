<?php
$nombre_archivo = strtr($nombre_archivo, " ", "_"); $nombre_archivo=$nombre_archivo.".xls";
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: filename=\"".$nombre_archivo."\";");
session_start();
set_time_limit(-1);
require('../../../conf/conex.php');
Conectarse();
extract($_GET);
extract($_POST);
$ahora=date("d-m-Y H:i:s");
//	----------------------------------------------------
$nom_mes['01']="Enero";
$nom_mes['02']="Febrero";
$nom_mes['03']="Marzo";
$nom_mes['04']="Abril";
$nom_mes['05']="Mayo";
$nom_mes['06']="Junio";
$nom_mes['07']="Julio";
$nom_mes['08']="Agosto";
$nom_mes['09']="Septiembre";
$nom_mes['10']="Octubre";
$nom_mes['11']="Noviembre";
$nom_mes['12']="Diciembre";
//	----------------------------------------------------
$dias_mes['01']=31;
$dias_mes['03']=31;
$dias_mes['04']=30;
$dias_mes['05']=31;
$dias_mes['06']=30;
$dias_mes['07']=31;
$dias_mes['08']=31;
$dias_mes['09']=30;
$dias_mes['10']=31;
$dias_mes['11']=30;
$dias_mes['12']=31;
?>

<?php
//	----------------------------------------
//	CUERPO DE LOS REPORTES
//	----------------------------------------
switch ($nombre) {
	//	Relacion de Ingresos y Egresos...
	case "relacion_ingresos_egresos":
		$tr1="background-color:#999999; font-size:12px;";
		$tr2="font-size:12px; color:#000000; font-weight:bold;";
		$tr3="background-color:RGB(225, 255, 255); font-size:12px; color:#000000; font-weight:bold;";
		$tr4="font-size:12px; color:#000000; font-weight:bold;";
		$tr5="font-size:12px; color:#000000;";
		//----------------------------------------------------
		list($a, $m, $d)=SPLIT( '[/.-]', $desde); $fdesde=$d."/".$m."/".$a;
		list($a, $m, $d)=SPLIT( '[/.-]', $hasta); $fhasta=$d."/".$m."/".$a;
		$filtro="(ief.fecha>='$desde' AND ief.fecha<='$hasta') ";
		if ($tipo=="0" && $movimiento=="0" && $banco=="0" && $cuenta=="0") { $head=1; $w=205; $x=5; }
		elseif ($tipo!="0" && $movimiento!="0" && $banco!="0" && $cuenta!="0") { $head=2; $filtro.="AND ief.tipo='".$tipo."' AND tmb.idtipo_movimiento_bancario='".$movimiento."' AND ief.idbanco='".$banco."' AND ief.idcuentas_bancarias='".$cuenta."' "; $w=75; $x=70; }
		elseif ($tipo!="0" && $movimiento=="0" && $banco=="0" && $cuenta=="0") { $head=3; $filtro.="AND ief.tipo='".$tipo."'"; $w=205; $x=5; }
		elseif ($tipo!="0" && $movimiento!="0" && $banco=="0" && $cuenta=="0") { $head=4; $filtro.="AND ief.tipo='".$tipo."' AND tmb.idtipo_movimiento_bancario='".$movimiento."' "; $w=205; $x=5; }
		elseif ($tipo!="0" && $movimiento!="0" && $banco!="0" && $cuenta=="0") { $head=5; $filtro.="AND ief.tipo='".$tipo."' AND tmb.idtipo_movimiento_bancario='".$movimiento."' AND ief.idbanco='".$banco."' "; $w=125; $x=45; }
		//----------------------------------------------------
		$sql="SELECT 
					b.denominacion AS Banco, 
					cb.numero_cuenta, 
					ief.numero_documento, 
					ief.fecha, 
					ief.tipo, 
					ief.monto, 
					tmb.siglas, 
					tmb.denominacion AS Movimiento, 
					tmb.afecta 
				FROM 
					ingresos_egresos_financieros ief 
					INNER JOIN banco b ON (ief.idbanco=b.idbanco) 
					INNER JOIN cuentas_bancarias cb ON (ief.idcuentas_bancarias=cb.idcuentas_bancarias) 
					INNER JOIN tipo_movimiento_bancario tmb ON (ief.idtipo_movimiento=tmb.idtipo_movimiento_bancario) 
				WHERE $filtro 
					ORDER BY ief.fecha";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		//-------------------------------------------------
		echo "<table border='1'>";
		for ($i=1; $i<=$rows; $i++) {
			$field=mysql_fetch_array($query);
			//----------------------------------------------------
			list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha']); $fecha=$d."/".$m."/".$a;
			$monto=number_format($field['monto'], 2, ',', '');
			if ($field['tipo']=="egreso") { $suma-=$field['monto']; $sig="-("; $sigc=")"; } else { $suma+=$field['monto']; $sig=""; $sigc=""; }
			//----------------------------------------------------
			//	Filtro periodo
			if ($head==1) {
				if ($i==1) {
					echo "
					<tr><td>Desde:</td><td style='$tr4' align='left' colspan='6'>".$fdesde."</td></tr>
					<tr><td>Hasta:</td><td style='$tr4' align='left' colspan='6'>".$fhasta."</td></tr>";
					echo "
					<tr>
						<th width='150' style='$tr1'>Banco</th>
						<th width='100' style='$tr1'>Cuenta</th>
						<th width='75' style='$tr1'>Documento</th>
						<th width='75' style='$tr1'>Fecha</th>
						<th width='50' style='$tr1'>Tipo</th>
						<th width='50' style='$tr1'>Mov.</th>
						<th width='75' style='$tr1'>Monto</th>
					</tr>";
				}
				echo "
				<tr>
					<td style='$tr5' align='left'>".utf8_decode($field['Banco'])."</td>
					<td style='$tr5' align='left'>".$field['numero_cuenta']."</td>
					<td style='$tr5' align='left'>".$field['numero_documento']."</td>
					<td style='$tr5' align='center'>".$fecha."</td>
					<td style='$tr5' align='left'>".strtoupper($field['afecta'])."</td>
					<td style='$tr5' align='left'>".strtoupper($field['siglas'])."</td>
					<td style='$tr5' align='right'>".$sig.$monto.$sigc."</td>
				</tr>";
				if ($i==$rows) {
					$suma=number_format($suma, 2, ',', '');
					echo "<tr><td style='$tr4' align='right' colspan='7'>=DECIMAL(".$suma."; 2)</td></tr>";
				}
			}
			//	Filtro todos
			elseif ($head==2) {
				if ($i==1) {
					echo "
					<tr><td>Tipo:</td><td style='$tr4' align='left' colspan='2'>".strtoupper($field['afecta'])."</td></tr>
					<tr><td>Movimiento:</td><td style='$tr4' align='left' colspan='2'>".strtoupper($field['siglas'])."</td></tr>
					<tr><td>Banco:</td><td style='$tr4' align='left' colspan='2'>".utf8_decode($field['Banco'])."</td></tr>
					<tr><td>Cuenta:</td><td style='$tr4' align='left' colspan='2'>".$field['numero_cuenta']."</td></tr>
					<tr><td>Desde:</td><td style='$tr4' align='left' colspan='2'>".$fdesde."</td></tr>
					<tr><td>Hasta:</td><td style='$tr4' align='left' colspan='2'>".$fhasta."</td></tr>";
					echo "
					<tr>
						<th width='100' style='$tr1'>Documento</th>
						<th width='100' style='$tr1'>Fecha</th>
						<th width='100' style='$tr1'>Monto</th>
					</tr>";
				}
				echo "
				<tr>
					<td style='$tr5' align='left'>".$field['numero_documento']."</td>
					<td style='$tr5 align='center'>".$fecha."</td>
					<td style='$tr5' align='right'>".$sig.$monto.$sigc."</td>
				</tr>";
				if ($i==$rows) {
					$suma=number_format($suma, 2, ',', '');
					echo "<tr><td style='$tr4' align='right' colspan='3'>=DECIMAL(".$suma."; 2)</td></tr>";
				}
			}
			//	Filtro tipo
			elseif ($head==3) {
				if ($i==1) {
					echo "
					<tr><td>Tipo:</td><td style='$tr4' align='left' colspan='5'>".strtoupper($field['afecta'])."</td></tr>
					<tr><td>Desde:</td><td style='$tr4' align='left' colspan='5'>".$fdesde."</td></tr>
					<tr><td>Hasta:</td><td style='$tr4' align='left' colspan='5'>".$fhasta."</td></tr>";
					echo "
					<tr>
						<th width='150' style='$tr1'>Banco</th>
						<th width='100' style='$tr1'>Cuenta</th>
						<th width='75' style='$tr1'>Documento</th>
						<th width='75' style='$tr1'>Fecha</th>
						<th width='50' style='$tr1'>Mov.</th>
						<th width='75' style='$tr1'>Monto</th>
					</tr>";
				}
				echo "
				<tr>
					<td style='$tr5' align='left'>".utf8_decode($field['Banco'])."</td>
					<td style='$tr5' align='left'>".$field['numero_cuenta']."</td>
					<td style='$tr5' align='left'>".$field['numero_documento']."</td>
					<td style='$tr5' align='center'>".$fecha."</td>
					<td style='$tr5' align='left'>".strtoupper($field['siglas'])."</td>
					<td style='$tr5' align='right'>".$sig.$monto.$sigc."</td>
				</tr>";
				if ($i==$rows) {
					$suma=number_format($suma, 2, ',', '');
					echo "<tr><td style='$tr4' align='right' colspan='6'>=DECIMAL(".$suma."; 2)</td></tr>";
				}
			}
			//	Filtro tipo + movimiento
			elseif ($head==4) {
				if ($i==1) {
					echo "
					<tr><td>Tipo:</td><td style='$tr4' align='left' colspan='4'>".strtoupper($field['afecta'])."</td></tr>
					<tr><td>Movimiento:</td><td style='$tr4' align='left' colspan='4'>".strtoupper($field['siglas'])."</td></tr>
					<tr><td>Desde:</td><td style='$tr4' align='left' colspan='4'>".$fdesde."</td></tr>
					<tr><td>Hasta:</td><td style='$tr4' align='left' colspan='4'>".$fhasta."</td></tr>";
					echo "
					<tr>
						<th width='150' style='$tr1'>Banco</th>
						<th width='100' style='$tr1'>Cuenta</th>
						<th width='75' style='$tr1'>Documento</th>
						<th width='75' style='$tr1'>Fecha</th>
						<th width='75' style='$tr1'>Monto</th>
					</tr>";
				}
				echo "
				<tr>
					<td style='$tr5' align='left'>".utf8_decode($field['Banco'])."</td>
					<td style='$tr5' align='left'>".$field['numero_cuenta']."</td>
					<td style='$tr5' align='left'>".$field['numero_documento']."</td>
					<td style='$tr5' align='center'>".$fecha."</td>
					<td style='$tr5' align='right'>".$sig.$monto.$sigc."</td>
				</tr>";
				if ($i==$rows) {
					$suma=number_format($suma, 2, ',', '');
					echo "<tr><td style='$tr4' align='right' colspan='5'>=DECIMAL(".$suma."; 2)</td></tr>";
				}
			}
			//	Filtro tipo + movimiento + banco 
			elseif ($head==5) {
				if ($i==1) {
					echo "
					<tr><td>Tipo:</td><td style='$tr4' align='left' colspan='3'>".strtoupper($field['afecta'])."</td></tr>
					<tr><td>Movimiento:</td><td style='$tr4' align='left' colspan='3'>".strtoupper($field['siglas'])."</td></tr>
					<tr><td>Banco:</td><td style='$tr4' align='left' colspan='3'>".utf8_decode($field['Banco'])."</td></tr>
					<tr><td>Desde:</td><td style='$tr4' align='left' colspan='3'>".$fdesde."</td></tr>
					<tr><td>Hasta:</td><td style='$tr4' align='left' colspan='3'>".$fhasta."</td></tr>";
					echo "
					<tr>
						<th width='100' style='$tr1'>Cuenta</th>
						<th width='75' style='$tr1'>Documento</th>
						<th width='75' style='$tr1'>Fecha</th>
						<th width='75' style='$tr1'>Monto</th>
					</tr>";
				}
				echo "
				<tr>
					<td style='$tr5' align='left'>".$field['numero_cuenta']."</td>
					<td style='$tr5' align='left'>".$field['numero_documento']."</td>
					<td style='$tr5' align='center'>".$fecha."</td>
					<td style='$tr5' align='right'>".$sig.$monto.$sigc."</td>
				</tr>";
				if ($i==$rows) {
					$suma=number_format($suma, 2, ',', '');
					echo "<tr><td style='$tr4' align='right' colspan='4'>=DECIMAL(".$suma."; 2)</td></tr>";
				}
			}
		}
		//----------------------------------------------------
		echo "</table>";
		break;
	
	//	Relacion de Cheques...
	case "relacion_cheques":
		$tr1="background-color:#999999; font-size:12px;";
		$tr2="font-size:12px; color:#000000; font-weight:bold;";
		$tr3="background-color:RGB(225, 255, 255); font-size:12px; color:#000000; font-weight:bold;";
		$tr4="font-size:12px; color:#000000; font-weight:bold;";
		$tr5="font-size:12px; color:#000000;";
		//----------------------------------------------------
		list($a, $m, $d)=SPLIT( '[/.-]', $desde); $fdesde=$d."/".$m."/".$a;
		list($a, $m, $d)=SPLIT( '[/.-]', $hasta); $fhasta=$d."/".$m."/".$a;
		$filtro=" WHERE (b.idbanco='".$banco."' AND p.idcuenta_bancaria='".$cuenta."') ";
		if ($estado=="0" && $desde=="" && $hasta=="" && $idbeneficiario=="") { $head=1; $w=205; $x=5; }
		elseif ($estado!="0" && $desde!="" && $hasta!="" && $idbeneficiario!="") { $head=2; $filtro.="AND p.estado='".$estado."' AND p.fecha_cheque>='".$desde."' AND p.fecha_cheque<='".$hasta."' AND op.idbeneficiarios='".$idbeneficiario."' "; $w=95; $x=65; }
		elseif ($estado!="0" && $desde=="" && $hasta=="" && $idbeneficiario=="") { $head=3; $filtro.="AND p.estado='".$estado."'"; $w=185; $x=10; }
		elseif ($estado=="0" && $desde=="" && $hasta=="" && $idbeneficiario!="") { $head=4; $filtro.="AND op.idbeneficiarios='".$idbeneficiario."' "; $w=115; $x=50; }
		elseif ($estado=="0" && $desde!="" && $hasta!="" && $idbeneficiario=="") { $head=5; $filtro.="AND p.fecha_cheque>='".$desde."' AND p.fecha_cheque<='".$hasta."' "; $w=205; $x=5; }
		elseif ($estado!="0" && $desde=="" && $hasta=="" && $idbeneficiario!="") { $head=6; $filtro.="AND p.estado='".$estado."' AND op.idbeneficiarios='".$idbeneficiario."' "; $w=205; $x=5; }
		elseif ($estado!="0" && $desde!="" && $hasta!="" && $idbeneficiario=="") { $head=7; $filtro.="AND p.estado='".$estado."' AND p.fecha_cheque>='".$desde."' AND p.fecha_cheque<='".$hasta."' "; $w=205; $x=5; }
		elseif ($estado=="0" && $desde!="" && $hasta!="" && $idbeneficiario!="") { $head=8; $filtro.="AND op.idbeneficiarios='".$idbeneficiario."' AND p.fecha_cheque>='".$desde."' AND p.fecha_cheque<='".$hasta."' "; $w=205; $x=5; }
		//----------------------------------------------------
		$sql="SELECT 
					p.monto_cheque,
					p.beneficiario, 
					p.estado, 
					p.numero_cheque, 
					p.fecha_cheque, 
					c.numero_cuenta, 
					b.denominacion AS Banco, 
					op.idbeneficiarios,
					op.justificacion,
					op.numero_orden 
				FROM 
					pagos_financieros p 
					INNER JOIN cuentas_bancarias c ON (p.idcuenta_bancaria=c.idcuentas_bancarias) 
					INNER JOIN orden_pago op ON (p.idorden_pago=op.idorden_pago) 
					INNER JOIN banco b ON (c.idbanco=b.idbanco) $filtro";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		//-------------------------------------------------
		echo "<table border='1'>";
		for ($i=1; $i<=$rows; $i++) {
			$field=mysql_fetch_array($query);
			//----------------------------------------------------
			list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_cheque']); $fecha=$d."/".$m."/".$a;
			$monto=number_format($field['monto_cheque'], 2, ',', '');
			$suma+=$field['monto_cheque'];
			//----------------------------------------------------
			//	Filtro Banco + Cuenta Bancaria
			if ($head==1) {
				if ($i==1) {
					echo "
					<tr><td>Banco:</td><td style='$tr4' align='left' colspan='5'>".utf8_decode($field['Banco'])."</td></tr>
					<tr><td>Cuenta:</td><td style='$tr4' align='left' colspan='5'>".strtoupper($field['numero_cuenta'])."</td></tr>";
					echo "
					<tr>
						<th width='100' style='$tr1'>Nro. Cheque</th>
						<th width='100' style='$tr1'>Fecha</th>
						<th width='350' style='$tr1'>Beneficiario</th>
						<th width='100' style='$tr1'>OP</th>
						<th width='100' style='$tr1'>Estado</th>
						<th width='150' style='$tr1'>Monto</th>
					</tr>";
				}
				echo "
				<tr>
					<td style='$tr5' align='left'>".$field['numero_cheque']."</td>
					<td style='$tr5' align='center'>".$fecha."</td>
					<td style='$tr5' align='left'>".utf8_decode($field['beneficiario'])."</td>
					<td style='$tr5' align='center'>".$field['numero_orden']."</td>
					<td style='$tr5' align='center'>".strtoupper($field['estado'])."</td>
					<td style='$tr5' align='right'>".$monto."</td>
				</tr>";
				if ($concepto == 'si'){
					echo "
					<tr>
						<td style='$tr5' align='left'>&nbsp;</td>
						<td style='$tr5' align='center'>&nbsp;</td>
						<td style='$tr5' align='left'>".utf8_decode($field['justificacion'])."</td>
						<td style='$tr5' align='center'>&nbsp;</td>
						<td style='$tr5' align='right'>&nbsp;</td>
						<td style='$tr5' align='right'>&nbsp;</td>
					</tr>";
				}
				if ($i==$rows) {
					$suma=number_format($suma, 2, ',', '');
					echo "<tr><td style='$tr4' align='right' colspan='5'>=DECIMAL(".$suma."; 2)</td></tr>";
				}
			}
			//	Filtro todos
			elseif ($head==2) {
				if ($i==1) {
					echo "
					<tr><td>Banco:</td><td style='$tr4' align='left' colspan='3'>".utf8_decode($field['Banco'])."</td></tr>
					<tr><td>Cuenta:</td><td style='$tr4' align='left' colspan='3'>".strtoupper($field['numero_cuenta'])."</td></tr>
					<tr><td>Beneficiario:</td><td style='$tr4' align='left' colspan='3'>".utf8_decode($field['beneficiario'])."</td></tr>
					<tr><td>Estado:</td><td style='$tr4' align='left' colspan='3'>".strtoupper($field['estado'])."</td></tr>
					<tr><td>Desde:</td><td style='$tr4' align='left' colspan='3'>".$fdesde."</td></tr>
					<tr><td>Hasta:</td><td style='$tr4' align='left' colspan='3'>".$fhasta."</td></tr>";
					echo "
					<tr>
						<th width='100' style='$tr1'>Nro. Cheque</th>
						<th width='100' style='$tr1'>Fecha</th>
						<th width='100' style='$tr1'>OP</th>
						<th width='150' style='$tr1'>Monto</th>
						
					</tr>";
				}
				echo "
				<tr>
					<td style='$tr5' align='left'>".$field['numero_cheque']."</td>
					<td style='$tr5' align='center'>".$fecha."</td>
					<td style='$tr5' align='center'>".$field['numero_orden']."</td>
					<td style='$tr5' align='right'>".$monto."</td>
				</tr>";
				if ($concepto == 'si'){
					echo "
					<tr>
						<td style='$tr5' align='left'>&nbsp;</td>
						<td style='$tr5' align='center'>&nbsp;</td>
						<td style='$tr5' align='left'>".utf8_decode($field['justificacion'])."</td>
						<td style='$tr5' align='center'>&nbsp;</td>
						<td style='$tr5' align='right'>&nbsp;</td>
						<td style='$tr5' align='right'>&nbsp;</td>
					</tr>";
				}
				if ($i==$rows) {
					$suma=number_format($suma, 2, ',', '');
					echo "<tr><td style='$tr4' align='right' colspan='3'>=DECIMAL(".$suma."; 2)</td></tr>";
				}
			}
			//	Filtro Banco + Cuenta Bancaria + Estado
			elseif ($head==3) {
				if ($i==1) {
					echo "
					<tr><td>Banco:</td><td style='$tr4' align='left' colspan='4'>".utf8_decode($field['Banco'])."</td></tr>
					<tr><td>Cuenta:</td><td style='$tr4' align='left' colspan='4'>".strtoupper($field['numero_cuenta'])."</td></tr>
					<tr><td>Estado:</td><td style='$tr4' align='left' colspan='4'>".strtoupper($field['estado'])."</td></tr>";
					echo "
					<tr>
						<th width='100' style='$tr1'>Nro. Cheque</th>
						<th width='100' style='$tr1'>Fecha</th>
						<th width='350' style='$tr1'>Beneficiario</th>
						<th width='100' style='$tr1'>OP</th>
						<th width='150' style='$tr1'>Monto</th>
					</tr>";
				}
				echo "
				<tr>
					<td style='$tr5' align='left'>".$field['numero_cheque']."</td>
					<td style='$tr5' align='center'>".$fecha."</td>
					<td style='$tr5' align='left'>".utf8_decode($field['beneficiario'])."</td>
					<td style='$tr5' align='center'>".$field['numero_orden']."</td>
					<td style='$tr5' align='right'>".$monto."</td>
				</tr>";
				if ($concepto == 'si'){
					echo "
					<tr>
						<td style='$tr5' align='left'>&nbsp;</td>
						<td style='$tr5' align='center'>&nbsp;</td>
						<td style='$tr5' align='left'>".utf8_decode($field['justificacion'])."</td>
						<td style='$tr5' align='center'>&nbsp;</td>
						<td style='$tr5' align='right'>&nbsp;</td>
						<td style='$tr5' align='right'>&nbsp;</td>
					</tr>";
				}
				if ($i==$rows) {
					$suma=number_format($suma, 2, ',', '');
					echo "<tr><td style='$tr4' align='right' colspan='4'>=DECIMAL(".$suma."; 2)</td></tr>";
				}
			}
			//	Filtro Banco + Cuenta Bancaria + Beneficiario
			elseif ($head==4) {
				if ($i==1) {
					echo "
					<tr><td>Banco:</td><td style='$tr4' align='left' colspan='4'>".utf8_decode($field['Banco'])."</td></tr>
					<tr><td>Cuenta:</td><td style='$tr4' align='left' colspan='4'>".strtoupper($field['numero_cuenta'])."</td></tr>
					<tr><td>Beneficiario:</td><td style='$tr4' align='left' colspan='4'>".utf8_decode($field['beneficiario'])."</td></tr>";
					echo "
					<tr>
						<th width='100' style='$tr1'>Nro. Cheque</th>
						<th width='100' style='$tr1'>Fecha</th>
						<th width='100' style='$tr1'>OP</th>
						<th width='100' style='$tr1'>Estado</th>
						<th width='150' style='$tr1'>Monto</th>
					</tr>";
				}
				echo "
				<tr>
					<td style='$tr5' align='left'>".$field['numero_cheque']."</td>
					<td style='$tr5' align='center'>".$fecha."</td>
					<td style='$tr5' align='center'>".$field['numero_orden']."</td>
					<td style='$tr5' align='center'>".strtoupper($field['estado'])."</td>
					<td style='$tr5' align='right'>".$monto."</td>
				</tr>";
				if ($concepto == 'si'){
					echo "
					<tr>
						<td style='$tr5' align='left'>&nbsp;</td>
						<td style='$tr5' align='center'>&nbsp;</td>
						<td style='$tr5' align='left'>".utf8_decode($field['justificacion'])."</td>
						<td style='$tr5' align='center'>&nbsp;</td>
						<td style='$tr5' align='right'>&nbsp;</td>
						<td style='$tr5' align='right'>&nbsp;</td>
					</tr>";
				}
				if ($i==$rows) {
					$suma=number_format($suma, 2, ',', '');
					echo "<tr><td style='$tr4' align='right' colspan='4'>=DECIMAL(".$suma."; 2)</td></tr>";
				}
			}
			//	Filtro Banco + Cuenta Bancaria + Fecha
			elseif ($head==5) {
				if ($i==1) {
					echo "
					<tr><td>Banco:</td><td style='$tr4' align='left' colspan='5'>".utf8_decode($field['Banco'])."</td></tr>
					<tr><td>Cuenta:</td><td style='$tr4' align='left' colspan='5'>".strtoupper($field['numero_cuenta'])."</td></tr>
					<tr><td>Desde:</td><td style='$tr4' align='left' colspan='5'>".$fdesde."</td></tr>
					<tr><td>Hasta:</td><td style='$tr4' align='left' colspan='5'>".$fhasta."</td></tr>";
					echo "
					<tr>
						<th width='100' style='$tr1'>Nro. Cheque</th>
						<th width='100' style='$tr1'>Fecha</th>
						<th width='350' style='$tr1'>Beneficiario</th>
						<th width='100' style='$tr1'>OP</th>
						<th width='100' style='$tr1'>Estado</th>
						<th width='150' style='$tr1'>Monto</th>
					</tr>";
				}
				echo "
				<tr>
					<td style='$tr5' align='left'>".$field['numero_cheque']."</td>
					<td style='$tr5' align='center'>".$fecha."</td>
					<td style='$tr5' align='left'>".utf8_decode($field['beneficiario'])."</td>
					<td style='$tr5' align='center'>".$field['numero_orden']."</td>
					<td style='$tr5' align='center'>".strtoupper($field['estado'])."</td>
					<td style='$tr5' align='right'>".$monto."</td>
				</tr>";
				if ($concepto == 'si'){
					echo "
					<tr>
						<td style='$tr5' align='left'>&nbsp;</td>
						<td style='$tr5' align='center'>&nbsp;</td>
						<td style='$tr5' align='left'>".utf8_decode($field['justificacion'])."</td>
						<td style='$tr5' align='center'>&nbsp;</td>
						<td style='$tr5' align='right'>&nbsp;</td>
						<td style='$tr5' align='right'>&nbsp;</td>
					</tr>";
				}
				if ($i==$rows) {
					$suma=number_format($suma, 2, ',', '');
					echo "<tr><td style='$tr4' align='right' colspan='5'>=DECIMAL(".$suma."; 2)</td></tr>";
				}
			}
			//	Filtro Banco + Cuenta Bancaria + Estado + Beneficiario
			elseif ($head==6) {
				if ($i==1) {
					echo "
					<tr><td>Banco:</td><td style='$tr4' align='left' colspan='3'>".utf8_decode($field['Banco'])."</td></tr>
					<tr><td>Cuenta:</td><td style='$tr4' align='left' colspan='3'>".strtoupper($field['numero_cuenta'])."</td></tr>
					<tr><td>Estado:</td><td style='$tr4' align='left' colspan='3'>".strtoupper($field['estado'])."</td></tr>
					<tr><td>Beneficiario:</td><td style='$tr4' align='left' colspan='3'>".utf8_decode($field['beneficiario'])."</td></tr>";
					echo "
					<tr>
						<th width='100' style='$tr1'>Nro. Cheque</th>
						<th width='100' style='$tr1'>Fecha</th>
						<th width='100' style='$tr1'>OP</th>
						<th width='150' style='$tr1'>Monto</th>
					</tr>";
				}
				echo "
				<tr>
					<td style='$tr5' align='left'>".$field['numero_cheque']."</td>
					<td style='$tr5' align='center'>".$fecha."</td>
					<td style='$tr5' align='center'>".$field['numero_orden']."</td>
					<td style='$tr5' align='right'>".$monto."</td>
				</tr>";
				if ($concepto == 'si'){
					echo "
					<tr>
						<td style='$tr5' align='left'>&nbsp;</td>
						<td style='$tr5' align='center'>&nbsp;</td>
						<td style='$tr5' align='left'>".utf8_decode($field['justificacion'])."</td>
						<td style='$tr5' align='center'>&nbsp;</td>
						<td style='$tr5' align='right'>&nbsp;</td>
						<td style='$tr5' align='right'>&nbsp;</td>
					</tr>";
				}
				if ($i==$rows) {
					$suma=number_format($suma, 2, ',', '');
					echo "<tr><td style='$tr4' align='right' colspan='3'>=DECIMAL(".$suma."; 2)</td></tr>";
				}
			}
			//	Filtro Banco + Cuenta Bancaria + Estado + Fecha
			elseif ($head==7) {
				if ($i==1) {
					echo "
					<tr><td>Banco:</td><td style='$tr4' align='left' colspan='4'>".utf8_decode($field['Banco'])."</td></tr>
					<tr><td>Cuenta:</td><td style='$tr4' align='left' colspan='4'>".strtoupper($field['numero_cuenta'])."</td></tr>
					<tr><td>Estado:</td><td style='$tr4' align='left' colspan='4'>".utf8_decode($field['Estado'])."</td></tr>
					<tr><td>Desde:</td><td style='$tr4' align='left' colspan='4'>".$fdesde."</td></tr>
					<tr><td>Hasta:</td><td style='$tr4' align='left' colspan='4'>".$fhasta."</td></tr>";
					echo "
					<tr>
						<th width='100' style='$tr1'>Nro. Cheque</th>
						<th width='100' style='$tr1'>Fecha</th>
						<th width='350' style='$tr1'>Beneficiario</th>
						<th width='100' style='$tr1'>OP</th>
						<th width='150' style='$tr1'>Monto</th>
					</tr>";
				}
				echo "
				<tr>
					<td style='$tr5' align='left'>".$field['numero_cheque']."</td>
					<td style='$tr5' align='center'>".$fecha."</td>
					<td style='$tr5' align='left'>".utf8_decode($field['beneficiario'])."</td>
					<td style='$tr5' align='center'>".$field['numero_orden']."</td>
					<td style='$tr5' align='right'>".$monto."</td>
				</tr>";
				if ($concepto == 'si'){
					echo "
					<tr>
						<td style='$tr5' align='left'>&nbsp;</td>
						<td style='$tr5' align='center'>&nbsp;</td>
						<td style='$tr5' align='left'>".utf8_decode($field['justificacion'])."</td>
						<td style='$tr5' align='center'>&nbsp;</td>
						<td style='$tr5' align='right'>&nbsp;</td>
						<td style='$tr5' align='right'>&nbsp;</td>
					</tr>";
				}
				if ($i==$rows) {
					$suma=number_format($suma, 2, ',', '');
					echo "<tr><td style='$tr4' align='right' colspan='4'>=DECIMAL(".$suma."; 2)</td></tr>";
				}
			}
			//	Filtro Banco + Cuenta Bancaria + Beneficiario + Fecha
			elseif ($head==8) {
				if ($i==1) {
					echo "
					<tr><td>Banco:</td><td style='$tr4' align='left' colspan='3'>".utf8_decode($field['Banco'])."</td></tr>
					<tr><td>Cuenta:</td><td style='$tr4' align='left' colspan='3'>".strtoupper($field['numero_cuenta'])."</td></tr>
					<tr><td>Beneficiario:</td><td style='$tr4' align='left' colspan='3'>".utf8_decode($field['beneficiario'])."</td></tr>
					<tr><td>Desde:</td><td style='$tr4' align='left' colspan='3'>".$fdesde."</td></tr>
					<tr><td>Hasta:</td><td style='$tr4' align='left' colspan='3'>".$fhasta."</td></tr>";
					echo "
					<tr>
						<th width='100' style='$tr1'>Fecha</th>
						<th width='100' style='$tr1'>OP</th>
						<th width='100' style='$tr1'>Estado</th>
						<th width='150' style='$tr1'>Monto</th>
					</tr>";
				}
				echo "
				<tr>
					<td style='$tr5' align='center'>".$fecha."</td>
					<td style='$tr5' align='center'>".$field['numero_orden']."</td>
					<td style='$tr5' align='center'>".strtoupper($field['estado'])."</td>
					<td style='$tr5' align='right'>".$monto."</td>
				</tr>";
				if ($concepto == 'si'){
					echo "
					<tr>
						<td style='$tr5' align='left'>&nbsp;</td>
						<td style='$tr5' align='center'>&nbsp;</td>
						<td style='$tr5' align='left'>".utf8_decode($field['justificacion'])."</td>
						<td style='$tr5' align='center'>&nbsp;</td>
						<td style='$tr5' align='right'>&nbsp;</td>
						<td style='$tr5' align='right'>&nbsp;</td>
					</tr>";
				}
				if ($i==$rows) {
					$suma=number_format($suma, 2, ',', '');
					echo "<tr><td style='$tr4' align='right' colspan='3'>=DECIMAL(".$suma."; 2)</td></tr>";
				}
			}
		}
		//----------------------------------------------------
		echo "</table>";
		break;
	
	//	Relacion Cheques-OP...
	case "relacion_cheques_op":
		$tr1="background-color:#999999; font-size:12px;";
		$tr2="font-size:12px; color:#000000; font-weight:bold;";
		$tr3="background-color:RGB(225, 255, 255); font-size:12px; color:#000000; font-weight:bold;";
		$tr4="font-size:12px; color:#000000; font-weight:bold;";
		$tr5="font-size:12px; color:#000000;";
		//----------------------------------------------------
		$sql="SELECT 
					pf.idpagos_financieros, 
					pf.idorden_pago, 
					pf.monto_cheque, 
					pf.beneficiario, 
					pf.numero_cheque, 
					o.numero_orden, 
					pf.fecha_cheque 
				FROM 
					pagos_financieros pf 
					INNER JOIN orden_pago o ON (pf.idorden_pago=o.idorden_pago) 
				WHERE pf.idorden_pago='".$id_emision_pago."'";
		$query=mysql_query($sql) or die ($sql.mysql_error());
		$rows=mysql_num_rows($query);
		echo "<table border='1'>";
		for ($i=1; $i<=$rows; $i++) {
			$field=mysql_fetch_array($query);
			if ($i==1) {
				echo "
				<tr><td>Nro. Orden de Pago:</td><td style='$tr4' align='left' colspan='3'>".$field['numero_orden']."</td></tr>";
				echo "
				<tr>
					<th width='125' style='$tr1'>Nro. Cheque</th>
					<th width='100' style='$tr1'>Fecha</th>
					<th width='400' style='$tr1'>Beneficiario</th>
					<th width='125' style='$tr1'>Monto</th>
				</tr>";
			}
			//----------------------------------------------------	
			list($a, $m, $d)=SPLIT( '[/.-]', $field['fecha_cheque']); $fecha=$d."/".$m."/".$a;
			$monto=number_format($field['monto_cheque'], 2, ',', '');
			$suma+=$field['monto_cheque'];
			//----------------------------------------------------
			echo "
			<tr>
				<td style='$tr5' align='left'>".$field['numero_cheque']."</td>
				<td style='$tr5' align='left'>".$fecha."</td>
				<td style='$tr5' align='left'>".utf8_decode($field['beneficiario'])."</td>
				<td style='$tr5' align='right'>".$monto."</td>
			</tr>";
			if ($i==$rows) {
				$suma=number_format($suma, 2, ',', '');
				echo "<tr><td style='$tr4' align='right' colspan='4'>=DECIMAL(".$suma."; 2)</td></tr>";
			}
		}
		echo "</table>";
		break;
	
	//	Conciliacion...
	case "conciliacion":
		$tr1="background-color:#999999; font-size:12px;";
		$tr2="font-size:12px; color:#000000; font-weight:bold;";
		$tr3="background-color:RGB(225, 255, 255); font-size:12px; color:#000000; font-weight:bold;";
		$tr4="font-size:12px; color:#000000; font-weight:bold;";
		$tr5="font-size:12px; color:#000000;";
		//	----------------------------------------------------
		$a=(int) $anio; 
		$m=(int) $mes; $mm = --$m; 
		if ($mm==0) { //--$a; 
			$alafecha="01/01/$a"; 
			$fapertura="$a-01-01"; 
		} 
		elseif ($m==2) { if ($a%4==0) $d=29; else $d=28; $alafecha="$d/02/$a"; $fapertura="$a-02-$d"; }
		else {
			if ($m<10) $m="0$m";
			$d=$dias_mes[$m];
			$alafecha="$d/$m/$a";
			$fapertura="$a-$m-$d";
		}
		//	----------------------------------------------------
		//	----------------------------------------------------
		list($d, $m, $a)=SPLIT( '[/.-]', $alafecha); 
		$alafecha_hasta = "$a-$m-$d";
		$alafecha_desde = "$a-$m-01"; 
		//	----------------------------------------------------
		if ($anio%4==0) $dias_mes['02']=29; else $dias_mes['02']=28;
		$d=$dias_mes[$mes];
		$desde="$anio-$mes-01"; $hasta="$anio-$mes-$d"; $fhasta="$d-$mes-$anio";
		//	----------------------------------------------------
		//	Obtengo la cabecera
		$sql="SELECT
					c.numero_cuenta,
					c.uso_cuenta,
					c.monto_apertura,
					c.fecha_apertura,
					b.denominacion AS Banco,
					t.denominacion AS Cuenta
				FROM
					cuentas_bancarias c
					INNER JOIN banco b ON (c.idbanco=b.idbanco)
					INNER JOIN tipo_cuenta_bancaria t ON (c.idtipo_cuenta=t.idtipo_cuenta)
				WHERE
					c.idcuentas_bancarias='".$cuenta."'";
		$query_head=mysql_query($sql) or die ($sql.mysql_error());
		$rows_head=mysql_num_rows($query_head);
		if ($rows_head!=0) $field_head=mysql_fetch_array($query_head);
		echo "<table border='1'>";
		//	----------------------------------------------------
		//	Imprimo el head
		echo "
		<tr><td style='$tr4' align='left' colspan='5'>".utf8_decode('BANCO '.$field_head['Banco'].' CTA. '.$field_head['Cuenta'].' Nº. '.$field_head['numero_cuenta'])."</td></tr>
		<tr><td style='$tr4' align='left' colspan='5'>DENOMINACION ".$field_head['uso_cuenta']."</td></tr>
		<tr><td style='$tr4' align='left' colspan='5'>Periodo: ".strtoupper($nom_mes[$mes]).' '.$anio."</td></tr>";
		//	-----------------------------------------------------
		if ($field_head['fecha_apertura']==$desde){
			$sum_saldo = $field_head["monto_apertura"];
		}else{
			//	Si la fecha de apertura es menor que el mes seleccionado entonces imprimo el reporte...
			//	----------------------------------------------------
			//	Obtengo la suma de los ingresos a la fecha
			$sql = "SELECT
						SUM(i.monto) AS Monto,
						t.denominacion As Denominacion,
						(SELECT SUM(p.monto_cheque) AS Monto
							FROM pagos_financieros p
								WHERE
									p.idcuenta_bancaria='".$cuenta."'
									AND p.estado='anulado'
									AND p.fecha_anulacion>='$alafecha_desde' AND p.fecha_anulacion<='$alafecha_hasta') AS MontoNulos
					FROM
						ingresos_egresos_financieros i
						INNER JOIN tipo_movimiento_bancario t ON (i.idtipo_movimiento=t.idtipo_movimiento_bancario)
					WHERE
						i.idcuentas_bancarias='".$cuenta."'
						AND i.idbanco='".$banco."'
						AND i.tipo='ingreso'
						AND i.fecha>='$alafecha_desde' AND i.fecha<='$alafecha_hasta'
					GROUP BY i.idtipo_movimiento";
			$query_acredita_alafecha = mysql_query($sql) or die ($sql.mysql_error());
			$field_acredita_alafecha = mysql_fetch_array($query_acredita_alafecha);
			$total_acredita_alafecha = $field_acredita_alafecha['Monto'] + $field_acredita_alafecha['MontoNulos'];
			
			
				// OBTENGO LA SUMA DE LOS INGRESOS
				$sum_saldo = $field_head["monto_apertura"];
				$sql_suma="SELECT
						SUM(i.monto) AS Monto
					FROM
						ingresos_egresos_financieros i
						INNER JOIN tipo_movimiento_bancario t ON (i.idtipo_movimiento=t.idtipo_movimiento_bancario)
					WHERE
						i.idcuentas_bancarias='".$cuenta."'
						AND i.idbanco='".$banco."'
						AND i.tipo='ingreso'
						AND i.fecha>='".$field_head['fecha_apertura']."' 
						AND i.fecha<'".$desde."'
						AND i.estado <> 'anulado'";
						
				$query_suma=mysql_query($sql_suma) or die ($sql_suma.mysql_error());
				$field_suma=mysql_fetch_array($query_suma);
				$sum_saldo += $field_suma["Monto"];
				
				// RESTO LOS EGRESOS
				$sql_suma="SELECT
						SUM(i.monto) AS Monto
					FROM
						ingresos_egresos_financieros i
						INNER JOIN tipo_movimiento_bancario t ON (i.idtipo_movimiento=t.idtipo_movimiento_bancario)
					WHERE
						i.idcuentas_bancarias='".$cuenta."'
						AND i.idbanco='".$banco."'
						AND i.tipo='egreso'
						AND i.fecha>='".$field_head['fecha_apertura']."' 
						AND i.fecha<'".$desde."'
						AND i.estado <> 'anulado'";
						
				$query_suma=mysql_query($sql_suma) or die ($sql_suma.mysql_error());
				$field_suma=mysql_fetch_array($query_suma);
				$sum_saldo -= $field_suma["Monto"];
				
				//OBTENGO LA SUMA DE LOS CHEQUES CONCILIADOS
				
				$sql_suma="SELECT
						SUM(p.monto_cheque) AS Monto
					FROM
						pagos_financieros p
						INNER JOIN tipo_movimiento_bancario t ON (p.idtipo_movimiento_bancario=t.idtipo_movimiento_bancario)
					WHERE
						p.idcuenta_bancaria='".$cuenta."'
						AND p.estado='conciliado'
						AND p.fecha_conciliado>='".$field_head['fecha_apertura']."' 
						AND p.fecha_conciliado<'".$desde."'";
						
				$query_suma=mysql_query($sql_suma) or die ($sql_suma.mysql_error());
				$field_suma=mysql_fetch_array($query_suma);
				$sum_saldo -= $field_suma["Monto"];
				
				
			}
			$total_apertura = $sum_saldo;
			$monto_apertura=number_format($total_apertura, 2, ',', '');
			echo "
			<tr>
				<td style='$tr5' align='left' width='400'>SALDO DISPONIBLE AL ".$alafecha."</td>
				<td style='$tr5' align='right' width='150'>".$monto_apertura."</td>
				<td style='$tr5' align='right' width='150'></td>
				<td style='$tr5' align='right' width='150'></td>
				<td style='$tr5' align='right' width='150'></td>
			</tr>";
			//	----------------------------------------------------
			//	Imprimo los ingresos
			
			//	----------------------------------------------------
			//	Obtengo la suma de los ingresos a la fecha
			$sql = "SELECT
						SUM(i.monto) AS Monto,
						
						(SELECT SUM(p.monto_cheque) AS Monto
							FROM pagos_financieros p
								WHERE
									p.idcuenta_bancaria='".$cuenta."'
									AND p.estado='anulado'
									AND p.fecha_anulacion>='$desde' AND p.fecha_anulacion<='$hasta') AS MontoNulos
					FROM
						ingresos_egresos_financieros i
						
					WHERE
						i.idcuentas_bancarias='".$cuenta."'
						AND i.idbanco='".$banco."'
						AND i.tipo='ingreso'
						AND i.fecha>='$desde' AND i.fecha<='$hasta'
						AND i.estado <> 'anulado'
					";
			$query_acredita = mysql_query($sql) or die ($sql.mysql_error());
			$field_acredita = mysql_fetch_array($query_acredita);
			$total_acredita = $field_acredita['Monto'] + $field_acredita['MontoNulos'];
			
			//	Obtengo la suma de los egresos a la fecha
			$sql = "SELECT
						SUM(i.monto) AS Monto,
						(SELECT SUM(p.monto_cheque) AS Monto
							FROM pagos_financieros p
								WHERE
									p.idcuenta_bancaria='".$cuenta."'
									AND p.estado='conciliado'
									AND p.fecha_conciliado>='$desde' AND p.fecha_conciliado<='$hasta') AS MontoConciliado
					FROM
						ingresos_egresos_financieros i
						INNER JOIN tipo_movimiento_bancario t ON (i.idtipo_movimiento=t.idtipo_movimiento_bancario)
					WHERE
						i.idcuentas_bancarias='".$cuenta."'
						AND i.idbanco='".$banco."'
						AND i.tipo='egreso'
						AND i.fecha>='$desde' AND i.fecha<='$hasta'
						AND i.estado <> 'anulado'";
			
			
			$query_debita = mysql_query($sql) or die ($sql.mysql_error());
			$field_debita = mysql_fetch_array($query_debita);
			$total_debita = $field_debita['MontoConciliado'] + $field_debita['Monto'];
			
			
			// DESGLOCE DE INGRESOS
			$sql_tipo_movimiento = mysql_query("select * from tipo_movimiento_bancario where afecta = 'a'");
			while($bus_tipo_movimiento = mysql_fetch_array($sql_tipo_movimiento)){
				//	Obtengo la suma de los egresos a la fecha
				$sql = "SELECT
						SUM(i.monto) AS MontoMovimiento
					FROM
						ingresos_egresos_financieros i
					WHERE
						i.idcuentas_bancarias='".$cuenta."'
						AND i.idbanco='".$banco."'
						AND i.idtipo_movimiento='".$bus_tipo_movimiento["idtipo_movimiento_bancario"]."'
						AND i.fecha>='$desde' AND i.fecha<='$hasta'
						AND i.estado <> 'anulado'
					";
				
				$query_movimiento = mysql_query($sql) or die ($sql.mysql_error());
				$field_movimiento = mysql_fetch_array($query_movimiento);
				
				if ($field_movimiento["MontoMovimiento"] > 0){
					//	Imprimo total del movimiento
					$monto_movimiento = number_format($field_movimiento['MontoMovimiento'], 2, ',', '.');
					echo "
					<tr>
						<td style='$tr5' align='left' width='400'>".utf8_decode($bus_tipo_movimiento["denominacion"])."</td>
						<td style='$tr5' align='right' width='150'>".$monto_movimiento."</td>
						<td style='$tr5' align='right' width='150'></td>
						<td style='$tr5' align='right' width='150'></td>
						<td style='$tr5' align='right' width='150'></td>
					</tr>";
					
				}
			}
			
			
						
			//	----------------------------------------------------
			//	Imprimo cheques nulos
			$sql="SELECT
						SUM(p.monto_cheque) AS Monto
					FROM
						pagos_financieros p
					WHERE
						p.idcuenta_bancaria='".$cuenta."'
						AND p.estado='anulado'
						AND p.fecha_anulacion>='$desde' AND p.fecha_anulacion<='$hasta'";
			$query_nulos=mysql_query($sql) or die ($sql.mysql_error());
			$rows_nulos=mysql_num_rows($query_nulos);
			if ($rows_nulos!=0) $field_nulos=mysql_fetch_array($query_nulos);
			//
			$monto=number_format($field_nulos['Monto'], 2, ',', ''); $sum_ingresos+=$field_nulos['Monto'];
			echo "
			<tr>
				<td style='$tr5' align='left' width='400'>CHEQUES NULOS</td>
				<td style='$tr5' align='right' width='150'>".$monto."</td>
				<td style='$tr5' align='right' width='150'></td>
				<td style='$tr5' align='right' width='150'></td>
				<td style='$tr5' align='right' width='150'></td>
			</tr>";
			$total_deposito = number_format($field_acredita['Monto'], 2,',', '.');
			echo "
			<tr>
				<td style='$tr5' align='left' width='400'>TOTAL INGRESOS DEL MES</td>
				<td style='$tr5' align='right' width='150'>".$total_deposito."</td>
				<td style='$tr5' align='right' width='150'></td>
				<td style='$tr5' align='right' width='150'></td>
				<td style='$tr5' align='right' width='150'></td>
			</tr>";
			$total_deposito = number_format($field_acredita['Monto']+$total_apertura, 2,',', '.');
			echo "
			<tr>
				<td style='$tr5' align='left' width='400'>TOTAL DISPONIBLE DEL MES</td>
				<td style='$tr5' align='right' width='150'>".$total_deposito."</td>
				<td style='$tr5' align='right' width='150'></td>
				<td style='$tr5' align='right' width='150'></td>
				<td style='$tr5' align='right' width='150'></td>
			</tr>";
			
			
			$total_deposito = number_format($field_acredita['Monto']+$total_apertura, 2,',', '.');
			echo "
			<tr>
				<td style='$tr5' align='left' width='400'>TOTAL DISPONIBLE DEL MES</td>
				<td style='$tr5' align='right' width='150'>".$total_deposito."</td>
				<td style='$tr5' align='right' width='150'></td>
				<td style='$tr5' align='right' width='150'></td>
				<td style='$tr5' align='right' width='150'></td>
			</tr>";
			
			$total_conciliado = $field_debita['MontoConciliado'];
			$total_egresos_conciliados = number_format($total_conciliado, 2, ',', '.');
			echo "
			<tr>
				<td style='$tr5' align='left' width='400'>EGRESOS DURANTE EL MES</td>
				<td style='$tr5' align='right' width='150'></td>
				<td style='$tr5' align='right' width='150'>".$total_egresos_conciliados."</td>
				<td style='$tr5' align='right' width='150'></td>
				<td style='$tr5' align='right' width='150'></td>
			</tr>";
			
			// DESGLOCE DE EGRESOS
			$sql_tipo_movimiento = mysql_query("select * from tipo_movimiento_bancario where afecta = 'd'");
			while($bus_tipo_movimiento = mysql_fetch_array($sql_tipo_movimiento)){
				//	Obtengo la suma de los egresos a la fecha
				$sql = "SELECT
						SUM(i.monto) AS MontoMovimiento
					FROM
						ingresos_egresos_financieros i
					WHERE
						i.idcuentas_bancarias='".$cuenta."'
						AND i.idbanco='".$banco."'
						AND i.idtipo_movimiento='".$bus_tipo_movimiento["idtipo_movimiento_bancario"]."'
						AND i.fecha>='$desde' AND i.fecha<='$hasta'
						AND i.estado <> 'anulado'
					";
				
				$query_movimiento = mysql_query($sql) or die ($sql.mysql_error());
				$field_movimiento = mysql_fetch_array($query_movimiento);
				
				if ($field_movimiento["MontoMovimiento"] > 0){
					//	Imprimo total del movimiento
					$monto_movimiento = number_format($field_movimiento['MontoMovimiento'], 2, ',', '.');
					echo "
					<tr>
						<td style='$tr5' align='left' width='400'>".utf8_decode($bus_tipo_movimiento["denominacion"])."</td>
						<td style='$tr5' align='right' width='150'></td>
						<td style='$tr5' align='right' width='150'>".$monto_movimiento."</td>
						<td style='$tr5' align='right' width='150'></td>
						<td style='$tr5' align='right' width='150'></td>
					</tr>";
					
				}
			}
			$total_egresos = number_format($total_debita, 2, ',', '.');
			echo "
			<tr>
				<td style='$tr5' align='left' width='400'>TOTAL EGRESOS DURANTE EL MES</td>
				<td style='$tr5' align='right' width='150'></td>
				<td style='$tr5' align='right' width='150'>".$total_egresos."</td>
				<td style='$tr5' align='right' width='150'></td>
				<td style='$tr5' align='right' width='150'></td>
			</tr>";
			
			
			$sql_transito="SELECT
						SUM(p.monto_cheque) AS MontoTransito						
					FROM
						pagos_financieros p
						INNER JOIN tipo_movimiento_bancario t ON (p.idtipo_movimiento_bancario=t.idtipo_movimiento_bancario)
					WHERE
						p.idcuenta_bancaria='".$cuenta."'
						AND ((p.estado='transito' AND p.fecha_cheque<='$hasta')
							OR (p.estado='conciliado' AND p.fecha_cheque<='$hasta' AND p.fecha_conciliado>'$hasta')
							OR (p.estado='anulado' AND p.fecha_cheque<='$hasta' AND p.fecha_anulacion>'$hasta'))";
			
			$query_transito = mysql_query($sql_transito) or die ($sql_transito.mysql_error());
			$field_transito = mysql_fetch_array($query_transito);
			
			
			$saldo_banco = $total_apertura + $field_acredita['Monto'] + $field_acredita['MontoNulos'] - $total_debita ;
			
			$saldo_libro = $total_apertura + $field_acredita['Monto'] + $field_acredita['MontoNulos'] - $field_transito['MontoTransito'] - $total_debita;
			$saldo_b=number_format($saldo_banco, 2, ',', '.');
			$saldo_l=number_format($saldo_libro, 2, ',', '.');
			//	Imprimo cheques en transito
			
			echo "
			<tr>
				<td style='$tr5' align='left' width='400'>SALDO SEGUN BANCO AL: ".$fhasta." </td>
				<td style='$tr5' align='right' width='150'></td>
				<td style='$tr5' align='right' width='150'></td>
				<td style='$tr5' align='right' width='150'>".$saldo_b."</td>
				<td style='$tr5' align='right' width='150'></td>
			</tr>";
			
			$total_transito = number_format($field_transito['MontoTransito'], 2, ',', '.');
			echo "
			<tr>
				<td style='$tr5' align='left' width='400'>CHEQUES EN TRANSITO</td>
				<td style='$tr5' align='right' width='150'></td>
				<td style='$tr5' align='right' width='150'></td>
				<td style='$tr5' align='right' width='150'>".$total_transito."</td>
				<td style='$tr5' align='right' width='150'></td>
			</tr>";
			
			echo "
			<tr>
				<td style='$tr5' align='left' width='400'>SALDO SEGUN LIBRO AL: ".$fhasta."</td>
				<td style='$tr5' align='right' width='150'></td>
				<td style='$tr5' align='right' width='150'></td>
				<td style='$tr5' align='right' width='150'></td>
				<td style='$tr5' align='right' width='150'>".$saldo_l."</td>
				
			</tr>";
		
		echo "</table>";
		break;
	
	//	Estado de Cuenta...
	case "estado_cuenta":
		$tr1="background-color:#999999; font-size:12px;";
		$tr2="font-size:12px; color:#000000; font-weight:bold;";
		$tr3="background-color:RGB(225, 255, 255); font-size:12px; color:#000000; font-weight:bold;";
		$tr4="font-size:12px; color:#000000; font-weight:bold;";
		$tr5="font-size:12px; color:#000000;";
		//	----------------------------------------------------
		$a=(int) $anio; 
		$m=(int) $mes; $mm = --$m; 
		if ($mm==0) { //--$a; 
			$alafecha="01/01/$a"; 
			$fapertura="$a-01-01"; 
		} 
		elseif ($m==2) { if ($a%4==0) $d=29; else $d=28; $alafecha="$d/02/$a"; $fapertura="$a-02-$d"; }
		else {
			if ($m<10) $m="0$m";
			$d=$dias_mes[$m];
			$alafecha="$d/$m/$a";
			$fapertura="$a-$m-$d";
		}
		//	----------------------------------------------------
		list($d, $m, $a)=SPLIT( '[/.-]', $alafecha); 
		$alafecha_hasta = "$a-$m-$d";
		$alafecha_desde = "$a-$m-01"; 
		//	----------------------------------------------------
		if ($anio%4==0) $dias_mes['02']=29; else $dias_mes['02']=28;
		$d=$dias_mes[$mes];
		$desde="$anio-$mes-01"; $hasta="$anio-$mes-$d";
		//	----------------------------------------------------
		//	Obtengo la cabecera
		$sql="SELECT
					c.numero_cuenta,
					c.uso_cuenta,
					c.monto_apertura,
					c.fecha_apertura,
					b.denominacion AS Banco,
					t.denominacion AS Cuenta
				FROM
					cuentas_bancarias c
					INNER JOIN banco b ON (c.idbanco=b.idbanco)
					INNER JOIN tipo_cuenta_bancaria t ON (c.idtipo_cuenta=t.idtipo_cuenta)
				WHERE
					c.idcuentas_bancarias='".$cuenta."'";
		$query_head=mysql_query($sql) or die ($sql.mysql_error());
		$rows_head=mysql_num_rows($query_head);
		if ($rows_head!=0) $field_head=mysql_fetch_array($query_head);
		$periodo="Periodo: ".strtoupper($nom_mes[$mes]).' '.$anio." ";
		echo "<table border='1'>";
		//	----------------------------------------------------
		//	Imprimo el head
		echo "
		<tr><td style='$tr4' align='left' colspan='8'>".utf8_decode('BANCO '.$field_head['Banco'].' CTA. '.$field_head['Cuenta'].' Nº. '.$field_head['numero_cuenta'])."</td></tr>
		<tr><td style='$tr4' align='left' colspan='8'>DENOMINACION ".$field_head['uso_cuenta']."</td></tr>
		<tr><td style='$tr4' align='left' colspan='8'>".$periodo."</td></tr>";
		echo "
			<tr>
				<td style='$tr4' align='left' width='80'>FECHA</td>
				<td style='$tr4' align='center' width='60'>TIPO</td>
				<td style='$tr4' align='center' width='150'>REFERENCIA</td>
				<td style='$tr4' align='left' width='400'>DESCRIPCION</td>
				<td style='$tr4' align='center' width='150'>TRANSITO</td>
				<td style='$tr4' align='center' width='150'>DEBE</td>
				<td style='$tr4' align='center' width='150'>HABER</td>
				<td style='$tr4' align='right' width='150'>SALDO</td>
			</tr>";
		//	-----------------------------------------------------
		if ($field_head['fecha_apertura']<=$fapertura){
			//	Si la fecha de apertura es menor que el mes seleccionado entonces imprimo el reporte...
			//	----------------------------------------------------
			//	Obtengo la suma de los ingresos
			if ($field_head['fecha_apertura'] == $desde){
				$sum_saldo = $field_head["monto_apertura"];
			}else{
				// OBTENGO LA SUMA DE LOS INGRESOS
				$sum_saldo = $field_head["monto_apertura"];
				$sql_suma="SELECT
						SUM(i.monto) AS Monto
					FROM
						ingresos_egresos_financieros i
						INNER JOIN tipo_movimiento_bancario t ON (i.idtipo_movimiento=t.idtipo_movimiento_bancario)
					WHERE
						i.idcuentas_bancarias='".$cuenta."'
						AND i.idbanco='".$banco."'
						AND i.tipo='ingreso'
						AND i.fecha>='".$field_head['fecha_apertura']."' 
						AND i.fecha<'".$desde."'
						AND i.estado <> 'anulado'";
						
				$query_suma=mysql_query($sql_suma) or die ($sql_suma.mysql_error());
				$field_suma=mysql_fetch_array($query_suma);
				$sum_saldo += $field_suma["Monto"];
				
				// RESTO LOS EGRESOS
				$sql_suma="SELECT
						SUM(i.monto) AS Monto
					FROM
						ingresos_egresos_financieros i
						INNER JOIN tipo_movimiento_bancario t ON (i.idtipo_movimiento=t.idtipo_movimiento_bancario)
					WHERE
						i.idcuentas_bancarias='".$cuenta."'
						AND i.idbanco='".$banco."'
						AND i.tipo='egreso'
						AND i.fecha>='".$field_head['fecha_apertura']."' 
						AND i.fecha<'".$desde."'
						AND i.estado <> 'anulado'";
						
				$query_suma=mysql_query($sql_suma) or die ($sql_suma.mysql_error());
				$field_suma=mysql_fetch_array($query_suma);
				$sum_saldo -= $field_suma["Monto"];
				
				//OBTENGO LA SUMA DE LOS CHEQUES EN TRANSITO Y CONCILIADOS
				
				$sql_suma="SELECT
						SUM(p.monto_cheque) AS Monto
					FROM
						pagos_financieros p
						INNER JOIN tipo_movimiento_bancario t ON (p.idtipo_movimiento_bancario=t.idtipo_movimiento_bancario)
					WHERE
						p.idcuenta_bancaria='".$cuenta."'
						AND p.estado='conciliado'
						AND p.fecha_conciliado>='".$field_head['fecha_apertura']."' 
						AND p.fecha_conciliado<'".$desde."'";
						
				$query_suma=mysql_query($sql_suma) or die ($sql_suma.mysql_error());
				$field_suma=mysql_fetch_array($query_suma);
				$sum_saldo -= $field_suma["Monto"];
				
			}
			$saldo = number_format($sum_saldo, 2, ',', '.');
			
			echo "
			<tr>
				<td style='$tr2' align='center' width='80'></td>
				<td style='$tr2' align='center' width='60'></td>
				<td style='$tr2' align='center' width='150'></td>
				<td style='$tr2' align='left' width='400'>SALDO DISPONIBLE AL ".$alafecha."</td>
				<td style='$tr2' align='center' width='150'></td>
				<td style='$tr2' align='center' width='150'></td>
				<td style='$tr2' align='center' width='150'></td>
				<td style='$tr2' align='right' width='150'>".$saldo."</td>
			</tr>";
			//	----------------------------------------------------
			//	Imprimo los ingresos
			$sql="(SELECT
						i.monto AS Monto,
						t.siglas,
						i.numero_documento as Documento,
						i.concepto As Denominacion,
						i.fecha AS Fecha
					FROM
						ingresos_egresos_financieros i
						INNER JOIN tipo_movimiento_bancario t ON (i.idtipo_movimiento=t.idtipo_movimiento_bancario)
					WHERE
						i.idcuentas_bancarias='".$cuenta."'
						AND i.idbanco='".$banco."'
						AND i.tipo='ingreso'
						AND i.fecha>='$desde' 
						AND i.fecha<='$hasta'
						AND i.estado <> 'anulado')
							
				UNION (
					SELECT
						p.monto_cheque AS Monto,
						t.siglas,
						p.numero_cheque as Documento,
						CONCAT(p.beneficiario,' (Anulado)') As Denominacion,
						p.fecha_cheque AS Fecha
					FROM
						pagos_financieros p
						INNER JOIN tipo_movimiento_bancario t ON (p.idtipo_movimiento_bancario=t.idtipo_movimiento_bancario)
					WHERE
						p.idcuenta_bancaria='".$cuenta."'
						AND p.estado='anulado'
						AND p.fecha_anulacion>='$desde' 
						AND p.fecha_anulacion<='$hasta')
						
				ORDER BY Fecha";
			$query_acredita=mysql_query($sql) or die ($sql.mysql_error());
			while ($field_acredita=mysql_fetch_array($query_acredita)) {
				list($a, $m, $d)=SPLIT( '[/.-]', $field_acredita['Fecha']); $fecha=$d."/".$m."/".$a;
				$monto=number_format($field_acredita['Monto'], 2, ',', '.'); $sum_ingresos+=$field_acredita['Monto'];
				echo "
				<tr>
					<td style='$tr5' align='center' width='80'>".$fecha."</td>
					<td style='$tr5' align='center' width='60'>".$field_acredita['siglas']."</td>
					<td style='$tr5' align='center' width='150'>".$field_acredita['Documento']."</td>
					<td style='$tr5' align='left' width='400'>".utf8_decode($field_acredita['Denominacion'])."</td>
					<td style='$tr5' align='center' width='150'></td>
					<td style='$tr5' align='center' width='150'></td>
					<td style='$tr5' align='right' width='150'>".$monto."</td>
					<td style='$tr5' align='center' width='150'></td>
				</tr>";
			}
			//	----------------------------------------------------
			//	Imprimo total acredita
			$total_acredita=number_format($sum_ingresos, 2, ',', '.');
			echo "
			<tr>
				<td style='$tr2' align='center' width='80'></td>
				<td style='$tr2' align='center' width='60'></td>
				<td style='$tr2' align='center' width='150'></td>
				<td style='$tr2' align='left' width='400'>Total Acredita</td>
				<td style='$tr5' align='center' width='150'></td>
				<td style='$tr5' align='center' width='150'></td>
				<td style='$tr2' align='right' width='150'>".$total_acredita."</td>
				<td style='$tr5' align='center' width='150'></td>
			</tr>";
			$total_disponible = $sum_ingresos+$sum_saldo;
			$total_acredita=number_format($sum_ingresos+$sum_saldo, 2, ',', '.');
			echo "
			<tr>
				<td style='$tr2' align='center' width='80'></td>
				<td style='$tr2' align='center' width='60'></td>
				<td style='$tr2' align='center' width='150'></td>
				<td style='$tr2' align='left' width='400'>Total Disponible</td>
				<td style='$tr5' align='center' width='150'></td>
				<td style='$tr5' align='center' width='150'></td>
				<td style='$tr5' align='center' width='150'></td>
				<td style='$tr2' align='right' width='150'>".$total_acredita."</td>
				
			</tr>";
			//	----------------------------------------------------
			//	Imprimo los egresos
			$sql="(SELECT
						p.monto_cheque AS Monto,
						t.siglas,
						p.numero_cheque as Documento,
						t.denominacion, 
						p.beneficiario As Denominacion,
						p.fecha_conciliado AS Fecha,
						p.fecha_cheque AS fecha_cheque
					FROM
						pagos_financieros p
						INNER JOIN tipo_movimiento_bancario t ON (p.idtipo_movimiento_bancario=t.idtipo_movimiento_bancario)
					WHERE
						p.idcuenta_bancaria='".$cuenta."'
						AND p.estado='conciliado'
						AND p.fecha_conciliado>='$desde' AND p.fecha_conciliado<='$hasta')
							
				UNION (
					SELECT
						i.monto AS Monto,
						t.siglas,
						i.numero_documento as Documento,
						t.denominacion, 
						i.concepto As Denominacion,
						i.fecha AS Fecha,
						i.fecha AS fecha_cheque
					FROM
						ingresos_egresos_financieros i
						INNER JOIN tipo_movimiento_bancario t ON (i.idtipo_movimiento=t.idtipo_movimiento_bancario)
					WHERE
						i.idcuentas_bancarias='".$cuenta."'
						AND i.idbanco='".$banco."'
						AND i.tipo='egreso'
						AND i.fecha>='$desde' AND i.fecha<='$hasta'
						AND i.estado <> 'anulado'
					)
						
				ORDER BY Fecha";
			$saldo = $total_disponible;
			$query_debita=mysql_query($sql) or die ($sql.mysql_error());
			while ($field_debita=mysql_fetch_array($query_debita)) {
				list($a, $m, $d)=SPLIT( '[/.-]', $field_debita['Fecha']); $fecha=$d."/".$m."/".$a;
				$monto=number_format($field_debita['Monto'], 2, ',', '.'); $sum_egresos+=$field_debita['Monto'];
				$saldo = $saldo - $field_debita['Monto'];
				$saldo_disponible = number_format($saldo, 2, ',', '.');
				echo "
				<tr>
					<td style='$tr5' align='center' width='80'>".$fecha."</td>
					<td style='$tr5' align='center' width='60'>".$field_debita['siglas']."</td>
					<td style='$tr5' align='center' width='150'>".$field_debita['Documento']."</td>
					<td style='$tr5' align='left' width='400'>".utf8_decode($field_debita['Denominacion'])."</td>
					<td style='$tr5' align='center' width='150'></td>
					<td style='$tr5' align='right' width='150'>".$monto."</td>
					<td style='$tr5' align='center' width='150'></td>
					<td style='$tr5' align='right' width='150'>".$saldo_disponible."</td>
				</tr>";
			}
			//	----------------------------------------------------
			//	Imprimo total debita
			$total_debita=number_format($sum_egresos, 2, ',', '.');
			echo "
			<tr>
				<td style='$tr2' align='center' width='80'></td>
				<td style='$tr2' align='center' width='60'></td>
				<td style='$tr2' align='center' width='150'></td>
				<td style='$tr2' align='left' width='400'>Total Debita</td>
				<td style='$tr5' align='center' width='150'></td>
				<td style='$tr2' align='right' width='150'>".$total_debita."</td>
				<td style='$tr5' align='center' width='150'></td>
				<td style='$tr5' align='center' width='150'></td>
			</tr>";
			//	----------------------------------------------------
			list($a, $m, $d)=SPLIT( '[/.-]', $hasta); $fhasta=$d."/".$m."/".$a;
			//	Imprimo saldo segun libro
			
			//	Imprimo cheques en transito
			$sql="SELECT
						p.monto_cheque AS Monto,
						t.siglas,
						p.numero_cheque as Documento,
						t.denominacion, 
						CONCAT(p.beneficiario, ' (T)') As Denominacion,
						p.fecha_cheque AS Fecha
					FROM
						pagos_financieros p
						INNER JOIN tipo_movimiento_bancario t ON (p.idtipo_movimiento_bancario=t.idtipo_movimiento_bancario)
					WHERE
						p.idcuenta_bancaria='".$cuenta."'
						AND ((p.estado='transito' AND p.fecha_cheque<='$hasta')
							or (p.estado='conciliado' AND p.fecha_cheque<='$hasta' AND p.fecha_conciliado>'$hasta')
							or (p.estado='anulado' AND p.fecha_cheque<='$hasta' AND p.anulacion>'$hasta'))
						
				ORDER BY Fecha";
			$query_transito=mysql_query($sql) or die ($sql.mysql_error());
			while ($field_transito=mysql_fetch_array($query_transito)) {
				list($a, $m, $d)=SPLIT( '[/.-]', $field_transito['Fecha']); $fecha=$d."/".$m."/".$a;
				$monto=number_format($field_transito['Monto'], 2, ',', ''); $sum_transito+=$field_transito['Monto'];
				echo "
				<tr>
					<td style='$tr5' align='center' width='80'>".$fecha."</td>
					<td style='$tr5' align='center' width='60'>".$field_transito['siglas']."</td>
					<td style='$tr5' align='center' width='150'>".$field_transito['Documento']."</td>
					<td style='$tr5' align='left' width='400'>".utf8_decode($field_transito['Denominacion'])."</td>
					<td style='$tr5' align='right' width='150'>".$monto."</td>
					<td style='$tr5' align='center' width='150'></td>
					<td style='$tr5' align='center' width='150'></td>
					<td style='$tr5' align='center' width='150'></td>
				</tr>";
			}
			//	----------------------------------------------------
			//	Imprimo total debita
			$total_transito=number_format($sum_transito, 2, ',', '.');
			echo "
			<tr>
				<td style='$tr2' align='center' width='80'></td>
				<td style='$tr2' align='center' width='60'></td>
				<td style='$tr2' align='center' width='150'></td>
				<td style='$tr2' align='left' width='400'>Total Cheques Transito</td>
				<td style='$tr2' align='right' width='150'>".$total_transito."</td>
				<td style='$tr5' align='center' width='150'></td>
				<td style='$tr5' align='center' width='150'></td>
				<td style='$tr5' align='center' width='150'></td>
			</tr>";
			//	----------------------------------------------------
			//	----------------------------------------------------
			//	Imprimo saldo segun banco
			$saldo_banco = $sum_ingresos - $sum_egresos + $saldo_libro + $sum_transito;
			$saldob=number_format($saldo, 2, ',', '.');
			echo "
			<tr>
				<td style='$tr2' align='center' width='80'></td>
				<td style='$tr2' align='center' width='60'></td>
				<td style='$tr2' align='center' width='150'></td>
				<td style='$tr2' align='left' width='400'>SALDO SEGUN BANCO AL ".$fhasta."</td>
				<td style='$tr2' align='center' width='150'></td>
				<td style='$tr2' align='center' width='150'></td>
				<td style='$tr2' align='center' width='150'></td>
				<td style='$tr2' align='right' width='150'>".$saldob."</td>
			</tr>";
			//	----------------------------------------------------
		} else {
			echo "<tr><td style='$tr4' align='left' colspan='5'>".$alafecha." NO SE HABIA REGISTRADO MONTO DE APERTURA</td></tr>";
		}
		echo "</table>";
		break;
		
		//	Libro Diario Banco...
	case "libro_diario_banco":
		$tr1="background-color:#999999; font-size:12px;";
		$tr2="font-size:12px; color:#000000; font-weight:bold;";
		$tr3="background-color:RGB(225, 255, 255); font-size:12px; color:#000000; font-weight:bold;";
		$tr4="font-size:12px; color:#000000; font-weight:bold;";
		$tr5="font-size:12px; color:#000000;";
		//	----------------------------------------------------
		$a=(int) $anio; 
		$m=(int) $mes; $mm = --$m; 
		if ($mm==0) { //--$a; 
			$alafecha="01/01/$a"; 
			$fapertura="$a-01-01"; 
		} 
		elseif ($m==2) { if ($a%4==0) $d=29; else $d=28; $alafecha="$d/02/$a"; $fapertura="$a-02-$d"; }
		else {
			if ($m<10) $m="0$m";
			$d=$dias_mes[$m];
			$alafecha="$d/$m/$a";
			$fapertura="$a-$m-$d";
		}
		//	----------------------------------------------------
		list($d, $m, $a)=SPLIT( '[/.-]', $alafecha); 
		$alafecha_hasta = "$a-$m-$d";
		$alafecha_desde = "$a-$m-01";
		$desde_inicial = "$a-01-01";
		//	----------------------------------------------------
		if ($anio%4==0) $dias_mes['02']=29; else $dias_mes['02']=28;
		$d=$dias_mes[$mes];
		$desde="$anio-$mes-01"; $hasta="$anio-$mes-$d";
		//	----------------------------------------------------
		//	Obtengo la cabecera
		$sql="SELECT
					c.numero_cuenta,
					c.uso_cuenta,
					c.monto_apertura,
					c.fecha_apertura,
					b.denominacion AS Banco,
					t.denominacion AS Cuenta
				FROM
					cuentas_bancarias c
					INNER JOIN banco b ON (c.idbanco=b.idbanco)
					INNER JOIN tipo_cuenta_bancaria t ON (c.idtipo_cuenta=t.idtipo_cuenta)
				WHERE
					c.idcuentas_bancarias='".$cuenta."'";
		$query_head=mysql_query($sql) or die ($sql.mysql_error());
		$rows_head=mysql_num_rows($query_head);
		if ($rows_head!=0) $field_head=mysql_fetch_array($query_head);
		$periodo="Periodo: ".strtoupper($nom_mes[$mes]).' '.$anio." ";
		echo "<table border='1'>";
		//	----------------------------------------------------
		//	Imprimo el head
		echo "
		<tr><td style='$tr4' align='left' colspan='7'>".utf8_decode('BANCO '.$field_head['Banco'].' CTA. '.$field_head['Cuenta'].' Nº. '.$field_head['numero_cuenta'])."</td></tr>
		<tr><td style='$tr4' align='left' colspan='7'>DENOMINACION ".$field_head['uso_cuenta']."</td></tr>
		<tr><td style='$tr4' align='left' colspan='7'>".$periodo."</td></tr>";
		echo "
			<tr>
				<td style='$tr4' align='left' width='80'>FECHA</td>
				<td style='$tr4' align='center' width='60'>TIPO</td>
				<td style='$tr4' align='center' width='150'>REFERENCIA</td>
				<td style='$tr4' align='left' width='400'>DESCRIPCION</td>
				<td style='$tr4' align='center' width='150'>DEBE</td>
				<td style='$tr4' align='center' width='150'>HABER</td>
				<td style='$tr4' align='right' width='150'>SALDO</td>
			</tr>";
		//	-----------------------------------------------------
		if ($field_head['fecha_apertura']<=$fapertura){
			//	Si la fecha de apertura es menor que el mes seleccionado entonces imprimo el reporte...
			//	----------------------------------------------------
			//	Obtengo la suma de los ingresos
			if ($field_head['fecha_apertura'] == $desde){
				$sum_saldo = $field_head["monto_apertura"];
			}else{
				// OBTENGO LA SUMA DE LOS INGRESOS
				$sum_saldo = $field_head["monto_apertura"];
				$sql_suma="SELECT
						SUM(i.monto) AS Monto
					FROM
						ingresos_egresos_financieros i
						INNER JOIN tipo_movimiento_bancario t ON (i.idtipo_movimiento=t.idtipo_movimiento_bancario)
					WHERE
						i.idcuentas_bancarias='".$cuenta."'
						AND i.idbanco='".$banco."'
						AND i.tipo='ingreso'
						AND i.fecha>='".$field_head['fecha_apertura']."' 
						AND i.fecha<'".$desde."'
						AND i.estado <> 'anulado'";
						
				$query_suma=mysql_query($sql_suma) or die ($sql_suma.mysql_error());
				$field_suma=mysql_fetch_array($query_suma);
				$sum_saldo += $field_suma["Monto"];
				
				// RESTO LOS EGRESOS
				$sql_suma="SELECT
						SUM(i.monto) AS Monto
					FROM
						ingresos_egresos_financieros i
						INNER JOIN tipo_movimiento_bancario t ON (i.idtipo_movimiento=t.idtipo_movimiento_bancario)
					WHERE
						i.idcuentas_bancarias='".$cuenta."'
						AND i.idbanco='".$banco."'
						AND i.tipo='egreso'
						AND i.fecha>='".$field_head['fecha_apertura']."' 
						AND i.fecha<'".$desde."'
						AND i.estado <> 'anulado'";
						
				$query_suma=mysql_query($sql_suma) or die ($sql_suma.mysql_error());
				$field_suma=mysql_fetch_array($query_suma);
				$sum_saldo -= $field_suma["Monto"];
				
				//OBTENGO LA SUMA DE LOS CHEQUES EN TRANSITO, CONCILIADOS ANULADOS DESPUES DEL PERIODO
				
				$sql_suma="SELECT
						SUM(p.monto_cheque) AS Monto
					FROM
						pagos_financieros p
						INNER JOIN tipo_movimiento_bancario t ON (p.idtipo_movimiento_bancario=t.idtipo_movimiento_bancario)
					WHERE
						p.idcuenta_bancaria='".$cuenta."'
						AND ((p.estado='conciliado' OR p.estado='transito')
						AND p.fecha_cheque>='".$field_head['fecha_apertura']."' 
						AND p.fecha_cheque<'".$desde."')
						OR (p.estado='anulado' 
						AND p.fecha_cheque>='".$field_head['fecha_apertura']."' 
						AND p.fecha_cheque<'".$desde."'
						AND p.fecha_anulacion>'".$hasta."')";
						
				$query_suma=mysql_query($sql_suma) or die ($sql_suma.mysql_error());
				$field_suma=mysql_fetch_array($query_suma);
				$sum_saldo -= $field_suma["Monto"];
				$saldo = number_format($sum_saldo, 2, ',', '.');
			}
			echo "
			<tr>
				<td style='$tr2' align='center' width='80'></td>
				<td style='$tr2' align='center' width='60'></td>
				<td style='$tr2' align='center' width='150'></td>
				<td style='$tr2' align='left' width='400'>VIENEN Bs. ".$alafecha."</td>
				<td style='$tr2' align='center' width='150'></td>
				<td style='$tr2' align='center' width='150'></td>
				<td style='$tr2' align='right' width='150'>".$saldo."</td>
			</tr>";
			//	----------------------------------------------------
			
			// OBTENGO LOS MOVIMIENTOS EN EL PERIODO
			$sql="(SELECT
						i.monto AS Monto,
						i.numero_documento as Documento,
						i.concepto As Denominacion,
						i.fecha AS Fecha,
						t.siglas,
						'Haber' AS Tipo,
						i.estado
					FROM
						ingresos_egresos_financieros i
						INNER JOIN tipo_movimiento_bancario t ON (i.idtipo_movimiento=t.idtipo_movimiento_bancario)
					WHERE
						i.idcuentas_bancarias='".$cuenta."'
						AND i.idbanco='".$banco."'
						AND i.tipo='ingreso'
						AND i.fecha>='$desde' AND i.fecha<='$hasta'
						AND i.estado <> 'anulado')
							
				UNION (
					SELECT
						p.monto_cheque AS Monto,
						p.numero_cheque as Documento,
						CONCAT(p.beneficiario, ' (Anulado)') As Denominacion,
						p.fecha_cheque AS Fecha,
						t.siglas,
						'Haber' AS Tipo,
						p.estado
					FROM
						pagos_financieros p
						INNER JOIN tipo_movimiento_bancario t ON (p.idtipo_movimiento_bancario=t.idtipo_movimiento_bancario)
					WHERE
						p.idcuenta_bancaria='".$cuenta."'
						AND p.estado='anulado'
						AND p.fecha_cheque>='$desde' AND p.fecha_cheque<='$hasta')
						
				UNION
				
				(SELECT
					p.monto_cheque AS Monto,
					p.numero_cheque as Documento,
					CONCAT(p.beneficiario, ' (Conciliado)') As Denominacion,
					p.fecha_cheque AS Fecha,
					t.siglas,
					'Debe' AS Tipo,
					p.estado
				FROM
					pagos_financieros p
					INNER JOIN tipo_movimiento_bancario t ON (p.idtipo_movimiento_bancario=t.idtipo_movimiento_bancario)
				WHERE
					p.idcuenta_bancaria='".$cuenta."'
					AND p.estado='conciliado'
					AND p.fecha_cheque>='$desde' AND p.fecha_cheque<='$hasta'
				)
							
				UNION 
				
				(SELECT
					i.monto AS Monto,
					i.numero_documento as Documento,
					i.concepto As Denominacion,
					i.fecha AS Fecha,
					t.siglas,
					'Debe' AS Tipo,
					i.estado
				FROM
					ingresos_egresos_financieros i
					INNER JOIN tipo_movimiento_bancario t ON (i.idtipo_movimiento=t.idtipo_movimiento_bancario)
				WHERE
					i.idcuentas_bancarias='".$cuenta."'
					AND i.idbanco='".$banco."'
					AND i.tipo='egreso'
					AND i.fecha>='$desde' AND i.fecha<='$hasta'
					AND i.estado <> 'anulado'
				)
				
				UNION
				
				(SELECT
						p.monto_cheque AS Monto,
						p.numero_cheque as Documento,
						CONCAT(p.beneficiario, ' (Transito)') As Denominacion,
						p.fecha_cheque AS Fecha,
						t.siglas,
						'Debe' AS Tipo,
						p.estado
					FROM
						pagos_financieros p
						INNER JOIN tipo_movimiento_bancario t ON (p.idtipo_movimiento_bancario=t.idtipo_movimiento_bancario)
					WHERE
						p.idcuenta_bancaria='".$cuenta."'
						AND p.estado='transito'
						AND p.fecha_cheque>='$desde' AND p.fecha_cheque<='$hasta'
				)
				
				ORDER BY Fecha";
			$query_detalle = mysql_query($sql) or die ($sql.mysql_error());
			
						
			while ($field_detalle = mysql_fetch_array($query_detalle)) {
				list($a, $m, $d)=SPLIT( '[/.-]', $field_detalle['Fecha']); $fecha=$d."/".$m."/".$a;
								
				if ($field_detalle['Tipo'] == "Debe") {
					$debe = number_format($field_detalle['Monto'], 2, ',', '.'); 	
					$sum_debe += $field_detalle['Monto'];
					$sum_saldo -= $field_detalle['Monto'];
					$haber = "-";
				} else {
					$haber = number_format($field_detalle['Monto'], 2, ',', '.'); 	
					$sum_haber += $field_detalle['Monto'];
					$sum_saldo += $field_detalle['Monto'];
					$debe = "-";
				}
				$saldo = number_format($sum_saldo, 2, ',', '.');
				
					
				echo "
					<tr>
						<td style='$tr5' align='center' width='80'>".$fecha."</td>
						<td style='$tr5' align='center' width='60'>".$field_detalle['siglas']."</td>
						<td style='$tr5' align='center' width='150'>".utf8_decode($field_detalle['Documento'])."</td>
						<td style='$tr5' align='left' width='400'>".utf8_decode($field_detalle['Denominacion'])."</td>
						<td style='$tr5' align='right' width='150'>".$debe."</td>
						<td style='$tr5' align='right' width='150'>".$haber."</td>
						<td style='$tr5' align='right' width='150'>".$saldo."</td>
					</tr>";
					
				
				
				
				
				
			}
			
			//	----------------------------------------------------
			//	Imprimo saldo segun banco
			//$saldo_banco = $sum_ingresos - $sum_egresos + $saldo_libro + $sum_transito;
			//$saldo=number_format($saldo_banco, 2, ',', '');
			$total_haber = number_format($sum_haber, 2, ',', '.');
			$total_debe = number_format($sum_debe, 2, ',', '.');
			echo "
			<tr>
				<td style='$tr2' align='center' width='80'></td>
				<td style='$tr2' align='center' width='60'></td>
				<td style='$tr2' align='center' width='150'></td>
				<td style='$tr2' align='left' width='400'>VAN Bs. </td>
				<td style='$tr2' align='right' width='150'></td>
				<td style='$tr2' align='right' width='150'></td>
				<td style='$tr2' align='right' width='150'>".$saldo."</td>
			</tr>";
			echo "
			<tr>
				<td style='$tr2' align='center' width='80'></td>
				<td style='$tr2' align='center' width='60'></td>
				<td style='$tr2' align='center' width='150'></td>
				<td style='$tr2' align='left' width='400'>TOTAL Bs. </td>
				<td style='$tr2' align='right' width='150'>".$total_debe."</td>
				<td style='$tr2' align='right' width='150'>".$total_haber."</td>
				<td style='$tr2' align='right' width='150'></td>
			</tr>";
			//	----------------------------------------------------
		} else {
			echo "<tr><td style='$tr4' align='left' colspan='5'>".$alafecha." NO SE HABIA REGISTRADO MONTO DE APERTURA</td></tr>";
		}
		echo "</table>";
		break;
}
?>
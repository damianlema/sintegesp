<?
session_start();
extract($_GET);
extract($_POST);
?>
<link href="../../css/theme/green/main.css" rel="stylesheet" type="text/css">
<script src="../../js/function.js" type="text/javascript" language="javascript"></script>
<script type="text/javascript">
function nuevoAjax() { 
	var xmlhttp=false;
	try{
		xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");
	}
	catch(e){
		try{
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		catch(E){
			if (!xmlhttp && typeof XMLHttpRequest!='undefined') xmlhttp=new XMLHttpRequest();
		}
	}
	return xmlhttp; 
}

function seleccionarBeneficiario(id, nombre) {
	opener.document.frmentrada.idbeneficiario.value=id;
	opener.document.frmentrada.beneficiario.value=nombre;
	window.close();
}

function seleccionarArticulo(id, nombre) {
	opener.document.frmentrada.idarticulo.value=id;
	opener.document.frmentrada.articulo.value=nombre;
	window.close();
}

function seleccionarCategoria(id, nombre) {
	opener.document.frmentrada.idcategoria.value=id;
	opener.document.frmentrada.categoria.value=nombre;
	window.close();
}

function seleccionarPartidaPorCategoria(id, nombre) {
	opener.document.frmentrada.idpartida.value=id;
	opener.document.frmentrada.partida.value=nombre;
	window.close();
}

function seleccionarOrdinal(id, codigo, nombre) {
	opener.document.frmentrada.idordinal.value=id;
	opener.document.frmentrada.codordinal.value=codigo;
	opener.document.frmentrada.nomordinal.value=nombre;
	window.close();
}

function seleccionarEmisionPago(id, nombre, orden) {
	opener.document.frmentrada.id_emision_pago.value=id;
	opener.document.frmentrada.orden_pago.value=orden;
	opener.document.frmentrada.beneficiario.value=nombre;
	window.close();
}

function seleccionarOrdenCompraServicio(id, nombre, orden) {
	opener.document.frmentrada.idorden.value=id;
	opener.document.frmentrada.orden.value=orden;
	opener.document.frmentrada.beneficiario.value=nombre;
	window.close();
}

function seleccionarConcepto(id, nombre) {
	opener.document.frmentrada.idconcepto.value=id;
	opener.document.frmentrada.nomconcepto.value=nombre;
	window.close();
}

function seleccionarConceptoConstante(id, nombre, tabla) {
	opener.document.frmentrada.tabla.value=tabla;
	opener.document.frmentrada.idconcepto.value=id;
	opener.document.frmentrada.nomconcepto.value=nombre;
	window.close();
}

function seleccionarUnidadFuncional(id, codigo, nombre) {
	opener.document.frmentrada.idunidad.value=id;
	opener.document.frmentrada.codunidad.value=codigo;
	opener.document.frmentrada.nomunidad.value=nombre;
	window.close();
}

function seleccionarCentroCosto(id, codigo, nombre) {
	opener.document.frmentrada.idcentro.value=id;
	opener.document.frmentrada.codcentro.value=codigo;
	opener.document.frmentrada.nomcentro.value=nombre;
	window.close();
}

function seleccionarTrabajador(id, nombre) {
	opener.document.frmentrada.idtrabajador.value=id;
	opener.document.frmentrada.nomtrabajador.value=nombre;
	window.close();
}

function seleccionarOrdenesCompraServicio(id, orden, nombre) {
	opener.document.frmentrada.idorden_compra_servicio.value=id;
	opener.document.frmentrada.nro_orden.value=orden;
	opener.document.frmentrada.proveedor.value=nombre;
	window.close();
}
</script>

<form name="form" id="form" method="post" action="lista.php?lista=<?=$lista?>">
<?php
include("../../conf/conex.php");
conectarse();

if ($_GET['lista']=="beneficiarios") {
	if ($filtro!="") $where="WHERE nombre LIKE '%$filtro%' OR rif LIKE '%$filtro%' OR telefonos LIKE '%$filtro%' "; else $where ="";
	echo "
	<h2 align='center'>Beneficiarios</h2><br />
	<h2 class='sqlmVersion'></h2><br />
	<table align='center'>
		<tr>
			<td>Buscar:</td>
			<td><input type='text' name='filtro' id='filtro' size='50' /></td>
			<td><input type='submit' name='buscar' id='buscar' value='Buscar' class='button'></td>
		</tr>
	</table><br />
	
	<table class='Browse' align=center cellpadding='0' cellspacing='0' width='100%'>
	  <thead>
		<tr>
			<td class='Browse' align='center'>Nombre</td>
			<td width='100' class='Browse' align='center'>R.I.F</td>
			<td width='125' class='Browse' align='center'>Tel&eacute;fonos</td>
			<td width='75' class='Browse' align='center'>Acciones</td>
		</tr>
	  </thead>";
	$query=mysql_query("SELECT idbeneficiarios, nombre, rif, telefonos FROM beneficiarios $where ORDER BY nombre");
	while ($field=mysql_fetch_array($query)) {
		echo "
		<tr bgcolor='#e7dfce' onMouseOver='setRowColor(this, 0, \"over\", \"#e7dfce\", \"#EAFFEA\", \"#FFFFAA\")' onMouseOut='setRowColor(this, 0, \"out\", \"#e7dfce\", \"#EAFFEA\", \"#FFFFAA\")' id='".$field['idbeneficiarios']."' style='cursor:pointer;' onclick='seleccionarBeneficiario(\"$field[0]\", \"$field[1]\");'>
			<td class='Browse'>".utf8_decode($field['nombre'])."&nbsp;</td>
			<td class='Browse'>".$field['rif']."&nbsp;</td>
			<td class='Browse'>".$field['telefonos']."&nbsp;</td>
			<td class='Browse' align='center' class='Browse'><a href='#' onclick='seleccionarBeneficiario(\"$field[0]\", \"$field[1]\");'><img src='../../imagenes/validar.png'></a></td>
		</tr>";
	}
	echo "
	</table>";
}

elseif ($_GET['lista']=="articulos") {
	if ($filtro!="") $where="WHERE (a.idarticulos_servicios LIKE '%$filtro%' OR a.descripcion LIKE '%$filtro%')"; else $where ="";
	echo "
	<h2 align='center'>Art&iacute;culos</h2><br />
	<h2 class='sqlmVersion'></h2><br />
	<table align='center'>
		<tr>
			<td>Buscar:</td>
			<td><input type='text' name='filtro' id='filtro' size='50' /></td>
			<td><input type='submit' name='buscar' id='buscar' value='Buscar' class='button'></td>
		</tr>
	</table><br />";
	
	echo "
	<table class='Browse' align=center cellpadding='0' cellspacing='0' width='100%'>
	  <thead>
		<tr>
			<td width='75' class='Browse' align='center'>Codigo</td>
			<td class='Browse' align='center'>Nombre</td>
			<td width='75' class='Browse' align='center'>Acciones</td>
		</tr>
	  </thead>";
	$sql = "SELECT 
				a.idarticulos_servicios, 
				a.descripcion
			FROM
				articulos_servicios a
				INNER JOIN tipos_documentos td ON (a.tipo = td.idtipos_documentos AND td.modulo like '%-".$_SESSION['modulo']."-%')
			$where 
			ORDER BY a.descripcion";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	while ($field=mysql_fetch_array($query)) {
		echo "
		<tr bgcolor='#e7dfce' onMouseOver='setRowColor(this, 0, \"over\", \"#e7dfce\", \"#EAFFEA\", \"#FFFFAA\")' onMouseOut='setRowColor(this, 0, \"out\", \"#e7dfce\", \"#EAFFEA\", \"#FFFFAA\")' id='".$field['idarticulos_servicios']."' style='cursor:pointer;' onclick='seleccionarArticulo(\"$field[0]\", \"$field[1]\");'>
			<td class='Browse' align='center'>".$field['idarticulos_servicios']."&nbsp;</td>
			<td class='Browse'>".utf8_decode($field['descripcion'])."&nbsp;</td>
			<td class='Browse' align='center' class='Browse'><a href='#' onclick='seleccionarArticulo(\"$field[0]\", \"$field[1]\");'><img src='../../imagenes/validar.png'></a></td>
		</tr>";
	}
	echo "</table>";
}

elseif ($_GET['lista']=="categorias") {
	if ($filtro!="") $where="AND (categoria_programatica.codigo LIKE '%$filtro%' OR unidad_ejecutora.denominacion LIKE '%$filtro%')"; else $where ="";
	echo "
	<h2 align='center'>Categor&iacute;as Program&aacute;ticas</h2><br />
	<h2 class='sqlmVersion'></h2><br />
	<table align='center'>
		<tr>
			<td>Buscar:</td>
			<td><input type='text' name='filtro' id='filtro' size='50' /></td>
			<td><input type='submit' name='buscar' id='buscar' value='Buscar' class='button'></td>
		</tr>
	</table><br />";
	
	echo "
	<table class='Browse' align=center cellpadding='0' cellspacing='0' width='100%'>
	  <thead>
		<tr>
			<td width='75' class='Browse' align='center'>Codigo</td>
			<td class='Browse' align='center'>Nombre</td>
			<td width='75' class='Browse' align='center'>Acciones</td>
		</tr>
	  </thead>";
	$query=mysql_query("SELECT categoria_programatica.idcategoria_programatica, categoria_programatica.codigo, unidad_ejecutora.denominacion FROM categoria_programatica, unidad_ejecutora WHERE (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) $where ORDER BY categoria_programatica.codigo");
	while ($field=mysql_fetch_array($query)) {
		$descripcion = utf8_decode($field[2]);
		echo "
		<tr bgcolor='#e7dfce' onMouseOver='setRowColor(this, 0, \"over\", \"#e7dfce\", \"#EAFFEA\", \"#FFFFAA\")' onMouseOut='setRowColor(this, 0, \"out\", \"#e7dfce\", \"#EAFFEA\", \"#FFFFAA\")' id='$field[0]' style='cursor:pointer;' onclick='seleccionarCategoria(\"$field[0]\", \"$field[2]\");'>
			<td class='Browse' align='center'>$field[1]&nbsp;</td>
			<td class='Browse'>$descripcion.&nbsp;</td>
			<td class='Browse' align='center' class='Browse'><a href='#' onclick='seleccionarCategoria(\"$field[0]\", \"$field[2]\");'><img src='../../imagenes/validar.png'></a></td>
		</tr>";
	}
	echo "</table>";
}

elseif ($_GET['lista']=="partidas_por_categoria") {
	if ($filtro!="") $where="AND (c.denominacion LIKE '%$filtro%' OR CONCAT(c.partida, '.', c.generica, '.', c.especifica, '.', c.sub_especifica) LIKE '%$filtro%')"; else $where ="";
	echo "
	<h2 align='center'>Clasificador Presupuestario</h2><br />
	<h2 class='sqlmVersion'></h2><br />
	<table align='center'>
		<tr>
			<td>Buscar:</td>
			<td><input type='text' name='filtro' id='filtro' size='50' /></td>
			<td><input type='submit' name='buscar' id='buscar' value='Buscar' class='button'></td>
		</tr>
	</table><br />
	<input type='hidden' name='idcategoria' id='idcategoria' value='$idcategoria' />";
	
	echo "
	<table class='Browse' align=center cellpadding='0' cellspacing='0' width='100%'>
	  <thead>
		<tr>
			<td width='75' class='Browse' align='center'>C&oacute;digo</td>
			<td class='Browse' align='center'>Denominaci&oacute;n</td>
			<td width='75' class='Browse' align='center'>Acciones</td>
		</tr>
	  </thead>";
	 $sql="SELECT m.idcategoria_programatica, c.idclasificador_presupuestario, c.denominacion, c.partida, c.generica, c.especifica, c.sub_especifica, CONCAT(c.partida, '.', c.generica, '.', c.especifica, '.', c.sub_especifica) AS Codigo FROM maestro_presupuesto m, clasificador_presupuestario c WHERE (m.idcategoria_programatica='".$idcategoria."') AND (m.idclasificador_presupuestario=c.idclasificador_presupuestario) $where group by c.codigo_cuenta";
	$query=mysql_query($sql) or die (mysql_error());
	while ($field=mysql_fetch_array($query)) {
		$partida=$field[3].".".$field[4].".".$field[5].".".$field[6];
		$denominacion = utf8_decode($field['denominacion']);
		echo "
		<tr bgcolor='#e7dfce' onMouseOver='setRowColor(this, 0, \"over\", \"#e7dfce\", \"#EAFFEA\", \"#FFFFAA\")' onMouseOut='setRowColor(this, 0, \"out\", \"#e7dfce\", \"#EAFFEA\", \"#FFFFAA\")' id='".$field['idclasificador_presupuestario']."' style='cursor:pointer;' onclick='seleccionarPartidaPorCategoria(\"$field[1]\", \"$denominacion\");'>
			<td class='Browse' align='center'>".$field['Codigo']."&nbsp;</td>
			<td class='Browse'>".utf8_decode($field['denominacion'])."&nbsp;</td>
			<td class='Browse' align='center' class='Browse'><a href='#' onclick='seleccionarPartidaPorCategoria(\"$field[1]\", \"$denominacion\");'><img src='../../imagenes/validar.png'></a></td>
			</tr>";
	}
	echo "</table>";
}

elseif ($_GET['lista']=="ordinales") {
	if ($filtro!="") $where="AND (c.denominacion LIKE '%$filtro%' OR CONCAT(c.partida, '.', c.generica, '.', c.especifica, '.', c.sub_especifica) LIKE '%$filtro%')"; else $where ="";
	echo "
	<h2 align='center'>Ordinales</h2><br />
	<h2 class='sqlmVersion'></h2><br /><br />";
	
	echo "
	<table class='Browse' align=center cellpadding='0' cellspacing='0' width='100%'>
	  <thead>
		<tr>
			<td width='75' class='Browse' align='center'>C&oacute;digo</td>
			<td class='Browse' align='center'>Denominaci&oacute;n</td>
			<td width='75' class='Browse' align='center'>Acciones</td>
		</tr>
	  </thead>";
	 $sql="SELECT o.idordinal, o.codigo, o.denominacion 
	 			FROM ordinal o 
					INNER JOIN maestro_presupuesto mp ON (mp.idordinal = o.idordinal AND mp.idclasificador_presupuestario = '".$idpartida."')
						GROUP BY o.codigo";
	$query=mysql_query($sql) or die (mysql_error());
	while ($field=mysql_fetch_array($query)) {
		$partida=$field[3].".".$field[4].".".$field[5].".".$field[6];
		echo "
		<tr bgcolor='#e7dfce' onMouseOver='setRowColor(this, 0, \"over\", \"#e7dfce\", \"#EAFFEA\", \"#FFFFAA\")' onMouseOut='setRowColor(this, 0, \"out\", \"#e7dfce\", \"#EAFFEA\", \"#FFFFAA\")' id='".$field['idordinal']."' style='cursor:pointer;' onclick='seleccionarOrdinal(\"$field[0]\", \"$field[1]\", \"$field[2]\");'>
			<td class='Browse' align='center'>".$field['codigo']."&nbsp;</td>
			<td class='Browse'>".utf8_decode($field['denominacion'])."&nbsp;</td>
			<td class='Browse' align='center' class='Browse'><a href='#' onclick='seleccionarOrdinal(\"$field[0]\", \"$field[1]\", \"$field[2]\");'><img src='../../imagenes/validar.png'></a></td>
			</tr>";
	}
	echo "</table>";
}

elseif ($_GET['lista']=="partidas") {
	if ($filtro!="") $where="AND (c.denominacion LIKE '%$filtro%' OR CONCAT(c.partida, '.', c.generica, '.', c.especifica, '.', c.sub_especifica) LIKE '%$filtro%')"; else $where ="";
	echo "
	<h2 align='center'>Clasificador Presupuestario</h2><br />
	<h2 class='sqlmVersion'></h2><br />
	<table align='center'>
		<tr>
			<td>Buscar:</td>
			<td><input type='text' name='filtro' id='filtro' size='50' /></td>
			<td><input type='submit' name='buscar' id='buscar' value='Buscar' class='button'></td>
		</tr>
	</table><br />";
	
	echo "
	<table class='Browse' align=center cellpadding='0' cellspacing='0' width='100%'>
	  <thead>
		<tr>
			<td width='75' class='Browse' align='center'>C&oacute;digo</td>
			<td class='Browse' align='center'>Denominaci&oacute;n</td>
			<td width='75' class='Browse' align='center'>Acciones</td>
		</tr>
	  </thead>";
	  $sql="SELECT m.idcategoria_programatica, c.idclasificador_presupuestario, c.denominacion, c.partida, c.generica, c.especifica, c.sub_especifica, CONCAT(c.partida, '.', c.generica, '.', c.especifica, '.', c.sub_especifica) AS Codigo FROM maestro_presupuesto m, clasificador_presupuestario c WHERE  (m.idclasificador_presupuestario=c.idclasificador_presupuestario) $where";
	$query=mysql_query($sql) or die (mysql_error());
	while ($field=mysql_fetch_array($query)) {
		$partida=$field[3].".".$field[4].".".$field[5].".".$field[6];
		echo "
		<tr bgcolor='#e7dfce' onMouseOver='setRowColor(this, 0, \"over\", \"#e7dfce\", \"#EAFFEA\", \"#FFFFAA\")' onMouseOut='setRowColor(this, 0, \"out\", \"#e7dfce\", \"#EAFFEA\", \"#FFFFAA\")' id='".$field['idclasificador_presupuestario']."' style='cursor:pointer;' onclick='seleccionarPartidaPorCategoria(\"$field[1]\", \"$field[2]\");'>
			<td class='Browse' align='center'>".$field['Codigo']."&nbsp;</td>
			<td class='Browse'>".utf8_decode($field['denominacion'])."&nbsp;</td>
			<td class='Browse' align='center' class='Browse'><a href='#' onclick='seleccionarPartidaPorCategoria(\"$field[1]\", \"$field[2]\");'><img src='../../imagenes/validar.png'></a></td>
		</tr>";
	}
	echo "</table>";
}

elseif ($_GET['lista']=="emisiones_pagos") {
	
	echo "
	<h2 align='center'>Emisiones de Pago</h2><br />
	<h2 class='sqlmVersion'></h2><br />
	<table align='center'>
		<tr>
			<td>Buscar:</td>
			<td><input type='text' name='filtro' id='filtro' size='50' /></td>
			<td><input type='submit' name='buscar' id='buscar' value='Buscar' class='button'></td>
		</tr>
	</table><br />";
	if ($filtro){ 
	echo "
	<table class='Browse' align=center cellpadding='0' cellspacing='0' width='98%'>
	  <thead>
		<tr>
			<td width='12%' align='center' class='Browse' style='font-size:9px'>Nro. Orden</td>
            <td width='6%' align='center' class='Browse' style='font-size:9px'>Fecha de la Orden</td>
			<td width='20%' align='center' class='Browse' style='font-size:9px'>Beneficiario</td>
            <td width='40%' align='center' class='Browse' style='font-size:9px'>Justificacion</td>
			<td width='10%' align='center' class='Browse' style='font-size:9px'>Total a Pagar</td>
            <td width='4%' align='center' class='Browse' style='font-size:9px'>Acci&oacute;n</td>
		</tr>
	  </thead>";
	$query=mysql_query("SELECT op.idorden_pago,  
								op.numero_orden,
								op.fecha_orden, 
								b.nombre,
								op.justificacion,
								op.total_a_pagar
									FROM 
								orden_pago op,
								beneficiarios b
									WHERE 
								(op.estado='procesado' 
								or op.estado = 'pagada')
								and b.idbeneficiarios = op.idbeneficiarios 
								AND (op.numero_orden LIKE '%$filtro%' 
								OR b.nombre LIKE '%$filtro%' 
								OR op.justificacion LIKE '%$filtro%')")or die(mysql_error());
	while ($field=mysql_fetch_array($query)) {
			$total_a_pagar=number_format($field['total_a_pagar'], 2, ',', '.');
			echo "
			<tr bgcolor='#e7dfce' onMouseOver='setRowColor(this, 0, \"over\", \"#e7dfce\", \"#EAFFEA\", \"#FFFFAA\")' onMouseOut='setRowColor(this, 0, \"out\", \"#e7dfce\", \"#EAFFEA\", \"#FFFFAA\")' id='$field[0]' style='cursor:pointer;' onclick='seleccionarEmisionPago(\"$field[0]\", \"$field[3]\", \"$field[1]\");'>
				<td class='Browse'>".$field['numero_orden']."&nbsp;</td>
				<td class='Browse'>".$field['fecha_orden']."&nbsp;</td>
				<td class='Browse' align='left'>".utf8_decode($field["nombre"])."&nbsp;</td>
				<td class='Browse'>".utf8_decode($field['justificacion'])."&nbsp;</td>
				<td class='Browse' align='right'>".$total_a_pagar."&nbsp;</td>
				<td class='Browse' align='center' class='Browse'><a href='#' onclick='seleccionarEmisionPago(\"$field[0]\", \"$field[3]\", \"$field[1]\");'><img src='../../imagenes/validar.png'></a></td>
			</tr>";
		//}
	}
	echo "
	</table>";
	}
}

elseif ($_GET['lista']=="relacion_cheques_op") {
	$where='';
	if ($filtro!="") $where="o.numero_orden LIKE '%$filtro%' OR p.beneficiario LIKE '%$filtro%' OR b.denominacion LIKE '%$filtro%' OR c.numero_cuenta LIKE '%$filtro%' OR p.numero_cheque LIKE '%$filtro%'"; else $where ="";
	echo "
	<h2 align='center'>Relación Cheques/OP</h2><br />
	<h2 class='sqlmVersion'></h2><br />
	<table align='center'>
		<tr>
			<td>Buscar:</td>
			<td><input type='text' name='filtro' id='filtro' size='50' /></td>
			<td><input type='submit' name='buscar' id='buscar' value='Buscar' class='button'></td>
		</tr>
	</table><br />";
	
	echo "
	<table class='Browse' align=center cellpadding='0' cellspacing='0' width='100%'>
	  <thead>
		<tr>
			<td width='10%' align='center' class='Browse' style='font-size:9px'>Orden Pago</td>
            <td width='37%' align='center' class='Browse' style='font-size:9px'>Beneficiario</td>
            <td width='11%' align='center' class='Browse' style='font-size:9px'>Monto Cheque</td>
            <td width='10%' align='center' class='Browse' style='font-size:9px'>Banco</td>
            <td align='center' class='Browse' style='font-size:9px'>Cuenta</td>
            <td align='center' class='Browse' style='font-size:9px'>Numero Cheque</td>
            <td width='6%' align='center' class='Browse' style='font-size:9px'>Seleccionar</td>
		</tr>
	  </thead>";
	$query=mysql_query("SELECT p.idpagos_financieros, p.idorden_pago, p.beneficiario, p.monto_cheque, p.numero_cheque, o.numero_orden, 
								c.numero_cuenta, b.denominacion 
						FROM pagos_financieros p 
							INNER JOIN orden_pago o ON (p.idorden_pago=o.idorden_pago) 
							INNER JOIN cuentas_bancarias c ON (p.idcuenta_bancaria=c.idcuentas_bancarias) 
							INNER JOIN banco b ON (c.idbanco=b.idbanco) 
						WHERE $where");
	while ($field=mysql_fetch_array($query)) {
		$monto_cheque=number_format($field['monto_cheque'], 2, ',', '.');
		echo "
		<tr bgcolor='#e7dfce' onMouseOver='setRowColor(this, 0, \"over\", \"#e7dfce\", \"#EAFFEA\", \"#FFFFAA\")' onMouseOut='setRowColor(this, 0, \"out\", \"#e7dfce\", \"#EAFFEA\", \"#FFFFAA\")' id='".$field['idpagos_financieros']."' style='cursor:pointer;' onclick='seleccionarEmisionPago(\"".$field['idorden_pago']."\", \"".$field['beneficiario']."\", \"".$field['numero_orden']."\");'>
			<td class='Browse' align='center'>".$field['numero_orden']."&nbsp;</td>
			<td class='Browse'>".utf8_decode($field['beneficiario'])."&nbsp;</td>
			<td class='Browse' align='right'>".$monto_cheque."&nbsp;</td>
			<td class='Browse'>".utf8_decode($field['denominacion'])."&nbsp;</td>
			<td class='Browse' align='center'>".$field['numero_cuenta']."&nbsp;</td>
			<td class='Browse' align='center'>".$field['numero_cheque']."&nbsp;</td>
			<td class='Browse' align='center' class='Browse'><a href='#' onclick='seleccionarEmisionPago(\"".$field['idorden_pago']."\", \"".$field['beneficiario']."\", \"".$field['numero_orden']."\");'><img src='../../imagenes/validar.png'></a></td>
		</tr>";
	}
	echo "
	</table>";
}

elseif ($_GET['lista']=="orden_compra_servicio") {
	if ($estado!="") $where=" AND (ocs.estado='".$estado."')";
	if ($tipo!="") $where=" AND (td.idtipos_documentos='".$tipo."')";
	if ($filtro!="") $where=" AND (ocs.numero_orden LIKE '%".$filtro."%' OR b.nombre LIKE '%".$filtro."%' OR ocs.justificacion LIKE '%".$filtro."%')";
	echo "
	<h2 align='center'>Ordenes de Compra y Servicio</h2><br />
	<h2 class='sqlmVersion'></h2><br />
	<table align='center'>
		<tr>
			<td>Tipo Documento:</td>
			<td>Estado:</td>
			<td>Nro. Orden / Beneficiario:</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>
				<select name='tipo' id='tipo'>
					<option value''>::. Seleccione .::</option>";
					$sql="SELECT idtipos_documentos, descripcion FROM tipos_documentos WHERE modulo like '%-3-%' ORDER BY idtipos_documentos";
					$query=mysql_query($sql) or die ($sql.mysql_error());
					while ($field=mysql_fetch_array($query)) {
						echo"<option value='".$field['idtipos_documentos']."'>".$field['descripcion']."</option>";
					}
					echo "
				</select>
			</td>
			<td>
				<select name='estado' id='estado'>
					<option value=''>::. Seleccione .::</option>
					<option value='elaboracion'>En Elaboracion</option>
					<option value='procesado'>Procesado</option>
					<option value='conformado'>Conformado</option>
					<option value='devuelto'>Devuelto</option>
					<option value='ordenada'>Ordenada</option>
					<option value='anulada'>Anulada</option>
					<option value='pagado'>Pagado</option>
				</select>
			</td>
			<td><input type='text' name='filtro' id='filtro' size='50' /></td>
			<td><input type='submit' name='buscar' id='buscar' value='Buscar' class='button'></td>
		</tr>
	</table><br />";
	
	echo "
	<table class='Browse' align=center cellpadding='0' cellspacing='0' width='100%'>
	  <thead>
		<tr>
			<td width='10%' align='center' class='Browse' style='font-size:9px'>Numero</td>
            <td width='35%' align='center' class='Browse' style='font-size:9px'>Proveedor</td>
            <td width='35%' align='center' class='Browse' style='font-size:9px'>Justificacion</td>
            <td width='15%' align='center' class='Browse' style='font-size:9px'>Estado</td>
            <td width='6%' align='center' class='Browse' style='font-size:9px'>Seleccionar</td>
		</tr>
	  </thead>";
	$query=mysql_query("SELECT ocs.idorden_compra_servicio, ocs.numero_orden, ocs.justificacion, ocs.estado, b.nombre AS Beneficiario, td.modulo FROM orden_compra_servicio ocs INNER JOIN beneficiarios b ON (ocs.idbeneficiarios=b.idbeneficiarios) INNER JOIN tipos_documentos td ON (ocs.tipo=td.idtipos_documentos AND (td.modulo like '%-1-%' OR td.modulo like '%-13-%')) $where"); 
	while ($field=mysql_fetch_array($query)) {
		$monto_cheque=number_format($field['monto_cheque'], 2, ',', '.');
		echo "
		<tr bgcolor='#e7dfce' onMouseOver='setRowColor(this, 0, \"over\", \"#e7dfce\", \"#EAFFEA\", \"#FFFFAA\")' onMouseOut='setRowColor(this, 0, \"out\", \"#e7dfce\", \"#EAFFEA\", \"#FFFFAA\")' id='".$field['idorden_compra_servicio']."' style='cursor:pointer;' onclick='seleccionarOrdenCompraServicio(\"".$field['idorden_compra_servicio']."\", \"".$field['Beneficiario']."\", \"".$field['numero_orden']."\");'>
			<td class='Browse' align='center'>".$field['numero_orden']."&nbsp;</td>
			<td class='Browse'>".utf8_decode($field['Beneficiario'])."&nbsp;</td>
			<td class='Browse'>".utf8_decode($field['justificacion'])."&nbsp;</td>
			<td class='Browse' align='center'>".$field['estado']."&nbsp;</td>
			<td class='Browse' align='center' class='Browse'><a href='#' onclick='seleccionarOrdenCompraServicio(\"".$field['idorden_compra_servicio']."\", \"".$field['Beneficiario']."\", \"".$field['numero_orden']."\");'><img src='../../imagenes/validar.png'></a></td>
		</tr>";
	}
	echo "
	</table>";
}

elseif ($_GET['lista']=="conceptos") {
	if ($tipo != "") $where .= " AND (tipo_concepto = '".$tipo."')";
	if ($filtro != "") $where .= " AND (codigo LIKE '%".$filtro."%' OR descripcion LIKE '%".$filtro."%')";
	echo "
	<h2 align='center'>Conceptos</h2><br />
	<h2 class='sqlmVersion'></h2><br />
	<table align='center'>
		<tr>
			<td colspan='2'>Buscar:</td>
		</tr>
		<tr>
			<td><input type='text' name='filtro' id='filtro' size='50' /></td>
			<td><input type='submit' name='buscar' id='buscar' value='Buscar' class='button'></td>
		</tr>
	</table><br />";
	
	echo "
	<table class='Browse' align=center cellpadding='0' cellspacing='0' width='100%'>
	  <thead>
		<tr>
			<td width='20%' class='Browse' style='font-size:9px'>C&oacute;çdigo</td>
            <td align='center' class='Browse' style='font-size:9px'>Descripci&oacute;n</td>
            <td width='6%' align='center' class='Browse' style='font-size:9px'>Seleccionar</td>
		</tr>
	  </thead>";
	$query=mysql_query("SELECT * FROM articulos_servicios WHERE 1 $where") or die (mysql_error()); 
	while ($field=mysql_fetch_array($query)) {
		echo "
		<tr bgcolor='#e7dfce' onMouseOver='setRowColor(this, 0, \"over\", \"#e7dfce\", \"#EAFFEA\", \"#FFFFAA\")' onMouseOut='setRowColor(this, 0, \"out\", \"#e7dfce\", \"#EAFFEA\", \"#FFFFAA\")' id='".$field['idarticulos_servicios']."' style='cursor:pointer;' onclick='seleccionarConcepto(\"".$field['idarticulos_servicios']."\", \"".$field['descripcion']."\");'>
			<td class='Browse'>".htmlentities($field['codigo'])."&nbsp;</td>
			<td class='Browse'>".htmlentities($field['descripcion'])."&nbsp;</td>
			<td class='Browse' align='center' class='Browse'><a href='#' onclick='seleccionarConcepto(\"".$field['idarticulos_servicios']."\", \"".$field['descripcion']."\");'><img src='../../imagenes/validar.png'></a></td>
		</tr>";
	}
	echo "
	</table>";
}

elseif ($_GET['lista']=="conceptos_constantes") {
	?>
    <br>
    <h4 align=center>Lista de Conceptos y Constantes</h4>
    <h2 class="sqlmVersion"></h2>
    <br>    
    <form name="buscar" action="lista.php?lista=conceptos_constantes" method="POST">
    <table align=center cellpadding=2 cellspacing=0>
        <tr>
            <td align='right' class='viewPropTitle'>Buscar:</td>
            <td class=''>
                <select name="buscar" id="buscar">
                <option <? if($_POST["buscar"] == "conceptos_nomina"){ echo "selected";}?> value="conceptos_nomina">Conceptos</option>
                <option <? if($_POST["buscar"] == "constantes_nomina"){ echo "selected";}?> value="constantes_nomina">Constantes</option>
                </select>
			</td>
            <td align='right' class='viewPropTitle'><input type="text" name="campoBuscar" id="campoBuscar" value="<?=$_POST["campoBuscar"]?>"></td>
            <td>
            	<input align=center name="boton_buscar" type="submit" value="Buscar" class="button">
            </td>
        </tr>
    </table>
    </form>
    
   <div align="center">
    <?
    if($_POST){
		if($_POST["buscar"] == "conceptos_nomina")
			$sql= mysql_query("select idconceptos_nomina As id, codigo, descripcion from conceptos_nomina where descripcion like '%".$_POST["campoBuscar"]."%'");
		else
			$sql= mysql_query("select idconstantes_nomina As id, codigo, descripcion from constantes_nomina where descripcion like '%".$_POST["campoBuscar"]."%'");
	?>
			<table class="Main" cellpadding="0" cellspacing="0" width="80%">
				<tr>
					<td>
						<form name="grilla" action="lista_cargos.php" method="POST">
						<table class="Browse" cellpadding="0" cellspacing="0" width="100%">
							<thead>
								<tr>
									<!--<td class="Browse">&nbsp;</td>-->
									<td align="center" class="Browse">C&oacute;digo</td>
									<td align="center" class="Browse">Descripci&oacute;n</td>
									<td align="center" class="Browse" colspan="2">Selecci&oacute;n</td>
								</tr>
							</thead>
							
							<?php
								while($bus= mysql_fetch_array($sql)){
								?>
								<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onclick="seleccionarConceptoConstante('<?=$bus['id']?>', '<?=$bus['codigo']?> - <?=$bus['descripcion']?>', '<?=$_POST["buscar"]?>');">
                                    <td align='center' class='Browse' width='20%'><?=$bus["codigo"]?>&nbsp;</td>
                                    <td align='left' class='Browse'><?=utf8_decode($bus["descripcion"])?>&nbsp;</td>
                                  <td align='center' class='Browse' width='7%'> 
                                    <a href='#' onClick="">
                                    <img src='../../imagenes/validar.png' onclick="seleccionarConceptoConstante('<?=$bus['id']?>', '<?=$bus['codigo']?> - <?=$bus['descripcion']?>', '<?=$_POST["buscar"]?>');">
                                    </a>
                                  </td>
								</tr>
								<?
                                }
							?>
						</table>
						</form>
					</td>
				</tr>
			</table>
   <?
	}
   ?>
		</div>
    <?
}

elseif ($_GET['lista']=="unidad_funcional") {
	if ($filtro!="") $where="AND (denominacion LIKE '%$filtro%' OR codigo LIKE '%$filtro%')"; else $where ="";
	echo "
	<h2 align='center'>Unidad Funcional</h2><br />
	<h2 class='sqlmVersion'></h2><br />
	<table align='center'>
		<tr>
			<td>Buscar:</td>
			<td><input type='text' name='filtro' id='filtro' size='50' /></td>
			<td><input type='submit' name='buscar' id='buscar' value='Buscar' class='button'></td>
		</tr>
	</table><br />";
	
	echo "
	<table class='Browse' align=center cellpadding='0' cellspacing='0' width='100%'>
	  <thead>
		<tr>
			<td width='75' class='Browse' align='center'>C&oacute;digo</td>
			<td class='Browse' align='center'>Denominaci&oacute;n</td>
			<td width='75' class='Browse' align='center'>Acciones</td>
		</tr>
	  </thead>";
	  $sql="SELECT idniveles_organizacionales, codigo, denominacion FROM niveles_organizacionales $where";
	$query=mysql_query($sql) or die (mysql_error());
	while ($field=mysql_fetch_array($query)) {
		$partida=$field[3].".".$field[4].".".$field[5].".".$field[6];
		echo "
		<tr bgcolor='#e7dfce' onMouseOver='setRowColor(this, 0, \"over\", \"#e7dfce\", \"#EAFFEA\", \"#FFFFAA\")' onMouseOut='setRowColor(this, 0, \"out\", \"#e7dfce\", \"#EAFFEA\", \"#FFFFAA\")' id='".$field['idniveles_organizacionales']."' style='cursor:pointer;' onclick='seleccionarUnidadFuncional(\"$field[0]\", \"$field[1]\", \"$field[2]\");'>
			<td class='Browse' align='center'>".$field['codigo']."&nbsp;</td>
			<td class='Browse'>".utf8_decode($field['denominacion'])."&nbsp;</td>
			<td class='Browse' align='center' class='Browse'><a href='#' onclick='seleccionarUnidadFuncional(\"$field[0]\", \"$field[1]\", \"$field[2]\");'><img src='../../imagenes/validar.png'></a></td>
		</tr>";
	}
	echo "</table>";
}

elseif ($_GET['lista']=="centro_costos") {
	if ($filtro!="") $where="AND (denominacion LIKE '%$filtro%' OR codigo LIKE '%$filtro%')"; else $where ="";
	echo "
	<h2 align='center'>Unidad Funcional</h2><br />
	<h2 class='sqlmVersion'></h2><br />
	<table align='center'>
		<tr>
			<td>Buscar:</td>
			<td><input type='text' name='filtro' id='filtro' size='50' /></td>
			<td><input type='submit' name='buscar' id='buscar' value='Buscar' class='button'></td>
		</tr>
	</table><br />";
	
	echo "
	<table class='Browse' align=center cellpadding='0' cellspacing='0' width='100%'>
	  <thead>
		<tr>
			<td width='75' class='Browse' align='center'>C&oacute;digo</td>
			<td class='Browse' align='center'>Denominaci&oacute;n</td>
			<td width='75' class='Browse' align='center'>Acciones</td>
		</tr>
	  </thead>";
	  /*$sql="SELECT
	  			no.idniveles_organizacionales,
				cp.codigo,
				ue.denominacion
			FROM
				categoria_programatica cp
				INNER JOIN unidad_ejecutora ue ON (cp.idunidad_ejecutora = ue.idunidad_ejecutora)
				INNER JOIN niveles_organizacionales no ON (cp.idcategoria_programatica = no.idcategoria_programatica AND no.idcategoria_programatica <> '0')
			ORDER BY codigo";*/
			
	  $sql="SELECT
	  			no.idcategoria_programatica,
				cp.codigo,
				ue.denominacion
			FROM
				categoria_programatica cp
				INNER JOIN unidad_ejecutora ue ON (cp.idunidad_ejecutora = ue.idunidad_ejecutora)
				INNER JOIN niveles_organizacionales no ON (cp.idcategoria_programatica = no.idcategoria_programatica)
			WHERE no.idcategoria_programatica <> '0' AND no.modulo = '1'
			GROUP BY idcategoria_programatica
			ORDER BY codigo";
	$query=mysql_query($sql) or die (mysql_error());
	while ($field=mysql_fetch_array($query)) {
		$partida=$field[3].".".$field[4].".".$field[5].".".$field[6];
		echo "
		<tr bgcolor='#e7dfce' onMouseOver='setRowColor(this, 0, \"over\", \"#e7dfce\", \"#EAFFEA\", \"#FFFFAA\")' onMouseOut='setRowColor(this, 0, \"out\", \"#e7dfce\", \"#EAFFEA\", \"#FFFFAA\")' id='".$field['idcategoria_programatica']."' style='cursor:pointer;' onclick='seleccionarCentroCosto(\"$field[0]\", \"$field[1]\", \"$field[2]\");'>
			<td class='Browse' align='center'>".$field['codigo']."&nbsp;</td>
			<td class='Browse'>".utf8_decode($field['denominacion'])."&nbsp;</td>
			<td class='Browse' align='center' class='Browse'><a href='#' onclick='seleccionarCentroCosto(\"$field[0]\", \"$field[1]\", \"$field[2]\");'><img src='../../imagenes/validar.png'></a></td>
		</tr>";
	}
	echo "</table>";
}

elseif ($_GET['lista']=="trabajadores") {
	?>
    <br>
	<h4 align=center>Listado de Trabajadores</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
	<?php //echo $formulario;?>
	<form name="buscar" action="lista_trabajador.php" method="POST">
	<table align=center cellpadding=2 cellspacing=0>
		<tr>
			<td align='right' class='viewPropTitle'>Buscar:</td>
			<td class=''><input type="text" name="textoabuscar" maxlength="30" size="30"></td>
			<td align='right' class='viewPropTitle'>Por:</td>
			<td class=''>
				<select name="tipobusqueda">
					<option VALUE="c">C&eacute;dula</option>
					<option VALUE="a">Apellidos</option>
					<option VALUE="n">Nombres</option>
				</select> 
			</td>
			<td>
				<input align=center name="buscar" type="submit" value="Buscar">
				</a>
			</td>
		</tr>
	</table>
	</form>
	
	<div align="center">
			<table class="Main" cellpadding="0" cellspacing="0" width="80%">
				<tr>
					<td>
						<table class="Browse" cellpadding="0" cellspacing="0" width="100%">
							<thead>
								<tr>
									<!--<td class="Browse">&nbsp;</td>-->
									<td align="center" class="Browse">C&eacute;dula</td>
									<td align="center" class="Browse">Apellidos</td>
									<td align="center" class="Browse">Nombres</td>
									<td align="center" class="Browse" colspan="2">Selecci&oacute;n</td>
								</tr>
							</thead>
							
							<?php
								$registros_grilla = mysql_query("select * from trabajador order by length(cedula), cedula") or die (mysql_error());
								while($llenar_grilla= mysql_fetch_array($registros_grilla)){
								 $c=$llenar_grilla["cedula"];
								 $id_trabajador = $llenar_grilla["idtrabajador"];
								?>
									<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" id="<?=$llenar_grilla['idtrabajador']?>" onClick="seleccionarTrabajador('<?=$llenar_grilla['idtrabajador']?>', '<?=$llenar_grilla['apellidos']?> <?=$llenar_grilla['nombres']?>');">
								<?php
									echo "<td align='right' class='Browse' width='20%'>".$llenar_grilla["cedula"]."</td>";
									echo "<td align='left' class='Browse'>".utf8_decode($llenar_grilla["apellidos"])."&nbsp;</td>";
									echo "<td align='left' class='Browse'>".utf8_decode($llenar_grilla["nombres"])."&nbsp;</td>";
									?>
									<td align='center' class='Browse' width='7%'><a href='#' onClick="seleccionarTrabajador('<?=$llenar_grilla['idtrabajador']?>', '<?=$llenar_grilla['apellidos']?> <?=$llenar_grilla['nombres']?>');"><img src='../../imagenes/validar.png'></a></td>
                                    <?
								echo "</tr>";
							}
							?>
						</table>
					</td>
				</tr>
			</table>
		</div>
    <?	
}

elseif ($_GET['lista']=="ordenes_compra_servicio") {
	?>
	<h2 align="center">
     <?
     if($_SESSION["modulo"] == "4" || $_SESSION["modulo"] == "1"){
	   	echo "Certificaci&oacute;n de Compromiso";
	 }else if($_SESSION["modulo"] == "16"){
		 echo "CAJA CHICA";
	 }else{
	 	echo "Ordenes de Compra y Servicio";
	 }
	 ?>
     </h2>
     <br />
     	<h2 class="sqlmVersion"></h2>
<br> 
     <form action="" method="post"> 
       
       <input type="hidden" name="destino" id="destino" <? if($_GET["destino"]){ echo "value='".$_GET["destino"]."'";}else{echo "value='".$_POST["destino"]."'";}?>>
       <table width="85%" border="0" align="center">
<tr>
  <td width="21%">Tipo de Documento</td>
  <td width="17%">
      <?

	  	if($_GET["destino"] == "apertura"){
			$sql_tipos_documentos = mysql_query("select * from tipos_documentos where modulo like  '%-".$_SESSION["modulo"]."-%' and compromete = 'no' and causa = 'no' and paga = 'no'");
		}else{
			$sql_tipos_documentos = mysql_query("select * from tipos_documentos where modulo like  '%-".$_SESSION["modulo"]."-%' and compromete = 'si' and causa = 'no' and paga = 'no'");
		}
	?>
    <select name="tipo_orden" id="tipo_orden">
    <option value="0">.:: Seleccione ::.</option>
	<?
    while($bus_tipos_documentos = mysql_fetch_array($sql_tipos_documentos)){
	?>
		<option <? if($bus_tipos_documentos["idtipos_documentos"] == $_POST["tipo_orden"]){echo "selected";}?> value="<?=$bus_tipos_documentos["idtipos_documentos"]?>"><?=$bus_tipos_documentos["descripcion"]?></option>
	<?
	}
	?>
        </select>  
  
  </td>
          <td width="22%"><div align="center">Estado de la Orden:<br>
                <select name="estado_cotizacion">
                	<option value="0">.:: Seleccione ::.</option>
                  <option <? if($_POST["estado_cotizacion"] == "elaboracion"){echo "selected";}?> value="elaboracion">En Elaboracion</option>
                  <option <? if($_POST["estado_cotizacion"] == "procesado"){echo "selected";}?> value="procesado">Procesado</option>
                  <option <? if($_POST["estado_cotizacion"] == "conformado"){echo "selected";}?> value="conformado">Conformado</option>
                  <option <? if($_POST["estado_cotizacion"] == "devuelto"){echo "selected";}?> value="devuelto">Devuelto</option>
                  <option <? if($_POST["estado_cotizacion"] == "ordenado"){echo "selected";}?> value="ordenadas">Ordenada</option>
                  <option <? if($_POST["estado_cotizacion"] == "anulado"){echo "selected";}?> value="anulado">Anulado</option>
                  <option <? if($_POST["estado_cotizacion"] == "pagado"){echo "selected";}?> value="pagado">Pagado</option>
              </select>
      </div></td>
  <td width="27%"><div align="center">Nro Orden / Beneficiario:<br>
      <input type="text" name="texto_busqueda" id="texto_busqueda"  size="40" value="<?=$_POST["texto_busqueda"]?>">
  </div></td>
      <td width="13%"><label>
        <input type="submit" name="buscar" id="buscar" value="Buscar" class="button">
      </label></td>
         </tr>
       </table>
       <input type="hidden" id="accion" name="accion" value="<?=$_GET["accion"]?>">
     </form>
        
  <?
  if($_POST["buscar"]){
		
		if ($_SESSION["modulo"] == '1' or $_SESSION["modulo"] == '13'){
			$query = "select * from orden_compra_servicio, beneficiarios, tipos_documentos  
						where beneficiarios.idbeneficiarios = orden_compra_servicio.idbeneficiarios ";
						if($_POST["texto_busqueda"] != ""){
							$query .= "and (beneficiarios.nombre like '%".$_POST["texto_busqueda"]."%' 
										or orden_compra_servicio.numero_orden like '%".$_POST["texto_busqueda"]."%') ";
						}
						
						if($_POST["estado_cotizacion"] != "0"){
								$query .= "and orden_compra_servicio.estado = '".$_POST["estado_cotizacion"]."' ";
							}
						
						if($_POST["tipo_orden"] != "0"){
							$query .= "and tipos_documentos.idtipos_documentos = '".$_POST["tipo_orden"]."'"; 
						}
						$query .= "and tipos_documentos.idtipos_documentos = orden_compra_servicio.tipo 
						and (tipos_documentos.modulo like '%-1-%' or  tipos_documentos.modulo like '%-13-%')
						and tipos_documentos.compromete = 'si' 
						and tipos_documentos.causa = 'no' 
						and tipos_documentos.paga = 'no' ";
						if($_REQUEST["accion"] == 734 or $_REQUEST["accion"] == 664){
							$query .= "and tipos_documentos.multi_categoria = 'si'";
						}else{
							$query .= "and tipos_documentos.multi_categoria = 'no'";
						}
						$query .= " order by orden_compra_servicio.codigo_referencia";
					
		}else if($_SESSION["modulo"] == '16'){
			
			$query = "select * from orden_compra_servicio, beneficiarios, tipos_documentos  
						where beneficiarios.idbeneficiarios = orden_compra_servicio.idbeneficiarios ";
						if($_POST["texto_busqueda"] != ""){
							$query .= "and (beneficiarios.nombre like '%".$_POST["texto_busqueda"]."%' 
										or orden_compra_servicio.numero_orden like '%".$_POST["texto_busqueda"]."%') ";
						}
						
						if($_POST["estado_cotizacion"] != "0"){
								$query .= "and orden_compra_servicio.estado = '".$_POST["estado_cotizacion"]."' ";
							}
						
						if($_POST["tipo_orden"] != "0"){
							$query .= "and tipos_documentos.idtipos_documentos = '".$_POST["tipo_orden"]."'"; 
						}
						$query .= "and tipos_documentos.idtipos_documentos = orden_compra_servicio.tipo 
						and tipos_documentos.modulo like '%-".$_SESSION["modulo"]."-%' 
						and tipos_documentos.compromete = 'no' 
						and tipos_documentos.causa = 'no' 
						and tipos_documentos.paga = 'no' ";
						
						if($_REQUEST["accion"] == 734 or $_REQUEST["accion"] == 664){
							$query .= "and tipos_documentos.multi_categoria = 'si'";
						}else{
							$query .= "and tipos_documentos.multi_categoria = 'no'";
						}
						$query .= " order by orden_compra_servicio.codigo_referencia";
		}else{

			$query = "select * from orden_compra_servicio, beneficiarios, tipos_documentos  
						where beneficiarios.idbeneficiarios = orden_compra_servicio.idbeneficiarios ";
						if($_POST["texto_busqueda"] != ""){
							$query .= "and (beneficiarios.nombre like '%".$_POST["texto_busqueda"]."%' 
										or orden_compra_servicio.numero_orden like '%".$_POST["texto_busqueda"]."%') ";
						}
						
						if($_POST["estado_cotizacion"] != "0"){
								$query .= "and orden_compra_servicio.estado = '".$_POST["estado_cotizacion"]."' ";
							}
						
						if($_POST["tipo_orden"] != "0"){
							$query .= "and tipos_documentos.idtipos_documentos = '".$_POST["tipo_orden"]."'"; 
						}
						$query .= "and tipos_documentos.idtipos_documentos = orden_compra_servicio.tipo 
						and tipos_documentos.modulo like '%-".$_SESSION["modulo"]."-%' 
						and tipos_documentos.compromete = 'si' 
						and tipos_documentos.causa = 'no' 
						and tipos_documentos.paga = 'no' ";
						
						if($_REQUEST["accion"] == 734 or $_REQUEST["accion"] == 664){
							$query .= "and tipos_documentos.multi_categoria = 'si'";
						}else{
							$query .= "and tipos_documentos.multi_categoria = 'no'";
						}
						$query .= " order by orden_compra_servicio.codigo_referencia";
			}
		//echo $query;
		
		$sql_ordenes = mysql_query($query);
		
		$num_ordenes = mysql_num_rows($sql_ordenes);
		if($num_ordenes != 0){	
  ?>      <br />
  
        <table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="96%">
          <thead>
          <tr>
          	<td class="Browse" width="8%"><div align="center">N&uacute;mero</div></td>
            <td class="Browse" width="12%"><div align="center">Fecha Orden</div></td>
            <td class="Browse" width="31%"><div align="center">Proveedor</div></td>
            <td class="Browse" width="34%"><div align="center">Justificacion</div></td>
            <td class="Browse" width="7%"><div align="center">Estado</div></td>
            <td class="Browse" width="6%"><div align="center">Acciones</div></td>
          </tr>
          </thead>
          <? 
		  
         
          while($bus_ordenes = mysql_fetch_array($sql_ordenes)){

		  ?>
                  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" id="<?=$bus_ordenes['idorden_compra_servicio']?>" onClick="seleccionarOrdenesCompraServicio('<?=$bus_ordenes['idorden_compra_servicio']?>', '<?=$bus_ordenes['numero_orden']?>', '<?=$bus_ordenes['nombre']?>');">
                    <td class='Browse' align='left'>&nbsp;<?=$bus_ordenes["numero_orden"]?></td>
                    <td class='Browse' align='center'>
						<?
                        if($bus_ordenes["fecha_orden"] == '0000-00-00'){
							echo "<strong>No Procesada</strong>";
						}else{
							echo $bus_ordenes["fecha_orden"];
						}
						?>
                    </td>
                    <td class='Browse' align='left'><?=utf8_decode($bus_ordenes["nombre"])?></td>
                    <td class='Browse' align='left'><?=utf8_decode($bus_ordenes["justificacion"])?></td>
                    <td class='Browse' align='left'><?=$bus_ordenes["estado"]?></td>
                    <td class='Browse' align="center">
                    <a href="#" onClick="seleccionarOrdenesCompraServicio('<?=$bus_ordenes['idorden_compra_servicio']?>', '<?=$bus_ordenes['numero_orden']?>', '<?=$bus_ordenes['nombre']?>');"><img src="../../imagenes/validar.png"></a>
                    </td>
                    
                  </tr>
          <?
          }
		  
          ?>
          
        </table>
 <?
  }else{
 echo "<center><strong>No se encontraron resultados</strong></center>";
 }
 
 }
}

?>
</form>
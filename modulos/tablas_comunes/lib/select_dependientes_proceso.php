<?php
session_start();
extract($_GET);
if ($listado=="listadoMovimientos") $listadoSelects=unserialize($_SESSION['listadoMovimientos']);
elseif ($listado=="listadoSNC") $listadoSelects=unserialize($_SESSION['listadoSNC']);
elseif ($listado=="listadoCuentas") $listadoSelects=unserialize($_SESSION['listadoCuentas']);
elseif ($listado=="listadoProgramas") $listadoSelects=unserialize($_SESSION['listadoProgramas']);
elseif ($listado=="listadoCatalogo") $listadoSelects=unserialize($_SESSION['listadoCatalogo']);
elseif ($listado=="listadoOrganizacion") $listadoSelects=unserialize($_SESSION['listadoOrganizacion']);
elseif ($listado=="listadoPeriodoNomina") $listadoSelects=unserialize($_SESSION['listadoPeriodoNomina']);

include_once("../../../conf/conex.php");
$tabla=$listadoSelects[$selectDestino]; echo $tabla;
/*

OJO  NO ESTA PASANDO EL ARRAY POR LA VARIABLE DE SESION 

/*



// Array que vincula los IDs de los selects declarados en el HTML con el nombre de la tabla donde se encuentra su contenido

$listadoSelects=array(
"pais"=>"pais",
"estados"=>"estado",
"select3"=>"municipios"
);
*/

function validaSelect($selectDestino)
{
	// Se valida que el select enviado via GET exista
	global $listadoSelects;
	if(isset($listadoSelects[$selectDestino])) return true;
	else return false;
}

function validaOpcion($opcionSeleccionada)
{
	// Se valida que la opcion seleccionada por el usuario en el select tenga un valor numerico
	if(is_numeric($opcionSeleccionada)) return true;
	else return false;
}

function listar_foros($padre, $titulo, $tab) {
	global $foros;
	$x = $tab."&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;";
	foreach($foros[$padre] as $foro => $datos) {			
		if(isset($foros[$foro])) {
			//$nuevo_titulo = ($titulo == '') ? $datos['denominacion'] : "$titulo - {$datos['denominacion']} -";
			?>
			<option value="<?=$datos['idniveles_organizacionales']?>">
				<?=$x.' .- '.$datos['denominacion']?>
			</option>
			<?
			listar_foros($foro, $nuevo_titulo, $x);
		}else{
			?>
			<option value="<?=$datos['idniveles_organizacionales']?>">
				<?=$x.' .- '.$datos['denominacion']?>
			</option>
			<?
		}
	}
}

$selectDestino=$_GET["select"]; $opcionSeleccionada=$_GET["opcion"];
if ($opcionSeleccionada=="ingreso") $afecta="a"; else $afecta="d";

if(validaSelect($selectDestino))
{
	$tabla=$listadoSelects[$selectDestino];
	conectarse();
	
	if ($tabla=="estado")
	   $consulta=mysql_query("SELECT idestado,denominacion FROM $tabla WHERE idpais='$opcionSeleccionada' and status='a' ORDER BY denominacion") or die(mysql_error());
	elseif ($tabla=="municipios")
	   $consulta=mysql_query("SELECT idmunicipios,denominacion FROM $tabla WHERE idestado='$opcionSeleccionada' and status='a' ORDER BY denominacion") or die(mysql_error());
	//--   
	elseif ($tabla=="familia")
	   $consulta=mysql_query("SELECT idsnc_familia_actividad, descripcion FROM snc_familia_actividad WHERE idsnc_actividades='$opcionSeleccionada' ORDER BY codigo") or die(mysql_error());  
	elseif ($tabla=="grupo")
	   $consulta=mysql_query("SELECT idsnc_grupo_actividad, descripcion FROM snc_grupo_actividad WHERE idsnc_familia_actividad='$opcionSeleccionada' ORDER BY codigo") or die(mysql_error()); 
	//-- 
	elseif ($tabla=="movimiento")
	   $consulta=mysql_query("SELECT idtipo_movimiento_bancario, denominacion FROM tipo_movimiento_bancario WHERE afecta='$afecta' ORDER BY denominacion") or die(mysql_error());  
	elseif ($tabla=="cuenta")
	   $consulta=mysql_query("SELECT idcuentas_bancarias, numero_cuenta FROM cuentas_bancarias WHERE idbanco='$opcionSeleccionada' ORDER BY idbanco") or die(mysql_error());  
	//-- 
	elseif ($tabla=="programa")
	   $consulta=mysql_query("SELECT idprograma, CONCAT(codigo,' - ',denominacion) as nombre  FROM programa WHERE idsector='$opcionSeleccionada' ORDER BY codigo") or die(mysql_error());  
	//--  
	elseif ($tabla=="sub_grupo")
	   $consulta=mysql_query("SELECT idsubgrupo_catalogo_bienes, CONCAT('(', codigo, ')', ' ', denominacion) FROM subgrupo_catalogo_bienes WHERE idgrupo_catalogo_bienes='$opcionSeleccionada' ORDER BY codigo") or die(mysql_error());  
	elseif ($tabla=="seccion")
	   $consulta=mysql_query("SELECT idsecciones_catalogo_bienes, CONCAT('(', codigo, ')', ' ', denominacion) FROM secciones_catalogo_bienes WHERE idsecciones_catalogo_bienes='$opcionSeleccionada' ORDER BY codigo") or die(mysql_error());
	//--  
	elseif ($tabla=="periodo")
	   $consulta=mysql_query("SELECT 
							 		rpn.idrango_periodo_nomina, 
									CONCAT(rpn.desde, ' - ', rpn.hasta) AS periodo 
								FROM 
									rango_periodo_nomina rpn 
									INNER JOIN periodos_nomina pn ON (rpn.idperiodo_nomina = pn.idperiodos_nomina)
								WHERE 
									pn.idtipo_nomina = '$opcionSeleccionada' AND pn.anio = '".$_SESSION["anio_fiscal"]."'
								ORDER BY desde, hasta") or die(mysql_error()); 
	//-- 
	elseif ($tabla=="nivel_organizacion") {
	   //$consulta=mysql_query("SELECT codigo, denominacion FROM niveles_organizacionales WHERE organizacion='$opcionSeleccionada' ORDER BY codigo") or die(mysql_error());
		$foros = array();
		$result = mysql_query("SELECT idniveles_organizacionales, 
										denominacion, 
										sub_nivel,
										codigo 
								FROM 
										niveles_organizacionales 
								WHERE 
										organizacion = '".$opcionSeleccionada."'
										AND modulo = '".$_SESSION["modulo"]."'") or die(mysql_error());
		while($row = mysql_fetch_assoc($result)) {
			$foro = $row['idniveles_organizacionales'];
			$padre = $row['sub_nivel'];
			if(!isset($foros[$padre]))
			$foros[$padre] = array();
			$foros[$padre][$foro] = $row;
		}
		
		$tab = "";
		?>
		<select id="nivel_organizacion" name="nivel_organizacion" style="width:450px;">
        	<option value="0">Elige</option>
			<? if (mysql_num_rows($result) != 0) listar_foros(0, '', $tab); ?>
		</select>
		<?
		return;
	   
	}
	//-- 
	
	if ($tabla!="nivel_organizacion" && $tabla!="programa" ) {
		desconectar();
		// Comienzo a imprimir el select
		echo "<select name='".$selectDestino."' id='".$selectDestino."' onChange='cargaContenido(this.id, \"$listado\")' class='Select1'>";
		echo "<option value='0'>Elige</option>";
		while($registro=mysql_fetch_row($consulta))
		{
			// Convierto los caracteres conflictivos a sus entidades HTML correspondientes para su correcta visualizacion
			$registro[1]=($registro[1]);
			// Imprimo las opciones del select
			echo "<option value='".$registro[0]."'>".$registro[1]."</option>";
		}			
		echo "</select>";
	}
	
	if ($tabla=="programa") {
		//desconectar();
		// Comienzo a imprimir el select
		echo "<select name='".$selectDestino."' id='".$selectDestino."' onChange='cargaContenido(this.id, \"$listado\")' class='Select1'>";
		echo "<option value=''>..:: TODOS ::..</option>";
		while($registro=mysql_fetch_row($consulta))
		{
			// Convierto los caracteres conflictivos a sus entidades HTML correspondientes para su correcta visualizacion
			$registro[1]=($registro[1]);
			// Imprimo las opciones del select
			echo "<option value='".$registro[0]."'>".$registro[1]."</option>";
		}			
		echo "</select>";
	}
	
	
}
?>
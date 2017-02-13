<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");

$conexion = Conectarse();
extract($_POST);
extract($_SESSION);


function diferenciaEntreFechas($fechaInicio, $fechaActual){
list($anioInicio, $mesInicio, $diaInicio) = explode("-", $fechaInicio);
list($anioActual, $mesActual, $diaActual) = explode("-", $fechaActual);

$b = 0;  
$mes = $mesInicio-1;  
if($mes==2){  
if(($anioActual%4==0 && $anioActual%100!=0) || $anioActual%400==0){  
$b = 29;  
}else{  
$b = 28;  
}  
}  
else if($mes<=7){  
if($mes==0){  
 $b = 31;  
}  
else if($mes%2==0){  
$b = 30;  
}  
else{  
$b = 31;  
}  
}  
else if($mes>7){  
if($mes%2==0){  
$b = 31;  
}  
else{  
$b = 30;  
}  
}  
if(($anioInicio>$anioActual) || ($anioInicio==$anioActual && $mesInicio>$mesActual) ||   
($anioInicio==$anioActual && $mesInicio == $mesActual && $diaInicio>$diaActual)){  
echo "La fecha de inicio ha de ser anterior a la fecha Actual";  
}else{  
if($mesInicio <= $mesActual){  
$anios = $anioActual - $anioInicio;  
if($diaInicio <= $diaActual){  
$meses = $mesActual - $mesInicio;  
$dies = $diaActual - $diaInicio;  
}else{  
if($mesActual == $mesInicio){  
$anios = $anios - 1;  
}  
$meses = ($mesActual - $mesInicio - 1 + 12) % 12;  
$dies = $b-($diaInicio-$diaActual);  
}  
}else{  
$anios = $anioActual - $anioInicio - 1;  
if($diaInicio > $diaActual){  
$meses = $mesActual - $mesInicio -1 +12;  
$dies = $b - ($diaInicio-$diaActual);  
}else{  
$meses = $mesActual - $mesInicio + 12;  
$dies = $diaActual - $diaInicio;  
}  
}  
return $anios."|.|".$meses."|.|".$dies;  
}


}
//**************************************************************************************************
//****AGREGADO POR JORGE RODRIGUEZ <ELGIGANTE31@GMAIL.COM>******************************************
//**************************************************************************************************
/**
 * Formatea una cadena con los valores dados como parametros,
 * La caneda es limpiada, es decir los espacios al inicio y al final son eliminados y los
 * espacios multiples son colocados como simples.
 * Ejemplo:
 *   'Hola {0} {1} {0}', 'mundo','adios' : 'Hola mundo adios mundo'
 */
function formatSTR(){
  $params = func_get_args();
  $params[0] = preg_replace(array('/\s+/','/^\s+/','/\s+$/s'),array(" ","",""),$params[0]);      
  return eval('return "'.str_replace('"','\"',preg_replace('#\{(\d+)\}#','$params[\\1]', array_shift($params))).'";');
}
//**************************************************************************************************
//**************************************************************************************************
//**************************************************************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************
//******************************************************* MOSTRAR PESTAÑAS *****************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************

if($ejecutar == "mostrarPestanas"){
	?>
	<ul>
        <li>
        	<a href="javascript:;" onClick="mostrarPestana('div_datosBasicos')">
        		<span>Datos B&aacute;sicos</span>
            </a>
        </li>
		<?       
        if(in_array(1091, $privilegios) == true){
		?>
        <li style="display:block" id="li_ubicacion">
        	<a href="javascript:;" onClick="mostrarPestana('div_ubicacion')">
        		<span>Ubicaci&oacute;n</span>
            </a>
        </li>
        <?
        }
		
		if(in_array(1092, $privilegios) == true){
		?>
        
        <li style="display:block" id="li_existencia">
        	<a href="javascript:;" onClick="mostrarPestana('div_existencia')">
        		<span>Existencia</span>
            </a>
        </li>
        <?
        }
		if(in_array(1093, $privilegios) == true){
		?>
        <li style="display:block" id="li_desagregaUnidad">
        	<a href="javascript:;" onClick="mostrarPestana('div_desagregaUnidad')">
        		<span>Desagregar Unidad</span>
            </a>
        </li>
        <?
        }
		if(in_array(1094, $privilegios) == true){
		?>
        <li style="display:block" id="li_reemplazoMateria">
        	<a href="javascript:;" onClick="mostrarPestana('div_reemplazoMateria')">
        		<span>Reemplazos</span>
            </a>
        </li>
        <?
        }
		if(in_array(1095, $privilegios) == true){
		?>
        <li style="display:block" id="li_equivalenciaMateria">
        	<a href="javascript:;" onClick="mostrarPestana('div_equivalenciaMateria')">
        		<span>Equivalencias</span>
            </a>
        </li>
        <?
        }
		if(in_array(1096, $privilegios) == true){
		?>
        <li style="display:block" id="li_registroFotografico">
        	<a href="javascript:;" onClick="mostrarPestana('div_registroFotografico')">
        		<span>Imagenes</span>
            </a>
        </li>
        <?
        }
		if(in_array(1097, $privilegios) == true){
		?>
        <li style="display:block" id="li_accesoriosMateria">
        	<a href="javascript:;" onClick="mostrarPestana('div_accesoriosMateria')">
        		<span>Accesorios</span>
            </a>
        </li>
        <?
        }
		if(in_array(1098, $privilegios) == true){
		?>
        <li style="display:block" id="li_compra_materia">
        	<a href="javascript:;" onClick="mostrarPestana('div_compra_materia')">
        		<span>Compra/Proveedor</span>
            </a>
        </li>
        <?
        }
		if(in_array(1099, $privilegios) == true){
		?>
        <li style="display:block" id="li_materia_articulos_servicios">
        	<a href="javascript:;" onClick="mostrarPestana('div_materia_articulos_servicios')">
        		<span>Articulos</span>
            </a>
        </li>
        <?
        }		
		?>
    </ul>
	<?
}


//******************************************************************************************************************************
//******************************************************************************************************************************
//******************************************************* ACTUALIZAR SELECT ****************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************


if ($ejecutar == "cargarSelect"){
	
	if($sel == "marca_materia"){
		$sel = "marca_materia";
		$idSelect = "marca_materia";
	}
	if($sel == "condicion_almacenaje_materia"){
		$sel = "condicion_almacenaje_materia";
		$idSelect = "condicion_almacenaje_materia";
	}
	if($sel == "condicion_conservacion_materia"){
		$sel = "condicion_conservacion_materia";
		$idSelect = "condicion_conservacion_materia";
	}
	if($sel == "forma_materia"){
		$sel = "forma_materia";
		$idSelect = "forma_materia";
	}
	if($sel == "volumen_materia"){
		$sel = "volumen_materia";
		$idSelect = "volumen_materia";
	}
	$sql_consulta = mysql_query("select * from  ".$sel." order by denominacion")or die("AQUI ES".mysql_error());
	?>
	<select id="<?=$idSelect?>">
	<?
	while($bus_consulta = mysql_fetch_array($sql_consulta)){
		?>
		<option <? if($bus_consulta["denominacion"] == $denominacion){echo "selected";}?> value="<?=$bus_consulta["id".$sel]?>"><?=$bus_consulta["denominacion"]?></option>
		<?
	}
	?>
	</select>
     <?
	if($sel == "marca_materia"){ ?>
	    <img src='imagenes/add.png' onClick="window.open('principal.php?modulo=19&accion=1074&pop=si','agregar marca','resisable = no, scrollbars = yes, width=900, height = 500')" style="cursor:pointer">	
    <? } ?>
    <?
	if($sel == "condicion_almacenaje_materia"){ ?>
	    <img src='imagenes/add.png' onClick="window.open('principal.php?modulo=19&accion=1053&pop=si','agregar almacenaje','resisable = no, scrollbars = yes, width=900, height = 500')" style="cursor:pointer">
    <? } ?>
    <?
	if($sel == "condicion_conservacion_materia"){ ?>
	    <img src='imagenes/add.png' onClick="window.open('principal.php?modulo=19&accion=1057&pop=si','agregar almacenaje','resisable = no, scrollbars = yes, width=900, height = 500')" style="cursor:pointer">
    <? } ?>
    <?
	if($sel == "forma_materia"){ ?>
	    <img src='imagenes/add.png' onClick="window.open('principal.php?modulo=19&accion=1066&pop=si','agregar almacenaje','resisable = no, scrollbars = yes, width=900, height = 500')" style="cursor:pointer">
    <? } ?>
    <?
	if($sel == "volumen_materia"){ ?>
	    <img src='imagenes/add.png' onClick="window.open('principal.php?modulo=19&accion=1062&pop=si','agregar almacenaje','resisable = no, scrollbars = yes, width=900, height = 500')" style="cursor:pointer">
    <? } ?>
	<?
}


//******************************************************************************************************************************
//******************************************************************************************************************************
//******************************************************* AUTOGENERAR CODIGO ***************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************




if($ejecutar == "generar_codigo"){
	$sql_inventario_materia = mysql_query("select * from relacion_contadores")or die(mysql_error());
	$bus_inventario_materia = mysql_fetch_array($sql_inventario_materia);
	if ($bus_inventario_materia["contador_inventario_materia"] > 0){
		$numero = $bus_inventario_materia["contador_inventario_materia"];
		$codigo_con_ceros = str_pad($bus_inventario_materia["contador_inventario_materia"], 13, "0", STR_PAD_LEFT);
	}else{
		$numero = 1;
		$codigo_con_ceros = str_pad($numero, 13, "0", STR_PAD_LEFT);
	}
	echo $numero."|.|".$codigo_con_ceros; 
}


//******************************************************************************************************************************
//******************************************************************************************************************************
//******************************************************* CARGAR TIPO DE DETALLE ***********************************************
//******************************************************************************************************************************
//******************************************************************************************************************************
if($ejecutar == "cargarTipo"){
	$sql_tipo = mysql_query("select * from tipo_detalle_almacen where iddetalle_materias_almacen = '".$iddetalle."'");
	$num_tipo = mysql_num_rows($sql_tipo);
	?>
	<select name="idtipo_detalle" id="idtipo_detalle"  style="width:50%" <? if($num_tipo == 0){echo "disabled";}?>>
      	<option value="0">.:: Seleccione ::.</option>
		<?
		while($bus_tipo = mysql_fetch_array($sql_tipo)){
			?>
				<option value="<?=$bus_tipo["idtipo_detalle_almacen"]?>" <?php if ($bus_tipo["idtipo_detalle_almacen"] == $idtipo){ echo " selected"; }?>><?=$bus_tipo["tipo"]?></option>
			<?
		}
		?>
      </select> 
	<?
}




//******************************************************************************************************************************
//******************************************************************************************************************************
//******************************************************* DATOS BASICOS ********************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************



if($ejecutar == "ingresarMateria"){
$sql_codigo= mysql_query("select * from inventario_materia where codigo='".$codigo_materia."'");
$num_codigo= mysql_num_Rows($sql_codigo);




if($num_codigo > 0){
	echo "codigo_repetido";	
}else{
	
	
	
	$result=mysql_query("insert into inventario_materia 
													(id_tipo_movimiento_almacen,
													codigo,
													descripcion,
													idunidad_medida,
													cantidad_unidad,
													iddetalle_materias_almacen,
													idtipo_detalle_almacen,
													idmarca_materia,
													modelo,
													serializado,
													caduca,
													utilidad,
													garantia,
													status) 
											values 
													('$idtipo_movimiento_almacen',
													'$codigo_materia',
													'$descripcion_materia',
													'$idunidad_medida',
													'$cantidad_unidad',
													'$iddetalle_materias_almacen',
													'$idtipo_detalle_almacen',
													'$idmarca_materia',
													'$modelo',
													'$serializado',
													'$fecha_vencimiento',
													'$utilidad',
													'$garantia',
													'a')")or die(mysql_error());
	
	
	
	
	$idmateria = mysql_insert_id();
	echo mysql_insert_id()."|.|exito";
	
	
	if($result){
		
		if ($codigo_materia_automatico <> ""){
			$contador = $codigo_materia_automatico + 1;	
			$sql_cambia_contador = mysql_query("update relacion_contadores set contador_inventario_materia = '".$contador."'")or die(mysql_error());

			
			$sql_unidad_desagregada = mysql_query("select * from desagrega_unidad_medida where idunidad_medida = '".$idunidad_medida."'")or die(mysql_error());
			$existe_desagregado = mysql_num_rows($sql_unidad_desagregada);
			if ($existe_desagregado > 0){
				while($bus_unidad_desagregada = mysql_fetch_array($sql_unidad_desagregada)){
					$ingresa_desagregado = mysql_query("insert into relacion_desagrega_unidad_materia
													   					(idinventario_materia,
																		 iddesagrega_unidad_medida)
																		VALUES
																		('".$idmateria."',
																		 '".$bus_unidad_desagregada["idunidad_medida_desagregada"]."')")or die(mysql_error());
				}
			}
		}	
	
	}
}
}

if($ejecutar =="modificarMateria"){
	
	$result=mysql_query("update inventario_materia set 	descripcion				= '$descripcion_materia',	
														idunidad_medida			= '$idunidad_medida',
														cantidad_unidad			= '$cantidad_unidad',
														iddetalle_materias_almacen	= '$iddetalle_materias_almacen',
														idtipo_detalle_almacen	= '$idtipo_detalle_almacen',
														idmarca_materia			= '$idmarca_materia',
														modelo			 		= '$modelo',
														serializado		 		= '$serializado',
														caduca					= '$fecha_vencimiento',
														utilidad 				= '$utilidad',
														garantia			 	= '$garantia'
											where idinventario_materia		= '".$idmateria."'")or die(mysql_error());	
	echo "exito";

}





if($ejecutar == "eliminarMateria"){
	$sql_eliminar =mysql_query("update inventario_materia set status = 'e' where idinventario_materia = '".$idmateria."'");	
}

if($ejecutar == "desbloquearMateria"){
	$sql_eliminar =mysql_query("update inventario_materia set status = 'a' where idinventario_materia = '".$idmateria."'");	
}





if($ejecutar == "consultarId"){
	$sql_consulta = mysql_query("select * from trabajador where cedula = '".$cedula."'");
	$num_consulta = mysql_num_rows($sql_consulta);
	
	if($num_consulta == 0){
		echo "no_existe";	
	}else{
		$bus_consulta = mysql_fetch_array($sql_consulta);
		echo $bus_consulta["idtrabajador"];
	}
}




if($ejecutar == "consultarMateria"){
		$sql_consulta = mysql_query("select * from inventario_materia where idinventario_materia = '".$id_materia."'");
		$bus_consulta = mysql_fetch_array($sql_consulta);
		
		
		$sql_foto = mysql_query("select * from relacion_imagen_materia where idinventario_materia = '".$id_materia."' and principal = 1");
		$bus_foto = mysql_fetch_array($sql_foto);
		
		$sql_detalle_materia = mysql_query("select * from detalle_materias_almacen where iddetalle_materias_almacen = '".$bus_consulta["iddetalle_materias_almacen"]."'");
		$bus_detalle_materia = mysql_fetch_array($sql_detalle_materia);
		
		$sql_unidad = mysql_query("select * from unidad_medida where idunidad_medida = '".$bus_consulta["idunidad_medida"]."'");
		$bus_unidad = mysql_fetch_array($sql_unidad);
		
		if ($bus_consulta["status"] == "a"){
			$estado='DISPONIBLE';
		}else{
			$estado='BLOQUEADO';
		}
		
		$existencia_actual = $bus_consulta["inventario_inicial"] + $bus_consulta["total_entradas"] - $bus_consulta["total_despachadas"];
		
		echo $bus_consulta["idinventario_materia"]			."|.|".
		$bus_consulta["id_tipo_movimiento_almacen"]			."|.|".
		$bus_consulta["codigo"]								."|.|".
		$bus_consulta["descripcion"]						."|.|".
		$bus_consulta["idunidad_medida"]					."|.|".
		$bus_consulta["cantidad_unidad"]					."|.|".
		$bus_consulta["iddetalle_materias_almacen"]			."|.|".
		$bus_consulta["idtipo_detalle_almacen"]				."|.|".
		$bus_consulta["idmarca_materia"]					."|.|".
		$bus_consulta["modelo"]								."|.|".
		$bus_consulta["utilidad"]							."|.|".
		$bus_consulta["garantia"]							."|.|".
		number_format($bus_consulta["existencia_actual"],2,",",".")	."|.|".
		$bus_consulta["serializado"]						."|.|".
		$bus_consulta["caduca"]								."|.|".
		$bus_foto["nombre_imagen"]							."|.|".
		$bus_detalle_materia["codigo"]						."|.|".
		$bus_detalle_materia["denominacion"]				."|.|".
		$estado												."|.|".
		$bus_consulta["idalmacen"]							."|.|".
		$bus_consulta["iddistribucion_almacen"]				."|.|".
		$bus_consulta["idcondicion_almacenaje"]				."|.|".
		$bus_consulta["idcondicion_conservacion"]			."|.|".
		$bus_consulta["idforma_materia"]					."|.|".
		$bus_consulta["idvolumen_materia"]					."|.|".
		$bus_consulta["color"]								."|.|".
		$bus_consulta["peso"]								."|.|".
		$bus_consulta["idunidad_medida_peso"]				."|.|".
		$bus_consulta["capacidad"]							."|.|".
		$bus_consulta["idunidad_medida_capacidad"]			."|.|".
		$bus_consulta["alto"]								."|.|".
		$bus_consulta["idunidad_medida_alto"]				."|.|".
		$bus_consulta["largo"]								."|.|".
		$bus_consulta["idunidad_medida_largo"]				."|.|".
		$bus_consulta["ancho"]								."|.|".
		$bus_consulta["idunidad_medida_ancho"]				."|.|".
		$bus_unidad["abreviado"]." - ".$bus_unidad["descripcion"]."|.|".
		number_format($bus_consulta["inventario_inicial"],2,",",".")."|.|".
		$bus_consulta["inventario_inicial"]					."|.|".
		number_format($bus_consulta["total_entradas"],2,",",".")	."|.|".
		number_format($bus_consulta["total_despachadas"],2,",",".")	."|.|".
		number_format($existencia_actual,2,",",".")	."|.|".
		number_format($bus_consulta["stock_minimo"],2,",",".")		."|.|".
		$bus_consulta["stock_minimo"]						."|.|".
		number_format($bus_consulta["stock_maximo"],2,",",".")."|.|".
		$bus_consulta["stock_maximo"]						."|.|".
		$bus_consulta["estado"]								."|.|".
		$bus_consulta["documento_ultima_compra"]			."|.|".
		$bus_consulta["fecha_ultima_compra"]				."|.|".
		number_format($bus_consulta["ultimo_costo"],2,",",".")						."|.|".
		number_format($bus_consulta["costo_promedio"],2,",",".");
		
		
		
}


//******************************************************************************************************************************
//******************************************************************************************************************************
//****************************************************** UBICACION *************************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************
if($ejecutar == "seleccionarAlmacen"){
  $rows = mysql_query("select idalmacen as value, concat(codigo,'-',denominacion,' (',(select count(*) from relacion_serial_materia b where idinventario_materia = '$idmateria' and b.idalmacen = a.idalmacen),')',' (',coalesce((select sum(cantidad) from relacion_vencimiento_materia b where idinventario_materia = '$idmateria' and b.idalmacen = a.idalmacen),0),')',case (select count(*) from relacion_materia_existencia_almacen x where x.idalmacen = a.idalmacen and idinventario_materia = '$idmateria')  when 0 then ' (Abierto)' else ' (Cerrado)' end) as text from almacen a");
  echo '<select  name="almacen" id="almacen" style="width:50%" onchange="seleccionarUbicacion(this.value)"><option value="0">.:: Seleccione ::.</option>';
  if(!isset($idalmacen)){
    while($row = mysql_fetch_array($rows)){
      echo "<option value=\"$row[value]\" >$row[text]</option>";
    }
  }else{
    while($row = mysql_fetch_array($rows)){
      $selected = $row['value'] == $idalmacen? 'selected':'';
      echo "<option value=\"$row[value]\" $selected>$row[text]</option>";
    }
  }
  echo '</select>';
  return;
}
if($ejecutar == "seleccionarUbicacion"){
  $rows = mysql_query("select iddistribucion_almacen as value, concat(codigo,'-',denominacion,' (',(select count(*) from relacion_serial_materia where idinventario_materia = '$idmateria' and idalmacen = '$idalmacen' and iddistribucion = iddistribucion_almacen),')',' (',coalesce((select sum(cantidad) from relacion_vencimiento_materia where idinventario_materia = '$idmateria' and idalmacen = '$idalmacen' and iddistribucion = iddistribucion_almacen),0),')') as text from distribucion_almacen where idalmacen = '$idalmacen'");
  echo '<select name="iddistribucion_almacen" id="iddistribucion_almacen" style="width:50%" onchange="updateExistencia()">';
  if(!isset($ubicacion)){
    while($row = mysql_fetch_array($rows)){
      echo "<option value=\"$row[value]\" >$row[text]</option>";
    }
  }else{
    while($row = mysql_fetch_array($rows)){
      $selected = $row['value'] == $ubicacion? 'selected':'';
      echo "<option value=\"$row[value]\" $selected>$row[text]</option>";
    }
  }
  echo '</select>';
  return;
}
if($ejecutar =="ingresarUbicacion"){	
  $result = mysql_query(formatSTR(
    "update inventario_materia set 
       idalmacen                 = '{0}',
       iddistribucion_almacen    = '{1}',
       idcondicion_almacenaje    = '{2}',
       idcondicion_conservacion  = '{3}',
       idforma_materia           = '{4}',
       idvolumen_materia         = '{5}',
       color                     = '{6}',
       peso                      = '{7}',
       idunidad_medida_peso      = '{8}',
       capacidad                 = '{9}',
       idunidad_medida_capacidad = '{10}',
       alto                      = '{11}',
       idunidad_medida_alto      = '{12}',
       largo                     = '{13}',
       idunidad_medida_largo     = '{14}',
       ancho                     = '{15}',
       idunidad_medida_ancho     = '{16}'
     where 
       idinventario_materia      = '{17}'",
    $idalmacen,	
    $iddistribucion_almacen,
    $idcondicion_almacenaje,
    $idcondicion_conservacion,
    $idforma_materia,
    $idvolumen_materia,
    $color,
    $peso,
    $idunidad_medida_peso,
    $capacidad,
    $idunidad_medida_capacidad,
    $alto,
    $idunidad_medida_alto,
    $largo,
    $idunidad_medida_largo,
    $ancho,
    $idunidad_medida_ancho,
    $idmateria
  ))or die(mysql_error());
	echo "exito";
}
//******************************************************************************************************************************
//******************************************************************************************************************************
//****************************************************** EXISTENCIA ************************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************
if($ejecutar == "consultarExistenciaAlmacen"){
	$rows = mysql_query(formatSTR("
    select distinct
      coalesce(rmea.inventario_inicial,0) as inventario_inicial,
      coalesce(rmea.estado,'a') as estado,
      im.serializado,
      im.caduca
    from 
      inventario_materia im 
      left join 
        relacion_materia_existencia_almacen rmea
      on 
        rmea.idinventario_materia = im.idinventario_materia and
        rmea.idalmacen = '{1}'
    where 
       im.idinventario_materia = '{0}'",
    $idinventario_inicial,
    $idAlmacen
  ));
  /**
   * Si no existe el inventario inicial es 0 y se encuetra abierto el inventario
   */
  switch(mysql_num_rows($rows)){
    case 0: $result = 'noExiste'; break;
    case 1: 
      $row = mysql_fetch_array($rows);
      $result = "{\"inicial\":$row[inventario_inicial],\"estado\":\"$row[estado]\",\"serial\":$row[serializado],\"caduca\":$row[caduca]}";
    break;
    default: $result = 'existeMultiple'; break;
  }
  echo $result;
}
if($ejecutar =="modificarExistencia"){
  mysql_query("update inventario_materia set inventario_inicial = '$idinventario_inicial', existencia_actual = '$idinventario_inicial',stock_minimo = '$idstock_minimo', stock_maximo = '$idstock_maximo' where idinventario_materia = '$idmateria'")or die(mysql_error());
	echo "exito";
}
if($ejecutar =="ingresarSerial"){
  $validar_cantidad = mysql_query("select * from relacion_serial_materia where idinventario_materia = '$idmateria'") or die(mysql_error());
  $existe_cantidad  = mysql_num_rows($validar_cantidad) ? mysql_num_rows($validar_cantidad) : 0;
  if($existe_cantidad < $inventario_inicial){
    $validar_existe = mysql_query("select * from relacion_serial_materia where idinventario_materia = '$idmateria' and serial = '$serial'") or die(mysql_error());
    $existe_serial = mysql_num_rows($validar_existe);
    if($existe_serial == 0){
      $ingresa_serial = mysql_query("insert into relacion_serial_materia (idinventario_materia, serial, estado, idalmacen,iddistribucion) values ('$idmateria', '$serial', 'Disponible', '$idAlmacen','$idDistribucion')") or die(mysql_error());
      echo "exito";
    }else{
      echo "existe";	
    }
  }else{
    echo "limite";	
  }
}
if($ejecutar == "consultarSeriales"){
  $rows = mysql_query("select * from relacion_serial_materia where idinventario_materia = '$idmateria' and idalmacen = '$idAlmacen' and iddistribucion = '$idDistribucion'") or die(mysql_error());
  $seriales = mysql_num_rows($rows);
  echo "<input name=\"van_seriales\" type=\"hidden\" id=\"van_seriales\" value=\"$seriales\">";
  $columnas = 0;
  echo '<table align="center" class="Entrada"><tr>';
  while($row = mysql_fetch_array($rows)){
    if($row["estado"] != 'Disponible'){
      $style     = 'style = "background:#FC3"';
      $eliminate = '&nbsp;';
    }else{
      unset($style);
      $eliminate = "<img src=\"imagenes/delete.png\" style=\"cursor:pointer\" onClick=\"EliminarSerial('$row[idrelacion_serial_materia]')\">";
    }
    echo "<td align=\"center\" class=\"Entrada\" width=\"10%\" $style >$row[serial]</td><td width=\"3%\">$eliminate</td>";
    $columnas++;
    if($columnas == 7){
		  echo "</tr><tr>";
		  $columnas = 0;
		}
  }
  echo "</tr></table>";      
}
if($ejecutar =="EliminarSerial"){
  mysql_query("delete from relacion_serial_materia where idrelacion_serial_materia = '$idrelacion_serial_materia'") or die(mysql_error());
	echo "exito";
}
if($ejecutar =="ingresarFVencimiento"){
  $validar_cantidad = mysql_query("select sum(cantidad) as cantidad from relacion_vencimiento_materia where idinventario_materia = '$idmateria' and idalmacen = '$idAlmacen' and iddistribucion = '$idDistribucion'") or die(mysql_error());
	$existe_cantidad  = mysql_fetch_array($validar_cantidad);
	$cantidad_inicial = $inventario_inicial;
	$existen_cantidad = $existe_cantidad["cantidad"] + $cantidad;	
  if($existen_cantidad <= $cantidad_inicial){
		$validar_existe = mysql_query("select * from relacion_vencimiento_materia where idinventario_materia = '$idmateria'	and lote = '$lote'") or die(mysql_error());
		$existe_lote    = mysql_num_rows($validar_existe);
		if ($existe_lote == 0){
			$ingresa_lote = mysql_query(formatSTR(
        "insert into relacion_vencimiento_materia (
           idinventario_materia,
           lote,
           cantidad,
           fecha_vencimiento,
           disponibles,
           idalmacen,
           iddistribucion
         ) values (
           '{0}',
           '{1}',
           '{2}',
           '{3}',
           '{4}',
           '{5}',
           '{6}'
         )",
         $idmateria,
         $lote,
         $cantidad,
         $fecha_vencimiento,
         $cantidad,
         $idAlmacen,
         $idDistribucion)) or die(mysql_error());
      echo "exito";
    }else{
      echo "existe";	
		}
	}else{
		echo "limite";	
	}
}
if($ejecutar == "consultarFVencimiento"){
  $sql_vencimiento     = mysql_query("select sum(cantidad) as cantidad from relacion_vencimiento_materia where idinventario_materia = '$idmateria' and idalmacen = '$idAlmacen' and iddistribucion = '$idDistribucion'") or die(mysql_error());
  $bus_vencimiento     = mysql_fetch_array($sql_vencimiento) or die(mysql_error());
  $existen_vencimiento = $bus_vencimiento["cantidad"];
  $sql_vencimiento = mysql_query("select * from relacion_vencimiento_materia where idinventario_materia = '$idmateria' and idalmacen = '$idAlmacen' and iddistribucion = '$idDistribucion'") or die(mysql_error());
  echo <<<STR
   <input name="van_fechas" type="hidden" id="van_fechas" value="$existen_vencimiento">
   <table class="Browse" cellpadding="0" cellspacing="0" width="50%" align="center">
    <thead>
      <tr>
        <td width="20%" align="center" class="Browse">Lote</td>	
        <td width="20%" align="center" class="Browse">Fecha Vencimiento</td>
        <td width="20%" align="center" class="Browse">Cantidad del Lote</td>
        <td width="20%" align="center" class="Browse">Disponibles</td>
        <td width="20%" align="center" class="Browse">Acci&oacute;n</td>
      </tr>
   </thead>
STR;
  while($bus_vencimiento = mysql_fetch_array($sql_vencimiento)){
    $bus_vencimiento["cantidad"]    = number_format($bus_vencimiento["cantidad"],2,",",".");
    $bus_vencimiento["disponibles"] = number_format($bus_vencimiento["disponibles"],2,",",".");
    echo <<<STR
    <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
		  <td align="left" class='Browse' style="font-weight:normal" width="20%">$bus_vencimiento[lote]</td>
        <td align="center" class='Browse' style="font-weight:normal" width="20%">$bus_vencimiento[fecha_vencimiento]</td>
        <td align="right" class='Browse' style="font-weight:normal" width="20%">$bus_vencimiento[cantidad]</td>
        <td align="right" class='Browse' style="font-weight:normal" width="20%">$bus_vencimiento[disponibles]</td>
      <td align="center" class='Browse'><img src="imagenes/delete.png" style="cursor:pointer" onClick="eliminarLoteVencimiento('$bus_vencimiento[idrelacion_vencimiento_materia]')"></td>
	  </tr>
STR;
	}
  echo '</table>';
}
if($ejecutar =="eliminarLoteVencimiento"){
  $sql_vencimiento = mysql_query("select * from relacion_vencimiento_materia where idinventario_materia = '".$idmateria."'") or die(mysql_error());
  $bus_vencimiento = mysql_fetch_array($sql_vencimiento) or die(mysql_error());
  if($bus_vencimiento["cantidad"] == $bus_vencimiento["disponibles"]){
    $sql_ingresar = mysql_query("delete from relacion_vencimiento_materia where idrelacion_vencimiento_materia='$idrelacion_vencimiento_materia'") or die(mysql_error());
    echo "exito";
	}else{
    echo "despachados";	
  }
}
if($ejecutar == "cerrarInventarioInicial"){
  $cantidad_inicial = $idinventario_inicial;	
  if($serializado == 1 && $fecha_vencimiento == 1){
    $sql_seriales     = mysql_query("select * from relacion_serial_materia where idinventario_materia = '$idmateria' and idalmacen = '$idAlmacen'") or die(mysql_error());
    $existen          = mysql_num_rows($sql_seriales)? mysql_num_rows($sql_seriales) or die(mysql_error()) : 0;
    $existen_seriales = $existen;
    if($existen_seriales < $cantidad_inicial){
      echo "faltan_seriales";
    }else{
      $validar_cantidad = mysql_query("select sum(cantidad) as cantidad from relacion_vencimiento_materia where idinventario_materia = '$idmateria' and idalmacen = '$idAlmacen'") or die(mysql_error());
      $existe_cantidad = mysql_fetch_array($validar_cantidad);
      $existen_fechas = $existe_cantidad["cantidad"];			
      if($existen_fechas < $cantidad_inicial){
        echo "faltan_fechas";
      }else{
        //La cantidad de los lotes deben de conincidir con la cantidad de seriales registrados
        $sql_registros = mysql_query("select a.idalmacen,bb.iddistribucion_almacen,concat(a.codigo, ' - ', a.denominacion , '-' , bb.denominacion) as denominacion,coalesce((select count(*) from relacion_serial_materia rsm where rsm.idalmacen = a.idalmacen and rsm.idinventario_materia = '$idmateria' and rsm.iddistribucion = bb.iddistribucion_almacen), 0) as seriales,coalesce((select sum(rvm.cantidad) from relacion_vencimiento_materia rvm where rvm.idalmacen = a.idalmacen and rvm.idinventario_materia = '$idmateria'  and rvm.iddistribucion = bb.iddistribucion_almacen),0) as lotes from almacen a inner join distribucion_almacen bb on bb.idalmacen = a.idalmacen where a.idalmacen = '$idAlmacen'") or die(mysql_error());
        $errors = array();
        while($row = mysql_fetch_array($sql_registros)){
          if($row['seriales'] != $row['lotes']){
            $errors[] = $row;
          }
        }
        if(count($errors) > 0){
          echo '<b>La cantidad de seriales registrados no coincide con la cantidad indicada en los lotes.</b><br />';
          echo '<table>';
          echo '<tr><td width="350px" align="center" class="Browse">Almacen</td><td width="50px" align="center" class="Browse">Seriales</td><td width="50px" align="center" class="Browse">Lotes</td></tr>';
          foreach($errors as $error){
            echo "<tr><td>$error[denominacion]</td><td align=\"right\">$error[seriales]</td><td align=\"right\">$error[lotes]</td></tr>";
          }
          echo '</table>';
        }else{
          mysql_query("update inventario_materia set estado = 'cerrado' where idinventario_materia = '$idmateria'") or die(mysql_error());
          mysql_query(
           formatSTR(
             'insert into relacion_materia_existencia_almacen
              select
                null,
                idinventario_materia,
                idalmacen,
                iddistribucion,
                0,
                0,
                count(*),
                \'cerrado\'
              from
                relacion_serial_materia rsm
              where
                rsm.idinventario_materia = \'{0}\' and
                rsm.idalmacen = \'{1}\'
              group by
                idinventario_materia,
                idalmacen,
                iddistribucion',
              $idmateria,
              $idAlmacen
          )) or die(mysql_error());
          echo "exito";
        }
      }
    }
  }
  if($serializado == 1 && $fecha_vencimiento == 0){
    $sql_seriales     = mysql_query("select * from relacion_serial_materia where idinventario_materia = '$idmateria' and idalmacen = '$idAlmacen'")or die(mysql_error());
    $existen          = mysql_num_rows($sql_seriales)?mysql_num_rows($sql_seriales) : 0;
    $existen_seriales = $existen;
    if($existen_seriales < $cantidad_inicial){
      echo "faltan_seriales";
    }else{
      mysql_query("update inventario_materia set estado = 'cerrado' where idinventario_materia = '$idmateria'") or die(mysql_error());
      mysql_query(
       formatSTR(
         'insert into relacion_materia_existencia_almacen
          select
            null,
            idinventario_materia,
            idalmacen,
            iddistribucion,
            0,
            0,
            count(*),
            \'cerrado\'
          from
            relacion_serial_materia rsm
          where
            rsm.idinventario_materia = \'{0}\' and
            rsm.idalmacen = \'{1}\'
          group by
            idinventario_materia,
            idalmacen,
            iddistribucion',
          $idmateria,
          $idAlmacen
      )) or die(mysql_error());	
      echo "exito";
    }
  }
  if($fecha_vencimiento == 1 and $serializado == 0){
    $validar_cantidad = mysql_query("select sum(cantidad) as cantidad from relacion_vencimiento_materia where idinventario_materia = '$idmateria' and idalmacen = '$idAlmacen'") or die(mysql_error());
    $existe_cantidad = mysql_fetch_array($validar_cantidad);
    $existen_fechas = $existe_cantidad["cantidad"];
    if($existen_fechas < $cantidad_inicial){
      echo "faltan_fechas";
    }else{
      mysql_query("update inventario_materia set estado = 'cerrado' where idinventario_materia = '$idmateria'") or die(mysql_error());
      mysql_query(
       formatSTR(
         'insert into relacion_materia_existencia_almacen
          select
            null,
            idinventario_materia,
            idalmacen,
            iddistribucion,
            0,
            0,
            count(*),
            \'cerrado\'
          from
            relacion_vencimiento_materia rsm
          where
            rsm.idinventario_materia = \'{0}\' and
            rsm.idalmacen = \'{1}\'
          group by
            idinventario_materia,
            idalmacen,
            iddistribucion',
          $idmateria,
          $idAlmacen
      )) or die(mysql_error());	
      echo "exito";
    }
  }
  if($serializado == 0 and $fecha_vencimiento == 0){
    $result = mysql_query("update inventario_materia set estado = 'cerrado' where idinventario_materia = '$idmateria'") or die(mysql_error());
      mysql_query(
       formatSTR(
         "insert into relacion_materia_existencia_almacen
          select
            null,
            '{0}',
            '{1}',
            '{2}',
            0,
            0,
            '{3}',
            'cerrado'",
          $idmateria,
          $idAlmacen,
          $idDistribucion,
          $cantidad_inicial
      )) or die(mysql_error());	

    echo "exito";
  }
}
//******************************************************************************************************************************
//******************************************************************************************************************************
//****************************************************** DESAGREGAR UNIDAD *****************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************

if($ejecutar == "consultarDesagregar"){

	?>
    
    
    <table class="Browse" cellpadding="0" cellspacing="0" width="30%" align="center">
  	<thead>
        <tr>
         <td width="60%" align="center" class="Browse">Unidad Desagregada</td>	
              <td width="10%" align="center" class="Browse">Cantidad</td>
              <td width="10%" align="center" class="Browse">Acci&oacute;n</td>
        </tr>
    
       
        <?
		$i=0;
		$sql_consulta = mysql_query("select unidad_medida.abreviado, unidad_medida.descripcion , relacion_desagrega_unidad_materia.cantidad_desagrega,
											relacion_desagrega_unidad_materia.idrelacion_desagrega_unidad_materia,
											desagrega_unidad_medida.iddesagrega_unidad_medida
										from desagrega_unidad_medida
								      	INNER JOIN unidad_medida ON
									  			(desagrega_unidad_medida.iddesagrega_unidad_medida=unidad_medida.idunidad_medida)
                        				INNER JOIN relacion_desagrega_unidad_materia ON									  					
											(relacion_desagrega_unidad_materia.iddesagrega_unidad_medida=desagrega_unidad_medida.iddesagrega_unidad_medida)
										where relacion_desagrega_unidad_materia.idinventario_materia = '".$idmateria."'")or die(mysql_error());
		while($bus_consulta = mysql_fetch_array($sql_consulta)){
			?>
            <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
				<td align='left' class='Browse' style="font-weight:normal"><?=$bus_consulta["abreviado"]." - ".$bus_consulta["descripcion"]?></td>
                <td align='left' class='Browse' style="font-weight:normal">
                 <input type="text" 
                		name="unidad<?=$bus_consulta["iddesagrega_unidad_medida"]?>" 
                        id="unidad<?=$bus_consulta["iddesagrega_unidad_medida"]?>" 
                        style="text-align:right" 
                        size="8" 
                        value="<?=$bus_consulta["cantidad_desagrega"]?>">
                </td>
                <td align="center" class='Browse'><img src="imagenes/refrescar.png" style="cursor:pointer" onClick="actualizarCantidadDesagrega('<?=$bus_consulta["idrelacion_desagrega_unidad_materia"]?>', '<?=$bus_consulta["iddesagrega_unidad_medida"]?>')"></td>
			</tr>
         <?
		}
	?>
	 </thead>
    </table>
	<?
}

if($ejecutar =="actualizarCantidadDesagrega"){
	
	$result=mysql_query("update relacion_desagrega_unidad_materia set 	
										cantidad_desagrega	= '$cantidad_desagrega'
											where idrelacion_desagrega_unidad_materia	= '".$idrelacion_desagrega_unidad_materia."'")or die(mysql_error());	
	echo "exito";

}

//******************************************************************************************************************************
//******************************************************************************************************************************
//****************************************************** REEMPLAZO *************************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************

if($ejecutar =="ingresarReemplazoMateria"){
	$sql_valida=mysql_query("select * from relacion_reemplazo_materia
								where idinventario_materia='".$idmateria."'
								and idinventario_materia_reemplazo='".$idmateria_reemplazo."'");
	$valida_existe= mysql_num_rows($sql_valida);
	if ($valida_existe == 0){
		$sql_ingresar = mysql_query("insert into relacion_reemplazo_materia (idinventario_materia,
																				idinventario_materia_reemplazo
																				)VALUES('".$idmateria."',
																								'".$idmateria_reemplazo."')")or die(mysql_error());

		echo "exito";
	}else{
		echo "existe";
	}

}

if($ejecutar == "consultarReemplazoMateria"){
	?>
    
    
    <table class="Browse" cellpadding="0" cellspacing="0" width="50%" align="center">
  	<thead>
        <tr>
          <td width="10%" align="center" class="Browse">C&oacute;digo</td>	
          <td width="70%" align="center" class="Browse">Denominaci&oacute;n</td>
          <td width="10%" align="center" class="Browse">Acci&oacute;n</td>
        </tr>
    
       
        <?
		$i=0;
		$sql_consulta = mysql_query("select inventario_materia.codigo, inventario_materia.descripcion , 		
											relacion_reemplazo_materia.idrelacion_reemplazo_materia
										from relacion_reemplazo_materia
								      	INNER JOIN inventario_materia ON 		
									  			(relacion_reemplazo_materia.idinventario_materia_reemplazo=inventario_materia.idinventario_materia)
										where relacion_reemplazo_materia.idinventario_materia = '".$idmateria."'")or die(mysql_error());
		while($bus_consulta = mysql_fetch_array($sql_consulta)){
			?>
            <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
				<td align='left' class='Browse' style="font-weight:normal"><?=$bus_consulta["codigo"]?></td>
                <td align='left' class='Browse' style="font-weight:normal"><?=$bus_consulta["descripcion"]?></td>
                <td align="center" class='Browse'><img src="imagenes/delete.png" style="cursor:pointer" onClick="seleccionarEliminarReemplazo('<?=$bus_consulta["idrelacion_reemplazo_materia"]?>')"></td>
			</tr>
         <?
		}
	?>
	 </thead>
    </table>
	<?
}

if($ejecutar =="seleccionarEliminarReemplazo"){
	
	$sql_ingresar = mysql_query("delete from relacion_reemplazo_materia 
									where idrelacion_reemplazo_materia='".$idmateria_reemplazo."'")or die(mysql_error());

	echo "exito";

}


//******************************************************************************************************************************
//******************************************************************************************************************************
//****************************************************** EQUIVALENCIAS *********************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************

if($ejecutar =="ingresarEquivalenciaMateria"){
	$sql_valida=mysql_query("select * from relacion_equivalencia_materia
								where idinventario_materia='".$idmateria."'
								and idinventario_materia_equivalente='".$idmateria_equivalente."'");
	$valida_existe= mysql_num_rows($sql_valida);
	if ($valida_existe == 0){
		$sql_ingresar = mysql_query("insert into relacion_equivalencia_materia (idinventario_materia,
																				idinventario_materia_equivalente,
																				describir_equivalencia
																				)VALUES('".$idmateria."',
																								'".$idmateria_equivalente."',
																								'".$describir_equivalencia."')")or die(mysql_error());

		echo "exito";
	}else{
		echo "existe";
	}

}

if($ejecutar == "consultarEquivalencia"){
	?>
    
    
    <table class="Browse" cellpadding="0" cellspacing="0" width="70%" align="center">
  	<thead>
        <tr>
          <td width="10%" align="center" class="Browse">C&oacute;digo</td>	
          <td width="60%" align="center" class="Browse">Denominaci&oacute;n</td>
          <td width="20%" align="center" class="Browse">Equivalencia</td>
          <td width="10%" align="center" class="Browse">Acci&oacute;n</td>
        </tr>
    
       
        <?
		$i=0;
		$sql_consulta = mysql_query("select inventario_materia.codigo, inventario_materia.descripcion , 		
											relacion_equivalencia_materia.idrelacion_equivalencia_materia,
											relacion_equivalencia_materia.describir_equivalencia
										from relacion_equivalencia_materia
								      	INNER JOIN inventario_materia ON 		
									  			(relacion_equivalencia_materia.idinventario_materia_equivalente=inventario_materia.idinventario_materia)
										where relacion_equivalencia_materia.idinventario_materia = '".$idmateria."'")or die(mysql_error());
		while($bus_consulta = mysql_fetch_array($sql_consulta)){
			?>
            <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
				<td align='left' class='Browse' style="font-weight:normal"><?=$bus_consulta["codigo"]?></td>
                <td align='left' class='Browse' style="font-weight:normal"><?=$bus_consulta["descripcion"]?></td>
                <td align='left' class='Browse' style="font-weight:normal"><?=$bus_consulta["describir_equivalencia"]?></td>
                <td align="center" class='Browse'><img src="imagenes/delete.png" style="cursor:pointer" onClick="seleccionarEliminarEquivalencia('<?=$bus_consulta["idrelacion_equivalencia_materia"]?>')"></td>
			</tr>
         <?
		}
	?>
	 </thead>
    </table>
	<?
}

if($ejecutar =="seleccionarEliminarEquivalencia"){
	
	$sql_ingresar = mysql_query("delete from relacion_equivalencia_materia 
									where idrelacion_equivalencia_materia='".$idrelacion_equivalencia_materia."'")or die(mysql_error());

	echo "exito";

}



//******************************************************************************************************************************
//******************************************************************************************************************************
//************************************************ REGISTRO FOTOGRAFICO ********************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************


if($ejecutar == "cargarImagen"){
	$tipo = substr($_FILES['foto_registroFotografico']['type'], 0, 5);
		$dir = '../imagenes/';
		if ($tipo == 'image') {
		$nombre_imagen = $_FILES['foto_registroFotografico']['name'];
			while(file_exists($dir.$nombre_imagen)){
				$partes_img = explode(".",$nombre_imagen);
				$nombre_imagen = $partes_img[0].rand(0,1000000).".".$partes_img[1];
			}
			if (!copy($_FILES['foto_registroFotografico']['tmp_name'], $dir.$nombre_imagen)){
				?>
                <script>
				parent.document.getElementById('mostrarImagen').innerHTML = "<table><tr><td style='color:#990000; font-weight:bold'>* Disculpe la imagen no se pudo ingresar</td></tr></table>";
				</script>
                <?
			}else{
				$ruta = 'modulos/almacen/imagenes/'.$nombre_imagen;
			}
			
				?>
                
			<script>
            parent.document.getElementById('nombre_imagen').value = '<?=$nombre_imagen?>';
            parent.document.getElementById('mostrarImagen').innerHTML = "<img src='modulos/almacen/imagenes/<?=$nombre_imagen?>' width = '100' height='120'>";
            parent.document.getElementById('foto').value = '';
            </script>
            <?
			
		}else{
			?>
			<script>
			parent.document.getElementById('mostrarImagen').innerHTML = "<table><tr><td style='color:#990000; font-weight:bold'>* Disculpe el archivo que intenta subir NO es una Imagen</td></tr></table>";
			</script>
			
			<?
		}
		
}



if($ejecutar == "subirRegistroFotografico"){
	$sql_ingresar = mysql_query("insert into relacion_imagen_materia (idinventario_materia,
																				nombre_imagen,
																				principal,
																				descripcion)VALUES('".$idmateria."',
																								'".$nombre_imagen."',
																								'0',
																								'".$descripcion."')");
}



if($ejecutar == "consultar_registroFotografico"){
	?>
	<table align="center">
    <tr>
	<?
	$i=0;
	$sql_consulta = mysql_query("select * from relacion_imagen_materia where idinventario_materia = '".$idmateria."'");
	while($bus_consulta = mysql_fetch_array($sql_consulta)){
		?>
        <td>
        
        	<table cellpadding="5" cellspacing="5">
                <tr>
                	<td align="right"><strong onclick="eliminar_registroFotografico('<?=$bus_consulta["idrelacion_imagen_materia"]?>')" style="cursor:pointer">X</strong></td>
                </tr>
                <tr>
                	<td align="center"><img src="modulos/almacen/imagenes/<?=$bus_consulta["nombre_imagen"]?>" width="100" height="100"></td>
                </tr>
                 <tr>
                	<td align="center"><?=$bus_consulta["descripcion"]?></td>
                </tr>
                <tr>
                	<td align="center"><input type="radio" name="imagen_principal" id="imagen_principal" value="<?=$bus_consulta["idrelacion_imagen_materia"]?>" style="cursor:pointer" onclick="principal_registroFotografico('<?=$bus_consulta["idrelacion_imagen_materia"]?>')" <? if($bus_consulta["principal"] == 1){echo "checked";}?>></td>
                </tr>
            </table>
        
        </td>
		<?
		if($i == 6){
			?>
			</tr>
            <tr>
			<?
			$i = 0;
		}else{
			$i++;
		}
		
	}
	?>
	</tr>
    </table>
	<?
}






if($ejecutar == "eliminar_registroFotografico"){
	$sql_consulta = mysql_query("select * from relacion_imagen_materia where idrelacion_imagen_materia = '".$idrelacion_imagen_materia."'");
	$bus_consulta = mysql_fetch_array($sql_consulta);
	
	
	$sql_eliminar = mysql_query("delete from relacion_imagen_materia where idrelacion_imagen_materia = '".$idrelacion_imagen_materia."'");
	
	if($sql_eliminar){
		unlink("../imagenes/".$bus_consulta["nombre_imagen"]);
	}
	
}




if($ejecutar == "principal_registroFotografico"){
	$sql_actualizar = mysql_query("update relacion_imagen_materia set principal = '0' where idinventario_materia = '".$idmateria."'");
	$sql_actualizar = mysql_query("update relacion_imagen_materia set principal = '1' where idrelacion_imagen_materia = '".$idrelacion_imagen_materia."'");
	
}



//******************************************************************************************************************************
//******************************************************************************************************************************
//****************************************************** ACCESORIOS ************************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************

if($ejecutar =="ingresarAccesorioMateria"){
	$sql_valida=mysql_query("select * from relacion_accesorios_materia
								where idinventario_materia='".$idmateria."'
								and idinventario_materia_accesoria='".$idmateria_accesorios."'");
	
	$valida_existe= mysql_num_rows($sql_valida);
	if ($valida_existe == 0){
		$sql_ingresar = mysql_query("insert into relacion_accesorios_materia (idinventario_materia,
																				idinventario_materia_accesoria,
																				describir_accesorio
																				)VALUES('".$idmateria."',
																								'".$idmateria_accesorios."',
																								'".$describir_accesorio."')")or die(mysql_error());

		echo "exito";
	}else{
		echo "existe";
	}

}

if($ejecutar == "consultarAccesorio"){
	?>
    
    
    <table class="Browse" cellpadding="0" cellspacing="0" width="70%" align="center">
  	<thead>
        <tr>
          <td width="10%" align="center" class="Browse">C&oacute;digo</td>	
          <td width="60%" align="center" class="Browse">Denominaci&oacute;n</td>
          <td width="20%" align="center" class="Browse">Utilidad</td>
          <td width="10%" align="center" class="Browse">Acci&oacute;n</td>
        </tr>
    
       
        <?
		$i=0;
		$sql_consulta = mysql_query("select inventario_materia.codigo, inventario_materia.descripcion , 		
											relacion_accesorios_materia.idrelacion_accesorios_materia,
											relacion_accesorios_materia.describir_accesorio
										from relacion_accesorios_materia
								      	INNER JOIN inventario_materia ON 		
									  			(relacion_accesorios_materia.idinventario_materia_accesoria=inventario_materia.idinventario_materia)
										where relacion_accesorios_materia.idinventario_materia = '".$idmateria."'")or die(mysql_error());
		while($bus_consulta = mysql_fetch_array($sql_consulta)){
			?>
            <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
				<td align='left' class='Browse' style="font-weight:normal"><?=$bus_consulta["codigo"]?></td>
                <td align='left' class='Browse' style="font-weight:normal"><?=$bus_consulta["descripcion"]?></td>
                <td align='left' class='Browse' style="font-weight:normal"><?=$bus_consulta["describir_accesorio"]?></td>
                <td align="center" class='Browse'><img src="imagenes/delete.png" style="cursor:pointer" onClick="seleccionarEliminarAccesorios('<?=$bus_consulta["idrelacion_accesorios_materia"]?>')"></td>
			</tr>
         <?
		}
	?>
	 </thead>
    </table>
	<?
}

if($ejecutar =="seleccionarEliminarAccesorios"){
	
	$sql_ingresar = mysql_query("delete from relacion_accesorios_materia 
									where idrelacion_accesorios_materia='".$idrelacion_accesorios_materia."'")or die(mysql_error());

	echo "exito";

}


//******************************************************************************************************************************
//******************************************************************************************************************************
//****************************************************** COMPRAS PROVEEDORES ***************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************
if($ejecutar == "consultarProveedor"){
	?>
    
    
    <table class="Browse" cellpadding="0" cellspacing="0" width="60%" align="center">
  	<thead>
        <tr>
         <td width="20%" align="center" class="Browse">Documento</td>	
          <td width="15%" align="center" class="Browse">Fecha</td>	
          <td width="65%" align="center" class="Browse">Proveedor</td>
        </tr>
    
       
        <?
		$i=0;
		$sql_consulta = mysql_query("select orden_compra_servicio.numero_orden, orden_compra_servicio.fecha_orden, beneficiarios.nombre  		
										from relacion_compra_materia
								      	INNER JOIN orden_compra_servicio ON 		
									  			(relacion_compra_materia.idorden_compra_servicio=orden_compra_servicio.idorden_compra_servicio
												 and (orden_compra_servicio.estado = 'pagado' or orden_compra_servicio.estado = 'procesado'))
										INNER JOIN beneficiarios ON 		
									  			(orden_compra_servicio.idbeneficiarios=beneficiarios.idbeneficiarios)
										where relacion_compra_materia.idinventario_materia = '".$idmateria."'")or die(mysql_error());
		while($bus_consulta = mysql_fetch_array($sql_consulta)){
			?>
            <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
				<td align='left' class='Browse' style="font-weight:normal"><?=$bus_consulta["numero_orden"]?></td>
                <td align='left' class='Browse' style="font-weight:normal"><?=$bus_consulta["fecha_orden"]?></td>
                <td align='left' class='Browse' style="font-weight:normal"><?=$bus_consulta["nombre"]?></td>
               
			</tr>
         <?
		}
	?>
	 </thead>
    </table>
	<?
}



//******************************************************************************************************************************
//******************************************************************************************************************************
//****************************************************** COMPRAS PROVEEDORES ***************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************
if($ejecutar == "consultarArticulos"){
	?>
    
    
    <table class="Browse" cellpadding="0" cellspacing="0" width="50%" align="center">
  	<thead>
        <tr>
         <td width="10%" align="center" class="Browse">C&oacute;digo</td>	
          <td width="70%" align="center" class="Browse">Denominaci&oacute;n</td>
        </tr>
    
       
        <?
		$i=0;
		$sql_consulta = mysql_query("select articulos_servicios.codigo, articulos_servicios.descripcion 		
										from relacion_materia_articulos_servicios
								      	INNER JOIN articulos_servicios ON 		
									  			(relacion_materia_articulos_servicios.idarticulos_servicios=articulos_servicios.idarticulos_servicios)
										where relacion_materia_articulos_servicios.idinventario_materia = '".$idmateria."'")or die(mysql_error());
		while($bus_consulta = mysql_fetch_array($sql_consulta)){
			?>
            <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
				<td align='left' class='Browse' style="font-weight:normal"><?=$bus_consulta["codigo"]?></td>
                <td align='left' class='Browse' style="font-weight:normal"><?=$bus_consulta["descripcion"]?></td>
               
			</tr>
         <?
		}
	?>
	 </thead>
    </table>
	<?
}



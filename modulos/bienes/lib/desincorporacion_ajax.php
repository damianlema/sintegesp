<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
$conexion = Conectarse();
extract($_POST);


switch($ejecutar){
	case "ingresarDatosBasicos":
		$sql_ingresar = mysql_query("insert into desincorporacion_bienes(justificacion,
																		fecha_proceso,
																		estado,
																		fechayhora,
																		usuario)VALUES('".$justificacion."',
																						'".date("Y-m-d")."',
																						'elaboracion',
																						'".$fh."',
																						'".$login."')")or die(mysql_error());
		echo mysql_insert_id();
	break;
	
	case "ingresarMueble":
		
		
		
		$sql_consulta = mysql_query("select * from 
												muebles_desincorporacion 
											where 
												iddesincorporacion = '".$iddesincorporacion."' 
												and idmueble = '".$idmueble."'");
		$num_consulta = mysql_num_rows($sql_consulta);
		if($num_consulta == 0){
		$sql_ingresar = mysql_query("insert into muebles_desincorporacion(iddesincorporacion,
																				idmueble)VALUES('".$iddesincorporacion."',
																								'".$idmueble."')")or die(mysql_error());
		}else{
			echo "existe";
		}
	break;
	
	
	case "eliminarMueble":
	
		$sql_eliminar = mysql_query("delete from muebles_desincorporacion where idmuebles_desincorporacion = '".$idmueble_desincorporacion."'")or die(mysql_error());
	break;
	
	case "consultarMuebles":
	
		?>
		<table width="60%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
        <thead>
          <tr>
            <td width="21%" class="Browse"><div align="center">Codigo del Bien</div></td>
            <td width="68%" class="Browse"><div align="center">Descripcion</div></td>
            <td class="Browse"><div align="center">Eliminar</div></td>
          </tr>
          </thead>
		<?
		$sql_desincorporacion = mysql_query("select * from desincorporacion_bienes where iddesincorporacion_bienes = '".$iddesincorporacion."'")or die(mysql_error());
		$bus_desincorporacion = mysql_fetch_array($sql_desincorporacion);
		
		$sql_consultar = mysql_query("select m.codigo_bien,
											 m.especificaciones,
											 md.idmuebles_desincorporacion
											from 
										muebles_desincorporacion md,
										muebles m
											where 
										md.iddesincorporacion = '".$iddesincorporacion."'
										and m.idmuebles = md.idmueble")or die(mysql_error());
										
										
		while($bus_consultar = mysql_fetch_array($sql_consultar)){
			?>
			<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
              
              <td class='Browse' align='center'>&nbsp;<?=$bus_consultar["codigo_bien"]?></td>
              <td class='Browse' align='left'>&nbsp;<?=$bus_consultar["especificaciones"]?></td>
              <td width="11%" align='center' class='Browse'>
              <?
              if($bus_desincorporacion["estado"] == "elaboracion"){
			  ?>
              <img src="imagenes/delete.png" style="cursor:pointer" onClick="eliminarMueble('<?=$bus_consultar["idmuebles_desincorporacion"]?>')">
              </td>
              <?
              }else{
			  echo "&nbsp;";
			  }
			  ?>
              
          </tr>
			<?
		}
	?>
	</table>
	<?
	break;
	
	
	case "procesarDesincorporacion":
	$sql_configuracion = mysql_query("select * from configuracion_bienes")or die(mysql_error());
	$bus_configuracion = mysql_fetch_array($sql_configuracion);
	
	
	$nro_planilla = "DES-".$_SESSION["anio_fiscal"]."-".$bus_configuracion["nro_desincorporacion"]."";
	
		$sql_procesar = mysql_query("update desincorporacion_bienes 
									set 
								numero_planilla = '".$nro_planilla."',
								estado = 'procesado' 
									where 
								iddesincorporacion_bienes = '".$iddesincorporacion."'")or die(mysql_error());
		
		$sql_consulta_muebles = mysql_query("select * from muebles_desincorporacion 
													where iddesincorporacion = '".$iddesincorporacion."'");
		while($bus_consulta_muebles = mysql_fetch_array($sql_consulta_muebles)){
			$sql_actualizar = mysql_query("update muebles set status = 'd' where idmuebles = '".$bus_consulta_muebles["idmueble"]."'");
		}
		
		$sql_actualizar = mysql_query("update 
										configuracion_bienes 
										set 
										nro_desincorporacion = nro_desincorporacion+1")or die(mysql_error());
		echo $nro_planilla;
	break;
	
	
	case "anularOrden":
		$sql_anular = mysql_query("update desincorporacion_bienes 
													set estado = 'anulado' 
													where iddesincorporacion_bienes ='".$iddesincorporacion."'");
		
		$sql_consulta_muebles = mysql_query("select * from muebles_desincorporacion 
													where iddesincorporacion = '".$iddesincorporacion."'");
		while($bus_consulta_muebles = mysql_fetch_array($sql_consulta_muebles)){
			$sql_actualizar = mysql_query("update muebles set status = 'a' where idmuebles = '".$bus_consulta_muebles["idmueble"]."'");
		}
	break;
	
	
	
	
	case "consultarDesincorporacion":
		$sql_consulta= mysql_query("select * from desincorporacion_bienes where iddesincorporacion_bienes = '".$iddesincorporacion."'");
		$bus_consulta = mysql_fetch_array($sql_consulta);
		
		echo $bus_consulta["numero_planilla"]."|.|".
		$bus_consulta["fecha_proceso"]."|.|".
		$bus_consulta["justificacion"]."|.|".
		$bus_consulta["estado"];
	break;
}
?>
<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
$conexion = Conectarse();
extract($_POST);
extract($_GET);

switch($ejecutar){

	case "consultarRif":
		if($_GET["rif"] != ""){
		$sql = mysql_query("select * from contribuyente where rif = '".$_GET["rif"]."'");
		$num = mysql_num_rows($sql);
			if($num == 0){
				echo "no";
			}else{
				echo "si";
			}
		}else{
		echo "vacio";
		}
	break;


	case "cargarSelect":
	if($sel == "municipios"){
		$hijo = "parroquia";
		$div = "celda_parroquia";
		$idSelect = "municipios";
	}else if($sel == "parroquia"){
		$hijo = "sectores";
		$div = "celda_sector";
		$idSelect = "parroquia";
	}else if($sel == "sectores"){
		$hijo = "urbanizacion";
		$div = "celda_urb";
		$idSelect = "sectores";
	}else if($sel == "urbanizacion"){
		$hijo = "calle";
		$div = "celda_calle";
		$idSelect = "urbanizacion";
	}else if($sel == "calle"){
		$hijo = "carrera";
		$div = "celda_carrera";
		$idSelect = "calle";
	}else if($sel == "carrera"){
		$sel = "carrera";
		$idSelect = "carrera";
	}else if($sel == "estado_vehiculo"){
		$hijo = "municipios_vehiculo";
		$div = "celda_municipios";
		$sel = "estado";
		$idSelect = "estado_vehiculo";
	}else if($sel == "municipios_vehiculo"){
		$hijo = "parroquia_vehiculo";
		$div = "celda_parroquia_vehiculo";
		$sel = "municipios";
		$idSelect = "municipios_vehiculo";
	}else if($sel == "parroquia_vehiculo"){
		$hijo = "sectores_vehiculo";
		$div = "celda_sector_vehiculo";
		$sel = "parroquia";
		$idSelect = "parroquia_vehiculo";
	}else if($sel == "sectores_vehiculo"){
		$hijo = "urbanizacion_vehiculo";
		$div = "celda_urb_vehiculo";
		$sel = "sectores";
		$idSelect = "sectores_vehiculo";
	}else if($sel == "urbanizacion_vehiculo"){
		$hijo = "calle_vehiculo";
		$div = "celda_calle_vehiculo";
		$sel = "urbanizacion";
		$idSelect = "urbanizacion_vehiculo";
	}else if($sel == "calle_vehiculo"){
		$hijo = "carrera_vehiculo";
		$div = "celda_carrera_vehiculo";
		$sel = "calle";
		$idSelect = "calle_vehiculo";
	}else if($sel == "carrera_vehiculo"){
		$sel = "carrera";
		$idSelect = "carrera_vehiculo";
	}
	
	$sql_configuracion = mysql_query("select * from configuracion_recaudacion");
	$bus_configuracion = mysql_fetch_array($sql_configuracion);
	
	$sql_consulta = mysql_query("select * from ".$sel." where ".$nombreIdPadre." = '".$idpadre."'")or die(mysql_error());
	?>
	<select id="<?=$idSelect?>" style="width:250px">
    <option value="0">.:: Seleccione ::.</option>
	<?
	while($bus_consulta = mysql_fetch_array($sql_consulta)){
		?>
		<option <? if($sel == 'municipios' and $bus_configuracion["idmunicipio"] == $bus_consulta["id".$sel]){echo "selected";}?> value="<?=$bus_consulta["id".$sel]?>" onClick="cargarSelect('<?=$hijo?>', '<?=$div?>', '<?=$bus_consulta["id".$sel]?>', 'id<?=$sel?>')"><?=$bus_consulta["denominacion"]?></option>
		<?
	}
	?>
	</select>
	<?
	break;
	
	
	case "cargarArchivo":
	$tipo = substr($_FILES['imagen']['type'], 0, 5);
		$dir = '../img/';
		if ($tipo == 'image') {
		$nombre_imagen = $_FILES['imagen']['name'];
			while(file_exists($dir.$nombre_imagen)){
				$partes_img = explode(".",$nombre_imagen);
				$nombre_imagen = $partes_img[0].rand(0,1000000).".".$partes_img[1];
			}
			if (!copy($_FILES['imagen']['tmp_name'], $dir.$nombre_imagen)){
				echo "<script>parent.resultadoUpload('0', '');</script>";
			}else{
				$ruta = 'modulos/recaudacion/img/'.$nombre_imagen;
				$sql_ingresar = mysql_query("insert into imagenes_contribuyente(idcontribuyente,
																				ruta,
																				descripcion,
																				status,
																				usuario,
																				fechayhora)VALUES('".$idcontribuyente_imagen."',
																									'".$ruta."',
																									'".$descripcion_imagen."',
																									'a',
																									'".$login."',
																									'".$fh."')")or die(mysql_error());
				echo "<script>parent.resultadoUpload('1', 'file');</script>";
			}
		}else{ echo "<script>parent.resultadoUpload('2', 'file');</script>";
		}
	break;
	
	case "ingresarDatosBasicos":
	
	
	if($tipo_contribuyente == "ve"){
		$sql_consulta = mysql_query("select * from contribuyente where nro_placa = '".$nro_placa."'");
		$num_consulta = mysql_num_rows($sql_consulta);
			if($num_consulta == 0){
				$sql_ingresar = mysql_query("insert into contribuyente(tipo_contribuyente,
																	   nro_placa,
																		tipo_vehiculo,
																		marca,
																		modelo,
																		tipo,
																		color,
																		peso,
																		capacidad,
																		nro_matricula,
																		fecha_matricula,
																		serial_motor,
																		serial_carroceria,
																		uso_vehiculo,
																		propietario,
																		cedula_propietario,
																		status,
																		usuario,
																		fechayhora,
																		estado,
																		municipio,
																		parroquia,
																		sectores,
																		urbanizacion,
																		calle,
																		carrera,
																		nro_casa,
																		punto_referencia)VALUES('".$tipo_contribuyente."',
																						'".$nro_placa."',
																						'".$tipo_vehiculo."',
																						'".$marca."',
																						'".$modelo."',
																						'".$tipo."',
																						'".$color."',
																						'".$peso."',
																						'".$capacidad."',
																						'".$nro_matricula."',
																						'".$fecha_matricula."',
																						'".$serial_motor."',
																						'".$serial_carroceria."',
																						'".$uso_vehiculo."',
																						'".$propietario."',
																						'".$cedula_propietario."',
																						'".$status."',
																						'".$usuario."',
																						'".$fechayhora."',
																						'".$estado."',
																						'".$municipios."',
																						'".$parroquia."',
																						'".$sectores."',
																						'".$urbanizacion."',
																						'".$calle."',
																						'".$carrera."',
																						'".$nro_casa."',
																						'".$punto_referencia."')")or die(mysql_error());
					echo mysql_insert_id();
			}else{
				echo "existe";
			}
	}else{
	$sql_consulta = mysql_query("select * from contribuyente where rif = '".$rif."'");
	$num_consulta = mysql_num_rows($sql_consulta);
			if($num_consulta == 0){
				
				$sql_ingresar = mysql_query("insert into contribuyente(tipo_contribuyente,
																	   razon_social,
																		rif,
																		telefono,
																		email,
																		estado,
																		municipio,
																		parroquia,
																		sectores,
																		urbanizacion,
																		calle,
																		carrera,
																		nro_casa,
																		punto_referencia,
																		status,
																		usuario,
																		fechayhora)VALUES('".$tipo_contribuyente."',
																							'".$razon_social."',
																								'".$rif."',
																								'".$telefono."',
																								'".$email."',
																								'".$estado."',
																								'".$municipios."',
																								'".$parroquia."',
																								'".$sectores."',
																								'".$urbanizacion."',
																								'".$calle."',
																								'".$carrera."',
																								'".$nro_casa."',
																								'".$punto_referencia."',
																								'a',
																								'".$login."',
																								'".$fh."')");
					echo mysql_insert_id();
			}else{
				echo "existe";
			}
	
	}
	
	break;
	case "cargarSocio":
		$sql_ingresar = mysql_query("insert into socios_contribuyente (idcontribuyente,
																		nombre_socio,
																		ci_socio,
																		cargo_socio,
																		acciones_socio,
																		status,
																		usuario,
																		fechayhora)VALUES('".$idcontribuyente."',
																								'".$nombre_socio."',
																								'".$ci_socio."',
																								'".$cargo_socio."',
																								'".$acciones_socio."',
																								'a',
																								'".$login."',
																								'".$fh."')")or die(mysql_error());
	break;
	
	case "cargarListaSocios":
	echo "entro";
	
	$sql_consultar = mysql_query("select * from socios_contribuyente where idcontribuyente = '".$idcontribuyente."'")or die(mysql_error());
	//$num_consultar = mysql_num_rows($sql_consultar);
	//if($num_consultar != 0){
	?>
	<br>
    <table width="80%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
    <thead>
      <tr>
        <td width="30%" class="Browse"><div align="center">Nombre</div></td>
        <td width="10%" class="Browse"><div align="center">CI</div></td>
        <td width="20%" class="Browse"><div align="center">Cargo</div></td>
        <td width="5%" class="Browse"><div align="center">Acciones</div></td>
        <td width="3%" class="Browse"><div align="center">Eliminar</div></td>
      </tr>
      </thead>
	
	<?
	
	while($bus_consultar = mysql_fetch_array($sql_consultar)){
		?>
        
          <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
            <td class='Browse' align='left'>&nbsp;<?=$bus_consultar["nombre_socio"]?></td>
            <td class='Browse' align='left'>&nbsp;<?=$bus_consultar["ci_socio"]?></td>
            <td class='Browse' align='left'>&nbsp;<?=$bus_consultar["cargo_socio"]?></td>
            <td class='Browse' align='left'>&nbsp;<?=$bus_consultar["acciones_socio"]?></td>
            <td class='Browse' align='center'><img src="imagenes/delete.png" style="cursor:pointer" onclick="eliminarSocio('<?=$bus_consultar["idsocios_contribuyente"]?>')"></td>
            </tr>
	<?
	  }
	  ?>
        </table>
        
		<?
		/*}else{
			echo "vacio";
		}*/
	break;
	
	
	case "consultarImagenes":
	
	$sql_consulta = mysql_query("select * from imagenes_contribuyente where idcontribuyente = '".$idcontribuyente."'");
	$num_consulta = mysql_num_rows($sql_consulta);
	if($num_consulta != 0){
	$i=0;
	?>
	<table align="center">
        <tr>
		<?
        while($bus_consulta = mysql_fetch_array($sql_consulta)){
		?>
		
        	<td valign="top">
            
                <table width="150" id="tabla_imagen<?=$bus_consulta["idimagenes_contribuyente"]?>" onMouseOver="document.getElementById('tabla_imagen<?=$bus_consulta["idimagenes_contribuyente"]?>').style.backgroundColor = '#CCCCCC'" onMouseOut="document.getElementById('tabla_imagen<?=$bus_consulta["idimagenes_contribuyente"]?>').style.backgroundColor = '#FFFFFF'" style="cursor:pointer; border:#CCCCCC solid 1px">
                <tr>
                <td align="right"><a href="javascript:;" onClick="eliminarImagen('<?=$bus_consulta["idimagenes_contribuyente"]?>')" title="Eliminar"><strong style="color:#990000;">X</strong></a></td>
                </tr>
                <tr>
                <td align="center"><img src="<?=$bus_consulta["ruta"]?>" width="100" height="100 " onclick="window.open('<?=$bus_consulta["ruta"]?>', 'imagen', 'width=800,height=600,scrollbars=yes, resizable=no')"></td>
                </tr>
                <tr>
                <td><?=$bus_consulta["descripcion"]?></td>
                </tr>
                </table>
            </td>
        
		<?
			if($i == 5){
				?>
				</tr>
				<tr>
				<?
				$i=0;
			}else{
				$i++;
			}
		}
		?>
        </tr>
    </table>
	<?
	}else{
		echo "vacio";
	}
	
	break;
	
	case "eliminarImagen":
	$sql_consulta = mysql_query("select * from imagenes_contribuyente where idimagenes_contribuyente = '".$idimagenes_contribuyente."'");
	$bus_consulta = mysql_fetch_array($sql_consulta);
	$partes = explode("/", $bus_consulta["ruta"]);
	unlink("../img/".$partes[3]);
	$sql_eliminar = mysql_query("delete from imagenes_contribuyente where idimagenes_contribuyente = '".$idimagenes_contribuyente."'");
	break;
	
	case "modificarDatosBasicos":
		
		if($tipo_contribuyente == "ve"){
				$sql_ingresar = mysql_query("update contribuyente set 	nro_placa = '".$nro_placa."',
																		tipo_vehiculo = '".$tipo_vehiculo."',
																		marca = '".$marca."',
																		modelo = '".$modelo."',
																		tipo = '".$tipo."',
																		color = '".$color."',
																		peso = '".$peso."',
																		capacidad = '".$capacidad."',
																		nro_matricula = '".$nro_matricula."',
																		fecha_matricula = '".$fecha_matricula."',
																		serial_motor = '".$serial_motor."',
																		serial_carroceria = '".$serial_carroceria."',
																		uso_vehiculo = '".$uso_vehiculo."',
																		propietario = '".$propietario."',
																		cedula_propietario = '".$cedula_propietario."',
																		estado = '".$estado."',
																		municipio = '".$municipios."',
																		parroquia = '".$parroquia."',
																		sectores = '".$sectores."',
																		urbanizacion = '".$urbanizacion."',
																		calle = '".$calle."',
																		carrera = '".$carrera."',
																		nro_casa = '".$nro_casa."',
																		punto_referencia = '".$punto_referencia."'
																		where 
																		idcontribuyente = '".$idcontribuyente."'")or die(mysql_error());
			if($sql_ingresar){
			echo "exito";
		}else{
			echo "fallo";
		}
	}else{
				$sql_ingresar = mysql_query("update contribuyente set razon_social = '".$razon_social."',
																		rif = '".$rif."',
																		telefono = '".$telefono."',
																		email = '".$email."',
																		estado = '".$estado."',
																		municipio = '".$municipios."',
																		parroquia = '".$parroquia."',
																		sectores = '".$sectores."',
																		urbanizacion = '".$urbanizacion."',
																		calle = '".$calle."',
																		carrera = '".$carrera."',
																		nro_casa = '".$nro_casa."',
																		punto_referencia = '".$punto_referencia."',
																		where
																		idcontribuyente = '".$idcontribuyente."'");
			if($sql_ingresar){
			echo "exito";
		}else{
			echo "fallo";
		}
	}
		
	break;
	
	case "eliminarSocio":
	echo "delete from socios_contribuyente where idsocios_contribuyente = '".$idsocios_contribuyentes."'";
		$sql_eliminar = mysql_query("delete from socios_contribuyente where idsocios_contribuyente = '".$idsocios_contribuyentes."'")or die(mysql_error());
	break;
	
	
	case "agregarActividad":
		$sql_ingresar = mysql_query("insert into actividades_contribuyente(idcontribuyente,
																			idactividad_comercial,
																			status,
																			usuario,
																			fechayhora)VALUES('".$idcontribuyente."',
																								'".$idactividad_comercial."',
																								'a',
																								'".$login."',
																								'".$fh."')");
	break;
	
	case "cargarListaActividades":
		$sql_consultar = mysql_query("select 
									ac.denominacion,
									aco.idactividades_contribuyente
		 								from 
									actividades_contribuyente aco,
									actividades_comerciales ac
										where 
									aco.idcontribuyente = '".$idcontribuyente."'
									and ac.idactividades_comerciales = aco.idactividad_comercial")or die(mysql_error());
	$num_consultar = mysql_num_rows($sql_consultar);
	if($num_consultar != 0){
	?>
	<br>
    <table width="80%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
    <thead>
      <tr>
        <td width="30%" class="Browse"><div align="center">Denominacion</div></td>
        <td width="3%" class="Browse"><div align="center">Eliminar</div></td>
      </tr>
      </thead>
	
	<?
	
	while($bus_consultar = mysql_fetch_array($sql_consultar)){
		?>
        
          <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
            <td class='Browse' align='left'>&nbsp;<?=$bus_consultar["denominacion"]?></td>
            <td class='Browse' align='center'><img src="imagenes/delete.png" style="cursor:pointer" onclick="eliminarActividad('<?=$bus_consultar["idactividades_contribuyente"]?>')"></td>
            </tr>
	<?
	  }
	  ?>
        </table>
        
		<?
		}else{
			echo "vacio";
		}
		break;
		
		case "eliminarActividad":
		
		$sql_eliminar = mysql_query("delete from actividades_contribuyente where idactividades_contribuyente = '".$idactividades_contribuyente."'")or die(mysql_error());
		
		break;
		
		case "eliminarContribuyente":
			$sql_eliminar = mysql_query("delete from actividades_contribuyente where idcontribuyente = '".$idcontribuyente."'")or die(mysql_error());
			
			$sql_consultar = mysql_query("select * from imagenes_contribuyente where idcontribuyente = '".$idcontribuyente."'");
			while($bus_consultar = mysql_fetch_array($sql_consultar)){
						$sql_consulta = mysql_query("select * from imagenes_contribuyente where idimagenes_contribuyente = '".$bus_consultar["idimagenes_contribuyente"]."'");
						$bus_consulta = mysql_fetch_array($sql_consulta);
						$partes = explode("/", $bus_consulta["ruta"]);
						unlink("../img/".$partes[3]);				
			}
			
			$sql_eliminar = mysql_query("delete from imagenes_contribuyente where idcontribuyente = '".$idcontribuyente."'")or die(mysql_error());
			$sql_eliminar = mysql_query("delete from socios_contribuyente where idcontribuyente = '".$idcontribuyente."'")or die(mysql_error());
			$sql_eliminar = mysql_query("delete from contribuyente where idcontribuyente = '".$idcontribuyente."'")or die(mysql_error());
		
		break;
		
		
		
		
		
		
		case "consultarContribuyente":
		
		$sql_consulta = mysql_query("select * from contribuyente where idcontribuyente = '".$idcontribuyente."'")or die(mysql_error());
		$bus_consulta = mysql_fetch_array($sql_consulta);
		
		if($bus_consulta["tipo_contribuyente"] == 've'){
			echo $bus_consulta["estado"]."|.|".
				$bus_consulta["municipios"]."|.|".
				$bus_consulta["parroquia"]."|.|".
				$bus_consulta["sectores"]."|.|".
				$bus_consulta["urbanizacion"]."|.|".
				$bus_consulta["calle"]."|.|".
				$bus_consulta["carrera"]."|.|".
				$bus_consulta["nro_placa"]."|.|".
				$bus_consulta["tipo_vehiculo"]."|.|".
				$bus_consulta["marca"]."|.|".
				$bus_consulta["modelo"]."|.|".
				$bus_consulta["tipo"]."|.|".
				$bus_consulta["color"]."|.|".
				$bus_consulta["peso"]."|.|".
				$bus_consulta["capacidad"]."|.|".
				$bus_consulta["nro_matricula"]."|.|".
				$bus_consulta["fecha_matricula"]."|.|".
				$bus_consulta["serial_motor"]."|.|".
				$bus_consulta["serial_carroceria"]."|.|".
				$bus_consulta["uso_vehiculo"]."|.|".
				$bus_consulta["propietario"]."|.|".
				$bus_consulta["cedula_propietario"];
		
		}else{
			echo $bus_consulta["razon_social"]."|.|".
			$bus_consulta["rif"]."|.|".
			$bus_consulta["telefono"]."|.|".
			$bus_consulta["email"]."|.|".
			$bus_consulta["estado"]."|.|".
			$bus_consulta["municipio"]."|.|".
			$bus_consulta["parroquia"]."|.|".
			$bus_consulta["sectores"]."|.|".
			$bus_consulta["urbanizacion"]."|.|".
			$bus_consulta["calle"]."|.|".
			$bus_consulta["carrera"]."|.|".
			$bus_consulta["nro_casa"]."|.|".
			$bus_consulta["punto_referencia"]."|.|".
			$bus_consulta["tipo_persona"]."|.|".
			$bus_consulta["tipo_empresa"]."|.|".
			$bus_consulta["tipo_sociedad"]."|.|".
			$bus_consulta["objeto"]."|.|".
			$bus_consulta["libro"]."|.|".
			$bus_consulta["folio"]."|.|".
			$bus_consulta["capital_social"]."|.|".
			$bus_consulta["capital_suscrito"];
			
		}
		
		
		
		
		break;
		
		
		
		case "consultarRequisitos":
			?>
			<center><div id="div_fechaVencimientoRequisitos" style="display:none; border:#000000 solid 1px; width:300px; background-color:#FFFFCC" align="center">
                  <input type="hidden" id="idrequisito" name="idrequisito">
                  <table>
                    <tr>
                        <td>Fecha Vencimiento</td>
                        <td><input name="fecha_vencimiento" type="text" id="fecha_vencimiento" size="12" readonly="readonly">
        <img src="imagenes/jscalendar0.gif" name="f_trigger_f" width="16" height="16" id="f_trigger_f" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" onclick="Calendar.setup({
                                inputField    : 'fecha_vencimiento',
                                button        : 'f_trigger_f',
                                align         : 'Tr',
                                ifFormat      : '%Y-%m-%d'
                                });"/>
                        </td>
                        <td><input type="button" id="boton_asociar_requisito" name="boton_asociar_requisito" onclick="seleccionarrequisitoVencimiento()" class="button" value="Asociar"></td>
                    </tr>
                </table>
            </div>
            <table>
            <tr>
            <td style="background-color:#FF0000">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td><strong>Documento Vencido</strong>&nbsp;&nbsp;&nbsp;</td>
            <td style="background-color:#e7dfce">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td><strong>Documento Asociado con Exito</strong></td>
            </tr>
            </table>
            </center>
            <table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
            <thead>
              <tr>
                <td width="5%" class="Browse"><div align="center">Sel</div></td>
                <td width="70%" class="Browse"><div align="center">Denominacion</div></td>
                <td width="30%" class="Browse"><div align="center">Fecha Vencimiento</div></td>
              </tr>
              </thead>
			<?
				$sql_consultar_requisitos = mysql_query("select 
														ara.idrequisitos,
														re.denominacion,
														re.vencimiento
															from 
														asociar_requisitos_actividad_comercial ara,
														requisitos re,
														actividades_contribuyente ac
															where 
														ac.idcontribuyente = '".$idcontribuyente."'
														and ara.idactividad_comercial = ac.idactividad_comercial
														and re.idrequisitos = ara.idrequisitos
														group by ara.idrequisitos")or die(mysql_error());
				while($bus_consultar_requisitos = mysql_fetch_array($sql_consultar_requisitos)){
					$sql_consultar_seleccionado = mysql_query("select * from relacion_requisitos_contribuyente where idcontribuyente = '".$idcontribuyente."' and idrequisitos = '".$bus_consultar_requisitos["idrequisitos"]."'");
					$bus_consultar_seleccionado = mysql_fetch_array($sql_consultar_seleccionado);
					$num_consultar_seleccionado = mysql_num_rows($sql_consultar_seleccionado);
					if($bus_consultar_seleccionado["fecha_vencimiento"] < date("Y-m-d") and $bus_consultar_seleccionado["fecha_vencimiento"] != '0000-00-00' and $bus_consultar_seleccionado["fecha_vencimiento"] != ''){
						$color = '#FF0000';
					}else{
						$color = '#e7dfce';
					}
					?>
					<tr bgcolor='<?=$color?>' onMouseOver="setRowColor(this, 0, 'over', '<?=$color?>', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '<?=$color?>', '#EAFFEA', '#FFFFAA')">
                        <td class='Browse' align='center'>
                        <input type="checkbox" <? if($num_consultar_seleccionado > 0){echo "checked='checked'";}?> id="check_<?=$bus_consultar_requisitos["idrequisitos"]?>"  name="check_<?=$bus_consultar_requisitos["idrequisitos"]?>" value="<?=$bus_consultar_requisitos["idrequisitos"]?>" style="cursor:pointer" onclick="seleccionarRequisito('<?=$idcontribuyente?>', '<?=$bus_consultar_requisitos["idrequisitos"]?>', '<?=$bus_consultar_requisitos["vencimiento"]?>', this.id)"></td>
                        <td class='Browse' align='left'>&nbsp;<?=$bus_consultar_requisitos["denominacion"]?></td>
                        <td class='Browse' align='center'>&nbsp;<? if($bus_consultar_seleccionado["fecha_vencimiento"] == '0000-00-00'){echo "<strong>SIN VENCIMIENTO</strong>";}else{echo $bus_consultar_seleccionado["fecha_vencimiento"];}?></td>
                        
                    </tr>
					<?	
				}
			?>
			</table>
			<?
		break;
		
		case "seleccionarrequisitoVencimiento":
			$sql_insertar = mysql_query("insert into relacion_requisitos_contribuyente(idrequisitos,
																							idcontribuyente,
																							fecha_vencimiento)VALUES('".$idrequisitos."',
																							'".$idcontribuyente."',
																							'".$fecha_vencimiento."')");
		break;
		
		case "seleccionarRequisito":
		$sql_consultar = mysql_query("select * from relacion_requisitos_contribuyente where idrequisitos = '".$idrequisitos."' and idcontribuyente = '".$idcontribuyente."'");
		$num_consultar = mysql_fetch_array($sql_consultar);
		
		if($num_consultar > 0){
			$sql_eliminar = mysql_query("delete from relacion_requisitos_contribuyente where idrequisitos = '".$idrequisitos."' and idcontribuyente = '".$idcontribuyente."'");
		}else{
			$sql_insertar = mysql_query("insert into relacion_requisitos_contribuyente(idrequisitos,
																						idcontribuyente)VALUES('".$idrequisitos."',
																						'".$idcontribuyente."')");
		}
		
		break;
		case "ingresarRegistroMercantil":
			$sql_ingresar = mysql_query("insert into registro_mercantil_contribuyente(idcontribuyente,
																		tipo_persona,
																		tipo_empresa,
																		tipo_sociedad,
																		fecha_registro,
																		objeto,
																		libro,
																		folio,
																		capital_social,
																		capital_suscrito)VALUES('".$idcontribuyente."',
																								'".$tipo_persona."',
																								'".$tipo_empresa."',
																								'".$tipo_sociedad."',
																								'".$fecha_registro."',
																								'".$objeto."',
																								'".$libro."',
																								'".$folio."',
																								'".$capital_social."',
																								'".$capital_suscrito."')")or die(mysql_error());
		
		break;
		
		case "consultarRegistroMercantil":
			$sql_consulta = mysql_query("SELECT tp.descripcion as denominacion_persona,
												te.descripcion as denominacion_empresa,
												ts.descripcion as denominacion_sociedad,
												rm.*
													FROM 
												registro_mercantil_contribuyente rm,
												tipos_persona tp,
												tipo_empresa te,
												tipo_sociedad ts 
													WHERE 
												rm.idcontribuyente = '".$idcontribuyente."'
												and tp.idtipos_persona = rm.tipo_persona
												and te.idtipo_empresa = rm.tipo_empresa
												and ts.idtipo_sociedad = rm.tipo_sociedad")or die(mysql_error());
			 $num_consulta = mysql_num_rows($sql_consulta);
			 if($num_consulta > 0){
			?>
			<table width="80%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
            <thead>
              <tr>
                <td class="Browse"><div align="center">Tipo Persona</div></td>
                <td class="Browse"><div align="center">Tipo Empresa</div></td>
                <td class="Browse"><div align="center">Tipo Sociedad</div></td>
                <td class="Browse"><div align="center">Fecha Registro</div></td>
                <td class="Browse"><div align="center">Capital Social</div></td>
                <td class="Browse"><div align="center">Capital Suscrito</div></td>
                <td class="Browse" colspan="2"><div align="center">Acciones</div></td>
              </tr>
              </thead>
            
			<?
			while($bus_consulta = mysql_fetch_array($sql_consulta)){
				?>
				<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                        <td class='Browse' align='center'><?=$bus_consulta["denominacion_persona"]?></td>
                        <td class='Browse' align='center'><?=$bus_consulta["denominacion_empresa"]?></td>
                        <td class='Browse' align='center'><?=$bus_consulta["denominacion_sociedad"]?></td>
                        <td class='Browse' align='center'><?=$bus_consulta["fecha_registro"]?></td>
                        <td class='Browse' align='right'><?=number_format($bus_consulta["capital_social"],2,",",".")?></td>
                        <td class='Browse' align='right'><?=number_format($bus_consulta["capital_suscrito"],2,",",".")?></td>
                        <td class='Browse' align='center'><img src="imagenes/modificar.png" onclick="seleccionarRegistroMercantil('<?=$bus_consulta["idregistro_mercantil_contribuyente"]?>', '<?=$bus_consulta["tipo_persona"]?>', '<?=$bus_consulta["tipo_empresa"]?>', '<?=$bus_consulta["tipo_sociedad"]?>', '<?=$bus_consulta["fecha_registro"]?>', '<?=$bus_consulta["objeto"]?>', '<?=$bus_consulta["libro"]?>', '<?=$bus_consulta["folio"]?>', '<?=$bus_consulta["capital_social"]?>', '<?=$bus_consulta["capital_suscrito"]?>')" style="cursor:pointer"></td>
                        <td class='Browse' align='center'><img src="imagenes/delete.png" onclick="eliminarRegistroMercantil('<?=$bus_consulta["idregistro_mercantil_contribuyente"]?>')" style="cursor:pointer"></td>
				<?
			}
			?>
			</table>
			<?
			}else{
				echo "<br /><br /><center><strong>NO HAY NINGUN REGISTRO MERCANTIL INGRESADO</strong></center>";
			}
		break;
		
		
		
		case "modificarRegistroMercantil":
		
		$sql_ingresar = mysql_query("UPDATE registro_mercantil_contribuyente 
																			SET 
																		tipo_persona = '".$tipo_persona."',
																		tipo_empresa = '".$tipo_empresa."',
																		tipo_sociedad = '".$tipo_sociedad."',
																		fecha_registro = '".$fecha_registro."',
																		objeto = '".$objeto."',
																		libro = '".$libro."',
																		folio = '".$folio."',
																		capital_social = '".$capital_social."',
																		capital_suscrito = '".$capital_suscrito."'
																			WHERE 
																		idregistro_mercantil_contribuyente = '".$idregistro_mercantil."'")or die(mysql_error());
		
		break;
		
		case "eliminarRegistroMercantil":
			$sql_eliminar = mysql_query("delete from registro_mercantil_contribuyente where idregistro_mercantil_contribuyente = '".$idregistro_mercantil_contribuyente."'");
		break;
		
}
?>
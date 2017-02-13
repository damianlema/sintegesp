<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
include("funciones_conceptos.php");
Conectarse();
extract($_POST);




if($ejecutar == "consultarAsociados"){
	$sql_consultar = mysql_query("select * from relacion_tipo_nomina_trabajador where idtrabajador = '".$idtrabajador."'");
?>
	<table class="Main" cellpadding="0" cellspacing="0" width="80%" align="center">
        <tr>
            <td>
            
            
                <table class="Browse" cellpadding="0" cellspacing="0" width="40%" align="center">
                    <thead>
                        <tr>
                            <!--<td class="Browse">&nbsp;</td>-->
                            <td width="83%" align="center" class="Browse">Titulo Nomina</td>
                            <td width="83%" align="center" class="Browse">Activa</td>
                            <td width="17%" align="center" class="Browse">Acci&oacute;n</td>
                        </tr>
                    </thead>
                    
                    <?php
                        while($bus= mysql_fetch_array($sql_consultar)){
                        ?>
                        <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer">
                            <?
                            $sql_tipo_nomina = mysql_query("select * from tipo_nomina where idtipo_nomina = '".$bus["idtipo_nomina"]."'");
							$bus_tipo_nomina = mysql_fetch_array($sql_tipo_nomina);
							?>
                            <td align='left' class='Browse'><?=$bus_tipo_nomina["titulo_nomina"]?></td>
                            <td class='Browse' align="center">
                            	<input type="checkbox" name="activar_tipo_nomina[]" id="activar_tipo_nomina<?=$bus_tipo_nomina["idtipo_nomina"]?>" value="<?=$bus_tipo_nomina["idtipo_nomina"]?>" <?php if ($bus["activa"]) echo "checked='checked'"; ?> onclick="activar_desactivar('<?=$bus["idrelacion_tipo_nomina_trabajador"]?>')">
                            </td>
                            <td align='center' class='Browse'>
                            	<img src='imagenes/delete.png' onclick="eliminarAsociacion('<?=$bus["idrelacion_tipo_nomina_trabajador"]?>')">
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








if($ejecutar == "asociarTipoNomina"){
	//echo $arreglo;
	if($arreglo == ""){
	
		$sql_consultar = mysql_query("select * from relacion_tipo_nomina_trabajador where idtrabajador = '".$idtrabajador."' and idtipo_nomina = '".$idtipo_nomina."'");
		$num_consultar = mysql_num_rows($sql_consultar);
		if($num_consultar == 0){
			$sql_insert= mysql_query("insert into relacion_tipo_nomina_trabajador
												(idtrabajador,
												 idtipo_nomina)
													VALUES
												('".$idtrabajador."',
												 '".$idtipo_nomina."')");
			
			registra_transaccion('Se asocio el tipo de nomina ('.$idtipo_nomina.') al trabajador ('.$idtrabajador.')',$login,$fh,$pc,'nomina',$conexion_db);
												 
			$sql_consulta_nomina = mysql_query("select * from tipo_nomina where idtipo_nomina = '".$idtipo_nomina."'");
				$bus_consulta_nomina = mysql_fetch_array($sql_consulta_nomina);
				
				$sql_consulta_trabajador = mysql_query("select * from trabajador where idtrabajador = '".$idtrabajador."'");
				$bus_consulta_trabajador = mysql_fetch_array($sql_consulta_trabajador);
				
					$sql_ingresar = mysql_query("insert into movimientos_personal(
																	  idtrabajador,
																	  fecha_movimiento,
																	  idtipo_movimiento,
																	  justificacion,
																	  fecha_ingreso,
																	  causal,
																	  idcargo,
																	  idubicacion_funcional,
																	  usuario,
																	  status,
																	  fechayhora,
																	  centro_costo,
																	  idtipo_nomina)VALUES(
																		'".$idtrabajador."',
																		'".date("Y-m-d")."',
																		'1000000',
																		'SE LE ASOCIA EL TIPO DE NOMINA (".$bus_consulta_nomina["titulo_nomina"].")',
																		'".$bus_consulta_trabajador["fecha_ingreso"]."',
																		'".$causal."',
																		'".$bus_consulta_trabajador["idcargo"]."',
																		'".$bus_consulta_trabajador["idunidad_funcional"]."',
																		'".$login."',
																		'a',
																		'".$fh."',
																		'".$bus_consulta_trabajador["centro_costo"]."',
																		'".$idtipo_nomina."')")or die(mysql_error());									 
				
		}else{
			echo "existe";	
		}
	}else{
		$arreglo = explode(",", $arreglo);
		foreach($arreglo as $arr){
			if($arr != ""){
				$sql_insert= mysql_query("insert into relacion_tipo_nomina_trabajador
												(idtrabajador,
												 idtipo_nomina)
													VALUES
												('".$arr."',
												 '".$idtipo_nomina."')");
												 
				registra_transaccion('Se asocio el Tipo de Nomina ('.$idtipo_nomina.') al trabajador ('.$arr.')',$login,$fh,$pc,'nomina',$conexion_db);
				
				$sql_consulta_nomina = mysql_query("select * from tipo_nomina where idtipo_nomina = '".$idtipo_nomina."'");
				$bus_consulta_nomina = mysql_fetch_array($sql_consulta_nomina);
				
				$sql_consulta_trabajador = mysql_query("select * from trabajador where idtrabajador = '".$arr."'");
				$bus_consulta_trabajador = mysql_fetch_array($sql_consulta_trabajador);
				
					$sql_ingresar = mysql_query("insert into movimientos_personal(
																	  idtrabajador,
																	  fecha_movimiento,
																	  idtipo_movimiento,
																	  justificacion,
																	  fecha_ingreso,
																	  causal,
																	  idcargo,
																	  idubicacion_funcional,
																	  usuario,
																	  status,
																	  fechayhora,
																	  centro_costo,
																	  idtipo_nomina)VALUES(
																		'".$arr."',
																		'".date("Y-m-d")."',
																		'1000000',
																		'SE LE ASOCIA EL TIPO DE NOMINA (".$bus_consulta_nomina["titulo_nomina"].")',
																		'".$bus_consulta_trabajador["fecha_ingreso"]."',
																		'".$causal."',
																		'".$bus_consulta_trabajador["idcargo"]."',
																		'".$bus_consulta_trabajador["idunidad_funcional"]."',
																		'".$login."',
																		'a',
																		'".$fh."',
																		'".$bus_consulta_trabajador["centro_costo"]."',
																		'".$idtipo_nomina."')")or die(mysql_error());
			}
		}
	}
}


if($ejecutar == "activar_desactivar"){
	$sql_consulta = mysql_query("select * from relacion_tipo_nomina_trabajador where idrelacion_tipo_nomina_trabajador = '".$id."'");
	$bus_consulta = mysql_fetch_array($sql_consulta);
	
	$sql_consultar_tipo_nomina = mysql_query("select * from tipo_nomina where idtipo_nomina = '".$bus_consulta["idtipo_nomina"]."'");
	$bus_consultar_tipo_nomina = mysql_fetch_array($sql_consultar_tipo_nomina);
	
	
	$sql_consulta_trabajador = mysql_query("select * from trabajador where idtrabajador = '".$bus_consulta["idtrabajador"]."'");
	$bus_consulta_trabajador = mysql_fetch_array($sql_consulta_trabajador);
	
	
	if ($bus_consulta["activa"] == '1'){
		$sql_desactiva = mysql_query("UPDATE relacion_tipo_nomina_trabajador set activa='0' 
											where 
											idrelacion_tipo_nomina_trabajador ='".$id."' ")or die("error desactiva ".mysql_error());	
		
		$sql_ingresar = mysql_query("insert into movimientos_personal(
																	  idtrabajador,
																	  fecha_movimiento,
																	  idtipo_movimiento,
																	  justificacion,
																	  fecha_ingreso,
																	  causal,
																	  idcargo,
																	  idubicacion_funcional,
																	  usuario,
																	  status,
																	  fechayhora,
																	  centro_costo,
																	  idtipo_nomina)VALUES(
																		'".$bus_consulta["idtrabajador"]."',
																		'".date("Y-m-d")."',
																		'1000000',
																		'SE DESACTIVO DE LA NOMINA (".$bus_consultar_tipo_nomina["titulo_nomina"].")',
																		'".$bus_consulta_trabajador["fecha_ingreso"]."',
																		'".$causal."',
																		'".$bus_consulta_trabajador["idcargo"]."',
																		'".$bus_consulta_trabajador["idunidad_funcional"]."',
																		'".$login."',
																		'a',
																		'".$fh."',
																		'".$bus_consulta_trabajador["centro_costo"]."',
																		'".$bus_consulta["idtipo_nomina"]."')")or die(mysql_error());
		
		
	}else{
		$sql_activa = mysql_query("UPDATE relacion_tipo_nomina_trabajador set activa='1' 
											where 
											idrelacion_tipo_nomina_trabajador ='".$id."' ")or die("error activa ".mysql_error());	
		
		$sql_ingresar = mysql_query("insert into movimientos_personal(
																	  idtrabajador,
																	  fecha_movimiento,
																	  idtipo_movimiento,
																	  justificacion,
																	  fecha_ingreso,
																	  causal,
																	  idcargo,
																	  idubicacion_funcional,
																	  usuario,
																	  status,
																	  fechayhora,
																	  centro_costo,
																	  idtipo_nomina)VALUES(
																		'".$bus_consulta["idtrabajador"]."',
																		'".date("Y-m-d")."',
																		'1000000',
																		'SE ACTIVO EN LA NOMINA (".$bus_consultar_tipo_nomina["titulo_nomina"].")',
																		'".$bus_consulta_trabajador["fecha_ingreso"]."',
																		'".$causal."',
																		'".$bus_consulta_trabajador["idcargo"]."',
																		'".$bus_consulta_trabajador["idunidad_funcional"]."',
																		'".$login."',
																		'a',
																		'".$fh."',
																		'".$bus_consulta_trabajador["centro_costo"]."',
																		'".$bus_consulta["idtipo_nomina"]."')")or die(mysql_error());
		
	}
}


if($ejecutar == "eliminarAsociacion"){
	$sql_consulta = mysql_query("select * from relacion_tipo_nomina_trabajador where idrelacion_tipo_nomina_trabajador = '".$id."'");
	$bus_consulta = mysql_fetch_array($sql_consulta);
	
	$sql_eliminar_concepto = mysql_query("delete from relacion_concepto_trabajador 
														where 
														idtrabajador = '".$bus_consulta["idtrabajador"]."' 
														and idtipo_nomina = '".$bus_consulta["idtipo_nomina"]."'");
	
	
	registra_transaccion('Se elimino la asociacion del tipo de nomina ('.$bus_consulta["idtipo_nomina"].') al trabajador ('.$bus_consulta["idtrabajador"].')',$login,$fh,$pc,'nomina',$conexion_db);
	
	$sql_consultar_tipo_nomina = mysql_query("select * from tipo_nomina where idtipo_nomina = '".$bus_consulta["idtipo_nomina"]."'");
	$bus_consultar_tipo_nomina = mysql_fetch_array($sql_consultar_tipo_nomina);
	
	
	$sql_consulta_trabajador = mysql_query("select * from trabajador where idtrabajador = '".$bus_consulta["idtrabajador"]."'");
	$bus_consulta_trabajador = mysql_fetch_array($sql_consulta_trabajador);
	
	$sql_ingresar = mysql_query("insert into movimientos_personal(
																	  idtrabajador,
																	  fecha_movimiento,
																	  idtipo_movimiento,
																	  justificacion,
																	  fecha_ingreso,
																	  causal,
																	  idcargo,
																	  idubicacion_funcional,
																	  usuario,
																	  status,
																	  fechayhora,
																	  centro_costo,
																	  idtipo_nomina)VALUES(
																		'".$bus_consulta["idtrabajador"]."',
																		'".date("Y-m-d")."',
																		'1000000',
																		'SE RETIRO DE LA NOMINA (".$bus_consultar_tipo_nomina["titulo_nomina"].")',
																		'".$bus_consulta_trabajador["fecha_ingreso"]."',
																		'".$causal."',
																		'".$bus_consulta_trabajador["idcargo"]."',
																		'".$bus_consulta_trabajador["idunidad_funcional"]."',
																		'".$login."',
																		'a',
																		'".$fh."',
																		'".$bus_consulta_trabajador["centro_costo"]."',
																		'".$bus_consulta["idtipo_nomina"]."')")or die(mysql_error());
	
	
	
	$sql_eliminar = mysql_query("delete from relacion_tipo_nomina_trabajador where idrelacion_tipo_nomina_trabajador = '".$id."'");
		
}






if($ejecutar == "consultarListaTrabajadores"){
	
	if($select_buscar == "cedula"){
		$sql_consulta = mysql_query("select tr.cedula,
										tr.nombres,
										tr.apellidos,
										tr.idtrabajador,
										tr.nro_ficha
										from 
											trabajador tr 
										where
											tr.cedula like '%".$texto_buscar."%'
											and tr.status = 'a'
										order by tr.nro_ficha, tr.cedula")or die(mysql_error());
	}else if($select_buscar == "nro_ficha"){
		$sql_consulta = mysql_query("select tr.cedula,
										tr.nombres,
										tr.apellidos,
										tr.idtrabajador,
										tr.nro_ficha
											from 
										trabajador tr 
											where
										tr.nro_ficha like '%".$texto_buscar."%'
										and tr.status = 'a'
										order by tr.nro_ficha, tr.cedula")or die(mysql_error());
	
	}else{
		$sql_consulta = mysql_query("select tr.cedula,
										tr.nombres,
										tr.apellidos,
										tr.idtrabajador,
										tr.nro_ficha
										from 
											trabajador tr 
										where 
											tr.status = 'a'
										order by tr.nro_ficha, tr.cedula")or die(mysql_error());
	}
	
	?>
	
    <table>
        <tr>
       		<td><input type="text" name="buscar_trabajador" id="buscar_trabajador"></td>
            <td>
            <select name="lista_tipo_busqueda" id="lista_tipo_busqueda">
            	<option value="cedula">Cedula</option>
                <option value="nro_ficha">Nro Ficha</option>
            </select>
            </td>
            <td><input type="button" name="boton_buscar_trabajador" id="boton_buscar_trabajador" onclick="consultarListaTrabajadores(document.getElementById('buscar_trabajador').value, document.getElementById('lista_tipo_busqueda').value)" value="Buscar"></td>
        </tr>
    </table>
    
    
	<form id="form_trabajadores" name="form_trabajadores">
    <table class="Browse" cellpadding="0" cellspacing="0" width="80%" align="center">
                    <thead>
                        <tr>
                            <!--<td class="Browse">&nbsp;</td>-->
                            <td width="10%" align="center" class="Browse"><input type="checkbox" name="sel_todos" id="sel_todos" onclick="seleccionarTodo()">&nbsp;Sel.</td>
                           <td width="2%" align="center" class="Browse">Nro</td>
			    <td width="15%" align="center" class="Browse">Nro Ficha</td>
                            <td width="15%" align="center" class="Browse">Cedula</td>
                            <td width="79%" align="center" class="Browse">Nombre</td>
                        </tr>
                    </thead>
                    
                    <?php
			$i=1;		
                        while($bus_consulta = mysql_fetch_array($sql_consulta)){
							$sql_tipo_nomina = mysql_query("select * 
														   			from 
																	relacion_tipo_nomina_trabajador 
																	where 
																	idtrabajador = '".$bus_consulta["idtrabajador"]."'
																	and idtipo_nomina = '".$idtipo_nomina."'");
							$num_tipo_nomina = mysql_num_rows($sql_tipo_nomina);
							if($num_tipo_nomina == 0){
                        ?>
                        <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer">
                        	<td class='Browse' align="center">
                            <input type="checkbox" name="seleccionar[]" id="seleccionar<?=$bus_consulta["idtrabajador"]?>" value="<?=$bus_consulta["idtrabajador"]?>">
                            </td>
				<td align='center' class='Browse'><?=$i?></td>                           
				 <td align='left' class='Browse'><?=$bus_consulta["nro_ficha"]?></td>
                            <td align='left' class='Browse'><?=$bus_consulta["cedula"]?></td>
                            <td align='left' class='Browse'><?=$bus_consulta["nombres"]." ".$bus_consulta["apellidos"]?></td>
                        </tr>
                        <?
				$i++;
							}
                        }
                    ?>
                </table>
                </form>
	<?
	
	
}
?>

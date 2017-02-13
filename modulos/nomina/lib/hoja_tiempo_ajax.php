<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
include("funciones_conceptos.php");
Conectarse();
extract($_POST);


if($ejecutar == 'seleccionarPeriodo'){
	$sql_consultar_periodo = mysql_query("select * from 
										 			periodos_nomina pn,
													rango_periodo_nomina rpn
										 			where 
													pn.idtipo_nomina = '".$idtipo_nomina."'
													and pn.periodo_activo = 'si'
													and rpn.idperiodo_nomina = pn.idperiodos_nomina")or die(mysql_error());
	?>
<select name="periodo" id="periodo">
    	<option value="0">.:: Seleccione ::.</option>
	<?
		while($bus_consultar_periodo= mysql_fetch_array($sql_consultar_periodo)){
			?>
			<option value="<?=$bus_consultar_periodo["idrango_periodo_nomina"]?>">
				<?
                $desde = explode(" ", $bus_consultar_periodo["desde"]);
                $hasta = explode(" ", $bus_consultar_periodo["hasta"]);
                echo $desde[0]." - ".$hasta[0];
                ?>
            </option>
			<?	
		}
	?>
	</select>
	<?
}





if($ejecutar=="ingresarDatos"){
    $centro_costo = 0;
	$sql_consultar = mysql_query("select * from hoja_tiempo where 
								 				idtipo_hoja_tiempo = '".$idtipo_hoja_tiempo."'
												and idtipo_nomina = '".$idtipo_nomina."'
												and centro_costo ='".$centro_costo."'
												and periodo = '".$idperiodo."'")or die(mysql_error());
	$num_consultar = mysql_num_rows($sql_consultar);
	if($num_consultar == 0){
		$sql_ingresar = mysql_query("insert into hoja_tiempo
												(idtipo_hoja_tiempo,
												 idtipo_nomina,
												 centro_costo,
												 periodo)VALUES('".$idtipo_hoja_tiempo."',
												 				'".$idtipo_nomina."',
																'".$centro_costo."',
																'".$idperiodo."')");
	
		echo mysql_insert_id();
	}else{
		echo "existe";	
	}
}



if($ejecutar=="duplicarDatos"){
    $centro_costo = 0;
	$sql_consultar = mysql_query("select * from hoja_tiempo where 
								 				idtipo_hoja_tiempo = '".$idtipo_hoja_tiempo."'
												and idtipo_nomina = '".$idtipo_nomina."'
												and centro_costo ='".$centro_costo."'
												and periodo = '".$idperiodo."'")or die(mysql_error());
	$num_consultar = mysql_num_rows($sql_consultar);
	if($num_consultar == 0){
		$sql_ingresar = mysql_query("insert into hoja_tiempo
												(idtipo_hoja_tiempo,
												 idtipo_nomina,
												 centro_costo,
												 periodo)VALUES('".$idtipo_hoja_tiempo."',
												 				'".$idtipo_nomina."',
																'".$centro_costo."',
																'".$idperiodo."')");
	
		$nueva_hoja_duplicada = mysql_insert_id();
		echo $nueva_hoja_duplicada;
	}else{
		echo "existe";	
	}
}





if($ejecutar == "consultarTrabajadores"){
    $centro_costo = 0;
	$sql_hoja_tiempo = mysql_query("select * from hoja_tiempo where idhoja_tiempo = '".$idhoja_tiempo."'");
	$bus_hoja_tiempo = mysql_fetch_array($sql_hoja_tiempo);
	
	
	
	$sql_con = mysql_query("select * from generar_nomina 
											where
											idtipo_nomina = '".$idtipo_nomina."' 
											and idperiodo = '".$bus_hoja_tiempo["periodo"]."'")or die(mysql_error());
	$bus_con = mysql_fetch_array($sql_con);
	//echo "ESTADO: ".$bus_con["estado"];
	if($bus_con["estado"] == 'procesado'){
		$sql_consulta = mysql_query("select tr.nombres,
										tr.apellidos,
										tr.cedula,
										tr.idtrabajador,
										tr.nro_ficha
											FROM 
										trabajador tr,
										relacion_generar_nomina rgn
											WHERE
										rgn.idgenerar_nomina = '".$bus_con["idgenerar_nomina"]."'
										and rgn.idtrabajador =  tr.idtrabajador
                    					group by tr.idtrabajador
										order by tr.cedula")or die(mysql_error());
	}else{
        if ($centro_costo <> '0') {
            $sql_consulta = mysql_query("select tr.nombres,
										tr.apellidos,
										tr.cedula,
										tr.idtrabajador,
										tr.nro_ficha
											FROM 
										trabajador tr,
										relacion_tipo_nomina_trabajador rtn
											WHERE
										rtn.idtrabajador = tr.idtrabajador	
										and rtn.idtipo_nomina = '" . $idtipo_nomina . "'
										and tr.centro_costo = '" . $centro_costo . "'
                    					group by tr.idtrabajador
										order by tr.cedula") or die(mysql_error());
        }else{
            $sql_consulta = mysql_query("select tr.nombres,
										tr.apellidos,
										tr.cedula,
										tr.idtrabajador,
										tr.nro_ficha
											FROM
										trabajador tr,
										relacion_tipo_nomina_trabajador rtn
											WHERE
										rtn.idtrabajador = tr.idtrabajador
										and rtn.idtipo_nomina = '" . $idtipo_nomina . "'
                    					group by tr.idtrabajador
										order by tr.cedula") or die(mysql_error());
        }
	}

	$sql_con_hoja= mysql_query("select tht.unidad from 
							   					hoja_tiempo ht, 
							   					tipo_hoja_tiempo tht
												where 
												ht.idhoja_tiempo = '".$idhoja_tiempo."'
												and ht.idtipo_hoja_tiempo = tht.idtipo_hoja_tiempo")or die(mysql_error());
	$bus_con_hoja = mysql_fetch_array($sql_con_hoja);
	?>
	<table class="Browse" cellpadding="0" cellspacing="0" width="80%" align="center">
        <thead>
        <tr>
    		<td width="5%" align="center" class="Browse">No</td>
            <td width="10%" align="center" class="Browse">No Ficha</td>
            <td width="10%" align="center" class="Browse">Cedula</td>
            <td width="35%" align="center" class="Browse">Nombre</td>
            <td width="35%" align="center" class="Browse">Apellido</td>
            <td width="5%" align="center" class="Browse">
            <?
            if($bus_con_hoja["unidad"] == "Anos"){
				echo "A&ntilde;os";
			}else{
				echo $bus_con_hoja["unidad"]	;
			}
			
			?></td>
   		</tr>
        </thead>
        <?
		$i=1;
        while($bus_consulta = mysql_fetch_array($sql_consulta)){
			$sql_con= mysql_query("select * from relacion_hoja_tiempo_trabajador where idhoja_tiempo = '".$idhoja_tiempo."' and idtrabajador='".$bus_consulta["idtrabajador"]."'");
					$num_con=mysql_num_rows($sql_con);
					if($num_con == 0){
						//echo "AQUI";
						$sql_agregar= mysql_query("insert into relacion_hoja_tiempo_trabajador(idhoja_tiempo, idtrabajador)
																VALUES('".$idhoja_tiempo."', '".$bus_consulta["idtrabajador"]."')");	
					}
			?>
			<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer">
                <td align='left' class='Browse'><?=$i?></td>
                <td align='left' class='Browse'><?=$bus_consulta["nro_ficha"]?></td>
                <td align='left' class='Browse'><?=number_format($bus_consulta["cedula"],0,",",".")?></td>
                <td align='left' class='Browse'><?=$bus_consulta["nombres"]?></td>
                <td align='left' class='Browse'><?=$bus_consulta["apellidos"]?></td>
                <td class='Browse' align="center">
                <?
                $sql_con= mysql_query("select * from relacion_hoja_tiempo_trabajador where idtrabajador = ".$bus_consulta["idtrabajador"]."
																							and idhoja_tiempo = '".$idhoja_tiempo."'");
				$bus_con= mysql_fetch_array($sql_con);
				?>
                <input type="text" 
                		name="horas<?=$bus_consulta["idtrabajador"]?>" 
                        id="horas<?=$bus_consulta["idtrabajador"]?>" 
                        style="text-align:right" 
                        size="8" 
                        onKeyUp="guardarHoras('<?=$bus_consulta["idtrabajador"]?>', '<?=$idhoja_tiempo?>', this.value)" 
                        value="<?=$bus_con["horas"]?>" 
                        onClick="this.select()">
                </td>
   			</tr>
			<?	
			$i++;
		}
		?>
        
    </table>
	<?
}


if($ejecutar == "consultarTrabajadoresDuplicar"){
    $centro_costo = 0;
	$sql_hoja_tiempo = mysql_query("select * from hoja_tiempo where idhoja_tiempo = '".$idhoja_tiempo."'");
	$bus_hoja_tiempo = mysql_fetch_array($sql_hoja_tiempo);
	
	
	
	$sql_con = mysql_query("select * from generar_nomina 
											where
											idtipo_nomina = '".$idtipo_nomina."' 
											and idperiodo = '".$bus_hoja_tiempo["periodo"]."'")or die(mysql_error());
	$bus_con = mysql_fetch_array($sql_con);
	//echo "ESTADO: ".$bus_con["estado"];
	if($bus_con["estado"] == 'procesado'){
		$sql_consulta = mysql_query("select tr.nombres,
										tr.apellidos,
										tr.cedula,
										tr.idtrabajador,
										tr.nro_ficha
											FROM 
										trabajador tr,
										relacion_generar_nomina rgn
											WHERE
										rgn.idgenerar_nomina = '".$bus_con["idgenerar_nomina"]."'
										and rgn.idtrabajador =  tr.idtrabajador
                    					group by tr.idtrabajador
										order by tr.cedula")or die(mysql_error());
	}else{
        if ($centro_costo <> '0') {
            $sql_consulta = mysql_query("select tr.nombres,
										tr.apellidos,
										tr.cedula,
										tr.idtrabajador,
										tr.nro_ficha
											FROM 
										trabajador tr,
										relacion_tipo_nomina_trabajador rtn
											WHERE
										rtn.idtrabajador = tr.idtrabajador	
										and rtn.idtipo_nomina = '" . $idtipo_nomina . "'
										and tr.centro_costo = '" . $centro_costo . "'
                    					group by tr.idtrabajador
										order by tr.cedula") or die(mysql_error());
        }else{
            $sql_consulta = mysql_query("select tr.nombres,
										tr.apellidos,
										tr.cedula,
										tr.idtrabajador,
										tr.nro_ficha
											FROM
										trabajador tr,
										relacion_tipo_nomina_trabajador rtn
											WHERE
										rtn.idtrabajador = tr.idtrabajador
										and rtn.idtipo_nomina = '" . $idtipo_nomina . "'
                    					group by tr.idtrabajador
										order by tr.cedula") or die(mysql_error());
        }
	}

	$sql_con_hoja= mysql_query("select tht.unidad from 
							   					hoja_tiempo ht, 
							   					tipo_hoja_tiempo tht
												where 
												ht.idhoja_tiempo = '".$idhoja_tiempo."'
												and ht.idtipo_hoja_tiempo = tht.idtipo_hoja_tiempo")or die(mysql_error());
	$bus_con_hoja = mysql_fetch_array($sql_con_hoja);
	?>
	<table class="Browse" cellpadding="0" cellspacing="0" width="80%" align="center">
        <thead>
        <tr>
    		<td width="5%" align="center" class="Browse">No</td>
            <td width="10%" align="center" class="Browse">No Ficha</td>
            <td width="10%" align="center" class="Browse">Cedula</td>
            <td width="35%" align="center" class="Browse">Nombre</td>
            <td width="35%" align="center" class="Browse">Apellido</td>
            <td width="5%" align="center" class="Browse">
            <?
            if($bus_con_hoja["unidad"] == "Anos"){
				echo "A&ntilde;os";
			}else{
				echo $bus_con_hoja["unidad"]	;
			}
			
			?></td>
   		</tr>
        </thead>
        <?
		$i=1;
        while($bus_consulta = mysql_fetch_array($sql_consulta)){
			$sql_con= mysql_query("select * from relacion_hoja_tiempo_trabajador where 
														idhoja_tiempo = '".$idhoja_tiempo_anterior."' 
														and idtrabajador='".$bus_consulta["idtrabajador"]."'");
			$bus_horas = mysql_fetch_array($sql_con);		
			$sql_agregar= mysql_query("insert into relacion_hoja_tiempo_trabajador(idhoja_tiempo, idtrabajador, horas)
																VALUES('".$idhoja_tiempo."'
																	, '".$bus_consulta["idtrabajador"]."'
																	, '".$bus_horas["horas"]."')");	
					
			?>
			<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer">
                <td align='left' class='Browse'><?=$i?></td>
                <td align='left' class='Browse'><?=$bus_consulta["nro_ficha"]?></td>
                <td align='left' class='Browse'><?=number_format($bus_consulta["cedula"],0,",",".")?></td>
                <td align='left' class='Browse'><?=$bus_consulta["nombres"]?></td>
                <td align='left' class='Browse'><?=$bus_consulta["apellidos"]?></td>
                <td class='Browse' align="center">
                <?
                $sql_con= mysql_query("select * from relacion_hoja_tiempo_trabajador where idtrabajador = ".$bus_consulta["idtrabajador"]."
																							and idhoja_tiempo = '".$idhoja_tiempo."'");
				$bus_con= mysql_fetch_array($sql_con);
				?>
                <input type="text" 
                		name="horas<?=$bus_consulta["idtrabajador"]?>" 
                        id="horas<?=$bus_consulta["idtrabajador"]?>" 
                        style="text-align:right" 
                        size="8" 
                        onKeyUp="guardarHoras('<?=$bus_consulta["idtrabajador"]?>', '<?=$idhoja_tiempo?>', this.value)" 
                        value="<?=$bus_con["horas"]?>" 
                        onClick="this.select()">
                </td>
   			</tr>
			<?	
			$i++;
		}
		?>
        
    </table>
	<?
}





if($ejecutar == "guardarHoras"){
	$sql_actualizar = mysql_query("UPDATE relacion_hoja_tiempo_trabajador 
								  			SET horas = '".$horas."' 
												WHERE 
											idhoja_tiempo ='".$idhoja_tiempo."' 
											and idtrabajador ='".$idtrabajador."'");	
}

if($ejecutar == "guardarHorasPrefijadas"){
    $sql_actualizar = mysql_query("UPDATE relacion_hoja_tiempo_trabajador
								  			SET horas = '".$prefijar_valor."'
												WHERE
											idhoja_tiempo ='".$idhoja_tiempo."'
											")or die(mysql_error());
}

if($ejecutar == 'modificarDatos'){
	$sql_actualizar= mysql_query("update hoja_tiempo set idtipo_hoja_tiempo='".$idtipo_hoja_tiempo."',
								 						idtipo_nomina= '".$idtipo_nomina."',
														centro_costo='".$centro_costo."',
														periodo='".$idperiodo."'
														where idhoja_tiempo = '".$idhoja_tiempo."'");
	$sql_eliminar = mysql_query("delete from relacion_hoja_tiempo_trabajador where idhoja_tiempo='".$idhoja_tiempo."'");
}




if($ejecutar =="eliminarDatos"){
	$sql_con= mysql_query("select * from hoja_tiempo where idhoja_tiempo='".$idhoja_tiempo."'");
	$bus_con = mysql_fetch_array($sql_con);
	$sql_consultar_conceptos= mysql_query("select * from relacion_formula_conceptos_nomina where valor_oculto = 'THT_".$bus_con["idtipo_hoja_tiempo"]."'");
		$num_consultar_conceptos = mysql_num_rows($sql_consultar_conceptos);
		if($num_consultar_conceptos > 0){
			echo "asociada_concepto";		
		}else{
		$sql_eliminar= mysql_query("delete from hoja_tiempo where idhoja_tiempo='".$idhoja_tiempo."'");
		$sql_eliminar= mysql_query("delete from relacion_hoja_tiempo_trabajador where idhoja_tiempo='".$idhoja_tiempo."'");
		
		}
	
}



if($ejecutar=="volverPeriodo"){
	?>
	<select name="periodo" id="periodo">
      <option value="0">.:: Seleccione un tipo de Nomina ::.</option>
      </select>
	<?	
}
?>
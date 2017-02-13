<?
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
Conectarse();

extract($_POST);


if($ejecutar == "consultarFicha"){
	
	$sql_nomenclatura = mysql_query("select * from nomenclatura_fichas where descripcion ='".$nomenclatura_ficha."'")or die(mysql_error());
	$bus_nomenclatura = mysql_fetch_array($sql_nomenclatura);
	
	$numero_con_ceros = str_pad($bus_nomenclatura["numero"], 4, "0", STR_PAD_LEFT);
	echo $numero_con_ceros;
}


if($ejecutar == "buscarMovimientosTrabajador"){
	$sql_consulta = mysql_query("select * FROM 
										movimientos_personal mp
											WHERE 
										mp.idtrabajador = '".$idtrabajador."'
										order by fecha_movimiento");
	?>
    <div align="center" style="font-weight:bold; font-size:12px">LISTA DE MOVIMIENTOS REALIZADOS PARA EL TRABAJADOR</div>
    <br>
	<br>

	<table align="center" class="Main" cellpadding="0" cellspacing="0" width="90%">
    	<thead>
        	<tr>
            	<td class="Browse" align="center">Tipo Movimiento</td>
                <td class="Browse" align="center">Fecha</td>
                <td class="Browse" align="center">Justificacion</td>
                
                <td class="Browse" align="center" colspan="2">Acciones</td>
            </tr>
        </thead>
     <?
     while($bus_consulta = mysql_fetch_array($sql_consulta)){
	 	?>
		<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
        	<td class='Browse'>
			<?
            $sql_tipo_movimiento = mysql_query("select * from tipo_movimiento_personal where idtipo_movimiento = '".$bus_consulta["idtipo_movimiento"]."'");
			$bus_tipo_movimiento = mysql_fetch_array($sql_tipo_movimiento);
			if($bus_consulta["idtipo_movimiento"] == 0){
				echo "INGRESO";
			}else if($bus_consulta["idtipo_movimiento"] == 1000000){
				echo "OTROS MOVIMIENTOS";
			}else{
            	echo $bus_tipo_movimiento["denominacion"];
			}
			?></td>
            <td class='Browse'><?=$bus_consulta["fecha_movimiento"]?></td>
            <td class='Browse'><?=$bus_consulta["justificacion"]?></td>
            
           
            <td class='Browse' align="center">
            &nbsp;
            <?
            if($bus_consulta["idtipo_movimiento"] != 0 and $bus_consulta["idtipo_movimiento"] != 1000000 ){
				?>
            <img src="imagenes/modificar.png" style="cursor:pointer" onclick="seleccionarModificar('<?=$bus_consulta["idmovimientos_personal"]?>','<?=$bus_consulta["fecha_movimiento"]?>', '<?=$bus_consulta["idtipo_movimiento"]?>', '<?=$bus_consulta["justificacion"]?>', '<?=$bus_consulta["fecha_egreso"]?>', '<?=$bus_consulta["causal"]?>', '<?=$bus_consulta["idubicacion_nueva"]?>', '<?=$bus_consulta["fecha_reingreso"]?>', '<?=$bus_consulta["desde"]?>', '<?=$bus_consulta["hasta"]?>', '<?=$bus_consulta["idnuevo_cargo"]?>', '<?=$bus_tipo_movimiento["relacion_laboral"]?>', '<?=$bus_tipo_movimiento["goce_sueldo"]?>', '<?=$bus_tipo_movimiento["afecta_cargo"]?>', '<?=$bus_tipo_movimiento["afecta_ubicacion"]?>', '<?=$bus_tipo_movimiento["afecta_tiempo"]?>', '<?=$bus_consulta["centro_costo"]?>', '<?=$bus_tipo_movimiento["afecta_centro_costo"]?>', '<?=$bus_tipo_movimiento["afecta_ficha"]?>')">
            <?
            }
			?>
            </td>
            <td class='Browse' align="center" style="height:15px;">
            &nbsp;
            <?
            if($bus_consulta["idtipo_movimiento"] != 0 and $bus_consulta["idtipo_movimiento"] != 1000000){
				?>
				<img src="imagenes/delete.png" style="cursor:pointer" onclick="eliminarMovimiento('<?=$bus_consulta["idmovimientos_personal"]?>')">
				<?
            }
			?>
            </td>
        </tr>
		<?
	 }
	 ?>
    </table>
	<?
}







if($ejecutar == "ingresarMovimiento"){
	
	if(strlen($ficha) < 6){
	echo "aqui";
		$sql_consulta_ficha = mysql_query("select * from trabajador where idtrabajador = '".$idtrabajador."'")or die(mysql_error());
		$bus_consulta_ficha = mysql_fetch_array($sql_consulta_ficha);
		$ficha = $bus_consulta_ficha["nro_ficha"];
		
	}

	$sql_ingresar = mysql_query("insert into movimientos_personal(
																	  idtrabajador,
																	  fecha_movimiento,
																	  idtipo_movimiento,
																	  justificacion,
																	  fecha_ingreso,
																	  causal,
																	  idcargo,
																	  idubicacion_funcional,
																	  fecha_egreso,
																	  usuario,
																	  status,
																	  fechayhora,
																	  centro_costo,
																	  fecha_reingreso,
																	  desde,
																	  hasta,
																	  idtipo_nomina,
																	  nro_ficha)VALUES(
																		'".$idtrabajador."',
																		'".$fecha_movimiento."',
																		'".$tipo_movimiento."',
																		'".$justificacion."',
																		'".$fecha_ingreso."',
																		'".$causal."',
																		'".$idnuevo_cargo."',
																		'".$idubicacion_nueva."',
																		'".$fecha_egreso."',
																		'".$login."',
																		'a',
																		'".$fh."',
																		'".$centro_costo."',
																		'".$fecha_reingreso."',
																		'".$desde."',
																		'".$hasta."',
																		'".$idtipo_nomina."',
																		'".$ficha."')")or die(mysql_error());

		
		$sql_actualizar = mysql_query("update trabajador set idcargo = '".$idnuevo_cargo."',
															idunidad_funcional = '".$idubicacion_nueva."',
															idcargo = '".$idnuevo_cargo."',
															centro_costo = '".$centro_costo."',
															nro_ficha = '".$ficha."'
									  						where idtrabajador = '".$idtrabajador."'")or die(mysql_error());	

	$sql_nomenclatura = mysql_query("update nomenclatura_fichas set numero=numero+1 where descripcion = '".substr($ficha,0,2)."'");
	
	
	$sql_consulta_tipo_movimiento = mysql_query("select * from tipo_movimiento_personal where idtipo_movimiento = '".$tipo_movimiento."'");
	$bus_consulta_tipo_movimiento = mysql_fetch_array($sql_consulta_tipo_movimiento);
		
		if($bus_consulta_tipo_movimiento["relacion_laboral"] == 'si'){
		
			$sql_consulta = mysql_query("delete from relacion_tipo_nomina_trabajador where idtrabajador = '".$idtrabajador."'");
			$sql_eliminar_concepto = mysql_query("delete from relacion_concepto_trabajador where idtrabajador = '".$idtrabajador."'");
			$sql_update = mysql_query("update trabajador set status = 'e' where idtrabajador = '".$idtrabajador."'");
		}																
																		
	registra_transaccion("Se ingreso el movimiento de personal con el ID (".mysql_insert_id().", TRABAJADOR : ".$idtrabajador.")",$login,$fh,$pc,'movimientos_personal');
}







if($ejecutar == "modificarMovimiento"){
		
		if($ficha == ""){
		$sql_consulta_ficha = mysql_query("select * from movimientos_personal where idtrabajador = '".$idtrabajador."' limit 0,1 order by desc");
		$bus_consulta_ficha = mysql_fetch_array($sql_consulta_ficha);
		$ficha = $bus_consulta_ficha["ficha"];
		
	}
		
		$sql_ingresar = mysql_query("update movimientos_personal set idtrabajador = '".$idtrabajador."',
																	  fecha_movimiento='".$fecha_movimiento."',
																	  idtipo_movimiento='".$tipo_movimiento."',
																	  justificacion='".$justificacion."',
																	  fecha_ingreso='".$fecha_ingreso."',
																	  idcargo='".$idnuevo_cargo."',
																	  idubicacion_funcional='".$idubicacion_nueva."',
																	  fecha_egreso='".$fecha_egreso."',
																	  causal='".$causal."',
																	  centro_costo='".$centro_costo."',
																	  fecha_reingreso='".$fecha_reingreso."',
																	  desde='".$desde."',
																	  hasta='".$hasta."',
																	  idtipo_nomina='".$idtipo_nomina."',
																	  nro_ficha='".$ficha."'
																		WHERE idmovimientos_personal = '".$idmovimiento."'")or die(mysql_error());

		$sql_consulta= mysql_query("select * from movimientos_personal where idmovimientos_personal = '".$idmovimiento."'");
		$bus_consulta= mysql_fetch_array($sql_consulta);
		
		
		$sql_actualizar = mysql_query("update trabajador set idcargo = '".$idnuevo_cargo."',
															idunidad_funcional = '".$idubicacion_nueva."',
															idcargo = '".$idnuevo_cargo."',
															centro_costo = '".$centro_costo."',
															nro_ficha = '".$ficha."'
									  						where idtrabajador = '".$idtrabajador."'")or die(mysql_error());
																	
	registra_transaccion("Se Modifico el movimiento de personal con el ID (".$idmovimiento.")",$login,$fh,$pc,'movimientos_personal');
}








if($ejecutar == "eliminarMovimiento"){
	$sql_consulta= mysql_query("select * from movimientos_personal where idmovimientos_personal = '".$idmovimiento."'")or die("error en la consulta ".mysql_error());
	$bus_consulta= mysql_fetch_array($sql_consulta);
	
	
	
	
	$sql_eliminar = mysql_query("delete from movimientos_personal where idmovimientos_personal = '".$idmovimiento."'");
	
	
	$sql_consultar_movimiento = mysql_query("select * from movimientos_personal where idtrabajador= '".$bus_consulta["idtrabajador"]."'
																				and idtipo_nomina != '0' order by idmovimientos_personal desc limit 0,1")or die("error al consultar el ultimo ".mysql_error());
	$bus_consultar_movimiento = mysql_fetch_array($sql_consultar_movimiento);
	$sql_actualizar = mysql_query("update trabajador set idtipo_nomina = '".$bus_consultar_movimiento["idtipo_nomina"]."'
									  							where idtrabajador = '".$bus_consultar_movimiento["idtrabajador"]."'")or die("error al actualizar: ".mysql_error());	
	registra_transaccion("Se Elimino el movimiento de personal con el ID (".$idmovimiento.")",$login,$fh,$pc,'movimientos_personal');
}
?>
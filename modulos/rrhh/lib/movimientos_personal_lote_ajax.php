<?
extract($_POST);
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
$conexion_db = Conectarse();

if($ejecutar == "buscarTrabajadores"){

	$where = " trabajador.idtrabajador != 0 ";
	$busqueda=1;
	if($cargo == "0"){
		$where .= "";
	}else{
		$where .= "AND trabajador.idcargo = '".$cargo."'";
	}
	if($prefijo_ficha == "0"){
		$where .= "";
	}else{
		$where .= "AND trabajador.nro_ficha LIKE '%".$prefijo_ficha."%'";
	}
	if($unidad_funcional == "0"){
		$where .= "";
	}else{
		$where .= "AND trabajador.idunidad_funcional = '".$unidad_funcional."'";
	}
	if($centro_costo == "0"){
		$where .= "";
	}else{
		$where .= "AND trabajador.centro_costo = '".$centro_costo."'";
	}
	if($tipo_nomina == "0"){
		$where .= "";
	}else{
		$where .= "AND trabajador.idtrabajador = relacion_tipo_nomina_trabajador.idtrabajador AND relacion_tipo_nomina_trabajador.idtipo_nomina = '".$tipo_nomina."'";
		$busqueda=2;
	}

	if ($busqueda == 2){
		
		$sql_consultar = mysql_query("select * from 
											trabajador, relacion_tipo_nomina_trabajador
											where
											".$where)or die(mysql_error());
	}else{
		
		$sql_consultar = mysql_query("select * from 
											trabajador
											where
											".$where)or die(mysql_error());
	}


											
	?>
	<form name="formulario_lista_trabajadores" id="formulario_lista_trabajadores" method="post" >
    <table align="center" class="Browse" cellpadding="0" cellspacing="0" width="99%">
        <thead>
            <tr>
                <!--<td class="Browse">&nbsp;</td>-->
                <td align="center" class="Browse"><input type="checkbox" name="todos" id="todos" style="cursor:pointer" onClick="seleccionarTodos(), buscarSeleccionados()">Seleccionar</td>
                <td align="center" class="Browse">Ficha</td>
                <td align="center" class="Browse">Cedula</td>
                <td align="center" class="Browse">Nombre</td>
                <td align="center" class="Browse">Apellido</td>
            </tr>
        </thead>
	<?
	while($bus_consultar = mysql_fetch_array($sql_consultar)){
		?>
		<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
            <td align="center" class='Browse'><input type="checkbox" id="marcar_<?=$bus_consultar["idtrabajador"]?>" name="marcar_<?=$bus_consultar["idtrabajador"]?>" value="<?=$bus_consultar["idtrabajador"]?>" style="cursor:pointer" onClick="buscarSeleccionados()"></td>
            <td align="center" class='Browse'><?=$bus_consultar["nro_ficha"]?></td>
            <td align="right" class='Browse'><?=$bus_consultar["cedula"]?></td>
            <td align="left" class='Browse'><?=$bus_consultar["nombres"]?></td>
            <td align="left" class='Browse'><?=$bus_consultar["apellidos"]?></td>
      </tr>
		<?
	}
	?>
	</table>
    </form>
	<?

}


/***********************************************************************************************************************/
/***********************************************************************************************************************/
/***********************************************************************************************************************/
/***********************************************************************************************************************/
/***********************************************************************************************************************/


if($ejecutar == "consultarFicha"){
	
	$sql_nomenclatura = mysql_query("select * from nomenclatura_fichas where descripcion ='".$nomenclatura_ficha."'")or die(mysql_error());
	$bus_nomenclatura = mysql_fetch_array($sql_nomenclatura);
	
	$numero_con_ceros = str_pad($bus_nomenclatura["numero"], 4, "0", STR_PAD_LEFT);
	echo $numero_con_ceros;
}









if($ejecutar == "ingresarMovimiento"){
	
	
	$idtrabajadores = explode(",", $trabajadores);
	
	
	
	
	
	
	
	
	
	foreach($idtrabajadores as $id){
		//echo "AQUI: ".$id;
		if($id != ''){
			$sql_consulta_trabajador = mysql_query("select * from trabajador where idtrabajador = '".$id."'");
			$bus_consulta_trabajador = mysql_fetch_array($sql_consulta_trabajador);
			
			if($fecha_ingreso == ""){
				$fecha_ingreso = $bus_consulta_trabajador["fecha_ingreso"];
			}
			if($idnuevo_cargo == 0){
				$idnuevo_cargo = $bus_consulta_trabajador["idcargo"];
			}
			if($idubicacion_nueva == 0){
				$idubicacion_nueva = $bus_consulta_trabajador["idunidad_funcional"];
			}
			if($centro_costo_nuevo == 0){
				$centro_costo_nuevo = $bus_consulta_trabajador["centro_costo"];
			}
			if($ficha == ""){
				$sql_consulta_ficha = mysql_query("select * from trabajador where idtrabajador = '".$id."' order by idtrabajador desc limit 0,1")or die(mysql_error());
				$bus_consulta_ficha = mysql_fetch_array($sql_consulta_ficha);
				$nro_ficha = $bus_consulta_ficha["nro_ficha"];
			}else{
				$sql_nomenclatura = mysql_query("select * from nomenclatura_fichas 
				where descripcion ='".$ficha."'")or die(mysql_error());
				$bus_nomenclatura = mysql_fetch_array($sql_nomenclatura);
				$numero_con_ceros = str_pad($bus_nomenclatura["numero"], 4, "0", STR_PAD_LEFT);
				$nro_ficha = $bus_nomenclatura["descripcion"].$numero_con_ceros;
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
																		'".$id."',
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
																		'".$centro_costo_nuevo."',
																		'".$fecha_reingreso."',
																		'".$desde."',
																		'".$hasta."',
																		'".$idtipo_nomina."',
																		'".$nro_ficha."')")or die(mysql_error());

		
		$sql_actualizar = mysql_query("update trabajador set idcargo = '".$idnuevo_cargo."',
															idunidad_funcional = '".$idubicacion_nueva."',
															idcargo = '".$idnuevo_cargo."',
															centro_costo = '".$centro_costo_nuevo."',
															nro_ficha = '".$nro_ficha."'
									  						where idtrabajador = '".$id."'")or die(mysql_error());
		$sql_nomenclatura = mysql_query("update nomenclatura_fichas set numero=numero+1 where descripcion = '".$ficha."'");
		}	
	}
	
	
	
	
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
																	  centro_costo='".$centro_costo_nuevo."',
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
															centro_costo = '".$centro_costo_nuevo."',
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
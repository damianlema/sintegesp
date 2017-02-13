<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
include("funciones_conceptos.php");
Conectarse();
extract($_POST);



if($ejecutar == "aplicar_ajuste"){
	//echo $arreglo;
	
	$sql_constante = mysql_query("select * from constantes_nomina where idconstantes_nomina = '".$idconstante."'");
	$bus_constante = mysql_fetch_array($sql_constante);
	
	


		$arreglo = explode(",", $arreglo);
		foreach($arreglo as $arr){
			if($arr != ""){
				
				//echo " trabajador ".$arr;
				$sql_consulta_trabajador = mysql_query("select * from trabajador where idtrabajador = '".$arr."'");
				$bus_consulta_trabajador = mysql_fetch_array($sql_consulta_trabajador);

				$sql_valor_actual = mysql_query("select * from relacion_concepto_trabajador
													where idtrabajador = '".$arr."'
														and idconcepto = '".$idconstante."'
														and tabla = 'constantes_nomina'
														and idtipo_nomina = '".$idtipo_nomina."'");
				$bus_valor_actual = mysql_fetch_array($sql_valor_actual);
				$valor_actual = $bus_valor_actual["valor"];
				$valor = $valor_actual;
				//echo " valor actual ".$valor_actual;
				//echo " desde ".$rango_desde;
				if($rango_desde == 'desde_todos'){ //INICIO RANGO DESDE TODOS LOS VALORES
					$aplicar_desde = 'si';
					
				}else if($rango_desde == 'desde_igual'){ // INICIO RANGO DESDE IGUAL A
					if($valor_actual == $valor_rango_desde){
						$aplicar_desde = 'si';
					}else{
						$aplicar_desde = 'no';
					}

				}else if($rango_desde == 'desde_mayor'){ // INICIO RANGO DESDE MAYOR A
					if($valor_actual > $valor_rango_desde){
						$aplicar_desde = 'si';
					}else{
						$aplicar_desde = 'no';
					}
				}else if($rango_desde == 'desde_mayor_igual'){ // INICIO RANGO DESDE MAYOR IGUAL A
					if($valor_actual >= $valor_rango_desde){
						$aplicar_desde = 'si';
					}else{
						$aplicar_desde = 'no';
					}
				}
				//echo " hasta ".$rango_hasta;
				if($rango_hasta == 'hasta_todos'){ //INICIO RANGO HASTA TODOS LOS VALORES
					$aplicar_hasta = 'si';
					
				}else if($rango_hasta == 'hasta_igual'){ // INICIO RANGO DESDE IGUAL A
					if($valor_actual == $valor_rango_hasta){
						$aplicar_hasta = 'si';
					}else{
						$aplicar_hasta = 'no';
					}

				}else if($rango_hasta == 'hasta_menor'){ // INICIO RANGO DESDE MAYOR A
					if($valor_actual < $valor_rango_hasta){
						$aplicar_hasta = 'si';
					}else{
						$aplicar_hasta = 'no';
					}
				}else if($rango_hasta == 'hasta_menor_igual'){ // INICIO RANGO DESDE MAYOR IGUAL A
					if($valor_actual <= $valor_rango_hasta){
						$aplicar_hasta = 'si';
					}else{
						$aplicar_hasta = 'no';
					}
				}

				//echo " aplica desde ".$aplicar_desde." aplicar hasta ".$aplicar_hasta;
				//ECHO " forma ".$forma_fijar_valor;
				if($aplicar_desde == 'si' && $aplicar_hasta == 'si'){
					if($forma_fijar_valor == 'porcentual'){ // APLICAR AJUSTE PORCENTUAL
						$monto_valor_ajuste = $valor_actual * $valor_ajuste / 100;
						$valor = $valor_actual + $monto_valor_ajuste;
					}else if($forma_fijar_valor == 'valor_fijo') { // APLICAR AJUSTE COLOCANDO UN VALOR FIJO
						$monto_valor_ajuste = $valor_ajuste;
						$valor = $valor_ajuste;
					}else if($forma_fijar_valor == 'sumar_valor') { // APLICAR AJUSTE SUMANDO UN VALOR AL ACTUAL
						$monto_valor_ajuste = $valor_ajuste;
						$valor = $valor_actual + $valor_ajuste;
					}
				

					//echo " monto valor ajuste ".$monto_valor_ajuste;
					//echo " nuevo valor ".$valor." br /";


					$sql_actualizar = mysql_query("UPDATE relacion_concepto_trabajador 
									  			SET valor = '".$valor."' 
													WHERE 
												idconcepto ='".$idconstante."' 
												and tabla = 'constantes_nomina'
												and idtipo_nomina = '".$idtipo_nomina."'
												and idtrabajador = '".$arr."'")or die(mysql_error());
													 
					registra_transaccion('Se ajusto la Constante ('.$bus_constante["descripcion"].') al trabajador ('.$bus_consulta_trabajador["apellidos"].' '.$bus_consulta_trabajador["nombres"].')',$login,$fh,$pc,'nomina',$conexion_db);
									
					$sql_consulta_trabajador = mysql_query("select * from trabajador where idtrabajador = '".$arr."'");
					$bus_consulta_trabajador = mysql_fetch_array($sql_consulta_trabajador);


					$justificacion = "AJUSTE DE CONSTANTE (".$bus_constante["descripcion"].") AL VALOR: ".$valor;


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
																		'".$fecha_ajuste."',
																		'1000000',
																		'".$justificacion."',
																		'".$bus_consulta_trabajador["fecha_ingreso"]."',
																		'',
																		'".$bus_consulta_trabajador["idcargo"]."',
																		'".$bus_consulta_trabajador["idunidad_funcional"]."',
																		'".$login."',
																		'a',
																		'".$fh."',
																		'".$bus_consulta_trabajador["centro_costo"]."',
																		'".$idtipo_nomina."')")or die("AQUI ".mysql_error());
				}
			}
		}
	//} 
}








if($ejecutar == "consultarListaTrabajadores"){
	
	if($select_buscar == "cedula"){
		$sql_consulta = mysql_query("select tr.cedula,
										tr.nombres,
										tr.apellidos,
										tr.idtrabajador
										from 
											trabajador tr, relacion_tipo_nomina_trabajador rtn
										where
											tr.cedula like '%".$texto_buscar."%'
											and tr.status = 'a'
											and tr.idtrabajador = rtn.idtrabajador
											and rtn.idtipo_nomina = '".$idtipo_nomina."'
										order by tr.apellidos, tr.nombres")or die(mysql_error());
	}else if($select_buscar == "apellidos"){
		$sql_consulta = mysql_query("select tr.cedula,
										tr.nombres,
										tr.apellidos,
										tr.idtrabajador
											from 
										trabajador tr , relacion_tipo_nomina_trabajador rtn
											where
										tr.apellidos like '%".$texto_buscar."%'
										and tr.status = 'a'
										and tr.idtrabajador = rtn.idtrabajador
										and rtn.idtipo_nomina = '".$idtipo_nomina."'
										order by tr.apellidos, tr.nombres")or die(mysql_error());
	
	}else{
		$sql_consulta = mysql_query("select tr.cedula,
										tr.nombres,
										tr.apellidos,
										tr.idtrabajador,
										rct.valor
										from 
											trabajador tr , relacion_tipo_nomina_trabajador rtn, relacion_concepto_trabajador rct
										where 
											tr.status = 'a'
											and tr.idtrabajador = rtn.idtrabajador
											and rtn.idtipo_nomina = '".$idtipo_nomina."'
											and tr.idtrabajador = rct.idtrabajador
											and rct.tabla = 'constantes_nomina'
											and rct.idconcepto = '".$idconstante."'
										order by tr.apellidos, tr.nombres")or die(mysql_error());
	}
	
	?>
	
    <table>
        <tr>
       		<td><input type="text" name="buscar_trabajador" id="buscar_trabajador"></td>
            <td>
            <select name="lista_tipo_busqueda" id="lista_tipo_busqueda">
            	<option value="cedula">Cedula</option>
                <option value="apellidos">Apellidos</option>
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
                            <td width="6%" align="center" class="Browse"><input type="checkbox" name="sel_todos" id="sel_todos" onclick="seleccionarTodo()">&nbsp;Sel.</td>
                           	<td width="2%" align="center" class="Browse">Nro</td>
                            <td width="8%" align="center" class="Browse">Cedula</td>
                            <td width="54%" align="center" class="Browse">Nombre</td>
                            <td width="15%" align="center" class="Browse">Valor Actual</td>
                            <td width="15%" align="center" class="Browse">Valor Ajuste</td>
                            <td width="15%" align="center" class="Browse">Nuevo Valor</td>
                        </tr>
                    </thead>
                    
                    <?php
			$i=1;		
                        while($bus_consulta = mysql_fetch_array($sql_consulta)){
							/*$sql_tipo_nomina = mysql_query("select * 
														   			from 
																	relacion_tipo_nomina_trabajador 
																	where 
																	idtrabajador = '".$bus_consulta["idtrabajador"]."'
																	and idtipo_nomina = '".$idtipo_nomina."'
																");
							$num_tipo_nomina = mysql_num_rows($sql_tipo_nomina);
							if($num_tipo_nomina == 0){*/
                        ?>
                        <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer">
                        	<td class='Browse' align="center">
                            <input type="checkbox" name="seleccionar[]" id="seleccionar<?=$bus_consulta["idtrabajador"]?>" value="<?=$bus_consulta["idtrabajador"]?>">
                            </td>
							<td align='center' class='Browse'><?=$i?></td>                           
                            <td align='right' class='Browse'><?=number_format($bus_consulta["cedula"],0,'','.')?></td>
                            <td align='left' class='Browse'><?=$bus_consulta["apellidos"].", ".$bus_consulta["nombres"]?></td>
                            <td align='right' class='Browse'><?=number_format($bus_consulta["valor"],2,',','.')?></td>
                            <td align='right' class='Browse'>
                            	<input type="text" 
			                		name="valor_ajuste<?=$bus_consulta["idtrabajador"]?>" 
			                        id="valor_ajuste<?=$bus_consulta["idtrabajador"]?>" 
			                        style="text-align:right" 
			                        size="8" 
			                        value="0" 
			                        disabled = "true"
			                        >

                            </td>
                            <td align='right' class='Browse'>
                            	<input type="text" 
			                		name="nuevo_valor<?=$bus_consulta["idtrabajador"]?>" 
			                        id="nuevo_valor<?=$bus_consulta["idtrabajador"]?>" 
			                        style="text-align:right" 
			                        size="8" 
			                        onKeyUp="guardarValor('<?=$bus_consulta["idtrabajador"]?>', '<?=$idconstante?>', this.value)" 
			                        value="0" 
			                        onClick="this.select()">

                            </td>  

                        </tr>
                        <?
				$i++;
							//}
                        }
                    ?>
                </table>
                </form>
	<?
	
	
}


if($ejecutar == "consultarConstantes"){

	$sql_constantes = mysql_query("select idconstantes_nomina, descripcion  
							from 
								constantes_nomina, relacion_concepto_trabajador rct
							where 
								constantes_nomina.idconstantes_nomina = rct.idconcepto
								and rct.idtipo_nomina = '".$idtipo_nomina."' 
								and rct.tabla = 'constantes_nomina'
							group by idconstantes_nomina");

	?>
    <select name="idconstante" id="idconstante">
	    <option value="0">.:: Seleccione ::.</option>
	    <?
	    while($bus_constantes = mysql_fetch_array($sql_constantes)){
			?>
				<option value="<?=$bus_constantes["idconstantes_nomina"]?>"
	              ><?=$bus_constantes["descripcion"]?></option>
			<?	
		}
		?>
    </select>
<?
}



if($ejecutar == "guardarValor"){
	
	$sql_actualizar = mysql_query("UPDATE relacion_concepto_trabajador 
								  			SET valor = '".$valor."' 
												WHERE 
											idconcepto ='".$idconstante."' 
											and tabla = 'constantes_nomina'
											and idtipo_nomina = '".$idtipo_nomina."'
											and idtrabajador = '".$idtrabajador."'")or die(mysql_error());

	$sql_constante = mysql_query("select * from constantes_nomina where idconstantes_nomina = '".$idconstante."'");
	$bus_constante = mysql_fetch_array($sql_constante);
	
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
											  idconstante,
											  valor)VALUES(
												'".$idtrabajador."',
												'".$fecha_ajuste."',
												'1000000',
												'MODIFICACION DE LA CONSTANTE ASOCIADA (".$bus_constante["descripcion"]." AL VALOR : ".$valor.")',
												'".$bus_consulta_trabajador["fecha_ingreso"]."',
												'',
												'".$bus_consulta_trabajador["idcargo"]."',
												'".$bus_consulta_trabajador["idunidad_funcional"]."',
												'".$login."',
												'a',
												'".$fh."',
												'".$bus_consulta_trabajador["centro_costo"]."',
												'".$idconstante."',
												'".$valor."')")or die(mysql_error());

	registra_transaccion('Se ajusto la Constante ('.$bus_constante["descripcion"].') al trabajador ('.$bus_consulta_trabajador["apellidos"].' '.$bus_consulta_trabajador["nombres"].')',$login,$fh,$pc,'nomina',$conexion_db);

}

?>

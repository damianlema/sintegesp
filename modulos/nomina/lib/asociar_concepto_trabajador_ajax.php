<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
include("funciones_conceptos.php");
Conectarse();
extract($_POST);


if($ejecutar == "sosciarConceptoConstante"){
	
	
	if($tipo_asociacion == "individual"){
	
	$sql_consulta = mysql_query("select * from relacion_concepto_trabajador where idconcepto = '".$idconcepto_constante."' and idtrabajador = '".$idtrabajador."' and tabla='".$tabla."' and idtipo_nomina = '".$tipo_nomina."'");
	$num_consulta = mysql_num_rows($sql_consulta);
	if($num_consulta == 0){
		
		$sql_tipo_nomina = mysql_query("select * from 
									   				relacion_tipo_nomina_trabajador 
														where 
													idtrabajador = '".$idtrabajador."' and idtipo_nomina = '".$tipo_nomina."'");
		$num_tipo_nomina = mysql_num_rows($sql_tipo_nomina);
		if($num_tipo_nomina > 0){
		$sql_insertar = mysql_query("insert into relacion_concepto_trabajador(tabla, 
																		  idconcepto,
																		  idtrabajador,
																		  valor,
																		  idtipo_nomina,
																		  fecha_ejecutar_desde,
																		  fecha_ejecutar_hasta)VALUES('".$tabla."',
																		  				'".$idconcepto_constante."',
																						'".$idtrabajador."',
																						'".$valor_fijo."',
																						'".$tipo_nomina."',
																						'".$fecha_ejecutar_desde."',
																						'".$fecha_ejecutar_hasta."')")or die(mysql_error());
			registra_transaccion('Se le asocio el concepto ('.$idconcepto_constante.') al trabajador ('.$idtrabajador.')',$login,$fh,$pc,'nomina',$conexion_db);
																						
			$sql_relacion = mysql_query("select * from relacion_concepto_trabajador where idrelacion_concepto_trabajador = '".mysql_insert_id()."'");
			$bus_relacion = mysql_fetch_array($sql_relacion);
			
			$sql_constante = mysql_query("select * from constantes_nomina where idconstantes_nomina = '".$bus_relacion["idconcepto"]."'");
			$bus_constante = mysql_fetch_array($sql_constante);
			
			$sql_consulta_trabajador = mysql_query("select * from trabajador where idtrabajador = '".$bus_relacion["idtrabajador"]."'");
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
														'".$bus_consulta_trabajador["idtrabajador"]."',
														'".date("Y-m-d")."',
														'1000000',
														'SE INGRESO LA CONSTANTE DE VALOR FIJO (".$bus_constante["descripcion"]." AL VALOR : ".$valor_fijo.")',
														'".$bus_consulta_trabajador["fecha_ingreso"]."',
														'".$causal."',
														'".$bus_consulta_trabajador["idcargo"]."',
														'".$bus_consulta_trabajador["idunidad_funcional"]."',
														'".$login."',
														'a',
														'".$fh."',
														'".$bus_consulta_trabajador["centro_costo"]."',
														'".$bus_relacion["idconcepto"]."',
														'".$valor_fijo."')")or die(mysql_error());	
														
							
																						
		}else{
			echo "no_nomina";	
		}
	}else{
		echo "existe";	
	}
	}else{
		$insertados= 0;
		$sql_trabajadores = mysql_query("select t.idtrabajador from trabajador t,
             										 relacion_tipo_nomina_trabajador rt
														where
													rt.idtipo_nomina = '".$tipo_nomina."'
													and rt.idtrabajador = t.idtrabajador");
		while($bus_trabajadores = mysql_fetch_array($sql_trabajadores)){
			$sql_consulta = mysql_query("select * 
													from 
												relacion_concepto_trabajador 
													where 
												idtrabajador = '".$bus_trabajadores["idtrabajador"]."'
												and idconcepto = '".$idconcepto_constante."'
												and tabla ='".$tabla."'
												and idtipo_nomina = '".$tipo_nomina."'");
			$num_consulta = mysql_num_rows($sql_consulta);
			if($num_consulta ==0){
				$sql_insertar = mysql_query("insert into relacion_concepto_trabajador(tabla, 
																		  idconcepto,
																		  idtrabajador,
																		  valor,
																		  idtipo_nomina,
																		  fecha_ejecutar_desde,
																		  fecha_ejecutar_hasta)VALUES('".$tabla."',
																		  				'".$idconcepto_constante."',
																						'".$bus_trabajadores["idtrabajador"]."',
																						'".$valor_fijo."',
																						'".$tipo_nomina."',
																						'".$fecha_ejecutar_desde."',
																						'".$fecha_ejecutar_hasta."')");
																						
				registra_transaccion('Se le asocio el concepto ('.$idconcepto_constante.') al trabajador ('.$bus_trabajador["idtrabajador"].')',$login,$fh,$pc,'nomina',$conexion_db);
				$sql_relacion = mysql_query("select * from relacion_concepto_trabajador where idrelacion_concepto_trabajador = '".mysql_insert_id()."'");
			$bus_relacion = mysql_fetch_array($sql_relacion);
			
			$sql_constante = mysql_query("select * from constantes_nomina where idconstantes_nomina = '".$bus_relacion["idconcepto"]."'");
			$bus_constante = mysql_fetch_array($sql_constante);
			
			$sql_consulta_trabajador = mysql_query("select * from trabajador where idtrabajador = '".$bus_relacion["idtrabajador"]."'");
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
														'".$bus_consulta_trabajador["idtrabajador"]."',
														'".date("Y-m-d")."',
														'1000000',
														'SE ASOCIO LA CONSTANTE DE VALOR FIJO (".$bus_constante["descripcion"]." AL VALOR : ".$valor_fijo.")',
														'".$bus_consulta_trabajador["fecha_ingreso"]."',
														'".$causal."',
														'".$bus_consulta_trabajador["idcargo"]."',
														'".$bus_consulta_trabajador["idunidad_funcional"]."',
														'".$login."',
														'a',
														'".$fh."',
														'".$bus_consulta_trabajador["centro_costo"]."',
														'".$idconstante."',
														'".$valor_fijo."')")or die(mysql_error());			
				$insertados++;
			}
		}
		
		echo $insertados;
	}
}





if($ejecutar == "consultarAsociados"){
	$sql_consultar = mysql_query("select * from relacion_concepto_trabajador where idtrabajador = '".$idtrabajador."' and idtipo_nomina = '".$tipo_nomina."'")or die(mysql_error());
?>
	<table class="Main" cellpadding="0" cellspacing="0" width="80%" align="center">
        <tr>
            <td>
                <form name="grilla" action="lista_cargos.php" method="POST">
               <br />
<br />

                <table>
                	<tr>
                        <td style="background-color:#9CF" width="150px" align="center"><strong>Asignaciones</strong></td>
                        <td style="background-color:#FFC" width="150px" align="center"><strong>Deducciones</strong></td>
                        <td bgcolor="#e7dfce" width="150px" align="center"><strong>Neutros</strong></td>
                	</tr>
                </table>
                <table class="Browse" cellpadding="0" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <!--<td class="Browse">&nbsp;</td>-->
                            <td align="center" class="Browse">Tipo</td>
                            <td width="12%" align="center" class="Browse">C&oacute;digo</td>
                            <td align="center" class="Browse">Descripci&oacute;n</td>
                            <td align="center" class="Browse">Valor Fijo</td>
                            <td align="center" class="Browse">Eliminar</td>
                           <!-- <td align="center" class="Browse">Estimado</td>-->
                        </tr>
                    </thead>
                    
                    <?php
					$i=0;
                        while($bus= mysql_fetch_array($sql_consultar)){
                        	$i +=1;
                            if($bus["tabla"] == "conceptos_nomina"){
								$campo = "tipo_concepto";	
							}else{
								$campo = "afecta";	
							}
							//echo $i."-".$campo;

							$sql_consulta_concepto = mysql_query("select * from ".$bus["tabla"]." where id".$bus["tabla"]." = ".$bus["idconcepto"]." order by '".$campo."' desc") or die(mysql_error());
							$bus_consulta_concepto = mysql_fetch_array($sql_consulta_concepto);
							
							
							
							
							$sql_tipo_afectacion = mysql_query("select * from tipo_conceptos_nomina where idconceptos_nomina = '".$bus_consulta_concepto[$campo]."'")or die(mysql_error());
							$bus_tipo_afectacion = mysql_fetch_array($sql_tipo_afectacion);
							
							if($bus_tipo_afectacion["afecta"] == "Asignacion"){
								$color = "#9CF";
							}else if($bus_tipo_afectacion["afecta"] == "Deduccion"){
								$color = "#FFC";
							}else{
								$color = "#e7dfce";
							}
							
						
						?>
                        <tr  onMouseOver="setRowColor(this, 0, 'over', '<?=$color?>', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '<?=$color?>', '#EAFFEA', '#FFFFAA')" style="cursor:pointer; background-color:<?=$color?>">
                            <td align='center' class='Browse' width='13%'>
								<?
                                $fechad='';
                                $fechah='';
                                $rango_periodo_ejecutar='';
                                if($bus["tabla"] == "conceptos_nomina"){
                                    echo "Concepto";
                                    if ($bus["fecha_ejecutar_desde"]!='0000-00-00'){
                                        list($a, $m, $d)=SPLIT( '[/.-]', $bus["fecha_ejecutar_desde"]); $fechad=$d."/".$m."/".$a;
                                        list($a, $m, $d)=SPLIT( '[/.-]', $bus["fecha_ejecutar_hasta"]); $fechah=$d."/".$m."/".$a;
                                        $rango_periodo_ejecutar = " - ".$fechad." - ".$fechah;
                                    }
                                }else{
                                    echo "Constante";	
                                }
                                ?>
                            </td>
                            
                            <td align='left' class='Browse'><?=$bus_consulta_concepto["codigo"]?></td>
                          	<td align='left' class='Browse' width='52%'><?=$bus_consulta_concepto["descripcion"].$rango_periodo_ejecutar?></td>
                          	<td align='center' class='Browse' width='16%'>
							&nbsp;
							 <?
                            if($bus["valor"] != 0){
								?>
								<input type="text" name="<?=$bus["idrelacion_concepto_trabajador"]?>valor" id="<?=$bus["idrelacion_concepto_trabajador"]?>valor" value="<?=$bus["valor"]?>" onblur="modificarValor('<?=$bus["idrelacion_concepto_trabajador"]?>', this.value)" size="10" style="text-align:right">
                                
								<?	
							}
							?>
                            </td>
                            <td align='center' class='Browse'>
                            	<img src='imagenes/delete.png' onclick="eliminarAsociacion('<?=$bus["idrelacion_concepto_trabajador"]?>')">
                          	</td>
                            <!-- <td align='right' class='Browse'>&nbsp;
                            	<?/*
								if($bus["valor"] != 0) { 
									if ($bus_tipo_afectacion["afecta"] == "Asignacion"){
										echo number_format($bus["valor"],2,",",".");
										$suma += $bus["valor"];
									} if ($bus_tipo_afectacion["afecta"] == "Deduccion"){
										echo number_format($bus["valor"],2,",",".");
										$suma -= $bus["valor"];
									}
								}else{
									$resultado = call_concepto($bus['idconcepto'], $idtrabajador, 'principal');
									echo number_format($resultado,2,",",".");
									$suma += $resultado;	
								}
								
								*/
								?>
                          	</td>-->
                            
                        </tr>
                        <?
                        }
                    ?>
                   <!-- <tr>
                    <td colspan="5" align="right"><strong>El Total Aproximado es:</strong></td>
                    <td align="right"><strong><?=number_format($suma,2,",",".")?></strong></td>
                    </tr> -->
                </table>
                </form>
                
            </td>
        </tr>
    </table>
<?	
}







if($ejecutar == "eliminarAsociacion"){
	//echo "delete from relacion_concepto_trabajador where idrelacion_concepto_trabajador = '".$idreacion."'";
	$sql_relacion = mysql_query("select * from relacion_concepto_trabajador where idrelacion_concepto_trabajador = '".$idreacion."'");
	
	
			$bus_relacion = mysql_fetch_array($sql_relacion);
			$sql_constante = mysql_query("select * from constantes_nomina where idconstantes_nomina = '".$bus_relacion["idconcepto"]."'");
			$bus_constante = mysql_fetch_array($sql_constante);
			$sql_consulta_trabajador = mysql_query("select * from trabajador where idtrabajador = '".$bus_relacion["idtrabajador"]."'");
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
														'".$bus_consulta_trabajador["idtrabajador"]."',
														'".date("Y-m-d")."',
														'1000000',
														'SE ELIMINA LA CONSTANTE ASOCIADA (".$bus_constante["descripcion"].")',
														'".$bus_consulta_trabajador["fecha_ingreso"]."',
														'".$causal."',
														'".$bus_consulta_trabajador["idcargo"]."',
														'".$bus_consulta_trabajador["idunidad_funcional"]."',
														'".$login."',
														'a',
														'".$fh."',
														'".$bus_consulta_trabajador["centro_costo"]."',
														'".$bus_relacion["idconcepto"]."',
														'".$bus_relacion["valor"]."')")or die(mysql_error());	
		$sql_eliminar = mysql_query("delete from relacion_concepto_trabajador where idrelacion_concepto_trabajador = '".$idreacion."'")or die(mysql_error());
		registra_transaccion('Se elimino la asociacion de concepto ('.$bus_relacion["idconcepto"].') al trabajador ('.$bus_relacion["idtrabajador"].')',$login,$fh,$pc,'nomina',$conexion_db);			
}






function call_concepto($idconcepto, $idtrabajador, $destino){
	$sql_relacion_formula= mysql_query("SELECT * FROM 
												relacion_formula_conceptos_nomina 
													WHERE 
												idconcepto_nomina ='".$idconcepto."'
												and destino = '".$destino."'");
	while($bus_relacion_formula = mysql_fetch_array($sql_relacion_formula)){
		$partes = explode("_", $bus_relacion_formula["valor_oculto"]);	
		//echo $partes[0];
		switch($partes[0]){
			case "SI":// SI ES UN SIMBOLO ENTRA ACA
				$formula .= $partes[1];
			break;
			case "CN":// SI ES UNA CONSTANTE ENTRA ACA
				//echo "aqui";
				$sql_constantes= mysql_query("SELECT * FROM constantes_nomina 
																					WHERE idconstantes_nomina='".$partes[1]."'");
											$bus_constantes = mysql_fetch_array($sql_constantes);
											if($bus_constantes["valor"] == 0){
												$sql_consulta_relacion= mysql_query("select * from relacion_concepto_trabajador
																							where tabla = 'constantes_nomina'
																							and idconcepto = '".$partes[1]."'
																							and idtrabajador = '".$idtrabajador."'");
												$bus_consulta_relacion = mysql_fetch_array($sql_consulta_relacion);
												$monto = $bus_consulta_relacion["valor"];
											}else{
												$monto = $bus_constantes["valor"];
											}
											$formula .= $monto;
			break;
			case "CO":// SI ES UN CONCEPTO ENTRA ACA
				//echo "aqui";
				$formula .= call_concepto($partes[1], $idtrabajador, 'principal');
			break;
			case "TA":// SI ES UNA TABLA CONSTANTE ENTRA ACA
				$sql_consultar_tabla= mysql_query("SELECT * FROM 
														tabla_constantes 
														WHERE 
														idtabla_constantes = '".$partes[1]."'");
				$bus_consultar_tabla = mysql_fetch_array($sql_consultar_tabla);
				$sql_consulta_siguiente_relacion = mysql_query("SELECT * FROM
																		relacion_formula_conceptos_nomina 
																		WHERE idconcepto_nomina = '".$bus_relacion_formula["idconcepto_nomina"]."' 
																		and orden = '".($bus_relacion_formula["orden"]+1)."'");
				$bus_consulta_siguiente_relacion = mysql_fetch_array($sql_consulta_siguiente_relacion);
				$partes_fu = explode("_", $bus_consulta_siguiente_relacion["valor_oculto"]);
				if($partes_fu[0] == "SI"){
						$sql_rango = mysql_query("SELECT * FROM 
													rango_tabla_constantes 
													WHERE 
														idtabla_constantes = '".$bus_consultar_tabla["idtabla_constantes"]."'
														and desde >= '".$numero."'
																	and hasta <= '".$numero."'
																	order by idrango_tabla_constantes asc limit 0,1");
						$bus_rango = mysql_fetch_array($sql_rango);
						$formula .= $bus_rango["valor"];
				}else if($partes_fu[0] == "FU"){
						if(ereg("numerode", $partes_fu[1])){
							$tabla = explode("(", $partes_fu[1]);						
							$tabla = substr($tabla[1], 0, strlen($tabla[1])-1);
							$final = explode("-",$tabla);
							$numero = numerode($final[0], $final[1], $final[2], $idtrabajador);	
						}else if(ereg("anioempresa", $partes_fu[1])){
							$numero = $partes_fu[1]($idtrabajador);
						}else if(ereg("tiempoentrefechas", $partes_fu[1])){
							$fechas = explode("(", $partes_fu[1]);	
							$fechas = substr($fechas[1], 0, strlen($fechas[1])-1);
							$fechas = explode(",", $fechas);
							$numero = tiempoentrefechas($fechas[0], $fechas[1]);
						}else if(ereg("diasferiadosentre", $partes_fu[1])){
							$fechas = explode("(", $partes_fu[1]);	
							$fechas = substr($fechas[1], 0, strlen($fechas[1])-1);
							$fechas = explode(",", $fechas);
							$numero = diasferiadosentre($fechas[0], $fechas[1]);
						}else if(ereg("mesactual", $partes_fu[1])){
							$numero = date("m");
						}else if(ereg("diasmes", $partes_fu[1])){
							$numero = diasmes(date("Y") , date("m"));
						}
						
						$sql_rango = mysql_query("SELECT * FROM 
													rango_tabla_constantes 
													WHERE 
														idtabla_constantes = '".$bus_consultar_tabla["idtabla_constantes"]."'
														and desde >= '".$numero."'
																	and hasta <= '".$numero."'
																	order by idrango_tabla_constantes asc limit 0,1");
						$bus_rango = mysql_fetch_array($sql_rango);
						$formula .= $bus_rango["valor"];
				}
			break;
			case "FU": // SI ES UNA FUNCION ENTRA ACA
				//echo "aqui";
				$sql_consultar_anterior = mysql_query("SELECT * FROM
																relacion_formula_conceptos_nomina 
																WHERE idconcepto_nomina = '".$bus_relacion_formula["idconcepto_nomina"]."' 
																and orden = '".($bus_relacion_formula["orden"]-1)."'");
				
				$bus_consultar_anterior = mysql_fetch_array($sql_consultar_anterior);
				$partes_anterior = explode("_", $bus_consultar_anterior["valor_oculto"]);
					if($partes_anterior[0] != "TA"){
						if(ereg("numerode", $partes[1])){
							$tabla = explode("(", $partes[1]);						
							$tabla = substr($tabla[1], 0, strlen($tabla[1])-1);
							$final = explode("-",$tabla);
							$formula .= numerode($final[0], $final[1], $final[2], $idtrabajador);	
							//echo $numero." AQUI";
						}else if(ereg("anioempresa", $partes[1])){
							$formula .= $partes[1]($idtrabajador);
						}else if(ereg("tiempoentrefechas", $partes[1])){
							$fechas = explode("(", $partes[1]);	
							$fechas = substr($fechas[1], 0, strlen($fechas[1])-1);
							$fechas = explode(",", $fechas);
							$formula .= tiempoentrefechas($fechas[0], $fechas[1]);
						}else if(ereg("diasferiadosentre", $partes[1])){
							$fechas = explode("(", $partes[1]);	
							$fechas = substr($fechas[1], 0, strlen($fechas[1])-1);
							$fechas = explode(",", $fechas);
							$formula .= diasferiadosentre($fechas[0], $fechas[1]);
						}else if(ereg("mesactual", $partes[1])){
							$formula .= date("m");
						}else if(ereg("diasmes", $partes[1])){
							$formula .= diasmes(date("Y") , date("m"));
						}
						//echo $formula;
					}
			break;
		}
	}
	return $formula;
}







if($ejecutar == "probarFormula"){
	$sql_consulta_asociado = mysql_query("SELECT * FROM 
										 			relacion_concepto_trabajador 
														WHERE
													idtrabajador = '".$idtrabajador."'");
	while($bus_consulta_asociado = mysql_fetch_array($sql_consulta_asociado)){
		$formula = "";
		$sql_consulta_tabla = mysql_query("SELECT * FROM ".$bus_consulta_asociado["tabla"]." 
													where id".$bus_consulta_asociado["tabla"]." ='".$bus_consulta_asociado["idconcepto"]."'");
		$bus_consulta_tabla = mysql_fetch_array($sql_consulta_tabla);
			if($bus_consulta_asociado["tabla"] == "conceptos_nomina"){ // SI LA TABLA ES CONCEPTOS
					$sql_relacion_formula= mysql_query("SELECT * FROM 
												   			relacion_formula_conceptos_nomina 
																WHERE 
															idconcepto_nomina ='".$bus_consulta_tabla["idconceptos_nomina"]."'
															and destino = 'condicion'");
				$num_relacion_formula= mysql_num_rows($sql_relacion_formula);
				
					if($num_relacion_formula > 0){
						// SI TIENE UN CONDICIONAL
						$result .= call_concepto($bus_consulta_tabla["idconceptos_nomina"], $idtrabajador, 'condicion');
						eval("\$res = $result;");
						if($res == "1"){
							$formula .= call_concepto($bus_consulta_tabla["idconceptos_nomina"], $idtrabajador, 'entonces');
						}else{
							$formula .= call_concepto($bus_consulta_tabla["idconceptos_nomina"], $idtrabajador, 'principal');
						}
					}else{
						$sql_relacion_formula= mysql_query("SELECT * FROM 
																	relacion_formula_conceptos_nomina 
																		WHERE 
																	idconcepto_nomina ='".$bus_consulta_tabla["idconceptos_nomina"]."'
																	and destino= 'principal'");
						while($bus_relacion_formula = mysql_fetch_array($sql_relacion_formula)){
							$partes = explode("_", $bus_relacion_formula["valor_oculto"]);	
							switch($partes[0]){
								case "SI":// SI ES UN SIMBOLO ENTRA ACA
									//echo "AA";
									$formula .= $partes[1];
									//echo $formula;
								break;
								case "CN":// SI ES UNA CONSTANTE ENTRA ACA
									$sql_constantes= mysql_query("SELECT * FROM constantes_nomina 
																					WHERE idconstantes_nomina='".$partes[1]."'");
											$bus_constantes = mysql_fetch_array($sql_constantes);
											if($bus_constantes["valor"] == 0){
												$sql_consulta_relacion= mysql_query("select * from relacion_concepto_trabajador
																							where tabla = 'constantes_nomina'
																							and idconcepto = '".$partes[1]."'
																							and idtrabajador = '".$idtrabajador."'");
												$bus_consulta_relacion = mysql_fetch_array($sql_consulta_relacion);
												$monto = $bus_consulta_relacion["valor"];
											}else{
												$monto = $bus_constantes["valor"];
											}
											//echo $formula;
											$formula .= $monto;
								break;
								case "CO":// SI ES UN CONCEPTO ENTRA ACA
										$formula .= call_concepto($partes[1], $idtrabajador, 'principal');		
										//echo $formula;
								break;
								case "TA":// SI ES UNA TABLA CONSTANTE ENTRA ACA
									$sql_consultar_tabla= mysql_query("SELECT * FROM 
																			tabla_constantes 
																			WHERE 
																			idtabla_constantes = '".$partes[1]."'");
									$bus_consultar_tabla = mysql_fetch_array($sql_consultar_tabla);
									$sql_consulta_siguiente_relacion = mysql_query("SELECT * FROM
																				relacion_formula_conceptos_nomina 
																				WHERE idconcepto_nomina = '".$bus_relacion_formula["idconcepto_nomina"]."' 
																				and orden = '".($bus_relacion_formula["orden"]+1)."'");
									$bus_consulta_siguiente_relacion = mysql_fetch_array($sql_consulta_siguiente_relacion);
									$partes_fu = explode("_", $bus_consulta_siguiente_relacion["valor_oculto"]);
									if($partes_fu[0] == "SI"){
											$sql_rango = mysql_query("SELECT * FROM 
																		rango_tabla_constantes 
																		WHERE 
																			idtabla_constantes = '".$bus_consultar_tabla["idtabla_constantes"]."'
																			order by idrango_tabla_constantes asc limit ".($partes_fu[1]-1).",1");
											$bus_rango = mysql_fetch_array($sql_rango);
											$formula .= $bus_rango["valor"];
									}else if($partes_fu[0] == "FU"){
											if(ereg("numerode", $partes_fu[1])){
												$tabla = explode("(", $partes_fu[1]);						
												$tabla = substr($tabla[1], 0, strlen($tabla[1])-1);
												$final = explode("-",$tabla);
												$numero = numerode($final[0], $final[1], $final[2], $idtrabajador);	
											}else if(ereg("anioempresa", $partes_fu[1])){
												$numero = $partes_fu[1]($idtrabajador);
											}else if(ereg("tiempoentrefechas", $partes_fu[1])){
												$fechas = explode("(", $partes_fu[1]);	
												$fechas = substr($fechas[1], 0, strlen($fechas[1])-1);
												$fechas = explode(",", $fechas);
												$numero = tiempoentrefechas($fechas[0], $fechas[1]);
											}else if(ereg("diasferiadosentre", $partes_fu[1])){
												$fechas = explode("(", $partes_fu[1]);	
												$fechas = substr($fechas[1], 0, strlen($fechas[1])-1);
												$fechas = explode(",", $fechas);
												$numero = diasferiadosentre($fechas[0], $fechas[1]);
											}else if(ereg("mesactual", $partes_fu[1])){
												$numero = date("m");
											}else if(ereg("diasmes", $partes_fu[1])){
												$numero = diasmes(date("Y") , date("m"));
											}
											
											$sql_rango = mysql_query("SELECT * FROM 
																		rango_tabla_constantes 
																		WHERE 
																			idtabla_constantes = '".$bus_consultar_tabla["idtabla_constantes"]."'
																			order by idrango_tabla_constantes asc limit ".($numero-1).",1");
											$bus_rango = mysql_fetch_array($sql_rango);
											$formula .= $bus_rango["valor"];
									}
								break;
								case "FU": // SI ES UNA FUNCION ENTRA ACA
									//echo "aqui";
									$sql_consultar_anterior = mysql_query("SELECT * FROM
																				relacion_formula_conceptos_nomina 
																				WHERE idconcepto_nomina = '".$bus_relacion_formula["idconcepto_nomina"]."' 
																				and orden = '".($bus_relacion_formula["orden"]-1)."'
																				and destino = 'principal'")or die(mysql_error());
									
									$bus_consultar_anterior = mysql_fetch_array($sql_consultar_anterior);
									$partes_anterior = explode("_", $bus_consultar_anterior["valor_oculto"]);
										//echo $partes_anterior[0].".....";
										if($partes_anterior[0] != "TA"){
											if(ereg("numerode", $partes[1])){
												$tabla = explode("(", $partes[1]);						
												$tabla = substr($tabla[1], 0, strlen($tabla[1])-1);
												$final = explode("-",$tabla);
												$formula .= numerode($final[0], $final[1], $final[2], $idtrabajador);		
												//echo "AQUI".$numero;
											}else if(ereg("anioempresa", $partes[1])){
												$formula .= $partes[1]($idtrabajador);
											}else if(ereg("tiempoentrefechas", $partes[1])){
												$fechas = explode("(", $partes[1]);	
												$fechas = substr($fechas[1], 0, strlen($fechas[1])-1);
												$fechas = explode(",", $fechas);
												$formula .= tiempoentrefechas($fechas[0], $fechas[1]);
											}else if(ereg("diasferiadosentre", $partes[1])){
												$fechas = explode("(", $partes[1]);	
												$fechas = substr($fechas[1], 0, strlen($fechas[1])-1);
												$fechas = explode(",", $fechas);
												$formula .= diasferiadosentre($fechas[0], $fechas[1]);
											}else if(ereg("mesactual", $partes[1])){
												$formula .= date("m");
											}else if(ereg("diasmes", $partes[1])){
												$formula .= diasmes(date("Y") , date("m"));
											}
											//echo $partes[1]($idtrabajador)."...";
										}
								break;
							}
						}
						//echo $formula;
						
				}//CIERRA SI NO TIENE UNA CONDICION
			}else{
				if($bus_consulta_tabla["valor"] == 0){
					$formula .= $bus_consulta_asociado["valor"];
				}else{
					$formula .= $bus_consulta_tabla["valor"];
				}
			}
		
		echo eval("echo $formula;")." ";
		//if(eval("echo $formula;")){echo "si";}else{echo "no";}
	}
	

}





if($ejecutar =="modificarValor"){
	$sql_modificar = mysql_query("update relacion_concepto_trabajador set valor = '".$valor."'
                                                                where idrelacion_concepto_trabajador= '".$id."'");
	
	
	$sql_relacion = mysql_query("select * from relacion_concepto_trabajador where idrelacion_concepto_trabajador = '".$id."'");
	$bus_relacion = mysql_fetch_array($sql_relacion);
	
	$sql_constante = mysql_query("select * from constantes_nomina where idconstantes_nomina = '".$bus_relacion["idconcepto"]."'");
	$bus_constante = mysql_fetch_array($sql_constante);
	
	$sql_consulta_trabajador = mysql_query("select * from trabajador where idtrabajador = '".$bus_relacion["idtrabajador"]."'");
	$bus_consulta_trabajador = mysql_fetch_array($sql_consulta_trabajador);
	
	
	registra_transaccion('Se modifico el valor del concepto ('.$bus_relacion["idconcepto"].') al trabajador ('.$bus_relacion["idtrabajador"].')',$login,$fh,$pc,'nomina',$conexion_db);
	
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
												'".$bus_consulta_trabajador["idtrabajador"]."',
												'".date("Y-m-d")."',
												'1000000',
												'MODIFICACION DE LA CONSTANTE ASOCIADA (".$bus_constante["descripcion"]." AL VALOR : ".$valor.")',
												'".$bus_consulta_trabajador["fecha_ingreso"]."',
												'".$causal."',
												'".$bus_consulta_trabajador["idcargo"]."',
												'".$bus_consulta_trabajador["idunidad_funcional"]."',
												'".$login."',
												'a',
												'".$fh."',
												'".$bus_consulta_trabajador["centro_costo"]."',
												'".$bus_relacion["idconcepto"]."',
												'".$valor."')")or die(mysql_error());		
		
}
?>
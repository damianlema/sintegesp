<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
include("funciones_conceptos.php");
Conectarse();
extract($_POST);
global $bandera_en;
$bandera_en = 0;

function desagregarConcepto($id){
	global $bandera_en;
	$sql_consulta = mysql_query("select * from relacion_formula_conceptos_nomina 
										where 
										idconcepto_nomina = '".$id."'
										and (valor_oculto like '%CN%' 
										OR valor_oculto like '%CO%')")or die(mysql_error());
	while($bus_consulta = mysql_fetch_array($sql_consulta)){
		//echo $bus_consulta["valor_oculto"]."<br />";
		$partes = explode("_", $bus_consulta["valor_oculto"]);
		if($partes[0] == "CN"){
			if($bandera_en == 0){
			//echo "aqui <br />";
				$query .= "idconstantes_nomina = '".$partes[1]."' ";
				$bandera_en = 1;
			}else{
				$query .= " or idconstantes_nomina = '".$partes[1]."' ";
			}
			
		}
		if($partes[0] == "CO"){
			$query .= desagregarConcepto($partes[1]);
		}
	}
	//
	return $query;
}





if($ejecutar == "cargarConstantes"){
global $bandera_en;	
	
	$query = "select * from constantes_nomina where (tipo = 'valor_fijo' 
											and (afecta = 4 or afecta = 2)) and (";
	$sql_consulta_conceptos = mysql_query("select * from 
												relacion_concepto_trabajador 
												where 
												tabla = 'conceptos_nomina' 
												and idtipo_nomina = '".$idtipo_nomina."'
												group by idconcepto")or die(mysql_error());
	while($bus_consulta_conceptos = mysql_fetch_array($sql_consulta_conceptos)){
		
		$sql_relacion_concepto = mysql_query("select * from 
								relacion_formula_conceptos_nomina 
								where 
								idconcepto_nomina ='".$bus_consulta_conceptos["idconcepto"]."' 
								and (valor_oculto like '%CN%' 
								OR valor_oculto like '%CO%')")or die(mysql_error());
		$num_relacion_concepto = mysql_num_rows($sql_relacion_concepto);
		
		if($num_relacion_concepto > 0){
	
		
			while($bus_relacion_concepto = mysql_fetch_array($sql_relacion_concepto)){
				//echo $bus_relacion_concepto["valor_oculto"]."<br />";
				$partes = explode("_", $bus_relacion_concepto["valor_oculto"]);
				if($partes[0] == "CN"){
					if($bandera_en == 0){
						//echo "aqui";
						$query .= "idconstantes_nomina = '".$partes[1]."' ";
						$bandera_en = 1;
					}else{
						$query .= " or idconstantes_nomina = '".$partes[1]."' ";
					}
				}
				if($partes[0] == "CO"){
					$query .= desagregarConcepto($partes[1]);
				}
			}
		
		}

	}
	
	$query .= ' ) group by idconstantes_nomina';
	
	/*$sql_consultar = mysql_query("select co.descripcion,
										co.idconstantes_nomina
										 from 
										 constantes_nomina co,
										 relacion_concepto_trabajador rct
										 where 
										 co.tipo = 'valor_fijo'
										 
										 and rct.idtipo_nomina = '".$idtipo_nomina."'
										 and rct.idconcepto = co.idconstantes_nomina
										 group by rct.idconcepto")or die(mysql_error());*/
	//echo $query;
	$sql_consultar = mysql_query($query)or die(mysql_error());
	$num_consultar = mysql_num_rows($sql_consultar);
	?>
	<input type='hidden' id="cantidad_constantes" name="cantidad_constantes" value="<?=$num_consultar?>">									 
	
	<form name="formulario_constantes" id="formulario_constantes">
    <table>
        <?
		$i=1;
        while($bus_consultar = mysql_fetch_array($sql_consultar)){
		?>
            <tr>
                <td align="right" valign="top" class='viewPropTitle'><?=$bus_consultar["descripcion"]?></td>
                <td>
                    <input type="hidden" id="id-<?=$i?>" name="id-<?=$i?>" value="<?=$bus_consultar["idconstantes_nomina"]?>">
                    <input type="text" name="valor-<?=$i?>" id="valor-<?=$i?>"></td>
                <td>
                    <select name="tipo_cambio-<?=$i?>" id="tipo_cambio-<?=$i?>">
                        <option value="porcentual">Porcentual</option>
                        <option value="sumatoria">Sumatoria</option>
                    </select>
                </td>
                <td>Desde&nbsp;</td>
                <td><input type="text" name="desde-<?=$i?>" id="desde-<?=$i?>"></td>
                <td>Hasta&nbsp;</td>
                <td><input type="text" name="hasta-<?=$i?>" id="hasta-<?=$i?>"></td>
            </tr>
        <?
		$i++;
		}
		?>
    </table>
    </form>
	<?
}








if($ejecutar == 'seleccionarPeriodo'){
if($idgenerar_nomina != ""){
	$sql_consulta = mysql_query("select * from simular_nomina where idsimular_nomina = '".$idgenerar_nomina."'");
	$bus_consulta = mysql_fetch_array($sql_consulta);
}
	$sql_consultar_periodo = mysql_query("select * from 
										 			periodos_nomina pn,
													rango_periodo_nomina rpn
										 			where 
													pn.idtipo_nomina = '".$idtipo_nomina."'
													and pn.periodo_activo = 'si'
													and rpn.idperiodo_nomina = pn.idperiodos_nomina")or die(mysql_error());
	?>
    
	<select name="idperiodo" id="idperiodo">
    	<option value="0">.:: Seleccione ::.</option>
	<?
		while($bus_consultar_periodo= mysql_fetch_array($sql_consultar_periodo)){
			?>
			<option <? if($bus_consulta["idperiodo"] == $bus_consultar_periodo["idrango_periodo_nomina"]){ echo "selected";}?> value="<?=$bus_consultar_periodo["idrango_periodo_nomina"]?>">
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







if($ejecutar == "ingresarDatosBasicos"){
	$sql_ingresr = mysql_query("insert into simular_nomina(idtipo_nomina, idperiodo)VALUES('".$idtipo_nomina."', '".$idperiodo."')");	
	echo mysql_insert_id()."|.|".date("Y-m-d");
}





if($ejecutar == "consultarTrabajadores"){
$sql_consulta = mysql_query("select tr.nombres,
										tr.apellidos,
										tr.cedula,
										tr.idtrabajador,
										tr.nro_ficha
											FROM 
										trabajador tr,
										relacion_tipo_nomina_trabajador rtnt
											WHERE
										rtnt.idtipo_nomina = '".$idtipo_nomina."'
										and tr.status = 'a'
										and tr.activo_nomina = 'si'
										and rtnt.idtrabajador =  tr.idtrabajador
										and (tr.nombres like '%".$buscar."%' 
											 or tr.apellidos like '%".$buscar."%' 
											 or tr.cedula like '%".$buscar."%')
										group by tr.idtrabajador
										order by tr.cedula")or die(mysql_error());

	

	?>
    
    <table>
            	<tr>
                <td>Buscar</td>
                <td><input type="text" name="campo_buscar_trabajador" id="campo_buscar_trabajador" value="<?=$buscar?>"></td>
                <td><input type="button" value="Buscar" id="boton_buscar" name="boton_buscar" class="button" onclick="consultarTrabajadores('Elaboracion', document.getElementById('campo_buscar_trabajador').value)"></td>
                </tr>
            </table>
            <br />
    
	<table class="Browse" cellpadding="0" cellspacing="0" width="98%" align="center">
        <thead>
        <tr>
    		<td width="5%" align="center" class="Browse">Nro</td>
            <td width="15%" align="center" class="Browse">Nro Ficha</td>
            <td width="15%" align="center" class="Browse">Cedula</td>
            <td width="37%" align="center" class="Browse">Nombre</td>
            <td width="37%" align="center" class="Browse">Apellido</td>
   		</tr>
        </thead>
        <?
		$i=1;
        while($bus_consulta = mysql_fetch_array($sql_consulta)){
			?>
			<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onClick="pdf.location.href='lib/reportes/nomina/reportes.php?nombre=relacion_nomina_trabajadores&idtrabajador=<?=$bus_consulta['idtrabajador']?>&nomina='+document.getElementById('idtipo_nomina').value+'&periodo='+document.getElementById('idperiodo').value; document.getElementById('pdf').style.display='block'; document.getElementById('divImprimir').style.display='block';">
                <td align='center' class='Browse'><?=$i?></td>
                <td align='center' class='Browse'><?=$bus_consulta["nro_ficha"]?></td>
                <td align='left' class='Browse'><?=number_format($bus_consulta["cedula"],0,",",".")?></td>
                <td align='left' class='Browse'><?=$bus_consulta["nombres"]?></td>
                <td align='left' class='Browse'><?=$bus_consulta["apellidos"]?></td>
   			</tr>
			<?	
			$i++;
		}
		?>
        
    </table>
    
	<?
}







function call_concepto($idconcepto, $idtrabajador, $destino, $idgenerar_nomina, $idtipo_nomina, $idperiodo, $desagregar_concepto, $factor, $arr_constantes){
	//echo "FORMULA";
	$sql_relacion_formula= mysql_query("SELECT * FROM 
												relacion_formula_conceptos_nomina 
													WHERE 
												idconcepto_nomina ='".$idconcepto."'
												and destino = '".$destino."'
												order by orden")or die(mysql_error());
	while($bus_relacion_formula = mysql_fetch_array($sql_relacion_formula)){
		$partes = explode("_", $bus_relacion_formula["valor_oculto"]);	
		//echo $partes[0];
		switch($partes[0]){
			case "SI":// SI ES UN SIMBOLO ENTRA ACA
				$formula .= $partes[1];
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
																	//and idtipo_nomina = '".$idtipo_nomina."'
												$bus_consulta_relacion = mysql_fetch_array($sql_consulta_relacion);
												$monto = $bus_consulta_relacion["valor"];
											}else{
												$monto = $bus_constantes["valor"];
											}
											//echo "AQUII :".$monto." ---- ";
											if(in_array("id_".$partes[1], $arr_constantes)){
									$pos = array_search('id_'.$partes[1],$arr_constantes);  
									if($arr_constantes[$pos+2] == "porcentual"){
										if($monto >= $arr_constantes[$pos+3] && $monto <= $arr_constantes[$pos+4]){
											$porcen = ($monto*$arr_constantes[$pos+1])/100;
											$suma = ($monto+$porcen);
											$formula .= $suma;
											//echo str_replace(".",",",$suma)."\n";
										}else{
											$formula .= $monto;
											//echo str_replace(".",",",$monto)."\n";
										}
									}else{
										if($monto >= $arr_constantes[$pos+3] && $monto <= $arr_constantes[$pos+4]){
											$formula .= $arr_constantes[$pos+1];
										}
									}
									
								}else{
									$formula .= $monto; 
									//echo str_replace(".",",",$monto)."\n";
								}
											
			break;
			case "CO":// SI ES UN CONCEPTO ENTRA ACA
				//echo "aqui";
				 $result = call_concepto($partes[1], $idtrabajador, 'principal', $idgenerar_nomina, $idtipo_nomina, $idperiodo, $desagregar_concepto, $factor, $arr_constantes);
				 
				 $formula .= $result;
				 
				 
				if($desagregar_concepto == "si"){
					eval("\$t = ($result*$factor);");
					$sql_insertar = mysql_query("insert into conceptos_desagregados(idsimular_nomina,
						idconcepto,
						idtrabajador,
						valor)VALUES('".$idgenerar_nomina."',
									'".$partes[1]."',
									'".$idtrabajador."',
									'".$t."')");
				}
				
			break;
			case "THT":// SI ES UNA HOJA DE TIEMPO
					$sql_hoja_tiempo = mysql_query("select * from hoja_tiempo 
														where 
														idtipo_hoja_tiempo = '".$partes[1]."'
														and periodo = '".$idperiodo."'")or die(mysql_error());
					$num_hoja_tiempo = mysql_num_rows($sql_hoja_tiempo);
					if($num_hoja_tiempo > 0){
						$bus_hoja_tiempo = mysql_fetch_array($sql_hoja_tiempo);
						$sql_relacion_trabajador = mysql_query("select horas from relacion_hoja_tiempo_trabajador
																where idhoja_tiempo = '".$bus_hoja_tiempo["idhoja_tiempo"]."'
																and idtrabajador = '".$idtrabajador."'");	
						$bus_relacion_trabajador = mysql_fetch_array($sql_relacion_trabajador);
						if($bus_relacion_trabajador["horas"] == ""){
							$formula .= "0";
						}else{
							$formula .= $bus_relacion_trabajador["horas"];
						}
						
						
					}else{
						$formula .= "0";
					}
											
										
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
														and ".$partes_fu[1]." >= desde  
														and ".$partes_fu[1]." <= hasta 
														order by idrango_tabla_constantes asc limit 0,1");
						$bus_rango = mysql_fetch_array($sql_rango);
						
						if($bus_rango["valor"] == ""){
							$formula .= "0";
						}else{
							$formula .= $bus_rango["valor"];
						}
						
						
				}else if($partes_fu[0] == "FU"){						
						if(ereg("numerode", $partes_fu[1])){
							$tabla = explode("(", $partes_fu[1]);						
							$tabla = substr($tabla[1], 0, strlen($tabla[1])-1);
							$final = explode("-",$tabla);
							
							$numero = numerode($final[0], $final[1], $final[2], $idtrabajador);
						}else if(ereg("tiempo_bono", $partes_fu[1])){
							$numero = tiempo_bono($idperiodo,$idtrabajador);											
						}else if(ereg("anioempresa", $partes_fu[1])){
							$numero = anioempresa($idtrabajador);
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
							$sql_periodos = mysql_query("select * from 
													rango_periodo_nomina
													where 
													idrango_periodo_nomina = '".$idperiodo."'");
							$bus_periodos = mysql_fetch_array($sql_periodos);
							$datos = explode("-", $bus_periodos["desde"]);
							$numero = $datos[1];
						}else if(ereg("diasmes", $partes_fu[1])){
							$numero = diasmes(date("Y") , date("m"));
						}else if(ereg("mesesentre", $partes_fu[1])){
							$fechas = explode("(", $partes_fu[1]);	
							$fechas = substr($fechas[1], 0, strlen($fechas[1])-1);
							$fechas = explode(",", $fechas);
							$numero = mesesentre($fechas[0], $fechas[1], $idtrabajador);
						}else if(ereg("diasentre", $partes_fu[1])){
							$fechas = explode("(", $partes_fu[1]);	
							$fechas = substr($fechas[1], 0, strlen($fechas[1])-1);
							$fechas = explode(",", $fechas);
							$numero = diasentre($fechas[0], $fechas[1], $idtrabajador);
						}
						
						$numero = (int)$numero;
						
						$sql_rango = mysql_query("SELECT * FROM 
													rango_tabla_constantes 
													WHERE 
														idtabla_constantes = '".$bus_consultar_tabla["idtabla_constantes"]."'
														and ".$numero." >= desde  
														and ".$numero." <= hasta 
														order by idrango_tabla_constantes asc limit 0,1");
						$bus_rango = mysql_fetch_array($sql_rango);
						//echo "RANGO ".$bus_rango["valor"];
						
						if($bus_rango["valor"] == ""){
							$formula .= "0";
						}else{
							$formula .= $bus_rango["valor"];
						}
						
						
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
						//echo $partes[1].".............";
						if(ereg("numerode", $partes[1])){
							$tabla = explode("(", $partes[1]);						
							$tabla = substr($tabla[1], 0, strlen($tabla[1])-1);
							$final = explode("-",$tabla);
							$result = numerode($final[0], $final[1], $final[2], $idtrabajador);
							if($result == ""){
								$formula .= "0";
							}else{
								$formula .= $result; 
							}
							
							
						}else if(ereg("tiempo_bono", $partes[1])){
						//echo "ALLA....\n";							
							$formula .= tiempo_bono($idperiodo,$idtrabajador);							
														
						}else if(ereg("anioempresa", $partes[1])){
							$result .= anioempresa($idtrabajador);
							
							if($result == ""){
								$formula .= "0";
							}else{
								$formula .= $result; 
							}
							
							
						}else if(ereg("tiempoentrefechas", $partes[1])){
							$fechas = explode("(", $partes[1]);	
							$fechas = substr($fechas[1], 0, strlen($fechas[1])-1);
							$fechas = explode(",", $fechas);
							$result = tiempoentrefechas($fechas[0], $fechas[1]);
							
							if($result == ""){
								$formula .= "0";
							}else{
								$formula .= $result; 
							}
						}else if(ereg("diasferiadosentre", $partes[1])){
							$fechas = explode("(", $partes[1]);	
							$fechas = substr($fechas[1], 0, strlen($fechas[1])-1);
							$fechas = explode(",", $fechas);
							$result = diasferiadosentre($fechas[0], $fechas[1]);
							
							if($result == ""){
								$formula .= "0";
							}else{
								$formula .= $result; 
							}
							
						}else if(ereg("mesactual", $partes[1])){
							$sql_periodos = mysql_query("select * from 
													rango_periodo_nomina
													where 
													idrango_periodo_nomina = '".$idperiodo."'");
							$bus_periodos = mysql_fetch_array($sql_periodos);
							$datos = explode("-", $bus_periodos["desde"]);
							$formula .= $datos[1];
							
						}else if(ereg("diasmes", $partes[1])){
							$formula .= diasmes(date("Y") , date("m"));
						}else if(ereg("mesesentre", $partes[1])){
							$fechas = explode("(", $partes[1]);	
							$fechas = substr($fechas[1], 0, strlen($fechas[1])-1);
							$fechas = explode(",", $fechas);
							$result = mesesentre($fechas[0], $fechas[1], $idtrabajador);
							
							if($result == ""){
								$formula .= "0";
							}else{
								$formula .= $result; 
							}
							
							//echo $formula;
						}else if(ereg("diasentre", $partes[1])){
							$fechas = explode("(", $partes[1]);	
							$fechas = substr($fechas[1], 0, strlen($fechas[1])-1);
							$fechas = explode(",", $fechas);
							$result = diasentre($fechas[0], $fechas[1], $idtrabajador);
							
							if($result == ""){
								$formula .= "0";
							}else{
								$formula .= $result; 
							}
							//echo "ACAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA";
						}
						//echo $formula;
					}
					
					
			break;
		}
	}
	return $formula;
}






if($ejecutar == "generarNomina"){
	
	//echo "ARREGLO: ".$arreglo."\n";
	//exit();
	global $arr_constantes;
	$arr_constantes = explode(",", $arreglo);
	
	
	
	
	$sql_limpiar = mysql_query("delete from relacion_simular_nomina where idsimular_nomina = '".$idgenerar_nomina."'");


	$sql_trabajador = mysql_query("select tr.nombres,
										tr.apellidos,
										tr.cedula,
										tr.idtrabajador
											FROM 
										trabajador tr,
										relacion_tipo_nomina_trabajador rtt
											WHERE 
										rtt.idtipo_nomina = '".$idtipo_nomina."'
										and rtt.idtrabajador = tr.idtrabajador
										and tr.status = 'a'
										and tr.activo_nomina = 'si'
										group by tr.idtrabajador")or die(mysql_error());
	$k=0;
	while($bus_trabajador =mysql_fetch_array($sql_trabajador)){
			
			$idtrabajador = $bus_trabajador["idtrabajador"]	;
			
			
			$sql_rango_periodo = mysql_query("select * from rango_periodo_nomina where idrango_periodo_nomina = '".$idperiodo."'");
			$bus_rango_periodo = mysql_fetch_array($sql_rango_periodo);
			
			$sql_consulta_asociado = mysql_query("(SELECT * FROM 
															relacion_concepto_trabajador 
																WHERE
															idtrabajador = '".$idtrabajador."'
															and idtipo_nomina = '".$idtipo_nomina."'
															and fecha_limite_ejecucion = '0000-00-00')
																UNION
																	(SELECT * FROM 
																	relacion_concepto_trabajador 
																		WHERE
																	idtrabajador = '".$idtrabajador."'
																	and idtipo_nomina = '".$idtipo_nomina."'
																	and fecha_limite_ejecucion >= '".$bus_rango_periodo["desde"]."'
																	and fecha_limite_ejecucion != '')")or die(mysql_error());
			
			
			
			/*$sql_consulta_asociado = mysql_query("SELECT * FROM 
															relacion_concepto_trabajador 
																WHERE
															idtrabajador = '".$idtrabajador."'
															and idtipo_nomina = '".$idtipo_nomina."'");*/
			
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
																	and destino = 'condicion'
																	order by orden");
						$num_relacion_formula= mysql_num_rows($sql_relacion_formula);
						
							if($num_relacion_formula > 0){
								// SI TIENE UN CONDICIONAL
								
								$result = call_concepto($bus_consulta_tabla["idconceptos_nomina"], $idtrabajador, 'condicion', $idgenerar_nomina, $idtipo_nomina, $idperiodo, $bus_consulta_tabla["desagregar_concepto"], $bus_consulta_tabla["factor_desagregacion"], $arr_constantes);
								//echo "AQUI";
								@eval("\$res = $result;");
								//echo $res."----";
								if($res == "1"){
								//echo "SI";
									$formula .= call_concepto($bus_consulta_tabla["idconceptos_nomina"], $idtrabajador, 'entonces',$idgenerar_nomina, $idtipo_nomina, $idperiodo, $bus_consulta_tabla["desagregar_concepto"], $bus_consulta_tabla["factor_desagregacion"], $arr_constantes);
									//echo $formula;
								}else{
								//echo "NOOOOOOO";
									
									$result = call_concepto($bus_consulta_tabla["idconceptos_nomina"], $idtrabajador, 'principal', $idgenerar_nomina, $idtipo_nomina, $idperiodo, $bus_consulta_tabla["desagregar_concepto"], $bus_consulta_tabla["factor_desagregacion"], $arr_constantes);
									$formula .= $result;

								}
							}else{
							
								$sql_relacion_formula= mysql_query("SELECT * FROM 
																			relacion_formula_conceptos_nomina 
																				WHERE 
																			idconcepto_nomina ='".$bus_consulta_tabla["idconceptos_nomina"]."'
																			and destino= 'principal'
																			order by orden");
								while($bus_relacion_formula = mysql_fetch_array($sql_relacion_formula)){
								
									$partes = explode("_", $bus_relacion_formula["valor_oculto"]);	
									switch($partes[0]){
										case "SI":// SI ES UN SIMBOLO ENTRA ACA
										
											
											
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
																	//and idtipo_nomina = '".$idtipo_nomina."'
												$bus_consulta_relacion = mysql_fetch_array($sql_consulta_relacion);
												$monto = $bus_consulta_relacion["valor"];
											}else{
												$monto = $bus_constantes["valor"];
											}
											//echo "MONTO: ".$monto." --- ";
											if($monto == ""){
											
												$formula .= "0";
											}else{
											
											//echo "ARRAY: ".var_dump($arr_constantes)."\n";
								if(in_array("id_".$partes[1], $arr_constantes)){
									$pos = array_search('id_'.$partes[1],$arr_constantes);  
									if($arr_constantes[$pos+2] == "porcentual"){
										if($monto >= $arr_constantes[$pos+3] && $monto <= $arr_constantes[$pos+4]){
											$porcen = ($monto*$arr_constantes[$pos+1])/100;
											$suma = ($monto+$porcen);
											$formula .= $suma;
											//echo str_replace(".",",",$suma)."\n";
										}else{
											$formula .= $monto;
											//echo str_replace(".",",",$monto)."\n";
										}
									}else{
										if($monto >= $arr_constantes[$pos+3] && $monto <= $arr_constantes[$pos+4]){
											$formula .= $arr_constantes[$pos+1];
										}
									}
									
								}else{
									$formula .= $monto; 
									//echo "A: ".str_replace(".",",",$monto)."\n";
								}
											}
											

										break;
										case "CO":// SI ES UN CONCEPTO ENTRA ACA
																								
												//echo "CONCEPTO";
												$result = call_concepto($partes[1], $idtrabajador, 'principal', $idgenerar_nomina, $idtipo_nomina, $idperiodo, $bus_consulta_tabla["desagregar_concepto"], $bus_consulta_tabla["factor_desagregacion"], $arr_constantes);	
												//echo "RESULTADO: ".$result."............    ";
												//echo $bus_consulta_tabla["desagregar_concepto"];
												
												if($bus_consulta_tabla["desagregar_concepto"] == "si"){
											$sql_insertar = mysql_query("insert into conceptos_desagregados(idsimular_nomina,
												idconcepto,
												idtrabajador,
												valor)VALUES('".$idgenerar_nomina."',
															'".$partes[1]."',
															'".$idtrabajador."',
															'".eval($result*$bus_consulta_tabla["factor_desagregacion"])."')");
												}
												
												if($result == ""){
													$formula .= "0";
												}else{
													$formula .= $result; 
												}
													
												//echo $formula;
										break;
										case "THT":// SI ES UNA HOJA DE TIEMPO
										
											$sql_hoja_tiempo = mysql_query("select * from hoja_tiempo where 
																		   		idtipo_hoja_tiempo = '".$partes[1]."'
																				and idtipo_nomina = '".$idtipo_nomina."'
																				and periodo = '".$idperiodo."'")or die(mysql_error());
											$num_hoja_tiempo = mysql_num_rows($sql_hoja_tiempo);
											if($num_hoja_tiempo > 0){
												$bus_hoja_tiempo = mysql_fetch_array($sql_hoja_tiempo);
												$sql_relacion_trabajador = mysql_query("select horas from relacion_hoja_tiempo_trabajador
																					   	where idhoja_tiempo = '".$bus_hoja_tiempo["idhoja_tiempo"]."'
																						and idtrabajador = '".$idtrabajador."'");	
												$bus_relacion_trabajador = mysql_fetch_array($sql_relacion_trabajador);
												if($bus_relacion_trabajador["horas"] == "" || $bus_relacion_trabajador["horas"] == "()"){
													$formula .= "0";
												}else{
													$formula .= $bus_relacion_trabajador["horas"]; 
												}
											}else{
												$formula .= "0";	
											}
											
										
										//echo $formula;
										break;
										case "TA":// SI ES UNA TABLA CONSTANTE ENTRA ACA
											//echo $partes[1]." - ";
											
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
																	and '".$partes_fu[1]."' >= desde  
																	and '".$partes_fu[1]."' <= hasta 
																	order by idrango_tabla_constantes asc limit 0,1");
													$bus_rango = mysql_fetch_array($sql_rango);
													
													if($bus_rango["valor"] == ""){
														$formula .= "0";
													}else{
														$formula .= $bus_rango["valor"]; 
													}
													
													
													
											}else if($partes_fu[0] == "FU"){
													if(ereg("numerode", $partes_fu[1])){
														$tabla = explode("(", $partes_fu[1]);						
														$tabla = substr($tabla[1], 0, strlen($tabla[1])-1);
														$final = explode("-",$tabla);
														$numero = numerode($final[0], $final[1], $final[2], $idtrabajador);
													}else if(ereg("anioempresa", $partes_fu[1])){
														$numero = anioempresa($idtrabajador);
													}else if(ereg("tiempo_bono", $partes_fu[1])){
													
														$numero = tiempo_bono($idperiodo,$idtrabajador);
														
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
														//echo "AQUI TE ENTRO";
														
														$sql_periodos = mysql_query("select * from 
																				rango_periodo_nomina
																				where 
																				idrango_periodo_nomina = '".$idperiodo."'");
														$bus_periodos = mysql_fetch_array($sql_periodos);
														$datos = explode("-", $bus_periodos["desde"]);
														$numero = $datos[1];
														
														//$numero = date("m");
														//echo " - ".$numero;
													}else if(ereg("diasmes", $partes_fu[1])){
														$numero = diasmes(date("Y") , date("m"));
													}else if(ereg("mesesentre", $partes_fu[1])){
														$fechas = explode("(", $partes_fu[1]);	
														$fechas = substr($fechas[1], 0, strlen($fechas[1])-1);
														$fechas = explode(",", $fechas);
														$numero = mesesentre($fechas[0], $fechas[1], $idtrabajador);
													}else if(ereg("diasentre", $partes_fu[1])){
														$fechas = explode("(", $partes_fu[1]);	
														$fechas = substr($fechas[1], 0, strlen($fechas[1])-1);
														$fechas = explode(",", $fechas);
														$numero = diasentre($fechas[0], $fechas[1], $idtrabajador);
													}
													
													$numero = (int)$numero;
													
													
													$sql_rango = mysql_query("SELECT * FROM 
																				rango_tabla_constantes 
																				WHERE 
																	idtabla_constantes = '".$bus_consultar_tabla["idtabla_constantes"]."'
																	and ".$numero." >= desde  
																	and ".$numero." <= hasta 
																	order by idrango_tabla_constantes asc limit 0,1")or die(mysql_error());
													$bus_rango = mysql_fetch_array($sql_rango);
													//echo "PRUEBA ".$bus_rango["valor"];
													
													
													if($bus_rango["valor"] == ""){
														$formula .= "0";
													}else{
														$formula .= $bus_rango["valor"];
													}
													
													
													
											}
										break;
										case "FU": // SI ES UNA FUNCION ENTRA ACA
											
											$sql_consultar_anterior = mysql_query("SELECT * FROM
																				relacion_formula_conceptos_nomina 
																				WHERE idconcepto_nomina = '".$bus_relacion_formula["idconcepto_nomina"]."' 
																				and orden = '".($bus_relacion_formula["orden"]-1)."'
																				and destino = 'principal'")or die(mysql_error());
											
											$bus_consultar_anterior = mysql_fetch_array($sql_consultar_anterior);
											$partes_anterior = explode("_", $bus_consultar_anterior["valor_oculto"]);
												
												//echo $partes_anterior[0].".....";
												if($partes_anterior[0] != "TA"){
													//echo "AA";
													
													//echo $partes[1]."...";
													if(ereg("numerode", $partes[1])){
														$tabla = explode("(", $partes[1]);						
														$tabla = substr($tabla[1], 0, strlen($tabla[1])-1);
														$final = explode("-",$tabla);
														$result = numerode($final[0], $final[1], $final[2], $idtrabajador);	
														
														
														if($result == ""){
															$formula .= "0";
														}else{
															$formula .= $result;
														}
														
														$cantidad_numero = numerode($final[0], $final[1], $final[2], $idtrabajador);
													}else if(ereg("tiempobono", $partes[1])){
													//echo "AQUI: ".$idperiodo;
													
													//$formula .= "PRUEBAAAA";
														$formula .= tiempo_bono($idperiodo,$idtrabajador);
														
														//echo "prueba".$formula."...... \n";
														
														
													}else if(ereg("anioempresa", $partes[1])){
														$result = anioempresa($idtrabajador);
														
														if($result == ""){
															$formula .= "0";
														}else{
															$formula .= $result;
														}
														
													}else if(ereg("tiempoentrefechas", $partes[1])){
														$fechas = explode("(", $partes[1]);	
														$fechas = substr($fechas[1], 0, strlen($fechas[1])-1);
														$fechas = explode(",", $fechas);
														$result = tiempoentrefechas($fechas[0], $fechas[1]);
														
														
														if($result == ""){
															$formula .= "0";
														}else{
															$formula .= $result;
														}
														
														
														
													}else if(ereg("diasferiadosentre", $partes[1])){
														$fechas = explode("(", $partes[1]);	
														$fechas = substr($fechas[1], 0, strlen($fechas[1])-1);
														$fechas = explode(",", $fechas);
														$result = diasferiadosentre($fechas[0], $fechas[1]);
														
														if($result == ""){
															$formula .= "0";
														}else{
															$formula .= $result;
														}
														
													}else if(ereg("mesactual", $partes[1])){
														$sql_periodos = mysql_query("select * from 
																				rango_periodo_nomina
																				where 
																				idrango_periodo_nomina = '".$idperiodo."'");
														$bus_periodos = mysql_fetch_array($sql_periodos);
														$datos = explode("-", $bus_periodos["desde"]);
														$formula .=$datos[1];
														
													}else if(ereg("diasmes", $partes[1])){
														$formula .= diasmes(date("Y") , date("m"));
													}else if(ereg("mesesentre", $partes[1])){
														
														$fechas = explode("(", $partes[1]);	
														$fechas = substr($fechas[1], 0, strlen($fechas[1])-1);
														$fechas = explode(",", $fechas);
														
														$result = mesesentre($fechas[0], $fechas[1], $idtrabajador);
														
														if($result == ""){
															$formula .= "0";
														}else{
															$formula .= $result;
														}
													}else if(ereg("diasentre", $partes[1])){
														
														$fechas = explode("(", $partes[1]);	
														$fechas = substr($fechas[1], 0, strlen($fechas[1])-1);
														$fechas = explode(",", $fechas);
														$result = diasentre($fechas[0], $fechas[1], $idtrabajador);
														
														if($result == ""){
															$formula .= "0";
														}else{
															$formula .= $result;
														}
														//echo $formula;
														
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
				//echo " | ".$formula;
				
				if($formula != ""){
					//echo $formula.".........\n";
					@eval("\$total = $formula;");
					//echo $total." ........ \n";
				}
				
				
				
				
				$sql_consulta = mysql_query("select * from relacion_simular_nomina where 
											idsimular_nomina ='".$idgenerar_nomina."'
											and idtrabajador = '".$idtrabajador."'
											and idconcepto = '".$bus_consulta_asociado["idconcepto"]."'
											and tabla = '".$bus_consulta_asociado["tabla"]."'")or die("!!!!".mysql_error());
				$num_consulta = mysql_num_rows($sql_consulta);
				
				$sql_neutro = mysql_query("select * from ".$bus_consulta_asociado["tabla"]." 
															where 
															id".$bus_consulta_asociado["tabla"]." = ".$bus_consulta_asociado["idconcepto"]."")or die(mysql_error());
				$bus_neutro = mysql_fetch_array($sql_neutro);
				
				if($bus_consulta_asociado["tabla"] == "constantes_nomina"){
					$afecta = $bus_neutro["afecta"];
				}else{
					$afecta = $bus_neutro["tipo_concepto"];
				}
				
				$sql_afecta = mysql_query("select * from tipo_conceptos_nomina where idconceptos_nomina = '".$afecta."'");
				$bus_afecta = mysql_fetch_array($sql_afecta);
				if($bus_afecta["afecta"] == "Asignacion"){
					$id_afecta = 1;
				}else if($bus_afecta["afecta"] == "Deduccion"){
					$id_afecta = 2;
				}else if($bus_afecta["afecta"] == "Aporte"){
					$id_afecta = 4;
				}else{
					$id_afecta = 3;
				}
				
				
				if($total != 0){
				if($id_afecta != 3){														
					if($num_consulta == 0){
						$sql_guardar_total= mysql_query("insert into relacion_simular_nomina(idsimular_nomina, 
															 idtrabajador, 
															 idconcepto,
															 tabla,
															 total)VALUES('".$idgenerar_nomina."', 
																			'".$idtrabajador."', 
																			'".$bus_consulta_asociado["idconcepto"]."',
																			'".$bus_consulta_asociado["tabla"]."',
																			'".$total."')")or die("2222".mysql_error());
					}else{
						$sql_guardar_total= mysql_query("update relacion_simular_nomina set total = '".$total."'
												where idsimular_nomina = '".$idgenerar_nomina."'
												and idtrabajador = '".$idtrabajador."'
												and idconcepto = '".$bus_consulta_asociado["idconcepto"]."'
												and tabla = '".$bus_consulta_asociado["tabla"]."'")or die("4444".mysql_error());
					}
				}
			//echo eval("echo $formula;")." ";
				//if(eval("echo $formula;")){echo "si";}else{echo "no";}
			
			$conceptos[$k] = array($bus_consulta_asociado["tabla"], $bus_consulta_asociado["idconcepto"], $total, $idtrabajador);
			$k++;
			$total = 0;
			
			}
			
			}
			
			//echo "AQUIII";
			//var_dump($conceptos);
		
	//echo "AQUI";	
	}
	
	
	
	
	/// GUARDO LOS DATOS GENERALES DE LA CERTIFICACION 
	$sql_simular_nomina = mysql_query("select tn.idtipo_documento,
									  gn.descripcion,
									  gn.idbeneficiarios
												  				from 
												  			simular_nomina gn,
															tipo_nomina tn
																where 
															gn.idsimular_nomina = '".$idgenerar_nomina."'
															and tn.idtipo_nomina =  gn.idtipo_nomina")or die("AQUIIIII".mysql_error());
	$bus_simular_nomina = mysql_fetch_array($sql_simular_nomina);
	
	registra_transaccion('Se genero la nomina ('.$idgenerar_nomina.')',$login,$fh,$pc,'nomina',$conexion_db);
	
	$idtipo_documento = $bus_simular_nomina["idtipo_documento"];
	
	
	if($idcertificacion == "" || $idcertificacion == 0){
	$sql_cargar_certificacion = mysql_query("insert into certificacion_simular_nomina(tipo,
																			   fecha_elaboracion,
																			   idbeneficiarios,
																			   idcategoria_programatica,
																			   anio,
																			   idfuente_financiamiento,
																			   idtipo_presupuesto,
																			   idordinal,
																			   justificacion,
																			   estado,
																			   ubicacion,
																			   status,
																			   usuario,
																			   fechayhora)
																							VALUES('".$idtipo_documento."',
																						   			'".date("Y-m-d")."',
																									'".$bus_simular_nomina["idbeneficiarios"]."',
																									'0',
																									'".$_SESSION["anio_fiscal"]."',
																									'2',
																									'1',
																									'0',
																									'".$bus_simular_nomina["descripcion"]."',
																									'elaboracion',
																									'0',
																									'a',
																									'".$login."',
																									'".$fh."')")or die("ERROR CREANDO LA ORDEN DE COMPRA: ".mysql_error());
		$idcertificacion = mysql_insert_id();
	}
	
	/// GUARDO LOS DATOS GENERALES DE LA CERTIFICACION 
	
	//var_dump($conceptos);
	$primera = true;
	$sql_simular_nomina= mysql_query("select * from simular_nomina where idsimular_nomina = '".$idgenerar_nomina."'")or die("qqqqqqq".mysql_error());
		$bus_simular_nomina = mysql_fetch_array($sql_simular_nomina);
		if($bus_simular_nomina["idcertificacion_simular_nomina"] != 0){
			$sql_eliminar = mysql_query("update partidas_simular_nomina set monto = 0 where idcertificacion_simular_nomina = '".$idcertificacion."'")or die("gggg".mysql_error());	
			$sql_actualiza_totales = mysql_query("update certificacion_simular_nomina set 
														sub_total = 0,
														exento = 0,
														total = 0
														where idcertificacion_simular_nomina='".$idcertificacion."' ")or die("4: ".mysql_error());
			$sql_actualizar = mysql_query("update conceptos_simular_nomina set
											  				total = 0,
															precio_unitario = 0
											  				where idcertificacion_simular_nomina = '".$idcertificacion."'")or die("eweee".mysql_error());
		}
	
	//var_dump($conceptos);
	foreach($conceptos as $con){
		
		
		
		if($con[0] == "conceptos_nomina"){
			//echo "EL MONTO TOTAL ES: ".$con[2];
			$sql_concepto = mysql_query("select * from conceptos_nomina where idconceptos_nomina = '".$con[1]."'")or die("tttttt".mysql_error());
			$bus_concepto = mysql_fetch_array($sql_concepto);
			
			$tipo_concepto = $bus_concepto["tipo_concepto"];
			$idclasificador_presupuestario = $bus_concepto["idclasificador_presupuestario"];
			$idordinal = $bus_concepto["idordinal"];
			$idarticulos_servicios = $bus_concepto["idarticulos_servicios"];
			
			/*if($primera == true){
				$sql_buscar = mysql_query("delete from conceptos_simular_nomina 
										  where idcertificacion_simular_nomina = '".$idcertificacion."'");	
				$primera = false;
			}
			*/
			$codigo_concepto = $bus_concepto["codigo"];
			$descripcion_concepto = $bus_concepto["descripcion"];
			
			if($idarticulos_servicios == 0){
				
				$sql_tipo_concepto = mysql_query("select * from tipo_conceptos_nomina where idconceptos_nomina = '".$bus_concepto["tipo_concepto"]."'");
					$bus_tipo_concepto = mysql_fetch_array($sql_tipo_concepto);
					
					if($bus_tipo_concepto["afecta"] == "Asignacion"){
						$tipo_concepto = 1;
					}else if($bus_tipo_concepto["afecta"] == "Deduccion"){
						$tipo_concepto = 2;
					}else if($bus_tipo_concepto["afecta"] == "Aporte"){
						$tipo_concepto = 4;
					}else{
						$tipo_concepto = 3;
					}
				
			//if($tipo_concepto != 4){
				
				$sql_ingresar_articulo = mysql_query("insert into articulos_servicios(codigo,
																				  tipo,
																				  descripcion,
																				  idunidad_medida,
																				  idramo_articulo,
																				  idclasificador_presupuestario,
																				  idimpuestos,
																				  exento,
																				  status,
																				  tipo_concepto,
																				  usuario,
																				  fechayhora,
																				  idordinal)VALUES('".$codigo_concepto."',
																				  					'".$idtipo_documento."',
																									'".$descripcion_concepto."',
																									'7',
																									'12',
																									'".$idclasificador_presupuestario."',
																									'1',
																									'1',
																									'a',
																									'".$tipo_concepto."',
																									'".$login."',
																									'".$fh."',
																									'".$idordinal."')")or die("HOLAAAAA".mysql_error());
				
				$id = mysql_insert_id();
				//echo "ESTE ES EL ID:  ".$id;
				if($id != 0){
					$sql_actualizar = mysql_query("update conceptos_nomina set idarticulos_servicios = '".$id."' where idconceptos_nomina = '".$con[1]."'")or die("wwwww".mysql_error());	
					
					$sql_categoria_programatica = mysql_query("select no.idcategoria_programatica 
															  			from 
																	trabajador tr,
																	niveles_organizacionales no
																		where 
																	tr.idtrabajador = '".$con[3]."'
																	and no.idniveles_organizacionales = tr.centro_costo")or die("eeeeee".mysql_error());
					$bus_categoria_programatica = mysql_fetch_array($sql_categoria_programatica);
					ingresarMaterial($id, $idcertificacion, $bus_categoria_programatica["idcategoria_programatica"], 1, $con[2], 2, 1,$idclasificador_presupuestario,$idordinal);
				}
				
				//}// FIN DE SI ES PATRONAL
				
			}else{
					$id = $idarticulos_servicios;
					$sql_actualizar = mysql_query("update conceptos_nomina set idarticulos_servicios = '".$id."' where idconceptos_nomina = '".$con[1]."'")or die("aquiii".mysql_error());
					$sql_actualizar = mysql_query("update articulos_servicios set
												  				idclasificador_presupuestario = '".$idclasificador_presupuestario."'
																where idarticulos_servicios = '".$id."'");
					//echo "update conceptos_nomina set idarticulos_servicios = '".$id."' where idconceptos_nomina = '".$con[1]."'";
					
					$sql_categoria_programatica = mysql_query("select no.idcategoria_programatica 
															  			from 
																	trabajador tr,
																	niveles_organizacionales no
																		where 
																	tr.idtrabajador = '".$con[3]."'
																	and no.idniveles_organizacionales = tr.centro_costo")or die("alla".mysql_error());
					$bus_categoria_programatica = mysql_fetch_array($sql_categoria_programatica);
			
					$sql_busqueda = mysql_query("select * from conceptos_simular_nomina 
																where 
																idarticulos_servicios = '".$id."'
																and idcategoria_programatica = '".$bus_categoria_programatica["idcategoria_programatica"]."'
																and idcertificacion_simular_nomina = '".$idcertificacion."'")or die("ertyuui".mysql_error());
				
					
					$num_busqueda = mysql_num_rows($sql_busqueda);
					//echo $nummmm."....";
					if($num_busqueda > 0){
						$bus_busqueda = mysql_fetch_array($sql_busqueda);
						actualizarPrecioCantidad($id, $idcertificacion, $bus_categoria_programatica["idcategoria_programatica"], 1, $con[2], 2, 1,$idclasificador_presupuestario,$idordinal, $bus_busqueda["idconceptos_simular_nomina"], $con[3]);
					}else{
						ingresarMaterial($id, $idcertificacion, $bus_categoria_programatica["idcategoria_programatica"], 1, $con[2], 2, 1,$idclasificador_presupuestario,$idordinal);
					}
			}
			
			
				
				
			}else if($con[0] == "constantes_nomina"){
				// AQUI VA EL TEXTO SI ES UNA CONSTANTE	
				
				$sql_constante = mysql_query("select * from constantes_nomina where idconstantes_nomina = '".$con[1]."'")or die("AQUIII".mysql_error());
				$bus_constante = mysql_fetch_array($sql_constante);
				
				$idclasificador_presupuestario = $bus_constante["idclasificador_presupuestario"];
				$idordinal = $bus_constante["idordinal"];
				$idarticulos_servicios = $bus_constante["idarticulos_servicios"];
				
				
				
				$codigo_concepto = $bus_constante["codigo"];
				$descripcion_concepto = $bus_constante["descripcion"];
				
				
				
				
				
				if($idarticulos_servicios == 0){
					$sql_tipo_concepto = mysql_query("select * from tipo_conceptos_nomina where idconceptos_nomina = '".$bus_constante["afecta"]."'");
					$bus_tipo_concepto = mysql_fetch_array($sql_tipo_concepto);
					
					if($bus_tipo_concepto["afecta"] == "Asignacion"){
						$tipo_constante = 1;
					}else if($bus_tipo_concepto["afecta"] == "Deduccion"){
						$tipo_constante = 2;
					}else if($bus_tipo_concepto["afecta"] == "Aporte"){
						$tipo_constante = 4;
					}else{
						$tipo_constante = 3;
					}
					
					//if($tipo_constante != 4){
					
					$sql_ingresar_articulo = mysql_query("insert into articulos_servicios(codigo,
																				  tipo,
																				  descripcion,
																				  idunidad_medida,
																				  idramo_articulo,
																				  idclasificador_presupuestario,
																				  idimpuestos,
																				  exento,
																				  status,
																				  tipo_concepto,
																				  usuario,
																				  fechayhora,
																				  idordinal)VALUES('".$codigo_concepto."',
																				  					'".$idtipo_documento."',
																									'".$descripcion_concepto."',
																									'7',
																									'12',
																									'".$idclasificador_presupuestario."',
																									'1',
																									'1',
																									'a',
																									'".$tipo_constante."',
																									'".$login."',
																									'".$fh."',
																									'".$idordinal."')")or die("ERROR EN EL INSERT".mysql_error);
				
				$id = mysql_insert_id();
				if($id != 0){
					$sql_actualizar = mysql_query("update constantes_nomina set idarticulos_servicios = '".$id."' where idconstantes_nomina = '".$con[1]."'")or die("ERROR".mysql_error());	
					$sql_categoria_programatica = mysql_query("select no.idcategoria_programatica 
															  			from 
																	trabajador tr,
																	niveles_organizacionales no
																		where 
																	tr.idtrabajador = '".$con[3]."'
																	and no.idniveles_organizacionales = tr.centro_costo")or die("qaqqq".mysql_error());
					$bus_categoria_programatica = mysql_fetch_array($sql_categoria_programatica);
					
					
					$sql_consulta_articulos = mysql_query("select * from articulos_servicios where idarticulos_servicios = '".$id."'");
					$bus_consulta_articulos = mysql_fetch_array($sql_consulta_articulos);
					
					if($bus_consulta_articulos["tipo_concepto"] != 3){
						ingresarMaterial($id, $idcertificacion, $bus_categoria_programatica["idcategoria_programatica"], 1, $con[2], 2, 1,$idclasificador_presupuestario,$idordinal);
					}
					
					
				}
				
				//}
				
			}else{
					
					$id = $idarticulos_servicios;
					$sql_actualizar = mysql_query("update constantes_nomina set 
												  				idarticulos_servicios = '".$id."' where idconstantes_nomina = '".$con[1]."'")or die("AQUIIII".mysql_error());
					$sql_actualizar = mysql_query("update articulos_servicios set
												  				idclasificador_presupuestario = '".$idclasificador_presupuestario."'
																where idarticulos_servicios = '".$id."'")or die("qqqqqqq".mysql_error());
					//echo "update conceptos_nomina set idarticulos_servicios = '".$id."' where idconceptos_nomina = '".$con[1]."'";
					$sql_categoria_programatica = mysql_query("select no.idcategoria_programatica 
															  			from 
																	trabajador tr,
																	niveles_organizacionales no
																		where 
																	tr.idtrabajador = '".$con[3]."'
																	and no.idniveles_organizacionales = tr.centro_costo")or die("alla".mysql_error());
					$bus_categoria_programatica = mysql_fetch_array($sql_categoria_programatica);
					
					$sql_buscar = mysql_query("select * from conceptos_simular_nomina 
																			where 
																			idarticulos_servicios = '".$id."'
																			and idcategoria_programatica = '".$bus_categoria_programatica["idcategoria_programatica"]."' 
																			and idcertificacion_simular_nomina = '".$idcertificacion."'")or die("qqqqqq".mysql_error());
					$num_buscar= mysql_num_rows($sql_buscar);
					
					$sql_consulta_articulos = mysql_query("select * from articulos_servicios where idarticulos_servicios = '".$id."'");
					$bus_consulta_articulos = mysql_fetch_array($sql_consulta_articulos);
					
					if($bus_consulta_articulos["tipo_concepto"] != 3){
						if($num_buscar > 0){
							$bus_busqueda = mysql_fetch_array($sql_buscar);
							actualizarPrecioCantidad($id, $idcertificacion, $bus_categoria_programatica["idcategoria_programatica"], 1, $con[2], 2, 1,$idclasificador_presupuestario,$idordinal, $bus_busqueda["idconceptos_simular_nomina"], $con[3]);
						}else{
							ingresarMaterial($id, $idcertificacion, $bus_categoria_programatica["idcategoria_programatica"], 1, $con[2], 2, 1,$idclasificador_presupuestario,$idordinal);
						}
					}
					
					
			}	
			}
		}
		
		if($estado == "Procesado"){
			//$sql_eliminar = mysql_query("update partidas_simular_nomina set monto = 0 where idcertificacion_simular_nomina = '".$idcertificacion."'");
				$sql_actualizar_certificacion = mysql_query("update certificacion_simular_nomina
														set total = sub_total - exento
														where idcertificacion_simular_nomina = '".$idcertificacion."'");
				$sql_actualizar  = mysql_query("update simular_nomina 
							   				set estado='procesado', 
											fecha_procesado = '".date("Y-m-d")."',
											idcertificacion_simular_nomina = '".$idcertificacion."'
												where 
											idsimular_nomina = '".$idgenerar_nomina."'");
				//$sql_consulta = mysql_query("select numero_orden from certificacion_simular_nomina where idcertificacion_simular_nomina = '".$idcertificacion."'");
				//$bus_consulta = mysql_fetch_array($sql_consulta);
				echo "exito";	
			
		}else{
			echo $idcertificacion;	
			$sql_actualizar_certificacion = mysql_query("update certificacion_simular_nomina
														set total = sub_total - exento
														where idcertificacion_simular_nomina = '".$idcertificacion."'");
			$sql_actualizar  = mysql_query("update simular_nomina set
											idcertificacion_simular_nomina = '".$idcertificacion."'
												where 
											idsimular_nomina = '".$idgenerar_nomina."'");
		}
	}










// FIN DE LA FUNCION DE PROCESAR LA CERTIFICACION **************************************************************************************






function actualizarPrecioCantidad($id_articulo, $id_orden_compra, $id_categoria_programatica, $cantidad, $precio, $fuente_financiamiento, $tipo_presupuesto,$id_clasificador_presupuestario, $idordinal, $id_articulo_compra, $idtrabajador){

				
				
				$sql_actualizar = mysql_query("update conceptos_simular_nomina set
											  				total = total + '".$precio."',
															precio_unitario = precio_unitario + '".$precio."'
											  				where idconceptos_simular_nomina = '".$id_articulo_compra."'
															and idcertificacion_simular_nomina = '".$id_orden_compra."'");
				
				
				
				
				
				$sql_articulos_servicios = mysql_query("select * from articulos_servicios where idarticulos_servicios = '".$id_articulo."'")or die(mysql_error());
				$bus_articulos_servicios = mysql_fetch_array($sql_articulos_servicios);
				
				//$idordinal = $bus_articulos_servicios["idordinal"];
				
				
				$sql_conceptos_simular_nomina = mysql_query("select * from conceptos_simular_nomina where idconceptos_simular_nomina = '".$id_articulo_compra."'");
				$bus_conceptos_simular_nomina = mysql_fetch_array($sql_conceptos_simular_nomina);
				
				$id_categoria_programatica = $id_categoria_programatica;
				//$id_clasificador_presupuestario = $bus_articulos_servicios["idclasificador_presupuestario"];
			//echo $id_clasificador_presupuestario;
			
			$total_articulo_individual = $cantidad * $precio;
//			echo "TOTAL ARTICULO INDIVIDUAL: ".$total_articulo_individual;
			
			//echo "TIPO CONCEPTO: ".$bus_articulos_servicios["tipo_concepto"]."<br />";
			if($bus_articulos_servicios["tipo_concepto"] == 1){
				
				$monto_total = $total_articulo_individual;
				$exento = 0; 
			}else{
				$monto_total = 0;
				$exento = $total_articulo_individual;
			}
			
			// busco el precio y la cantidad anteriores para restarsela a los totales
			$sql_orden_compra_viejo = mysql_query("select * from certificacion_simular_nomina where idcertificacion_simular_nomina = '".$id_orden_compra."'");
			$bus_orden_compra_viejo = mysql_fetch_array($sql_orden_compra_viejo);
		
			$exento_viejo = $bus_orden_compra_viejo["exento"];
			$sub_total_viejo = $bus_orden_compra_viejo["sub_total"];
			
			
			
			// actualizo la tabla de articulos de la orden de compra con la nueva cantidad y el nuevo precio										

			
		
			
			// ACTUALIZO LOS TOTALES EN LA TABLA ORDEN_COMPRA_SERVICIO
			$total_anterior = $sub_total_viejo - $exento_viejo;
			$total_nuevo = $monto_total - $exento;
			$sql_actualiza_totales = mysql_query("update certificacion_simular_nomina set 
														sub_total = sub_total  + '".$monto_total."',
														exento = exento  + '".$exento."',
														total = total + '".$total_nuevo."'
														where idcertificacion_simular_nomina=".$id_orden_compra." ")or die("4: ".mysql_error());
			
			
			// CONSULTA DEL TOTAL DISPONIBLE EN EL PRESUPUESTO PARA SABER SI EL PRODUCTO PUEE SER PROCESADO O RECHAZADO POR FALTA DE DISPONIBILIDAD PRESUPUESTARIA
			
			
			$sql = mysql_query("select * from conceptos_simular_nomina where 
													idconceptos_simular_nomina = '".$id_articulo_compra."'")or die("5: ".mysql_error());
			$bus = mysql_fetch_array($sql);
			
			if($bus["estado"] == "aprobado" || $bus["estado"] == "sin disponibilidad"){ // en cualquiera de stos estados el articulo tiene partida en el maestro
					$sql_compra_servicio = mysql_query("select * from certificacion_simular_nomina where 
														idcertificacion_simular_nomina = '".$id_orden_compra."'")or die("6: ".mysql_error());
					$bus_compra_servicio = mysql_fetch_array($sql_compra_servicio);
					//echo "ID: ".$bus_compra_servicio["idcategoria_programatica"]." ";

					$partida_impuestos = 0;


	
				$sql_imputable = mysql_query("select total from conceptos_simular_nomina where idconceptos_simular_nomina = '".$id_articulo_compra."'")or die("12: ".mysql_error());
											
				$bus_imputable = mysql_fetch_array($sql_imputable);
				$total_imputable = $bus_imputable["total"];
				//echo "TOTAL: ".$total_imputable. "ID ";
				//echo $bus_imputable["totales"];
				//echo $bus_imputable["exentos"];


	if($bus_articulos_servicios["tipo_concepto"] == 1){

	
				$sql_maestro = mysql_query("select * from maestro_presupuesto where anio = ".$_SESSION["anio_fiscal"]." 
												and idcategoria_programatica = '".$id_categoria_programatica."' 
												and idclasificador_presupuestario = '".$id_clasificador_presupuestario."'
												and idfuente_financiamiento = '".$fuente_financiamiento."'
												and idtipo_presupuesto = '".$tipo_presupuesto."'
												and idordinal = '".$idordinal."'")or die("8: ".mysql_error());

				
				$bus_maestro = mysql_fetch_array($sql_maestro);
				
				//$disponible = $bus_maestro["monto_actual"]-$bus_maestro["total_compromisos"];
				$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
				if($total_imputable > $disponible){
					$sql_partida = mysql_query("update partidas_simular_nomina set estado = 'sobregiro', 
																		monto = monto + '".$total_articulo_individual."',
																		monto_original = monto_original + '".$total_articulo_individual."' 
																		where 
																		idcertificacion_simular_nomina = '".$id_orden_compra."'
																		and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'")or die("15: ".mysql_error());
					$estado = "sin disponibilidad";
				}else{
					
					$sql_partida = mysql_query("update partidas_simular_nomina set estado = 'disponible', 
																			monto = monto + '".$total_articulo_individual."',
																			monto_original = monto_original + '".$total_articulo_individual."' 
																			where 
																			idcertificacion_simular_nomina = ".$id_orden_compra."
																			and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'")or die("16: ".mysql_error());
				
				
					$estado = "aprobado";
				}
			}else{
				//echo "ALLA";
				$estado = "aprobado";
			}
		}else{
			$estado = "aprobado";
		}
			// CONSULTA DEL TOTAL DISPONIBLE EN EL PRESUPUESTO PARA SABER SI EL PRODUCTO PUEE SER PROCESADO O RECHAZADO POR FALTA DE DISPONIBILIDAD PRESUPUESTARIA		
			
			$sql2 = mysql_query("update conceptos_simular_nomina set estado = '".$estado."' 
																where idconceptos_simular_nomina = '".$id_articulo_compra."'")or die("17: ".mysql_error());
			
		if($sql2){
				registra_transaccion("Actualizar Precio Cantidad de Orden de Compra (".$id_articulo_compra.")",$login,$fh,$pc,'certificacion_simular_nominas');

				//echo "exito";
		}else{
				registra_transaccion("Actualizar Precio Cantidd de Orden de Compra ERROR (".$id_articulo_compra.")",$login,$fh,$pc,'certificacion_simular_nominas');

				//echo $sql2." MYSQL ERROR: ".mysql_error();
		}
	
}









function ingresarMaterial($id_material, $id_orden_compra, $id_categoria_programatica, $cantidad, $precio_unitario, $fuente_financiamiento, $tipo_presupuesto,$id_clasificador_presupuestario, $idordinal){
	
	
	$sql = mysql_query("select * from conceptos_simular_nomina where 
													idarticulos_servicios = ".$id_material." 
													and idcertificacion_simular_nomina = ".$id_orden_compra." 
													and idcategoria_programatica = '".$id_categoria_programatica."'")or die(mysql_error());
	
	$sql_articulos_servicios = mysql_query("select * from articulos_servicios where idarticulos_servicios = '".$id_material."'");
	
	$bus_articulos_servicios = mysql_fetch_array($sql_articulos_servicios);
	//$idordinal = $bus_articulos_servicios["idordinal"];
	
	
	//$id_clasificador_presupuestario = $bus_articulos_servicios["idclasificador_presupuestario"];
	$num = mysql_num_rows($sql);
	
	// SI EL ARTICULO NO EXISTE ENTRE LOS ARTICULOS CARGADOS
	if($num == 0){

		$total_articulo_individual = $cantidad * $precio_unitario;
		$sql_orden = mysql_query("select * from certificacion_simular_nomina where idcertificacion_simular_nomina = ".$id_orden_compra."");
		$bus_orden = mysql_fetch_array($sql_orden);
		
		//BUSCO EL IMPUESTO QUE SE LE APLICA AL ARTICULO PARA SABER SI TIENE PARTIDA PROPIA O SE VA A CARGAR A LA PARTIDA DEL ARTICULO
	

		// AGREGO EL ARTICULO DE LA SOLICITUD DE COTIZACION A LA ORDEN DE COMPRA
		
		if($bus_articulos_servicios["tipo_concepto"] == 1){
			$monto_total = $total_articulo_individual;
			$exento = 0; 
		}else{
			$monto_total = 0;
			$exento = $total_articulo_individual;
		}
		
		//echo "MONTO TOTAL: ".$monto_total;
		//echo "EXENTO: ".$exento;
		

		
		$sql = mysql_query("insert into conceptos_simular_nomina (idcertificacion_simular_nomina,
																	idarticulos_servicios,
																	idcategoria_programatica,
																	cantidad,
																	precio_unitario,
																	porcentaje_impuesto,
																	impuesto,
																	total,
																	exento,
																	status,
																	usuario,
																	fechayhora,
																	idpartida_impuesto)values(
																	'".$id_orden_compra."',
																	'".$id_material."',
																	'".$id_categoria_programatica."',
																	'".$cantidad."',
																	'".$precio_unitario."',
																	'".$porcentaje_impuesto."',
																	'".$total_impuesto_individual."',
																	'".$monto_total."',
																	'".$exento."',																	
																	'a',
																	'".$login."',
																	'".date("Y-m-d H:i:s")."',
																	'".$id_partida_impuesto."'
																	)")or die("AQUIIIIIIII ".mysql_error());

		$id_ultimo_generado = mysql_insert_id(); 	// OBTENGO EL ULTIMO ID INGRESADO EN LA TABLA DE ARTICULOS PARA ACTUALIZARLE EL ESTADO DESPUES DE ANALIZAR LA
													//DISPONIBILIDAD DE LAS PARTIDAS
		$total_general =  $monto_total - $exento;
		$actualiza_totales = mysql_query("update certificacion_simular_nomina set 	
											sub_total = sub_total + '".$monto_total."',
											exento = exento + '".$exento."',
											total = total + '".$total_general."'
											where idcertificacion_simular_nomina=".$id_orden_compra." ")or die ("11111111 ".mysql_error());
		
	
		$sql_articulos = mysql_query("select * from articulos_servicios where idarticulos_servicios = ".$id_material."");
		$bus_articulos = mysql_fetch_array($sql_articulos);
		// 	se realiza la consulta en la tabla maestro para verificar si hay partidas para este articulo, de lo contrario el articulo se coloca en un estado de rechazado para que
		//	en la tabla aparesca la fila en rojo y muestre al usuario que no hay partidas para ese articulo
	if($bus_articulos_servicios["tipo_concepto"] == 1){	
		
		$sql_maestro = mysql_query("select * from maestro_presupuesto where anio = '".$_SESSION["anio_fiscal"]."' 
														and idcategoria_programatica = '".$id_categoria_programatica."' 
														and idclasificador_presupuestario = '".$id_clasificador_presupuestario."'
														and idfuente_financiamiento = '".$fuente_financiamiento."'
														and idtipo_presupuesto = '".$tipo_presupuesto."'
														and idordinal = '".$idordinal."'"
																		)or die($anio."ERROR SQL MAESTRO: ".mysql_error());

		$num_maestro = mysql_num_rows($sql_maestro);
			
			if($num_maestro == 0){ // VALIDO QUE EXISTA UNA PARTIDA EN EL MAESTRO DE PRESUPUESTO PARA ESE ARTICULO
				$estado = "rechazado";  // si no tiene partida en el maestro de presupuesto le coloca RECHAZADO para pintarlo de color ROJO
			}else{
				$bus_maestro = mysql_fetch_array($sql_maestro);
				//$disponible = $bus_maestro["monto_actual"]-$bus_maestro["total_compromisos"];
				$disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
				//echo $bus_maestro["idRegistro"];
				// de lo contrario verifica si en la partida que esta asignada este articulo hay disponibilidad de dinero para comprar la cantidad que se esta pidiendo, si para 
				// esta partida no hay disponibilidad se coloca el estado en SIN DISPONIBILIDAD para que al mostrar el articulo en color en AMARILLO para indicar que no 
				// hay presupuesto para este articulo
				
				$sql_imputable = mysql_query("select (cantidad*precio_unitario) as total from conceptos_simular_nomina where idconceptos_simular_nomina = '".$id_ultimo_generado."'");
				// SUMO EL TOTAL DE TODOS LOS ARTICULOS QUE ESTAN IMPUTANDO ESA PARTIDA PARA COMPARARLO CON EL DISPONIBLE EN EL MAESTRO DE PRESUPUESTO
				$bus_imputable = mysql_fetch_array($sql_imputable);
				$total_imputable = $bus_imputable["total"]; 
				
				if($total_imputable > $disponible){ // si el total a imputar es mayor al disponible en la partida
					//echo "ESTA SOBREGIRADOOOOOOOOO";
					$estado = "sin disponibilidad";
					$estado_partida = "sobregiro";
				}else{
					//si nada de esto sucede se coloca el estado en aprobado y el material se muestra normalmente
					$estado = "aprobado";
					$estado_partida = "disponible";
				}
				/*echo "select * from partidas_simular_nomina where idcertificacion_simular_nomina=".$id_orden_compra." 
																		and idmaestro_presupuesto = ".$bus_maestro["idRegistro"]."";*/
					
				$sql_partidas_orden_compra=mysql_query("select * from partidas_simular_nomina where 
																		idcertificacion_simular_nomina='".$id_orden_compra."' 
																		and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'") 
																	or die("66666 ".mysql_error());
				$num=mysql_num_rows($sql_partidas_orden_compra);
				
				if ($num==0){ // SI NO EXISTE LA PARTIDA LA INGRESO
					$ingresar_partida=mysql_query("insert into partidas_simular_nomina (idcertificacion_simular_nomina, 
																								idmaestro_presupuesto,
																								monto,
																								monto_original,
																								estado,
																								status,
																								usuario,
																								fechayhora) 
																							values (".$id_orden_compra.",
																									".$bus_maestro["idRegistro"].",
																									".$total_imputable.",
																									".$total_imputable.",
																									'".$estado_partida."',
																									'a',
																									'".$login."',
																									'".date("Y-m-d H:i:s")."')")
																								or die("ERROR GUARDANDO PARTIDAS:". mysql_error());
				}else{ // DE LO CONTRARIO LA ACTUALIZO
					//echo "AQUI";
					$actualiza_partida=mysql_query("update partidas_simular_nomina set 
																		monto = monto + '".$total_imputable."',
																		monto_original = monto_original + '".$total_imputable."',
																		estado='".$estado_partida."' 
																		where idcertificacion_simular_nomina='".$id_orden_compra."' 
																		and idmaestro_presupuesto = '".$bus_maestro["idRegistro"]."'")
																		or die ($total_item."ERROR MODIFICANDO PARTIDAS: ".mysql_error());
				}														
	
			}
		}else{ // SI ES DEDUCCION ******************************************************************
			$estado = "disponible";
		}
			// actualizo el estado del material ingresado				
			$sql_update_articulos_compras = mysql_query("update conceptos_simular_nomina set estado = '".$estado."' 
																where idconceptos_simular_nomina = ".$id_ultimo_generado."");



		
		if($sql){
		registra_transaccion("Ingresar Material Individual en Orden de Compra (".$id_ultimo_generado.")",$login,$fh,$pc,'certificacion_simular_nominas');
			//echo "exito";
		}else{
			//echo "fallo";
		}
}else{
//echo "existe";
}
	
}









if($ejecutar == "validarErroresConceptos"){
	$sql_articulos_certificacion_simular_nomina = mysql_query("select * from conceptos_simular_nomina,  
																		unidad_medida, 
																		articulos_servicios, 
																		categoria_programatica
									 where 
									 	conceptos_simular_nomina.idcertificacion_simular_nomina = '".$id_orden_compra."' 
									  	and articulos_servicios.idarticulos_servicios = conceptos_simular_nomina.idarticulos_servicios 
										and articulos_servicios.idunidad_medida = unidad_medida.idunidad_medida
										and categoria_programatica.idcategoria_programatica = conceptos_simular_nomina.idcategoria_programatica 
										order by categoria_programatica.codigo,conceptos_simular_nomina.idconceptos_simular_nomina")or die(mysql_error());	
	$error= "no";
	while($bus_articulos_certificacion_simular_nomina = mysql_fetch_array($sql_articulos_certificacion_simular_nomina)){
		if($bus_articulos_certificacion_simular_nomina["estado"] == "rechazado" || $bus_articulos_certificacion_simular_nomina["estado"] == "sin disponibilidad"){
			$error = "si";	
		}
	}
	echo $error;
}










if($ejecutar == "consultarConceptos"){
$sql_suma_asignaciones = mysql_query("select SUM(conceptos_simular_nomina.total) as total_asignaciones
											from 
											articulos_servicios, 
											conceptos_simular_nomina 
											where 
											conceptos_simular_nomina.idcertificacion_simular_nomina = '".$id_orden_compra."'
											and articulos_servicios.idarticulos_servicios = conceptos_simular_nomina.idarticulos_servicios
											and articulos_servicios.tipo_concepto = 1")or die(mysql_error());
	$bus_suma_asignaciones = mysql_fetch_array($sql_suma_asignaciones);
	
	
	$sql_suma_deducciones = mysql_query("select SUM(conceptos_simular_nomina.precio_unitario) as total_deducciones
													 	from 
														articulos_servicios, 
														conceptos_simular_nomina 
														where 
														conceptos_simular_nomina.idcertificacion_simular_nomina = '".$id_orden_compra."'
														and articulos_servicios.idarticulos_servicios = conceptos_simular_nomina.idarticulos_servicios
														and articulos_servicios.tipo_concepto = 2")or die(mysql_error());
	$bus_suma_deducciones = mysql_fetch_array($sql_suma_deducciones);




	if($tipo == "detallado"){
	
	
	$sql_articulos_orden_compra_servicio = mysql_query("select * from conceptos_simular_nomina,  
																		unidad_medida, 
																		articulos_servicios, 
																		categoria_programatica
									 where 
									 	conceptos_simular_nomina.idcertificacion_simular_nomina = '".$id_orden_compra."'
									  	and articulos_servicios.idarticulos_servicios = conceptos_simular_nomina.idarticulos_servicios 
										and articulos_servicios.idunidad_medida = unidad_medida.idunidad_medida
										and categoria_programatica.idcategoria_programatica = conceptos_simular_nomina.idcategoria_programatica 
										order by articulos_servicios.tipo_concepto, 
										categoria_programatica.codigo,
										conceptos_simular_nomina.idconceptos_simular_nomina")or die(mysql_error());
	
	$num = mysql_num_rows($sql_articulos_orden_compra_servicio);
	
	
	
	
	
	?>
    <table cellpadding="3" cellspacing="10">
        <tr>
            <td style="cursor:pointer" bgcolor="#EAEAEA" onclick="consultarConceptos('<?=$id_orden_compra?>', 'resumido')"><strong>Resumido</strong></td>
            <td style="cursor:pointer" bgcolor="#EAEAEA" onclick="consultarConceptos('<?=$id_orden_compra?>', 'detallado')"><strong>Detallado</strong></td>
        </tr>
    </table>
    <table style="margin-left:8px">
        <tr>
            <td style="background-color:#9CF" width="150px" align="center"><strong>Asignaciones</strong></td>
            <td style="background-color:#FFC" width="150px" align="center"><strong>Deducciones</strong></td>
            <td  align="center" bgcolor="#EAEAEA"><strong>Asignaciones: </strong><?=number_format($bus_suma_asignaciones["total_asignaciones"],2,",",".")?></td>
            <td align="center" bgcolor="#EAEAEA">&nbsp;&nbsp;<strong>Deducciones: </strong><?=number_format($bus_suma_deducciones["total_deducciones"],2,",",".")?></td>
            <td align="center" bgcolor="#EAEAEA">&nbsp;&nbsp;<strong>Total a Pagar: </strong><?=number_format($bus_suma_asignaciones["total_asignaciones"]-$bus_suma_deducciones["total_deducciones"],2,",",".")?></td>
        </tr>
    </table>
    <table width="98%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
<thead>
          <tr>
            <?
            if($bus_orden["duplicados"] == 1){
			?>
			<td class="Browse"><div align="center">Duplicados</div></td>
			<?
			}
			?>
            <td class="Browse"><div align="center">Categoria</div></td>
            <td class="Browse"><div align="center">Tipo</div></td>
            <td class="Browse"><div align="center">Descripcion</div></td>
            <td class="Browse"><div align="center">Monto</div></td>
			<?
            if($bus_orden["estado"] == "elaboracion"){
            ?>
            <td class="Browse" colspan="2"><div align="center">Acciones</div></td>
			<?
            }
            ?>
          </tr>
          </thead>
          <? 
		  
          while($bus_articulos_orden_compra_servicio = mysql_fetch_array($sql_articulos_orden_compra_servicio)){
			
            if($bus_articulos_orden_compra_servicio["tipo_concepto"] == 1){
				$color = "#9CF";
				$tipo_concepto =  "Asignacion";
			}else if($bus_articulos_orden_compra_servicio["tipo_concepto"] == 2){
				$tipo_concepto =  "Deduccion";
				$color = "#FFC";
			}else if($bus_articulos_orden_compra_servicio["tipo_concepto"] == 4){
				$tipo_concepto =  "Aporte Patronal";
			}else{
				$tipo_concepto = "Neutro";	
			}
		if($tipo_concepto != "Aporte Patronal"){	
          	if($bus_articulos_orden_compra_servicio["estado"] == "rechazado"){
			?>
			<tr bgcolor='#FF0000' onMouseOver="setRowColor(this, 0, 'over', '#FF0000', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FF0000', '#EAFFEA', '#FFFFAA')">
			<?
			}else if($bus_articulos_orden_compra_servicio["estado"] == "sin disponibilidad"){
			?>
			<tr bgcolor="#FFFF00" onMouseOver="setRowColor(this, 0, 'over', '#FFFF00', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FFFF00', '#EAFFEA', '#FFFFAA')">
			<?
			}else{
			?>
			<tr style="background-color:<?=$color?>" onMouseOver="setRowColor(this, 0, 'over', '<?=$color?>', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '<?=$color?>', '#EAFFEA', '#FFFFAA')">
			<?
			
			}
		  ?>
          <?
          if($bus_orden["duplicados"] == 1){
			  if($bus_articulos_orden_compra_servicio["duplicado"] == 1){
			  ?>
				<td class='Browse' align='center'><img src="imagenes/advertencia.png" title="Articulo Duplicado"></td>
			   <?
			   }else{
			   	?>
				<td class='Browse' align='center'>&nbsp;</td>
			   <?
			   }
		   }
		   ?>
           <td class='Browse' align='left'><?=$bus_articulos_orden_compra_servicio["codigo"]?></td>
            <td class='Browse' align='left'>
			<?=$tipo_concepto?>
            </td>
             <td class='Browse' align='left'><?=$bus_articulos_orden_compra_servicio[29]?> 
              <div align="right"></div></td>
            
            
          <td class="Browse" align="right">
				<? 
				echo number_format($bus_articulos_orden_compra_servicio["precio_unitario"],2,',','.');
                ?>             </td>
				<?
                if($bus_orden["estado"] == "elaboracion"){
				?>
            <td class='Browse' align="center">
			<a href="javascript:;" onclick=""><a href="javascript:;" onclick=""><img src="imagenes/refrescar.png" onclick=" 
                                actualizarPrecioCantidad(<?=$bus_articulos_orden_compra_servicio["idcertificacion_simular_nomina"]?>, 
                                document.getElementById('precio<?=$bus_articulos_orden_compra_servicio["idconceptos_simular_nomina"]?>').value,
                                document.getElementById('cantidad<?=$bus_articulos_orden_compra_servicio["idconceptos_simular_nomina"]?>').value, 
                                <?=$bus_articulos_orden_compra_servicio["idarticulos_servicios"]?>, 
                                <?=$bus_articulos_orden_compra_servicio["idconceptos_simular_nomina"]?>, 
                                document.getElementById('anio').value,
                                document.getElementById('fuente_financiamiento').value,
                                document.getElementById('tipo_presupuesto').value,
                                document.getElementById('id_ordinal').value,
                                document.getElementById('contribuyente_ordinario').value)" 
                                title="Actualizar Precio y Cantidad" /></a></td>  
<td class='Browse' align="center">
                    <a href="javascript:;" onClick="eliminarMateriales(<?=$bus_articulos_orden_compra_servicio["idconceptos_simular_nomina"]?>, <?=$bus_articulos_orden_compra_servicio["idconceptos_simular_nomina"]?>, <?=$bus_articulos_orden_compra_servicio["idsolicitud_cotizacion"]?>, document.getElementById('id_categoria_programatica').value, document.getElementById('anio').value, document.getElementById('fuente_financiamiento').value, document.getElementById('tipo_presupuesto').value, document.getElementById('id_ordinal').value, document.getElementById('contribuyente_ordinario').value)">
           			<img src="imagenes/delete.png" title="Eliminar Materiales">           		</a>            </td>
              <?
                }
				?>
          </tr>
          <?
          }
		  
		  }
		  
          ?>
        </table>
        <?	
		
	}else{
	// AQUI VA SI ES RESUMIDO	 **********************************************************************************************************
		$sql_asignaciones = mysql_query("select SUM(conceptos_simular_nomina.total) as total,
												SUM(conceptos_simular_nomina.cantidad) as cantidad,
												SUM(conceptos_simular_nomina.precio_unitario) as precio_unitario,
												articulos_servicios.descripcion,
												articulos_servicios.tipo_concepto, 
												conceptos_simular_nomina.estado
																		from 
																		conceptos_simular_nomina, 
																		unidad_medida, 
																		articulos_servicios, 
																		categoria_programatica
									 where 
									 	conceptos_simular_nomina.idcertificacion_simular_nomina = '".$id_orden_compra."'
									  	and articulos_servicios.idarticulos_servicios = conceptos_simular_nomina.idarticulos_servicios 
										and articulos_servicios.idunidad_medida = unidad_medida.idunidad_medida
										and categoria_programatica.idcategoria_programatica = conceptos_simular_nomina.idcategoria_programatica 
										group by articulos_servicios.idarticulos_servicios
										order by articulos_servicios.tipo_concepto")or die(mysql_error());
	?>
	
	<table cellpadding="3" cellspacing="10">
        <tr>
        <td style="cursor:pointer" bgcolor="#EAEAEA" onclick="consultarConceptos('<?=$id_orden_compra?>', 'resumido')"><strong>Resumido</strong></td>
        <td style="cursor:pointer" bgcolor="#EAEAEA" onclick="consultarConceptos('<?=$id_orden_compra?>', 'detallado')"><strong>Detallado</strong></td>
        </tr>
    </table>
    <table style="margin-left:8px">
        <tr>
        <td style="background-color:#9CF" width="150px" align="center"><strong>Asignaciones</strong></td>
        <td style="background-color:#FFC" width="150px" align="center"><strong>Deducciones</strong></td>
        <td  align="center" bgcolor="#EAEAEA"><strong>Asignaciones: </strong><?=number_format($bus_suma_asignaciones["total_asignaciones"],2,",",".")?></td>
        <td align="center" bgcolor="#EAEAEA">&nbsp;&nbsp;<strong>Deducciones: </strong><?=number_format($bus_suma_deducciones["total_deducciones"],2,",",".")?></td>
        <td align="center" bgcolor="#EAEAEA">&nbsp;&nbsp;<strong>Total a Pagar: </strong><?=number_format($bus_suma_asignaciones["total_asignaciones"]-$bus_suma_deducciones["total_deducciones"],2,",",".")?></td>
        </tr>
    </table>
    <table width="98%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
<thead>
          <tr>
            <?
            if($bus_orden["duplicados"] == 1){
			?>
			<td class="Browse"><div align="center">Duplicados</div></td>
			<?
			}
			?>
            <!-- <td class="Browse"><div align="center">Categoria</div></td> -->
            <td class="Browse"><div align="center">Tipo</div></td>
            <td class="Browse"><div align="center">Descripcion</div></td>
            <td class="Browse"><div align="center">Monto</div></td>
			<?
            if($bus_orden["estado"] == "elaboracion"){
            ?>
            <td class="Browse" colspan="2"><div align="center">Acciones</div></td>
			<?
            }
            ?>
          </tr>
          </thead>
          <? 
		  
          while($bus_articulos_orden_compra_servicio = mysql_fetch_array($sql_asignaciones)){
			
            if($bus_articulos_orden_compra_servicio["tipo_concepto"] == 1){
				$color = "#9CF";
				$tipo_concepto =  "Asignacion";
			}else if($bus_articulos_orden_compra_servicio["tipo_concepto"] == 2){
				$tipo_concepto =  "Deduccion";
				$color = "#FFC";
			}else if($bus_articulos_orden_compra_servicio["tipo_concepto"] == 4){
				$tipo_concepto =  "Aporte Patronal";
			}else{
				$tipo_concepto = "Neutro";	
			}
			
			if($tipo_concepto != "Aporte Patronal"){
          	if($bus_articulos_orden_compra_servicio["estado"] == "rechazado"){
			?>
			<tr bgcolor='#FF0000' onMouseOver="setRowColor(this, 0, 'over', '#FF0000', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FF0000', '#EAFFEA', '#FFFFAA')">
			<?
			}else if($bus_articulos_orden_compra_servicio["estado"] == "sin disponibilidad"){
			?>
			<tr bgcolor="#FFFF00" onMouseOver="setRowColor(this, 0, 'over', '#FFFF00', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FFFF00', '#EAFFEA', '#FFFFAA')">
			<?
			}else{
			?>
			<tr style="background-color:<?=$color?>" onMouseOver="setRowColor(this, 0, 'over', '<?=$color?>', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '<?=$color?>', '#EAFFEA', '#FFFFAA')">
			<?
			
			}
		  ?>
          <?
          if($bus_orden["duplicados"] == 1){
			  if($bus_articulos_orden_compra_servicio["duplicado"] == 1){
			  ?>
				<td class='Browse' align='center'><img src="imagenes/advertencia.png" title="Articulo Duplicado"></td>
			   <?
			   }else{
			   	?>
				<td class='Browse' align='center'>&nbsp;</td>
			   <?
			   }
		   }
		   ?>
         <!--  <td class='Browse' align='left'><?=$bus_articulos_orden_compra_servicio["codigo"]?></td>-->
            <td class='Browse' align='left'>
			<?=$tipo_concepto?>
            </td>
             <td class='Browse' align='left'><?=$bus_articulos_orden_compra_servicio["descripcion"]?> 
              <div align="right"></div></td>
            
            
          <td class="Browse" align="right">
				<?
				echo number_format($bus_articulos_orden_compra_servicio["precio_unitario"],2,',','.');
                ?>             </td>
				<?
                if($bus_orden["estado"] == "elaboracion"){
				?>
            <td class='Browse' align="center">
			<a href="javascript:;" onclick=""><a href="javascript:;" onclick=""><img src="imagenes/refrescar.png" onclick=" 
                                actualizarPrecioCantidad(<?=$bus_articulos_orden_compra_servicio["idcertificacion_simular_nomina"]?>, 
                                document.getElementById('precio<?=$bus_articulos_orden_compra_servicio["idconceptos_simular_nomina"]?>').value,
                                document.getElementById('cantidad<?=$bus_articulos_orden_compra_servicio["idconceptos_simular_nomina"]?>').value, 
                                <?=$bus_articulos_orden_compra_servicio["idarticulos_servicios"]?>, 
                                <?=$bus_articulos_orden_compra_servicio["idconceptos_simular_nomina"]?>, 
                                document.getElementById('anio').value,
                                document.getElementById('fuente_financiamiento').value,
                                document.getElementById('tipo_presupuesto').value,
                                document.getElementById('id_ordinal').value,
                                document.getElementById('contribuyente_ordinario').value)" 
                                title="Actualizar Precio y Cantidad" /></a></td>  
<td class='Browse' align="center">
                    <a href="javascript:;" onClick="eliminarMateriales(<?=$bus_articulos_orden_compra_servicio["idcertificacion_simular_nomina"]?>, <?=$bus_articulos_orden_compra_servicio["idconceptos_simular_nomina"]?>, <?=$bus_articulos_orden_compra_servicio["idsolicitud_cotizacion"]?>, document.getElementById('id_categoria_programatica').value, document.getElementById('anio').value, document.getElementById('fuente_financiamiento').value, document.getElementById('tipo_presupuesto').value, document.getElementById('id_ordinal').value, document.getElementById('contribuyente_ordinario').value)">
           			<img src="imagenes/delete.png" title="Eliminar Materiales">           		</a>            </td>
              <?
                }
				?>
          </tr>
          <?
		  }
          }
          ?>
        </table>
    
    
    
    
	
	<?
	
	
	
	
	}
}












if($ejecutar == "consultarPartidas"){
	
$sql_orden = mysql_query("select * from certificacion_simular_nomina where idcertificacion_simular_nomina = '".$id_orden_compra."'");
$bus_orden = mysql_fetch_array($sql_orden);
//and idclasificador_presupuestario = ".$bus_orden["idclasificador_presupuestario"]." 

$sql_partidas = mysql_query("select * from partidas_simular_nomina where idcertificacion_simular_nomina = '".$id_orden_compra."' order by idmaestro_presupuesto");
																		
$num_partidas = mysql_num_rows($sql_partidas);
if($num_partidas != 0){
	?>
        <table width="98%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
<thead>
          <tr>
            <td class="Browse"><div align="center">Categoria</div></td>
            <td class="Browse" colspan="4"><div align="center">Partida</div></td>
            <td class="Browse"><div align="center">Descripci&oacute;n</div></td>
            <td class="Browse"><div align="center">Disponible</div></td>
            <td class="Browse"><div align="center">Monto a Comprometer</div></td>
          </tr>
          </thead>
          <? 
          while($bus_partidas = mysql_fetch_array($sql_partidas)){
		  
		  $sql_maestro = mysql_query("select * from maestro_presupuesto where idRegistro = ".$bus_partidas["idmaestro_presupuesto"]."");
		  $bus_maestro = mysql_fetch_array($sql_maestro);
		  
		  
		  
		  
          	if($bus_partidas["estado"] == "sobregiro"){
		  ?>
			<tr bgcolor="#FFFF00" onMouseOver="setRowColor(this, 0, 'over', '#FFFF00', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FFFF00', '#EAFFEA', '#FFFFAA')">
			<?
			}else if($bus_partidas["estado"] == "disponible"){
			?>
			
            <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
			<?
			}
			
			
          $sql_clasificador = mysql_query("select * from clasificador_presupuestario where idclasificador_presupuestario = '".$bus_maestro["idclasificador_presupuestario"]."'");
		  $bus_clasificador = mysql_fetch_array($sql_clasificador);
		  ?>
            
            
            
            <td class='Browse' align='left'>
			<?
            $sql_categoria_programatica = mysql_query("select * from categoria_programatica where idcategoria_programatica = '".$bus_maestro["idcategoria_programatica"]."'");
			$bus_categoria_programatica = mysql_fetch_array($sql_categoria_programatica);
			echo $bus_categoria_programatica["codigo"];
			?>
            </td>
            <td class='Browse' align='left'><?=$bus_clasificador["partida"]?></td>
            <td class='Browse' align='left'><?=$bus_clasificador["generica"]?></td>
            <td class='Browse' align='left'><?=$bus_clasificador["especifica"]?></td>
            <td class='Browse' align='left'><?=$bus_clasificador["sub_especifica"]?></td>
            <td class='Browse' align='left'><?=$bus_clasificador["denominacion"]?></td>

    	      <td class='Browse' align="right"><?=number_format(consultarDisponibilidad($bus_maestro["idRegistro"]),2,',','.')?></td>
	          <td class='Browse' align='right'><?=number_format($bus_partidas["monto"],2,',','.')?></td>
          </tr>
          <?
          }
          ?>
        </table>																	
<?
    }else{
	echo "No hay Partidas Asociadas";
    }																		
}















if($ejecutar == "modificarNomina"){
	$sql_actualizar= mysql_query("update simular_nomina set descripcion = '".$justificacion."' where idsimular_nomina ='".$idgenerar_nomina."'");
}




if($ejecutar == "consultarNominas"){
	$sql_consulta = mysql_query("select * from generar_nomina where idtipo_nomina = '".$idtipo_nomina."'");
	?>
    
	<select name="idcomparar" id="idcomparar">
	<?
	$bus_consulta = mysql_fetch_array($sql_consulta);
		
	while($bus_consulta = mysql_fetch_array($sql_consulta)){
		$sql_periodo = mysql_query("select * from rango_periodo_nomina where idrango_periodo_nomina = '".$bus_consulta["idperiodo"]."'")or die(mysql_error());
		$bus_periodo = mysql_fetch_array($sql_periodo);
		?>
		<option value="<?=$bus_consulta["idperiodo"]?>"><?=$bus_periodo["desde"]." - ".$bus_periodo["hasta"]?></option>
		<?
	}	
	?>
	</select>
	<?
}
?>
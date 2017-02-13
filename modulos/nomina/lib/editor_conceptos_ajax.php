<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
Conectarse();
extract($_POST);


	function validar_formula($destino){
		//echo "aqui";
		$sql_consulta = mysql_query("select * from temporal_conceptos where idsession = '".session_id()."'
																		and destino = '".$destino."' 
																		order by orden asc")or die(mysql_error());
		$i = 0;
		$cantidad = mysql_num_rows($sql_consulta);
		while($bus_consulta = mysql_fetch_array($sql_consulta)){
			$valores[$i][0] = $bus_consulta["valor_oculto"];
			$valores[$i][1] = $bus_consulta["idtemporal_conceptos"];
			$i++;
		}
		
		$parentesis_apertura=0;
		$parentesis_cierre=0;
		$bandera = false;
		
		
		for($i=0;$i<=$cantidad;$i++){
			$partes = explode("_", $valores[$i][0]);
			
			if($partes[1] == "("){
				$parentesis_apertura++;
				if($parentesis_apertura > $parentesis_cierre){
					$id_parentesis = $valores[$i][1];
				}
			}
			if($partes[1] == ")"){
				$parentesis_cierre++;
				if($parentesis_apertura < $parentesis_cierre){
					$id_parentesis = $valores[$i][1];
				}
			}
			
			
			if($partes[0] == "CO" || $partes[0] == "CN" || $partes[0] == "THT"){
				// VALIDO EL QUE LE ANTECEDE
				if($i > 0){
					$partes_antecede = explode("_" , $valores[($i-1)][0]);
					if($partes_antecede[0] == "SI"){
						if(is_numeric($partes_antecede[1])){
							$mensaje = "Error, no puede existir un valor numerico antes de una Constante, 
										un Concepto o de un Tipo de Hoja Constante, por favor verifique";
							$resultado = "error|.|".$mensaje."|.|".$valores[$i][1];
							$bandera = true;
							break;
						}
					}else{
						$mensaje = "Error, antes de una constante, un concepto o un Tipo de Hoja de Tiempo
									Solo puede haber un valor No Numerico";
						$resultado = "error|.|".$mensaje."|.|".$valores[$i][1];
						$bandera = true;
						break;
					}
				}
				// VALIDO EL QUE LE SIGUE
				$partes_sigue = explode("_" , $valores[($i+1)][0]);
					if($partes_sigue[0] == "SI"){
						if(is_numeric($partes_sigue[1])){
							$mensaje = "Error, no puede existir un valor numerico despues de una Constante, 
										un Concepto o de un Tipo de Hoja Constante, por favor verifique";
							$resultado = "error|.|".$mensaje."|.|".$valores[$i][1];
							$bandera = true;
							break;
						}
					}else{
						$mensaje = "Error, Despues de una constante, un concepto o un Tipo de Hoja de Tiempo
									Solo puede haber un valor No Numerico";
						$resultado = "error|.|".$mensaje."|.|".$valores[$i][1];
						$bandera = true;
						break;
					}
					
					
			}else if($partes[0] == "TA"){ // FIN VALIDACION SI ES CONSTANTE CONCEPTO O TIPO DE HOJA DE TIEMPO
				// VALIDO EL QUE LE ANTECEDE
				if($i > 0){
					$partes_antecede = explode("_" , $valores[($i-1)][0]);
					if($partes_antecede[0] == "SI"){
						if(is_numeric($partes_antecede[1])){
							$mensaje = "Error, no puede existir un valor Numerico antes de una Tabla Constante";
							$resultado = "error|.|".$mensaje."|.|".$valores[$i][1];
							$bandera = true;
							break;
						}
					}else{
						$mensaje = "Error, antes de una Tabla Constante Solo puede haber un valor No Numerico";
						$resultado = "error|.|".$mensaje."|.|".$valores[$i][1];
						$bandera = true;
						break;
					}
				}
				
				// VALIDO EL QUE LE SIGUE
				$partes_sigue = explode("_" , $valores[($i+1)][0]);
					if($partes_sigue[0] == "SI"){
						if(!is_numeric($partes_antecede[1])){
							$mensaje = "Error, a una Tabla de Contantes Solo debe Seguirla un Valor 
										 Numerico o una Funsion";
							$resultado = "error|.|".$mensaje."|.|".$valores[$i][1];
							$bandera = true;
							break;
						}
					}else if($partes_sigue[0] != "FU" and $partes_sigue[0] != "SI"){
						$mensaje = "Error, a una Tabla de Contantes Solo debe Seguirla un Valor 
									Numerico o una Funsion";
						$resultado = "error|.|".$mensaje."|.|".$valores[$i][1];
						$bandera = true;
						break;
					}
				
				
			}else if($partes[0] == "FU"){// FIN VALIDACION SI ES UNA TABLA CONSTANTE
				if($i > 0){
					$partes_antecede = explode("_" , $valores[($i-1)][0]);
					if($partes_antecede[0] == "SI"){
						if(is_numeric($partes_antecede[1])){
							$mensaje = "Error, no puede existir un valor Numerico antes de una Funcion,
										Solo debe ir Simbolos No Numericos o Tabla de Constantes";
							$resultado = "error|.|".$mensaje."|.|".$valores[$i][1];
							$bandera = true;
							break;
						}
					}else if($partes_antecede[0] != "TA"){
						$mensaje = "Error, antes de una Funsion solo debe ir Simbolos No Numericos
									o una Tabla de Constentes";
						$resultado = "error|.|".$mensaje."|.|".$valores[$i][1];
						$bandera = true;
						break;
					}
				}
				
				
				$partes_sigue = explode("_" , $valores[($i+1)][0]);
					if($partes_sigue[0] == "SI"){
						if(is_numeric($partes_sigue[1])){
							$mensaje = "Error, no puede existir un valor numerico despues de una Funsion";
							$resultado = "error|.|".$mensaje."|.|".$valores[$i][1];
							$bandera = true;
							break;
						}
					}
				
			}else if($partes[0] == "SI"){// FIN VALIDACION SI ES UNA FUNSION
				if($i > 0){
					if(is_numeric($partes[1])){
						$partes_antecede = explode("_" , $valores[($i-1)][0]);
						if($partes_antecede[1] == ")" and $partes_antecede[0] != "TA"){
							$mensaje = "Error, Antes de un Simbolo Numerico solo puede existir Otro simbolo
										o una Tabla de Constantes";
							$resultado = "error|.|".$mensaje."|.|".$valores[$i][1];
							$bandera = true;
							break;	
						}
						
						
						
						/*$partes_sigue = explode("_" , $valores[($i+1)][0]);
						if($partes_sigue[0] != "SI"){	
								$mensaje = "Error, A un simbolo Numerico solo lo puede seguir otro simbolo
								o Numerico o No Numerico";
								$resultado = "error|.|".$mensaje."|.|".$valores[$i][1];
								break;
						}*/
						
						
						
					}else{
						$partes_antecede = explode("_" , $valores[($i-1)][0]);
						if($partes_antecede[0] == "SI"){
							if($partes[1] == ")"){
								//echo "aqui";
								if($partes_antecede[1] != ")" and !is_numeric($partes_antecede[1])){
									$mensaje = "Error, A un parentesis de cierre solo lo puede anteceder otro
									parentesis de cierre, un valor Numerico, Una Funcion, Una Constante Etc., mas
									No lo puede anteceder otro simbolo No Numerico";
									$resultado = "error|.|".$mensaje."|.|".$valores[$i][1];
									$bandera = true;
									break;
								}
							}else if($partes[1] == "("){
								if($partes_antecede[1] != "(" and $partes_antecede[1] != "+" and $partes_antecede[1] != "-" and $partes_antecede[1] != "/" and $partes_antecede[1] != "*" and is_numeric($partes_antecede[1])){
									$mensaje = "Error, A un parentesis de apartura solo lo puede anteceder otro
									parentesis de apertura o un caracter No Numerico";
									$resultado = "error|.|".$mensaje."|.|".$valores[$i][1];
									$bandera = true;
									break;
								}						 
							}else if($partes[1] == "+" || $partes[1] == "/" || $partes[1] == "-" || $partes[1] == "*"){
								if($partes_antecede[1] == "+" || $partes_antecede[1] == "-" || $partes_antecede[1] == "/" || $partes_antecede[1] == "*" || $partes_antecede[1] == "("){
									$mensaje = "Error, A un simbolo matematico no lo puede anteceder otro simbolo
									matematico ni parentesis de apertura";
									$resultado = "error|.|".$mensaje."|.|".$valores[$i][1];
									$bandera = true;
									break;
								}						 
							}
								
						}
					
						
						/*$partes_sigue = explode("_" , $valores[($i+1)][0]);
						if($partes_sigue[0] != "SI"){	
								$mensaje = "Error, A un simbolo Numerico solo lo puede seguir otro simbolo
								o Numerico o No Numerico";
								$resultado = "error|.|".$mensaje."|.|".$valores[$i][1];
								break;
						}*/
						
						
						
					}
				}else{
					if(!is_numeric($partes[1]) and $partes[1] != "("){
						$mensaje = "Error, Un simbolo No Numerico no puede comenzar un concepto a menos
						de que el simbolo sea un parentesis de apertura";
									$resultado = "error|.|".$mensaje."|.|".$valores[$i][1];
									$bandera = true;
									break;
					}	
				}
			
				
			
			}//FIN SI ES UN SIMBOLO

		}
		if($bandera == false){
			if($parentesis_apertura > $parentesis_cierre){
				$mensaje = "Error, Faltan Parentesis de Cierre";
				$resultado = "error|.|".$mensaje."|.|".$id_parentesis;
			}else if($parentesis_apertura < $parentesis_cierre){
				$mensaje = "Error, Faltan Parentesis de Apertura";
				$resultado = "error|.|".$mensaje."|.|".$id_parentesis;	
			}
		}
		
		return $resultado;
		$resulado = "";
	}
	






















if($ejecutar == "ingresarValores"){
	
	if($valor == "suma"){
		$valor = "+";	
	}
	
	$partes = explode("_", $valor_oculto);
	
	if($partes[1] == "suma"){
		$valor_oculto = $partes[0]."_+";	
	}
	
	$sql_query = mysql_query("select * from temporal_conceptos where idtemporal_conceptos = '".$id_actualizar."'");
	$num_query = mysql_fetch_array($sql_query);
	
	if($num_query > 0){
		$sql_actualizar = mysql_query("update temporal_conceptos set valor = '".$valor."', valor_oculto = '".$valor_oculto."' where idtemporal_conceptos = '".$id_actualizar."'");
		
	}else{
		$sql_consulta = mysql_query("select * from temporal_conceptos where idsession = '".session_id()."'");
		$num_consulta = mysql_num_rows($sql_consulta);
		$sql_ingresar = mysql_query("insert into temporal_conceptos (idsession, valor, orden, valor_oculto, destino)VALUES('".session_id()."', '".$valor."', '".($num_consulta+1)."', '".$valor_oculto."', '".$destino."')")or die(mysql_error());
	}
}











if($ejecutar == "consultarVisor"){
	$sql_consultar = mysql_query("select * from temporal_conceptos where idsession = '".session_id()."' and destino = '".$destino."' order by orden asc");
	?>
    
	<table border="0">
    	<tr>
        <?
		$num_datos = 1;
        while($bus_consultar = mysql_fetch_array($sql_consultar)){
			?>
			<td id="id<?=$bus_consultar["idtemporal_conceptos"]?>" onClick="color(this.id, '#0000FF', '#FFFFFF'), colocarItemEliminar('<?=$bus_consultar["idtemporal_conceptos"]?>'), seleccionarColor(this.id, '#0000FF', '#FFFFFF')" style="cursor:pointer; font-size:9px;">
			<?=$bus_consultar["valor"]?></td>
			<?
			//if($num_datos == 40){
				/*?>
					</tr>
                    <td>
				<?
				$num_datos = 1;*/
			//}else{
				$num_datos++;	
			//}
		}
		?>
        </tr>
    </table>
    &nbsp;
	<?	
}








if($ejecutar == "eliminarItem"){
	$sql_consulta = mysql_query("select * from temporal_conceptos where idtemporal_conceptos = '".$iditem."' and destino = '".$destino."'");
	$bus_consulta = mysql_fetch_array($sql_consulta);
	$num_consulta= mysql_num_rows($sql_consulta);
	if($num_consulta > 0){
		$sql_elimianr = mysql_query("delete from temporal_conceptos where idtemporal_conceptos = '".$iditem."'");
		$sql_actualizar = mysql_query("update temporal_conceptos set orden = orden + 1 where orden > '".$bus_consulta["orden"]."' and idsession = '".session_id()."'");
	}
		
}






if($ejecutar == "insertarDespues"){
	$sql_consulta = mysql_query("select * from temporal_conceptos where idtemporal_conceptos = '".$iditem."'");
	$bus_consulta = mysql_fetch_array($sql_consulta);
	$sql_actualizar = mysql_query("update temporal_conceptos set orden = orden + 1 where orden > '".$bus_consulta["orden"]."' and idsession = '".session_id()."'");
	$sql_ingresar = mysql_query("insert into temporal_conceptos(idsession, valor, orden, destino)VALUES('".session_id()."', 'VACIO', '".($bus_consulta["orden"]+1)."', '".$destino."')");
	$id = mysql_insert_id();
	echo "id".$id."_".$id;
}






if($ejecutar == "insertarAntes"){
	$sql_consulta = mysql_query("select * from temporal_conceptos where idtemporal_conceptos = '".$iditem."'")or die(mysql_error());
	$bus_consulta = mysql_fetch_array($sql_consulta);
	$sql_actualizar = mysql_query("update temporal_conceptos set orden = orden + 1 where orden >= '".$bus_consulta["orden"]."' and idsession = '".session_id()."'")or die(mysql_error());
	$sql_ingresar = mysql_query("insert into temporal_conceptos(idsession, valor, orden, destino)VALUES('".session_id()."', 'VACIO', '".$bus_consulta["orden"]."', '".$destino."')")or die(mysql_error());
	$id = mysql_insert_id();
	echo "id".$id."_".$id;
}






if($ejecutar == "limpiarDatos"){
	$sql_actualizar = mysql_query("delete from temporal_conceptos where idsession = '".session_id()."' and destino= '".$destino."'");	
}







if($ejecutar == "ingresarConcepto"){
	
	$sql_principal= mysql_query("select * from temporal_conceptos where idsession= '".session_id()."' and destino = 'principal'");
	$num_principal= mysql_num_rows($sql_principal);
	if($num_principal == 0){
		echo "vacio|.|Disculpe debe escribir la formula del Concepto";	
	}else{
		$resultado = explode("|.|" , validar_formula('principal'));
		$bandera = true;
		if($resultado[0] != "error"){
			$sql_consulta= mysql_query("select * from temporal_conceptos where idsession = '".session_id()."' and destino = 'condicion'");
			$num_consulta = mysql_num_rows($sql_consulta);
			if($num_consulta > 0){	
				$resultado = explode("|.|" , validar_formula('condicion'));
				if($resultado[0] != "error"){
					$resultado = explode("|.|", validar_formula('entonces'));
						if($resultado[0] == "error"){
							//echo $resultado[1];	
							$bandera = false;
						}else{
							$bandera = true;	
						}
				}else{
					//echo $resultado[1];			
					$bandera = false;
				}
			}else{
				$bandera = true;	
			}
		}else{
			//echo $resultado[1];	
			$bandera = false;
		}
		
		
		if($bandera == true){
		
		
		
		
			$sql_ingresar = mysql_query("insert into conceptos_nomina (codigo, 
																	   descripcion, 
																	   status, 
																	   fechayhora, 
																	   usuario,
																	   tipo_concepto,
																	   idclasificador_presupuestario,
																	   idordinal,
																	   posicion,
																	   aplica_prestaciones,
																	   columna_prestaciones)VALUE('".$codigo."',
																					'".$descripcion."',
																					'".$status."',
																					'".$fh."',
																					'".$login."',
																					'".$tipo_concepto."',
																					'".$id_clasificador."',
																					'".$idordinal."',
																					'".$posicion."',
																					'".$aplica_prestaciones."',
																					'".$columna_prestaciones."')");
			$id = mysql_insert_id();
			$sql_consulta= mysql_query("select * from temporal_conceptos where idsession = '".session_id()."'");
			while($bus_consulta = mysql_fetch_array($sql_consulta)){
				$sql_ingresar_relacion = mysql_query("insert into relacion_formula_conceptos_nomina(idconcepto_nomina, 
																									valor, 
																									valor_oculto, 
																									orden,
																									destino)VALUES('".$id."',
																												'".$bus_consulta["valor"]."',
																												'".$bus_consulta["valor_oculto"]."',
																												'".$bus_consulta["orden"]."',
																												'".$bus_consulta["destino"]."')");	
			}
			$sql_actualizar = mysql_query("delete from temporal_conceptos where idsession = '".session_id()."'");
			
			
			
			
			registra_transaccion("Se ingreso un nuevo concepto de nomina (".$id.") ".$denominacion."",$login,$fh,$pc,'conceptos_nomina');
		}else{
			echo $resultado[0]."|.|".$resultado[1]."|.|".$resultado[2];
		}
	}
}

















if($ejecutar == "consultarFormulas"){
	$sql_eliminar = mysql_query("delete from temporal_conceptos 
								 			where 
												idsession = '".session_id()."' 
												and destino = '".$destino."'");
	
	$sql_consultar = mysql_query("select * from relacion_formula_conceptos_nomina 
								 			where 
												idconcepto_nomina = '".$idconcepto."' 
												and destino = '".$destino."' 
											order by orden asc");
	while($bus_consultar= mysql_fetch_array($sql_consultar)){
		$sql_ingresar = mysql_query("insert into temporal_conceptos (idsession, 
																	 valor, 
																	 orden, 
																	 valor_oculto, 
																	 destino)VALUES('".session_id()."',
																	 				'".$bus_consultar["valor"]."',
																					'".$bus_consultar["orden"]."',
																					'".$bus_consultar["valor_oculto"]."',
																					'".$bus_consultar["destino"]."')");
	}
	$sql_consultar = mysql_query("select * from temporal_conceptos 
								 			where 
												idsession = '".session_id()."' 
												and destino = '".$destino."' 
											order by orden asc");
	?>
    
	<table border="0">
    	<tr>
        <?
		$num_datos = 1;
        while($bus_consultar = mysql_fetch_array($sql_consultar)){
			?>
			<td id="id<?=$bus_consultar["idtemporal_conceptos"]?>" onClick="color(this.id, '#0000FF', '#FFFFFF'), colocarItemEliminar('<?=$bus_consultar["idtemporal_conceptos"]?>', '#0000FF'), seleccionarColor(this.id, '#0000FF', '#FFFFFF')" style="cursor:pointer; font-size:9px;">
			<?=$bus_consultar["valor"]?></td>
			<?
			//if($num_datos == 40){
				/*?>
					</tr>
                    <tr>
				<?
				$num_datos = 1;*/
			//}else{
				$num_datos++;	
			//	}
		}
		?>
        </tr>
    </table>
    &nbsp;
	<?		
}



















if($ejecutar == "modificarConcepto"){
	$sql_principal= mysql_query("select * from temporal_conceptos where idsession= '".session_id()."' and destino = 'principal'");
	$num_principal= mysql_num_rows($sql_principal);
	if($num_principal == 0){
		echo "vacio|.|Disculpe debe escribir la formula del Concepto";	
	}else{
	$resultado = explode("|.|" , validar_formula('principal'));
	$bandera = true;
	if($resultado[0] != "error"){
		$sql_consulta= mysql_query("select * from temporal_conceptos where idsession = '".session_id()."' and destino = 'condicion'");
		$num_consulta = mysql_num_rows($sql_consulta);
		if($num_consulta > 0){	
			$resultado = explode("|.|" , validar_formula('condicion'));
			if($resultado[0] != "error"){
				$resultado = explode("|.|", validar_formula('entonces'));
					if($resultado[0] == "error"){
						//echo $resultado[1];	
						$bandera = false;
					}else{
						$bandera = true;	
					}
			}else{
				//echo $resultado[1];			
				$bandera = false;
			}
		}else{

			$bandera = true;	
		}
	}else{
		//echo $resultado[1];	
		$bandera = false;
	}
	
	
	if($bandera == true){
		$sql_actualizar = mysql_query("update conceptos_nomina set codigo= '".$codigo."',
																   descripcion ='".$descripcion."',
																   tipo_concepto= '".$idtipo_concepto."',
																   idclasificador_presupuestario= '".$id_clasificador."',
																   idordinal= '".$idordinal."',
																   posicion='".$posicion."',
                                                                    aplica_prestaciones='".$aplica_prestaciones."',
                                                                    columna_prestaciones='".$columna_prestaciones."'
																   where 
																   idconceptos_nomina ='".$idconcepto."'");

		$sql_eliminar = mysql_query("delete from relacion_formula_conceptos_nomina where idconcepto_nomina ='".$idconcepto."'");
		$sql_consulta= mysql_query("select * from temporal_conceptos where idsession = '".session_id()."'");
		while($bus_consulta = mysql_fetch_array($sql_consulta)){
			$sql_ingresar_relacion = mysql_query("insert into relacion_formula_conceptos_nomina(idconcepto_nomina, 
																								valor, 
																								valor_oculto, 
																								orden,
																								destino)VALUES('".$idconcepto."',
																											'".$bus_consulta["valor"]."',
																											'".$bus_consulta["valor_oculto"]."',
																											'".$bus_consulta["orden"]."',
																											'".$bus_consulta["destino"]."')");	
		}
		$sql_actualizar = mysql_query("delete from temporal_conceptos where idsession = '".session_id()."'");
		registra_transaccion("Se ingreso un nuevo concepto de nomina (".$id.") ".$denominacion."",$login,$fh,$pc,'conceptos_nomina');
	}else{
		echo $resultado[0]."|.|".$resultado[1]."|.|".$resultado[2];
	}
	}
}
?>
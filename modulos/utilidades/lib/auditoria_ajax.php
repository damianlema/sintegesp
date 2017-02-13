<?
session_start();
include("../../../conf/conex.php");
$conexion_gestion = Conectarse();
extract($_POST);

if($ejecutar == "consultarUsuarios"){

	if($_POST["valor"] != ""){
		if($_POST["valor"] == "*"){
			$sql = mysql_query("select * from usuarios where status != 'e' order by nombres");
			$sql_eliminados = mysql_query("select * from usuarios where status = 'e' order by nombres");
		}else{
			$sql = mysql_query("select * from usuarios where (nombres like '%".$_POST["valor"]."%' or apellidos like '%".$_POST["valor"]."%') 
																			and status != 'e' order by nombres");
			$sql_eliminados = mysql_query("select * from usuarios where 
															(nombres like '%".$_POST["valor"]."%' or apellidos like '%".$_POST["valor"]."%') 
															and status = 'e' order by nombres");
		}
		?>
        <label style='font-family:Verdana, Arial, Helvetica, sans-serif; font-size:9px;'> 
            <a href="javascript:;" onclick="document.getElementById('usuarios').value = 'TODOS LOS USUARIOS', document.getElementById('cedulaUsuario').value = 'todos', document.getElementById('divListaUsuarios').style.display='none'">
                <b>-</b> <strong>TODOS LOS USUARIOS</strong>
            </a>
        </label>
        <br />
        <br />
        <strong>
        	<label style='font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px; color:#3366CC'>
            	Usuarios Activos
            </label>
        </strong>
       	<br />

        <?
			while($bus=mysql_fetch_array($sql)){
				?>
                <label style='font-family:Verdana, Arial, Helvetica, sans-serif; font-size:9px;'> 
                    <a href="javascript:;" onclick="document.getElementById('usuarios').value = '<?=$bus["nombres"]." ".$bus["apellidos"]?>', document.getElementById('cedulaUsuario').value = '<?=$bus["login"]?>', document.getElementById('divListaUsuarios').style.display='none'">
                        <b>-</b> <?=$bus["nombres"]." ".$bus["apellidos"]?>
                    </a>
                </label>
                <br />
				<?
			}
		$num = mysql_num_rows($sql);
			if($num == 0){
			?>
            <label style='font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px; color:#990000'>
            	No hay usuarios con este nombre
            </label>
			<?
			}
		
			?>
            <br />
            <strong>
            	<label style='font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px; color:#990000'>
                	Usuarios Inactivos
                </label>
            </strong>
            <br />
            <?
		$num_eliminados = mysql_num_rows($sql_eliminados);
			if($num_eliminados == 0){
				?>
                <label style='font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px; color:#990000'>
                	No hay usuarios con este nombre
              	</label>
                <?		
			}else{
				while($bus_eliminados = mysql_fetch_array($sql_eliminados)){
					?>
                    <label style='font-family:Verdana, Arial, Helvetica, sans-serif; font-size:9px;'> 
                        <a href="javascript:;" onclick="document.getElementById('usuarios').value = '<?=$bus_eliminados["nombres"]." ".$bus_eliminados["apellidos"]?>', document.getElementById('cedulaUsuario').value = '<?=$bus_eliminados["login"]?>', document.getElementById('divListaUsuarios').style.display='none'">
                            <b>-</b> <?=$bus_eliminados["nombres"]." ".$bus_eliminados["apellidos"]?>
                        </a>
                    </label>
                    <br />
					<?
				}
			}
	}
}






if($ejecutar == "listarTransacciones"){
		
		$sql_configuracion = mysql_query("select * from configuracion");
		$bus_configuracion = mysql_fetch_array($sql_configuracion);
		// REALIZO LA CONEXION A LA BASE DE DATOS DE RESPALDO
		$dbhost = "localhost"; 
		$dbuser = $bus_configuracion["usuario_bd"]; 
		$dbpassword = $bus_configuracion["clave_bd"];

		
		$tabla_conexion = "gestion_".$_SESSION["anio_fiscal"];
		//$tabla_conexion = "gestion_demo";
		
		$arregloMeses["01"] = "enero";
		$arregloMeses["02"] = "febrero";
		$arregloMeses["03"] = "marzo";
		$arregloMeses["04"] = "abril";
		$arregloMeses["05"] = "mayo";
		$arregloMeses["06"] = "junio";
		$arregloMeses["07"] = "julio";
		$arregloMeses["08"] = "agosto";
		$arregloMeses["09"] = "septiembre";
		$arregloMeses["10"] = "octubre";
		$arregloMeses["11"] = "noviembre";
		$arregloMeses["12"] = "diciembre";		
		
		
		// CREO EL ARREGLO CON TODOS LOS NOMBRES DE LAS TABLAS DE LA BD DE RESPALDO
		$contador = 0;
		for($anio=2009; $anio <= 2014; $anio++){
			for($mes=1; $mes <= 12; $mes++){
				if($mes < 10){
					$mesNuevo = "0".$mes;
				}else{
					$mesNuevo = $mes;
				}
				$arreglo_tablas[$contador] = $arregloMeses[$mesNuevo];
				$contador++;
			}
		}
		
		//var_dump($arreglo_tablas);
		// SE CREA UN ARREGLO CON LOS MESES DEL A&ntilde;O

		
	$tamanio_arraglo = count($arreglo_tablas);	
		if($fecha_desde != "" and $fecha_hasta != ""){
				// SE CREAN LAS VARIABLES DE MES Y A&ntilde;O DESDE
				$explode_fecha_desde = explode("-",$fecha_desde);
				$mes_fecha_desde = $explode_fecha_desde[1];
				$anio_fecha_desde = $explode_fecha_desde[0];
				
				// SE CREAN LAS VARIABLES DE MES Y A&ntilde;O HASTA
				$explode_fecha_hasta = explode("-",$fecha_hasta);
				$mes_fecha_hasta = $explode_fecha_hasta[1];
				$anio_fecha_hasta = $explode_fecha_hasta[0];
		
				
		
				// SE BUSCA EL INDICE DEL ARREGLO DESDE
				
				
				for($j=0; $j < $tamanio_arraglo; $j++){
					 if($arreglo_tablas[$j] == $arregloMeses[$mes_fecha_desde]."_".$anio_fecha_desde){
							  $indice_desde = $j;
					 }
				}
				
				// SE BUSCA EL INDICE DEEL ARREGLO HASTA
				for($k=0; $k < $tamanio_arraglo; $k++){
					 if($arreglo_tablas[$k] == $arregloMeses[$mes_fecha_hasta]."_".$anio_fecha_hasta){
							  $indice_hasta = $k;
					 }
				}
		}// fin del si que valida si vienen las fechas
		
		
		
		// SE CREA EL QUERY PARA LA CONSULTA
		$sql = "";
		
		
		if($fecha_desde != "" and $fecha_hasta != ""){// si vienen las fechas
		
			if($mes_fecha_desde == $mes_fecha_hasta){
				// HACER UN CODIGO QUE ME PERMITA RECORRER TODAS LAS TABLAS CON DISTINTOS QUERYS
				
					for($i=1; $i<=12; $i++){
						if($i<10){
							$numero = "0".$i;
						}else{
							$numero = $i;
						}
						$sql .= " (select tipo, tabla, usuario, fechayhora, estacion 
										from 
									gestion_respaldo_".$_SESSION["anio_fiscal"].".".$arregloMeses[$numero]."
										where 
									fechayhora between '".$fecha_desde." 00:00:00' and '".$fecha_hasta." 23:59:59' ";
							
							
						if($documento != ""){
							$sql .= "and tipo like '%".$documento."%' ";
						}
						
						if($cedula != "todos"){
							$sql .= "and usuario = '".$cedula."')";
						}else{
							$sql .= ")";
						}
						
						if($i < 12){
							$sql .= " UNION ";
						}else{
							$sql .= " order by fechayhora DESC";
						}
					}
					
				
			}else{
			//echo "INDICE DESDE: ".$indice_hasta;
					
					for($i=1; $i<=12; $i++){
						if($i<10){
							$numero = "0".$i;
						}else{
							$numero = $i;
						}
					
						$sql .= " (select tipo, tabla, usuario, fechayhora, estacion 
										from 
									gestion_respaldo_".$_SESSION["anio_fiscal"].".".$arregloMeses[$numero]."
										where 
									fechayhora between '".$fecha_desde." 00:00:00' and '".$fecha_hasta." 23:59:59' ";
						
						if($documento != ""){
							$sql .= "and tipo like '%".$documento."%' ";
						}
						
						if($cedula != "todos"){
							$sql .= "and usuario = '".$cedula."')";
						}else{
							$sql .= ")";
						}
						
						if($i < 12){
							$sql .= " UNION ";
						}else{
							$sql .= " order by fechayhora DESC";
						}
					}
				}
			
			
		}else{ 	
					for($i=1; $i<=12; $i++){
						if($i<10){
							$numero = "0".$i;
						}else{
							$numero = $i;
						}
					
						$sql .= " (select tipo, tabla, usuario, fechayhora, estacion 
									from 
									gestion_respaldo_".$_SESSION["anio_fiscal"].".".$arregloMeses[$numero]."";
						
						if($documento != ""){
							$sql .= "and tipo like '%".$documento."%' ";
						}
						
						if($cedula != "todos"){
							$sql .= " where usuario = '".$cedula."')";
						}else{
							$sql .= ")";
						}
						
						if($i < 12){
							$sql .= " UNION ";
						}else{
							$sql .= " order by fechayhora DESC";
						}
				
					}
		}
		
		//echo $sql;
		//echo $sql."<br /><br />";
		$query = mysql_query($sql)or die("FFF: ".mysql_error());
		
		?>
		<table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="100%">
          <thead>
          <tr>
          	<td width="2%" align="center" class="Browse">Nro</td>
            <td width="41%" align="center" class="Browse">Tipo</td>
            <td width="15%" align="center" class="Browse">Tabla</td>
            <td width="10%" align="center" class="Browse">Usuario</td>
            <td width="27%" align="center" class="Browse">Fecha y Hora</td>
            <td width="5%" align="center" class="Browse">Estacion</td>
          </tr>
          </thead>
         <?
		
	if($fecha_desde != "" and $fecha_hasta != ""){
		$query_registro = "select * from ".$tabla_conexion.".registro_transacciones where fechayhora between '".$fecha_desde." 00:00:00' and '".$fecha_hasta." 23:59:59' ";
		if($documento != ""){
			$query_registro .= "and tipo like '%".$documento."%' ";
		}
		
		if($cedula != "todos"){
			$query_registro .= "and usuario = '".$cedula."' order by fechayhora DESC";
		}else{
			$query_registro .= "order by fechayhora DESC";
		}
		
		
	}else{
		$query_registro = "select * from ".$tabla_conexion.".registro_transacciones where tipo != '' ";
		if($documento != ""){
			$query_registro .= "and tipo like '%".$documento."%' ";
		}
		if($cedula != "todos"){
			$query_registro .= "and usuario = '".$cedula."' order by fechayhora DESC";
		}else{
			$query_registro .= "order by fechayhora DESC";
		}	
	}	
		
		
		$arregloDias["Monday"] = "Lunes";
		$arregloDias["Tuesday"] = "Martes";
		$arregloDias["Wednesday"] = "Miercoles";
		$arregloDias["Thursday"] = "Jueves";
		$arregloDias["Friday"] = "Viernes";
		$arregloDias["Saturday"] = "Sabado";
		$arregloDias["Sunday"] = "Domingo";
			
			
         $sql_registro_transacciones = mysql_query($query_registro)or die("X".mysql_error());
$numero = 1;
		while($bus_registro_transacciones = mysql_fetch_array($sql_registro_transacciones)){
		
		?>
		<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
            <td class='Browse' align='left'><strong><?=$numero?></strong></td>
            <td class='Browse' align='left'><?=$bus_registro_transacciones["tipo"]?></td>
            <td class='Browse' align='left'><?=$bus_registro_transacciones["tabla"]?></td>
            <td class='Browse' align='left'><?=$bus_registro_transacciones["usuario"]?></td>
            <td class='Browse' align='left'>
			<?
			$fechayhora = explode(" ",$bus_registro_transacciones["fechayhora"]);
			$fecha = $fechayhora[0];
			$desgloze_fecha = explode("-", $fecha);
			$hora = $fechayhora[1];
			
			$MiTimeStamp = mktime(0,0,0,$desgloze_fecha[1],$desgloze_fecha[2],$desgloze_fecha[0]);
			$dia_semana = date("l",$MiTimeStamp);
			
			if($desgloze_fecha[2] < 10){
				$desgloze_fecha[2] = "0".$desgloze_fecha[2];
			}
			
			/*if($desgloze_fecha[1] < 10){
				$desgloze_fecha[1] = "0".$desgloze_fecha[1];
			}*/
			
			echo "El ".$arregloDias[$dia_semana]." ".$desgloze_fecha[2]." de ".ucfirst($arregloMeses[$desgloze_fecha[1]])." de ".$desgloze_fecha[0]." A las ".$hora;

			?></td>
            <td class='Browse' align='left'><?=$bus_registro_transacciones["estacion"]?></td>              
         </tr>
		<?
		$numero++;
		}
		 ?> 
         
		<?
		//echo $sql;
		while($bus = mysql_fetch_array($query)){
		//echo "AAA";
		?>
		<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
            <td class='Browse' align='left'><strong><?=$numero?></strong></td>
            <td class='Browse' align='left'><?=$bus["tipo"]?></td>
            <td class='Browse' align='left'><?=$bus["tabla"]?></td>
            <td class='Browse' align='left'><?=$bus["usuario"]?></td>
            <td class='Browse' align='left'>
			<?
            $fechayhora = explode(" ",$bus["fechayhora"]);
			$fecha = $fechayhora[0];
			$desgloze_fecha = explode("-", $fecha);
			$hora = $fechayhora[1];
			
			$MiTimeStamp = mktime(0,0,0,$desgloze_fecha[1],$desgloze_fecha[2],$desgloze_fecha[0]);
			$dia_semana = date("l",$MiTimeStamp);
			
			if($desgloze_fecha[2] < 10){
				$desgloze_fecha[2] = "0".$desgloze_fecha[2];
			}
			
			/*if($desgloze_fecha[1] < 10){
				$desgloze_fecha[1] = "0".$desgloze_fecha[1];
			}*/
			
			echo "El ".$arregloDias[$dia_semana]." ".$desgloze_fecha[2]." de ".ucfirst($arregloMeses[$desgloze_fecha[1]])." de ".$desgloze_fecha[0]." A las ".$hora;
			
			
			?>
            </td>
            <td class='Browse' align='left'><?=$bus["estacion"]?></td>              
         </tr>
		<?
		$numero++;
		}
		?>
		</table>
		<?
	//}
}
?>
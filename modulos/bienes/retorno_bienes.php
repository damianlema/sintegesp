<?
if($_GET["idmovimiento"] != ""){
	$sql_realizar_movimiento = mysql_query("select * from movimientos_bienes_individuales where idmovimientos_bienes_individuales = '".$_GET["idmovimiento"]."'");
	$bus_realizar_movimiento = mysql_fetch_array($sql_realizar_movimiento);
	$sql_ingresar = mysql_query("insert into movimientos_bienes_individuales (tipo,
											  codigo_bien,
											  idcatalogo_bienes,
											  especificaciones,
											  idorganizacion_actual,
											  idnivel_organizacional_actual,
											  nro_orden,
											  fecha_orden,
											  idtipo_movimiento,
											  idorganizacion_destino,
											  idnivel_organizacional_destino,
											  fecha_movimiento,
											  fecha_regreso,
											  retorno_automatico,
											  justificacion_movimiento,
											  status,
											  usuario,
											  fechayhora,
											  idbien,
											  tipo_bien,
											  estado)VALUES('".$bus_realizar_movimiento["tipo"]."',
											  					'".$bus_realizar_movimiento["codigo_bien"]."',
																'".$bus_realizar_movimiento["idcatalogo_bienes"]."',
																'".$bus_realizar_movimiento["especificaciones"]."',
																'".$bus_realizar_movimiento["idorganizacion_destino"]."',
																'".$bus_realizar_movimiento["idnivel_organizacional_destino"]."',
																'".$bus_realizar_movimiento["nro_orden"]."',
																'".$bus_realizar_movimiento["fecha_orden"]."',
																'".$bus_realizar_movimiento["idtipo_movimiento"]."',
																'".$bus_realizar_movimiento["idorganizacion_actual"]."',
																'".$bus_realizar_movimiento["idnivel_organizacional_actual"]."',
																'".date("Y-m-d")."',
																'',
																'no',
																'Regreso de Bien: ".$bus_realizar_movimiento["justificacion_movimiento"]."',
																'a',
																'".$login."',
																'".$fh."',
																'".$bus_realizar_movimiento["idbien"]."',
																'".$bus_realizar_movimiento["tipo_bien"]."',
																'procesado')")or die(mysql_error());
	$sql_actualizar = mysql_query("update movimientos_bienes_individuales set estado = 'devuelto' where idmovimientos_bienes_individuales = '".$idmovimiento."'");
	if($bus_realizar_movimiento["tipo"] == "mueble"){
		$sql_actualizar = mysql_query("update muebles set idorganizacion = '".$bus_realizar_movimiento["idorganizacion_actual"]."',
															idnivel_organizacion = '".$bus_realizar_movimiento["idnivel_organizacional_actual"]."'
															where idmuebles = '".$bus_realizar_movimiento["idbien"]."'")or die(mysql_error());
	}else{
		$sql_actualizar = mysql_query("update ".$bus_realizar_movimiento["tipo_bien"]."s set 
												organizacion = '".$bus_realizar_movimiento["idorganizacion_actual"]."'
												where id".$bus_realizar_movimiento["tipo_bien"]."s = '".$bus_realizar_movimiento["idbien"]."'")
												or die(mysql_error());
	
	}
	?>
	<script>
    window.location.href="principal.php?modulo=8&accion=855";
    </script>
	<?
	
}
?>
    <br />
     	<h2 class="sqlmVersion"></h2>
	<br> 
    <script>
		function retornarMovimiento(idmovimiento){
			if(confirm("Seguro desea retornar el bien en movimiento seleccionado?")){
				window.location.href="principal.php?modulo=8&accion=855&idmovimiento="+idmovimiento+"";
			}
		} 
	</script>
     <form action="" method="post"> 
       <table width="552" border="0" align="center">
  <tr>
            <td width="32%" align="right" class='viewPropTitle'> Codigo del Bien:</td>
      <td width="24%"><input type="text" name="codigo" id="codigo"></td>
          <td width="15%" align="center"><input type="submit" name="buscar" id="buscar" value="Buscar" class="button"></td>
         </tr>
      </table>
    </form>
        
  <?
  	if($_POST){
	$sql_query = mysql_query("select movimientos_bienes_individuales.idmovimientos_bienes_individuales as idmovimiento,
									movimientos_bienes_individuales.tipo as tipo,
									movimientos_bienes_individuales.codigo_bien as codigo_bien,
									movimientos_bienes_individuales.especificaciones as especificaciones,
									movimientos_bienes_individuales.idmovimientos_bienes_individuales as idmovimientos,
									movimientos_bienes_individuales.idorganizacion_actual as organizacion_actual,
									movimientos_bienes_individuales.idnivel_organizacional_actual as nivel_organizacional_actual,
									movimientos_bienes_individuales.idorganizacion_destino as organizacion_destino,
									movimientos_bienes_individuales.idnivel_organizacional_destino as nivel_organizacional_destino,
									detalle_catalogo_bienes.codigo as codigo_catalogo,
									detalle_catalogo_bienes.denominacion as denominacion_catalogo
									 	from 
									 movimientos_bienes_individuales, detalle_catalogo_bienes
									 	where 
									 movimientos_bienes_individuales.idcatalogo_bienes = detalle_catalogo_bienes.iddetalle_catalogo_bienes
									 and movimientos_bienes_individuales.codigo_bien like '%".$_POST["codigo"]."%'
									 and movimientos_bienes_individuales.retorno_automatico = 'si'
									 and movimientos_bienes_individuales.estado = 'procesado'")or die(mysql_error());
	

		//echo $query;		
  ?>      <br />
  
        <table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="98%">
          <thead>
          <tr>
            <td width="6%" align="center" class="Browse" style="font-size:9px">Codigo del Bien</td>
            <td width="8%" align="center" class="Browse" style="font-size:9px">Codigo de Catalogo</td>
            <td width="21%" align="center" class="Browse" style="font-size:9px">Denominacion de Catalogo</td>
            
            <td width="22%" align="center" class="Browse" style="font-size:9px"><span class="Browse" style="font-size:9px">Especificaciones</span></td>
            <td width="20%" align="center" class="Browse" style="font-size:9px">Ubicacion Actual</td>
            <td width="19%" align="center" class="Browse" style="font-size:9px">Ubicacion de Retorno</td>
            <td width="4%" align="center" class="Browse" style="font-size:9px">Acci&oacute;n</td>
          </tr>
          </thead>
          <?
		  while($bus_consultar = mysql_fetch_array($sql_query)){
		  ?>
                  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                  	
                    <td class='Browse' align='left' width="6%" style="font-size:10px"><?=$bus_consultar["codigo_bien"]?></td>
                    <td class='Browse' align='left' width="8%" style="font-size:10px"><?=$bus_consultar["codigo_catalogo"]?></td>
                    <td class='Browse' align='left' width="21%" style="font-size:10px"><?=$bus_consultar["denominacion_catalogo"]?></td>
                    
                    <td class='Browse' align='left' width="22%" style="font-size:10px"><?=$bus_consultar["especificaciones"]?></td>
                    <td class='Browse' align='left' width="20%" style="font-size:10px">
					<?
                    if($bus_consultar["tipo"] == "inmueble"){
						$sql_organizacion_actual = mysql_query("select * from organizacion where idorganizacion = ".$bus_consultar["organizacion_destino"]."");
						$bus_organizacion_actual = mysql_fetch_array($sql_organizacion_actual);
							echo $bus_organizacion_actual["denominacion"];
					}else{
						$sql_organizacion_actual = mysql_query("select * from organizacion where idorganizacion = ".$bus_consultar["organizacion_destino"]."");
						$bus_organizacion_actual = mysql_fetch_array($sql_organizacion_actual);
						$sql_nivel_organizacional_actual = mysql_query("select * from niveles_organizacionales where idniveles_organizacionales = '".$bus_consultar["nivel_organizacional_destino"]."'");
						$bus_nivel_organizacional_actual = mysql_fetch_array($sql_nivel_organizacional_actual);
							echo $bus_organizacion_actual["denominacion"]." - ".$bus_nivel_organizacional_actual["denominacion"]; 
					}
					?></td>
                    <td class='Browse' align='left' width="19%" style="font-size:10px">
					<?
                    if($bus_consultar["tipo"] == "inmueble"){
						$sql_organizacion_actual = mysql_query("select * from organizacion where idorganizacion = ".$bus_consultar["organizacion_actual"]."");
						$bus_organizacion_actual = mysql_fetch_array($sql_organizacion_actual);
							echo $bus_organizacion_actual["denominacion"];
					}else{
						$sql_organizacion_actual = mysql_query("select * from organizacion where idorganizacion = ".$bus_consultar["organizacion_actual"]."");
						$bus_organizacion_actual = mysql_fetch_array($sql_organizacion_actual);
						$sql_nivel_organizacional_actual = mysql_query("select * from niveles_organizacionales where idniveles_organizacionales = '".$bus_consultar["nivel_organizacional_actual"]."'");
						$bus_nivel_organizacional_actual = mysql_fetch_array($sql_nivel_organizacional_actual);
							echo $bus_organizacion_actual["denominacion"]." - ".$bus_nivel_organizacional_actual["denominacion"]; 
					}
					?></td>
                    <td align="center" valign="middle" class='Browse'>
                        <img src="imagenes/refrescar.png"
                            style="cursor:pointer"
                            onClick="retornarMovimiento('<?=$bus_consultar["idmovimiento"]?>')">                    
                            </td>
   		        </tr>
          <?
          }
          ?>
        </table>
 <?
 }
 registra_transaccion("Listar Muebles",$login,$fh,$pc,'muebles');
 ?>
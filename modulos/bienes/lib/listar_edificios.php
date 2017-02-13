<?
session_start();
include("../../../conf/conex.php");
$conection_db = conectarse();
include("../../../funciones/funciones.php");




?>
<script type="text/javascript" src="../../../js/funciones.js"></script>
<script type="text/javascript" src="../../../js/function.js"></script>
<link href="../../../css/theme/green/main.css" rel="stylesheet" type="text/css">
  <style type="text/css"> @import url("../../../css/theme/calendar-win2k-cold-1.css"); </style>   
    <br />
     	<h2 class="sqlmVersion"></h2>
	<br> 
     <form action="" method="post"> 
       <table width="379" border="0" align="center">
			<tr>
            <td width="48%"> Denominacion Inmueble:</td>
            <td width="34%"><input type="text" name="denominacion_inmueble" id="denominacion_inmueble"></td>
             <td width="18%">
       		  <input type="submit" name="buscar" id="buscar" value="Buscar" class="button">      		</td>
         </tr>
      </table>
    </form>
        
  <?
  $query = "select * from edificios";
	if($_POST){
		if($_POST["denominacion_inmueble"] != ""){
			$query .= " where denominacion_inmueble like '%".$_POST["denominacion_inmueble"]."%'";
		}
	}			
  ?>      <br />
  
        <table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="98%">
          <thead>
          <tr>
          	<td width="10%" align="center" class="Browse" style="font-size:9px">Estado / Municipio</td>
            <td width="37%" align="center" class="Browse" style="font-size:9px">Denominacion del Inmueble</td>
            <td width="11%" align="center" class="Browse" style="font-size:9px">Ubicacion Geografica</td>
            <td width="10%" align="center" class="Browse" style="font-size:9px">Area Total del Terreno</td>
            <td width="6%" align="center" class="Browse" style="font-size:9px">Seleccionar</td>
          </tr>
          </thead>
          <?
		  $sql_consultar = mysql_query($query); 
          while($bus_consultar = mysql_fetch_array($sql_consultar)){
		  ?>
                  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                    <td class='Browse' align='left' width="10%" style="font-size:10px"><?=$bus_consultar["estado_municipio_propietario"]?></td>
                    <td class='Browse' align='left' width="37%" style="font-size:10px"><?=$bus_consultar["denominacion_inmueble"]?></td>
                    <td class='Browse' align='right' width="11%" style="font-size:10px"><?=$bus_consultar["ubicacion_geografica_direccion"]?></td>
                    <td class='Browse' align='left' style="font-size:10px">&nbsp;<?=$bus_consultar["area_terreno"]?></td>
                    <td align="center" valign="middle" class='Browse'>
                        <img src="../../../imagenes/validar.png"
                            style="cursor:pointer"
                            onClick="opener.cargarEdificio('<?=$bus_consultar["idedificios"]?>',
                            								'<?=$bus_consultar["idtipo_movimiento"]?>',
                                                              '<?=$bus_consultar["iddetalle_catalogo_bienes"]?>',
                                                              '<?=$bus_consultar["estado_municipio_propietario"]?>',
                                                              '<?=$bus_consultar["denominacion_inmueble"]?>',
                                                              '<?=$bus_consultar["clasificacion_funcional_inmueble"]?>',
                                                              '<?=$bus_consultar["ubicacion_geografica_estado"]?>',
                                                              '<?=$bus_consultar["ubicacion_geografica_municipio"]?>',
                                                              '<?=$bus_consultar["ubicacion_geografica_direccion"]?>',
                                                              '<?=$bus_consultar["area_terreno"]?>',
                                                              '<?=$bus_consultar["area_construccion"]?>',
                                                              '<?=$bus_consultar["numero_pisos"]?>',
                                                              '<?=$bus_consultar["area_total_construccion"]?>',
                                                              '<?=$bus_consultar["area_anexidades"]?>',
                                                              '<?=$bus_consultar["tipo_estructura"]?>',
                                                              '<?=$bus_consultar["pisos"]?>',
                                                              '<?=$bus_consultar["paredes"]?>',
                                                              '<?=$bus_consultar["techos"]?>',
                                                              '<?=$bus_consultar["puertas_ventanas"]?>',
                                                              '<?=$bus_consultar["servicios"]?>',
                                                              '<?=$bus_consultar["otras_anexidades"]?>',
                                                              '<?=$bus_consultar["linderos"]?>',
                                                              '<?=$bus_consultar["estado_legal"]?>',
                                                              '<?=$bus_consultar["valor_contabilidad_fecha"]?>',
                                                              '<?=$bus_consultar["valor_contabilidad_monto"]?>',
                                                              '<?=$bus_consultar["mejoras_fecha"]?>',
                                                              '<?=$bus_consultar["mejoras_valor"]?>',
                                                              '<?=$bus_consultar["mejoras_fecha2"]?>',
                                                              '<?=$bus_consultar["mejoras_valor2"]?>',
                                                              '<?=$bus_consultar["mejoras_fecha3"]?>',
                                                              '<?=$bus_consultar["mejoras_valor3"]?>',
                                                              '<?=$bus_consultar["mejoras_fecha4"]?>',
                                                              '<?=$bus_consultar["mejoras_valor4"]?>',
                                                              '<?=$bus_consultar["mejoras_fecha5"]?>',
                                                              '<?=$bus_consultar["mejoras_valor5"]?>',
                                                              '<?=$bus_consultar["avaluo_provicional"]?>',
                                                              '<?=$bus_consultar["planos_esquemas_fotocopias"]?>',
                                                              '<?=$bus_consultar["preparado_por"]?>',
                                                              '<?=$bus_consultar["lugar"]?>',
                                                              '<?=$bus_consultar["fecha"]?>',
                                                              '<?=$bus_consultar["cargo"]?>',
                                                              '<?=$bus_consultar["organizacion"]?>',
                                                              '<?=$bus_consultar["codigo_bien"]?>'), opener.document.getElementById('linkEliminar').style.display = 'block', window.close()">
                    </td>
   		        </tr>
          <?
          }
          ?>
        </table>
 <?
 registra_transaccion("Listar Edificios",$login,$fh,$pc,'documentos_recibidos');
 ?>
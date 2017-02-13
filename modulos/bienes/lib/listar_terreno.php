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
  $query = "select * from terrenos";
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
		  echo $query;
		  $sql_consultar = mysql_query($query); 
          while($bus_consultar = mysql_fetch_array($sql_consultar)){
		  ?>
                  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                    <td class='Browse' align='left' width="10%" style="font-size:10px"><?=$bus_consultar["estado_municipio"]?></td>
                    <td class='Browse' align='left' width="37%" style="font-size:10px"><?=$bus_consultar["denominacion_inmueble"]?></td>
                    <td class='Browse' align='right' width="11%" style="font-size:10px"><?=$bus_consultar["ubicacion_territorio"]?></td>
                    <td class='Browse' align='left' style="font-size:10px">&nbsp;<?=$bus_consultar["area_total_terreno_hectarias"]?></td>
                    <td align="center" valign="middle" class='Browse'>
                        
                        <img src="../../../imagenes/validar.png"
                            style="cursor:pointer"
                            onClick="opener.seleccionarDatos('<?=$bus_consultar["idterrenos"]?>',
                                                          '<?=$bus_consultar["idtipo_movimiento"]?>',
                                                          '<?=$bus_consultar["iddetalle_catalogo_bienes"]?>',
                                                          '<?=$bus_consultar["estado_municipio"]?>',
                                                          '<?=$bus_consultar["denominacion_inmueble"]?>',
                                                          '<?=$bus_consultar["clasificacion_agricultura"]?>',
                                                          '<?=$bus_consultar["clasificacion_ganaderia"]?>',
                                                          '<?=$bus_consultar["clasificacion_mixto_agropecuario"]?>',
                                                          '<?=$bus_consultar["clasificacion_otros"]?>',
                                                          '<?=$bus_consultar["ubicacion_municipio"]?>',
                                                          '<?=$bus_consultar["ubicacion_territorio"]?>',
                                                          '<?=$bus_consultar["area_total_terreno_hectarias"]?>',
                                                          '<?=$bus_consultar["area_total_terreno_metros"]?>',
                                                          '<?=$bus_consultar["area_construccion_metros"]?>',
                                                          '<?=$bus_consultar["tipografia_plana"]?>',
                                                          '<?=$bus_consultar["tipografia_semiplana"]?>',
                                                          '<?=$bus_consultar["tipografia_pendiente"]?>',
                                                          '<?=$bus_consultar["tipografia_muypendiente"]?>',
                                                          '<?=$bus_consultar["cultivos_permanentes"]?>',
                                                          '<?=$bus_consultar["cultivos_deforestados"]?>',
                                                          '<?=$bus_consultar["otros_bosques"]?>',
                                                          '<?=$bus_consultar["otros_tierras_incultas"]?>',
                                                          '<?=$bus_consultar["otros_noaprovechables"]?>',
                                                          '<?=$bus_consultar["potreros_naturales"]?>',
                                                          '<?=$bus_consultar["potreros_cultivados"]?>',
                                                          '<?=$bus_consultar["recursos_cursos"]?>',
                                                          '<?=$bus_consultar["recursos_manantiales"]?>',
                                                          '<?=$bus_consultar["recursos_canales"]?>',
                                                          '<?=$bus_consultar["recursos_embalses"]?>',
                                                          '<?=$bus_consultar["recursos_pozos"]?>',
                                                          '<?=$bus_consultar["recursos_acuaductos"]?>',
                                                          '<?=$bus_consultar["recursos_otros"]?>',
                                                          '<?=$bus_consultar["cercas_longitud"]?>',
                                                          '<?=$bus_consultar["cercas_estantes"]?>',
                                                          '<?=$bus_consultar["cercas_material"]?>',
                                                          '<?=$bus_consultar["vias_interiores"]?>',
                                                          '<?=$bus_consultar["otras_bienhechurias"]?>',
                                                          '<?=$bus_consultar["linceros"]?>',
                                                          '<?=$bus_consultar["estudio_legal"]?>',
                                                          '<?=$bus_consultar["contabilidad_fecha"]?>',
                                                          '<?=$bus_consultar["contabilidad_valor"]?>',
                                                          '<?=$bus_consultar["adicionales_fecha"]?>',
                                                          '<?=$bus_consultar["adicionales_valor"]?>',
                                                          '<?=$bus_consultar["adicionales_fecha2"]?>',
                                                          '<?=$bus_consultar["adicionales_valor2"]?>',
                                                          '<?=$bus_consultar["adicionales_fecha3"]?>',
                                                          '<?=$bus_consultar["adicionales_valor3"]?>',
                                                          '<?=$bus_consultar["adicionales_fecha4"]?>',
                                                          '<?=$bus_consultar["adicionales_valor4"]?>',
                                                          '<?=$bus_consultar["adicionales_fecha5"]?>',
                                                          '<?=$bus_consultar["adicionales_valor5"]?>',
                                                          '<?=$bus_consultar["avaluo_hectarias"]?>',
                                                          '<?=$bus_consultar["avaluo_bs"]?>',
                                                          '<?=$bus_consultar["avaluo_hectarias2"]?>',
                                                          '<?=$bus_consultar["avaluo_bs2"]?>',
                                                          '<?=$bus_consultar["avaluo_hectarias3"]?>',
                                                          '<?=$bus_consultar["avaluo_bs3"]?>',
                                                          '<?=$bus_consultar["planos_esquemas_fotografias"]?>',
                                                          '<?=$bus_consultar["preparado_por"]?>',
                                                          '<?=$bus_consultar["lugar"]?>',
                                                          '<?=$bus_consultar["fecha"]?>',
                                                          '<?=$bus_consultar["cargo"]?>',
                                                          '<?=$bus_consultar["codigo_bien"]?>',
                                                          '<?=$bus_consultar["organizacion"]?>'),
                                                          opener.document.getElementById('linkEliminar').style.display = 'block', 
                                                          window.close()">
                    </td>
   		        </tr>
          <?
          }
          ?>
        </table>
 <?
 registra_transaccion("Listar Terrenos",$login,$fh,$pc,'documentos_recibidos');
 ?>
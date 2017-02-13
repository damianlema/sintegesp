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
       <input type="hidden" id="tipo_busqueda" name="tipo_busqueda" value="<?=$_REQUEST["tipo_busqueda"]?>">
       <table width="552" border="0" align="center">
  <tr>
            <td width="32%"> Denominacion Inmueble:</td>
      <td width="24%"><input type="text" name="denominacion_inmueble" id="denominacion_inmueble"></td>
        <td width="10%"> Tipo:</td>
          <td width="19%">
          <select name="tipo_inmueble">
            	<option value="terreno">Terreno</option>
           		<option value="edificio">Edificio</option>
            </select>
            </td>
          <td width="15%" align="center"><input type="submit" name="buscar" id="buscar" value="Buscar" class="button"></td>
         </tr>
      </table>
    </form>
        
  <?
  	if($_POST){
  
	  if($_POST["tipo_inmueble"] == "edificio"){
		$tabla = "edificios";
	  }else{
		$tabla = "terrenos";
	  }
		$query = "select dtb.codigo as codigo_catalogo,
						dtb.denominacion as denominacion_catalogo,
						dtb.iddetalle_catalogo_bienes as iddetalle_catalogo_bienes,
						".$tabla.".id".$tabla.",
						".$tabla.".codigo_bien,
						".$tabla.".organizacion,
						".$tabla.".denominacion_inmueble as denominacion_inmueble
							from 
						".$tabla."
						LEFT JOIN detalle_catalogo_bienes dtb ON (".$tabla.".iddetalle_catalogo_bienes = dtb.iddetalle_catalogo_bienes)";
		
		if($_POST["denominacion_inmueble"] != ""){
			$query .= " where ".$tabla.".denominacion_inmueble like '%".$_POST["denominacion_inmueble"]."%'";
		}
		//echo $query;		
  ?>      <br />
  
        <table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="98%">
          <thead>
          <tr>
            <td width="37%" align="center" class="Browse" style="font-size:9px">Denominacion del Inmueble</td>
            <td width="6%" align="center" class="Browse" style="font-size:9px">Seleccionar</td>
          </tr>
          </thead>
          <?
		  //echo $query;
		  $sql_consultar = mysql_query($query)or die(mysql_error()); 
		  //echo $query;
          while($bus_consultar = mysql_fetch_array($sql_consultar)){
		  ?>
                  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                    <td class='Browse' align='left' width="37%" style="font-size:10px">&nbsp;<?=$bus_consultar["denominacion_inmueble"]?></td>
                    <td align="center" valign="middle" class='Browse'>
                       <?
                       if($_REQUEST["tipo_busqueda"] == "movimiento_individual_nuevo"){
					   ?>
                        <img src="../../../imagenes/validar.png"
                            style="cursor:pointer"
                            onClick="opener.document.getElementById('codigo_bien_bienes_nuevos').value = '<?=$bus_consultar["codigo_bien"]?>',
                            <?
                                    if($_POST["tipo_inmueble"] == "edificio"){
										?>
										opener.document.getElementById('idcodigo_bien_bienes_nuevos').value = '<?=$bus_consultar["idedificios"]?>',
										<?
									}else{
										?>
										opener.document.getElementById('idcodigo_bien_bienes_nuevos').value = '<?=$bus_consultar["idterrenos"]?>',
										<?
									}
									?>
                                    opener.document.getElementById('codigo_catalogo_bienes_nuevos').value = '(<?=$bus_consultar["codigo_catalogo"]?>) <?=$bus_consultar["denominacion_catalogo"]?>',
                                    opener.document.getElementById('idcodigo_catalogo_bienes_nuevos').value = '<?=$bus_consultar["iddetalle_catalogo_bienes"]?>',
                                     window.close()">
                     <?
                     }else if($_REQUEST["tipo_busqueda"] == "movimiento_individual_existente_desincorporacion"){
					   ?>
                        <img src="../../../imagenes/validar.png"
                            style="cursor:pointer"
                            onClick="opener.document.getElementById('codigo_bien_existente_desincorporacion').value = '<?=$bus_consultar["codigo_bien"]?>',
                            <?
                                    if($_POST["tipo_inmueble"] == "edificio"){
										?>
										opener.document.getElementById('idcodigo_bien_existente_desincorporacion').value = '<?=$bus_consultar["idedificios"]?>',
										<?
									}else{
										?>
										opener.document.getElementById('idcodigo_bien_existente_desincorporacion').value = '<?=$bus_consultar["idterrenos"]?>',
										<?
									}
									?>
                                    opener.document.getElementById('codigo_catalogo_existente_desincorporacion').value = '(<?=$bus_consultar["codigo_catalogo"]?>) <?=$bus_consultar["denominacion_catalogo"]?>',
                                    opener.document.getElementById('idcodigo_catalogo_existente_desincorporacion').value = '<?=$bus_consultar["iddetalle_catalogo_bienes"]?>',
                                     window.close()">
                     <?
                     
					 }else if($_REQUEST["tipo_busqueda"] == "movimiento_individual_existente_incorporacion"){
					   ?>
                        <img src="../../../imagenes/validar.png"
                            style="cursor:pointer"
                            onClick="opener.document.getElementById('codigo_bien_existente_incorporacion').value = '<?=$bus_consultar["codigo_bien"]?>',
                            <?
                                    if($_POST["tipo_inmueble"] == "edificio"){
										?>
										opener.document.getElementById('idcodigo_bien_existente_incorporacion').value = '<?=$bus_consultar["idedificios"]?>',
										<?
									}else{
										?>
										opener.document.getElementById('idcodigo_bien_existente_incorporacion').value = '<?=$bus_consultar["idterrenos"]?>',
										<?
									}
									?>
                                    opener.document.getElementById('codigo_catalogo_existente_incorporacion').value = '(<?=$bus_consultar["codigo_catalogo"]?>) <?=$bus_consultar["denominacion_catalogo"]?>',
                                    opener.document.getElementById('idcodigo_catalogo_existente_incorporacion').value = '<?=$bus_consultar["iddetalle_catalogo_bienes"]?>',
                                     window.close()">
                     <?
                     }else{
					 ?>
					 <img src="../../../imagenes/validar.png"
                            style="cursor:pointer"
                            onClick="opener.document.getElementById('tablaIngresarModificar').style.display = 'block',
                            		opener.document.getElementById('base').style.display = 'block'
                                    opener.document.getElementById('tipo_inmueble').value = '<?=$_POST["tipo_inmueble"]?>',
                                    opener.document.getElementById('denominacion_inmueble').value = '<?=$bus_consultar["denominacion_inmueble"]?>',
                                    <?
                                    if($_POST["tipo_inmueble"] == "edificio"){
										?>
										opener.document.getElementById('idinmueble').value = '<?=$bus_consultar["idedificios"]?>',
										<?
									}else{
										?>
										opener.document.getElementById('idinmueble').value = '<?=$bus_consultar["idterrenos"]?>',
										<?
									}
									?>
                                    opener.consultarDatos(),
                                     window.close()">
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
 registra_transaccion("Listar Edificios",$login,$fh,$pc,'documentos_recibidos');
 ?>
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
  
	  $query = "select muebles.idmuebles as idmuebles,
	  					muebles.codigo_bien as codigo_bien,
	  					muebles.especificaciones as especificaciones_mueble,
						muebles.idorganizacion as idorganizacion,
						muebles.idnivel_organizacion as idnivel_organizacion,
						organizacion.denominacion as organizacion,
						niveles_organizacionales.codigo as codigo_nivel,
						niveles_organizacionales.denominacion as nivel_organizacional,
						detalle_catalogo_bienes.codigo as codigo_catalogo,
						detalle_catalogo_bienes.denominacion as denominacion_catalogo,
						detalle_catalogo_bienes.iddetalle_catalogo_bienes as iddetalle_catalogo_bienes
	   						from 
						muebles,
						organizacion,
						niveles_organizacionales,
						detalle_catalogo_bienes
							where
						organizacion.idorganizacion = muebles.idorganizacion
						and niveles_organizacionales.idniveles_organizacionales = muebles.idnivel_organizacion
						and muebles.codigo_bien like '%".$_POST["codigo"]."%'
						and muebles.idcatalogo_bienes = detalle_catalogo_bienes.iddetalle_catalogo_bienes";
		//echo $query;		
  ?>      <br />
  
        <table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="98%">
          <thead>
          <tr>
            <td width="37%" align="center" class="Browse" style="font-size:9px">Codigo del Bien</td>
            <td width="37%" align="center" class="Browse" style="font-size:9px">Organizacion</td>
            <td width="37%" align="center" class="Browse" style="font-size:9px">Nivel Organizacional</td>
            
            <td width="37%" align="center" class="Browse" style="font-size:9px">Denominacion del Mueble</td>
            <td width="6%" align="center" class="Browse" style="font-size:9px">Seleccionar</td>
          </tr>
          </thead>
          <?
		  $sql_consultar = mysql_query($query)or die(mysql_error()); 
          while($bus_consultar = mysql_fetch_array($sql_consultar)){
		  
		  if($_REQUEST["tipo_busqueda"] == "movimiento_individual_nuevo"){
		  
		  ?>
                  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" onClick=" 
                            		 opener.document.getElementById('idcodigo_bien_bienes_nuevos').value = '<?=$bus_consultar["idmuebles"]?>',
                                     opener.document.getElementById('codigo_bien_bienes_nuevos').value = '<?=$bus_consultar["codigo_bien"]?>',
                                     opener.document.getElementById('codigo_catalogo_bienes_nuevos').value = '(<?=$bus_consultar["codigo_catalogo"]?>) <?=$bus_consultar["denominacion_catalogo"]?>',
                                     opener.document.getElementById('idcodigo_catalogo_bienes_nuevos').value = '<?=$bus_consultar["iddetalle_catalogo_bienes"]?>',
                                     window.close()">
                  	
                    <td class='Browse' align='left' width="37%" style="font-size:10px"><?=$bus_consultar["codigo_bien"]?></td>
                    <td class='Browse' align='left' width="37%" style="font-size:10px"><?=$bus_consultar["organizacion"]?></td>
                    <td class='Browse' align='left' width="37%" style="font-size:10px"><?=$bus_consultar["nivel_organizacional"]?></td>
                    
                    <td class='Browse' align='left' width="37%" style="font-size:10px"><?=$bus_consultar["especificaciones_mueble"]?></td>
                    <td align="center" valign="middle" class='Browse'>
                        <img src="../../../imagenes/validar.png"
                            style="cursor:pointer"
                            onClick="opener.document.getElementById('idcodigo_bien_bienes_nuevos').value = '<?=$bus_consultar["idmuebles"]?>',
                                     opener.document.getElementById('codigo_bien_bienes_nuevos').value = '<?=$bus_consultar["codigo_bien"]?>',
                                     opener.document.getElementById('codigo_catalogo_bienes_nuevos').value = '(<?=$bus_consultar["codigo_catalogo"]?>) <?=$bus_consultar["denominacion_catalogo"]?>',
                                     opener.document.getElementById('idcodigo_catalogo_bienes_nuevos').value = '<?=$bus_consultar["iddetalle_catalogo_bienes"]?>',
                                     window.close()">
                    </td>
   		        </tr>
          <?
		  
		  
		 }else if($_REQUEST["tipo_busqueda"] == "movimiento_individual_existente_desincorporacion"){
					   ?>
                  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" onClick=" 
                            		 opener.document.getElementById('idcodigo_bien_existente_desincorporacion').value = '<?=$bus_consultar["idmuebles"]?>',
                                     opener.document.getElementById('codigo_bien_existente_desincorporacion').value = '<?=$bus_consultar["codigo_bien"]?>',
                                     opener.document.getElementById('codigo_catalogo_existente_desincorporacion').value = '(<?=$bus_consultar["codigo_catalogo"]?>) <?=$bus_consultar["denominacion_catalogo"]?>',
                                     opener.document.getElementById('idcodigo_catalogo_existente_desincorporacion').value = '<?=$bus_consultar["iddetalle_catalogo_bienes"]?>',
                                     opener.document.getElementById('especificaciones_existente_desincorporacion').value = '<?=$bus_consultar["especificaciones_mueble"]?>',
                                     window.close()">
                  	
                    <td class='Browse' align='left' width="37%" style="font-size:10px"><?=$bus_consultar["codigo_bien"]?></td>
                    <td class='Browse' align='left' width="37%" style="font-size:10px"><?=$bus_consultar["organizacion"]?></td>
                    <td class='Browse' align='left' width="37%" style="font-size:10px"><?=$bus_consultar["nivel_organizacional"]?></td>
                    
                    <td class='Browse' align='left' width="37%" style="font-size:10px"><?=$bus_consultar["especificaciones_mueble"]?></td>
                    <td align="center" valign="middle" class='Browse'>
                        <img src="../../../imagenes/validar.png"
                            style="cursor:pointer"
                            onClick=" 
                            		 opener.document.getElementById('idcodigo_bien_existente_desincorporacion').value = '<?=$bus_consultar["idmuebles"]?>',
                                     opener.document.getElementById('codigo_bien_existente_desincorporacion').value = '<?=$bus_consultar["codigo_bien"]?>',
                                     opener.document.getElementById('codigo_catalogo_existente_desincorporacion').value = '(<?=$bus_consultar["codigo_catalogo"]?>) <?=$bus_consultar["denominacion_catalogo"]?>',
                                     opener.document.getElementById('idcodigo_catalogo_existente_desincorporacion').value = '<?=$bus_consultar["iddetalle_catalogo_bienes"]?>',
                                     opener.document.getElementById('especificaciones_existente_desincorporacion').value = '<?=$bus_consultar["especificaciones_mueble"]?>'
                                     window.close()">
                    </td>
   		        </tr>
          <?
                     }else if($_REQUEST["tipo_busqueda"] == "movimiento_individual_existente_incorporacion"){
					   ?>
                  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" onClick=" 
                            		 opener.document.getElementById('idcodigo_bien_existente_incorporacion').value = '<?=$bus_consultar["idmuebles"]?>',
                                     opener.document.getElementById('codigo_bien_existente_incorporacion').value = '<?=$bus_consultar["codigo_bien"]?>',
                                     opener.document.getElementById('codigo_catalogo_existente_incorporacion').value = '(<?=$bus_consultar["codigo_catalogo"]?>) <?=$bus_consultar["denominacion_catalogo"]?>',
                                     opener.document.getElementById('idcodigo_catalogo_existente_incorporacion').value = '<?=$bus_consultar["iddetalle_catalogo_bienes"]?>',
                                     window.close()">
                  	
                    <td class='Browse' align='left' width="37%" style="font-size:10px"><?=$bus_consultar["codigo_bien"]?></td>
                    <td class='Browse' align='left' width="37%" style="font-size:10px"><?=$bus_consultar["organizacion"]?></td>
                    <td class='Browse' align='left' width="37%" style="font-size:10px"><?=$bus_consultar["nivel_organizacional"]?></td>
                    
                    <td class='Browse' align='left' width="37%" style="font-size:10px"><?=$bus_consultar["especificaciones_mueble"]?></td>
                    <td align="center" valign="middle" class='Browse'>
                        <img src="../../../imagenes/validar.png"
                            style="cursor:pointer"
                            onClick=" 
                            		 opener.document.getElementById('idcodigo_bien_existente_incorporacion').value = '<?=$bus_consultar["idmuebles"]?>',
                                     opener.document.getElementById('codigo_bien_existente_incorporacion').value = '<?=$bus_consultar["codigo_bien"]?>',
                                     opener.document.getElementById('codigo_catalogo_existente_incorporacion').value = '(<?=$bus_consultar["codigo_catalogo"]?>) <?=$bus_consultar["denominacion_catalogo"]?>',
                                     opener.document.getElementById('idcodigo_catalogo_existente_incorporacion').value = '<?=$bus_consultar["iddetalle_catalogo_bienes"]?>',
                                     window.close()">
                    </td>
   		        </tr>
          <?
                     }else{
		  ?>
                  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" onClick="opener.document.getElementById('tipo_bien').value = 'mueble', 
                            		 opener.document.getElementById('idbien').value = '<?=$bus_consultar["idmuebles"]?>',
                                     opener.document.getElementById('codigo_bien').value = '<?=$bus_consultar["codigo_bien"]?>',
                                     opener.document.getElementById('organizacion_mueble').value = '<?=$bus_consultar["idorganizacion"]?>',
                                     opener.seleccionarNivelOrganizacionalMueble('<?=$bus_consultar["idorganizacion"]?>'),
                                     opener.seleccionarNivelEmergente('<?=$bus_consultar["idnivel_organizacion"]?>'),
                                     opener.document.getElementById('organizacion_mueble').disabled = true,
                                     opener.document.getElementById('catalogo_bienes').value = '(<?=$bus_consultar["codigo_catalogo"]?>) <?=$bus_consultar["denominacion_catalogo"]?>',
                                     opener.document.getElementById('idcatalogo_bienes').value = '<?=$bus_consultar["iddetalle_catalogo_bienes"]?>',
                                     opener.document.getElementById('catalogo_bienes').disabled = true,
                                    opener.document.getElementById('especificaciones').disabled = true,
                                     opener.document.getElementById('especificaciones').value = '<?=$bus_consultar["especificaciones_mueble"]?>',
                                     window.close()">
                  	
                    <td class='Browse' align='left' width="37%" style="font-size:10px"><?=$bus_consultar["codigo_bien"]?></td>
                    <td class='Browse' align='left' width="37%" style="font-size:10px"><?=$bus_consultar["organizacion"]?></td>
                    <td class='Browse' align='left' width="37%" style="font-size:10px"><?=$bus_consultar["nivel_organizacional"]?></td>
                    
                    <td class='Browse' align='left' width="37%" style="font-size:10px"><?=$bus_consultar["especificaciones_mueble"]?></td>
                    <td align="center" valign="middle" class='Browse'>
                        <img src="../../../imagenes/validar.png"
                            style="cursor:pointer"
                            onClick="opener.document.getElementById('tipo_bien').value = 'mueble', 
                            		 opener.document.getElementById('idbien').value = '<?=$bus_consultar["idmuebles"]?>',
                                     opener.document.getElementById('codigo_bien').value = '<?=$bus_consultar["codigo_bien"]?>',
                                     opener.document.getElementById('organizacion_mueble').value = '<?=$bus_consultar["idorganizacion"]?>',
                                     opener.seleccionarNivelOrganizacionalMueble('<?=$bus_consultar["idorganizacion"]?>'),
                                     opener.seleccionarNivelEmergente('<?=$bus_consultar["idnivel_organizacion"]?>'),
                                     opener.document.getElementById('organizacion_mueble').disabled = true,
                                     opener.document.getElementById('catalogo_bienes').value = '(<?=$bus_consultar["codigo_catalogo"]?>) <?=$bus_consultar["denominacion_catalogo"]?>',
                                     opener.document.getElementById('idcatalogo_bienes').value = '<?=$bus_consultar["iddetalle_catalogo_bienes"]?>',
                                     opener.document.getElementById('catalogo_bienes').disabled = true,
                                    opener.document.getElementById('especificaciones').disabled = true,
                                     opener.document.getElementById('especificaciones').value = '<?=$bus_consultar["especificaciones_mueble"]?>',
                                     window.close()">
                    </td>
   		        </tr>
          <?
		  
		  }
		  
          }
          ?>
        </table>
 <?
 }
 registra_transaccion("Listar Muebles",$login,$fh,$pc,'muebles');
 ?>
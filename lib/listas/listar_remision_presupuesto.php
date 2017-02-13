<?
include("../../conf/conex.php");
$conection_db = conectarse();
include("../../funciones/funciones.php");




?>
<script type="text/javascript" src="../../js/funciones.js"></script>
<script type="text/javascript" src="../../js/function.js"></script>
<link href="../../css/theme/green/main.css" rel="stylesheet" type="text/css">
  <style type="text/css"> @import url("../../css/theme/calendar-win2k-cold-1.css"); </style> 
  
  <? if($_REQUEST["destino"] == "Compras") { 
  		$sql_configuracion = mysql_query("select * from configuracion_compras");
	  	$bus_configuracion = mysql_fetch_array($sql_configuracion);
		?>
     <h2 align="center">Remision de Compras y Servicios</h2>
	<? } else if($_REQUEST["destino"] == "Presupuesto"){ 
		$sql_configuracion = mysql_query("select * from configuracion_presupuesto");
	  	$bus_configuracion = mysql_fetch_array($sql_configuracion);
	?>
     <h2 align="center">Remision de Presupuesto</h2>
    <? } else if($_REQUEST["destino"] == "Administracion"){ 
		$sql_configuracion = mysql_query("select * from configuracion_administracion");
	  	$bus_configuracion = mysql_fetch_array($sql_configuracion);
	?>
     <h2 align="center">Remision de Administracion</h2>
    <? } ?>
     <br />
     	<h2 class="sqlmVersion"></h2>
<br> 
     <form action="" method="post"> 
       
       <input type="hidden" name="destino" id="destino" <? if($_GET["destino"]){ echo "value='".$_GET["destino"]."'";}else{echo "value='".$_POST["destino"]."'";}?>>
       <table width="511" border="0" align="center">
<tr>
            <td width="35%"><div align="center">Estado de la Remision:<br>
                <select name="estado_remision">
                  <option value="elaboracion">En Elaboraci&oacute;n</option>
                  <option value="enviado">Enviados</option>
                  <option value="recibido">Recibidos</option>
                  <option value="anulado">Anulados</option>
                  </select>
      </div></td>
  <td width="47%"><div align="center">Justificacion:<br>
      <input type="text" name="texto_busqueda" id="texto_busqueda">
  </div></td>
      <td width="18%"><label>
        <input type="submit" name="buscar" id="buscar" value="Buscar" class="button">
      </label></td>
         </tr>
        </table>
     </form>
        
  <?
  //echo $_REQUEST["destino"];
  if($_POST["buscar"]){
			$sql_solicitudes = mysql_query("select * from remision_presupuesto where justificacion like '%".$_POST["texto_busqueda"]."%' 
											and estado = '".$_POST["estado_remision"]."' and iddependencia_origen =".$bus_configuracion["iddependencia"]."")or die(mysql_error());
		
			
  ?>      <br />
  
        <table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="100%">
          <thead>
          <tr>
          	<td width="8%" align="center" class="Browse">No. Documento</td>
            <td width="30%" align="center" class="Browse">Fecha Elaboracion</td>
            <td width="51%" align="center" class="Browse">Asunto</td>
            <td width="6%" align="center" class="Browse">Justificacion</td>
            <td width="5%" align="center" class="Browse">Acciones</td>
          </tr>
          </thead>
          <? 
		  
         
          while($bus_solicitudes = mysql_fetch_array($sql_solicitudes)){

		  ?>
                  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onclick="window.onUnload = window.opener.consultarRemisiones('<?=$bus_solicitudes["idremision_presupuesto"]?>'),  window.close()">
                     <td class='Browse' align='left'>&nbsp;<?=$bus_solicitudes["numero_documento"]?></td>
                    <td class='Browse' align='left'><?=$bus_solicitudes["fecha_elaboracion"]?></td>
                    <td class='Browse' align='left'><?=$bus_solicitudes["asunto"]?></td>
                    <td class='Browse' align='left'><?=$bus_solicitudes["justificacion"]?></td>
                    
                    <?
                    //if($_REQUEST["destino"] == "Compras"){
                    ?>
                        <td class='Browse' align="center">
                        <a href="#" onClick="window.onUnload = window.opener.consultarRemisiones('<?=$bus_solicitudes["idremision_presupuesto"]?>'),  window.close()">
                        	<img src="../../imagenes/validar.png">
                        </a>
                        </td>
                    <?
                    //}
                    ?>
                    
                  </tr>
          <?
          }
		  
          ?>
          
        </table>
 <?
 }
 ?>
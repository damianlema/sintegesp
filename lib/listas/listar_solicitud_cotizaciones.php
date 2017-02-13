<?
session_start();
include("../../conf/conex.php");
$conection_db = conectarse();
include("../../funciones/funciones.php");




?>
<script type="text/javascript" src="../../js/funciones.js"></script>
<script type="text/javascript" src="../../js/function.js"></script>
<link href="../../css/theme/green/main.css" rel="stylesheet" type="text/css">
  <style type="text/css"> @import url("../../css/theme/calendar-win2k-cold-1.css"); </style>   
     <h2 align="center">Consultas de Precios</h2>
     <br />
     	<h2 class="sqlmVersion"></h2>
<br> 
     <form action="" method="post"> 
       
       <input type="hidden" name="destino" id="destino" <? if($_GET["destino"]){ echo "value='".$_GET["destino"]."'";}else{echo "value='".$_POST["destino"]."'";}?>>
       <table width="511" border="0" align="center">
       		<tr>
              <td width="30%" align="right">Texto a Buscar:</td>
              <td><input type="text" name="texto_busqueda" id="texto_busqueda" size="45"></td>
              <td width="18%"><input type="submit" name="buscar" id="buscar" value="Buscar" class="button"></td>
            </tr>
        </table>
     </form>
        
  <?
 
  if($_POST["buscar"]){
			$sql_solicitudes = mysql_query("select * FROM 
										solicitud_cotizacion, 
										tipos_documentos
											WHERE 
										solicitud_cotizacion.justificacion like '%".$_POST["texto_busqueda"]."%'
										and tipos_documentos.modulo like '%-".$_SESSION["modulo"]."-%'
										group by solicitud_cotizacion.idsolicitud_cotizacion")or die("ERROR 1 ".mysql_error());
		
  ?>      <br />
  
        <table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="100%">
          <thead>
          <tr>
          	<td width="9%" align="center" class="Browse">No. Documento</td>
            <td width="80%" align="center" class="Browse">Justificacion</td>
            <td width="6%" align="center" class="Browse">Estado</td>
            <td width="5%" align="center" class="Browse">Acciones</td>
          </tr>
          </thead>
          <? 
          while($bus_solicitudes = mysql_fetch_array($sql_solicitudes)){
		  ?>
                  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer"
                  onclick="
                  <?
                  if($_REQUEST["destino"] == "solicitudes"){
				  ?>
				  window.onUnload = window.opener.consultarSolicitud(<?=$bus_solicitudes["idsolicitud_cotizacion"]?>),  window.close()
				  <?
				  }
				  if($_REQUEST["destino"] == "orden_compra"){
				  ?>
				  window.onUnload = window.opener.consultarOrdenCompra(<?=$bus_solicitudes["idsolicitud_cotizacion"]?>),  window.close()
				  <?
				  }
				  ?>
                  ">
                    <td class='Browse' align='left'>&nbsp;<?=$bus_solicitudes["numero"]?></td>
                    <td class='Browse' align='left'>&nbsp;<?=$bus_solicitudes["justificacion"]?></td>
                    <td class='Browse' align='left'>&nbsp;<?=$bus_solicitudes["estado"]?></td>
                    
                    <?
                    if($_REQUEST["destino"] == "solicitudes"){
                    ?>
                        <td class='Browse' align="center"><a href="#" onClick="window.onUnload = window.opener.consultarSolicitud(<?=$bus_solicitudes["idsolicitud_cotizacion"]?>),  window.close()"><img src="../../imagenes/validar.png"></a></td>
                    <?
                    }
					
                    if($_REQUEST["destino"] == "orden_compra"){
                    ?>
                        <td class='Browse' align="center"><a href="#" onClick="window.onUnload = window.opener.consultarOrdenCompra(<?=$bus_solicitudes["idsolicitud_cotizacion"]?>),  window.close()"><img src="../../imagenes/validar.png"></a></td>
                    <?
                    }
                    ?>
                    
                  </tr>
          <?
          }
		  
          ?>
          
        </table>
 <?
 }
 ?>
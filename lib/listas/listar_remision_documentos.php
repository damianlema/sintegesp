<?
session_start();
include("../../conf/conex.php");
$conection_db = conectarse();
include("../../funciones/funciones.php");
extract($_POST);
if($_GET["eliminar"]){
	$sql_eliminar = mysql_query("delete from remision_documentos where idremision_documentos = '".$_GET["eliminar"]."'");
}


?>
<script type="text/javascript" src="../../js/funciones.js"></script>
<script type="text/javascript" src="../../js/function.js"></script>
<link href="../../css/theme/green/main.css" rel="stylesheet" type="text/css">
  <style type="text/css"> @import url("../../css/theme/calendar-win2k-cold-1.css"); </style> 
   <? if ($modulo == 1){
		  $sql_configuracion = mysql_query("select * from configuracion_rrhh");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion); ?>
  		  <h2 align="center">Remisi&oacute;n de Recursos Humanos</h2>
      <?
	  }else if($modulo == 2){
		  $sql_configuracion = mysql_query("select * from configuracion_presupuesto");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion); ?>
  		  <h2 align="center">Remisi&oacute;n de Presupuesto</h2>
          <?
	  }else if($modulo == 3){
		  $sql_configuracion = mysql_query("select * from configuracion_compras");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion); ?>
          <h2 align="center">Remisi&oacute;n de Compras y Servicios</h2>
	  <?
  	  }else if($modulo == 4){
		  $sql_configuracion = mysql_query("select * from configuracion_administracion");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion); ?>
          <h2 align="center">Remisi&oacute;n de Administraci&oacute;n</h2> 
	  <?
	  }else if($modulo == 6){
		  $sql_configuracion = mysql_query("select * from configuracion_tributos");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion); ?>
          <h2 align="center">Remisi&oacute;n de Tributos Internos</h2> 
	  <?
	  }else if($modulo == 7){
		  $sql_configuracion = mysql_query("select * from configuracion_tesoreria");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion); ?>
          <h2 align="center">Remisi&oacute;n de Tesoreria</h2> 
	  <?
	  }else if($modulo == 5){
		  $sql_configuracion = mysql_query("select * from configuracion_contabilidad");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion); ?>
          <h2 align="center">Remisi&oacute;n de Tesoreria</h2> 
	  <?
	  }else if($modulo == 13){
		  $sql_configuracion = mysql_query("select * from configuracion_nomina");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion); ?>
          <h2 align="center">Remisi&oacute;n de N&oacute;mina</h2> 
	  <?
	  }else if($modulo == 12){
		  $sql_configuracion = mysql_query("select * from configuracion_despacho");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion); ?>
          <h2 align="center">Remisi&oacute;n de Despacho</h2> 
	  <?
	  } ?>
     <br />
     	<h2 class="sqlmVersion"></h2>
<br> 
     <form action="" method="post"> 
       
       <input type="hidden" name="destino" id="destino" <? if($_GET["destino"]){ echo "value='".$_GET["destino"]."'";}else{echo "value='".$_POST["destino"]."'";}?>>
       <table width="511" border="0" align="center">
<tr>
            <td width="35%"><div align="center">Estado de la Remision:<br>
                <select name="estado_remision">
                  <option <? if($_REQUEST["estado_remision"] == "elaboracion"){echo "selected";}?> value="elaboracion">En Elaboraci&oacute;n</option>
                  <option <? if($_REQUEST["estado_remision"] == "enviado"){echo "selected";}?> value="enviado">Enviados</option>
                  <option <? if($_REQUEST["estado_remision"] == "recibido"){echo "selected";}?> value="recibido">Recibidos</option>
                  <option <? if($_REQUEST["estado_remision"] == "anulado"){echo "selected";}?> value="anulado">Anulados</option>
                  </select>
      </div></td>
  <td width="47%"><div align="center">Justificacion:<br>
      <input type="text" name="texto_busqueda" id="texto_busqueda"  value="<?=$_REQUEST["texto_busqueda"]?>">
  </div></td>
      <td width="18%"><label>
        <input type="submit" name="buscar" id="buscar" value="Buscar" class="button">
      </label></td>
         </tr>
        </table>
     </form>
        
  <?
  //echo $_REQUEST["destino"];
  if($_REQUEST["buscar"]){
			$sql_solicitudes = mysql_query("select * from remision_documentos where justificacion like '%".$_REQUEST["texto_busqueda"]."%' 
											and estado = '".$_REQUEST["estado_remision"]."' and iddependencia_origen =".$bus_configuracion["iddependencia"]."")or die(mysql_error());
		
			
  ?>      <br />
  
        <table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="100%">
          <thead>
          <tr>
          	<td width="7%" align="center" class="Browse">No. Documento</td>
            <td width="12%" align="center" class="Browse">Fecha Elaboracion</td>
            <td width="24%" align="center" class="Browse">Asunto</td>
            <td width="51%" align="center" class="Browse">Justificacion</td>
            <td width="6%" align="center" class="Browse" colspan="2">Acciones</td>
          </tr>
          </thead>
          <? 
		  
         
          while($bus_solicitudes = mysql_fetch_array($sql_solicitudes)){

		  ?>
                  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onclick="window.onUnload = window.opener.consultarRemisiones('<?=$bus_solicitudes["idremision_documentos"]?>'),  window.close()">
                     <td class='Browse' align='left'>&nbsp;<?=$bus_solicitudes["numero_documento"]?></td>
                    <td class='Browse' align='left'><?=$bus_solicitudes["fecha_elaboracion"]?></td>
                    <td class='Browse' align='left'><?=$bus_solicitudes["asunto"]?></td>
                    <td class='Browse' align='left'><?=$bus_solicitudes["justificacion"]?></td>
                    <td class='Browse' align="center">
                    <a href="#" onClick="window.onUnload = window.opener.consultarRemisiones('<?=$bus_solicitudes["idremision_documentos"]?>'),  window.close()">
                        	<img src="../../imagenes/validar.png">
                    </a>
                    </td> 
                    <?
                    if($bus_solicitudes["numero_documento"] == ""){
						?>
						<td class='Browse' align="center">
							<a href="?eliminar=<?=$bus_solicitudes["idremision_documentos"]?>&texto_busqueda=<?=$_REQUEST["texto_busqueda"]?>&estado_remision=<?=$_REQUEST["estado_remision"]?>&buscar=''">
								<img src="../../imagenes/delete.png">
						</a>
						</td> 
						<?
                    }else{
						?>
						<td class='Browse' align="center">&nbsp;</td>
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
<?
session_start();
include("../../conf/conex.php");
$conection_db = conectarse();
include("../../funciones/funciones.php");
extract($_POST);
if($_GET["eliminar"]){
	$sql_eliminar = mysql_query("delete from autorizar_generar_comprobante where idautorizar_generar_comprobante = '".$_GET["eliminar"]."'");
	$sql_eliminar2 = mysql_query("delete from relacion_documentos_generar_comprobante where idautorizar_generar_comprobante = '".$_GET["eliminar"]."'");
}


?>
<script type="text/javascript" src="../../js/funciones.js"></script>
<script type="text/javascript" src="../../js/function.js"></script>
<link href="../../css/theme/green/main.css" rel="stylesheet" type="text/css">
  <style type="text/css"> @import url("../../css/theme/calendar-win2k-cold-1.css"); </style> 
  
  		  <h2 align="center">Relaci&oacute;n Documentos para Generar Comprobante</h2>
      
     <br />
     	<h2 class="sqlmVersion"></h2>
<br> 
     <form action="" method="post"> 
       
       <input type="hidden" name="destino" id="destino" <? if($_GET["destino"]){ echo "value='".$_GET["destino"]."'";}else{echo "value='".$_POST["destino"]."'";}?>>
       <table width="511" border="0" align="center">
	<tr>
           
  		<td width="47%"><div align="center">Concepto:<br>
      		<input type="text" name="texto_busqueda" id="texto_busqueda"  value="<?=$_REQUEST["texto_busqueda"]?>">
            </div>
  		</td>
       	<td width="18%"><label>
        	<input type="submit" name="buscar" id="buscar" value="Buscar" class="button">
      		</label>
         </td>
     </tr>
   </table>
 </form>
        
  <?
  //echo $_REQUEST["destino"];
  if($_REQUEST["buscar"]){
		$sql_solicitudes = mysql_query("select * from autorizar_generar_comprobante where justificacion like '%".$_REQUEST["texto_busqueda"]."%'")or die(mysql_error());
		
			
  ?>      <br />
  
        <table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="100%">
          <thead>
          <tr>
          	<td width="7%" align="center" class="Browse">No. Documento</td>
            <td width="12%" align="center" class="Browse">Fecha Elaboracion</td>
            <td width="24%" align="center" class="Browse">Asunto</td>
            <td width="51%" align="center" class="Browse">Concepto</td>
            <td width="6%" align="center" class="Browse" colspan="2">Acciones</td>
          </tr>
          </thead>
          <? 
		  
         
          while($bus_solicitudes = mysql_fetch_array($sql_solicitudes)){

		  ?>
                  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onclick="window.onUnload = window.opener.consultarRemisiones('<?=$bus_solicitudes["idautorizar_generar_comprobante"]?>'),  window.close()">
                     <td class='Browse' align='left'>&nbsp;<?=$bus_solicitudes["numero_documento"]?></td>
                    <td class='Browse' align='left'><?=$bus_solicitudes["fecha_elaboracion"]?></td>
                    <td class='Browse' align='left'><?=$bus_solicitudes["asunto"]?></td>
                    <td class='Browse' align='left'><?=$bus_solicitudes["justificacion"]?></td>
                    <td class='Browse' align="center">
                    <a href="#" onClick="window.onUnload = window.opener.consultarRemisiones('<?=$bus_solicitudes["idautorizar_generar_comprobante"]?>'),  window.close()">
                        	<img src="../../imagenes/validar.png">
                    </a>
                    </td> 
                    <?
                    if($bus_solicitudes["numero_documento"] == ""){
						?>
						<td class='Browse' align="center">
							<a href="?eliminar=<?=$bus_solicitudes["idautorizar_generar_comprobante"]?>&buscar=''">
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
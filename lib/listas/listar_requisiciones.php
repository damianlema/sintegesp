<?
session_start();
include("../../conf/conex.php");
$conection_db = conectarse();
include("../../funciones/funciones.php");

if($_GET["eliminar"]){
	$sql_eliminar = mysql_query("delete from requisicion where idrequisicion = '".$_GET["eliminar"]."'");
	$sql_eliminar_partidas = mysql_query("delete from partidas_requisiciones where idrequisicion = '".$_GET["eliminar"]."'");
	$sql_eliminar_relacion_impuestos = mysql_query("delete from relacion_impuestos_requisiciones where idrequisicion = '".$_GET["eliminar"]."'");
}


?>
<script type="text/javascript" src="../../js/funciones.js"></script>
<script type="text/javascript" src="../../js/function.js"></script>
<link href="../../css/theme/green/main.css" rel="stylesheet" type="text/css">
  <style type="text/css"> @import url("../../css/theme/calendar-win2k-cold-1.css"); </style>   
     <h2 align="center">
Requisiciones
     </h2>
     <br />
     	<h2 class="sqlmVersion"></h2>
<br> 
     <form action="" method="post" name="formularioBusqueda" id="formularioBusqueda"> 
       
       <input type="hidden" name="destino" id="destino" <? if($_GET["destino"]){ echo "value='".$_GET["destino"]."'";}else{echo "value='".$_POST["destino"]."'";}?>>
       <table width="511" border="0" align="center">
<tr>
            <td width="35%"><div align="center">Estado de la Orden:<br>
                <select name="estado_requisicion">
                  <option value="0" <? if($_POST["estado_requisicion"] == "0"){echo "selected";}?>>.:: Seleccione ::.</option>
                  <option value="elaboracion" <? if($_POST["estado_requisicion"] == "elaboracion"){echo "selected";}?>>En Elaboracion</option>
                  <option value="procesado" <? if($_POST["estado_requisicion"] == "procesado"){echo "selected";}?>>Procesado</option>
                  <option value="conformado" <? if($_POST["estado_requisicion"] == "conformado"){echo "selected";}?>>Conformado</option>
                  <option value="devuelto" <? if($_POST["estado_requisicion"] == "devuelto"){echo "selected";}?>>Devuelto</option>
                  <option value="ordenado" <? if($_POST["estado_requisicion"] == "ordenado"){echo "selected";}?>>Ordenada</option>
                  <option value="anulado" <? if($_POST["estado_requisicion"] == "anulado"){echo "selected";}?>>Anulado</option>
                  <option value="pagado" <? if($_POST["estado_requisicion"] == "pagado"){echo "selected";}?>>Pagado</option>
                 </select>
      </div></td>
  <td width="47%"><div align="center">Nro Requisicion / Beneficiario:<br>
      <input type="text" name="texto_busqueda" id="texto_busqueda"  size="40" value="<?=$_POST["texto_busqueda"]?>">
  </div></td>
      <td width="18%"><label>
        <input type="submit" name="buscar" id="buscar" value="Buscar" class="button">
      </label></td>
         </tr>
        </table>
     </form>
        
  <?
  if($_POST["buscar"]){
		//echo $_POST["estado_requisicion"];
		if($_POST["estado_requisicion"] == "0"){
			$sql_ordenes = mysql_query("select * from requisicion, beneficiarios, tipos_documentos  
								where beneficiarios.idbeneficiarios = requisicion.idbeneficiarios
								and (beneficiarios.nombre like '%".$_POST["texto_busqueda"]."%' 
								or requisicion.numero_requisicion like '%".$_POST["texto_busqueda"]."%') 
								group by requisicion.idrequisicion order by requisicion.codigo_referencia")or die(mysql_error());
		}else{
			$sql_ordenes = mysql_query("select * from requisicion, beneficiarios, tipos_documentos  
								where beneficiarios.idbeneficiarios = requisicion.idbeneficiarios
								and beneficiarios.nombre like '%".$_POST["texto_busqueda"]."%' 
								and requisicion.estado = '".$_POST["estado_requisicion"]."'
								group by requisicion.idrequisicion order by requisicion.codigo_referencia")or die(mysql_error());
		}
				

				/*$sql_ordenes = mysql_query("select * from requisicion, beneficiarios, tipos_documentos  
								where beneficiarios.idbeneficiarios = requisicion.idbeneficiarios
								and (beneficiarios.nombre like '%".$_POST["texto_busqueda"]."%' 
								or requisicion.numero_requisicion like '%".$_POST["texto_busqueda"]."%') 
								and requisicion.estado = '".$_POST["estado_cotizacion"]."' 
								and tipos_documentos.idtipos_documentos = requisicion.tipo 
								and tipos_documentos.modulo = ".$_SESSION["modulo"]." 
								and tipos_documentos.compromete = 'si' 
								and tipos_documentos.causa = 'no' 
								and tipos_documentos.paga = 'no' order by requisicion.codigo_referencia")or die(mysql_error());
			*/
		$num_ordenes = mysql_num_rows($sql_ordenes);
		if($num_ordenes != 0){	
  ?>      <br />
  
        <table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="96%">
          <thead>
          <tr>
          	<td class="Browse" width="10%"><div align="center">N&uacute;mero</div></td>
            <td class="Browse" width="30%"><div align="center">Proveedor</div></td>
            <td class="Browse" width="45%"><div align="center">Justificaci&oacute;n</div></td>
            <td class="Browse" width="10%"><div align="center">Estado</div></td>
            <td class="Browse" width="5%" colspan="2"><div align="center">Acciones</div></td>
          </tr>
          </thead>
          <? 
		  
         
          while($bus_ordenes = mysql_fetch_array($sql_ordenes)){

		  ?>
                  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer">
                    <td class='Browse' align='left' onclick="
                  <?
                  if($_REQUEST["destino"] == "ordenes"){
				  ?>
				  window.onUnload = window.opener.consultarOrdenCompra(<?=$bus_ordenes["idrequisicion"]?>, <?=$bus_ordenes["idcategoria_programatica"]?>),  window.close()
				  <?
				  }
				  if($_REQUEST["destino"] == "certificacionesAdministracion"){
				  ?>
				  window.onUnload = window.opener.consultarOrdenCompra(<?=$bus_ordenes["idrequisicion"]?>, <?=$bus_ordenes["idcategoria_programatica"]?>),  window.close()
				  <?
				  }
				  ?>
                  ">&nbsp;<?=$bus_ordenes["numero_requisicion"]?></td>
                    <td class='Browse' align='left' onclick="
                  <?
                  if($_REQUEST["destino"] == "ordenes"){
				  ?>
				  window.onUnload = window.opener.consultarOrdenCompra(<?=$bus_ordenes["idrequisicion"]?>, <?=$bus_ordenes["idcategoria_programatica"]?>),  window.close()
				  <?
				  }
				  if($_REQUEST["destino"] == "certificacionesAdministracion"){
				  ?>
				  window.onUnload = window.opener.consultarOrdenCompra(<?=$bus_ordenes["idrequisicion"]?>, <?=$bus_ordenes["idcategoria_programatica"]?>),  window.close()
				  <?
				  }
				  ?>
                  "><?=utf8_decode($bus_ordenes["nombre"])?></td>
                    <td class='Browse' align='left' onclick="
                  <?
                  if($_REQUEST["destino"] == "ordenes"){
				  ?>
				  window.onUnload = window.opener.consultarOrdenCompra(<?=$bus_ordenes["idrequisicion"]?>, <?=$bus_ordenes["idcategoria_programatica"]?>),  window.close()
				  <?
				  }
				  if($_REQUEST["destino"] == "certificacionesAdministracion"){
				  ?>
				  window.onUnload = window.opener.consultarOrdenCompra(<?=$bus_ordenes["idrequisicion"]?>, <?=$bus_ordenes["idcategoria_programatica"]?>),  window.close()
				  <?
				  }
				  ?>
                  "><?=utf8_decode($bus_ordenes["justificacion"])?></td>
                    <td class='Browse' align='left' onclick="
                  <?
                  if($_REQUEST["destino"] == "ordenes"){
				  ?>
				  window.onUnload = window.opener.consultarOrdenCompra(<?=$bus_ordenes["idrequisicion"]?>, <?=$bus_ordenes["idcategoria_programatica"]?>),  window.close()
				  <?
				  }
				  if($_REQUEST["destino"] == "certificacionesAdministracion"){
				  ?>
				  window.onUnload = window.opener.consultarOrdenCompra(<?=$bus_ordenes["idrequisicion"]?>, <?=$bus_ordenes["idcategoria_programatica"]?>),  window.close()
				  <?
				  }
				  ?>
                  "><?=$bus_ordenes["estado"]?></td>
                  <?
                    if($_REQUEST["destino"] == "ordenes"){
                    ?>
                        <td class='Browse' align="center"><a href="#" onClick="window.onUnload = window.opener.consultarOrdenCompra(<?=$bus_ordenes["idrequisicion"]?>, <?=$bus_ordenes["idcategoria_programatica"]?>),  window.close()"><img src="../../imagenes/validar.png"></a></td>
                    <?
                    }
                    ?>
                                      <?
                    if($_REQUEST["destino"] == "certificacionesAdministracion"){
                    ?>
                        <td class='Browse' align="center"><a href="#" onClick="window.onUnload = window.opener.consultarOrdenCompra(<?=$bus_ordenes["idrequisicion"]?>, <?=$bus_ordenes["idcategoria_programatica"]?>),  window.close()"><img src="../../imagenes/validar.png"></a></td>
                    <?
                    }
                    if($bus_ordenes["estado"] == "elaboracion"){
					?>
                    <td class='Browse' align="center"><a href="javascript:;>"><img src="../../imagenes/delete.png" onClick="window.location.href='?eliminar=<?=$bus_ordenes["idrequisicion"]?>&destino=<?=$_REQUEST["destino"]?>'"></a></td>
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
  }else{
 echo "<center><strong>No se encontraron resultados</strong></center>";
 }
 
 }
 

 ?>
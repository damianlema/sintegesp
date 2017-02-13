<?
session_start();
include("../../conf/conex.php");
$conection_db = conectarse();
include("../../funciones/funciones.php");




?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    </head>
<script type="text/javascript" src="../../js/funciones.js"></script>
<script type="text/javascript" src="../../js/function.js"></script>
<link href="../../css/theme/green/main.css" rel="stylesheet" type="text/css">
<style type="text/css"> @import url("../../css/theme/calendar-win2k-cold-1.css"); </style>   
  
     <h2 align="center">
     <?
     if($_SESSION["modulo"] == "4" || $_SESSION["modulo"] == "1"  || $_SESSION["modulo"] == "13"){
	   	echo "Certificaci&oacute;n de Compromiso";
	 }else if($_SESSION["modulo"] == "16"){
		 echo "CAJA CHICA";
	 }else if($_SESSION["modulo"] == "19"){
		 echo "Certificaci&oacute;n de Compromiso";
	 }else{
	 	echo "Ordenes de Compra y Servicio";
	 }
	 ?>
     </h2>
     <br />
     	<h2 class="sqlmVersion"></h2>
<br> 
	
     <form action="" method="post"> 
       <input type="hidden" name="idbeneficiario" id="idbeneficiario" value="">
       <input type="hidden" name="destino" id="destino" <? if($_GET["destino"]){ echo "value='".$_GET["destino"]."'";}else{echo "value='".$_POST["destino"]."'";}?>>
       
       <input type="hidden" name="t" id="t" <? if($_GET["t"]){ echo "value='".$_GET["t"]."'";}else{echo "value='".$_POST["t"]."'";}?>>
       <table width="85%" border="0" align="center">
<tr>
  <td width="21%" align="center">Tipo de Documento
      <?

	  	if($_REQUEST["t"] == "fondos_t"){
			$sql_tipos_documentos = mysql_query("select * from tipos_documentos 
													where 
													((compromete='si' and causa='no' and paga='no') 
													or (compromete='no' and causa='no' and paga='no'))
													and modulo like '-".$_SESSION["modulo"]."-'
													and fondos_terceros = 'si'");
		}else{
			if($_GET["destino"] == "apertura"){
				$sql_tipos_documentos = mysql_query("select * from tipos_documentos where modulo like  '%-".$_SESSION["modulo"]."-%' and compromete = 'no' and causa = 'no' and paga = 'no'");
			}else{
				$sql_tipos_documentos = mysql_query("select * from tipos_documentos where modulo like  '%-".$_SESSION["modulo"]."-%' and compromete = 'si' and causa = 'no' and paga = 'no'");
			}
		}
	?>
    <select name="tipo_orden" id="tipo_orden">
    <option value="0">.:: Seleccione ::.</option>
	<?
    while($bus_tipos_documentos = mysql_fetch_array($sql_tipos_documentos)){
	?>
		<option <? if($bus_tipos_documentos["idtipos_documentos"] == $_POST["tipo_orden"]){echo "selected";}?> value="<?=$bus_tipos_documentos["idtipos_documentos"]?>"><?=$bus_tipos_documentos["descripcion"]?></option>
	<?
	}
	?>
        </select>  
  
  </td>
          <td width="22%"><div align="center">Estado:<br>
                <select name="estado_cotizacion">
                	<option value="0">.:: Seleccione ::.</option>
                  <option <? if($_POST["estado_cotizacion"] == "elaboracion"){echo "selected";}?> value="elaboracion">En Elaboracion</option>
                  <option <? if($_POST["estado_cotizacion"] == "procesado"){echo "selected";}?> value="procesado">Procesado</option>
                  <option <? if($_POST["estado_cotizacion"] == "conformado"){echo "selected";}?> value="conformado">Conformado</option>
                  <option <? if($_POST["estado_cotizacion"] == "devuelto"){echo "selected";}?> value="devuelto">Devuelto</option>
                  <option <? if($_POST["estado_cotizacion"] == "ordenado"){echo "selected";}?> value="ordenado">Ordenada</option>
                  <option <? if($_POST["estado_cotizacion"] == "parcial"){echo "selected";}?> value="parcial">Parcial</option>
                  <option <? if($_POST["estado_cotizacion"] == "pagado"){echo "selected";}?> value="pagado">Pagado</option>
                  <option <? if($_POST["estado_cotizacion"] == "anulado"){echo "selected";}?> value="anulado">Anulado</option>
              </select>
      </div></td>
  <td width="27%"><div align="center">Nro Orden / Beneficiario:<br>
  
      <input type="text" name="texto_busqueda" id="texto_busqueda"  size="40" value="<?=$_POST["texto_busqueda"]?>">
  </div></td>
      <td width="13%"><label>
        <input type="submit" name="buscar" id="buscar" value="Buscar" class="button">
      </label></td>
         </tr>
       </table>
       <input type="hidden" id="accion" name="accion" value="<?=$_GET["accion"]?>">
     </form>
        
  <?
  if($_POST["buscar"]){
		echo $_POST["idbeneficiario"];
		if ($_SESSION["modulo"] == '1' or $_SESSION["modulo"] == '13'){
			$query = "select * from orden_compra_servicio, beneficiarios, tipos_documentos  
						where beneficiarios.idbeneficiarios = orden_compra_servicio.idbeneficiarios ";
						if($_POST["texto_busqueda"] != ""){
							$query .= "and (beneficiarios.nombre like '%".$_POST["texto_busqueda"]."%' 
										or orden_compra_servicio.numero_orden like '%".$_POST["texto_busqueda"]."%'
										or orden_compra_servicio.justificacion like '%".$_POST["texto_busqueda"]."%') ";
						}
						
						if($_POST["estado_cotizacion"] != "0"){
								$query .= "and orden_compra_servicio.estado = '".$_POST["estado_cotizacion"]."' ";
							}
						
						if($_POST["tipo_orden"] != "0"){
							$query .= "and tipos_documentos.idtipos_documentos = '".$_POST["tipo_orden"]."'"; 
						}
						$query .= "and tipos_documentos.idtipos_documentos = orden_compra_servicio.tipo 
						and (tipos_documentos.modulo like '%-1-%' or  tipos_documentos.modulo like '%-13-%')
						and ((tipos_documentos.compromete = 'si' 
								and tipos_documentos.causa = 'no' 
								and tipos_documentos.paga = 'no') 
							or
							(tipos_documentos.compromete = 'no' 
								and tipos_documentos.causa = 'no' 
								and tipos_documentos.paga = 'no')) ";
						if($_REQUEST["accion"] == 734 or $_REQUEST["accion"] == 664 or $_REQUEST["t"] == "fondos_t"){
							$query .= "and tipos_documentos.multi_categoria = 'si'";
						}else{
							$query .= "and tipos_documentos.multi_categoria = 'no'";
						}
						
						if($_REQUEST["t"] == "fondos_t"){
							$query .= "and tipos_documentos.fondos_terceros = 'si'";
						}
						$query .= " order by orden_compra_servicio.codigo_referencia";
					
		}else if($_SESSION["modulo"] == '16' and $_GET["destino"] == "apertura"){
			
			$query = "select * from orden_compra_servicio, beneficiarios, tipos_documentos  
						where beneficiarios.idbeneficiarios = orden_compra_servicio.idbeneficiarios ";
						if($_POST["texto_busqueda"] != ""){
							$query .= "and (beneficiarios.nombre like '%".$_POST["texto_busqueda"]."%' 
										or orden_compra_servicio.numero_orden like '%".$_POST["texto_busqueda"]."%'
										or orden_compra_servicio.justificacion like '%".$_POST["texto_busqueda"]."%') ";
						}
						
						if($_POST["estado_cotizacion"] != "0"){
								$query .= "and orden_compra_servicio.estado = '".$_POST["estado_cotizacion"]."' ";
							}
						
						if($_POST["tipo_orden"] != "0"){
							$query .= "and tipos_documentos.idtipos_documentos = '".$_POST["tipo_orden"]."'"; 
						}
						$query .= "and tipos_documentos.idtipos_documentos = orden_compra_servicio.tipo 
						and tipos_documentos.modulo like '%-".$_SESSION["modulo"]."-%' 
						and tipos_documentos.compromete = 'no' 
						and tipos_documentos.causa = 'no' 
						and tipos_documentos.paga = 'no' ";
						
						if($_REQUEST["accion"] == 734 or $_REQUEST["accion"] == 664){
							$query .= "and tipos_documentos.multi_categoria = 'si'";
						}else{
							$query .= "and tipos_documentos.multi_categoria = 'no'";
						}
						$query .= " order by orden_compra_servicio.codigo_referencia";
		}else {

			$query = "select * from orden_compra_servicio, beneficiarios, tipos_documentos  
						where beneficiarios.idbeneficiarios = orden_compra_servicio.idbeneficiarios ";
						if($_POST["texto_busqueda"] != ""){
							$query .= "and (beneficiarios.nombre like '%".$_POST["texto_busqueda"]."%' 
										or orden_compra_servicio.numero_orden like '%".$_POST["texto_busqueda"]."%'
										or orden_compra_servicio.justificacion like '%".$_POST["texto_busqueda"]."%') ";
						}
						
						if($_POST["estado_cotizacion"] != "0"){
								$query .= "and orden_compra_servicio.estado = '".$_POST["estado_cotizacion"]."' ";
							}
						
						if($_POST["tipo_orden"] != "0"){
							$query .= "and tipos_documentos.idtipos_documentos = '".$_POST["tipo_orden"]."'"; 
						}
						$query .= "and tipos_documentos.idtipos_documentos = orden_compra_servicio.tipo 
						and tipos_documentos.modulo like '%-".$_SESSION["modulo"]."-%' 
						and tipos_documentos.compromete = 'si' 
						and tipos_documentos.causa = 'no' 
						and tipos_documentos.paga = 'no' ";
						
						if($_REQUEST["accion"] == 734 or $_REQUEST["accion"] == 664){
							$query .= "and tipos_documentos.multi_categoria = 'si'";
						}else{
							$query .= "and tipos_documentos.multi_categoria = 'no'";
						}
						$query .= " order by orden_compra_servicio.codigo_referencia";
			}
		//echo $query;
		
		$sql_ordenes = mysql_query($query);
		
		$num_ordenes = mysql_num_rows($sql_ordenes);
		if($num_ordenes != 0){	
  ?>      <br />
  
        <table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="96%">
          <thead>
          <tr>
          	<td class="Browse" width="8%"><div align="center">N&uacute;mero</div></td>
            <td class="Browse" width="12%"><div align="center">Fecha Orden</div></td>
            <td class="Browse" width="31%"><div align="center">Proveedor</div></td>
            <td class="Browse" width="34%"><div align="center">Justificacion</div></td>
            <td class="Browse" width="7%"><div align="center">Estado</div></td>
            <td class="Browse" width="6%"><div align="center">Acciones</div></td>
          </tr>
          </thead>
          <? 
		  
         
          while($bus_ordenes = mysql_fetch_array($sql_ordenes)){

		  ?>
                  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer"
                  onclick="
                  <?
                  if($_REQUEST["destino"] == "ordenes"){
				  ?>
				  opener.document.getElementById('idestado').value='<?=$bus_ordenes["estado"]?>', window.onUnload = window.opener.consultarOrdenCompra(<?=$bus_ordenes["idorden_compra_servicio"]?>, <?=$bus_ordenes["idcategoria_programatica"]?>),  window.close()
				  <?
				  }
				  if($_REQUEST["destino"] == "rendicion"){
				  ?>
				  window.onUnload = window.opener.consultarOrden(<?=$bus_ordenes["idorden_compra_servicio"]?>),  window.close()
				  <?
				  }
				  
				  
				  if($_REQUEST["destino"] == "apertura"){
				  ?>
				  window.onUnload = window.opener.consultarApertura('<?=$bus_ordenes["idorden_compra_servicio"]?>'),  window.close()
				  <?
				  }
				  
				  if($_GET["destino"] == "almacen"){
					?>
					opener.document.getElementById('idorden_compra_servicio').value='<?=$bus_ordenes["idorden_compra_servicio"]?>', opener.document.getElementById('orden_compra').value='<?=$bus_ordenes["numero_orden"]?>' , opener.document.getElementById('fecha_orden_compra').value='<?=$bus_ordenes["fecha_orden"]?>' , opener.document.getElementById('idbeneficiario').value='<?=$bus_ordenes["idbeneficiarios"]?>' , opener.document.getElementById('proveedor').value='<?=$bus_ordenes["nombre"]?>', window.close()
					<?
					}
				  
				  if($_REQUEST["destino"] == "certificacionesAdministracion"){
				  ?>
				  window.onUnload = window.opener.consultarOrdenCompra(<?=$bus_ordenes["idorden_compra_servicio"]?>, <?=$bus_ordenes["idcategoria_programatica"]?>),  window.close()
				  <?
				  }
				  
				  if($_REQUEST["t"] == "fondos_t"){
                    ?>
                     window.onUnload = window.opener.consultarCertificacion(<?=$bus_ordenes["idorden_compra_servicio"]?>), window.close()
                    <?
                    }
				  
				  
				  ?>
                  ">
                    <td class='Browse' align='left'>&nbsp;<?=$bus_ordenes["numero_orden"]?></td>
                    <td class='Browse' align='center'>
						<?
                        if($bus_ordenes["fecha_orden"] == '0000-00-00'){
							echo "<strong>No Procesada</strong>";
						}else{
							echo $bus_ordenes["fecha_orden"];
						}
						?>
                    </td>
                    <td class='Browse' align='left'><?=utf8_decode($bus_ordenes["nombre"])?></td>
                    <td class='Browse' align='left'><?=utf8_decode($bus_ordenes["justificacion"])?></td>
                    <td class='Browse' align='left'><?=$bus_ordenes["estado"]?></td>
                  <?
                    if($_REQUEST["destino"] == "ordenes"){
                    ?>
                        <td class='Browse' align="center">
                        <a href="#" onClick="opener.consultarOrdenCompra(<?=$bus_ordenes["idorden_compra_servicio"]?>, <?=$bus_ordenes["idcategoria_programatica"]?>),  window.close()">
                        	<img src="../../imagenes/validar.png">
                        </a>
                        </td>
                    <?
                    }
					
					 if($_REQUEST["destino"] == "apertura"){
                    ?>
                        <td class='Browse' align="center">
                        <a href="#" onClick="opener.consultarApertura('<?=$bus_ordenes["idorden_compra_servicio"]?>'),  window.close()">
                        	<img src="../../imagenes/validar.png">
                        </a>
                        </td>
                    <?
                    }
					
					
					
					if($_REQUEST["destino"] == "rendicion"){
                    ?>
                        <td class='Browse' align="center">
                        <a href="#" onClick="opener.consultarOrden(<?=$bus_ordenes["idorden_compra_servicio"]?>),  window.close()"
                        	<img src="../../imagenes/validar.png">
                        </a>
                        </td>
                    <?
                    }
					

                    if($_REQUEST["destino"] == "certificacionesAdministracion"){
                    ?>
                        <td width="2%" align="center" class='Browse'><a href="#" onClick="opener.consultarOrdenCompra(<?=$bus_ordenes["idorden_compra_servicio"]?>, <?=$bus_ordenes["idcategoria_programatica"]?>),  window.close()"><img src="../../imagenes/validar.png"></a></td>
                    <?
                    }
					
					if($_REQUEST["destino"] == "almacen"){
                    ?>
					 <td width="2%" align="center" class='Browse'><a href="#" onClick="opener.document.getElementById('idorden_compra_servicio').value='<?=$bus_ordenes["idorden_compra_servicio"]?>', opener.document.getElementById('orden_compra').value='<?=$bus_ordenes["numero_orden"]?>' , opener.document.getElementById('fecha_orden_compra').value='<?=$bus_ordenes["fecha_orden"]?>' , opener.document.getElementById('idbeneficiario').value='<?=$bus_ordenes["idbeneficiarios"]?>' , opener.document.getElementById('proveedor').value='<?=$bus_ordenes["nombre"]?>', window.close()"><img src="../../imagenes/validar.png"></a></td>
					<?
					}
					
					if($_REQUEST["t"] == "fondos_t"){
                    ?>
                        <td width="2%" align="center" class='Browse'>
                            <a href="#" onClick="opener.consultarCertificacion(<?=$bus_ordenes["idorden_compra_servicio"]?>), window.close()">
                            <img src="../../imagenes/validar.png">
                            </a>
                        </td>
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
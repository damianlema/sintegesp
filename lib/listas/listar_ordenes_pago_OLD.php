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
     <h2 align="center">
     <?
     if($_REQUEST["destino"] == "certificacionesAdministracion"){
	 	echo "Certificaci&oacute;n de Compromiso";
	 }else{
	 	echo "Ordenes de Pago";
	 }
	 ?>
     </h2>
     <br />
     	<h2 class="sqlmVersion"></h2>
<br> 
     <form action="" method="post">

       <input type="hidden" name="destino" id="destino" <? if($_GET["destino"]){ echo "value='".$_GET["destino"]."'";}else{echo "value='".$_POST["destino"]."'";}?>>
       <table width="90%" border="0" align="center">
<tr>
		 <td width="35%">
         	<div align="center">Fuente de Financiamiento:<br>
                <select name="fuente_financiamiento">
                  <option value="0">.:: Seleccione ::.</option>
                  <?
				  	$sql_fuente_financiamiento = mysql_query("select * from fuente_financiamiento");
					while ($bus_fuente_financiamiento = mysql_fetch_array($sql_fuente_financiamiento)){
				  ?>
                      <option value="<?=$bus_fuente_financiamiento["idfuente_financiamiento"]?>"><?=$bus_fuente_financiamiento["denominacion"]?></option>
                  <?
				  	}
					?>
               </select>
      		</div>
         </td>

            <td width="35%"><div align="center">Estado de la Orden:<br>
                <select name="estado_cotizacion">
                  <option value="0">.:: Seleccione ::.</option>
                  <option <? if($_POST["estado_cotizacion"] == "elaboracion"){echo "selected";}?> value="elaboracion">En Elaboracion</option>
                  <option <? if($_POST["estado_cotizacion"] == "procesado"){echo "selected";}?> value="procesado">Procesado</option>
                  <option <? if($_POST["estado_cotizacion"] == "conformado"){echo "selected";}?> value="conformado">Conformado</option>
                  <option <? if($_POST["estado_cotizacion"] == "devuelto"){echo "selected";}?> value="devuelto">Devuelto</option>
                  <option <? if($_POST["estado_cotizacion"] == "parcial"){echo "selected";}?> value="devuelto">Parcial</option>
                  <option <? if($_POST["estado_cotizacion"] == "anulado"){echo "selected";}?> value="anulado">Anulado</option>
                  <option <? if($_POST["estado_cotizacion"] == "pagada"){echo "selected";}?> value="pagada">Pagado</option>
                  </select>
      </div></td>
  <td width="47%"><div align="center">Texto a Buscar:<br>
      <input type="text" name="texto_busqueda" id="texto_busqueda" size="40" value="<?=$_POST["texto_busqueda"]?>">
  </div></td>
      <td width="18%"><label>
        <input type="submit" name="buscar" id="buscar" value="Buscar" class="button">
      </label></td>
         </tr>
        </table>
     </form>

  <?
  if($_POST["buscar"]){
  		$filtro = '';
		if ($_POST["estado_cotizacion"] != "0"){
			$filtro = " and orden_pago.estado = '".$_POST["estado_cotizacion"]."' ";
		}
		if ($_POST["fuente_financiamiento"] != "0"){
			$filtro = $filtro." and orden_pago.idfuente_financiamiento = '".$_POST["fuente_financiamiento"]."' ";
		}

		$sqlordenes = "select * from orden_pago, beneficiarios, tipos_documentos
											where beneficiarios.idbeneficiarios = orden_pago.idbeneficiarios
											and (orden_pago.numero_orden like '%".$_POST["texto_busqueda"]."%'
											or beneficiarios.nombre like '%".$_POST["texto_busqueda"]."%'
											or orden_pago.justificacion like '%".$_POST["texto_busqueda"]."%') ".$filtro."
											group by orden_pago.idorden_pago order by orden_pago.codigo_referencia";

		$sql_ordenes = mysql_query($sqlordenes);
		$num_ordenes = mysql_num_rows($sql_ordenes);
		if($num_ordenes != 0){
  ?>      <br />

        <table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="98%">
          <thead>
          <tr>
          	<td class="Browse" width="15%"><div align="center">N&uacute;mero</div></td>
            <td class="Browse" width="30%"><div align="center">Proveedor / Beneficiario</div></td>
            <td class="Browse" width="10%"><div align="center">Fecha</div></td>
            <td class="Browse" width="60%"><div align="center">Justificaci&oacute;n</div></td>
            <td class="Browse" width="10%"><div align="center">Estado</div></td>
            <td class="Browse" width="5%"><div align="center">Acciones</div></td>
          </tr>
          </thead>
          <?

          while($bus_ordenes = mysql_fetch_array($sql_ordenes)){

		  ?>
                  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" 
                  onclick="
                  <?
                  if($_REQUEST["destino"] == "ordenes_pago"){
				  ?>
				  window.onUnload = window.opener.consultarOrdenPago(<?=$bus_ordenes["idorden_pago"]?>),  window.close()
				  <?
				  }
				  if($_REQUEST["destino"] == "certificacionesAdministracion"){
				  ?>
				  window.onUnload = window.opener.consultarOrdenCompra(<?=$bus_ordenes["idorden_pago"]?>),  window.close()
				  <?
				  }
				  ?>
                  ">
                    <td class='Browse' align='left'>&nbsp;<?=$bus_ordenes["numero_orden"]?></td>
                    <td class='Browse' align='left'>&nbsp;<?=$bus_ordenes["nombre"]?></td>
                    <td class='Browse' align='center'>&nbsp;<?=$bus_ordenes["fecha_orden"]?></td>
                    <td class='Browse' align='left'>&nbsp;<?=$bus_ordenes["justificacion"]?></td>
                    <td class='Browse' align='left'>&nbsp;<?=$bus_ordenes["estado"]?></td>
                  <?
                    if($_REQUEST["destino"] == "ordenes_pago"){
                    ?>
                        <td class='Browse' align="center"><a href="#" onClick="window.onUnload = window.opener.consultarOrdenPago(<?=$bus_ordenes["idorden_pago"]?>),  window.close()"><img src="../../imagenes/validar.png"></a></td>
                    <?
                    }
                    ?>
                                      <?
                    if($_REQUEST["destino"] == "certificacionesAdministracion"){
                    ?>
                        <td class='Browse' align="center"><a href="#" onClick="window.onUnload = window.opener.consultarOrdenCompra(<?=$bus_ordenes["idorden_pago"]?>),  window.close()"><img src="../../imagenes/validar.png"></a></td>
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
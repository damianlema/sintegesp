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
  
  <? if ($modulo == 1) {
  		$sql_dependencia = mysql_query("select * from configuracion_rrhh");
  		$bus_dependencia = mysql_fetch_array($sql_dependencia);
		$oficina = "Nomina"; ?>
     	<h2 align="center">Documentos Recibidos en Nomina</h2>
   <? }
     if ($modulo == 2) {
  		$sql_dependencia = mysql_query("select * from configuracion_presupuesto");
  		$bus_dependencia = mysql_fetch_array($sql_dependencia);
		$oficina = "Presupuesto"; ?>
     	<h2 align="center">Documentos Recibidos en Presupuesto</h2>
   <? }
	 if ($modulo == 3) {
		$sql_dependencia = mysql_query("select * from configuracion_compras");
  		$bus_dependencia = mysql_fetch_array($sql_dependencia);
		$oficina = "Compras y Servicios";	 ?>
     	<h2 align="center">Documentos Recibidos en Compras y Servicios</h2>
   <? } 
	 if ($modulo == 4) {
		$sql_dependencia = mysql_query("select * from configuracion_administracion");
  		$bus_dependencia = mysql_fetch_array($sql_dependencia);
		$oficina = "Administracion";	 ?>
     	<h2 align="center">Documentos Recibidos en Administraci&oacute;n</h2>
   <? } 
     if ($modulo == 6) {
		$sql_dependencia = mysql_query("select * from configuracion_tributos");
  		$bus_dependencia = mysql_fetch_array($sql_dependencia);
		$oficina = "Tributos";	 ?>
     	<h2 align="center">Documentos Recibidos en Tributos</h2>
   <? } 
      if ($modulo == 7) {
		$sql_dependencia = mysql_query("select * from configuracion_tesoreria");
  		$bus_dependencia = mysql_fetch_array($sql_dependencia);
		$oficina = "Tesoreria";	 ?>
     	<h2 align="center">Documentos Recibidos en Tesoreria</h2>
   <? }
  	 if ($modulo == 5) {
		$sql_dependencia = mysql_query("select * from configuracion_contabilidad");
  		$bus_dependencia = mysql_fetch_array($sql_dependencia);
		$oficina = "Contabilidad";	 ?>
     	<h2 align="center">Documentos Recibidos en Contabilidad</h2>
   <? }
   if ($modulo == 12) {
		$sql_dependencia = mysql_query("select * from configuracion_despacho");
  		$bus_dependencia = mysql_fetch_array($sql_dependencia);
		$oficina = "Despacho";	 ?>
     	<h2 align="center">Documentos Recibidos en Despacho</h2>
   <? }
   if ($modulo == 13) {
		$sql_dependencia = mysql_query("select * from configuracion_nomina");
  		$bus_dependencia = mysql_fetch_array($sql_dependencia);
		$oficina = "Nomina";	 ?>
     	<h2 align="center">Documentos Recibidos en Nomina</h2>
   <? } ?>
  
     <br />
     	<h2 class="sqlmVersion"></h2>
<br> 
     <form action="" method="post"> 
       
       <input type="hidden" name="destino" id="destino" <? if($_GET["destino"]){ echo "value='".$_GET["destino"]."'";}else{echo "value='".$_POST["destino"]."'";}?>>
       <table width="511" border="0" align="center">
<tr>
            <td><div align="center"> Nro de Documento:
              <input type="text" name="texto_busqueda" id="texto_busqueda">
                      </div></td>
      <td width="18%"><label>
        <input type="submit" name="buscar" id="buscar" value="Buscar" class="button">
      </label></td>
         </tr>
        </table>
     </form>
        
  <?
  if($_POST["buscar"]){
  
  
  	$id_dependencia = $bus_dependencia["iddependencia"];
	$sql_remisiones = mysql_query("select * from recibir_documentos, remision_documentos where 
											recibir_documentos.idremision_documentos = remision_documentos.idremision_documentos 
											and remision_documentos.iddependencia_destino = ".$id_dependencia."
											and remision_documentos.numero_documento LIKE '%".$_POST["texto_busqueda"]."%'");
		
			
  ?>      <br />
  
        <table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="96%">
          <thead>
          <tr>
          	<td width="7%" align="center" class="Browse">No. Documento</td>
            <td width="7%" align="center" class="Browse">Fecha Recibido</td>
            <td width="21%" align="center" class="Browse">Recibido Por</td>
            <td width="7%" align="center" class="Browse">CI</td>
            <td width="53%" align="center" class="Browse">Observaciones</td>
            <td width="5%"  class="Browse">Nro. Doc.</td>
            <td class="Browse" align="center">Detalles</td>
          </tr>
          </thead>
          <? 
          while($bus_remisiones = mysql_fetch_array($sql_remisiones)){
		  ?>
                  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                    <td class='Browse' align='left' width="7%">
                    	<div id="divMostrarDocumentos<?=$bus_remisiones["idremision_documentos"]?>" style="display:none; background-color:#FFFFCC; position:absolute; padding:5px; border:#000000 1px solid">
                        <div align="right">
                        	<a href="javascript:;" onclick="document.getElementById('divMostrarDocumentos<?=$bus_remisiones["idremision_documentos"]?>').style.display='none'">
                            	<strong>X</strong>
                             </a>
                        </div>
                        	<table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="96%">
                            	<thead>
                                  <tr>
                                    <td width="15%" align="center" class="Browse"><strong>Tipo Documento</strong></td>
                                    <td width="10%" align="center" class="Browse"><strong>Numero</strong></td>
                                    <td width="10%" align="center" class="Browse"><strong>Fecha</strong></td>
                                    <td width="10%" align="right" class="Browse"><strong>Monto</strong></td>
                                    <td width="10%" align="right" class="Browse"><strong>Beneficiario</strong></td>
                                    <td width="45%" align="center" class="Browse"><strong>Justificacion</strong></td>
                                  </tr>
                               </thead>

							<?
                            $sql_detalles = mysql_query("select * from relacion_documentos_remision where idremision_documentos = '".$bus_remisiones["idremision_documentos"]."' ")or die(mysql_error());
							while($bus_detalles = mysql_fetch_array($sql_detalles)){
								//echo "select * from ".$bus_detalles["tabla"]." where id".$bus_detalles["tabla"]." = '".$bus_detalles["id_documento"]."'";
								$sql_consulta = mysql_query("select * from ".$bus_detalles["tabla"]." where id".$bus_detalles["tabla"]." = '".$bus_detalles["id_documento"]."'");
								$bus_consulta = mysql_fetch_array($sql_consulta);
								?>
								<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                                	<td class='Browse'>
									<?
                                    $sql_tipos_documentos = mysql_query("select * from tipos_documentos where idtipos_documentos = '".$bus_consulta["tipo"]."'");
									$bus_tipos_documentos = mysql_fetch_array($sql_tipos_documentos);
									echo $bus_tipos_documentos["descripcion"];
									
									?>
                                    </td>
                                    <td class='Browse' align="center"><?=$bus_consulta["numero_orden"]?></td>
                                    <td class='Browse' align="center"><?=$bus_consulta["fecha_elaboracion"]?></td>
                                    <td class='Browse' align="right"><?=number_format($bus_consulta["total"],2,",",".")?></td>
                                    <td class='Browse'>
									<?
                                    $sql_beneficiario = mysql_query("select * from beneficiarios where idbeneficiarios = '".$bus_consulta["idbeneficiarios"]."'");
									$bus_beneficiario = mysql_fetch_array($sql_beneficiario);
									echo "&nbsp;".$bus_beneficiario["nombre"];
									?></td>
                                    <td class='Browse'><?=$bus_consulta["justificacion"]?></td>
                                </tr>
								<?
							}
							?>
                            </table>	
                        </div>
                        &nbsp;<?=$bus_remisiones["numero_documento"]?>
                    </td>
                    <td class='Browse' align='left' width="7%">&nbsp;<?=$bus_remisiones["fecha_recibido"]?></td>
                    <td class='Browse' align='left' width="21%">&nbsp;<?=$bus_remisiones["recibido_por"]?></td>
                    <td class='Browse' align='left' width="7%">&nbsp;<?=$bus_remisiones["ci_recibe"]?></td>
                    <td class='Browse' align='left' width="53%">&nbsp;<?=$bus_remisiones["observaciones"]?></td>
                    <td class='Browse' align='left' width="5%">&nbsp;<?=$bus_remisiones["numero_documentos_recibidos"]?></td>
                    <td class='Browse' align='center' width="5%"><img src="../../imagenes/ver.png" onclick="document.getElementById('divMostrarDocumentos<?=$bus_remisiones["idremision_documentos"]?>').style.display = 'block'" style="cursor:pointer"></td>
                 </tr>
          <?
          }
          ?>
        </table>
 <?
 }
 //registra_transaccion("Listar Documentos Recibidos en ".$oficina." ",$login,$fh,$pc,'documentos_recibidos');
 ?>
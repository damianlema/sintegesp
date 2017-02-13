<?
include("../../../conf/conex.php");
$conection_db = conectarse();
include("../../../funciones/funciones.php");




?>
<script type="text/javascript" src="../js/funciones.js"></script>
<script type="text/javascript" src="../js/function.js"></script>
<link href="../../../css/theme/green/main.css" rel="stylesheet" type="text/css">
  <style type="text/css"> @import url("../css/theme/calendar-win2k-cold-1.css"); </style>   
 	<?
  		$sql_dependencia = mysql_query("select * from configuracion_presupuesto");
  		$bus_dependencia = mysql_fetch_array($sql_dependencia);
 	?>
     	<h2 align="center">Documentos Conformados en Presupuesto</h2>
   
     <br />
     	<h2 class="sqlmVersion"></h2>
<br> 
     <form action="" method="post"> 
       
       <input type="hidden" name="destino" id="destino" <? if($_GET["destino"]){ echo "value='".$_GET["destino"]."'";}else{echo "value='".$_POST["destino"]."'";}?>>
       <table width="90%" border="0" align="center">
     <tr>
             <td width="22%" align="right">Tipo de Documento:</td>
             <td width="18%">
                    <select name="tipo_documento">
                          <option value="0">.::Seleccione::.</option>
                          <? 	$sql_tipos_documentos = mysql_query("select * from tipos_documentos where compromete != 'no' or paga != 'no' or causa != 'no' or documento_asociado != 0");
                                while ($bus_tipos_documentos = mysql_fetch_array($sql_tipos_documentos)){
                          ?>                              
                                      <option value="<?=$bus_tipos_documentos["idtipos_documentos"]?>"><?=$bus_tipos_documentos["descripcion"]?></option>
                             <? } ?>
                    </select>
          </td>
                    <td width="40%" align="right">Beneficiario / Nro de Documento:</td>
          <td width="13%"><input type="text" name="texto_busqueda" id="texto_busqueda"></td>
              <td width="7%" align="center">
                      <input type="submit" name="buscar" id="buscar" value="Buscar" class="button">
          </td>
         </tr>
        </table>
     </form>
        
  <?
  if($_POST["buscar"]){
  
  	if ($_POST["tipo_documento"] == "0"){
		echo "<script text:javascript>alert ('Seleccione un tipo de documento'); this.form.submit();</script>";
	}else{
		$id_dependencia = $bus_dependencia["iddependencia"];
		$idtipos_documentos = $_POST["tipo_documento"];
		$sql_tipo_documento = mysql_query("select * from tipos_documentos where idtipos_documentos = ".$idtipos_documentos."");
		$bus_tipo_documento = mysql_fetch_array($sql_tipo_documento);
		if ($bus_tipo_documento["compromete"] == "si" and $bus_tipo_documento["causa"] == "no"){
			$sql_conformados = mysql_query("select 	conformar_documentos.fecha_conformado, 
													conformar_documentos.conformado_por, 
													conformar_documentos.observaciones, 
													orden_compra_servicio.numero_orden, 
													orden_compra_servicio.estado,
													beneficiarios.nombre 
											from conformar_documentos,orden_compra_servicio,beneficiarios
											where conformar_documentos.tipo = '".$idtipos_documentos."'
												and orden_compra_servicio.idorden_compra_servicio = conformar_documentos.iddocumento
												and (orden_compra_servicio.estado = 'conformado' or orden_compra_servicio.estado = 'devuelto')
												and orden_compra_servicio.idbeneficiarios = beneficiarios.idbeneficiarios
												and (orden_compra_servicio.numero_orden like '%".$_POST[texto_busqueda]."%'
												or beneficiarios.nombre like '%".$_POST[texto_busqueda]."%')")or die(mysql_error());
											
		}else if(($bus_tipo_documento["compromete"] == "si" or $bus_tipo_documento["compromete"] == "no") and $bus_tipo_documento["causa"] == "si"){
			$sql_conformados = mysql_query("select conformar_documentos.fecha_conformado, 
													conformar_documentos.conformado_por, 
													conformar_documentos.observaciones, 
													orden_pago.numero_orden, 
													orden_pago.estado,
													beneficiarios.nombre   
											from conformar_documentos,orden_pago,beneficiarios
											where conformar_documentos.tipo = '".$idtipos_documentos."' 
												and orden_pago.idorden_pago = conformar_documentos.iddocumento
												and (orden_pago.estado = 'conformado' or orden_pago.estado = 'devuelto')
												and orden_pago.idbeneficiarios = beneficiarios.idbeneficiarios
												and (orden_pago.numero_orden like '%".$_POST[texto_busqueda]."%'
												or beneficiarios.nombre like '%".$_POST[texto_busqueda]."%')")or die(mysql_error());
		
		}
	  ?>      
      		<br>
	  		<table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="96%">
			  <thead>
			  <tr>
				<td width="5%" align="center" class="Browse">No. Documento</td>
                <td width="22%" align="center" class="Browse">Beneficiario</td>
				<td width="7%" align="center" class="Browse">Fecha Conformado</td>
				<td width="6%" align="center" class="Browse">Estado</td>
				<td width="16%" align="center" class="Browse">Conformado por</td>
				<td width="44%" align="center" class="Browse">Observaciones</td>
			  </tr>
			  </thead>
			  <? 
			  while($bus_conformado = mysql_fetch_array($sql_conformados)){
			  ?>
					  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
						<td class='Browse' align='left' width="5%">&nbsp;<?=$bus_conformado["numero_orden"]?></td>
                        <td class='Browse' align='left' width="22%">&nbsp;<?=$bus_conformado["nombre"]?></td>
						<td class='Browse' align='left' width="7%">&nbsp;<?=$bus_conformado["fecha_conformado"]?></td>
						<td class='Browse' align='left' width="6%">&nbsp;<? 
							if ($bus_conformado["estado"] == "conformado") { echo "Conformado"; } else { echo "Devuelto"; } ?>						</td>
						<td class='Browse' align='left' width="16%">&nbsp;<?=$bus_conformado["conformado_por"]?></td>
						<td class='Browse' align='left' width="44%">&nbsp;<?=$bus_conformado["observaciones"]?></td>
			  </tr>
			  <?
			  }
			  ?>
			</table>
 <?
 	}
 }
 registra_transaccion("Listar Documentos Conformados de presupuesto",$login,$fh,$pc,'conformar_documentos');
 ?>
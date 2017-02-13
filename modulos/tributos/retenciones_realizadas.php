<script src="modulos/tributos/js/retenciones_realizadas_ajax.js"></script>
    <br>
	<h4 align=center>Retenciones Realizadas</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
    <br />
    <form method="post" action="">
    <table align="center" width="37%">
<tr>
        	<td width="37%" align="right" class='viewPropTitle'>Numero de Documento</td>
            <td width="41%"><input name="nro_documento" type="text" id="nro_documento" size="30"></td>
            <td width="21%"><input type="submit" name="boton_buscar" id="boton_buscar" value="Buscar" class="button"></td>
        <td width="1%"></td>
    </table>
    </form>
<?
if($_POST["nro_documento"]){
	
		
	$sql_consultar = mysql_query("select * from retenciones where numero_documento like '%".$_POST["nro_documento"]."%' order by fecha_retencion")or die(mysql_error());
/*}else{
		$sql_consultar = mysql_query("select * from retenciones where (estado = 'pagado' or estado = 'procesado') order by fecha_retencion");
	}*/

?>

<div id="divImprimir1" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid; top:50px;">
    <table align="center">
        <tr><td align="right"><a href="#" onClick="document.getElementById('divImprimir1').style.display='none';">X</a></td></tr>
        <tr><td><iframe name="ventanaPDF1" id="ventanaPDF1" style="display:none" height="600" width="750"></iframe></td></tr>
    </table>
</div>


<table class="Browse" align=center cellpadding="0" cellspacing="0" width="50%">
  <thead>
	<tr>
    	<td width="23%" class="Browse">Nro Documento</td>
      <td width="27%" align="center" class="Browse">Fecha de Retencion</td>
      <td width="23%" class="Browse">Nro Factura</td>
      <td width="19%" class="Browse">Total Retenido</td>
        <td width="8%" class="Browse">Acci&oacute;n</td>
     </tr>
     </thead>
     <?
     while($bus_consultar = mysql_fetch_array($sql_consultar)){
	?>
		<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
        	<td class="Browse">
            &nbsp;<?=$bus_consultar["numero_documento"]?>
            </td>
            <td align="center" class="Browse">&nbsp;<?=$bus_consultar["fecha_retencion"]?></td>
            <td class="Browse">&nbsp;<?=$bus_consultar["numero_factura"]?></td>
            <td class="Browse" align="right">&nbsp;<?=number_format($bus_consultar["total_retenido"],2,",",".")?></td>
            <td class="Browse" align="center">
            <a href="#" onclick="document.getElementById('ventanaPDF1').src='lib/reportes/tributos/reportes.php?nombre=registro_retenciones&id_retencion=<?=$bus_consultar["idretenciones"]?>'; document.getElementById('ventanaPDF1').style.display='block'; document.getElementById('divImprimir1').style.display='block';">
            	<img src="imagenes/imprimir.png" style="cursor:pointer" title="Imprimir Retenciones del Documento Nro. <?=$bus_consultar["numero_documento"]?>">
            </a>
            <?
			
            // VERIFICO SI LA RETENCION NO SE HA PAGADO PARA PODER ELIMINARLA
			$sql_validar = mysql_query("select * from relacion_orden_pago_retencion where idretencion = '".$bus_consultar["idretenciones"]."'");
			$bus_validar = mysql_fetch_array($sql_validar);
			if (mysql_num_rows($sql_validar)>0){
				$sql_op = mysql_query("select * from orden_pago where idorden_pago = '".$bus_validar["idorden_pago"]."'");
				$bus_op = mysql_fetch_array($sql_op);
				if ($bus_op["estado"] <> 'anulado'){
					$pagado = 1;
				}else{
					$pagado = 0;
				}
			}else{
				$pagado = 0;
			}
			if ($pagado == 0){
				?>
				<a onclick="eliminar_retencion('<?=$bus_consultar["idretenciones"]?>')" href="javascript:;">
					<img src="imagenes/delete.png" style="cursor:pointer" title="Eliminar esta Retención del Documento Nro. <?=$bus_consultar["numero_documento"]?>">
				</a>
            <? } ?>
            
            </td>
        </tr>  	
	<?
	 }
}
	 ?>
</table>
        
<?


if($_POST){
extract($_POST);
if ($_POST["genera_comprobante"])
		$genera_comprobante=1;
	else
		$genera_comprobante=0;
		
			$sql_actualizar = mysql_query("update configuracion_tributos set primero_tributos = '".$primero_tributos."',
																			ci_primero_tributos = '".$ci_primero_tributos."',
																			cargo_primero_tributos = '".$cargo_primero_tributos."',
																			segundo_tributos = '".$segundo_tributos."',
																			ci_segundo_tributos = '".$ci_segundo_tributos."',
																			cargo_segundo_tributos = '".$cargo_segundo_tributos."',
																			tercero_tributos = '".$tercero_tributos."',
																			ci_tercero_tributos = '".$ci_tercero_tributos."',
																			cargo_tercero_tributos = '".$cargo_tercero_tributos."',
																			nro_remision = '".$nro_remision."',
																			iddependencia = '".$dependencias."', 
																			nro_linea_comprobante = '".$nro_linea_comprobante."', 
																			status = 'a',
																			usuario= '".$login."',
																			fechayhora= '".$fh."',
																			nro_retencion_externa = '".$nro_retencion."',
																			genera_comprobante = '".$genera_comprobante."',
																			primera_linea = '".$primera_linea."',
																			segunda_linea = '".$segunda_linea."',
																			tercera_linea = '".$tercera_linea."',
																			firma1 = '".$firma1."',
																			cargofirma1 = '".$cargofirma1."',
																			firma2 = '".$firma2."',
																			cargofirma2 = '".$cargofirma2."',
																			firma3 = '".$firma3."',
																			cargofirma3 = '".$cargofirma3."'
																			")or die(mysql_query());
			$sql_actualizar_dependencia = mysql_query("update dependencias set idmodulo = '".$_SESSION["modulo"]."' where iddependencia = '".$dependencias."'");																
			?>
			<script>
			mostrarMensajes("exito", "La configuracion ha sido Guardada con Exito");
			</script>
			<?
}
$sql= mysql_query("select * from configuracion_tributos");
$bus = mysql_fetch_array($sql);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
	<title>Gesti&oacute;n P&uacute;blica</title>
    </head>
    <body>
	<br>
	<h4 align=center>Configuraci&oacute;n Tributos Internos</h4>
	<h2 class="sqlmVersion"></h2>
	<br>

<form name="form1" method="post" action="">
  <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="316">&nbsp;</td>
      <td width="270">&nbsp;</td>
    </tr>
    <tr>
   	 <td class="viewPropTitle"><div align="right">Dependencia:</div></td>
     <td colspan="5">
     <select name="dependencias" id="dependencias">
	  <option value="0">.:: Seleccione ::. </option>
	  <?
      $sql_dependencias = mysql_query("select * from dependencias order by denominacion");
	  while($bus_dependencias = mysql_fetch_array($sql_dependencias)){
	  	?>
	  	<option value="<?=$bus_dependencias["iddependencia"]?>"<? if ($bus_dependencias["iddependencia"] == $bus["iddependencia"]) echo " selected"?>><?=$bus_dependencias["denominacion"]?></option>
	  	<?
	  }
	  ?>
      </select>      </td>
    </tr>
     <tr>
      <td width="316">&nbsp;</td>
      <td width="270">&nbsp;</td>
    </tr>
    <tr>
      <td class="viewPropTitle"><p align="right">Nombre y Apellidos Primer nivel:</p>      </td>
      <td><label>
        <input name="primero_tributos" type="text" id="primero_tributos" value="<?=$bus["primero_tributos"]?>" size="45">
      </label></td>
      <td width="42" class="viewPropTitle"><div align="right">C.I.: </div></td>
      <td width="109"><label>
        <input name="ci_primero_tributos" type="text" id="ci_primero_tributos" value="<?=$bus["ci_primero_tributos"]?>" size="16">
      </label></td>
       <td width="57" class="viewPropTitle"><div align="right">Cargo: </div></td>
      <td width="388"><label>
        <input name="cargo_primero_tributos" type="text" id="cargo_primero_tributos" value="<?=$bus["cargo_primero_tributos"]?>" size="60">
      </label></td>
    </tr>
    <tr>
      <td class="viewPropTitle"><p align="right">Nombre y Apellidos Segundo nivel:</p>      </td>
      <td><label>
        <input name="segundo_tributos" type="text" id="segundo_tributos" value="<?=$bus["segundo_tributos"]?>" size="45">
      </label></td>
      <td width="42" class="viewPropTitle"><div align="right">C.I.: </div></td>
      <td width="109"><label>
        <input name="ci_segundo_tributos" type="text" id="ci_segundo_tributos" value="<?=$bus["ci_segundo_tributos"]?>" size="16">
      </label></td>
      <td width="57" class="viewPropTitle"><div align="right">Cargo: </div></td>
      <td width="388"><label>
        <input name="cargo_segundo_tributos" type="text" id="cargo_segundo_tributos" value="<?=$bus["cargo_segundo_tributos"]?>" size="60">
      </label></td>
    </tr>
     <tr>
      <td class="viewPropTitle"><p align="right">Nombre y Apellidos Tercer nivel:</p>      </td>
      <td><label>
        <input name="tercero_tributos" type="text" id="tercero_tributos" value="<?=$bus["tercero_tributos"]?>" size="45">
      </label></td>
      <td width="42" class="viewPropTitle"><div align="right">C.I.: </div></td>
      <td width="109"><label>
        <input name="ci_tercero_tributos" type="text" id="ci_tercero_tributos" value="<?=$bus["ci_tercero_tributos"]?>" size="16">
      </label></td>
       <td width="57" class="viewPropTitle"><div align="right">Cargo: </div></td>
      <td width="388"><label>
        <input name="cargo_tercero_tributos" type="text" id="cargo_tercero_tributos" value="<?=$bus["cargo_tercero_tributos"]?>" size="60">
      </label></td>
    </tr>
     <tr>
      <td width="316">&nbsp;</td>
      <td width="270">&nbsp;</td>
    </tr>
     <tr>
       <td colspan="2" class="viewPropTitle">N&uacute;mero de Documento de Remisi&oacute;n:</td>
       <td><input style="text-align:right" name="nro_remision" type="text" id="nro_remision" value="<?=$bus["nro_remision"]?>" size="10"></td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>

     </tr>
     <tr>
       <td colspan="2" class="viewPropTitle">N&uacute;mero de Retencion Externa</td>
       <td><input style="text-align:right" name="nro_retencion" type="text" id="nro_retencion" value="<?=$bus["nro_retencion_externa"]?>" size="10"></td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>

     </tr>
     <tr>
       <td colspan="2" class="viewPropTitle">N&uacute;mero de Lineas de Comprobante</td>
       <td><label>
         <input name="nro_linea_comprobante" type="text" id="nro_linea_comprobante" size="10" value="<?=$bus["nro_linea_comprobante"]?>" style="text-align:right">
       </label></td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>

     </tr>
     <tr>
       <td colspan="2" class="viewPropTitle">Generar Comprobante al hacer cheque</td>
       <td><label>
         <input type="checkbox" name="genera_comprobante" id="genera_comprobante" value="1" <? if($bus["genera_comprobante"] == 1){ echo "checked";}?>>
       </label></td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>

     </tr>
     <tr>
      <td width="316">&nbsp;</td>
      <td width="270">&nbsp;</td>
    </tr>
    <tr>
       <td class="viewPropTitle" colspan="2"><p align="left">Encabezado Comprobante linea 1:</p>      </td>
       <td colspan="4"><input name="primera_linea" type="text" id="primera_linea" value="<?=$bus["primera_linea"]?>" size="70"></td>
    </tr>
     <tr>
       <td class="viewPropTitle" colspan="2"><p align="left">Encabezado Comprobante linea 2:</p>      </td>
       <td colspan="4"><input name="segunda_linea" type="text" id="segunda_linea" value="<?=$bus["segunda_linea"]?>" size="70"></td>
    </tr>
     <tr>
       <td class="viewPropTitle" colspan="2"><p align="left">Encabezado Comprobante linea 3:</p>      </td>
       <td colspan="4"><input name="tercera_linea" type="text" id="tercera_linea" value="<?=$bus["tercera_linea"]?>" size="70"></td>
    </tr>
    <tr>
      <td width="316">&nbsp;</td>
      <td width="270">&nbsp;</td>
    </tr>
    <tr>
      <td width="316" colspan="5"><strong>Firmas Libro Uno x Mil</strong></td>
      <td width="270">&nbsp;</td>
    </tr>
    <tr>
       <td class="viewPropTitle"><p align="right">Firma 1</p>      </td>
       <td colspan="2"><input name="firma1" type="text" id="firma1" value="<?=$bus["firma1"]?>" size="70"></td>
       <td class="viewPropTitle"><p align="right">Cargo Firma 1</p>      </td>
       <td colspan="2"><input name="cargofirma1" type="text" id="cargofirma1" value="<?=$bus["cargofirma1"]?>" size="70"></td>
    </tr>
     <tr>
       <td class="viewPropTitle"><p align="right">Firma 2</p>      </td>
       <td colspan="2"><input name="firma2" type="text" id="firma2" value="<?=$bus["firma2"]?>" size="70"></td>
       <td class="viewPropTitle"><p align="right">Cargo Firma 2</p>      </td>
       <td colspan="2"><input name="cargofirma2" type="text" id="cargofirma2" value="<?=$bus["cargofirma2"]?>" size="70"></td>
    </tr>
    <tr>
       <td class="viewPropTitle"><p align="right">Firma 3</p>      </td>
       <td colspan="2"><input name="firma3" type="text" id="firma3" value="<?=$bus["firma3"]?>" size="70"></td>
       <td class="viewPropTitle"><p align="right">Cargo Firma 3</p>      </td>
       <td colspan="2"><input name="cargofirma3" type="text" id="cargofirma3" value="<?=$bus["cargofirma3"]?>" size="70"></td>
    </tr>
    <tr>
      <td colspan="6"><div align="center">
        <input type="submit" name="button" id="button" value="Actualizar" class="button" />
        <input type="reset" name="button2" id="button2" value="Cancelar" class="button" />
      </div></td>
    </tr>
  </table>
</form>
</body>
</html>
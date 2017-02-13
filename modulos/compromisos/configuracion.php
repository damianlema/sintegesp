<?


if($_POST){
extract($_POST);
			$sql_actualizar = mysql_query("update configuracion_compras set primero_compras = '".$primero_compras."',
																			ci_primero_compras = '".$ci_primero_compras."',
																			cargo_primero_compras = '".$cargo_primero_compras."',
																			segundo_compras = '".$segundo_compras."',
																			ci_segundo_compras = '".$ci_segundo_compras."',
																			cargo_segundo_compras = '".$cargo_segundo_compras."',
																			tercero_compras = '".$tercero_compras."',
																			ci_tercero_compras = '".$ci_tercero_compras."',
																			cargo_tercero_compras = '".$cargo_tercero_compras."',
																			iddependencia = '".$dependencias."',
																			nro_solicitud_cotizacion = '".$nro_solicitud_cotizacion."',
																			nro_remision = '".$nro_remision."',
																			nro_requisicion = '".$nro_requisicion."',
																			status = 'a',
																			usuario= '".$login."',
																			fechayhora= '".$fh."',
																			organo_responsable = '".$organo_responsable."',
																			organo_ejecutor = '".$organo_ejecutor."',
																			rif_ejecutor = '".$rif_ejecutor."',
																			funcionario_responsable = '".$funcionario_responsable."',
																			funcionario_contacto = '".$funcionario_contacto."',
																			telefono = '".$telefono."',
																			email = '".$email."',
																			nombre_acto_motivado_1 = '".$nombre_acto_motivado_1."',
																			nombre_acto_motivado_2 = '".$nombre_acto_motivado_2."',
																			nombre_acto_motivado_3 = '".$nombre_acto_motivado_3."',
																			cedula_acto_motivado_1 = '".$cedula_acto_motivado_1."',
																			cedula_acto_motivado_2 = '".$cedula_acto_motivado_2."',
																			cedula_acto_motivado_3 = '".$cedula_acto_motivado_3."',
																			cargo_acto_motivado_1 = '".$cargo_acto_motivado_1."',
																			cargo_acto_motivado_2 = '".$cargo_acto_motivado_2."',
																			cargo_acto_motivado_3 = '".$cargo_acto_motivado_3."',
																			nombre_analisis_1 = '".$nombre_analisis_1."',
																			nombre_analisis_2 = '".$nombre_analisis_2."',
																			nombre_analisis_3 = '".$nombre_analisis_3."',
																			cedula_analisis_1 = '".$cedula_analisis_1."',
																			cedula_analisis_2 = '".$cedula_analisis_2."',
																			cedula_analisis_3 = '".$cedula_analisis_3."',
																			cargo_analisis_1 = '".$cargo_analisis_1."',
																			cargo_analisis_2 = '".$cargo_analisis_2."',
																			cargo_analisis_3 = '".$cargo_analisis_3."'
																			")or die(mysql_error());
																			
			$sql_actualizar_dependencia = mysql_query("update dependencias set idmodulo = '".$_SESSION["modulo"]."' where iddependencia = '".$dependencias."'");
																			
			?>
			<script>
			mostrarMensajes("exito", "La configuracion ha sido Guardada con Exito");
			</script>
			<?
}
$sql= mysql_query("select * from configuracion_compras");
$bus = mysql_fetch_array($sql);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
	<title>Gesti&oacute;n P&uacute;blica</title>
</head>
    <body>
	<br>
	<h4 align=center>Configuraci&oacute;n Compras y Servicios</h4>
	<h2 class="sqlmVersion"></h2>
	<br>

<form name="form1" method="post" action="">
  <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td colspan="6">&nbsp;</td>
    </tr>
    <tr>
   	 <td width="305" class="viewPropTitle"><div align="right">Dependencia:</div></td>
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
      <td colspan="6" >&nbsp;</td>
    </tr>
    <tr>
      <td class="viewPropTitle"><p align="right">Nombre y Apellidos Primer nivel:&nbsp;</p>      </td>
      <td width="284"><label>
        <input name="primero_compras" type="text" id="primero_compras" value="<?=$bus["primero_compras"]?>" size="45">
      </label></td>
      <td width="48" class="viewPropTitle"><div align="right">C.I.: </div></td>
      <td width="118"><label>
        <input name="ci_primero_compras" type="text" id="ci_primero_compras" value="<?=$bus["ci_primero_compras"]?>" size="16">
      </label></td>
      <td width="60" class="viewPropTitle"><div align="right">Cargo: </div></td>
      <td width="367"><label>
        <input name="cargo_primero_compras" type="text" id="cargo_primero_compras" value="<?=$bus["cargo_primero_compras"]?>" size="60">
      </label></td>
    </tr>
     <tr>
      <td class="viewPropTitle"><p align="right">Nombre y Apellidos Segundo nivel:&nbsp;</p>      </td>
      <td><label>
        <input name="segundo_compras" type="text" id="segundo_compras" value="<?=$bus["segundo_compras"]?>" size="45">
      </label></td>
      <td width="48" class="viewPropTitle"><div align="right">C.I.: </div></td>
      <td width="118"><label>
        <input name="ci_segundo_compras" type="text" id="ci_segundo_compras" value="<?=$bus["ci_segundo_compras"]?>" size="16">
      </label></td>
       <td width="60" class="viewPropTitle"><div align="right">Cargo: </div></td>
      <td width="367"><label>
        <input name="cargo_segundo_compras" type="text" id="cargo_segundo_compras" value="<?=$bus["cargo_segundo_compras"]?>" size="60">
      </label></td>
    </tr>
    <tr>
      <td class="viewPropTitle"><p align="right">Nombre y Apellidos Tercer nivel:&nbsp;</p>      </td>
      <td><label>
        <input name="tercero_compras" type="text" id="tercero_compras" value="<?=$bus["tercero_compras"]?>" size="45">
      </label></td>
      <td width="48" class="viewPropTitle"><div align="right">C.I.: </div></td>
      <td width="118"><label>
        <input name="ci_tercero_compras" type="text" id="ci_tercero_compras" value="<?=$bus["ci_tercero_compras"]?>" size="16">
      </label></td>
      <td width="60" class="viewPropTitle"><div align="right">Cargo: </div></td>
      <td width="367"><label>
        <input name="cargo_tercero_compras" type="text" id="cargo_tercero_compras" value="<?=$bus["cargo_tercero_compras"]?>" size="60">
      </label></td>
    </tr>
     <tr>
      <td colspan="6" >&nbsp;</td>
    </tr>
    <tr>
      <td class="viewPropTitle" ><div align="right">N&uacute;mero de Consulta de Precios:&nbsp;</div></td>
      <td colspan="5"><label>
        <input style="text-align:right" name="nro_solicitud_cotizacion" type="text" id="nro_solicitud_cotizacion" value="<?=$bus["nro_solicitud_cotizacion"]?>" size="5">
      </label></td>
    </tr>
    <tr>
      <td align="right" class="viewPropTitle">N&uacute;mero de Requisicion&nbsp;</td>
      <td colspan="5"><input style="text-align:right" name="nro_requisicion" type="text" id="nro_requisicion" value="<?=$bus["nro_requisicion"]?>" size="5"></td>
    </tr>
    <tr>
      <td class="viewPropTitle"><div align="right">N&uacute;mero de Documento de Remisi&oacute;n&nbsp;</div></td>
      <td colspan="5"><input style="text-align:right" name="nro_remision" type="text" id="nro_remision" value="<?=$bus["nro_remision"]?>" size="5" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="5">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="6" class="viewPropTitle"><div align="center"><strong>FIRMAS ACTO MOTIVADO</strong></div></td>
    </tr>
    <tr>
      <td colspan="6">&nbsp;</td>
    </tr>
    <tr>
      <td align="right" class="viewPropTitle">Nombre Firma 1&nbsp;</td>
      <td><label>
        <input name="nombre_acto_motivado_1" type="text" id="nombre_acto_motivado_1" size="45" value="<?=$bus["nombre_acto_motivado_1"]?>">
      </label></td>
      <td class="viewPropTitle">Cedula</td>
      <td><label>
        <input type="text" name="cedula_acto_motivado_1" id="cedula_acto_motivado_1" value="<?=$bus["cedula_acto_motivado_1"]?>">
      </label></td>
      <td class="viewPropTitle">Cargo</td>
      <td><label>
        <input name="cargo_acto_motivado_1" type="text" id="cargo_acto_motivado_1" size="60" value="<?=$bus["cargo_acto_motivado_1"]?>">
      </label></td>
    </tr>
    <tr>
      <td align="right" class="viewPropTitle"><p>Nombre Firma 2&nbsp;</p>      </td>
      <td><label>
        <input name="nombre_acto_motivado_2" type="text" id="nombre_acto_motivado_2" size="45" value="<?=$bus["nombre_acto_motivado_2"]?>">
      </label></td>
      <td class="viewPropTitle">Cedula</td>
      <td><label>
        <input type="text" name="cedula_acto_motivado_2" id="cedula_acto_motivado_2" value="<?=$bus["cedula_acto_motivado_2"]?>">
      </label></td>
      <td class="viewPropTitle">Cargo</td>
      <td><label>
        <input name="cargo_acto_motivado_2" type="text" id="cargo_acto_motivado_2" size="60" value="<?=$bus["cargo_acto_motivado_2"]?>">
      </label></td>
    </tr>
    <tr>
      <td align="right" class="viewPropTitle">Nombre Firma 3&nbsp;</td>
      <td><label>
        <input name="nombre_acto_motivado_3" type="text" id="nombre_acto_motivado_3" size="45" value="<?=$bus["nombre_acto_motivado_3"]?>">
      </label></td>
      <td class="viewPropTitle">Cedula</td>
      <td><label>
        <input type="text" name="cedula_acto_motivado_3" id="cedula_acto_motivado_3" value="<?=$bus["cedula_acto_motivado_3"]?>">
      </label></td>
      <td class="viewPropTitle">Cargo</td>
      <td><label>
        <input name="cargo_acto_motivado_3" type="text" id="cargo_acto_motivado_3" size="60" value="<?=$bus["cargo_acto_motivado_3"]?>">
      </label></td>
    </tr>
    <tr>
      <td colspan="6" align="center" >&nbsp;</td>
    </tr>
    
    <tr>
      <td colspan="6" class="viewPropTitle"><div align="center"><strong>FIRMAS ANALISIS DE COTIZACION</strong></div></td>
    </tr>
    <tr>
      <td colspan="6">&nbsp;</td>
    </tr>
    <tr>
      <td align="right" class="viewPropTitle">Nombre Firma 1&nbsp;</td>
      <td><label>
        <input name="nombre_analisis_1" type="text" id="nombre_analisis_1" size="45" value="<?=$bus["nombre_analisis_1"]?>">
      </label></td>
      <td class="viewPropTitle">Cedula</td>
      <td><label>
        <input type="text" name="cedula_analisis_1" id="cedula_analisis_1" value="<?=$bus["cedula_analisis_1"]?>">
      </label></td>
      <td class="viewPropTitle">Cargo</td>
      <td><label>
        <input name="cargo_analisis_1" type="text" id="cargo_analisis_1" size="60" value="<?=$bus["cargo_analisis_1"]?>">
      </label></td>
    </tr>
    <tr>
      <td align="right" class="viewPropTitle"><p>Nombre Firma 2&nbsp;</p>      </td>
      <td><label>
        <input name="nombre_analisis_2" type="text" id="nombre_analisis_2" size="45" value="<?=$bus["nombre_analisis_2"]?>">
      </label></td>
      <td class="viewPropTitle">Cedula</td>
      <td><label>
        <input type="text" name="cedula_analisis_2" id="cedula_analisis_2" value="<?=$bus["cedula_analisis_2"]?>">
      </label></td>
      <td class="viewPropTitle">Cargo</td>
      <td><label>
        <input name="cargo_analisis_2" type="text" id="cargo_analisis_2" size="60" value="<?=$bus["cargo_analisis_2"]?>">
      </label></td>
    </tr>
    <tr>
      <td align="right" class="viewPropTitle">Nombre Firma 3&nbsp;</td>
      <td><label>
        <input name="nombre_analisis_3" type="text" id="nombre_analisis_3" size="45" value="<?=$bus["nombre_analisis_3"]?>">
      </label></td>
      <td class="viewPropTitle">Cedula</td>
      <td><label>
        <input type="text" name="cedula_analisis_3" id="cedula_analisis_3" value="<?=$bus["cedula_analisis_3"]?>">
      </label></td>
      <td class="viewPropTitle">Cargo</td>
      <td><label>
        <input name="cargo_analisis_3" type="text" id="cargo_analisis_3" size="60" value="<?=$bus["cargo_analisis_3"]?>">
      </label></td>
    </tr>
    
    <tr>
      <td colspan="6" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="6" align="center" class="viewPropTitle"><strong>DATOS DEL SUMARIO DE CONTRATACIONES</strong></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="5">&nbsp;</td>
    </tr>
    <tr>
      <td class="viewPropTitle"><div align="right">Organismo o Ente Responsable</div></td>
      <td colspan="5"><input style="text-align:left" name="organo_responsable" type="text" id="organo_responsable" value="<?=$bus["organo_responsable"]?>" size="70" /></td>
    </tr>
    <tr>
      <td class="viewPropTitle"><div align="right">Organismo o Ente Ejecutor</div></td>
      <td colspan="5"><input style="text-align:left" name="organo_ejecutor" type="text" id="organo_ejecutor" value="<?=$bus["organo_ejecutor"]?>" size="70" /></td>
      <tr>
      <td class="viewPropTitle"><div align="right">R.I.F. Del Organo o Ente Ejecutor</div></td>
      <td colspan="5"><input style="text-align:left" name="rif_ejecutor" type="text" id="rif_ejecutor" value="<?=$bus["rif_ejecutor"]?>" size="45" /></td>
    </tr>
    <tr>
      <td class="viewPropTitle"><div align="right">Funcionario Responsable</div></td>
      <td colspan="5"><input style="text-align:left" name="funcionario_responsable" type="text" id="funcionario_responsable" value="<?=$bus["funcionario_responsable"]?>" size="70" /></td>
    </tr>
    <tr>
      <td class="viewPropTitle"><div align="right">Funcionario Contacto</div></td>
      <td colspan="5"><input style="text-align:left" name="funcionario_contacto" type="text" id="funcionario_contacto" value="<?=$bus["funcionario_contacto"]?>" size="45" /></td>
    </tr>
    <tr>
      <td class="viewPropTitle"><div align="right">Telefono</div></td>
      <td colspan="5"><input style="text-align:left" name="telefono" type="text" id="telefono" value="<?=$bus["telefono"]?>" size="45" /></td>
    </tr>
    <tr>
      <td class="viewPropTitle"><div align="right">Correo Electronico</div></td>
      <td colspan="5"><input style="text-align:left" name="email" type="text" id="email" value="<?=$bus["email"]?>" size="45" /></td>
    </tr>
    <tr>
      <td colspan="6">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="6" align="center">
        <input type="submit" name="button" id="button" value="Actualizar" class="button">
   		<input type="reset" name="button2" id="button2" value="Cancelar" class="button"></td>
    </tr>
  </table>
</form>

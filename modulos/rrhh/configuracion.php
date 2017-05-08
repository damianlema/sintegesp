<?


if($_POST){
extract($_POST);
			$sql_actualizar = mysql_query("update configuracion_rrhh set primero_rrhh = '".$primero_rrhh."',
																			ci_primero_rrhh = '".$ci_primero_rrhh."',
																			cargo_primero_rrhh = '".$cargo_primero_rrhh."',
																			segundo_rrhh = '".$segundo_rrhh."',
																			ci_segundo_rrhh = '".$ci_segundo_rrhh."',
																			cargo_segundo_rrhh = '".$cargo_segundo_rrhh."',
																			tercero_rrhh = '".$tercero_rrhh."',
																			ci_tercero_rrhh = '".$ci_tercero_rrhh."',
																			cargo_tercero_rrhh = '".$cargo_tercero_rrhh."',
																			nro_remision = '".$nro_remision."',
																			iddependencia = '".$dependencias."',
																			status = 'a',
																			usuario = '".$login."',
																			fechayhora= '".$fh."',
                                      numero_patronal_ivss = '".$numero_patronal_ivss."',
                                      fecha_inscripcion_patronal_ivss = '".$fecha_inscripcion_patronal_ivss."',
                                      regimen_ivss = '".$regimen_ivss."',
                                      riesgo_ivss = '".$riesgo_ivss."',
                                      nombre_apellido_representante_ivss = '".$nombre_apellido_representante_ivss."',
                                      cedula_representante_ivss = '".$cedula_representante_ivss."'")or die(mysql_query());

				$sql_actualizar_dependencia = mysql_query("update dependencias set idmodulo = '".$_SESSION["modulo"]."' where iddependencia = '".$dependencias."'");

			?>
			<script>
			mostrarMensajes("exito", "La configuracion ha sido Guardada con Exito");
			</script>
			<?
}
$sql= mysql_query("select * from configuracion_rrhh");
$bus = mysql_fetch_array($sql);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
	<title>Gesti&oacute;n P&uacute;blica</title>
    </head>
    <body>
	<br>
	<h4 align=center>Configuraci&oacute;n Recursos Humanos</h4>
	<h2 class="sqlmVersion"></h2>
	<br>

<form name="form1" method="post" action="">
  <table width="85%" border="0" align="center" cellpadding="0" cellspacing="0">
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
      </select>
      </td>
    </tr>
     <tr>
      <td width="316">&nbsp;</td>
      <td width="270">&nbsp;</td>
    </tr>
    <tr>
      <td class="viewPropTitle"><p align="right">Nombre y Apellidos Primer nivel:</p>      </td>
      <td><label>
        <input name="primero_rrhh" type="text" id="primero_rrhh" value="<?=$bus["primero_rrhh"]?>" size="45">
      </label></td>
      <td width="42" class="viewPropTitle"><div align="right">C.I.: </div></td>
      <td width="109"><label>
        <input name="ci_primero_rrhh" type="text" id="ci_primero_rrhh" value="<?=$bus["ci_primero_rrhh"]?>" size="16">
      </label></td>
       <td width="57" class="viewPropTitle"><div align="right">Cargo: </div></td>
      <td width="388"><label>
        <input name="cargo_primero_rrhh" type="text" id="cargo_primero_rrhh" value="<?=$bus["cargo_primero_rrhh"]?>" size="60">
      </label></td>
    </tr>
    <tr>
      <td class="viewPropTitle"><p align="right">Nombre y Apellidos Segundo nivel:</p>      </td>
      <td><label>
        <input name="segundo_rrhh" type="text" id="segundo_rrhh" value="<?=$bus["segundo_rrhh"]?>" size="45">
      </label></td>
      <td width="42" class="viewPropTitle"><div align="right">C.I.: </div></td>
      <td width="109"><label>
        <input name="ci_segundo_rrhh" type="text" id="ci_segundo_rrhh" value="<?=$bus["ci_segundo_rrhh"]?>" size="16">
      </label></td>
      <td width="57" class="viewPropTitle"><div align="right">Cargo: </div></td>
      <td width="388"><label>
        <input name="cargo_segundo_rrhh" type="text" id="cargo_segundo_rrhh" value="<?=$bus["cargo_segundo_rrhh"]?>" size="60">
      </label></td>
    </tr>
     <tr>
      <td class="viewPropTitle"><p align="right">Nombre y Apellidos Tercer nivel:</p>      </td>
      <td><label>
        <input name="tercero_rrhh" type="text" id="tercero_rrhh" value="<?=$bus["tercero_rrhh"]?>" size="45">
      </label></td>
      <td width="42" class="viewPropTitle"><div align="right">C.I.: </div></td>
      <td width="109"><label>
        <input name="ci_tercero_rrhh" type="text" id="ci_tercero_rrhh" value="<?=$bus["ci_tercero_rrhh"]?>" size="16">
      </label></td>
       <td width="57" class="viewPropTitle"><div align="right">Cargo: </div></td>
      <td width="388"><label>
        <input name="cargo_tercero_rrhh" type="text" id="cargo_tercero_rrhh" value="<?=$bus["cargo_tercero_rrhh"]?>" size="60">
      </label></td>
    </tr>
     <tr>
      <td width="316">&nbsp;</td>
      <td width="270">&nbsp;</td>
    </tr>
     <tr>
       <td class="viewPropTitle">N&uacute;mero de Documento de Remisi&oacute;n:</td>
       <td><input style="text-align:right" name="nro_remision" type="text" id="nro_remision" value="<?=$bus["nro_remision"]?>" size="10"></td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
     </tr>

    <tr>
      <td colspan="6">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="6" style="">
        <h4 align="left">I.V.S.S.</h4>
        <h2 class="sqlmVersion"></h2>
      </td>
    </tr>
    </tr>
     <tr>
       <td class="viewPropTitle" align="right">N&uacute;mero de Inscripci&oacute;n Patronal:</td>
       <td><input style="text-align:right" size="20" name="numero_patronal_ivss" type="text" id="numero_patronal_ivss" value="<?=$bus["numero_patronal_ivss"]?>"></td>
       <td colspan="3" class="viewPropTitle" align="right">Fecha de Inscripci&oacute;n Patronal:</td>
       <td><input style="text-align:right" size="12" name="fecha_inscripcion_patronal_ivss" type="text" id="fecha_inscripcion_patronal_ivss" value="<?=$bus["fecha_inscripcion_patronal_ivss"]?>">
          <img src="imagenes/jscalendar0.gif" name="f_trigger_f" id="f_trigger_f" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
                      <script type="text/javascript">
              Calendar.setup({
              inputField    : "fecha_inscripcion_patronal_ivss",
              button        : "f_trigger_f",
              align         : "Tr",
              ifFormat      : "%Y-%m-%d"
              });
            </script>
       </td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td class="viewPropTitle" align="right">R&eacute;gimen:</td>
       <td>
       <select name="regimen_ivss" id="regimen_ivss">
          <option <? if($bus["regimen_ivss"] == 'Parcial'){echo "selected";}?> value="Parcial">Parcial</option>
          <option <? if($bus["regimen_ivss"] == 'General'){echo "selected";}?> value="General">General</option>
       </select>
       </td>
       <td colspan='3' class="viewPropTitle" align="right">Riesgo:</td>
       <td>
        <select name="riesgo_ivss" id="riesgo_ivss">
          <option <? if($bus["riesgo_ivss"] == 'Minimo'){echo "selected";}?> value="Minimo">M&iacute;nimo</option>
          <option <? if($bus["riesgo_ivss"] == 'Medio'){echo "selected";}?> value="Medio">Medio</option>
          <option <? if($bus["riesgo_ivss"] == 'Maximo'){echo "selected";}?> value="Maximo">M&aacute;ximo</option>
       </select>
       </td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
     </tr>
     <tr>
      <td class="viewPropTitle"><p align="right">Nombre y Apellidos Representante:</p>      </td>
      <td><label>
        <input name="nombre_apellido_representante_ivss" type="text" id="nombre_apellido_representante_ivss" value="<?=$bus["nombre_apellido_representante_ivss"]?>" size="45">
      </label></td>
      <td width="42" class="viewPropTitle"><div align="right">C.I.: </div></td>
      <td width="109"><label>
        <input name="cedula_representante_ivss" type="text" id="cedula_representante_ivss" value="<?=$bus["cedula_representante_ivss"]?>" size="16">
      </label></td>
        <td>&nbsp;</td>
       <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="6">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="6" style="">
        <h2 class="sqlmVersion"></h2>
      </td>
    </tr>
    <tr>
      <td colspan="6">&nbsp;</td>
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
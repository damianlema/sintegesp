<SCRIPT language=JavaScript> 
document.oncontextmenu=new Function("return false")
<!--- oculta el script para navegadores antiguos
var miPopUp
var w=0

function abreVentana(){
	miPopup=window.open("lib/listas/lista_trabajador.php?frm=historico_permisos","historico_permisos","width=600,height=400,scrollbars=yes")
	miPopup.focus()
}

function habilitaDeshabilita(form){
    
	if (form.desc_bono.checked == true){
	   	form.motivo.disabled = false;
    }
	
    if (form.desc_bono.checked == false){
	    form.motivo.disabled = true;
    }
	
}

function llenarGrilla(id){
	//alert(id);
	buscarPermisos(id);
}

</SCRIPT>
<?
extract($_POST);
?>
<script src="modulos/rrhh/js/historico_permiso_ajax.js"></script>
<?
$boton_modificar_td = "<input type='submit' name='ingresar' id='ingresar' value='Ingresar' class='button'/>";
if($modificar == "Guardar"){
	//echo "Guardar";
	//echo $justificado;
	$str_actualizar = "update historico_permisos set 
						fecha_solicitud='".$fecha_solicitud."',
						fecha_inicio ='".$fecha_inicio."',
						hora_inicio = '".$hr_inicio."',
						fecha_culminacion = '".$fecha_culminacion."',
						hora_culminacion = '".$hr_culminacion."',
						tiempo_total = '".$tiempo_horas."',
						descuenta_bono_alimentacion = '".$desc_bono."',
						motivo = '".$motivo."',
						justificado = '".$justificado1."',
						remunerado = '".$remunerado1."',
						aprobado_por = '".$aprobado_por."',
						ci_aprobado = '".$ci_aprobado."',
						usuario = '".$login."',
						fechayhora = '".$fh."'
						where idhistorico_permisos = '".$idhistorico."'";
	$result_actualizar = mysql_query($str_actualizar,$conexion_db);
		
	if(!$result_actualizar){
			$error = mysql_error();
			echo $error;
			?>
			<SCRIPT language=JavaScript>
				window.alert("Fallo el registro");
			</SCRIPT>
		<? }else{?>
			<SCRIPT language=JavaScript>
				alert("Se actualizó registro");
				//document.getElementById('form2').reset;
			</SCRIPT>
		<? 
			$bandera = 1;
			}
}
if($accion_form=="modificar"){
	//echo "modificar";
	//echo $idtrabajador;
	//buffer de busquedas para mostrar valores del encabezado
	$str_sql_buffer = "select * from trabajador where idtrabajador = ".$idtrabajador.";";
	$result_sql_buffer = mysql_query($str_sql_buffer,$conexion_db);
	$array_sql_buffer = mysql_fetch_array($result_sql_buffer);
	
	$boton_modificar_td = "<input type='submit' name='modificar' id='modificar' value='Guardar' class='button'/>";
	
	//buffer de busquedas para mostrar valores de la grilla
	$str_sql_buffer_permisos = "select * from historico_permisos where idhistorico_permisos = ".$idhistorico.";";
	$result_sql_buffer_permisos = mysql_query($str_sql_buffer_permisos,$conexion_db);
	$array_sql_buffer_permisos = mysql_fetch_array($result_sql_buffer_permisos);
	
	//valores que se muestran al modificar un permiso
	$fecha_solicitud = $array_sql_buffer_permisos["fecha_solicitud"];
	$fecha_inicio = $array_sql_buffer_permisos["fecha_inicio"];
	$hr_inicio = $array_sql_buffer_permisos["hora_inicio"];
	$hr_culminacion = $array_sql_buffer_permisos["hora_culminacion"];
	$fecha_culminacion = $array_sql_buffer_permisos["fecha_culminacion"];
	$tiempo_horas = $array_sql_buffer_permisos["tiempo_total"];
	$aprobado_por = $array_sql_buffer_permisos["aprobado_por"];
	$ci_aprobado = $array_sql_buffer_permisos["ci_aprobado"];
	$motivo = $array_sql_buffer_permisos["motivo"];
	$desc_bono = $array_sql_buffer_permisos["descuenta_bono_alimentacion"];
	$justificado1 = $array_sql_buffer_permisos["justificado"];
	$remunerado1 = $array_sql_buffer_permisos["remunerado"];
	//echo $remunerado1;
	//echo $array_sql_buffer_permisos["justificado"];
	?>
	<script language="javascript">
		buscarPermisos(<?=$idtrabajador?>);
	</script>
	<?
}
if($bandera_url == 1){
	//buffer de busquedas para mantener valores anteriores en el form_encabezado
	$str_sql_buffer = "select * from trabajador where idtrabajador = ".$idtrabajador.";";
	$result_sql_buffer = mysql_query($str_sql_buffer,$conexion_db);
	$array_sql_buffer = mysql_fetch_array($result_sql_buffer);
	?>
	<script language="javascript">
		buscarPermisos(<?=$idtrabajador?>);
	</script>
	<?
}

if($ingresar == "Ingresar"){
	if($idtrabajador == ""){?>
		<SCRIPT language=JavaScript>
				window.alert("Por favor seleccione un trabajador");
		</SCRIPT>
	<? }else{
		//validamos los campos que no esten vacios
		if($fecha_solicitud == "" || $fecha_inicio == "" || $hr_inicio == "" || $fecha_culminacion=="" || $hr_culminacion=="" || $tiempo_horas == "" || $aprobado_por =="" || $ci_aprobado == ""){
			?><SCRIPT language=JavaScript>
				window.alert("Campos vacios");
			</SCRIPT>
			<script language="javascript">
                buscarPermisos(<?=$idtrabajador?>);
            </script>
			<?
		//buffer de busquedas para mantener valores anteriores en el form_encabezado
		$str_sql_buffer = "select * from trabajador where idtrabajador = ".$idtrabajador.";";
		$result_sql_buffer = mysql_query($str_sql_buffer,$conexion_db);
		$array_sql_buffer = mysql_fetch_array($result_sql_buffer);
		}else{//fin de validacion de los campos
		//echo "Ingresar";
		
		$sql_ingresar = "insert into historico_permisos(
						idtrabajador,
						fecha_inicio,
						hora_inicio,
						fecha_culminacion,
						hora_culminacion,
						fecha_solicitud,
						tiempo_total,
						descuenta_bono_alimentacion,
						motivo,
						justificado,
						remunerado,
						aprobado_por,
						ci_aprobado,
						usuario,
						fechayhora) values (
						'".$idtrabajador."',
						'".$fecha_inicio."',
						'".$hr_inicio."',
						'".$fecha_culminacion."',
						'".$hr_culminacion."',
						'".$fecha_solicitud."',
						'".$tiempo_horas."',
						'".$desc_bono."',
						'".$motivo."',
						'".$justificado1."',
						'".$remunerado1."',
						'".$aprobado_por."',
						'".$ci_aprobado."',
						'".$login."',
						'".$fh."');";
						
		$result_ingresar = mysql_query($sql_ingresar,$conexion_db);
		
		if(!$result_ingresar){
			$error = mysql_error();
			echo $error;
			?>
        	<SCRIPT language=JavaScript>
				window.alert("Fallo el registro");
			</SCRIPT>
		<? }else{?>
			<SCRIPT language=JavaScript>
				window.alert("Registro exitoso");
			</SCRIPT>
		<? }
		//buffer de busquedas para mantener valores anteriores en el form_encabezado
		$str_sql_buffer = "select * from trabajador where idtrabajador = ".$idtrabajador.";";
		$result_sql_buffer = mysql_query($str_sql_buffer,$conexion_db);
		$array_sql_buffer = mysql_fetch_array($result_sql_buffer);
		//limpiamos los campos 1 a 1
		unset($fecha_solicitud);
		unset($fecha_inicio);
		unset($hr_inicio);
		unset($fecha_culminacion);
		unset($hr_culminacion);
		unset($tiempo_horas);
		unset($motivo);
		//unset($remunerado);
		unset($aprobado_por);
		unset($ci_aprobado);
	}//fin de la validacion de todos los campos y agregar trabajador
	}//fin de la validacion y de agregar al trabajador
}//	fin de ingresar

?>


<h4 align=center>Histórico Permisos</h4>

<form name="form_encabezado" id="form_encabezado">
<table width="943" border="0" align="center">
  <tr>
    <td width="72">&nbsp;</td>
    <td width="180">&nbsp;</td>
    <td width="87" class='viewPropTitle'><div align="right">Cédula:</div></td>
    <td width="242">
    <input type="text" name="cedula_trabajador" readonly="readonly" id="cedula_trabajador" size="30" value="<?=$array_sql_buffer['cedula']?>" />
        <button name="listado" type="button" style="background-color:white;border-style:none;cursor:pointer;" onclick="abreVentana()"><img src='imagenes/search0.png' title="Buscar Trabajador" /></button><a href="principal.php?modulo=1&accion=21"><img src='imagenes/nuevo.png' title="Nuevo Historico Permiso"></a></td>
    <td width="60">&nbsp;</td>
    <td width="276">&nbsp;</td>
  </tr>
  <tr>
    <td class='viewPropTitle'><div align="right">Nombres:</div></td>
    <td><input type="text" name="nombre_trabajador" id="nombre_trabajador" size="30" readonly="readonly" value="<?=$array_sql_buffer['nombres']?>"/></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td class='viewPropTitle'><div align="right">Apellidos:</div></td>
    <td><input type="text" size="30" name="apellido_trabajador" id="apellido_trabajador" readonly="readonly" value="<?=$array_sql_buffer['apellidos']?>"/></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<h2 class="sqlmVersion"></h2>
<br>
</form>

<form name="form2" id="form2" method="post">
  <table width="100%" border="0" align="center">
    <tr>
      <td width="128">
      <input type="hidden"  name="idtrabajador" id="idtrabajador" value="<?=$array_sql_buffer['idtrabajador']?>" onchange="buscarPermisos(document.getElementById('cedula_trabajador').value, document.getElementById('nombre_trabajador').value, document.getElementById('apellido_trabajador').value)"/>
      &nbsp;</td>
      <td width="180">&nbsp;</td>
      <td width="171">&nbsp;</td>
      <td width="88">&nbsp;</td>
      <td width="91">&nbsp;</td>
      <td width="92">&nbsp;</td>
      <td width="89">&nbsp;</td>
      <td width="108">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="7" align="left"><div align="center">
        <table width="335" border="0" align="left">
          <tr>
            <td width="138" class='viewPropTitle'><div align="right">Fecha De Solicitud:</div></td>
            <td width="187">
            <?
            list($fecha_solicitud, $hora) = split('[ ]', $fecha_solicitud);
			?>
            <input type="text" name="fecha_solicitud" id="fecha_solicitud" size="13" readonly="readonly" value="<?=$fecha_solicitud?>"/>
        <img src="imagenes/jscalendar0.gif" name="fecha_solicitud_cal" width="16" height="16" id="fecha_solicitud_cal" style="cursor: pointer;" title="Selector de Fecha" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" />
          <script type="text/javascript">
							Calendar.setup({
							inputField    : "fecha_solicitud",
							button        : "fecha_solicitud_cal",
							align         : "Tr",
							ifFormat      : "%Y-%m-%d"
							});
						</script></td>
          </tr>
      </table>        <div align="center"></div></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td class='viewPropTitle'><div align="right">Fecha De Inicio:</div></td>
      <td>
      <?
      list($fecha_inicio, $hora) = split('[ ]', $fecha_inicio);
	  ?>
      <input type="text" value="<?=$fecha_inicio?>" name="fecha_inicio" id="fecha_inicio" size="13" readonly="readonly" onchange="validarFechas(document.getElementById('fecha_inicio').value, document.getElementById('fecha_culminacion').value) , totalHoras(document.getElementById('tiempo').value, document.getElementById('hr_inicio').value, document.getElementById('hr_culminacion').value)"/>
          <img src="imagenes/jscalendar0.gif" name="fecha_inicio_cal" width="16" height="16" id="fecha_inicio_cal" style="cursor: pointer;" title="Selector de Fecha" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" />
          <script type="text/javascript">
							Calendar.setup({
							inputField    : "fecha_inicio",
							button        : "fecha_inicio_cal",
							align         : "Tr",
							ifFormat      : "%Y-%m-%d"
							});
						</script></td>
      <td class='viewPropTitle'><div align="right">Hora De Inicio:</div></td>
      <td><p>
        <input type="text" size="13" name="hr_inicio" id="hr_inicio" title="Ejemplo: 07:45 am" maxlength="8" value="<?=$hr_inicio?>" onkeyup="validarHoras(document.getElementById('hr_inicio').value)" onchange="totalHoras(document.getElementById('tiempo').value, document.getElementById('hr_inicio').value, document.getElementById('hr_culminacion').value)"/>
      </p>
      <p align="left"><font size="1"><b>Ejemplo: 07:45 am</b></font></p></td>
      <td class='viewPropTitle'><div align="right">Fecha De Culminación:</div></td>
      <td>
      <?
      list($fecha_culminacion, $hora) = split('[ ]', $fecha_culminacion);
	  ?>
      <input type="text" value="<?=$fecha_culminacion?>" name="fecha_culminacion" id="fecha_culminacion" size="13" readonly="readonly" onchange="validarFechas(document.getElementById('fecha_inicio').value, document.getElementById('fecha_culminacion').value) , totalHoras(document.getElementById('tiempo').value, document.getElementById('hr_inicio').value, document.getElementById('hr_culminacion').value)"/>
          <img src="imagenes/jscalendar0.gif" name="fecha_inicio_cal" width="16" height="16" id="fecha_culminacion_cal" style="cursor: pointer;" title="Selector de Fecha" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" />
          <script type="text/javascript">
							Calendar.setup({
							inputField    : "fecha_culminacion",
							button        : "fecha_culminacion_cal",
							align         : "Tr",
							ifFormat      : "%Y-%m-%d"
							});
						</script></td>
      <td class='viewPropTitle'><div align="right">Hora de Culminación:</div></td>
      <td><p>
        <input type="text" size="13" name="hr_culminacion" id="hr_culminacion" value="<?=$hr_culminacion?>" maxlength="8" title="Ejemplo: 07:45 am" onkeyup="validarHoras(document.getElementById('hr_culminacion').value)" onchange="totalHoras(document.getElementById('tiempo').value, document.getElementById('hr_inicio').value, document.getElementById('hr_culminacion').value)"/>
      </p>
        <p align="left"><font size="1"><b>Ejemplo: 07:45 am</b></font></p></td>
    </tr>
    <tr>
      <td class='viewPropTitle'><div align="right">Tiempo Total:</div></td>
      <td colspan="2"><input value="<?=$tiempo_horas?>" type="text" size="50" name="tiempo_horas" id="tiempo_horas" readonly="readonly"/>
      <input type="hidden" id="tiempo" size="50" onchange="totalHoras(document.getElementById('tiempo').value, document.getElementById('hr_inicio').value, document.getElementById('hr_culminacion').value)" /></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr><?php //<input type="checkbox" value="1" name="desc_bono" id="desc_bono" onchange="validarDescuento(document.getElementById('desc_bono').value)"/>?>
      <td class='viewPropTitle'><div align="right">Descuenta Bono Alimentario:</div></td>
      <td>
      <?
      	if($desc_bono==1){?>
			<input type="checkbox" name="desc_bono" id="desc_bono" checked="checked" value="1" onClick="habilitaDeshabilita(this.form)">
	  <? }else{?>
	  		<input type="checkbox" name="desc_bono" id="desc_bono" value="1" onClick="habilitaDeshabilita(this.form)" />
	  <? }
	  ?>      </td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td class='viewPropTitle'><div align="right">Motivo:</div></td>
      <td colspan="2">
      <?
      	if($desc_bono==1){?>
			<textarea rows="5" cols="40" name="motivo" id="motivo"><?=$motivo?></textarea>
	 <? }else{?>
	 		<textarea rows="5" cols="40" name="motivo" id="motivo" disabled="disabled"><?=$motivo?></textarea>
	 <? }
	  ?>      </td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td class='viewPropTitle'><div align="right">Justificado:</div></td>
      <td>
      <?
      	if($justificado1 == 1){?>
			<input type="checkbox" name="justificado1" id="justificado1" checked="checked"  value="1"/>
	 <? }else{?>
	 		<input type="checkbox" value="1" name="justificado1" id="justificado1" />
	 <? }?>      </td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td class='viewPropTitle'><div align="right">Remunerado:</div></td>
      <td>
	  <?
      	if($remunerado1 == 1 ){?>
			<input type="checkbox" checked="checked" value="1" name="remunerado1" id="remunerado1" />
	 <? }else{?>
	 		<input type="checkbox" value="1" name="remunerado1" id="remunerado1" />
	 <? } ?>      </td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td class='viewPropTitle'><div align="right">Aprobado Por:</div></td>
      <td><input type="text" size="30"  maxlength="30" name="aprobado_por" id="aprobado_por" value="<?=$aprobado_por?>"/></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td class='viewPropTitle'><div align="right">C.I.:</div></td>
      <td><input type="text" size="30"  maxlength="30" name="ci_aprobado" id="ci_aprobado" value="<?=$ci_aprobado?>"/></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="6"><div align="center">
        <?=$boton_modificar_td?>
      </div></td>
      <td>&nbsp;</td>
    </tr>
  </table>
</form>
<div id="grilla" align="center"></div>
<?
if($bandera == 1){
	//echo "aqui";
	//echo $idtrabajador;
	?>
   <script>
	 nuevaUrl='principal.php?accion=21&modulo=1&idtrabajador=<?=$idtrabajador?>&bandera_url=1'
	 nuevaWin='_self'
	 nuevoTime=400 //Tiempo en milisegundos
	 setTimeout("open(nuevaUrl,nuevaWin)",nuevoTime);
	</script>
	<!--header('refresh:2; url=http://www.tutores.org');-->
    <?
}
?>
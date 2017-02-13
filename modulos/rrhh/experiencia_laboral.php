<?
$accion_form = $_GET['accion_form'];
$id_exp = $_GET['id_exp'];
$btn_style = "display:none";
//$btn_style = "style='display:none'";
$btn_style_ingresar = "";
extract($_POST);



if($_POST["boton_ingresar"]){
	extract($_POST);
	
	if($idtrabajador == ""){
	?>
		<SCRIPT language=JavaScript>
		  window.alert("Por favor seleccione un empleado");
		</SCRIPT>
    <?php 
	}
    else{
    	if($tiempo_srv == 'La fecha de inicio ha de ser anterior a la fecha Actual' || $tiempo_srv == ''){
			?>
				<SCRIPT language=JavaScript>
		 		 window.alert("Tiempo de servicio inválido");
				</SCRIPT>
    		<?php 
		}
		else{
		$sql_ingresar = mysql_query("insert into experiencia_laboral(idtrabajador,													
																empresa,
																desde,
																hasta,
																tiempo_servicio,
																motivo_salida,
																ultimo_cargo,
																direccion_empresa,
																telefono_empresa,
																observaciones,
																usuario,
																status,
																fechayhora)
																	VALUES(
																'".$idtrabajador."',
																'".$empresa."',
																'".$desde_exp."',
																'".$hasta_exp."',
																'".$tiempo_srv."',
																'".$motivo."',
																'".$ultimo_cargo."',
																'".$direccion_empresa."',
																'".$telefono."',
																'".$observaciones."',
																'".$login."',
																'a',
																'".$fh."')")or die(mysql_error());
	}
	}
}
if($_POST["boton_eliminar"]){
	extract($_POST);
	$str_borrar = mysql_query ("delete from experiencia_laboral where idexperiencia_laboral = '$id_exp';")or die(mysql_error());
}
if($accion_form=='modificar'){
	$str_sql_accion = "SELECT * FROM experiencia_laboral where idexperiencia_laboral='".$id_exp."';";
	$result = mysql_query ($str_sql_accion,$conexion_db);
	$row = mysql_fetch_array ($result);
	$btn_style = "";
	$btn_style_ingresar = "style='display:none'";
	$accion_form = "";
}

if($_POST["boton_modificar"]){
		extract($_POST);
		if($tiempo_srv == 'La fecha de inicio ha de ser anterior a la fecha Actual' || $tiempo_srv == ''){
			?>
				<SCRIPT language=JavaScript>
		 		 window.alert("Tiempo de servicio inválido");
				</SCRIPT>
    		<?php 
			$str_sql_accion = "SELECT * FROM experiencia_laboral where idexperiencia_laboral='".$id_exp."';";
			$result = mysql_query ($str_sql_accion,$conexion_db);
			$row = mysql_fetch_array ($result);
			$btn_style = "";
			$btn_style_ingresar = "style='display:none'";
			$accion_form = "";
		}else{
		$str_modificar = mysql_query("  UPDATE experiencia_laboral set 
										empresa='".$empresa."', 
										desde='".$desde_exp."', 
										hasta='".$hasta_exp."', 
										tiempo_servicio='".$tiempo_srv."',
										motivo_salida='".$motivo."',
										ultimo_cargo='".$ultimo_cargo."',
										direccion_empresa='".$direccion_empresa."', 
										telefono_empresa='".$telefono."', 
										observaciones='".$observaciones."', 
										usuario='".$login."', 
										fechayhora='".$fh."'
										where idexperiencia_laboral='".$id_exp."'; ")or die(mysql_error());
		
		if(!$str_modificar){
			?>
				<SCRIPT language=JavaScript>
		 		 window.alert("Fallo modificación");
				</SCRIPT>
	   		 <?php 
		}else{
			?>
				<SCRIPT language=JavaScript>
		  			window.alert("Modificación exitosa");
                </SCRIPT>
                <?php $empresa = "";?>
	   		 <?php 
		}
	}
}


?>


<SCRIPT language=JavaScript> 
document.oncontextmenu=new Function("return false")
<!--- oculta el script para navegadores antiguos
var miPopUp
var w=0

function abreVentana(){
	miPopup=window.open("lib/listas/lista_trabajador.php?frm=experiencia_laboral","experiencia_laboral","width=600,height=400,scrollbars=yes")
	miPopup.focus()
}


function buscarExperiencias(){
	document.getElementById("formulario_principal").submit();
}

function confirmarEliminar(ruta){
	if(confirm("¿Esta seguro que desea eliminar este registro?")){
		window.location.href= ""+ruta+"";
	}
}
</SCRIPT>
<script src="modulos/rrhh/js/experiencia_laboral_ajax.js"></script>
<h4 align=center>Experiencia Laboral</h4>
<form id="formulario_principal" method="post" action="">
<table width="80%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
        <td>&nbsp;</td>
        <td align='right' class='viewPropTitle'>C&eacute;dula: </td>
        <td><input type="hidden" name="idtrabajador" id="idtrabajador" value="<?=$_REQUEST["idtrabajador"]?>">
          <input name="cedula_trabajador" type="text" readonly id="cedula_trabajador" size="30" value="<?=$_REQUEST["cedula_trabajador"]?>"/>
          <button name="listado" type="button" style="background-color:white;border-style:none;cursor:pointer;" onClick="abreVentana()"><img src='imagenes/search0.png' title="Buscar Trabajador"> </button><a href="principal.php?modulo=1&accion=19"><img src='imagenes/nuevo.png' title="Nueva Experiencia Laboral"></a></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td align='right' class='viewPropTitle'>Nombre:</td>
        <td><input name="nombre_trabajador" type="text" readonly id="nombre_trabajador" size="30" value="<?=$_REQUEST["nombre_trabajador"]?>"/></td>
        <td align='right' class='viewPropTitle'>Apellido:&nbsp;</td>
        <td><input name="apellido_trabajador" type="text" readonly id="apellido_trabajador" size="30" value="<?=$_REQUEST["apellido_trabajador"]?>"/></td>
      </tr>
    </table>
</form>

<br>
<h2 class="sqlmVersion"></h2>
<br>
<form method="post" name="frm_p" id="frm_p" action="principal.php?accion=19&modulo=1&id_exp=<?=$_REQUEST["id_exp"]?>">

<input name="cedula_trabajador" type="hidden" readonly id="cedula_trabajador" size="30" value="<?=$_REQUEST["cedula_trabajador"]?>"/>
<input name="nombre_trabajador" type="hidden" readonly id="nombre_trabajador" size="30" value="<?=$_REQUEST["nombre_trabajador"]?>"/>
<input name="apellido_trabajador" type="hidden" readonly id="apellido_trabajador" size="30" value="<?=$_REQUEST["apellido_trabajador"]?>"/>

<table width="51%" border="0" align="center">
  <input type="hidden" name="idtrabajador" id="idtrabajador" value="<?=$_REQUEST["idtrabajador"]?>">
  <tr>
    <td width="147" align='right' class='viewPropTitle'>Empresa:</td>
    <td colspan="3" ><input type="text" size="70" name="empresa" id="empresa" value="<?php echo $row['empresa']; ?>"/></td>
    </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Desde:</td>
    <td width="120">
    	<?
		list($desde, $hora) = split('[ ]', $row['desde']);
		list($hasta, $hora) = split('[ ]', $row['hasta']);
		?>
    	<input type="text" name="desde_exp" id="desde_exp" size="13" readonly="readonly" value="<?php echo $desde; ?>"/>
    	<img src="imagenes/jscalendar0.gif" name="desde_cal" width="16" height="16" id="desde_cal" style="cursor: pointer;" title="Selector de Fecha" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" />
        <script type="text/javascript">
							Calendar.setup({
							inputField    : "desde_exp",
							button        : "desde_cal",
							align         : "Tr",
							ifFormat      : "%Y-%m-%d"
							});
						</script>
    <td width="45" align='right' class='viewPropTitle'>Hasta:</td>
    <td width="248"><input type="text" name="hasta_exp" id="hasta_exp" size="13" readonly="readonly" value="<?php echo $hasta; ?>" onchange="validarFechas(document.getElementById('desde_exp').value, document.getElementById('hasta_exp').value)"/>
        <img src="imagenes/jscalendar0.gif" name="hasta_cal" width="16" height="16" id="hasta_cal" style="cursor: pointer;" title="Selector de Fecha" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" />
        <script type="text/javascript">
							Calendar.setup({
							inputField    : "hasta_exp",
							button        : "hasta_cal",
							align         : "Tr",
							ifFormat      : "%Y-%m-%d"
							});
						</script>    </td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Tiempo de servicio:</td>
    <td colspan="3"><input type="text" size="70" value="<?php echo $row['tiempo_servicio'];?>" id="tiempo_srv" name="tiempo_srv"/></td>
    </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Motivo de salida:</td>
    <td colspan="3"><input type="text" size="70" name="motivo" id="motivo" value="<?php echo $row['motivo_salida']; ?>"/></td>
    </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Último Cargo:</td>
    <td colspan="3"> <input type="text" size="70" name="ultimo_cargo" id="ultimo_cargo" value="<?php echo $row['ultimo_cargo']; ?>" /> </td>
    </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Dirección de la empresa:</td>
    <td colspan="3"><textarea cols="67" rows="5" name="direccion_empresa" id="direccion_empresa" ><?php echo $row['direccion_empresa']; ?></textarea></td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Telefono de la empresa:</td>
    <td><input type="text" size="15" name="telefono" id="telefono" value="<?php echo $row['telefono_empresa']; ?>" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Observaciones:</td>
    <td colspan="3"><textarea cols="67" rows="5" name="observaciones" id="observaciones" ><?php echo $row['observaciones']; ?></textarea></td>
  </tr>
  <tr>
    <td colspan="4" align="center">
    	<input type="submit" value="Ingresar" class="button" id="boton_ingresar" name="boton_ingresar" <?php echo $btn_style_ingresar; ?>/>
        <input type="submit" value="Modificar" class="button" name="boton_modificar" id="boton_modificar" style="<?php echo $btn_style; ?>"/>
        <input type="submit" value="Eliminar" class="button" name="boton_eliminar" id="boton_eliminar" <?php echo $btn_style; ?>/></td>
  </tr>
</table>
</form>


<?


if($_REQUEST["idtrabajador"]){
$sql_consulta = mysql_query("select * from experiencia_laboral where idtrabajador = '".$_REQUEST["idtrabajador"]."'");
?>
<br>
<h2 class="sqlmVersion"></h2>
<br>
<div align="center">

    <table class="Browse" cellpadding="0" cellspacing="0" width="80%" align=center>
        <thead>
            <tr>
                <td align="center" class="Browse">Nombre de la Empresa</td>
                <td align="center" class="Browse">&Uacute;ltimo Cargo</td>
                <td align="center" class="Browse" colspan="2">Acci&oacute;n</td>
            </tr>
        </thead>
            <?
            while($bus_consulta= mysql_fetch_array($sql_consulta)){
			?>
			<tr bordercolor="#000000" bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                <td align="left" class="Browse"><?=$bus_consulta["empresa"]?></td>
                <td align="left" class="Browse"><?=$bus_consulta["ultimo_cargo"]?></td>
                <td align="center" class="Browse">
                <a href="principal.php?accion=19&modulo=1&idtrabajador=<?php echo $_REQUEST["idtrabajador"]; ?>&nombre_trabajador=<?=$_REQUEST["nombre_trabajador"]?>&cedula_trabajador=<?=$_REQUEST[	"cedula_trabajador"]?>&accion_form=modificar&apellido_trabajador=<?=$_REQUEST["apellido_trabajador"]?>&id_exp=<?php echo $bus_consulta['idexperiencia_laboral']; ?>"><img src="imagenes/modificar.png" style="cursor:pointer" alt='Modificar' title='Modificar'>
                </a>
              </td>
      </tr>
			<?
			}
			?>
        
    </table>

		</div>
        
<p>
  <?
}
?>
</p>


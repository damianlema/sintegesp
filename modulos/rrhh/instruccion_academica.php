<?php
if($_POST["ingresar"]){
$_GET["accion"] = 867;
}


$buscar_registros=$_REQUEST["busca"];
$idtrabajador_seleccionado=$_REQUEST["id"]; //variable pasada para los datos del trabajador hasta que se escriba otra cedula
$idtrabajador_seleccion2=$_REQUEST["t"]; //variable pasada por la seleccion en la grilla para modificar o eliminar un familiar
$entro_buscar=false;
$existen_registros=1; // switch para validar si hay datos a cargar en la grilla 0 existen 1 no existen



if ($idtrabajador_seleccionado<>"" || $idtrabajador_seleccion2<>""){
	
	if($idtrabajador_seleccionado<>""){$idtrabajador_buscar=$idtrabajador_seleccionado;} else {$idtrabajador_buscar=$idtrabajador_seleccion2;}
		$sql_validar_id=mysql_query("select * from trabajador 
														where idtrabajador=".$idtrabajador_buscar
															,$conexion_db);
		$regtrabajador=mysql_fetch_assoc($sql_validar_id);
		$c=$regtrabajador["cedula"];
		$id_trabajador=$regtrabajador["idtrabajador"];
		$registros_grilla_carga=mysql_query("select nivel_estudio.denominacion as denominacion_nivel, 
											profesion.denominacion as denominacion_profesion, 
											mension.denominacion as denominacion_mension,
											instruccion_academica.idtrabajador,
											instruccion_academica.institucion,
											instruccion_academica.anio_egreso,
											instruccion_academica.observaciones,
											instruccion_academica.flag_constancia,
											instruccion_academica.flag_actual,
											instruccion_academica.idinstruccion_academica,
											instruccion_academica.foto
										from nivel_estudio,
											profesion,
											mension,
											instruccion_academica
										where 
											instruccion_academica.status='a'
											and nivel_estudio.idnivel_estudio = instruccion_academica.idnivel_estudio
											and profesion.idprofesion = instruccion_academica.idprofesion
											and mension.idmension = instruccion_academica.idmension
											and idtrabajador=".$id_trabajador,$conexion_db)or die(mysql_error());
	if (mysql_num_rows($registros_grilla_carga)>0){
		$existen_registros=0;
	}

}

$profesion=mysql_query("select * from profesion 
									where status='a'"
										,$conexion_db); // registros para llenar el combo profesion

$mension=mysql_query("select * from mension 
									where status='a'"
										,$conexion_db); // registros para llenar el combo mension

$nivel_estudio=mysql_query("select * from nivel_estudio 
									where status='a'"
										,$conexion_db); // registros para llenar el combo nivel_estudio
										
//if (isset($_POST["validar_cedula"])){
	if ($_REQUEST["cedula_buscar"]<>"")
	{
		$cedula_validar=$_REQUEST["cedula_buscar"];
		$sql_validar_cedula=mysql_query("select * from trabajador 
														where cedula=".$cedula_validar." and status='a'"
															,$conexion_db);
		if (mysql_num_rows($sql_validar_cedula)>0)
			{
				//header("location:error_rrhh.php?err=9&modo=0&busca=0");
				$regtrabajador=mysql_fetch_assoc($sql_validar_cedula);
				$c=$regtrabajador["cedula"];
				$id_trabajador=$regtrabajador["idtrabajador"];
				$registros_grilla_carga=mysql_query("select nivel_estudio.denominacion as denominacion_nivel, 
											profesion.denominacion as denominacion_profesion, 
											mension.denominacion as denominacion_mension,
											instruccion_academica.idtrabajador,
											instruccion_academica.institucion,
											instruccion_academica.anio_egreso,
											instruccion_academica.observaciones,
											instruccion_academica.flag_constancia,
											instruccion_academica.flag_actual,
											instruccion_academica.idinstruccion_academica,
											instruccion_academica.foto
										from nivel_estudio,
											profesion,
											mension,
											instruccion_academica
										where 
											instruccion_academica.status='a'
											and nivel_estudio.idnivel_estudio = instruccion_academica.idnivel_estudio
											and profesion.idprofesion = instruccion_academica.idprofesion
											and mension.idmension = instruccion_academica.idmension
											and idtrabajador=".$id_trabajador,$conexion_db)or die(mysql_error());
				if (mysql_num_rows($registros_grilla_carga)>0){
					$existen_registros=0;
				}
			}
	}
if ($_GET["accion"] == 868 || $_GET["accion"] == 869){
	$result=mysql_query("select * from instruccion_academica where idinstruccion_academica = '".$_GET['c']."'",$conexion_db);
	$row=mysql_fetch_assoc($result);
	$idinstruccion_academica=$_GET['c'];
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
	<title>Gesti&oacute;n P&uacute;blica</title>
	<!-- <META HTTP-EQUIV="Refresh" CONTENT="600; URL=../../lib/principal/cerrar.php"> -->
	<link href="../../css/theme/green/main.css" rel="stylesheet" type="text/css">
	<script src="../../js/function.js" type="text/javascript" language="javascript"></script>
	<script src="js/valida.js" type="text/javascript" language="javascript"></script>
	<script type="text/javascript" src="js/funciones.js"></script>
	<script type="text/javascript" src="../js/calendar/calendar.js"></script>
	<script type="text/javascript" src="../js/calendar/calendar-setup.js"></script>
	<script type="text/javascript" src="../js/calendar/lang/calendar-es.js"></script>
	<style type="text/css"> @import url("../theme/calendar-win2k-cold-1.css"); </style>
	<script type="text/javascript" src="../js/actualiza_select.js"></script>
	<link rel="stylesheet" type="text/css" href="index.css">
	<script type="text/javascript" src="index.js"></script>
	
<SCRIPT language=JavaScript>
document.oncontextmenu=new Function("return false")
<!--- oculta el script para navegadores antiguos
var miPopUp
var w=0

function abreVentana(){
	miPopup=window.open("lib/listas/lista_trabajador.php?frm=carga_familiar","carga_familiar","width=600,height=400,scrollbars=yes")
	miPopup.focus()
}

function solonumeros(e){
var key;
if(window.event)
	{key=e.keyCode;}
else if(e.which)
	{key=e.which;}
if (key < 48 || key > 57)
	{return false;}
return true;
}
// end hiding from old browsers -->
</SCRIPT>
</head>
	<body>
	<br>
	<h4 align=center>Instrucci&oacute;n Academica</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
	<?php //echo $idinstruccion_academica; ?>
	<form action='principal.php?accion=<?=$_GET["accion"]?>&modulo=1' method='POST' enctype="multipart/form-data" name='instruccion_academica'>
    <input type="hidden" name="idinstruccion_academica" id="idinstruccion_academica" value="<?=$row["idinstruccion_academica"]?>">
        <input type="hidden" name="id_trabajador" id="id_trabajador" value="<?=$id_trabajador?>">
        <table align=center cellpadding=2 cellspacing=0>
			<tr><td align='right' class='viewPropTitle'>C&eacute;dula:</td>
				<td class=''><input type="text" id="cedula_buscar" name="cedula_buscar" maxlength="12" size="12" onKeyPress="javascript:return solonumeros(event)" <?php if (isset($_REQUEST["cedula_buscar"])){ echo "value=".$_REQUEST["cedula_buscar"];} else { echo "value=".$regtrabajador["cedula"];}?>>
					<button name="validar_cedula" type="button" style="background-color:white;border-style:none;cursor:pointer;" onClick="this.form.submit()"><img src='imagenes/validar.png' >
					</button>
					<button name="listado" type="button" style="background-color:white;border-style:none;cursor:pointer;" onClick="abreVentana()"><img src='imagenes/search0.png'>
					</button>
				</td>
			</tr>
		</table>
	<div id="resultado">
		<br>
		<table align=center cellpadding=2 cellspacing=0 width="80%">
			<tr>
				<td align='right' class='viewPropTitle'>Apellidos:</td>
				<td class=''><input type="text" name="apellidos" maxlength="45" size="45" id="apellidos" disabled <?php echo "value=".$regtrabajador["apellidos"];?>></td>
				<td align='right' class='viewPropTitle'>Nombres:</td>
				<td class=''><input type="text" name="nombres" maxlength="45" size="45" id="nombres" disabled <?php echo "value=".$regtrabajador["nombres"];?>></td>
			</tr>
		</table>
	</div>
	<br>
	<h2 class="sqlmVersion"></h2>
	<br>
	<table align=center cellpadding=2 cellspacing=0 width="80%">
			<tr>
			<td width="15%" align='right' class='viewPropTitle'>Nivel de Estudio:</td>
			  <td width="34%" class='viewProp'><table width="100%" border="0" cellspacing="0" cellpadding="4">
                      <tr>
                        <td width="15%"><select name="idnivel_estudio" id="idnivel_estudio">
                          <option value=0>&nbsp;</option>
                          <?php
						while($regnivel_estudio = mysql_fetch_array($nivel_estudio))
							{ ?>
                          <option <?php echo "value=".$regnivel_estudio["idnivel_estudio"]; if ($_GET["accion"]==868 || $_GET["accion"]==869) {if ($regnivel_estudio['idnivel_estudio']==$row['idnivel_estudio']) {echo " selected";}} ?> > <?php echo $regnivel_estudio["denominacion"];?> </option>
                          <?
				 } 
				 ?>
                        </select></td>
                        <td width="85%" align="left"><img src='imagenes/add.png' onClick="window.open('principal.php?modulo=1&accion=13&pop=si','agregar estudios','resisable = no, scrollbars = yes, width=900, height = 500')" style="cursor:pointer"></td>
                      </tr>
                    </table></td>
			<td width="11%" align='right' class='viewPropTitle'>Profesi&oacute;n:</td>
			  <td width="40%" class='viewProp'><table width="100%" border="0" cellspacing="0" cellpadding="4">
                      <tr>
                        <td width="13%"><select name="idprofesion" id="idprofesion">
                          <option value=0>&nbsp;</option>
                          <?php
						while($regprofesion = mysql_fetch_array($profesion))
							{ ?>
                          <option <?php echo "value=".$regprofesion["idprofesion"]; if ($_GET["accion"]==868 || $_GET["accion"]==869) {if ($regprofesion['idprofesion']==$row['idprofesion']) {echo " selected";}} ?> > <?php echo $regprofesion["abreviatura"]." ".$regprofesion["denominacion"];?> </option>
                          <?		
                  } 
				  ?>
                        </select></td>
                        <td width="87%"><img src='imagenes/add.png' onClick="window.open('principal.php?modulo=1&accion=14&pop=si','agregar estudios','resisable = no, scrollbars = yes, width=900, height = 500')"></td>
                </tr>
                    </table></td>
	  </tr>	

			<td align='right' class='viewPropTitle'>Mensi&oacute;n:</td>
				<td class='viewProp' colspan="3"><button name="agregar_mension" type="button" style="background-color:white;border-style:none;cursor:pointer;" onClick=""></button>
                <table width="100%" border="0" cellspacing="0" cellpadding="4">
                  <tr>
                    <td width="6%"><select name="idmension" id="idmension" >
                      <option value=0>&nbsp;</option>
                      <?php
						while($regmension = mysql_fetch_array($mension))
							{ ?>
                      <option <?php echo "value=".$regmension["idmension"]; if ($_GET["accion"]==868 || $_GET["accion"]==869) {if ($regmension['idmension']==$row['idmension']) {echo " selected";}} ?> > <?php echo $regmension["denominacion"];?> </option>
                      <?
					} 
					?>
                    </select></td>
                    <td width="94%"><img src='imagenes/add.png' onClick="window.open('principal.php?modulo=1&accion=15&pop=si','agregar estudios','resisable = no, scrollbars = yes, width=900, height = 500')"></td>
                  </tr>
                </table></td>
			</tr>
			<tr>
				<td align='right' class='viewPropTitle'>Instituci&oacute;n:</td>
				<td class='' colspan="3">
                <input type="text" name="institucion" maxlength="150" size="100" id="institucion" <?php if ($_GET["accion"]==868 || $_GET["accion"]==869) {echo 'value="'.$row['institucion'].'"';} if ($_GET["accion"]==869) echo "disabled";?>></td>
			</tr>
	</table>
			
<table align=center cellpadding=2 cellspacing=0 width="80%">		
			<tr>
				<td width="15%" align='right' class='viewPropTitle'>A&ntilde;o de egreso:</td>
			  <td width="8%" class=''><input type="text" name="anio_egreso" maxlength="4" size="5" id="anio_egreso" <?php if ($_GET["accion"]==868 || $_GET["accion"]==869) {echo 'value="'.$row['anio_egreso'].'"';} if ($_GET["accion"]==869) echo "disabled";?>></td>
			  <td width="10%" align='right' class='viewPropTitle'>Constancia:</td>
			  <td width="4%">
                <input type ="checkbox" name ="flag_constancia" value="si"
				<?php 
				if ($_GET["accion"]==868 || $_GET["accion"]==869){ 
					if($row["flag_constancia"] == 1){
						echo "checked";
					}
				} 
				if ($_GET["accion"]==869) echo "disabled";?>
                >
                </td>
			  <td width="15%" align='right' class='viewPropTitle'>Profesi&oacute;n Actual?:</td>
			  <td width="48%">
                <input type ="checkbox" name ="flag_actual" value="si" 
				<?php 
				if ($_GET["accion"]==868 || $_GET["accion"]==869) { 
					if($row["flag_actual"] == 1){
						echo "checked";
					}
				}
				if ($_GET["accion"]==869) echo "disabled";?>
                >
                </td>
	  </tr>
	</table>
	<table align=center cellpadding=2 cellspacing=0 width="80%">
			<tr>
			  <td align='right' class='viewPropTitle'>Observaciones:</td>
			  <td class=''><input type="text" id="observaciones" name="observaciones" maxlength="150" size="150" <?php if ($_GET["accion"]==868 || $_GET["accion"]==869) {echo 'value="'.$row['observaciones'].'"';} if ($_GET["accion"]==869) echo "disabled";?>></td>
	  </tr>
			<tr>
				<td width="134" align='right' class='viewPropTitle'>Foto:</td>
			  <td width="779" class=''><label>
			    <input type="file" name="foto" id="foto">
			  </label></td>
	  </tr>
	</table>
	<br><br>
	<table align=center cellpadding=2 cellspacing=0>
		<tr><td>
				<?
                if($_GET["accion"] != 868 and $_GET["accion"] != 869 and in_array(867, $privilegios) == true){
						echo "<input align=center class='button' name='ingresar' type='submit' value='Ingresar'>";
					}
				
					if($_GET["accion"] == 868 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
					}
				
					if($_GET["accion"] == 869 and in_array($_GET["accion"], $privilegios) == true){
						echo "<input align=center class='button' name='eliminar' type='submit' value='Eliminar'>";
					}
				?>
			<input type="reset" value="Reiniciar" class="button">
		</td></tr>
	</table>
	<?php
	if ($c<>""){ ?>
		<script> document.instruccion_academica.cedula_buscar.focus() </script>
	<?php }else{?>
		<script> document.instruccion_academica.cedula_buscar.focus() </script>
	<?php } ?>
	</form>
	<br><br>
	<h2 class="sqlmVersion"></h2>	
	<div align="center">
			<table class="Main" cellpadding="0" cellspacing="0" width="80%">
				<tr>
					<td>
						<form name="grilla" action="instruccion_academica.php" method="POST">
						<table class="Browse" cellpadding="0" cellspacing="0" width="80%" align=center>
							<thead>
								<tr>
									<!--<td class="Browse">&nbsp;</td>-->
									<td align="center" class="Browse">Imagen</td>
                                    <td align="center" class="Browse">Nivel</td>
									<td align="center" class="Browse">Profesi&oacute;n</td>
									<td align="center" class="Browse">Mensi&oacute;n</td>
									<td align="center" class="Browse" colspan="2">Acci&oacute;n</td>
								</tr>
							</thead>
							
							<?php
							if ($existen_registros==0){
								while($llenar_grilla= mysql_fetch_array($registros_grilla_carga)) 
								{ 
								?>
								<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
								<td align='center' class='Browse' width='20%'>
                                <a href="javascript:;" onClick="document.getElementById('imagen_<?=$llenar_grilla["idinstruccion_academica"]?>').style.display= 'block'">Ver Imagen</a>
                                <div style=" position:absolute; background-color:#CCCCCC; display:none; width:600px; height:400px; border:#666666 solid 1px;" id="imagen_<?=$llenar_grilla["idinstruccion_academica"]?>">
                                <div align="right" style="cursor:pointer"><strong onClick="document.getElementById('imagen_<?=$llenar_grilla["idinstruccion_academica"]?>').style.display= 'none'">Cerrar</strong></div>
                                <img src="modulos/rrhh/imagenes/estudios/<?=$llenar_grilla["foto"]?>">
                                </div>
                                </td>
								<?php
								echo "<td align='left' class='Browse' width='20%'>".$llenar_grilla["denominacion_nivel"]."</td>";
								
								echo "<td align='left' class='Browse'>".$llenar_grilla["denominacion_profesion"]."</td>";
								echo "<td align='left' class='Browse'>".$llenar_grilla["denominacion_mension"]."</td>";
								$c=$llenar_grilla["idinstruccion_academica"];
								$t=$llenar_grilla["idtrabajador"];
								echo "<td align='center' class='Browse' width='7%'><a href='principal.php?modulo=1&accion=868&c=$c&t=$t&busca=0' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
								echo "<td align='center' class='Browse' width='7%'><a href='principal.php?modulo=1&accion=869&&c=$c&t=$t&busca=0' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";	
							echo "</tr>";
							}
							}
							?>
						</table>
						</form>
					</td>
				</tr>
			</table>
		</div>
	<br>
</body>
</html>
<?php
if($_POST){
extract($_POST);
	$idinstruccion_academica=$_REQUEST["idinstruccion_academica"];
	$id_trabajador = $_POST["id_trabajador"];
	$cedula=$_REQUEST["cedula_buscar"];
	$idnivel_estudio=$_POST["idnivel_estudio"];
	$idprofesion=$_POST["idprofesion"];
	$idmension=$_POST["idmension"];
	$institucion=$_POST["institucion"];
	$anio_egreso=$_POST["anio_egreso"];
	if($_POST["flag_constancia"] =="si"){
		$flag_constancia=1;
	}else{
		$flag_constancia=0;
	}
	
	if($_POST["flag_actual"] =="si"){
		$flag_actual=1;
	}else{
		$flag_actual=0;
	}

	
if($_GET["accion"] == 867 and in_array(867, $privilegios) == true){

			
			if($_FILES["foto"]["name"] != ""){	
				$tipo = substr($_FILES['foto']['type'], 0, 5);
				$dir = 'modulos/rrhh/imagenes/estudios/';
				if ($tipo == 'image') {
				$nombre_imagen = $_FILES['foto']['name'];
				while(file_exists($dir.$nombre_imagen)){
					$partes_img = explode(".",$nombre_imagen);
					$nombre_imagen = $partes_img[0].rand(0,1000000).".".$partes_img[1];
				}
				if (copy($_FILES['foto']['tmp_name'], $dir.$nombre_imagen)){
				$pic = $dir.$nombre_imagen;	
					$result=mysql_query("insert into instruccion_academica 
															(idtrabajador,
															idnivel_estudio,
															idprofesion,
															idmension,
															institucion,
															anio_egreso,
															observaciones,
															flag_constancia,
															flag_actual,
															usuario,
															status,
															fechayhora,
															foto) 
													values 
															($id_trabajador,
															$idnivel_estudio,
															'$idprofesion',
															'$idmension',
															'$institucion',
															'$anio_egreso',
															'$observaciones',
															'$flag_contancia',
															'$flag_actual',
															'$login',
															'a',
															'$fh',
															'$nombre_imagen')",
													$conexion_db)or die("AQUI".mysql_error());
						registra_transaccion('a',$login,$fh,$pc,'instruccion_academica',$conexion_db);
						mensaje("El resgirto se Inserto con exito");
						redirecciona("principal.php?modulo=1&accion=18&cedula_buscar=".$_REQUEST["cedula_buscar"]."");
					}else{
						mensaje("Disculpe no se pudo guardar la imagen, por ello no se registraron los datos con exito, por favor intente de nuevo mas tarde");
						redirecciona("principal.php?modulo=1&accion=18&cedula_buscar=".$_REQUEST["cedula_buscar"]."");
					}
				
				}else{
					mensaje("Disculpe el archivo que intenta ingresar no parece ser un archivo de imagen valido, por favor verifique");
					redirecciona("principal.php?modulo=1&accion=18&cedula_buscar=".$_REQUEST["cedula_buscar"]."");
				}
				
			}else{
				$result=mysql_query("insert into instruccion_academica 
															(idtrabajador,
															idnivel_estudio,
															idprofesion,
															idmension,
															institucion,
															anio_egreso,
															observaciones,
															flag_constancia,
															flag_actual,
															usuario,
															status,
															fechayhora) 
													values 
															($id_trabajador,
															$idnivel_estudio,
															'$idprofesion',
															'$idmension',
															'$institucion',
															'$anio_egreso',
															'$observaciones',
															'$flag_contancia',
															'$flag_actual',
															'$login',
															'a',
															'$fh')",
													$conexion_db)or die("AQUI".mysql_error());
					registra_transaccion('a',$login,$fh,$pc,'instruccion_academica',$conexion_db);
					mensaje("El resgirto se Inserto con exito");
					redirecciona("principal.php?modulo=1&accion=18&cedula_buscar=".$_REQUEST["cedula_buscar"]."");
			}
		}

	if ($_GET["accion"] == 868 and in_array(868,$privilegios) == true and !$_POST["buscar"]){
			mysql_query("update instruccion_academica set 
												idtrabajador='".$id_trabajador."',
												idnivel_estudio='".$idnivel_estudio."',
												idprofesion='".$idprofesion."',
												idmension='".$idmension."',
												institucion='".$institucion."',
												anio_egreso='".$anio_egreso."',
												observaciones='".$observaciones."',
												flag_constancia='".$flag_constancia."',
												flag_actual='".$flag_actual."'
										where idinstruccion_academica = '$idinstruccion_academica'"
												,$conexion_db)or die(mysql_error());	
			registra_transaccion('m',$login,$fh,$pc,'instruccion_academica',$conexion_db);
			mensaje("El resgirto se Modifico con exito");
			redirecciona("principal.php?modulo=1&accion=18&cedula_buscar=".$_REQUEST["cedula_buscar"]."");
	}
	if ($_GET["accion"] == 869 and in_array(869, $privilegios) == true and !$_POST["buscar"]){
			mysql_query("delete from instruccion_academica where idinstruccion_academica = '$idinstruccion_academica'",$conexion_db)or die(mysql_error());	
			registra_transaccion('e',$login,$fh,$pc,'instruccion_academica',$conexion_db);
			mensaje("El resgirto se Elimino con exito");
			redirecciona("principal.php?modulo=1&accion=18&cedula_buscar=".$_REQUEST["cedula_buscar"]."");
	}
}
?>
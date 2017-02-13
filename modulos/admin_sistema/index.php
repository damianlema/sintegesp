<?
//session_start();
include("../../conf/conex.php");
Conectarse();
if($_POST["botonSubirSql"]){
extract($_POST);
	if($nombreBD == ""){
		$query = mysql_query($sql)or die(mysql_error());
		if($query){
			echo "exito";
		}
		
	}else{
		//$query = "USE ".$nombreBD.";\n";
		mysql_select_db($nombreBD);
		//$patron = "^(\r\n)+|^(\n)+|^(\r)+|^(\n\r)+";
		//$query = eregi_replace($patron, '', $query);
		$query = utf8_decode($sql);
		//$query = "insert into tabla (nombre)values(1);";
		$result = mysql_query(stripslashes($query))or die(mysql_error());
		
		if($result){
			?>
				<script>
					alert("Se ingreso el Script con Exito");
				</script>
			<?
		}
	}
}



if($_POST["botonSubirArchivos"]){
	$nombre_temporal = $_FILES["archivo"]["tmp_name"];
	$nombre_real = $_FILES["archivo"]["name"];
	echo $nombre_temporal."<br>";
	echo $nombre_real."<br>";
	echo $ruta_archivo."<br>";
	
	
	if(move_uploaded_file($nombre_temporal, "../../".$ruta_archivo.$nombre_real)){
		?>
		<script>
			alert("El archivo se subio con exito");
		</script>
		<?
	}else{
		?>
		<script>
			alert("Error al subir el archivo");
		</script>
		<?
	}
}


?>
<title>Administracion del Sistema</title>
<link href="../../css/theme/green/main.css" rel="stylesheet" type="text/css">
<link href="css/estilos_admin.css" rel="stylesheet" type="text/css">
<script src="js/admin_sistema_ajax.js"></script>
<script src="../../js/function.js" type="text/javascript" language="javascript"></script>
<style type="text/css">
<!--
.Estilo1 {color: #EE0000}
-->
</style>
</head>

<body>
<div id="tabsF">
  <ul>
    <li><a href="javascript:;" onClick="mostrarContenido('agrupar_modulos')"><span>Agrupar Modulos</span></a></li>
    <li><a href="javascript:;" onClick="mostrarContenido('limpiar_repetidos')"><span>Limpiar Repetidos</span></a></li>
    <li><a href="javascript:;" onClick="mostrarContenido('modulos')"><span>Ingresar Modulos</span></a></li>
    <li><a href="javascript:;" onClick="mostrarContenido('nuevas_acciones')"><span>Crear Nuevas Acciones</span></a></li>
    <li><a href="javascript:;" onClick="mostrarContenido('montar_sql')"><span>Montar SQL's</span></a></li>
    <li><a href="javascript:;" onClick="mostrarContenido('incluir_archivos')"><span>Incluir Archivos</span></a></li>
    <li><a href="javascript:;" onClick="mostrarContenido('ruta_reportes')"><span>Configuracion Reportes</span></a></li>
    <li><a href="javascript:;" onClick="mostrarContenido('configuracion')"><span>Configuracion General</span></a></li>
    <li><a href="javascript:;" onClick="mostrarContenido('respaldo')"><span>Respaldo Transacciones</span></a></li>
    <li><a href="javascript:;" onClick="mostrarContenido('configuracion_cheques')"><span>Configuracion Cheques</span></a></li>
    
  </ul>
</div>
<br /><br />

<div id="divMensaje" align="center"></div>





<div style="margin-left:20px; margin-right:20px; margin-top:10px; border:#999999 1px solid; display:none" class="contenido" id="respaldo"> 
<?
$sql_configuracion = mysql_query("select * from configuracion");
$bus_configuracion = mysql_fetch_array($sql_configuracion);
?>
<table align="center" border="0">
	<th colspan="2">Configuracion Respaldo Transacciones</th>
    <tr>
    <td>&nbsp;</td>
    </tr>
    
    <tr>    
        <td class="contenido">Usuario de la BD</td>
        <td><input type="text" name="usaurio_bd" id="usuario_bd" size="40" class="campoTexto" value="<?=$bus_configuracion["usuario_bd"]?>"></td>
    </tr>
    <tr>    
        <td class="contenido">Clave de la BD</td>
        <td><input type="password" name="clave_bd" id="clave_bd" size="40" class="campoTexto" value="<?=$bus_configuracion["clave_bd"]?>"></td>
    </tr>
    <tr>
      <td class="contenido">BD de Conexion</td>
      <td><input type="text" name="bd_conexion" id="bd_conexion" size="40"  class="campoTexto" value="<?=$bus_configuracion["bd_conexion"]?>"/></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>    
        <td colspan="2" align="center">
          <input type="button" 
          			name="boton_guardar_respaldo" 
                    id="boton_guardar_respaldo" 
                    value="Guardar Datos para Respaldo" 
                    onclick="guardarDatosRespaldo(document.getElementById('usuario_bd').value, 
                                                    document.getElementById('clave_bd').value, 
                                                    document.getElementById('bd_conexion').value)"/>       </td>
    </tr>
</table>

</div>






















<div style="margin-left:20px; margin-right:20px; margin-top:10px; border:#999999 1px solid; display:none" class="contenido" id="limpiar_repetidos"> 

<table align="center" border="0">
	<th colspan="2">Limpiar Registros Duplicados de una Tabla</th>
    <tr>
    <td>&nbsp;</td>
    </tr>
    
    <tr>    
        <td class="contenido">Nombre de la tabla a Limpiar</td>
        <td><input type="text" name="nombre_tabla_cambiar" id="nombre_tabla_cambiar" size="40" class="campoTexto"></td>
    </tr>
    <tr>    
        <td class="contenido">Nombre del Campo por donde Agrupar</td>
        <td><input type="text" name="nombre_campo_agrupar" id="nombre_campo_agrupar" size="20" class="campoTexto"></td>
    </tr>
    <tr>
      <td class="contenido">Nombre de la base de datos</td>
      <td><input type="text" name="nombre_bd" id="nombre_bd" size="20"  class="campoTexto"/></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>    
        <td colspan="2" align="center">
          <input type="button" 
          			name="botonLimpiarDatos" 
                    id="botonLimpiarDatos" 
                    value="Limpiar" 
                    onclick="limpiarDatosDuplicados(document.getElementById('nombre_tabla_cambiar').value, 
                                                    document.getElementById('nombre_campo_agrupar').value, 
                                                    document.getElementById('nombre_bd').value)"/>       </td>
    </tr>
</table>

</div>



<div style="margin-left:20px; margin-right:20px; margin-top:10px; border:#999999 1px solid; display:none" class="contenido" id="nuevas_acciones">
<table align="center" border="0">
	<th colspan="2">Crear Nuevas Acciones</th>
    <tr>
    <td>&nbsp;</td>
    </tr>
    <tr>
    	<td class="contenido">Nombre de la Accion</td>
        <td><input type="text" name="nombre_accion" id="nombre_accion" size="20" class="campoTexto"></td>
    </tr>
    <tr>    
        <td class="contenido">Id del Modulo que pertenece</td>
        <td><input type="text" name="id_modulo" id="id_modulo" size="1" class="campoTexto"></td>
    </tr>
    <tr>    
        <td class="contenido">Url de la Accion</td>
        <td><input type="text" name="url" id="url" size="40" class="campoTexto"></td>
    </tr>
    <tr>
      <td class="contenido">Esta accion se muestra en el Menu?</td>
      <td><input type="checkbox" name="mostrar" id="mostrar" value="1" class="campoTexto" /></td>
    </tr>
    <tr>
      <td class="contenido">Numero de la Accion Padre</td>
      <td><input type="text" name="accion_padre" id="accion_padre" size="4" class="campoTexto" /></td>
    </tr>
    <tr>
      <td class="contenido">Numero de la Posicion que desea Colocar la Accion</td>
      <td><input type="text" name="posicion" id="posicion" size="1" class="campoTexto" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>    
        <td colspan="2" align="center">
          <input type="button" 
          			name="botonCrearAccion" 
                    id="botonCrearAccion" 
                    value="Crear Accion"
                    onclick="crearNuevasAcciones(document.getElementById('nombre_accion').value, 
                    							document.getElementById('id_modulo').value, 
                                                document.getElementById('url').value, 
                                                document.getElementById('mostrar').value, 
                                                document.getElementById('accion_padre').value, 
                                                document.getElementById('posicion').value)"/>
        </td>
    </tr>
</table>
<br>
<strong style="font-size:16px">&nbsp;&nbsp;Acciones Existentes</strong>
<center>
<div style="overflow:auto; width:100%; height:400px; text-align:left; border-top:#000000 solid 1px">
    <table class="Browse" cellpadding="0" cellspacing="0" width="100%">
        <thead>
            <tr>
                <!--<td class="Browse">&nbsp;</td>-->
                <td width="2%" align="center" class="Browse">ID</td>
                <td width="24%" align="center" class="Browse">Nombre</td>
                <td width="20%" align="center" class="Browse">Modulo</td>
                <td width="29%" align="center" class="Browse">Ruta</td>
                <td width="19%" align="center" class="Browse">Pertenece a</td>
                <td width="3%" align="center" class="Browse">Pos</td>
                <td width="3%" align="center" class="Browse">Sel</td>
          </tr>
        </thead>
    <?
    $sql_acciones= mysql_query("select * from accion");
        while($bus_acciones= mysql_fetch_array($sql_acciones)){
            ?>
            <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                <td align='center' class='Browse'>&nbsp;<?=$bus_acciones["id_accion"]?></td>
                <td align='left' class='Browse'>&nbsp;<?=$bus_acciones["nombre_accion"]?></td>
                <td align='left' class='Browse'>&nbsp;
                <?
                $sql_modulos = mysql_query("select * from modulo where id_modulo = '".$bus_acciones["id_modulo"]."'");
                $bus_modulos = mysql_fetch_array($sql_modulos);
                    if($bus_acciones["id_modulo"] != 0){
                        echo $bus_modulos["nombre_modulo"];
                    }else{
                        echo "<strong>Sin Modulo Asociado</strong>";
                    }
                ?>                </td>
                <td align='left' class='Browse' style="padding:5px">&nbsp;
                <?
                if($bus_acciones["url"] != ""){
                        echo $bus_acciones["url"];
                    }else{
                        echo "<strong>Sin Ruta</strong>";
                    }
				$bus_acciones["url"]
				?></td>
                <td align='left' class='Browse'>&nbsp;
                <?
                $sql_accion_padre = mysql_query("select * from accion where id_accion = '".$bus_acciones["accion_padre"]."'");
                $bus_accion_padre = mysql_fetch_array($sql_accion_padre);
                    if($bus_acciones["accion_padre"] != 0){
                        echo $bus_accion_padre["nombre_accion"];
                    }else{
                        echo "<strong>Ninguna</strong>";
                    }
                ?>                </td>
                <td align='center' class='Browse'><?=$bus_acciones["posicion"]?></td>
                <td width="3%" align="center" class="Browse"><img src="../../imagenes/validar.png" style="cursor:pointer; margin:3px"></td>
        </tr>
            <?
        }
    ?>
	</table>
</div>
</center>

</div>



<div style="margin-left:20px; margin-right:20px; margin-top:10px; border:#999999 1px solid; display:none" class="contenido" id="montar_sql">
<form method="post" action="">
<table align="center" border="0">
	<th colspan="2">Montar SQL's</th>
    <tr>
    <td colspan="2" class="Estilo1" style="font-size:11px"><br><strong>Esta opcion solo funciona para script Cortos</strong><br><br></td>
    </tr>
    <tr>
        <td class="contenido">Nombre de la Base de Datos</td>
      <td class="contenido">
        <input type="text" name="nombreBD" id="nombreBD" class="campoTexto"> <span class="Estilo1">* Deje este campo en blanco si no va a seleccionar ninguna base de datos</span>        </td>
    </tr>
    <tr>
        <td colspan="2"><textarea name="sql" id="sql" rows="20" cols="140" class="campoTexto"></textarea></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>    
        <td align="left">
          <input type="submit" 
          			name="botonSubirSql" 
                    id="botonSubirSql" 
                    value="Subir SQL" />
        </td>
        <td align="right">
          &nbsp;
          <input type="button" 
          			name="botonLimpiarSql" 
                    id="botonLimpiarSql" 
                    value="Limpiar Campo" 
                    onclick="document.getElementById('sql').value = ''"/>
        </td>
    </tr>
</table>
</form>
</div>


<div style="margin-left:20px; margin-right:20px; margin-top:10px; border:#999999 1px solid; display:none" class="contenido" id="incluir_archivos">
<form method="post" action="" enctype="multipart/form-data">
<table align="center" border="0">
	<th colspan="2">Subir Nuevos Archivos</th>
    <tr>
    <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
        <td class="contenido">Ruta donde va a Colocar el Archivo</td>
        <td style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:9px; color:#990000"><input type="text" name="ruta_archivo" id="ruta_archivo" size="40" class="campoTexto">
        	* A Partir de Gestion, es decir si el archivo se colocara en el Lib principal, debe escribir "lib/"
        </td>
    </tr>
    <tr>
        <td class="contenido">Archivo</td>
        <td><input type="file" name="archivo" id="archivo" class="campoTexto"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>    
        <td colspan="2" align="center">
          <input type="submit" name="botonSubirArchivos" id="botonSubirArchivos" value="Subir Archivo" />
        </td>
    </tr>
</table>
</form>
</div>








<?
$sql_configuracion = mysql_query("select * from configuracion_logos");
$bus_configuracion = mysql_fetch_array($sql_configuracion);
?>
<div style="margin-left:20px; margin-right:20px; margin-top:10px; border:#999999 1px solid; display:none" class="contenido" id="ruta_reportes">
<table align="center" border="0">
	<th colspan="2">Configuracion Reportes</th>
    <tr>
    <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
        <td class="contenido">Logo Principal</td>
        <td style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:9px; color:#990000">
        <input type="text" name="logo" id="logo" class="campoTexto" value="<?=$bus_configuracion["logo"]?>" size="40">
        </td>
        <td class="contenido">Alto</td>
        <td><input type="text" id="alto_primero" name="alto_primero" size="5" value="<?=$bus_configuracion["alto_primero"]?>"></td>
        <td class="contenido">Ancho</td>
        <td><input type="text" id="ancho_primero" name="ancho_primero" size="5" value="<?=$bus_configuracion["ancho_primero"]?>"></td>
        <td class="contenido">Pix</td>
    </tr>
    <tr>
        <td class="contenido">Segunda Imagen</td>
        <td style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:9px; color:#990000">
        <input type="text" name="segundo_logo" id="segundo_logo" class="campoTexto" value="<?=$bus_configuracion["segundo_logo"]?>" size="40">
        </td>
        <td class="contenido">Alto</td>
        <td><input type="text" id="alto_segundo" name="alto_segundo" size="5" value="<?=$bus_configuracion["alto_segundo"]?>"></td>
        <td class="contenido">Ancho</td>
        <td><input type="text" id="ancho_segundo" name="ancho_segundo" size="5" value="<?=$bus_configuracion["ancho_segundo"]?>"></td>
        <td class="contenido">Pix</td>
    </tr>
    <tr>    
        <td colspan="2" align="center">
          <input type="submit" 
          			name="botonCambiarReportes" 
                    id="botonCambiarReportes" 
                    value="Guardar Datos" 
                    onClick="procesarConfiguracion()"/>
        </td>
    </tr>
</table>
</div>









<div style="margin-left:20px; margin-right:20px; margin-top:10px; border:#999999 1px solid; display:none" class="contenido" id="configuracion">
<table align="center" border="0">
	<th colspan="2">Configuracion General</th>
    <tr>
    <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
        <td class="contenido">Version del Sistema a Instalar</td>
        <td style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:9px; color:#990000">
        <?
        $sql_consulta = mysql_query("select * from configuracion");
		$bus_consulta = mysql_fetch_array($sql_consulta);
		?>
        <select name="tipo_sistema" id="tipo_sistema">
        	<option value="0">.:: Seleccione ::.</option>
            <option value="basico" <? if($bus_consulta["version"] == "basico"){echo "selected";}?>>Basico</option>
            <option value="completo" <? if($bus_consulta["version"] == "completo"){echo "selected";}?>>Completo</option>
        </select>
        </td>
    </tr>
    <tr>    
        <td colspan="2" align="center">
          <input type="submit" 
          			name="botonActualizar" 
                    id="botonActualizar" 
                    value="Actualizar Configuracion" 
                    onClick="actualizar_configuracion(document.getElementById('tipo_sistema').value)"/>
        </td>
    </tr>
</table>
</div>




















<div style="margin-left:20px; margin-right:20px; margin-top:10px; border:#999999 1px solid; display:block" class="contenido" id="agrupar_modulos">
<table align="center" border="0">
	<th colspan="2">Agrupar Modulos</th>
    <tr>
    <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
        <td class="contenido">Grupo</td>
        <td style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:9px; color:#990000">
        <select name="grupo" id="grupo">
        	<option value="0" selected>.:: Seleccione ::.</option>
            <option value="1" onClick="mostrar_modulos(this.value)">Grupo 1</option>
            <option value="2" onClick="mostrar_modulos(this.value)">Grupo 2</option>
            <option value="3" onClick="mostrar_modulos(this.value)">Grupo 3</option>
            <option value="4" onClick="mostrar_modulos(this.value)">Grupo 4</option>
            <option value="5" onClick="mostrar_modulos(this.value)">Grupo 5</option>
        </select>
        </td>
    </tr>
    <tr>
    	<td>Modulos</td>
        <td>
        <?
        $sql_modulos = mysql_query("select * from dependencias");
		?>
        <select id="modulos_asociados" name="modulos_asociados">
        <?
        while($bus_modulos = mysql_fetch_array($sql_modulos)){
			?>
			<option value="<?=$bus_modulos["iddependencia"]?>"><?=$bus_modulos["iddependencia"]?><?=$bus_modulos["denominacion"]?></option>
			<?	
		}
		?>
        </select>
        </td>
    </tr>
    <tr>    
        <td colspan="2" align="center">
          <input type="submit"  
          			name="botonIngresarModulo" 
                    id="botonIngresarModulo" 
                    value="Agrupar Modulo" 
                    onClick="agrupar_modulos(document.getElementById('modulos_asociados').value, document.getElementById('grupo').value)"/>
        </td>
    </tr>
</table>
<br>

<div id="listaGrupoModulos"></div>



</div>






















<div style="margin-left:20px; margin-right:20px; margin-top:10px; border:#999999 1px solid; display:none" class="contenido" id="modulos">
<table align="center" border="0">
	<th colspan="2">Ingresar Nuevos Modulos</th>
    <tr>
    <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
        <td class="contenido">Nombre del Nuevo Modulo</td>
        <td style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:9px; color:#990000">
        
        <input type="text" name="nuevo_modulo" id="nuevo_modulo" class="campoTexto">
        </td>
    </tr>
    <tr>    
        <td colspan="2" align="center">
          <input type="submit" 
          			name="botonIngresarModulo" 
                    id="botonIngresarModulo" 
                    value="Ingresar Modulo" 
                    onClick="ingresar_modulos(document.getElementById('nuevo_modulo').value)"/>
        </td>
    </tr>
</table>
<br>
<br>
<strong style="font-size:16px">&nbsp;&nbsp;Modulos Existentes</strong>
<center><div style="overflow:auto; width:90%; height:400px; text-align:left">
<?
$sql_consultar_modulos= mysql_query("select * from modulo");
	while($bus_consultar_modulos = mysql_fetch_array($sql_consultar_modulos)){
		?>
        &nbsp;-&nbsp;
        <input type="checkbox" id="modulo_mostrar<?=$bus_consultar_modulos["id_modulo"]?>" name="modulo_mostrar<?=$bus_consultar_modulos["id_modulo"]?>" style="cursor:pointer" <? if($bus_consultar_modulos["mostrar"] == "si"){echo "checked";}?> onClick="mostrar_modulo(this.id, '<?=$bus_consultar_modulos["id_modulo"]?>')">&nbsp;<?=$bus_consultar_modulos["nombre_modulo"]?><br>
		<?
	}
?>
</div></center>
</div>



<div style="margin-left:20px; margin-right:20px; margin-top:10px; border:#999999 1px solid; display:none" class="contenido" id="configuracion_cheques">
<br>
<center><strong style="font-size:16px" style="text-align:center">&nbsp;&nbsp;Configuracion Cheques</strong></center>
<br>
<table align="center">
<tr>
<td>Banco</td>
<td>
<select name="idbanco" id="idbanco">
<option>.:: Seleccione ::.</option>
<?
$sql_consulta = mysql_query("select * from banco");
while($bus_consulta = mysql_fetch_array($sql_consulta)){
?>

<option onClick="mostrarConfiguracion('<?=$bus_consulta["idbanco"]?>')" value="<?=$bus_consulta["idbanco"]?>"><?=$bus_consulta["denominacion"]?></option>
<?
}
?>

</select>
</td>
</tr>
<tr>
<td>Alto Monto en Numeros</td>
<td><input type="text" name="alto_monto_numeros" id="alto_monto_numeros"></td>
<td>Izquierda Monto en Numeros</td>
<td><input type="text" name="izquierda_monto_numeros" id="izquierda_monto_numeros"></td>
</tr>

<tr>
<td>Alto Beneficiario</td>
<td><input type="text" name="alto_beneficiario" id="alto_beneficiario"></td>
<td>Izquierda Beneficiario</td>
<td><input type="text" name="izquierda_beneficiario" id="izquierda_beneficiario"></td>
</tr>


<tr>
<td>Alto Monto en Letras</td>
<td><input type="text" name="alto_monto_letras" id="alto_monto_letras"></td>
<td>Izquierda Monto en Letras</td>
<td><input type="text" name="izquierda_monto_letras" id="izquierda_monto_letras"></td>
</tr>


<tr>
<td>Alto de la  Fecha</td>
<td><input type="text" name="alto_fecha" id="alto_fecha"></td>
<td>Izquierda de la Fecha</td>
<td><input type="text" name="izquierda_fecha" id="izquierda_fecha"></td>
</tr>


<tr>
<td>Alto del A&ntilde;o</td>
<td><input type="text" name="alto_ano" id="alto_ano"></td>
<td>Izquierda del A&ntilde;o</td>
<td><input type="text" name="izquierda_ano" id="izquierda_ano"></td>
</tr>

<tr>
<td colspan="4">
<input type="button" name="boton_ingresar_cheque" id="boton_ingresar_cheque" value="Guardar Configuracion del Cheque" onClick="actualizarConfiguracion()">
</td>
</tr>

</table>

</div>
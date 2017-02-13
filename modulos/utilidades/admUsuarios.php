<head>
<link rel="STYLESHEET" type="text/css" href="modulos/utilidades/css/dhtmlXTree.css">
<script src="modulos/utilidades/js/dhtmlXCommon.js"></script>
<script src="modulos/utilidades/js/dhtmlXTree.js"></script>
<script src="modulos/utilidades/js/consultar_usuarios_ajax.js"></script>
<script src="modulos/utilidades/js/admUsuarios_ajax.js"></script>

<?
extract($_POST);
if($_POST["nuevoUsuario"]){
	$con1 = mysql_query("select * from usuarios where login = '".$usuario."'");
	$num1 = mysql_num_rows($con1);
	$con = mysql_query("select * from usuarios where cedula = '".$cedula."'");
	$num = mysql_num_rows($con);
		if($nombres == "" || $apellidos == "" || $login == "" || $clave == "" || $repetir_clave == "" || $preguntasecreta == "" || $respuestasecreta == ""){
			?>
			<script>
			mostrarMensajes("error", "Disculpe los campos no pueden estar vacios");
			setTimeout("window.location.href='principal.php?modulo=10&accion=62'",5000);
            </script>
			<?
			exit();
		}
		
		if(!$mods){
			?>
			<script>
			mostrarMensajes("error", "Disculpe debe seleccionar los privilegios del usuario");
			setTimeout("window.location.href='principal.php?modulo=10&accion=62'",5000);
            </script>
			<?
			echo "seleccionarPrivilegios";
			exit();
		}
		
		if($clave != $repetir_clave){
			?>
			<script>
			mostrarMensajes("error", "Disculpe La clave y la repeticion de la misma son distintos");
			setTimeout("window.location.href='principal.php?modulo=10&accion=62'",5000);
            </script>
			<?
			exit();
		}
		
		if($num > 0){
			?>
			<script>
			mostrarMensajes("error", "Disculpe la cedula que ingreso ya existe");
			setTimeout("window.location.href='principal.php?modulo=10&accion=62'",5000);
            </script>
			<?
			exit();
		}else if($num1 > 0){
			?>
			<script>
			mostrarMensajes("error", "Disculpe el usuario que ingreso ya existe");
			setTimeout("window.location.href='principal.php?modulo=10&accion=62'",5000);
            </script>
			<?
			exit();
		}else{	
			
			$sql = mysql_query("insert into usuarios (cedula, 
														nombres,
													 	apellidos,
														login,
														clave,
														preguntasecreta,
														respuestasecreta,
														fechayhora,
														estacion,
														status
														)values(
															".$cedula.",
															'".$nombres."',
															'".$apellidos."',
															'".$usuario."',
															'".md5($clave)."',
															'".$preguntasecreta."',
															'".$respuestasecreta."',
															'".$fh."',
															'".$pc."',
															'a'
															)")or die(mysql_error());
			
			$registros = explode(",", $mods);
			foreach($registros as $reg){
				$mod = explode("_", $reg);
				$modulo = $mod[1]; 
				$accion = $reg;
				$sql2 = mysql_query("insert into privilegios_modulo(id_modulo, id_usuario)values(".$modulo.", ".$cedula.")");
				if(!$mod[1]){
					$sql3 = mysql_query("insert into privilegios_acciones(id_accion, id_usuario)values(".$accion.", ".$cedula.")");
				}
			}	
		}
			registra_transaccion('Ingresar Usuarios ('.$cedula.')',$login,$fh,$pc,'usuarios',$conexion_db);		
	?>
	<script>
			mostrarMensajes("exito", "El usuario creado con exito");
			setTimeout("window.location.href='principal.php?modulo=10&accion=62'",5000);
            </script>
	<?
}



if($_POST["actualizarUsuario"]){
		if($cedula == "" || $nombres == ""){
			?>
			<script>
			mostrarMensajes("error", "Disculpe el Nombre o la cedula estan vacios");
			setTimeout("window.location.href='principal.php?modulo=10&accion=62'",5000);
            </script>
			<?
			exit();
		}
		
		if(!$mods){
			?>
			<script>
			mostrarMensajes("error", "Disculpe seleccione los privilegios");
			setTimeout("window.location.href='principal.php?modulo=10&accion=62'",5000);
            </script>
			<?
			exit();
		}
		
		if($clave != $repetir_clave){
			?>
			<script>
			mostrarMensajes("error", "Disculpe la clave y la repeticion no son iguales");
			setTimeout("window.location.href='principal.php?modulo=10&accion=62'",5000);
            </script>
			<?
			exit();
		}
		if($clave != ""){
			$sql = mysql_query("update usuarios set nombres = '".$nombres."',
													apellidos = '".$apellidos ."',
													clave = '".md5($clave)."',
													preguntasecreta = '".$preguntasecreta."',
													respuestasecreta = '".$respuestasecreta."',
													fechayhora = '".$fh."',
													estacion = '".$pc."'
													 where cedula = ".$cedula."")or die(mysql_error());
		}else{
			$sql = mysql_query("update usuarios set nombres = '".$nombres."',
													apellidos = '".$apellidos ."',
													preguntasecreta = '".$preguntasecreta."',
													respuestasecreta = '".$respuestasecreta."',
													fechayhora = '".$fh."',
													estacion = '".$pc."'
													 where cedula = ".$cedula."")or die(mysql_error());
		}
		
			
			$delete = mysql_query("delete from privilegios_modulo where id_usuario = ".$cedula."");
			$delete2 = mysql_query("delete from privilegios_acciones where id_usuario = ".$cedula."");	
			//var_dump($mods);exit();
			
			$registros = explode(",", $mods);
			foreach($registros as $reg){
				$mod = explode("_", $reg);
				$modulo = $mod[1]; 
				$accion = $reg;
				$sql2 = mysql_query("insert into privilegios_modulo(id_modulo, id_usuario)values(".$modulo.", ".$cedula.")");
				if(!$mod[1]){
					$sql3 = mysql_query("insert into privilegios_acciones(id_accion, id_usuario)values(".$accion.", ".$cedula.")");
				}
			}	
			registra_transaccion('Modificar Usuarios ('.$cedula.')',$login,$fh,$pc,'usuarios',$conexion_db);
			?>
			<script>
				mostrarMensajes("exito", "Usuario Actualizado con Exito");
				setTimeout("window.location.href='principal.php?modulo=10&accion=62'",5000);
            </script>
			<?
}





if($_GET["id_usuario"]){
	$sql_datos_usuario = mysql_query("select * from usuarios where cedula = ".$_GET["id_usuario"]."");
	$bus_datos_usuario = mysql_fetch_array($sql_datos_usuario);
}
?>

<script language="javascript">

function carga_checks()
	{
		document.form1.mods.value=tree.getAllChecked();
	}

</script>

</head>
<body>




<table width="100%" align="center">
<tr>
    	<td width="29%" valign="top">




  <table width="86%" align="right" height="100%" style="border:#666666 1px solid">
  <tr>
  	<td valign="top" bgcolor="#DFEFFF">
    Escriba el Nombre del Usuario que desea buscar  (* para buscar todos) 
    	<input type="text" name="buscarNombre" id="buscarNombre" size="40" onKeyUp="consultarUsuarios(this.value)" autocomplete="OFF"/>
    <div id="resultadoUsuarios" style=" height:600px; overflow:auto;"></div>
    </td>
  </tr>
  </table>



</td>
<td width="71%">



    <form name="form1" method="post" action="" onSubmit="carga_checks();">
    <input type="hidden" name="mods" />
      <table width="45%" border="0" align="left" cellpadding="2" cellspacing="2" class="table">
        <tr>
          <td colspan="2" class="fondoAzul">&nbsp;
            <div align="center">Agregar Usuario</div></td>
          </tr>
        <tr>
          <td width="41%" class="fondoGrisClaro">&nbsp;</td>
          <td width="59%" class="fondoGrisClaro">&nbsp;</td>
        </tr>
        <tr>
          <td class="viewPropTitle">&nbsp;Cedula</td>
          <td class="fondoGrisClaro"><input type="text" name="cedula" id="cedula" value="<?=$bus_datos_usuario["cedula"]?>" <? if($_GET["id_usuario"]){ echo "readonly";}?>></td>
        </tr>
        <tr>
          <td class="viewPropTitle">&nbsp;Nombres</td>
          <td class="fondoGrisClaro"><input name="nombres" type="text" id="nombres" size="40" value="<?=$bus_datos_usuario["nombres"]?>"></td>
        </tr>
        <tr>
          <td class="viewPropTitle">&nbsp;Apellidos</td>
          <td class="fondoGrisClaro"><input name="apellidos" type="text" id="apellidos" size="40" value="<?=$bus_datos_usuario["apellidos"]?>"></td>
        </tr>
        <tr>
          <td class="viewPropTitle">&nbsp;Usuario </td>
          <td class="fondoGrisClaro"><input type="text" name="usuario" id="usuario" value="<?=$bus_datos_usuario["login"]?>" <?php if($_GET["id_usuario"]){ echo "disabled";}?>></td>
        </tr>
        <tr>
          <td class="viewPropTitle">&nbsp;Clave</td>
          <td class="fondoGrisClaro"><input type="password" name="clave" id="clave"></td>
        </tr>
        <tr>
          <td class="viewPropTitle">&nbsp;Repetir Clave</td>
          <td class="fondoGrisClaro"><input type="password" name="repetir_clave" id="repetir_clave"></td>
        </tr>
        <tr>
          <td class="viewPropTitle">&nbsp;Pregunta Secreta</td>
          <td class="fondoGrisClaro"><input name="preguntasecreta" type="text" id="preguntasecreta" size="40" value="<?=$bus_datos_usuario["preguntasecreta"]?>"></td>
        </tr>
        <tr>
          <td class="viewPropTitle">&nbsp;Respuesta</td>
          <td class="fondoGrisClaro"><input name="respuestasecreta" type="text" id="respuestasecreta" size="40" value="<?=$bus_datos_usuario["respuestasecreta"]?>"></td>
        </tr>
        <tr>
          <td class="viewPropTitle">&nbsp;</td>
          <td class="fondoGrisClaro">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" class="fondoGrisClaro">&nbsp;
          <?
          if($_GET["id_usuario"]){
		  	?>
				<table>
                	<tr>
                		<td width="112"><input name="nuevoUsuario" type="button" onClick="window.location.href='principal.php?modulo=9&accion=62'" class="button" id="nuevoUsuario" value="Nuevo Usuario"></td>
                		<td width="136"><input name="actualizarUsuario" type="submit" class="button" id="actualizarUsuario" value="Actualizar Usuario" onClick="mostrarProcesando()"></td>
               		    <td width="216"><input <?php if(!$_GET["inactivo"]){ echo "name='eliminar' id='eliminar' value='Eliminar Usuario' onclick='eliminarUsuario(".$bus_datos_usuario["cedula"].")'";}else{echo " onclick='activarUsuario(".$bus_datos_usuario["cedula"].")' name='activar' id='activar' value='Activar Usuario'";}?>  type="button" class="button"  /></td>
                	</tr>
               </table>
			<?
		  }else{
		  ?>
		 	 <table>
                	<tr>
                		<td width="112"><input name="nuevoUsuario" type="submit" class="button" id="nuevoUsuario" value="Ingresar Usuario" onClick="mostrarProcesando()"></td>
                		<td width="136"><input name="reiniciar" type="reset" class="button" id="reiniciar" value="Reiniciar"></td>
                	</tr>
              </table>
		  <?
		  }
		  ?>
          
          <br>          </td>
          </tr>
      </table>
 


 
 	
      <table width="55%" border="0" cellpadding="1" cellspacing="0" class="table" style="border:#666666 1px solid">
        <tr>
          <td class="fondoAzul">&nbsp;<strong>Permisos del Usuario</strong></td>
        </tr>
        <tr>
            <td>
            <div align="right" style="background:#FFFFFF; border-bottom:#666666 1px solid;"><a href="#" onClick="checkTodos()">Todo</a> | <a href="#" onClick="checkNinguno()">Ninguno</a></div>
            <br />

            <div id="treeboxbox_tree" style="width:100%;height:600px;  background-color:#FFFFFF;border:0px;"></div>
            </td>
        </tr>
      </table>
    </form>


</td>
</tr>
</table>

<script>

<?
if($_GET["id_usuario"]){
?>

         tree=new dhtmlXTreeObject("treeboxbox_tree","100%","100%",0);
         tree.setImagePath("modulos/utilidades/imagenes/csh_bluebooks/");
		 tree.enableCheckBoxes(1);
         //tree.enableMultiselection(true);
		 tree.enableThreeStateCheckboxes(false);
		 tree.loadXML("modulos/utilidades/listas.php");
		 
		 //tree.setOnClickHandler(tonclick);

		var wait = setInterval('defaults()',5000);

		document.getElementById("divCargando").style.display = "block";
		function defaults(){
			tree.openAllItems(0);
			clearInterval(wait);
		
			<?php
			$sql3 = mysql_query("select * from privilegios_modulo where id_usuario = ".$_GET["id_usuario"]."");
				while($row_modulos = mysql_fetch_array($sql3)){
					echo "tree.setCheck('m_".$row_modulos['id_modulo']."',true);\n";
				}
			$sql4 = mysql_query("select * from privilegios_acciones where id_usuario = ".$_GET["id_usuario"]."");
				while($row_accion = mysql_fetch_array($sql4)){
					echo "tree.setCheck('".$row_accion['id_accion']."',true);\n";
				}
			?>
			document.getElementById("divCargando").style.display = "none";
		}
		
		function checkTodos(){
			tree.enableThreeStateCheckboxes(true);
			tree.setCheck(999999,true);
			tree.enableThreeStateCheckboxes(false);
			}
			
		function checkNinguno(){
			tree.enableThreeStateCheckboxes(true);
			tree.setCheck(999999,false);
			tree.enableThreeStateCheckboxes(false);
			}
			
		
<?
}else{
?>

tree=new dhtmlXTreeObject("treeboxbox_tree","100%","100%",0);
         tree.setImagePath("modulos/utilidades/imagenes/csh_bluebooks/");
		 tree.enableCheckBoxes(1);
         tree.enableThreeStateCheckboxes(false);	
         tree.loadXML("modulos/utilidades/listas.php");
			//tree.setOnClickHandler(tonclick);
		 //tree.enableMultiselection(true);
		 //setTimeout("",3000);
		 
		 //var wait = setInterval('defaults()',1000);
		
		function defaults(){
			tree.openAllItems(0);
			
			clearInterval(wait);
		}
				
				
				function checkTodos()
			{
			tree.enableThreeStateCheckboxes(true);
			tree.setCheck(999999,true);
			tree.enableThreeStateCheckboxes(false);
			}
		function checkNinguno()
			{
			tree.enableThreeStateCheckboxes(true);
			tree.setCheck(999999,false);
			tree.enableThreeStateCheckboxes(false);
			}
			
			
		
<?
}
?>

</script>
</body>
</html>
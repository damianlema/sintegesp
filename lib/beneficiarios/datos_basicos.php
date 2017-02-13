<?php
	$entro_buscar=false;
	$existen_registros=0; // switch para validar si hay datos a cargar en la grilla 0 existen 1 no existen


if($_POST["buscarBeneficiario"]){
	$sql_busqueda = mysql_query("select * from beneficiarios where rif = '".$_POST["buscarBeneficiario"]."'");
	$bus_busqueda = mysql_fetch_array($sql_busqueda);
	$encontroRegistros = mysql_num_rows($sql_busqueda);
	if($encontroRegistros == 0){
		?>
		<script>
		mostrarMensajes("error", "No hay datos registrados con ese RIF");
		</script>
		<?
	}else{
		registra_transaccion("Consultar Beneficiario (".$_POST["buscarBeneficiario"].")",$login,$fh,$pc,'beneficiario');
	}
}

if($_POST["eliminar"]){
	$id_beneficiario = $_POST["id_beneficiario"];
	$sql_consulta = mysql_query("select * from orden_compra_servicio, orden_pago where orden_compra_servicio.idbeneficiarios = '".$id_beneficiario."' or orden_pago.idbeneficiarios = '".$id_beneficiario."'");
	$num_consulta = mysql_num_rows($sql_consulta);
	
	if($num_consulta == 0){
		$sql = mysql_query("select * from beneficiarios where idbeneficiarios = ".$id_beneficiario."");
		$bus = mysql_fetch_array($sql);
		$sql_eliminar = mysql_query("delete from beneficiarios where idbeneficiarios = ".$id_beneficiario."");
		if($sql_eliminar){
			mensaje("");
			?>
			<script>
            mostrarMensajes("exito", "El beneficiario ha sido Eliminado con Exito");
            </script>
            <?
			registra_transaccion("Eliminar Beneficiario (".$bus["rif"].")",$login,$fh,$pc,'beneficiario');
		}else{
			?>
			<script>
            mostrarMensajes("error", "Disculpe no se pudo eliminar el registro, posiblemente es que el mismo este siendo usado en otra tabla, por favor verifique");
            </script>
            <?
			registra_transaccion("Eliminar Beneficiario (ERROR) (".$bus["rif"].")",$login,$fh,$pc,'beneficiario');
		}
	}else{
		?>
		<script>
		mostrarMensajes("error", "Disculpe no se puede eliminar este beneficiario, ya que el mismo tiene compromisos asociados en el sistema");
		</script>
		<?
	}
	
}

if($_POST["modificar"]){
	extract($_POST);
	$fh=date("Y-m-d H:i:s");
	$sql_modificar = mysql_query("update beneficiarios set
												nombre = '".$nombre."',
												rif = '".$rif."',
												nro_expediente = '".$nro_expediente."',
												direccion = '".$direccion."',
												telefonos = '".$telefonos."',
												url = '".$url."',
												email = '".$email."',
												objeto = '".$objeto."',
												datos_registro = '".$datos_registro."',
												representante_legal = '".$representante_legal."',
												cedula_representante = '".$cedula_representante."',
												telefono_representante = '".$telefono_representante."',
												persona_autorizada = '".$persona_autorizada."',
												cedula_persona_autorizada = '".$cedula_persona_autorizada."',
												telefono_persona_autorizada = '".$telefono_persona_autorizada."',
												contribuyente_ordinario = '".$contribuyente_ordinario."', 
												idtipo_beneficiario = ".$idtipo_beneficiario.",
												idtipo_sociedad = ".$idtipo_sociedad.",
												idestado_beneficiario = ".$idestado_beneficiario.",
												idtipos_persona = ".$idtipos_persona.",
												idtipo_empresa = ".$idtipo_empresa.",
												pre_requisitos = '".$pre_requisitos."',
												status = 'a',
												idestado = '".$idestado."',
												usuario = '".$login."',
												fechayhora = '".$fh."' where idbeneficiarios = ".$id_beneficiario."")or die(mysql_error());
												
	if($sql_modificar){
		?>
		<script>
		mostrarMensajes("exito", "El Beneficiario ha sido Actualizado con Exito");
		</script>
		<?
		registra_transaccion("Actualizar Beneficiario (".$rif.")",$login,$fh,$pc,'beneficiarios');
	}
}



if($_POST["guardar"]){
	extract($_POST);
	if($nombre == "" || $rif == ""){
	?>
		<script>
		mostrarMensajes("error", "Disculpe debe escribir por lo menos el Nombre y Rif del Beneficiario");
		</script>
		<?
	}else{
	$fh=date("Y-m-d H:i:s");
	$sql_registrar_beneficiario = mysql_query("insert into beneficiarios(
												nombre,
												rif,
												nro_expediente,
												direccion,
												telefonos,
												url,
												email,
												objeto,
												datos_registro,
												representante_legal,
												cedula_representante,
												telefono_representante,
												persona_autorizada,
												cedula_persona_autorizada,
												telefono_persona_autorizada,
												contribuyente_ordinario,
												idtipo_beneficiario,
												idtipo_sociedad,
												idestado_beneficiario,
												idtipos_persona,
												idtipo_empresa,
												pre_requisitos,
												status,
												usuario,
												fechayhora,
												idestado
														)values(
																'".$nombre."',
																'".$rif."',
																'".$nro_expediente."',
																'".$direccion."',
																'".$telefonos."',
																'".$url."',
																'".$email."',
																'".$objeto."',
																'".$datos_registro."',
																'".$representante_legal."',
																'".$cedula_representante."',
																'".$telefono_representante."',
																'".$persona_autorizada."',
																'".$cedula_persona_autorizada."',
																'".$telefono_persona_autorizada."',
																'".$contribuyente_ordinario."',
																".$idtipo_beneficiario.",
																".$idtipo_sociedad.",
																".$idestado_beneficiario.",
																".$idtipos_persona.",
																".$idtipo_empresa.",
																'".$pre_requisitos."',
																'a',
																'".$login."',
																'".$fh."',
																'".$idestado."'
															)")or die(mysql_error());	
			if($sql_registrar_beneficiario){
				?>
		<script>
		mostrarMensajes("exito", "El nuevo Beneficiario ha sido registrado con Exito");
		</script>
		<?
				registra_transaccion("Ingresar Beneficiario (".$rif.")",$login,$fh,$pc,'beneficiarios');
			}
		}
		}

?>

   

	<body onLoad="document.getElementById('nombre').focus()">
	<br>
	<h4 align=center>Datos B&aacute;sicos Beneficiarios</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
    <style>
		#lineaBeneficiarios:hover{
			background-color: #0099FF;
			
		}
    </style>
    <form method="post" action="" name="form1">
    <table width="301" align="center">
<tr>
        	<td width="61" class="viewPropTitle">Rif / C.I:</td>
          <td width="215"><input name="buscarBeneficiario" type="text" id="buscarBeneficiario">&nbsp;<input type="image" src="imagenes/validar.png">&nbsp; 
     <img src="imagenes/search0.png" border="0" style="cursor:pointer" onClick="window.open('modulos/compromisos/lib/listar_beneficiarios.php?destino=beneficiario','Buscar Beneficiarios','resizable=no, scrollbars=yes, width=900, height = 500')" title="Buscar Beneficiario">
     &nbsp;         
          <?
          if(in_array(284, $privilegios) == true){
			  	if($_SESSION["modulo"] == 4){
				?>
                <a href="principal.php?accion=284&modulo=4"><img src="imagenes/nuevo.png" border="0" title="Nuevo Beneficiario"></a>
		  		<?
				}
				if($_SESSION["modulo"] == 3){
				?>
                <a href="principal.php?accion=83&modulo=3"><img src="imagenes/nuevo.png" border="0" title="Nuevo Beneficiario"></a>
		  		<?
				}
				if($_SESSION["modulo"] == 1){
				?>
			  	
                <a href="principal.php?accion=444&modulo=1"><img src="imagenes/nuevo.png" border="0" title="Nuevo Beneficiario"></a>
		  		<?
				}
				if($_SESSION["modulo"] == 2){
				?>
			  	
                <a href="principal.php?accion=511&modulo=2"><img src="imagenes/nuevo.png" border="0" title="Nuevo Beneficiario"></a>
		  		<?
				}
				if($_SESSION["modulo"] == 12){
				?>
			  	
                <a href="principal.php?accion=609&modulo=12"><img src="imagenes/nuevo.png" border="0" title="Nuevo Beneficiario"></a>
		  		<?
				}
				if($_SESSION["modulo"] == 13){
				?>
			  	
                <a href="principal.php?accion=679&modulo=13"><img src="imagenes/nuevo.png" border="0" title="Nuevo Beneficiario"></a>
		  		<?
				}
				if($_SESSION["modulo"] == 14){
				?>
			  	
                <a href="principal.php?accion=800&modulo=14"><img src="imagenes/nuevo.png" border="0" title="Nuevo Beneficiario"></a>
		  		<?
				}
				if($_SESSION["modulo"] == 16){
				?>
			  	
                <a href="principal.php?accion=890&modulo=16"><img src="imagenes/nuevo.png" border="0" title="Nuevo Beneficiario"></a>
		  		<?
				}
				if($_SESSION["modulo"] == 19){
				?>
			  	
                <a href="principal.php?accion=1033&modulo=16"><img src="imagenes/nuevo.png" border="0" title="Nuevo Beneficiario"></a>
		  		<?
				}
          }
		  ?>
          
          
          </td>
      </tr>
    </table>
    </form>
<form name="formDatosBasicos" method="post" action="" id="form1">
<table align="center" cellpadding=2 cellspacing=0 width="90%">
    <tr>
      <td class='viewPropTitle'><div align="right">Nombre:&nbsp; </div></td>
      <td colspan="3"><input name="nombre" type="text" id="nombre" value="<?php echo $bus_busqueda["nombre"]?>" size="100" maxlength="250" onBlur="validarVacios('nombre', this.value, 'form1')" onKeyUp="validarVacios('nombre', this.value, 'form1'), mostrarLista(this.value)" autocomplete="OFF" style="padding:0px 20px 0px 0px;">
      <label id="mensajeNombre" style="border:#990000 1px solid; color:#990000; font-weight:bold; font-size:11px; display:none; width:150px"></label>      
      <div id="listaBeneficiarios" style="background-color:#FFFFFF; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; color:#000000; display:none; border:1px solid #000000; position:absolute; border-top:0px; width:531px"></div>
      </td>
    </tr>
    <tr>
      <td class='viewPropTitle'><div align="right">Rif / C.I:&nbsp;</div></td>
      <td align="left"><label>
        <input name="rif" type="text" id="rif" size="13" maxlength="10" value="<?php echo $bus_busqueda["rif"]?>" onBlur="consultarRif(this.value)" autocomplete="OFF" style="padding:0px 20px 0px 0px">
        <label id="divMensaje" style="border:#990000 1px solid; color:#990000; font-weight:bold; font-size:11px; display:none; width:150px"></label>
        </label>        </td>
      <td class='viewPropTitle'><div align="right">No. Expediente:</div></td>
      <td><label>
        <input name="nro_expediente" type="text" id="nro_expediente" value="<?php echo $bus_busqueda["nro_expediente"]?>" size="20" maxlength="20">
      </label></td>
    </tr>
    <tr>
      <td class='viewPropTitle'><div align="right">Telefonos:&nbsp; </div></td>
      <td><label>
        <input name="telefonos" type="text" id="telefonos" value="<?php echo $bus_busqueda["telefonos"]?>" size="40" maxlength="100">
      </label></td>
      <td class='viewPropTitle'><div align="right">Email:&nbsp;</div></td>
      <td><label>
        <input name="email" type="text" id="email" maxlength="60" value="<?php echo $bus_busqueda["email"]?>"  onKeyUp="validarEmail('email', this.value)" autocomplete="OFF" style=" padding:0px 20px 0px 0px;">
      </label></td>
    </tr>
    <tr>
      <td class='viewPropTitle'><div align="right">Direcci&oacute;n:&nbsp;</div></td>
      <td colspan="3"><div align="right">
        <label>
        <div align="left">
          <textarea name="direccion" cols="70" rows="2" id="direccion"><?php echo $bus_busqueda["direccion"]?></textarea>
        </div>
        </label>
      </div></td>
    </tr>
    <tr>
      <td class='viewPropTitle'><div align="right">Url:&nbsp;</div></td>
      <td colspan="3"><label>
        <input name="url" type="text" id="url" value="<?php echo $bus_busqueda["url"]?>" size="70" maxlength="200">
      </label></td>
    </tr>
    <tr>
      <td class='viewPropTitle'><div align="right">Objeto:&nbsp;</div></td>
      <td colspan="3"><textarea name="objeto" cols="70" rows="2" id="objeto"><?php echo $bus_busqueda["objeto"]?></textarea>
      <div align="right"></div>        <label></label></td>
    </tr>
    <tr>
      <td class='viewPropTitle'><div align="right">Datos Registro:&nbsp;</div></td>
      <td colspan="3"><label>
        <input name="datos_registro" type="text" id="datos_registro" value="<?php echo $bus_busqueda["datos_registro"]?>" size="70" maxlength="200">
      </label>        <div align="right"></div></td>
    </tr>
    <tr>
      <td class='viewPropTitle'><div align="right">Representante Legal:&nbsp;</div></td>
      <td><label>
        <input name="representante_legal" type="text" id="representante_legal" value="<?php echo $bus_busqueda["representante_legal"]?>" size="50" maxlength="60">
      </label></td>
      <td class='viewPropTitle'><div align="right">C.I Representante Legal:&nbsp;</div></td>
      <td><label>
        <input name="cedula_representante" type="text" id="cedula_representante" size="12" maxlength="12" value="<?php echo $bus_busqueda["cedula_representante"]?>">
      </label></td>
    </tr>
    <tr>
      <td class='viewPropTitle'><div align="right">Tlfn. Representante Legal:&nbsp;</div></td>
      <td><label>
        <input name="telefono_representante" type="text" id="telefono_representante" size="30" maxlength="30" value="<?php echo $bus_busqueda["telefono_representante"]?>">
      </label></td>
      <td align="right" class='viewPropTitle'>Estado en el que recide</td>
      <td>
      <?
	  $sql_configuracion = mysql_query("select * from configuracion");
	  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	  
	  
      $sql_estados = mysql_query("select * from estado");
	  
	  ?>
        <select name="idestado" id="idestado">
        <?
        while($bus_estados = mysql_fetch_array($sql_estados)){
		?>
		<option <? if($bus_configuracion["estado"] == $bus_estados["idestado"] and $bus_busqueda["estado"] == ''){echo "selected";} if($bus_busqueda["estado"] == $bus_estados["idestado"]) {echo "selected";}?> value="<?=$bus_estados["idestado"]?>">(<?=$bus_estados["codigo"]?>)&nbsp;<?=$bus_estados["denominacion"]?></option>
		<?
		}
		?>
        </select>
      </td>
    </tr>
    <tr>
      <td class='viewPropTitle'><div align="right">Persona Autorizada:&nbsp;</div></td>
      <td><label>
        <input name="persona_autorizada" type="text" id="persona_autorizada" value="<?php echo $bus_busqueda["persona_autorizada"]?>" size="50" maxlength="60">
      </label></td>
      <td class='viewPropTitle'><div align="right">C.I Persona Autorizada:&nbsp;</div></td>
      <td><label>
        <input name="cedula_persona_autorizada" type="text" id="cedula_persona_autorizada" size="12" maxlength="12" value="<?php echo $bus_busqueda["cedula_persona_autorizada"]?>">
      </label></td>
    </tr>
    <tr>
      <td class='viewPropTitle'><div align="right">Tlfn. Persona Autorizada:&nbsp;</div></td>
      <td><label>
        <input name="telefono_persona_autorizada" type="text" id="telefono_persona_autorizada" size="30" maxlength="30" value="<?php echo $bus_busqueda["telefono_persona_autorizada"]?>">
      </label></td>
      <td class='viewPropTitle'><div align="right">Contribuyente Ordinario</div></td>
      <td><label>
        <input type="checkbox" name="contribuyente_ordinario" id="contribuyente_ordinario" <? if($bus_busqueda["contribuyente_ordinario"] == 'si'){echo "checked";}?> value='si'>
      </label></td>
    </tr>
    <tr>
      <td class='viewPropTitle'><div align="right">Tipo de Beneficiario:&nbsp;</div></td>
      <td><label>
      <select name="idtipo_beneficiario" id="idtipo_beneficiario">
		<?php
        $sql_tipo_beneficiario = mysql_query("select * from tipo_beneficiario order by idtipo_beneficiario ASC");
		while($bus_tipo_beneficiario=mysql_fetch_array($sql_tipo_beneficiario)){
			?>
			<option <?php if($bus_busqueda["idtipo_beneficiario"] == $bus_tipo_beneficiario["idtipo_beneficiario"]){ echo "selected='selected'";} ?> value="<?php echo $bus_tipo_beneficiario["idtipo_beneficiario"]?>"><?php echo $bus_tipo_beneficiario["descripcion"]?></option>
			<?php
		}
		?>
       </select>
      </label>
      <a href="#" onClick="
      
      <?
      if($_SESSION["modulo"] == 4){
	  ?>
      window.open('principal.php?modulo=4&accion=292&pop=si','agregar tipo beneficiario','width=900, height = 500, scrollbars = yes')
      <?
      }
      if($_SESSION["modulo"] == 12){
	  ?>
      window.open('principal.php?modulo=12&accion=617&pop=si','agregar tipo beneficiario','width=900, height = 500, scrollbars = yes')
      <?
      }
	  if($_SESSION["modulo"] == 13){
	  ?>
      window.open('principal.php?modulo=13&accion=687&pop=si','agregar tipo beneficiario','width=900, height = 500, scrollbars = yes')
      <?
      }
	  if($_SESSION["modulo"] == 14){
	  ?>
      window.open('principal.php?modulo=14&accion=808&pop=si','agregar tipo beneficiario','width=900, height = 500, scrollbars = yes')
      <?
      }
	  if($_SESSION["modulo"] == 16){
	  ?>
      window.open('principal.php?modulo=16&accion=898&pop=si','agregar tipo beneficiario','width=900, height = 500, scrollbars = yes')
      <?
      }
	  if($_SESSION["modulo"] == 19){
	  ?>
      window.open('principal.php?modulo=19&accion=1035&pop=si','agregar tipo beneficiario','width=900, height = 500, scrollbars = yes')
      <?
      }
	  ?>
      ">
      <img src="imagenes/add.png">
      </a>
      </td>
      <td class='viewPropTitle'><div align="right">Tipo de Sociedad:&nbsp;</div></td>
      <td><label>
       <select name="idtipo_sociedad" id="idtipo_sociedad">
      	<?php
        $sql_tipo_sociedad = mysql_query("select * from tipo_sociedad order by  idtipo_sociedad ASC");
		while($bus_tipo_sociedad=mysql_fetch_array($sql_tipo_sociedad)){
			?>
			<option <?php if($bus_busqueda["idtipo_sociedad"] == $bus_tipo_sociedad["idtipo_sociedad"]){ echo "selected='selected'";} ?> value="<?php echo $bus_tipo_sociedad["idtipo_sociedad"]?>"><?php echo $bus_tipo_sociedad["descripcion"]?></option>
			<?php
		}
		?>
       </select>
      </label>
      <a href="#" onClick="
      <?
      if($_SESSION["modulo"] == 4){
	  ?>
      window.open('principal.php?modulo=4&accion=304&pop=true','Tipo de Sociedad','width=500, height=600, scrollbars = yes')
      <?
      }
	  if($_SESSION["modulo"] == 12){
	  ?>
      window.open('principal.php?modulo=12&accion=617&pop=si','agregar tipo Sociedad','width=900, height = 500, scrollbars = yes')
      <?
      }
	  if($_SESSION["modulo"] == 13){
	  ?>
      window.open('principal.php?modulo=13&accion=699&pop=si','agregar tipo Sociedad','width=900, height = 500, scrollbars = yes')
      <?
      }
	  if($_SESSION["modulo"] == 14){
	  ?>
      window.open('principal.php?modulo=14&accion=820&pop=si','agregar tipo Sociedad','width=900, height = 500, scrollbars = yes')
      <?
      }
	  if($_SESSION["modulo"] == 16){
	  ?>
      window.open('principal.php?modulo=16&accion=910&pop=si','agregar tipo Sociedad','width=900, height = 500, scrollbars = yes')
      <?
      }
	  if($_SESSION["modulo"] == 19){
	  ?>
      window.open('principal.php?modulo=19&accion=1038&pop=si','agregar tipo Sociedad','width=900, height = 500, scrollbars = yes')
      <?
      }
	  ?>
      ">
      <img src="imagenes/add.png">
      </a>
      </td>
    </tr>
    <tr>
      <td class='viewPropTitle'><div align="right">Estado del Beneficiario:&nbsp;</div></td>
      <td><label>
        
       <select name="idestado_beneficiario" id="idestado_beneficiario">

      	<?php
        $sql_estado_beneficiario = mysql_query("select * from estado_beneficiario order by idestado_beneficiario ASC");
		while($bus_estado_beneficiario=mysql_fetch_array($sql_estado_beneficiario)){
			?>
			<option <?php if($bus_busqueda["idestado_beneficiario"] == $bus_estado_beneficiario["idestado_beneficiario"]){ echo "selected='selected'";} ?> value="<?php echo $bus_estado_beneficiario["idestado_beneficiario"]?>"><?php echo $bus_estado_beneficiario["descripcion"]?></option>
			<?php
		}
		?>
       </select>
      </label>
      <a href="#" onClick="
      <?
      if($_SESSION["modulo"] == 4){
	  ?>
      window.open('principal.php?modulo=4&accion=296&pop=true','Estado del Beneficiario','width=900, height = 500, scrollbars = yes')
      <?
      }
	  if($_SESSION["modulo"] == 12){
	  ?>
      window.open('principal.php?modulo=12&accion=621&pop=true','Estado del Beneficiario','width=900, height = 500, scrollbars = yes')
      <?
	  }
	  if($_SESSION["modulo"] == 13){
	  ?>
      window.open('principal.php?modulo=13&accion=691&pop=true','Estado del Beneficiario','width=900, height = 500, scrollbars = yes')
      <?
	  }
	  if($_SESSION["modulo"] == 14){
	  ?>
      window.open('principal.php?modulo=14&accion=812&pop=true','Estado del Beneficiario','width=900, height = 500, scrollbars = yes')
      <?
	  }
	  if($_SESSION["modulo"] == 16){
	  ?>
      window.open('principal.php?modulo=16&accion=902&pop=true','Estado del Beneficiario','width=900, height = 500, scrollbars = yes')
      <?
	  }
	  if($_SESSION["modulo"] == 19){
	  ?>
      window.open('principal.php?modulo=19&accion=1036&pop=true','Estado del Beneficiario','width=900, height = 500, scrollbars = yes')
      <?
	  }
	  ?>
      ">
      <img src="imagenes/add.png">
      </a>
      </td>
      <td class='viewPropTitle'><div align="right">Tipo de Persona:&nbsp;</div></td>
      <td><label>
       <select name="idtipos_persona" id="idtipos_persona">
      	<?php
        $sql_tipos_persona = mysql_query("select * from tipos_persona order by idtipos_persona ASC");
		while($bus_tipos_persona=mysql_fetch_array($sql_tipos_persona)){
			?>
			<option <?php if($bus_busqueda["idtipos_persona"] == $bus_tipos_persona["idtipos_persona"]){ echo "selected='selected'";} ?> value="<?php echo $bus_tipos_persona["idtipos_persona"]?>"><?php echo $bus_tipos_persona["descripcion"]?></option>
			<?php
		}
		?>
       </select>
      </label>
      <a href="#" onClick="
      <?
      if($_SESSION["modulo"] == 4){
	  ?>
      window.open('principal.php?modulo=4&accion=300&pop=true','Tipo de Persona','width=900, height = 500, scrollbars = yes')
      <?
      }
	  if($_SESSION["modulo"] == 12){
	  ?>
      window.open('principal.php?modulo=12&accion=625&pop=true','Tipo de Persona','width=900, height = 500, scrollbars = yes')
      <?
      }
	  if($_SESSION["modulo"] == 13){
	  ?>
      window.open('principal.php?modulo=13&accion=695&pop=true','Tipo de Persona','width=900, height = 500, scrollbars = yes')
      <?
      }
	  if($_SESSION["modulo"] == 14){
	  ?>
      window.open('principal.php?modulo=14&accion=816&pop=true','Tipo de Persona','width=900, height = 500, scrollbars = yes')
      <?
      }
	  if($_SESSION["modulo"] == 16){
	  ?>
      window.open('principal.php?modulo=16&accion=906&pop=true','Tipo de Persona','width=900, height = 500, scrollbars = yes')
      <?
      }
	  if($_SESSION["modulo"] == 19){
	  ?>
      window.open('principal.php?modulo=19&accion=1037&pop=true','Tipo de Persona','width=900, height = 500, scrollbars = yes')
      <?
      }
	  ?>
      ">
      <img src="imagenes/add.png">
      </a>
      </td>
    </tr>
    <tr>
      <td class='viewPropTitle'><div align="right">Tipo de Empresa:&nbsp;</div></td>
      <td><label>
       <select name="idtipo_empresa" id="idtipo_empresa">
      	<?php
        $sql_tipo_empresa = mysql_query("select * from tipo_empresa order by idtipo_empresa ASC");
		while($bus_tipo_empresa=mysql_fetch_array($sql_tipo_empresa)){
			?>
			<option <?php if($bus_busqueda["idtipo_empresa"] == $bus_tipo_empresa["idtipo_empresa"]){ echo "selected='selected'";} ?> value="<?php echo $bus_tipo_empresa["idtipo_empresa"]?>"><?php echo "(".$bus_tipo_empresa["codigo"].")".$bus_tipo_empresa["descripcion"]?></option>
			<?php
		}
		?>
       </select>
      </label>
      <a href="#" onClick="
      <?
      if($_SESSION["modulo"] == 4){
	  ?>
      window.open('principal.php?modulo=4&accion=308&pop=true','Tipo Empresa','width=900, height = 500, scrollbars = yes, resizable=no')
      <?
      }
	  if($_SESSION["modulo"] == 12){
	  ?>
      window.open('principal.php?modulo=12&accion=633&pop=true','Tipo Empresa','width=900, height = 500, scrollbars = yes, resizable=no')
      <?
      }
	  if($_SESSION["modulo"] == 13){
	  ?>
      window.open('principal.php?modulo=13&accion=703&pop=true','Tipo Empresa','width=900, height = 500, scrollbars = yes, resizable=no')
      <?
      }
	  if($_SESSION["modulo"] == 14){
	  ?>
      window.open('principal.php?modulo=14&accion=824&pop=true','Tipo Empresa','width=900, height = 500, scrollbars = yes, resizable=no')
      <?
      }
	  if($_SESSION["modulo"] == 16){
	  ?>
      window.open('principal.php?modulo=16&accion=914&pop=true','Tipo Empresa','width=900, height = 500, scrollbars = yes, resizable=no')
      <?
      }
	  if($_SESSION["modulo"] == 19){
	  ?>
      window.open('principal.php?modulo=19&accion=1039&pop=true','Tipo Empresa','width=900, height = 500, scrollbars = yes, resizable=no')
      <?
      }
	  ?>
      ">
      <img src="imagenes/add.png">
      </a>
      </td>
      <td class='viewPropTitle'><div align="right">Pre Requisitos:&nbsp;</div></td>
      <td><label>
        <input name="pre_requisitos" type="checkbox" id="pre_requisitos" value="1" <?php if($bus_busqueda["pre_requisitos"] != ""){echo "checked='checked'";}?> size="1" maxlength="1">
      </label></td>
    </tr>
    <tr>
      <td><input type="hidden" value="<?php echo $bus_busqueda["idbeneficiarios"]?>" name="id_beneficiario" id="id_beneficiario"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><label>
        <div align="right">

        
<?php 
		 if($_SESSION["modulo"] == 4){
			 if($encontroRegistros > 0 and in_array(285, $privilegios) == true){
				  ?>
				  <input  value='Modificar' name='modificar' id="modificar" type='submit'  class="button">
				  <?
			  }
			  if($encontroRegistros == 0 and in_array(286, $privilegios) == true){
				  ?>
				  <input  name='guardar' id='guardar' value='Ingresar' type='submit' class="button">
				  <?
			  }
			  if($encontroRegistros > 0 and in_array(287, $privilegios) == true){
				  ?>
				  <input name="eliminar" type="submit" value="Eliminar" class="button">
				  <?
			  }
		 }
		 if($_SESSION["modulo"] == 3){
			 if($encontroRegistros > 0 and in_array(88, $privilegios) == true){
				  ?>
				  <input  value='Modificar' name='modificar' id="modificar" type='submit'  class="button">
				  <?
			  }
			  if($encontroRegistros == 0 and in_array(89, $privilegios) == true){
				  ?>
				  <input  name='guardar' id='guardar' value='Ingresar' type='submit' class="button">
				  <?
			  }
			  if($encontroRegistros > 0 and in_array(90, $privilegios) == true){
				  ?>
				  <input name="eliminar" type="submit" value="Eliminar" class="button">
				  <?
			  }
		 }
		 if($_SESSION["modulo"] == 1){
			 if($encontroRegistros > 0 and in_array(445, $privilegios) == true){
				  ?>
				  <input  value='Modificar' name='modificar' id="modificar" type='submit'  class="button">
				  <?
			  }
			  if($encontroRegistros == 0 and in_array(446, $privilegios) == true){
				  ?>
				  <input  name='guardar' id='guardar' value='Ingresar' type='submit' class="button">
				  <?
			  }
			  if($encontroRegistros > 0 and in_array(447, $privilegios) == true){
				  ?>
				  <input name="eliminar" type="submit" value="Eliminar" class="button">
				  <?
			  }
		 }
		 if($_SESSION["modulo"] == 2){
			 if($encontroRegistros > 0 and in_array(513, $privilegios) == true){
				  ?>
				  <input  value='Modificar' name='modificar' id="modificar" type='submit'  class="button">
				  <?
			  }
			  if($encontroRegistros == 0 and in_array(512, $privilegios) == true){
				  ?>
				  <input  name='guardar' id='guardar' value='Ingresar' type='submit' class="button">
				  <?
			  }
			  if($encontroRegistros > 0 and in_array(514, $privilegios) == true){
				  ?>
				  <input name="eliminar" type="submit" value="Eliminar" class="button">
				  <?
			  }
		 }
		 
		 
		 if($_SESSION["modulo"] == 12){
			 if($encontroRegistros > 0 and in_array(611, $privilegios) == true){
				  ?>
				  <input  value='Modificar' name='modificar' id="modificar" type='submit'  class="button">
				  <?
			  }
			  if($encontroRegistros == 0 and in_array(610, $privilegios) == true){
				  ?>
				  <input  name='guardar' id='guardar' value='Ingresar' type='submit' class="button">
				  <?
			  }
			  if($encontroRegistros > 0 and in_array(612, $privilegios) == true){
				  ?>
				  <input name="eliminar" type="submit" value="Eliminar" class="button">
				  <?
			  }
		 }
		 
		 
		 
		 
		 if($_SESSION["modulo"] == 14){
			 if($encontroRegistros > 0 and in_array(802, $privilegios) == true){
				  ?>
				  <input  value='Modificar' name='modificar' id="modificar" type='submit'  class="button">
				  <?
			  }
			  if($encontroRegistros == 0 and in_array(801, $privilegios) == true){
				  ?>
				  <input  name='guardar' id='guardar' value='Ingresar' type='submit' class="button">
				  <?
			  }
			  if($encontroRegistros > 0 and in_array(803, $privilegios) == true){
				  ?>
				  <input name="eliminar" type="submit" value="Eliminar" class="button">
				  <?
			  }
		 }
		 
		 
		 
		 if($_SESSION["modulo"] == 13){
			 if($encontroRegistros > 0 and in_array(681, $privilegios) == true){
				  ?>
				  <input  value='Modificar' name='modificar' id="modificar" type='submit'  class="button">
				  <?
			  }
			  if($encontroRegistros == 0 and in_array(680, $privilegios) == true){
				  ?>
				  <input  name='guardar' id='guardar' value='Ingresar' type='submit' class="button">
				  <?
			  }
			  if($encontroRegistros > 0 and in_array(682, $privilegios) == true){
				  ?>
				  <input name="eliminar" type="submit" value="Eliminar" class="button">
				  <?
			  }
		 }
		 
		 
		 if($_SESSION["modulo"] == 16){
			 if($encontroRegistros > 0 and in_array(892, $privilegios) == true){
				  ?>
				  <input  value='Modificar' name='modificar' id="modificar" type='submit'  class="button">
				  <?
			  }
			  if($encontroRegistros == 0 and in_array(891, $privilegios) == true){
				  ?>
				  <input  name='guardar' id='guardar' value='Ingresar' type='submit' class="button">
				  <?
			  }
			  if($encontroRegistros > 0 and in_array(893, $privilegios) == true){
				  ?>
				  <input name="eliminar" type="submit" value="Eliminar" class="button">
				  <?
			  }
		 }


		 if($_SESSION["modulo"] == 19){
			 if($encontroRegistros > 0 and in_array(1042, $privilegios) == true){
				  ?>
				  <input  value='Modificar' name='modificar' id="modificar" type='submit'  class="button">
				  <?
			  }
			  if($encontroRegistros == 0 and in_array(1041, $privilegios) == true){
				  ?>
				  <input  name='guardar' id='guardar' value='Ingresar' type='submit' class="button">
				  <?
			  }
			  if($encontroRegistros > 0 and in_array(1043, $privilegios) == true){
				  ?>
				  <input name="eliminar" type="submit" value="Eliminar" class="button">
				  <?
			  }
		 }
		  ?>
          </div>
      </label></td>
      <td><label>
        <input name="limpiar" type="reset" class="button"  value="Reiniciar" id="reiniciar">
      </label></td>
      <td>&nbsp;</td>
    </tr>
  </table>
</form>
</body>

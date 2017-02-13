<?

if($_POST["ingresar"]){
$_GET["accion"] = 289;
}
if($_POST["ingresar2"]){
$_GET["accion"] = 227;
}
if($_POST["ingresar3"]){
$_GET["accion"] = 473;
}
if($_POST["ingresar4"]){
$_GET["accion"] = 540;
}
if($_POST["ingresar5"]){
$_GET["accion"] = 614;
}
if($_POST["ingresar6"]){
$_GET["accion"] = 684;
}
if($_POST["ingresar7"]){
$_GET["accion"] = 805;
}
if($_POST["ingresar8"]){
$_GET["accion"] = 895;
}
if($_POST["ingresar9"]){
$_GET["accion"] = 1044;
}

if($_REQUEST["cedula"]){
	$sql_consulta = mysql_query("select * from beneficiarios where rif = '".$_REQUEST["cedula"]."'");
	$bus_consulta = mysql_fetch_array($sql_consulta);
}


if($_REQUEST["id"]){
	$sql_id = mysql_query("select * from documento_entregado_beneficiario where iddocumento_consignado_beneficiario = ".$_REQUEST["id"]."");
	$bus_id = mysql_fetch_array($sql_id);
}

?>
<body>
<br>
<h2 align="center"><strong>Documentos Entregados</strong></h2>
<h2 class="sqlmVersion"></h2>
<br>
<form id="form" name="form" method="post" action="principal.php?modulo=<?=$_SESSION["modulo"]?>&accion=<?=$_GET["accion"]?>">
  <table width="55%" border="0" align="center">

    <tr>
      <td width="21%">&nbsp;</td>
      <td colspan="2" class='viewPropTitle'><div align="center">Cedula  o R.I.F.:
          <input type="text" name="cedula" id="cedula" value="<?=$bus_consulta["rif"]?>" />
          &nbsp;
          <img src="imagenes/validar.png" border="0" onClick="document.form.submit()" style="cursor:pointer" title="Consultar Beneficiario">&nbsp;
          <img src="imagenes/search0.png" border="0" style="cursor:pointer" onClick="
          <?
          if($_SESSION["modulo"] == 3){
		  ?>
          window.open('modulos/administracion/lib/listar_beneficiarios.php?destino=documentos','listar documentos entregados','resizable=no, scrollbars=yes, width=900, height = 500')
          <?
          }else if($_SESSION["modulo"] == 12){
		  ?>
          window.open('modulos/administracion/lib/listar_beneficiarios.php?destino=despacho','listar documentos entregados','resizable=no, scrollbars=yes, width=900, height = 500')
          <?
		  }
		  ?>
          " title="Buscar Beneficiario">&nbsp;
          
          <?
          if($_SESSION["modulo"] == 4){
		  ?>
          <a href="principal.php?accion=288&modulo=4"><img src="imagenes/nuevo.png" border="0" title="Nuevo Documento Entregado"></a>
          <?
          }
		  if($_SESSION["modulo"] == 3){
		  ?>
          <a href="principal.php?accion=84&modulo=3"><img src="imagenes/nuevo.png" border="0" title="Nuevo Documento Entregado"></a>
          <?
          }
		  if($_SESSION["modulo"] == 1){
		  ?>
          <a href="principal.php?accion=472&modulo=1"><img src="imagenes/nuevo.png" border="0" title="Nuevo Documento Entregado"></a>
          <?
          }
		  if($_SESSION["modulo"] == 2){
		  ?>
          <a href="principal.php?accion=539&modulo=2"><img src="imagenes/nuevo.png" border="0" title="Nuevo Documento Entregado"></a>
          <?
          }
		  if($_SESSION["modulo"] == 12){
		  ?>
          <a href="principal.php?accion=613&modulo=12"><img src="imagenes/nuevo.png" border="0" title="Nuevo Documento Entregado"></a>
          <?
          }
		  if($_SESSION["modulo"] == 14){
		  ?>
          <a href="principal.php?accion=804&modulo=14"><img src="imagenes/nuevo.png" border="0" title="Nuevo Documento Entregado"></a>
          <?
          }
		  if($_SESSION["modulo"] == 13){
		  ?>
          <a href="principal.php?accion=683&modulo=13"><img src="imagenes/nuevo.png" border="0" title="Nuevo Documento Entregado"></a>
          <?
          }
		  if($_SESSION["modulo"] == 16){
		  ?>
          <a href="principal.php?accion=894&modulo=16"><img src="imagenes/nuevo.png" border="0" title="Nuevo Documento Entregado"></a>
          <?
          }
		  if($_SESSION["modulo"] == 19){
		  ?>
          <a href="principal.php?accion=1034&modulo=19"><img src="imagenes/nuevo.png" border="0" title="Nuevo Documento Entregado"></a>
          <?
          }
		  ?>
          
          
          &nbsp;
       </td>
      <td width="21%">&nbsp;</td>
    </tr>
    <tr>
      <td class='viewPropTitle'><div align="right">Nombre&nbsp;</div></td>
      <td width="29%"><input type="text" name="nombre_beneficiario" id="nombre_beneficiario" disabled value="<?=$bus_consulta["nombre"]?>"/></td>
      <td width="29%" class='viewPropTitle'><div align="right">Telefono&nbsp;</div></td>
      <td>
      <input type="text" name="apellido_beneficiario" id="apellido_beneficiario" disabled value="<?=$bus_consulta["telefonos"]?>"/>
      <input type="hidden" name="idbeneficiarios" id="idbeneficiarios" value="<?=$bus_consulta["idbeneficiarios"]?>"/>
      <input type="hidden" name="id" id="id" value="<?=$_REQUEST["id"]?>"/>
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>


	<h2 class="sqlmVersion"></h2>
<br>


  <table width="70%" border="0" align="center">

    <tr>
      <td width="23%" class='viewPropTitle'><div align="right">Documento:&nbsp;
      </div>
        <label></label></td>
      <td colspan="3">
<label>      <select name="documento" id="documento">
      	<?
        $sql_documentos = mysql_query("select * from documentos_requeridos");
		$num_documentos = mysql_num_rows($sql_documentos);
		while($bus_documentos =mysql_fetch_array($sql_documentos)){
		?>
			<option <? if($bus_id["iddocumentos_requeridos"] == $bus_documentos["iddocumentos_requeridos"]){echo "selected";}?> value=<?=$bus_documentos["iddocumentos_requeridos"]?>><?=$bus_documentos["descripcion"]?></option>
		<?
		}
		?>
      </select> </label>
      
      <a href="#" onClick="
      <?
      if($_SESSION["modulo"] == 3){
	  ?>
      window.open('principal.php?modulo=3&accion=75&pop=si','nuevo documento','width=900, height = 500, scrollbars = yes')
      <?
      }
	  if($_SESSION["modulo"] == 12){
	  ?>
      window.open('principal.php?modulo=12&accion=637&pop=si','nuevo documento','width=900, height = 500, scrollbars = yes')
      <?
      }
	  if($_SESSION["modulo"] == 14){
	  ?>
      window.open('principal.php?modulo=14&accion=828&pop=si','nuevo documento','width=900, height = 500, scrollbars = yes')
      <?
      }
	  if($_SESSION["modulo"] == 13){
	  ?>
      window.open('principal.php?modulo=13&accion=707&pop=si','nuevo documento','width=900, height = 500, scrollbars = yes')
      <?
      }
	  if($_SESSION["modulo"] == 16){
	  ?>
      window.open('principal.php?modulo=16&accion=918&pop=si','nuevo documento','width=900, height = 500, scrollbars = yes')
      <?
      }
	  if($_SESSION["modulo"] == 19){
	  ?>
      window.open('principal.php?modulo=19&accion=1040&pop=si','nuevo documento','width=900, height = 500, scrollbars = yes')
      <?
      }
	  ?>
      ">
      <img src="imagenes/add.png" title="Agregar Nuevo Documento">
      </a>
      </td>
    </tr>
    <tr>
      <td class='viewPropTitle' align="right">No. Comprobante 
        <label></label></td>
      <td width="36%"><input type="text" name="nro_comprobante" id="nro_comprobante" value="<?=$bus_id["nro_comprobante"]?>" size="20"></td>
      <td>&nbsp;</td>
      <td width="18%">&nbsp;</td>
    </tr>
    <tr>
      <td align="right" class='viewPropTitle'>Fecha de Emisi&oacute;n:</td>
      <td><input name="fecha_emision" type="text" id="fecha_emision" size="13" maxlength="10" value="<?=$bus_id["fecha_emision"]?>">
          <img src="imagenes/jscalendar0.gif" alt="" name="f_trigger_c" width="16" height="16" id="f_trigger_c" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
          <script type="text/javascript">
							Calendar.setup({
							inputField    : "fecha_emision",
							button        : "f_trigger_c",
							align         : "Tr",
							ifFormat      : "%Y-%m-%d"
							});
						</script></td>
      <td class='viewPropTitle' align="right">Fecha de Vencimiento:&nbsp;</td>
      <td><input name="fecha_vencimiento" type="text" id="fecha_vencimiento" size="13" maxlength="10" value="<?=$bus_id["fecha_vencimiento"]?>">
          <img src="imagenes/jscalendar0.gif" alt="" name="f_trigger_c2" width="16" height="16" id="f_trigger_c2" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
          <script type="text/javascript">
							Calendar.setup({
							inputField    : "fecha_vencimiento",
							button        : "f_trigger_c2",
							align         : "Tr",
							ifFormat    	: "%Y-%m-%d"
							});
						</script>
      </td>
    </tr>
    <tr>
      <td><div align="right"><span class="viewPropTitle">Verificado Por:&nbsp;</span></div></td>
      <td colspan="2"><input name="verificador_por" type="text" id="verificador_por" value="<?=$bus_id["verificador_por"]?>" size="45" /></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2">
      
      			<div align="center">
      			  <?php
				if($_SESSION["modulo"] == 4){
					if($_GET["accion"] != 290 and $_GET["accion"] != 291 and in_array(289, $privilegios) == true and $num_documentos > 1){
						?>
                        <input align=center class='button' name='ingresar' type='submit' value='Ingresar'>
                        <?
					}
				
					if($_GET["accion"] == 290 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
                        <?
					}
				
					if($_GET["accion"] == 291 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
						<?
					}
				}
				if($_SESSION["modulo"] == 3){
					if($_GET["accion"] != 228 and $_GET["accion"] != 229 and in_array(227, $privilegios) == true and $num_documentos > 1){
						?>
                        <input align=center class='button' name='ingresar2' type='submit' value='Ingresar'>
                        <?
					}
				
					if($_GET["accion"] == 228 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
                        <?
					}
				
					if($_GET["accion"] == 229 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
                        <?
					}
				}
				if($_SESSION["modulo"] == 1){
					if($_GET["accion"] != 474 and $_GET["accion"] != 475 and in_array(473, $privilegios) == true and $num_documentos > 1){
						?>
                        <input align=center class='button' name='ingresar3' type='submit' value='Ingresar'>
                        <?
					}
				
					if($_GET["accion"] == 474 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
                        <?
					}
				
					if($_GET["accion"] == 475 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
                        <?
					}
				}
				if($_SESSION["modulo"] == 2){
					if($_GET["accion"] != 541 and $_GET["accion"] != 542 and in_array(540, $privilegios) == true and $num_documentos > 1){
						?>
                        <input align=center class='button' name='ingresar4' type='submit' value='Ingresar'>
                        <?
					}
				
					if($_GET["accion"] == 541 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
                        <?
					}
				
					if($_GET["accion"] == 542 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
                        <?
					}
				}
				
				if($_SESSION["modulo"] == 12){
					if($_GET["accion"] != 615 and $_GET["accion"] != 616 and in_array(614, $privilegios) == true and $num_documentos > 1){
						?>
                        <input align=center class='button' name='ingresar5' type='submit' value='Ingresar'>
                        <?
					}
				
					if($_GET["accion"] == 615 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
                        <?
					}
				
					if($_GET["accion"] == 616 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
                        <?
					}
				}
				if($_SESSION["modulo"] == 13){
					if($_GET["accion"] != 685 and $_GET["accion"] != 686 and in_array(684, $privilegios) == true and $num_documentos > 1){
						?>
                        <input align=center class='button' name='ingresar6' type='submit' value='Ingresar'>
                        <?
					}
				
					if($_GET["accion"] == 685 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
                        <?
					}
				
					if($_GET["accion"] == 686 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
                        <?
					}
				}
				
				if($_SESSION["modulo"] == 14){
					if($_GET["accion"] != 806 and $_GET["accion"] != 807 and in_array(805, $privilegios) == true and $num_documentos > 1){
						?>
                        <input align=center class='button' name='ingresar7' type='submit' value='Ingresar'>
                        <?
					}
				
					if($_GET["accion"] == 806 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
                        <?
					}
				
					if($_GET["accion"] == 807 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
                        <?
					}
				}
				
				
				if($_SESSION["modulo"] == 16){
					if($_GET["accion"] != 896 and $_GET["accion"] != 897 and in_array(895, $privilegios) == true and $num_documentos > 1){
						?>
                        <input align=center class='button' name='ingresar8' type='submit' value='Ingresar'>
                        <?
					}
				
					if($_GET["accion"] == 896 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
                        <?
					}
				
					if($_GET["accion"] == 897 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
                        <?
					}
				}
				
				if($_SESSION["modulo"] == 19){
					if($_GET["accion"] != 1045 and $_GET["accion"] != 1046 and in_array(1044, $privilegios) == true and $num_documentos > 1){
						?>
                        <input align=center class='button' name='ingresar9' type='submit' value='Ingresar'>
                        <?
					}
				
					if($_GET["accion"] == 1045 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' type='submit' value='Modificar'>
                        <?
					}
				
					if($_GET["accion"] == 1046 and in_array($_GET["accion"], $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' type='submit' value='Eliminar'>
                        <?
					}
				}
			?>
      			  <input type="reset" value="Reiniciar" class="button">
                </div></td>
      <td>&nbsp;</td>
    </tr>
  </table>
</form>


	<br><br>
<h2 class="sqlmVersion"></h2>
	<?
    if($_REQUEST["cedula"] and isset($bus_consulta["idbeneficiarios"])){
    ?>
        <table width="60%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
          <thead>
          <tr>
            <td class="Browse"><div align="center">Documento</div></td>
            <td class="Browse">Nro. Comprobante</td>
            <td class="Browse"><div align="center">Emision</div></td>
            <td class="Browse"><div align="center">Vencimiento</div></td>
            <td class="Browse"><div align="center">Verificado</div></td>
            <td class="Browse" colspan="2"><div align="center">Acciones</div></td>
          </tr>
          <thead>
          <? 
          $sql_documentos_entregados = mysql_query("select * from documento_entregado_beneficiario, documentos_requeridos where documento_entregado_beneficiario.idbeneficiarios = ".$bus_consulta["idbeneficiarios"]." and documento_entregado_beneficiario.iddocumentos_requeridos = documentos_requeridos.iddocumentos_requeridos");
          while($bus_documentos_entregados = mysql_fetch_array($sql_documentos_entregados)){
          ?>
          <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
            <td class="Browse"><?=$bus_documentos_entregados["descripcion"]?></td>
            <td class="Browse"><?=$bus_documentos_entregados["nro_comprobante"]?></td>
            <td class="Browse"><?=$bus_documentos_entregados["fecha_emision"]?></td>
            <td class="Browse"><?=$bus_documentos_entregados["fecha_vencimiento"]?></td>
            <td class="Browse"><?=$bus_documentos_entregados["verificador_por"]?></td>
            <?
            if($_SESSION["modulo"] == 4){
				if(in_array(290,$privilegios) == true){
					?>
					<td class="Browse" align="center"><a href="principal.php?modulo=4&accion=290&id=<?=$bus_documentos_entregados["iddocumento_consignado_beneficiario"]?>&cedula=<?=$_REQUEST["cedula"]?>"><img src="imagenes/modificar.png"></a></td>
					<?
				}
				if(in_array(291, $privilegios) == true){
					?>
					<td class="Browse" align="center"><a href="principal.php?modulo=4&accion=291&id=<?=$bus_documentos_entregados["iddocumento_consignado_beneficiario"]?>&cedula=<?=$_REQUEST["cedula"]?>"><img src="imagenes/delete.png"></a></td>
					<?
				}
			}
			 
			 
			 if($_SESSION["modulo"] == 3){
				if(in_array(228,$privilegios) == true){
					?>
					<td class="Browse" align="center"><a href="principal.php?modulo=3&accion=228&id=<?=$bus_documentos_entregados["iddocumento_consignado_beneficiario"]?>&cedula=<?=$_REQUEST["cedula"]?>"><img src="imagenes/modificar.png"></a></td>
					<?
				}
				if(in_array(229, $privilegios) == true){
					?>
					<td class="Browse" align="center"><a href="principal.php?modulo=3&accion=229&id=<?=$bus_documentos_entregados["iddocumento_consignado_beneficiario"]?>&cedula=<?=$_REQUEST["cedula"]?>"><img src="imagenes/delete.png"></a></td>
					<?
				}
			}
			
			
			if($_SESSION["modulo"] == 1){
				if(in_array(474,$privilegios) == true){
					?>
					<td class="Browse" align="center"><a href="principal.php?modulo=1&accion=474&id=<?=$bus_documentos_entregados["iddocumento_consignado_beneficiario"]?>&cedula=<?=$_REQUEST["cedula"]?>"><img src="imagenes/modificar.png"></a></td>
					<?
				}
				if(in_array(475, $privilegios) == true){
					?>
					<td class="Browse" align="center"><a href="principal.php?modulo=1&accion=475&id=<?=$bus_documentos_entregados["iddocumento_consignado_beneficiario"]?>&cedula=<?=$_REQUEST["cedula"]?>"><img src="imagenes/delete.png"></a></td>
					<?
				}
			}
			
			
			if($_SESSION["modulo"] == 2){
				if(in_array(541,$privilegios) == true){
					?>
					<td class="Browse" align="center"><a href="principal.php?modulo=2&accion=541&id=<?=$bus_documentos_entregados["iddocumento_consignado_beneficiario"]?>&cedula=<?=$_REQUEST["cedula"]?>"><img src="imagenes/modificar.png"></a></td>
					<?
				}
				if(in_array(542, $privilegios) == true){
					?>
					<td class="Browse" align="center"><a href="principal.php?modulo=2&accion=542&id=<?=$bus_documentos_entregados["iddocumento_consignado_beneficiario"]?>&cedula=<?=$_REQUEST["cedula"]?>"><img src="imagenes/delete.png"></a></td>
					<?
				}
			}
			
			
			
			
			
			if($_SESSION["modulo"] == 12){
				if(in_array(615,$privilegios) == true){
					?>
					<td class="Browse" align="center"><a href="principal.php?modulo=12&accion=615&id=<?=$bus_documentos_entregados["iddocumento_consignado_beneficiario"]?>&cedula=<?=$_REQUEST["cedula"]?>"><img src="imagenes/modificar.png"></a></td>
					<?
				}
				if(in_array(616, $privilegios) == true){
					?>
					<td class="Browse" align="center"><a href="principal.php?modulo=12&accion=616&id=<?=$bus_documentos_entregados["iddocumento_consignado_beneficiario"]?>&cedula=<?=$_REQUEST["cedula"]?>"><img src="imagenes/delete.png"></a></td>
					<?
				}
			}
			
			if($_SESSION["modulo"] == 13){
				if(in_array(685,$privilegios) == true){
					?>
					<td class="Browse" align="center"><a href="principal.php?modulo=13&accion=685&id=<?=$bus_documentos_entregados["iddocumento_consignado_beneficiario"]?>&cedula=<?=$_REQUEST["cedula"]?>"><img src="imagenes/modificar.png"></a></td>
					<?
				}
				if(in_array(686, $privilegios) == true){
					?>
					<td class="Browse" align="center"><a href="principal.php?modulo=13&accion=686&id=<?=$bus_documentos_entregados["iddocumento_consignado_beneficiario"]?>&cedula=<?=$_REQUEST["cedula"]?>"><img src="imagenes/delete.png"></a></td>
					<?
				}
			}
			
			if($_SESSION["modulo"] == 14){
				if(in_array(806,$privilegios) == true){
					?>
					<td class="Browse" align="center"><a href="principal.php?modulo=14&accion=806&id=<?=$bus_documentos_entregados["iddocumento_consignado_beneficiario"]?>&cedula=<?=$_REQUEST["cedula"]?>"><img src="imagenes/modificar.png"></a></td>
					<?
				}
				if(in_array(807, $privilegios) == true){
					?>
					<td class="Browse" align="center"><a href="principal.php?modulo=14&accion=807&id=<?=$bus_documentos_entregados["iddocumento_consignado_beneficiario"]?>&cedula=<?=$_REQUEST["cedula"]?>"><img src="imagenes/delete.png"></a></td>
					<?
				}
			}
			
			
			
			if($_SESSION["modulo"] == 16){
				if(in_array(896,$privilegios) == true){
					?>
					<td class="Browse" align="center"><a href="principal.php?modulo=16&accion=896&id=<?=$bus_documentos_entregados["iddocumento_consignado_beneficiario"]?>&cedula=<?=$_REQUEST["cedula"]?>"><img src="imagenes/modificar.png"></a></td>
					<?
				}
				if(in_array(897, $privilegios) == true){
					?>
					<td class="Browse" align="center"><a href="principal.php?modulo=16&accion=897&id=<?=$bus_documentos_entregados["iddocumento_consignado_beneficiario"]?>&cedula=<?=$_REQUEST["cedula"]?>"><img src="imagenes/delete.png"></a></td>
					<?
				}
			}
			
			
			if($_SESSION["modulo"] == 19){
				if(in_array(1045,$privilegios) == true){
					?>
					<td class="Browse" align="center"><a href="principal.php?modulo=19&accion=1045&id=<?=$bus_documentos_entregados["iddocumento_consignado_beneficiario"]?>&cedula=<?=$_REQUEST["cedula"]?>"><img src="imagenes/modificar.png"></a></td>
					<?
				}
				if(in_array(1046, $privilegios) == true){
					?>
					<td class="Browse" align="center"><a href="principal.php?modulo=19&accion=1046&id=<?=$bus_documentos_entregados["iddocumento_consignado_beneficiario"]?>&cedula=<?=$_REQUEST["cedula"]?>"><img src="imagenes/delete.png"></a></td>
					<?
				}
			}
            ?>
          </tr>
          <?
          }
          ?>
        </table>
<?
    }
    ?>
</body>
</html>





<?
if($_POST){
extract($_POST);
if($_SESSION["modulo"] == 4){
	if($_GET["accion"] == 289 and in_array(289, $privilegios) == true){
		$sql = mysql_query("select * from documento_entregado_beneficiario where idbeneficiarios = ".$idbeneficiarios." and iddocumentos_requeridos = ".$documento."");
		$num = mysql_num_rows($sql);
		if($num > 0){
			?>
			<script>
            mostrarMensajes("error", "Disculpe el registro que ingreso ya existe, por favor vuelva a intentarlo");
            setTimeout("window.location.href='principal.php?modulo=4&accion=288&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?
		}else{
			$sql = mysql_query("insert into documento_entregado_beneficiario (idbeneficiarios, iddocumentos_requeridos, nro_comprobante, fecha_emision, fecha_vencimiento, verificador_por, status, usuario, fechayhora)values($idbeneficiarios,$documento, '$nro_comprobante', '$fecha_emision', '$fecha_vencimiento', '$verificador_por', 'a', '$login', '$fh')")or die(mysql_error());
			if($sql){
				registra_transaccion('Ingresar Documentos Entregados ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				
				?>
				<script>
                mostrarMensajes("exito", "Registro Ingresado con Exito");
                setTimeout("window.location.href='principal.php?modulo=4&accion=288&cedula=<?=$_POST["cedula"]?>'",5000);
                </script>
                <?
			}else{
				registra_transaccion('Ingresar Documentos Entregados (ERROR) ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				
				?>
				<script>
                mostrarMensajes("error", "Disculpe ocurrio un error al momento de ingresar el Documento, vuelva a intentarlo mas tarde, si el problema persiste comuniquese con el administrador");
                setTimeout("window.location.href='principal.php?modulo=4&accion=288&cedula=<?=$_POST["cedula"]?>'",5000);
                </script>
                <?
			}
		}
	}
	if($_GET["accion"] == 290 and in_array(290, $privilegios) == true){
		$sql = mysql_query("update documento_entregado_beneficiario set iddocumentos_requeridos = $documento, nro_comprobante = '$nro_comprobante', fecha_emision = '$fecha_emision', fecha_vencimiento ='$fecha_vencimiento', verificador_por = '$verificador_por', usuario = '$login', fechayhora = '$fh' where iddocumento_consignado_beneficiario = ".$_REQUEST["id"]."");
			if($sql){
				registra_transaccion('Modificar Documentos Entregados ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				?>
				<script>
                mostrarMensajes("exito", "Registro Modifco con Exito");
                setTimeout("window.location.href='principal.php?modulo=4&accion=288&cedula=<?=$_POST["cedula"]?>'",5000);
                </script>
                <?
			}else{
				registra_transaccion('Modificar Documentos Entregados (ERROR) ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("error", "Disculpe ocurrio un error al momento de Modificar el Documento, vuelva a intentarlo mas tarde, si el problema persiste comuniquese con el administrador");
            setTimeout("window.location.href='principal.php?modulo=4&accion=288&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?
			}

	}
	
	
	
	if($_GET["accion"] == 291 and in_array(291, $privilegios) == true){
		$sql = mysql_query("delete from documento_entregado_beneficiario where iddocumento_consignado_beneficiario = ".$_REQUEST["id"]."")or die(mysql_error());
			if($sql){
				registra_transaccion('Eliminar Documentos Entregados ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				?>
				<script>
                mostrarMensajes("exito", "Registro Elimino con Exito");
                setTimeout("window.location.href='principal.php?modulo=4&accion=288&cedula=<?=$_POST["cedula"]?>'",5000);
                </script>
                <?
			}else{
				registra_transaccion('Eliminar Documentos Entregados (ERROR) ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				?>
			<script>
            mostrarMensajes("error", "Disculpe ocurrio un error al momento de Eliminar el Documento, vuelva a intentarlo mas tarde, si el problema persiste comuniquese con el administrador");
            setTimeout("window.location.href='principal.php?modulo=4&accion=288&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?
				
			}

	}
	
}	
if($_SESSION["modulo"] == 3){
	if($_GET["accion"] == 227 and in_array(227, $privilegios) == true){
		$sql = mysql_query("select * from documento_entregado_beneficiario where idbeneficiarios = ".$idbeneficiarios." and iddocumentos_requeridos = ".$documento."");
		$num = mysql_num_rows($sql);
		if($num > 0){
			?>
			<script>
            mostrarMensajes("error", "Disculpe el registro que ingreso ya existe, por favor vuelva a intentarlo");
            setTimeout("window.location.href='principal.php?modulo=4&accion=288&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?
		}else{
			$sql = mysql_query("insert into documento_entregado_beneficiario (idbeneficiarios, iddocumentos_requeridos, nro_comprobante, fecha_emision, fecha_vencimiento, verificador_por, status, usuario, fechayhora)values($idbeneficiarios,$documento, '$nro_comprobante','$fecha_emision', '$fecha_vencimiento', '$verificador_por', 'a', '$login', '$fh')")or die(mysql_error());
			if($sql){
				registra_transaccion('Ingresar Documentos Entregados ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				
				?>
				<script>
                mostrarMensajes("exito", "Registro Ingresado con Exito");
                setTimeout("window.location.href='principal.php?modulo=3&accion=84&cedula=<?=$_POST["cedula"]?>'",5000);
                </script>
                <?
			}else{
				registra_transaccion('Ingresar Documentos Entregados (ERROR) ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				
				?>
				<script>
                mostrarMensajes("error", "Disculpe ocurrio un error al momento de ingresar el Documento, vuelva a intentarlo mas tarde, si el problema persiste comuniquese con el administrador");
                setTimeout("window.location.href='principal.php?modulo=3&accion=84&cedula=<?=$_POST["cedula"]?>'",5000);
                </script>
                <?
			}
		}
	}
	if($_GET["accion"] == 228 and in_array(228, $privilegios) == true){
		$sql = mysql_query("update documento_entregado_beneficiario set iddocumentos_requeridos = $documento, nro_comprobante = '$nro_comprobante', fecha_emision = '$fecha_emision', fecha_vencimiento ='$fecha_vencimiento', verificador_por = '$verificador_por', usuario = '$login', fechayhora = '$fh' where iddocumento_consignado_beneficiario = ".$_REQUEST["id"]."");
			if($sql){
				registra_transaccion('Modificar Documentos Entregados ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				
				?>
				<script>
                mostrarMensajes("exito", "Registro Modifico con Exito");
                setTimeout("window.location.href='principal.php?modulo=3&accion=84&cedula=<?=$_POST["cedula"]?>'",5000);
                </script>
                <?
			}else{
				registra_transaccion('Modificar Documentos Entregados (ERROR) ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("error", "Disculpe ocurrio un error al momento de Modificar el Documento, vuelva a intentarlo mas tarde, si el problema persiste comuniquese con el administrador");
            setTimeout("window.location.href='principal.php?modulo=3&accion=84&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?
				
			}

	}
	
	
	
	if($_GET["accion"] == 229 and in_array(229, $privilegios) == true){
		$sql = mysql_query("delete from documento_entregado_beneficiario where iddocumento_consignado_beneficiario = ".$_REQUEST["id"]."")or die(mysql_error());
			if($sql){
				registra_transaccion('Eliminar Documentos Entregados ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				?>
			<script>
            mostrarMensajes("exito", "Registro Elimino con Exito");
            setTimeout("window.location.href='principal.php?modulo=3&accion=84&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?
			}else{
				registra_transaccion('Eliminar Documentos Entregados (ERROR) ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("error", "Disculpe ocurrio un error al momento de Eliminar el Documento, vuelva a intentarlo mas tarde, si el problema persiste comuniquese con el administrador");
            setTimeout("window.location.href='principal.php?modulo=3&accion=84&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?
			}

	}
	
}	

if($_SESSION["modulo"] == 1){
	if($_GET["accion"] == 473 and in_array(473, $privilegios) == true){
		$sql = mysql_query("select * from documento_entregado_beneficiario where idbeneficiarios = ".$idbeneficiarios." and iddocumentos_requeridos = ".$documento."");
		$num = mysql_num_rows($sql);
		if($num > 0){
			
			?>
			<script>
            mostrarMensajes("error", "Disculpe el registro que ingreso ya existe, por favor vuelva a intentarlo");
            setTimeout("window.location.href='principal.php?modulo=4&accion=288&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?
		}else{
			$sql = mysql_query("insert into documento_entregado_beneficiario (idbeneficiarios, iddocumentos_requeridos, nro_comprobante, fecha_emision, fecha_vencimiento, verificador_por, status, usuario, fechayhora)values($idbeneficiarios,$documento, '$nro_comprobante','$fecha_emision', '$fecha_vencimiento', '$verificador_por', 'a', '$login', '$fh')")or die(mysql_error());
			if($sql){
				registra_transaccion('Ingresar Documentos Entregados ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				
				?>
				<script>
                mostrarMensajes("exito", "Registro Ingresado con Exito");
                setTimeout("window.location.href='principal.php?modulo=1&accion=472&cedula=<?=$_POST["cedula"]?>'",5000);
                </script>
                <?
			}else{
				registra_transaccion('Ingresar Documentos Entregados (ERROR) ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				
				?>
				<script>
                mostrarMensajes("error", "Disculpe ocurrio un error al momento de ingresar el Documento, vuelva a intentarlo mas tarde, si el problema persiste comuniquese con el administrador");
                setTimeout("window.location.href='principal.php?modulo=1&accion=472&cedula=<?=$_POST["cedula"]?>'",5000);
                </script>
                <?
				
			}
		}
	}
	if($_GET["accion"] == 474 and in_array(474, $privilegios) == true){
		$sql = mysql_query("update documento_entregado_beneficiario set iddocumentos_requeridos = $documento, nro_comprobante = '$nro_comprobante', fecha_emision = '$fecha_emision', fecha_vencimiento ='$fecha_vencimiento', verificador_por = '$verificador_por', usuario = '$login', fechayhora = '$fh' where iddocumento_consignado_beneficiario = ".$_REQUEST["id"]."");
			if($sql){
				registra_transaccion('Modificar Documentos Entregados ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				
				?>
				<script>
                mostrarMensajes("exito", "Registro Modifco con Exito");
                setTimeout("window.location.href='principal.php?modulo=1&accion=472&cedula=<?=$_POST["cedula"]?>'",5000);
                </script>
                <?
			}else{
				registra_transaccion('Modificar Documentos Entregados (ERROR) ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				
				?>
				<script>
                mostrarMensajes("error", "Disculpe ocurrio un error al momento de Modificar el Documento, vuelva a intentarlo mas tarde, si el problema persiste comuniquese con el administrador");
                setTimeout("window.location.href='principal.php?modulo=1&accion=472&cedula=<?=$_POST["cedula"]?>'",5000);
                </script>
                <?
				
			}

	}
	
	
	
	if($_GET["accion"] == 475 and in_array(475, $privilegios) == true){
		$sql = mysql_query("delete from documento_entregado_beneficiario where iddocumento_consignado_beneficiario = ".$_REQUEST["id"]."")or die(mysql_error());
			if($sql){
				registra_transaccion('Eliminar Documentos Entregados ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				
				
				?>
				<script>
                mostrarMensajes("exito", "Registro Elimino con Exito");
                setTimeout("window.location.href='principal.php?modulo=1&accion=472&cedula=<?=$_POST["cedula"]?>'",5000);
                </script>
                <?
			}else{
				registra_transaccion('Eliminar Documentos Entregados (ERROR) ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				?>
				<script>
                mostrarMensajes("error", "Disculpe ocurrio un error al momento de Eliminar el Documento, vuelva a intentarlo mas tarde, si el problema persiste comuniquese con el administrador");
                setTimeout("window.location.href='principal.php?modulo=1&accion=472&cedula=<?=$_POST["cedula"]?>'",5000);
                </script>
                <?
			
			}

	}
	
}












if($_SESSION["modulo"] == 2){
	if($_GET["accion"] == 540 and in_array(540, $privilegios) == true){
		$sql = mysql_query("select * from documento_entregado_beneficiario where idbeneficiarios = ".$idbeneficiarios." and iddocumentos_requeridos = ".$documento."");
		$num = mysql_num_rows($sql);
		if($num > 0){
			
			?>
			<script>
            mostrarMensajes("error", "Disculpe el registro que ingreso ya existe, por favor vuelva a intentarlo");
            setTimeout("window.location.href='principal.php?modulo=2&accion=539&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?
		}else{
			$sql = mysql_query("insert into documento_entregado_beneficiario (idbeneficiarios, iddocumentos_requeridos, nro_comprobante, fecha_emision, fecha_vencimiento, verificador_por, status, usuario, fechayhora)values($idbeneficiarios,$documento, '$nro_comprobante','$fecha_emision', '$fecha_vencimiento', '$verificador_por', 'a', '$login', '$fh')")or die(mysql_error());
			if($sql){
				registra_transaccion('Ingresar Documentos Entregados ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("exito", "Registro Ingresado con Exito");
            setTimeout("window.location.href='principal.php?modulo=2&accion=539&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?
			}else{
				registra_transaccion('Ingresar Documentos Entregados (ERROR) ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("error", "Disculpe ocurrio un error al momento de ingresar el Documento, vuelva a intentarlo mas tarde, si el problema persiste comuniquese con el administrador");
            setTimeout("window.location.href='principal.php?modulo=2&accion=539&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?
				
			}
		}
	}
	if($_GET["accion"] == 541 and in_array(541, $privilegios) == true){
		$sql = mysql_query("update documento_entregado_beneficiario set iddocumentos_requeridos = $documento, nro_comprobante = '$nro_comprobante', fecha_emision = '$fecha_emision', fecha_vencimiento ='$fecha_vencimiento', verificador_por = '$verificador_por', usuario = '$login', fechayhora = '$fh' where iddocumento_consignado_beneficiario = ".$_REQUEST["id"]."");
			if($sql){
				registra_transaccion('Modificar Documentos Entregados ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("exito", "Registro Modifco con Exito");
            setTimeout("window.location.href='principal.php?modulo=2&accion=539&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?
			}else{
				registra_transaccion('Modificar Documentos Entregados (ERROR) ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("error", "Disculpe ocurrio un error al momento de Modificar el Documento, vuelva a intentarlo mas tarde, si el problema persiste comuniquese con el administrador");
            setTimeout("window.location.href='principal.php?modulo=2&accion=539&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?
				
			}

	}
	
	
	
	if($_GET["accion"] == 542 and in_array(542, $privilegios) == true){
		$sql = mysql_query("delete from documento_entregado_beneficiario where iddocumento_consignado_beneficiario = ".$_REQUEST["id"]."")or die(mysql_error());
			if($sql){
				registra_transaccion('Eliminar Documentos Entregados ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("exito", "Registro Elimino con Exito");
            setTimeout("window.location.href='principal.php?modulo=2&accion=539&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?
			}else{
				registra_transaccion('Eliminar Documentos Entregados (ERROR) ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("error", "Disculpe ocurrio un error al momento de Eliminar el Documento, vuelva a intentarlo mas tarde, si el problema persiste comuniquese con el administrador");
            setTimeout("window.location.href='principal.php?modulo=2&accion=539&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?
			}

	}
	
}
















if($_SESSION["modulo"] == 12){
	if($_GET["accion"] == 614 and in_array(614, $privilegios) == true){
		$sql = mysql_query("select * from documento_entregado_beneficiario where idbeneficiarios = ".$idbeneficiarios." and iddocumentos_requeridos = ".$documento."");
		$num = mysql_num_rows($sql);
		if($num > 0){
			?>
			<script>
            mostrarMensajes("error", "Disculpe el registro que ingreso ya existe, por favor vuelva a intentarlo");
            setTimeout("window.location.href='principal.php?modulo=12&accion=613&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?
		}else{
			$sql = mysql_query("insert into documento_entregado_beneficiario (idbeneficiarios, iddocumentos_requeridos, nro_comprobante, fecha_emision, fecha_vencimiento, verificador_por, status, usuario, fechayhora)values($idbeneficiarios,$documento, '$nro_comprobante','$fecha_emision', '$fecha_vencimiento', '$verificador_por', 'a', '$login', '$fh')")or die(mysql_error());
			if($sql){
				registra_transaccion('Ingresar Documentos Entregados ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				?>
			<script>
            mostrarMensajes("exito", "Registro Ingresado con Exito");
            setTimeout("window.location.href='principal.php?modulo=12&accion=613&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?
			}else{
				registra_transaccion('Ingresar Documentos Entregados (ERROR) ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("error", "Disculpe ocurrio un error al momento de ingresar el Documento, vuelva a intentarlo mas tarde, si el problema persiste comuniquese con el administrador");
            setTimeout("window.location.href='principal.php?modulo=12&accion=613&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?
				
			}
		}
	}
	if($_GET["accion"] == 615 and in_array(615, $privilegios) == true){
		$sql = mysql_query("update documento_entregado_beneficiario set iddocumentos_requeridos = $documento, nro_comprobante = '$nro_comprobante', fecha_emision = '$fecha_emision', fecha_vencimiento ='$fecha_vencimiento', verificador_por = '$verificador_por', usuario = '$login', fechayhora = '$fh' where iddocumento_consignado_beneficiario = ".$_REQUEST["id"]."");
			if($sql){
				registra_transaccion('Modificar Documentos Entregados ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				?>
			<script>
            mostrarMensajes("exito", "Registro Modifco con Exito");
            setTimeout("window.location.href='principal.php?modulo=12&accion=613&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?
			}else{
				registra_transaccion('Modificar Documentos Entregados (ERROR) ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				?>
			<script>
            mostrarMensajes("error", "Disculpe ocurrio un error al momento de Modificar el Documento, vuelva a intentarlo mas tarde, si el problema persiste comuniquese con el administrador");
            setTimeout("window.location.href='principal.php?modulo=12&accion=613&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?
			}

	}
	
	
	
	if($_GET["accion"] == 616 and in_array(616, $privilegios) == true){
		$sql = mysql_query("delete from documento_entregado_beneficiario where iddocumento_consignado_beneficiario = ".$_REQUEST["id"]."")or die(mysql_error());
			if($sql){
				registra_transaccion('Eliminar Documentos Entregados ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("exito", "Registro Elimino con Exito");
            setTimeout("window.location.href='principal.php?modulo=12&accion=613&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?
			}else{
				registra_transaccion('Eliminar Documentos Entregados (ERROR) ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("error", "Disculpe ocurrio un error al momento de Eliminar el Documento, vuelva a intentarlo mas tarde, si el problema persiste comuniquese con el administrador");
            setTimeout("window.location.href='principal.php?modulo=12&accion=613&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?
				
			}

	}
	
}











if($_SESSION["modulo"] == 13){
	if($_GET["accion"] == 684 and in_array(684, $privilegios) == true){
		$sql = mysql_query("select * from documento_entregado_beneficiario where idbeneficiarios = ".$idbeneficiarios." and iddocumentos_requeridos = ".$documento."");
		$num = mysql_num_rows($sql);
		if($num > 0){
			?>
			<script>
            mostrarMensajes("error", "Disculpe el registro que ingreso ya existe, por favor vuelva a intentarlo");
            setTimeout("window.location.href='principal.php?modulo=13&accion=683&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?
		}else{
			$sql = mysql_query("insert into documento_entregado_beneficiario (idbeneficiarios, iddocumentos_requeridos, nro_comprobante, fecha_emision, fecha_vencimiento, verificador_por, status, usuario, fechayhora)values($idbeneficiarios,$documento, '$nro_comprobante','$fecha_emision', '$fecha_vencimiento', '$verificador_por', 'a', '$login', '$fh')")or die(mysql_error());
			if($sql){
				registra_transaccion('Ingresar Documentos Entregados ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				?>
			<script>
            mostrarMensajes("exito", "Registro Ingresado con Exito");
            setTimeout("window.location.href='principal.php?modulo=13&accion=683&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?
			}else{
				registra_transaccion('Ingresar Documentos Entregados (ERROR) ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("error", "Disculpe ocurrio un error al momento de ingresar el Documento, vuelva a intentarlo mas tarde, si el problema persiste comuniquese con el administrador");
            setTimeout("window.location.href='principal.php?modulo=13&accion=683&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?				
			}
		}
	}
	if($_GET["accion"] == 685 and in_array(685, $privilegios) == true){
		$sql = mysql_query("update documento_entregado_beneficiario set iddocumentos_requeridos = $documento, nro_comprobante = '$nro_comprobante', fecha_emision = '$fecha_emision', fecha_vencimiento ='$fecha_vencimiento', verificador_por = '$verificador_por', usuario = '$login', fechayhora = '$fh' where iddocumento_consignado_beneficiario = ".$_REQUEST["id"]."");
			if($sql){
				registra_transaccion('Modificar Documentos Entregados ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("exito", "Registro Modifco con Exito");
            setTimeout("window.location.href='principal.php?modulo=13&accion=683&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?
			}else{
				registra_transaccion('Modificar Documentos Entregados (ERROR) ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				?>
			<script>
            mostrarMensajes("error", "Disculpe ocurrio un error al momento de Modificar el Documento, vuelva a intentarlo mas tarde, si el problema persiste comuniquese con el administrador");
            setTimeout("window.location.href='principal.php?modulo=13&accion=683&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?
				
			}

	}
	
	
	
	if($_GET["accion"] == 686 and in_array(686, $privilegios) == true){
		$sql = mysql_query("delete from documento_entregado_beneficiario where iddocumento_consignado_beneficiario = ".$_REQUEST["id"]."")or die(mysql_error());
			if($sql){
				registra_transaccion('Eliminar Documentos Entregados ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("exito", "Registro Elimino con Exito");
            setTimeout("window.location.href='principal.php?modulo=13&accion=683&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?
			}else{
				registra_transaccion('Eliminar Documentos Entregados (ERROR) ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("error", "Disculpe ocurrio un error al momento de Eliminar el Documento, vuelva a intentarlo mas tarde, si el problema persiste comuniquese con el administrador");
            setTimeout("window.location.href='principal.php?modulo=13&accion=683&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?
			}

	}
	
}










if($_SESSION["modulo"] == 14){
	if($_GET["accion"] == 805 and in_array(805, $privilegios) == true){
		$sql = mysql_query("select * from documento_entregado_beneficiario where idbeneficiarios = ".$idbeneficiarios." and iddocumentos_requeridos = ".$documento."");
		$num = mysql_num_rows($sql);
		if($num > 0){
			
			?>
			<script>
            mostrarMensajes("error", "Disculpe el registro que ingreso ya existe, por favor vuelva a intentarlo");
            setTimeout("window.location.href='principal.php?modulo=14&accion=804&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?
		}else{
			$sql = mysql_query("insert into documento_entregado_beneficiario (idbeneficiarios, iddocumentos_requeridos, nro_comprobante, fecha_emision, fecha_vencimiento, verificador_por, status, usuario, fechayhora)values($idbeneficiarios,$documento, '$nro_comprobante','$fecha_emision', '$fecha_vencimiento', '$verificador_por', 'a', '$login', '$fh')")or die(mysql_error());
			if($sql){
				registra_transaccion('Ingresar Documentos Entregados ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("exito", "Registro Ingresado con Exito");
            setTimeout("window.location.href='principal.php?modulo=14&accion=804&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?
			}else{
				registra_transaccion('Ingresar Documentos Entregados (ERROR) ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("error", "Disculpe ocurrio un error al momento de ingresar el Documento, vuelva a intentarlo mas tarde, si el problema persiste comuniquese con el administrador");
            setTimeout("window.location.href='principal.php?modulo=14&accion=804&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?
			}
		}
	}
	if($_GET["accion"] == 806 and in_array(806, $privilegios) == true){
		$sql = mysql_query("update documento_entregado_beneficiario set iddocumentos_requeridos = $documento, nro_comprobante = '$nro_comprobante', fecha_emision = '$fecha_emision', fecha_vencimiento ='$fecha_vencimiento', verificador_por = '$verificador_por', usuario = '$login', fechayhora = '$fh' where iddocumento_consignado_beneficiario = ".$_REQUEST["id"]."");
			if($sql){
				registra_transaccion('Modificar Documentos Entregados ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("exito", "Registro Modifco con Exito");
            setTimeout("window.location.href='principal.php?modulo=14&accion=804&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?
			}else{
				registra_transaccion('Modificar Documentos Entregados (ERROR) ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("error", "Disculpe ocurrio un error al momento de Modificar el Documento, vuelva a intentarlo mas tarde, si el problema persiste comuniquese con el administrador");
            setTimeout("window.location.href='principal.php?modulo=14&accion=804&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?
				
			}

	}
	
	
	
	if($_GET["accion"] == 807 and in_array(807, $privilegios) == true){
		$sql = mysql_query("delete from documento_entregado_beneficiario where iddocumento_consignado_beneficiario = ".$_REQUEST["id"]."")or die(mysql_error());
			if($sql){
				registra_transaccion('Eliminar Documentos Entregados ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("exito", "Registro Elimino con Exito");
            setTimeout("window.location.href='principal.php?modulo=14&accion=804&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?
			}else{
				registra_transaccion('Eliminar Documentos Entregados (ERROR) ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("error", "Disculpe ocurrio un error al momento de Eliminar el Documento, vuelva a intentarlo mas tarde, si el problema persiste comuniquese con el administrador");
            setTimeout("window.location.href='principal.php?modulo=14&accion=804&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?
				
				
			}

	}
	
}



















if($_SESSION["modulo"] == 16){
	if($_GET["accion"] == 895 and in_array(895, $privilegios) == true){
		$sql = mysql_query("select * from documento_entregado_beneficiario where idbeneficiarios = ".$idbeneficiarios." and iddocumentos_requeridos = ".$documento."");
		$num = mysql_num_rows($sql);
		if($num > 0){
			
			?>
			<script>
            mostrarMensajes("error", "Disculpe el registro que ingreso ya existe, por favor vuelva a intentarlo");
            setTimeout("window.location.href='principal.php?modulo=16&accion=894&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?
			
		}else{
			$sql = mysql_query("insert into documento_entregado_beneficiario (idbeneficiarios, iddocumentos_requeridos, nro_comprobante, fecha_emision, fecha_vencimiento, verificador_por, status, usuario, fechayhora)values($idbeneficiarios,$documento, '$nro_comprobante','$fecha_emision', '$fecha_vencimiento', '$verificador_por', 'a', '$login', '$fh')")or die(mysql_error());
			if($sql){
				registra_transaccion('Ingresar Documentos Entregados ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("exito", "Registro Ingresado con Exito");
            setTimeout("window.location.href='principal.php?modulo=16&accion=894&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?
			}else{
				registra_transaccion('Ingresar Documentos Entregados (ERROR) ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("error", "Disculpe ocurrio un error al momento de ingresar el Documento, vuelva a intentarlo mas tarde, si el problema persiste comuniquese con el administrador");
            setTimeout("window.location.href='principal.php?modulo=16&accion=894&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?
				
			}
		}
	}
	if($_GET["accion"] == 896 and in_array(896, $privilegios) == true){
		$sql = mysql_query("update documento_entregado_beneficiario set iddocumentos_requeridos = $documento, nro_comprobante = '$nro_comprobante', fecha_emision = '$fecha_emision', fecha_vencimiento ='$fecha_vencimiento', verificador_por = '$verificador_por', usuario = '$login', fechayhora = '$fh' where iddocumento_consignado_beneficiario = ".$_REQUEST["id"]."");
			if($sql){
				registra_transaccion('Modificar Documentos Entregados ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("exito", "Registro Modifco con Exito");
            setTimeout("window.location.href='principal.php?modulo=16&accion=894&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?
			}else{
				registra_transaccion('Modificar Documentos Entregados (ERROR) ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("error", "Disculpe ocurrio un error al momento de Modificar el Documento, vuelva a intentarlo mas tarde, si el problema persiste comuniquese con el administrador");
            setTimeout("window.location.href='principal.php?modulo=16&accion=894&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?				
			}

	}
	
	
	
	if($_GET["accion"] == 897 and in_array(897, $privilegios) == true){
		$sql = mysql_query("delete from documento_entregado_beneficiario where iddocumento_consignado_beneficiario = ".$_REQUEST["id"]."")or die(mysql_error());
			if($sql){
				registra_transaccion('Eliminar Documentos Entregados ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				?>
			<script>
            mostrarMensajes("exito", "Registro Elimino con Exito");
            setTimeout("window.location.href='principal.php?modulo=16&accion=894&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?
				
			}else{
				registra_transaccion('Eliminar Documentos Entregados (ERROR) ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("error", "Disculpe ocurrio un error al momento de Eliminar el Documento, vuelva a intentarlo mas tarde, si el problema persiste comuniquese con el administrador");
            setTimeout("window.location.href='principal.php?modulo=16&accion=894&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?
				
			}

	}
	
}


if($_SESSION["modulo"] == 19){
	if($_GET["accion"] == 1044 and in_array(1044, $privilegios) == true){
		$sql = mysql_query("select * from documento_entregado_beneficiario where idbeneficiarios = ".$idbeneficiarios." and iddocumentos_requeridos = ".$documento."");
		$num = mysql_num_rows($sql);
		if($num > 0){
			
			?>
			<script>
            mostrarMensajes("error", "Disculpe el registro que ingreso ya existe, por favor vuelva a intentarlo");
            setTimeout("window.location.href='principal.php?modulo=19&accion=1034&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?
			
		}else{
			$sql = mysql_query("insert into documento_entregado_beneficiario (idbeneficiarios, iddocumentos_requeridos, nro_comprobante, fecha_emision, fecha_vencimiento, verificador_por, status, usuario, fechayhora)values($idbeneficiarios,$documento, '$nro_comprobante','$fecha_emision', '$fecha_vencimiento', '$verificador_por', 'a', '$login', '$fh')")or die(mysql_error());
			if($sql){
				registra_transaccion('Ingresar Documentos Entregados ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("exito", "Registro Ingresado con Exito");
            setTimeout("window.location.href='principal.php?modulo=19&accion=1034&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?
			}else{
				registra_transaccion('Ingresar Documentos Entregados (ERROR) ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("error", "Disculpe ocurrio un error al momento de ingresar el Documento, vuelva a intentarlo mas tarde, si el problema persiste comuniquese con el administrador");
            setTimeout("window.location.href='principal.php?modulo=19&accion=1034&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?
				
			}
		}
	}
	if($_GET["accion"] == 1045 and in_array(1045, $privilegios) == true){
		$sql = mysql_query("update documento_entregado_beneficiario set iddocumentos_requeridos = $documento, nro_comprobante = '$nro_comprobante', fecha_emision = '$fecha_emision', fecha_vencimiento ='$fecha_vencimiento', verificador_por = '$verificador_por', usuario = '$login', fechayhora = '$fh' where iddocumento_consignado_beneficiario = ".$_REQUEST["id"]."");
			if($sql){
				registra_transaccion('Modificar Documentos Entregados ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("exito", "Registro Modifco con Exito");
            setTimeout("window.location.href='principal.php?modulo=19&accion=1034&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?
			}else{
				registra_transaccion('Modificar Documentos Entregados (ERROR) ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("error", "Disculpe ocurrio un error al momento de Modificar el Documento, vuelva a intentarlo mas tarde, si el problema persiste comuniquese con el administrador");
            setTimeout("window.location.href='principal.php?modulo=19&accion=1034&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?				
			}

	}
	
	
	
	if($_GET["accion"] == 1046 and in_array(1046, $privilegios) == true){
		$sql = mysql_query("delete from documento_entregado_beneficiario where iddocumento_consignado_beneficiario = ".$_REQUEST["id"]."")or die(mysql_error());
			if($sql){
				registra_transaccion('Eliminar Documentos Entregados ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				?>
			<script>
            mostrarMensajes("exito", "Registro Elimino con Exito");
            setTimeout("window.location.href='principal.php?modulo=19&accion=1034&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?
				
			}else{
				registra_transaccion('Eliminar Documentos Entregados (ERROR) ('.$documento.')',$login,$fh,$pc,'carga_familiar',$conexion_db);
				
				?>
			<script>
            mostrarMensajes("error", "Disculpe ocurrio un error al momento de Eliminar el Documento, vuelva a intentarlo mas tarde, si el problema persiste comuniquese con el administrador");
            setTimeout("window.location.href='principal.php?modulo=19&accion=1034&cedula=<?=$_POST["cedula"]?>'",5000);
            </script>
			<?
				
			}

	}
	
}



	
}
?>

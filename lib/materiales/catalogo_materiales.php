<?
if($_POST["ingresar"]){
	$_GET["accion"] = 317;
}else if($_POST["ingresar2"]){
	$_GET["accion"] = 234;
}else if($_POST["ingresar3"]){
	$_GET["accion"] = 431;
}else if($_POST["ingresar4"]){
	$_GET["accion"] = 545;
}else if($_POST["ingresar5"]){
	$_GET["accion"] = 642;
}else if($_POST["ingresar6"]){
	$_GET["accion"] = 712;
}else if($_POST["ingresar7"]){
	$_GET["accion"] = 832;
}else if($_POST["ingresar8"]){
	$_GET["accion"] = 923;
}else if($_POST["ingresar9"]){
	$_GET["accion"] = 1069;
}




if($_POST["id_material"] || $_POST["duplicar"] || $_POST["buscar"]){	
if($_POST["id_material"]){			
	$id = $_POST["id_material"];
	$sql_busqueda = mysql_query("select * from articulos_servicios where idarticulos_servicios = '".$id."'");
	$buscar = true;	
}else{		
	$codigo = $_POST["buscar"];		
	$sql_busqueda = mysql_query("select * from articulos_servicios where codigo = '".$codigo."'");		
	$buscar = true;			
}		
$bus_busqueda = mysql_fetch_array($sql_busqueda);
$num_busqueda = mysql_num_rows($sql_busqueda);
		if($num_busqueda == 0){
			mensaje("No existen materiales con ese codigo");
		}
}


if($_POST["ingresar"] || $_POST["ingresar2"] || $_POST["ingresar3"] || $_POST["ingresar4"] || $_POST["ingresar5"] || $_POST["ingresar6"] || $_POST["ingresar7"] || $_POST["ingresar8"] || $_POST["ingresar9"]){
	if ($_POST["exento"]){
		$esexento = 1;
	}else{
		$esexento = 0;
	}
	if ($_POST["activo"]){
		$esactivo = "a";
	}else{
		$esactivo = "0";
	}
	extract($_POST);
	if (!isset($tipo_concepto)){$tipo_concepto = '';}
	if (!isset($unidad_medida)){$unidad_medida = 7;}
	if (!isset($ramo)){$ramo = 12;}
	if (!isset($id_snc_detalle_grupo)){$id_snc_detalle_grupo = 29652;}
	if (!isset($impuesto)){$impuesto = 1;}
	if (!isset($exento)){$exento = 0;}
	if (!isset($ultimo_costo)){$ultimo_costo = 0;}
	if (!isset($fecha_ultima_compra)){$fecha_ultima_compra = '';}
	if (!isset($tipo_concepto)){$tipo_concepto = 1;}
	
	$sql = mysql_query("select * from articulos_servicios where codigo = '".$codigo."'");
	$num = mysql_num_rows($sql);
	if($num > 0){
		mensaje("Disculpe el Material que Ingreso ya existe, Por favor vuelva a intentarlo");
	}else{
		$deudora = explode('-',$cuenta_deudora);
		$cuenta_deudorag = $deudora[0];
		$nivel_cuenta_deudora = $deudora[1];
		$acreedora = explode('-',$cuenta_acreedora);
		$cuenta_acreedorag = $acreedora[0];
		$nivel_cuenta_acreedora = $acreedora[1];
		
		$sql_ingresar = mysql_query("insert into articulos_servicios (codigo,
															tipo,
															descripcion,
															idunidad_medida,
															idramo_articulo,
															activo,
															idclasificador_presupuestario,
															idsnc_detalle_grupo,
															idimpuestos,
															exento,
															ultimo_costo,
															fecha_ultima_compra,
															tipo_concepto,
															status,
															usuario,
															fechayhora,
															idordinal,
															idcuenta_debe,
															tabla_debe,
															idcuenta_haber,
															tabla_haber)values('".$codigo."',
																				'".$tipo."',
																				'".$descripcion."',
																				'".$unidad_medida."',
																				'".$ramo."',
																				'".$activo."',
																				'".$id_clasificador."',
																				'".$id_snc_detalle_grupo."',
																				'".$impuesto."',
																				'".$exento."',
																				'".$ultimo_costo."',
																				'".$fecha_ultima_compra."',
																				'".$tipo_concepto."',
																				'a',
																				'".$login."',
																				'".$fh."',
																				'".$idordinal."',
																				'".$cuenta_deudorag."',
																				'".$nivel_cuenta_deudora."',
																				'".$cuenta_acreedora."',
																				'".$nivel_cuenta_acreedora."'
																				)")or die(" error ingresando ".mysql_error());
			if($sql_ingresar){
					mensaje("El nuevo Catalogo fue Ingresado con Exito");
					registra_transaccion("Ingresar Catalogo de Materiales (".$codigo.")",$login,$fh,$pc,'catologo_materiales');
					redirecciona("principal.php?accion=".$_GET["accion"]."&modulo=".$_GET["modulo"]."");
			}else{
					mysql_error();
			}
		}	
}



if($_POST["modificar"]){
	if ($_POST["exento"]){
		$esexento = 1;
	}else{
		$esexento = 0;
	}
	if ($_POST["activo"]){
		$esactivo = "a";
	}else{
		$esactivo = "0";
	}
	extract($_POST);
		if (!isset($tipo_concepto)){$tipo_concepto = '';}
		if (!isset($unidad_medida)){$unidad_medida = 7;}
		if (!isset($ramo)){$ramo = 12;}
		if (!isset($id_snc_detalle_grupo)){$id_snc_detalle_grupo = 29652;}
		if (!isset($impuesto)){$impuesto = 1;}
		if (!isset($exento)){$exento = "0";}
		if (!isset($ultimo_costo)){$ultimo_costo = "0";}
		if (!isset($fecha_ultima_compra)){$fecha_ultima_compra = '';}
		if (!isset($tipo_concepto)){$tipo_concepto = 1;}
		
		$deudora = explode('-',$cuenta_deudora);
		$cuenta_deudorag = $deudora[0];
		$nivel_cuenta_deudora = $deudora[1];
		$acreedora = explode('-',$cuenta_acreedora);
		$cuenta_acreedorag = $acreedora[0];
		$nivel_cuenta_acreedora = $acreedora[1];
		//echo " cuenta completa ".$cuenta_acreedora;
		//echo " cuenta ".$nivel_cuenta_acreedora;
		
		$sql_modificar = mysql_query("update articulos_servicios set tipo = '".$tipo."',
															descripcion = '".$descripcion."',
															idunidad_medida = '".$unidad_medida."',
															idramo_articulo = '".$ramo."',
															activo = '".$activo."',
															idclasificador_presupuestario = '".$id_clasificador."',
															idsnc_detalle_grupo ='".$id_snc_detalle_grupo."',
															idimpuestos = '".$impuesto."',
															exento = '".$exento."',
															ultimo_costo = '".$ultimo_costo."',
															fecha_ultima_compra = '".$fecha_ultima_compra."',
															tipo_concepto = '".$tipo_concepto."',
															status = 'a',
															usuario = '".$login."',
															fechayhora = '".$fh."',
															idordinal='".$idordinal."',
															idcuenta_debe='".$cuenta_deudorag."',
															tabla_debe='".$nivel_cuenta_deudora."',
															idcuenta_haber='".$cuenta_acreedora."',
															tabla_haber='".$nivel_cuenta_acreedora."'
															where idarticulos_servicios = '".$id_material."'		
																				")or die("AQUI".mysql_error());


			if($sql_modificar){
					mensaje("El nuevo Catalogo fue Modificado con Exito");
					registra_transaccion("Modificar Catalogo de Materiales (".$codigo.")",$login,$fh,$pc,'catologo_materiales');
					redirecciona("principal.php?accion=".$_GET["accion"]."&modulo=".$_GET["modulo"]."");
			}else{
					mysql_error();
			}
}


if($_POST["eliminar"]){
extract($_POST);
	$sql_acs = mysql_query("select * from articulos_compra_servicio
							where 
						articulos_compra_servicio.idarticulos_servicios = '".$_POST["id_material"]."'") or die(mysql_error());
	$num_acs = mysql_num_rows($sql_acs);
	$sql_ar = mysql_query("select * from articulos_requisicion
							where 
						articulos_requisicion.idarticulos_servicios = '".$_POST["id_material"]."'") or die(mysql_error());
	$num_ar = mysql_num_rows($sql_ar);
	$sql_arsc = mysql_query("select * from articulos_solicitud_cotizacion 
							where 
						articulos_solicitud_cotizacion.idarticulos_servicios = '".$_POST["id_material"]."'") or die(mysql_error());
	$num_arsc = mysql_num_rows($sql_arsc);
	if($num_acs == 0 and $num_ar == 0 and $num_arsc == 0){
		$sql_eliminar = mysql_query("delete from articulos_servicios where codigo = '".$codigo."'")or die(mysql_error());
		if($sql_eliminar){
			mensaje("El Articulo fue Eliminado con Exito");		
			registra_transaccion("Eliminar Catalogo de Materiales (".$codigo.")",$login,$fh,$pc,'catologo_materiales');
			redirecciona("principal.php?accion=".$_GET["accion"]."&modulo=".$_GET["modulo"]."");
		}else{
			mensaje("Disculpe no se puede eliminar el registro seleccionado, seguramente el mismo esta relacionado con algun otro registro dentro del sistema");
			registra_transaccion("Eliminar Catalogo de Materiales (ERROR) (".$codigo.")",$login,$fh,$pc,'catologo_materiales');
			redirecciona("principal.php?accion=".$_GET["accion"]."&modulo=".$_GET["modulo"]."");
		}
	}else{
		mensaje("Disculpe el Articulo no se puede eliminar ya que el mismo esta siendo utilizado por algun compromiso o pago en el sistema");
	}
}


?>

	<br>
	<h4 align=center>
    <?
    if($_SESSION["modulo"] == 4 or $_SESSION["modulo"] == 2 or $_SESSION["modulo"] == 19){
		?>
			Catalogo de Servicios / Obras y Otros 
		<?
	}else if($_SESSION["modulo"] == 1 or $_SESSION["modulo"] == 13){
	?>
    		Catalogo de Conceptos
	<?
	}else if($_SESSION["modulo"] == 12 || $_SESSION["modulo"] == 14){
	?>
    		Catalogo de Servicios
	<?
	}else{
		?>
			Catalogo de Materiales	
		<?
	}
	?>
    
    
    
    </h4>
	<h2 class="sqlmVersion"></h2>
	<br>
    <style>
	#celdaMateriales:hover{
		background-color:#0099FF;
		color:#FFFFFF;
	}
	</style>
<body onLoad="document.getElementById('codigo').focus()">
<form id="form1" method="post" action="" name="form1">
<table align="center">
    <tr>
      <td class="viewPropTitle" align="right">Codigo:</td> 
       <td>
       <? if (isset($_POST["duplicar"])) {
	   		$buscar=false;
	   }?>
       <input type="text" name="buscar" id="buscar">
       <input type="hidden" name="id_material" id="id_material" <? if($buscar == true){?> value="<?=$bus_busqueda["idarticulos_servicios"]?>"<? } ?>>
       
       &nbsp;
      <img src="imagenes/validar.png" border="0" onClick="document.form1.submit()" style="cursor:pointer" title="Consultar Material">&nbsp;
      <img src="imagenes/search0.png" border="0" style="cursor:pointer" onClick="window.open('lib/listas/listar_materiales.php?destino=materiales','listar catalogo de materiales','resizable=no, scrollbars=yes, width=900, height = 500')" title="Buscar Material">&nbsp;
      <a href="principal.php?accion=<?=$_GET["accion"]?>&modulo=<?=$_GET["modulo"]?>"><img src="imagenes/nuevo.png" border="0" title="Nuevo Material"></a>&nbsp;
       </td>
    </tr>
</table>

  <table width="90%" border="0" align="center">
    <tr>
      <td width="18%" align="right" class="viewPropTitle">C&oacute;digo:</td>
      <td width="26%"><input type="text" name="codigo" id="codigo" <? if($buscar==true){ ?>value="<?= utf8_decode($bus_busqueda["codigo"])?>"<? } ?> onKeyUp="validarVacios('codigo', this.value, 'form1')" onBlur="validarVacios('codigo', this.value, 'form1')" autocomplete="OFF" style="padding:0px 20px 0px 0px;"></td>
      <td width="20%" align="right" class="viewPropTitle">Tipo:</td>
    <td width="36%">
        <?
		if ($_SESSION["modulo"] == '1' or $_SESSION["modulo"] == '13'){
			$sql_tipos_documentos = mysql_query("select * from tipos_documentos where compromete = 'si' and causa = 'no' and paga = 'no' and (modulo like '%-1-%' or modulo like '%-13-%')");
		}else{
        	$sql_tipos_documentos = mysql_query("select * from tipos_documentos where compromete = 'si' and causa = 'no' and paga = 'no' and modulo like '%-".$_SESSION["modulo"]."-%'");
		}
		?>
        
        <select name="tipo" id="tipo">
          <?
          while($bus_tipos_documentos = mysql_fetch_array($sql_tipos_documentos)){
		  	?>
			
			<option value="<?=$bus_tipos_documentos["idtipos_documentos"]?>" <? if($bus_busqueda["tipo"] == $bus_tipos_documentos["idtipos_documentos"]){echo "selected";}?>><?=utf8_decode($bus_tipos_documentos["descripcion"])?></option>
			<?
		  }
		  ?>
          </select>          </td>
    </tr>
    
    <tr>
      <td class="viewPropTitle" align="right">Descripci&oacute;n:</td>
      <td colspan="3">
      <textarea name="descripcion" cols="130" rows="3" id="descripcion" onKeyUp="validarVacios('descripcion', this.value, 'form1'), mostrarListaMateriales(this.value)" onBlur="validarVacios('descripcion', this.value, 'form1')" autocomplete="OFF" style="padding:0px 20px 0px 0px;"><?= utf8_decode($bus_busqueda["descripcion"])?></textarea>
      <div id="listaDeMateriales" style="position:absolute; display:none; width:600px; border:#000000 1px solid; background-color:#FFFFFF"></div>      </td>
    </tr>
    <? if($_SESSION["modulo"] == 1 or $_SESSION["modulo"] == 13){?>
    <tr>
      <td class="viewPropTitle" align="right">Tipo de Concepto:</td>
      <td colspan="2">
        <input type="radio" name="tipo_concepto" id="tipo_concepto" value="1" <? if($bus_busqueda["tipo_concepto"] == 1){echo "checked";}?>>Asignaci&oacute;n
      	<input type="radio" name="tipo_concepto" id="tipo_concepto" value="2" <? if($bus_busqueda["tipo_concepto"] == 2){echo "checked";}?>>Deducci&oacute;n
      	<input type="radio" name="tipo_concepto" id="tipo_concepto" value="4" <? if($bus_busqueda["tipo_concepto"] == 4){echo "checked";}?>>Aporte Patronal        </td>
    </tr>
    <? } 
    if($_SESSION["modulo"] <> 13){ ?>
    <tr>
      <td class="viewPropTitle" align="right">Unidad de Medida:</td>
      <td>
     <label> <select name="unidad_medida" id="unidad_medida">
      <?
      $sql_unidad = mysql_query("select * from unidad_medida order by idunidad_medida")or die(mysql_error());
	  while($bus_unidad = mysql_fetch_array($sql_unidad)){
	  ?>
	  	<option <? if($bus_busqueda["idunidad_medida"] == $bus_unidad["idunidad_medida"]){echo "selected";}?> value="<?=$bus_unidad["idunidad_medida"]?>"><?=utf8_decode($bus_unidad["descripcion"])?></option>
	  <?
	  }
	  ?>
      </select></label>
      
      <?
      if($_SESSION["modulo"] == 3){
	  	$accion_unidad = 77;
	  }else if($_SESSION["modulo"] == 4){
	  	$accion_unidad = 320;
	  }else if($_SESSION["modulo"] == 2){
	  	$accion_unidad = 560;
	  }else if($_SESSION["modulo"] == 12){
	  	$accion_unidad = 645;
	  }else if($_SESSION["modulo"] == 1){
	  	$accion_unidad = 717;
	  }else if($_SESSION["modulo"] == 14){
	  	$accion_unidad = 836;
	  }else if($_SESSION["modulo"] == 16){
	  	$accion_unidad = 926;
	  }else if($_SESSION["modulo"] == 19){
	  	$accion_unidad = 1066;
	  }
	  ?>
      <a href="#" onClick="window.open('principal.php?modulo=<?=$_SESSION["modulo"]?>&accion=<?=$accion_unidad?>&pop=si','agregar unidad de medida','width=900, height = 500, scrollbars = yes')">
      <img src="imagenes/add.png" title="Ingresar Nueva Unidad de Medida">      </a>      </td>
      <td class="viewPropTitle" align="right">Ramo del Material:</td>
      <td>
       <label> <select name="ramo" id="ramo">
        <?
        $sql_ramos = mysql_query("select * from ramos_articulos order by descripcion");
		while($bus_ramos = mysql_fetch_array($sql_ramos)){
		?>
		<option <? if($bus_busqueda["idramo_articulo"] == $bus_ramos["idramo_articulo"]){echo "selected";}?> value="<?=$bus_ramos["idramo_articulo"]?>"><?=utf8_decode($bus_ramos["descripcion"])?></option>
		<?
		}
		?>
        </select></label>
        
         <?
      if($_SESSION["modulo"] == 3){
	  	$accion_ramo = 230;
	  }else if($_SESSION["modulo"] == 4){
	  	$accion_ramo = 324;
	  }else if($_SESSION["modulo"] == 2){
	  	$accion_ramo = 556;
	  }else if($_SESSION["modulo"] == 12){
	  	$accion_ramo = 649;
	  }else if($_SESSION["modulo"] == 1){
	  	$accion_ramo = 721;
	  }else if($_SESSION["modulo"] == 14){
	  	$accion_ramo = 840;
	  }else if($_SESSION["modulo"] == 16){
	  	$accion_ramo = 930;
	  }else if($_SESSION["modulo"] == 19){
	  	$accion_ramo = 1067;
	  }
	  ?>
        
            <a href="#" onClick="window.open('principal.php?modulo=<?=$_SESSION["modulo"]?>&accion=<?=$accion_ramo?>&pop=si','agregar ramo de materiales','width=900, height = 500, scrollbars = yes')">
           		<img src="imagenes/add.png" title="Ingresar Nuevo Ramo">            </a>        </td>
    </tr>
     <tr>
      <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4" style="background:#09F; text-align:center;"><STRONG>AFECTACI&Oacute;N IMPOSITIVA</STRONG></td>
    </tr>
    <tr>
    <td class="viewPropTitle" align="right">Impuesto:</td>
      <td>
      <label><select name="impuesto" id="impuesto">
      <?
      $sql_impuestos = mysql_query("select * from impuestos");
	  while($bus_impuestos = mysql_fetch_array($sql_impuestos)){
	  ?>
      	<option value="<?=$bus_impuestos["idimpuestos"]?>" <? if($bus_busqueda["idimpuestos"] == $bus_impuestos["idimpuestos"]) echo " selected"; ?>><?=$bus_impuestos["siglas"]?> (<?=$bus_impuestos["porcentaje"]?>%)</option>
        
        <?
        }
		?>
      </select></label>
        <?
      if($_SESSION["modulo"] == 3){
	  	$accion_impuestos = 238;
	  }else if($_SESSION["modulo"] == 4){
	  	$accion_impuestos = 328;
	  }else if($_SESSION["modulo"] == 2){
	  	$accion_impuestos = 552;
	  }else if($_SESSION["modulo"] == 12){
	  	$accion_impuestos = 653;
	  }else if($_SESSION["modulo"] == 14){
	  	$accion_impuestos = 844;
	  }else if($_SESSION["modulo"] == 16){
	  	$accion_impuestos = 934;
	  }else if($_SESSION["modulo"] == 19){
	  	$accion_impuestos = 1068;
	  }
	  ?>
      <a href="#" onClick="window.open('principal.php?modulo=<?=$_SESSION["modulo"]?>&accion=<?=$accion_impuestos?>&pop=si','ingresar nuevo Impuesto','width=900, height = 500, scrollbars = yes')">
      <img src="imagenes/add.png" title="Ingresar Nuevo Impuesto">      </a></td>
      
      <td class="viewPropTitle" align="right">Exento:</td>
      <td><input type="checkbox" name="exento" id="exento" value="1" <? if($bus_busqueda["exento"] == 1){ echo "checked";}?>>    
     
    </tr>
     <? } ?>
    <tr>
      <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4" style="background:#09F; text-align:center;"><STRONG>AFECTACI&Oacute;N PRESUPUESTARIA</STRONG></td>
    </tr>
    <tr><td colspan="4">
      <table> <tr>
      	<td width="185" class="viewPropTitle" align="right">Clasificador Presupuestario</td>
      	<td><?
	  if($bus_busqueda){
     	 $sql_clasificador_presupuestario = mysql_query("select * from clasificador_presupuestario where idclasificador_presupuestario = ".$bus_busqueda["idclasificador_presupuestario"]."");
	 	 $bus_clasificador_presupuestario = mysql_fetch_array($sql_clasificador_presupuestario);
	  }
	  ?>
        <label>
        <input type="text" name="clasificador" size="150" disabled id="clasificador" value="<? echo "(".$bus_clasificador_presupuestario["codigo_cuenta"].")";?> <?=utf8_decode($bus_clasificador_presupuestario["denominacion"])?>">
        <input type="hidden" name="id_clasificador" id="id_clasificador" value="<?=$bus_clasificador_presupuestario["idclasificador_presupuestario"]?>">
        </label>
        <a href="#" onClick="window.open('lib/listas/listar_clasificador_presupuestario.php?destino=materiales','buscar clasificador presupuestario','width=900, height = 500, scrollbars = yes')"><img src="imagenes/search0.png" title="Buscar Clasificador Presupuestario"> </a>
        </td>
    </tr>
 	
    <tr>
      <td width="185" class="viewPropTitle" align="right">Ordinal:</td>
      <td><label>
      <?
      
	  
	  
	    if($_SESSION["version"] == "basico" and $bus_busqueda["idordinal"] == ''){
			$sql_ordinal = mysql_query("select * from ordinal where idordinal = '6'")or die(mysql_error());
	 	 	$bus_ordinal = mysql_fetch_array($sql_ordinal);
		}else{
			$sql_ordinal = mysql_query("select * from ordinal where idordinal = '".$bus_busqueda["idordinal"]."'")or die(mysql_error());
	 	 	$bus_ordinal = mysql_fetch_array($sql_ordinal);

		}
	  ?>
      
        <input name="nombre_ordinal" type="text" id="nombre_ordinal" size="150" value="<?="(".$bus_ordinal["codigo"].") ".utf8_decode($bus_ordinal["denominacion"])?>" disabled>
        <input name="idordinal" type="hidden" id="idordinal" value="<?=$bus_ordinal["idordinal"]?>">
        <a href="#" onClick="window.open('lib/listas/lista_ordinal.php?destino=materiales','buscar clasificador presupuestario','width=900, height = 500, scrollbars = yes')"><img src="imagenes/search0.png" title="Buscar Clasificador Presupuestario"></a></label></td>
    </tr>
    </table>
    </td></tr>
    
    
    
	<tr>
      <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4" style="background:#09F; text-align:center; font-family:Verdana, Geneva, sans-serif; font-size:12px"><strong>AFECTACI&Oacute;N CONTABLE</strong></td>
    </tr>
    <? if($_SESSION["modulo"] <> 13){ ?>
	<tr>
      <td class="viewPropTitle" align="right">Activo:</td>
      <td><input type="checkbox" name="activo" id="activo" value="a" <? if($bus_busqueda["activo"] == "a"){ echo "checked";}?>></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <? }?>
    <tr>
    <td class="viewPropTitle" align="right">Afecta por el debe:</td>
      <td colspan="3">
       <label> 
       <select name="cuenta_deudora" id="cuenta_deudora">
       <option value='0'>..:: Cuenta afectada por el Tipo de Transacci&oacute;n ::..</option>
        <?
       		$sql_consultar = mysql_query("(SELECT
						  d.iddesagregacion_cuentas_contables as idcuenta,
						  CONCAT(g.codigo, '.', s.codigo, '.', r.codigo, '.', c.codigo, '.', sc.codigo, '.', sc2.codigo, '.', d.codigo) AS codigo,
						  d.denominacion,
						  'desagregacion_cuentas_contables' AS tabla
					FROM
						  desagregacion_cuentas_contables d
						  INNER JOIN subcuenta_segundo_cuentas_contables sc2 ON (d.idsubcuenta_segundo = sc2.idsubcuenta_segundo_cuentas_contables)
						  INNER JOIN subcuenta_primer_cuentas_contables sc ON (sc2.idsubcuenta_primer = sc.idsubcuenta_primer_cuentas_contables)
						  INNER JOIN cuenta_cuentas_contables c ON (sc.idcuenta= c.idcuenta_cuentas_contables)
						  INNER JOIN rubro_cuentas_contables r ON (c.idrubro = r.idrubro_cuentas_contables)
						  INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables)
						  INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables)
					)

						UNION

					(SELECT
							  sc2.idsubcuenta_segundo_cuentas_contables as idcuenta,
							  CONCAT(g.codigo, s.codigo, r.codigo, '.', c.codigo, '.', sc.codigo, '.', sc2.codigo) AS codigo,
							  sc2.denominacion,
							  'subcuenta_segundo_cuentas_contables' AS tabla
					FROM
							  subcuenta_segundo_cuentas_contables sc2
							  INNER JOIN subcuenta_primer_cuentas_contables sc ON (sc2.idsubcuenta_primer = sc.idsubcuenta_primer_cuentas_contables)
							  INNER JOIN cuenta_cuentas_contables c ON (sc.idcuenta= c.idcuenta_cuentas_contables)
							  INNER JOIN rubro_cuentas_contables r ON (c.idrubro = r.idrubro_cuentas_contables)
							  INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables)
							  INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables)
					WHERE (
							  sc2.idsubcuenta_segundo_cuentas_contables not in(select idsubcuenta_segundo from desagregacion_cuentas_contables)

					)
					)
					UNION
					(SELECT
							  sc.idsubcuenta_primer_cuentas_contables as idcuenta,
							  CONCAT(g.codigo, s.codigo, r.codigo, '.', c.codigo, '.', sc.codigo) AS codigo,
							  sc.denominacion,
							  'subcuenta_primer_cuentas_contables' AS tabla
					FROM
							  subcuenta_primer_cuentas_contables sc
							  INNER JOIN cuenta_cuentas_contables c ON (sc.idcuenta= c.idcuenta_cuentas_contables)
							  INNER JOIN rubro_cuentas_contables r ON (c.idrubro = r.idrubro_cuentas_contables)
							  INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables)
							  INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables)
					WHERE (
							  sc.idsubcuenta_primer_cuentas_contables not in(select idsubcuenta_primer from subcuenta_segundo_cuentas_contables)
					
					)
					)
					UNION
					(SELECT
							  cc.idcuenta_cuentas_contables as idcuenta,
							  CONCAT(g.codigo, s.codigo, r.codigo, '.', cc.codigo) AS codigo,
							  cc.denominacion,
							  'cuenta_cuentas_contables' AS tabla
					FROM
							  cuenta_cuentas_contables cc
							  INNER JOIN rubro_cuentas_contables r ON (cc.idrubro = r.idrubro_cuentas_contables)
							  INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables)
							  INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables)

					)
          			UNION
					(SELECT
							  r.idrubro_cuentas_contables as idcuenta,
							  CONCAT(g.codigo, s.codigo, r.codigo) AS codigo,
							  r.denominacion,
							  'rubro_cuentas_contables' AS tabla
					FROM
							  rubro_cuentas_contables r
							  INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables)
							  INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables)

					) ORDER BY codigo")or die(mysql_error());
			
			while($bus_cuenta_deudora = mysql_fetch_array($sql_consultar)){?>
				<option value="<?=$bus_cuenta_deudora["idcuenta"]?>-<?=$bus_cuenta_deudora["tabla"]?>" <? if($bus_busqueda["idcuenta_debe"] == $bus_cuenta_deudora["idcuenta"] and $bus_busqueda["tabla_debe"] == $bus_cuenta_deudora["tabla"]){echo "selected";}?>><?=$bus_cuenta_deudora["codigo"]?>- <?=utf8_decode($bus_cuenta_deudora["denominacion"])?></option>
			<? }?>
      </select>
      </label>
        
        </td>
    </tr>
    <tr>
      
      <td class="viewPropTitle" align="right">Afecta por el haber:</td>
      <td colspan="3">
       <label> 
       <select name="cuenta_acreedora" id="cuenta_acreedora">
       <option value='0'>..:: Cuenta afectada por el Tipo de Transacci&oacute;n ::..</option>
        <?
       		$sql_consultar = mysql_query("(SELECT
						  d.iddesagregacion_cuentas_contables as idcuenta,
						  CONCAT(g.codigo, '.', s.codigo, '.', r.codigo, '.', c.codigo, '.', sc.codigo, '.', sc2.codigo, '.', d.codigo) AS codigo,
						  d.denominacion,
						  'desagregacion_cuentas_contables' AS tabla
					FROM
						  desagregacion_cuentas_contables d
						  INNER JOIN subcuenta_segundo_cuentas_contables sc2 ON (d.idsubcuenta_segundo = sc2.idsubcuenta_segundo_cuentas_contables)
						  INNER JOIN subcuenta_primer_cuentas_contables sc ON (sc2.idsubcuenta_primer = sc.idsubcuenta_primer_cuentas_contables)
						  INNER JOIN cuenta_cuentas_contables c ON (sc.idcuenta= c.idcuenta_cuentas_contables)
						  INNER JOIN rubro_cuentas_contables r ON (c.idrubro = r.idrubro_cuentas_contables)
						  INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables)
						  INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables)
					)

						UNION

					(SELECT
							  sc2.idsubcuenta_segundo_cuentas_contables as idcuenta,
							  CONCAT(g.codigo, s.codigo, r.codigo, '.', c.codigo, '.', sc.codigo, '.', sc2.codigo) AS codigo,
							  sc2.denominacion,
							  'subcuenta_segundo_cuentas_contables' AS tabla
					FROM
							  subcuenta_segundo_cuentas_contables sc2
							  INNER JOIN subcuenta_primer_cuentas_contables sc ON (sc2.idsubcuenta_primer = sc.idsubcuenta_primer_cuentas_contables)
							  INNER JOIN cuenta_cuentas_contables c ON (sc.idcuenta= c.idcuenta_cuentas_contables)
							  INNER JOIN rubro_cuentas_contables r ON (c.idrubro = r.idrubro_cuentas_contables)
							  INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables)
							  INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables)
					WHERE (
							  sc2.idsubcuenta_segundo_cuentas_contables not in(select idsubcuenta_segundo from desagregacion_cuentas_contables)

					)
					)
					UNION
					(SELECT
							  sc.idsubcuenta_primer_cuentas_contables as idcuenta,
							  CONCAT(g.codigo, s.codigo, r.codigo, '.', c.codigo, '.', sc.codigo) AS codigo,
							  sc.denominacion,
							  'subcuenta_primer_cuentas_contables' AS tabla
					FROM
							  subcuenta_primer_cuentas_contables sc
							  INNER JOIN cuenta_cuentas_contables c ON (sc.idcuenta= c.idcuenta_cuentas_contables)
							  INNER JOIN rubro_cuentas_contables r ON (c.idrubro = r.idrubro_cuentas_contables)
							  INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables)
							  INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables)
					WHERE (
							  sc.idsubcuenta_primer_cuentas_contables not in(select idsubcuenta_primer from subcuenta_segundo_cuentas_contables)
					
					)
					)
					UNION
					(SELECT
							  cc.idcuenta_cuentas_contables as idcuenta,
							  CONCAT(g.codigo, s.codigo, r.codigo, '.', cc.codigo) AS codigo,
							  cc.denominacion,
							  'cuenta_cuentas_contables' AS tabla
					FROM
							  cuenta_cuentas_contables cc
							  INNER JOIN rubro_cuentas_contables r ON (cc.idrubro = r.idrubro_cuentas_contables)
							  INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables)
							  INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables)

					)
         			UNION
					(SELECT
							  r.idrubro_cuentas_contables as idcuenta,
							  CONCAT(g.codigo, s.codigo, r.codigo) AS codigo,
							  r.denominacion,
							  'rubro_cuentas_contables' AS tabla
					FROM
							  rubro_cuentas_contables r
							  INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables)
							  INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables)

					) ORDER BY codigo")or die(mysql_error());
			while($bus_cuenta_acreedora = mysql_fetch_array($sql_consultar)){?>
				<option value="<?=$bus_cuenta_acreedora["idcuenta"]?>-<?=$bus_cuenta_acreedora["tabla"]?>"  <? if($bus_busqueda["idcuenta_haber"] == $bus_cuenta_acreedora["idcuenta"] and $bus_busqueda["tabla_haber"] == $bus_cuenta_acreedora["tabla"]){echo "selected";}?>><?=$bus_cuenta_acreedora["codigo"]?> - <?=utf8_decode($bus_cuenta_acreedora["denominacion"])?></option>
			<? }?>
      </select>
      </label>
        
        </td>
    </tr>
	
    
    <? if($_SESSION["modulo"] <> 1 and $_SESSION["modulo"] <> 13){ ?>
    
    <tr>
      <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4" style="background:#09F; text-align:center; font-family:Verdana, Geneva, sans-serif; font-size:12px"><strong>SERVICIO NACIONAL DE CONTRATISTAS</strong></td>
    </tr>
    <tr><td colspan="4">
      <table> <tr>
      <td width="185" align="right" class="viewPropTitle">C&oacute;digo SNC:</td>
      <td>
       <?
	   if($bus_busqueda){
      		$sql_detalle_grupo = mysql_query("select * from snc_detalle_grupo where idsnc_detalle_grupo = ".$bus_busqueda["idsnc_detalle_grupo"]."");
	  		$bus_detalle_grupo = mysql_fetch_array($sql_detalle_grupo);
	  }
	  if ($_SESSION["modulo"] == 1 or $_SESSION["modulo"] == 13){
	  		$sql_detalle_grupo = mysql_query("select * from snc_detalle_grupo where descripcion = 'NO APLICA'");
	  		$bus_detalle_grupo = mysql_fetch_array($sql_detalle_grupo);
	  }
	  ?>
       <label>
      <input name="snc_detalle_grupo" type="text" disabled id="snc_detalle_grupo" value="<? echo "(".$bus_detalle_grupo["codigo"].")";?> <?=utf8_decode($bus_detalle_grupo["descripcion"])?>" size="150"><input type="hidden" name="id_snc_detalle_grupo" id="id_snc_detalle_grupo" value="<?=$bus_detalle_grupo["idsnc_detalle_grupo"]?>">
       </label>
       <a href="#" onClick="window.open('lib/listas/listar_snc_detalle_grupo.php?destino=materiales','Buscar Detalle Grupo','width=900, height = 500, scrollbars = yes')"><img src="imagenes/search0.png" title="Buscar Detalle Grupo"></a>
       </td>
    </tr>
    </table>
    </td>
    </tr>
    <? } ?>
    
    
    
    <? if($_SESSION["modulo"] <> 13){ ?>
    
    <tr>
      <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
      
      <td class="viewPropTitle" align="right">Costo Promedio:</td>
      <td><input type="text" disabled name="costo_promedio" id="costo_promedio" style="text-align:right" value="<?=number_format($bus_busqueda["costo_promedio"],2,",",".")?>"></td>
    </tr>
    <tr>
      <td class="viewPropTitle" align="right">Ultimo Costo:</td>
      <td><input type="text" name="ultimo_costo" id="ultimo_costo" style="text-align:right" value="<?=number_format($bus_busqueda["ultimo_costo"],2,",",".")?>"></td>
      <td class="viewPropTitle" align="right">Fecha de la Ultima Compra:</td>
      <td><input name="fecha_ultima_compra" type="text" id="fecha_ultima_compra" size="13" maxlength="10" value="<?=$bus_busqueda["fecha_ultima_compra"]?>">
        <img src="imagenes/jscalendar0.gif" name="f_trigger_c" width="16" height="16" id="f_trigger_c" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
        <script type="text/javascript">
							Calendar.setup({
							inputField    : "fecha_ultima_compra",
							button        : "f_trigger_c",
							align         : "Tr",
							ifFormat    	: "%Y-%m-%d"
							});
						</script></td>
    </tr>
    <tr>
      <td class="viewPropTitle" align="right">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <? } ?>
    <tr>
      <td height="25" colspan="4">
      
     <center> <?php

				if($_SESSION["modulo"] == 4){	
					if($buscar == false and in_array(317, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar' id='ingresar' type='submit' value='Ingresar'>
                        <?
					}
				
					if($buscar == true and in_array(318, $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' id='modificar' type='submit' value='Modificar'>
                        <?
					}
				
					if($buscar == true and in_array(319, $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' id='eliminar' type='submit' value='Eliminar'>
						<?
					}
					if($buscar == true and (in_array(318, $privilegios) == true || in_array(319, $privilegios) == true)){
						?>
                        <input align=center class='button' name='duplicar' id='duplicar' type='submit' value='Duplicar'>
                        <?
					}
				}else if($_SESSION["modulo"] == 3){
					if($buscar == false and in_array(234, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar2' id='ingresar2' type='submit' value='Ingresar'>
                        <?
					}
				
					if($buscar == true and in_array(235, $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' id='modificar' type='submit' value='Modificar'>
                        <?
					}
				
					if($buscar == true and in_array(236, $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' id='eliminar' type='submit' value='Eliminar'>
						<?
					}
					if($buscar == true and (in_array(235, $privilegios) == true || in_array(236, $privilegios) == true)){
						?>
                        <input align=center class='button' name='duplicar' id='duplicar' type='submit' value='Duplicar'>
                        <?
					}
				}else if($_SESSION["modulo"] == 1){
					if($buscar == false and in_array(431, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar3' id='ingresar3' type='submit' value='Ingresar'>
                        <?
					}
				
					if($buscar == true and in_array(432, $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' id='modificar' type='submit' value='Modificar'>
                        <?
					}
				
					if($buscar == true and in_array(433, $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' id='eliminar' type='submit' value='Eliminar'>
						<?
					}
					if($buscar == true and (in_array(432, $privilegios) == true || in_array(433, $privilegios) == true)){
						?>
                        <input align=center class='button' name='duplicar' id='duplicar' type='submit' value='Duplicar'>
                        <?
					}
				}else if($_SESSION["modulo"] == 2){
					if($buscar == false and in_array(545, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar4' id='ingresar4' type='submit' value='Ingresar'>
                        <?
					}
				
					if($buscar == true and in_array(546, $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' id='modificar' type='submit' value='Modificar'>
                        <?
					}
				
					if($buscar == true and in_array(547, $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' id='eliminar' type='submit' value='Eliminar'>
						<?
					}
					if($buscar == true and (in_array(546, $privilegios) == true || in_array(547, $privilegios) == true)){
						?>
                        <input align=center class='button' name='duplicar' id='duplicar' type='submit' value='Duplicar'>
                        <?
					}
				}else if($_SESSION["modulo"] == 12){
					if($buscar == false and in_array(642, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar5' id='ingresar5' type='submit' value='Ingresar'>
                        <?
					}
				
					if($buscar == true and in_array(643, $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' id='modificar' type='submit' value='Modificar'>
                        <?
					}
				
					if($buscar == true and in_array(644, $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' id='eliminar' type='submit' value='Eliminar'>
						<?
					}
					if($buscar == true and (in_array(643, $privilegios) == true || in_array(644, $privilegios) == true)){
						?>
                        <input align=center class='button' name='duplicar' id='duplicar' type='submit' value='Duplicar'>
                        <?
					}
				}else if($_SESSION["modulo"] == 13){
					if($buscar == false and in_array(712, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar6' id='ingresar6' type='submit' value='Ingresar'>
                        <?
					}
				
					if($buscar == true and in_array(713, $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' id='modificar' type='submit' value='Modificar'>
                        <?
					}
				
					if($buscar == true and in_array(714, $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' id='eliminar' type='submit' value='Eliminar'>
						<?
					}
					if($buscar == true and (in_array(715, $privilegios) == true || in_array(644, $privilegios) == true)){
						?>
                        <input align=center class='button' name='duplicar' id='duplicar' type='submit' value='Duplicar'>
                        <?
					}
				}else if($_SESSION["modulo"] == 14){
					if($buscar == false and in_array(833, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar7' id='ingresar7' type='submit' value='Ingresar'>
                        <?
					}
				
					if($buscar == true and in_array(834, $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' id='modificar' type='submit' value='Modificar'>
                        <?
					}
				
					if($buscar == true and in_array(835, $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' id='eliminar' type='submit' value='Eliminar'>
						<?
					}
					if($buscar == true and (in_array(851, $privilegios) == true || in_array(851, $privilegios) == true)){
						?>
                        <input align=center class='button' name='duplicar' id='duplicar' type='submit' value='Duplicar'>
                        <?
					}
				}else if($_SESSION["modulo"] == 16){
					if($buscar == false and in_array(923, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar8' id='ingresar8' type='submit' value='Ingresar'>
                        <?
					}
				
					if($buscar == true and in_array(924, $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' id='modificar' type='submit' value='Modificar'>
                        <?
					}
				
					if($buscar == true and in_array(925, $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' id='eliminar' type='submit' value='Eliminar'>
						<?
					}
					if($buscar == true and (in_array(941, $privilegios) == true || in_array(941, $privilegios) == true)){
						?>
                        <input align=center class='button' name='duplicar' id='duplicar' type='submit' value='Duplicar'>
                        <?
					}
				}else if($_SESSION["modulo"] == 19){
					if($buscar == false and in_array(1069, $privilegios) == true){
						?>
                        <input align=center class='button' name='ingresar8' id='ingresar9' type='submit' value='Ingresar'>
                        <?
					}
				
					if($buscar == true and in_array(1070, $privilegios) == true){
						?>
                        <input align=center class='button' name='modificar' id='modificar' type='submit' value='Modificar'>
                        <?
					}
				
					if($buscar == true and in_array(1071, $privilegios) == true){
						?>
                        <input align=center class='button' name='eliminar' id='eliminar' type='submit' value='Eliminar'>
						<?
					}
					if($buscar == true and (in_array(1069, $privilegios) == true || in_array(1069, $privilegios) == true)){
						?>
                        <input align=center class='button' name='duplicar' id='duplicar' type='submit' value='Duplicar'>
                        <?
					}
				}
			?>
      			  <input type="reset" value="Reiniciar" class="button"></center>      </td>
    </tr>
  </table>
</form>
            <script>document.getElementById('codigo').focus()</script>
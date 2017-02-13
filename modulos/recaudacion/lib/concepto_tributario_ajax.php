<?
session_start();
include("../../../conf/conex.php");
Conectarse();
extract($_POST);


switch($ejecutar){
	case "ingresarConcepto":
	
	if($tipo_calculo_aforo == "fijo"){
		$valor_aforo = $fijo_valor_aforo;
		$divisor_aforo = 0;
	}else{
		$valor_aforo = $porcentual_valor_aforo;
		$divisor_aforo = $porcentual_divisor_aforo;
	}
	
	if($tipo_calculo_minimo == "fijo"){
		$valor_minimo = $minimo_fijo_valor;
		$divisor_minimo = 0;
	}else{
		$valor_minimo = $porcentual_valor_minimo;
		$divisor_minimo = $porcentual_divisor_minimo;
	}
	
		$sql_ingresar = mysql_query("insert into concepto_tributario (codigo,
																		anio,
																		denominacion,
																		tipo_base_aforo,
																		tipo_calculo_aforo,
																		valor_aforo,
																		divisor_aforo,
																		tipo_base_minimo,
																		tipo_calculo_minimo,
																		valor_minimo,
																		divisor_minimo,
																		idclasificador_presupuestario,
																		status,
																		usuario,
																		fechayhora,
																		idtabla_constantes_recaudacion,
																		monto_variable)VALUES('".$codigo."',
																					'".$anio."',
																					'".$denominacion."',
																					'".$tipo_base_aforo."',
																					'".$tipo_calculo_aforo."',
																					'".$valor_aforo."',
																					'".$divisor_aforo."',
																					'".$tipo_base_minimo."',
																					'".$tipo_calculo_minimo."',
																					'".$valor_minimo."',
																					'".$divisor_minimo."',
																					'".$idclasificador_presupuestario."',
																					'a',
																					'".$login."',
																					'".$fh."',
																					'".$idtabla_constantes_recaudacion."',
																					'".$monto_variable."')")or die(mysql_error());
		echo mysql_insert_id();
	break;
	case "listarUnidades":
	$sql_consulta = mysql_query("select * from concepto_tributario");
	?>
	<table align="center" cellpadding="0" cellspacing="0" border="0" width="40%">
<thead>
              <tr>
                <td align="center" class="Browse">A&ntilde;o</td>
                <td align="center" class="Browse">Codigo</td>
                <td align="center" class="Browse">Descripcion</td>
                <td colspan="2" align="center" class="Browse">Acciones</td>
      </tr>
              </thead>
	<?
	while($bus_consulta = mysql_fetch_array($sql_consulta)){
	$sql_clasificador = mysql_query("select * from clasificador_presupuestario where idclasificador_presupuestario = '".$bus_consulta["idclasificador_presupuestario"]."'");
	$bus_clasificador = mysql_fetch_array($sql_clasificador);
	?>
	<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                        <td class='Browse' align='center'><?=$bus_consulta["anio"]?></td>
                        <td class='Browse' align='center'><?=$bus_consulta["codigo"]?></td>
                        <td class='Browse' align='center'><?=$bus_consulta["denominacion"]?></td>
                        <td class='Browse' align='center'><img src="imagenes/modificar.png" onClick="seleccionarUnidad('<?=$bus_consulta["idconcepto_tributario"]?>', '<?=$bus_consulta["codigo"]?>', '<?=$bus_consulta["anio"]?>', '<?=$bus_consulta["denominacion"]?>', '<?=$bus_consulta["tipo_base_aforo"]?>', '<?=$bus_consulta["tipo_calculo_aforo"]?>', '<?=$bus_consulta["valor_aforo"]?>', '<?=$bus_consulta["divisor_aforo"]?>', '<?=$bus_consulta["tipo_base_minimo"]?>', '<?=$bus_consulta["tipo_calculo_minimo"]?>', '<?=$bus_consulta["valor_minimo"]?>', '<?=$bus_consulta["divisor_minimo"]?>', '<?=$bus_consulta["idclasificador_presupuestario"]?>', '(<?=$bus_clasificador["codigo_cuenta"]?>) <?=$bus_clasificador["denominacion"]?>', '<?=$bus_consulta["idtabla_constantes_recaudacion"]?>', '<?=$bus_consulta["monto_variable"]?>'), document.getElementById('boton_ingresar').style.display='none', document.getElementById('boton_modificar').style.display='block', document.getElementById('boton_eliminar').style.display='none'" style="cursor:pointer"></td>
                        <td class='Browse' align='center'><img src="imagenes/delete.png" onClick="seleccionarUnidad('<?=$bus_consulta["idconcepto_tributario"]?>', '<?=$bus_consulta["codigo"]?>', '<?=$bus_consulta["anio"]?>', '<?=$bus_consulta["denominacion"]?>', '<?=$bus_consulta["tipo_base_aforo"]?>', '<?=$bus_consulta["tipo_calculo_aforo"]?>', '<?=$bus_consulta["valor_aforo"]?>', '<?=$bus_consulta["divisor_aforo"]?>', '<?=$bus_consulta["tipo_base_minimo"]?>', '<?=$bus_consulta["tipo_calculo_minimo"]?>', '<?=$bus_consulta["valor_minimo"]?>', '<?=$bus_consulta["divisor_minimo"]?>', '<?=$bus_consulta["idclasificador_presupuestario"]?>', '(<?=$bus_clasificador["codigo_cuenta"]?>) <?=$bus_clasificador["denominacion"]?>', '<?=$bus_consulta["idtabla_constantes_recaudacion"]?>', '<?=$bus_consulta["monto_variable"]?>'), document.getElementById('boton_ingresar').style.display='none', document.getElementById('boton_modificar').style.display='none', document.getElementById('boton_eliminar').style.display='block'" style="cursor:pointer"></td>
     </tr>
	<?
	}
	?>
	</table>
<?
	break;
	case "modificarConcepto":
	if($tipo_calculo_aforo == "fijo"){
		$valor_aforo = $fijo_valor_aforo;
		$divisor_aforo = 0;
	}else{
		$valor_aforo = $porcentual_valor_aforo;
		$divisor_aforo = $porcentual_divisor_aforo;
	}
	
	if($tipo_calculo_minimo == "fijo"){
		$valor_minimo = $minimo_fijo_valor;
		$divisor_minimo = 0;
	}else{
		$valor_minimo = $porcentual_valor_minimo;
		$divisor_minimo = $porcentual_divisor_minimo;
	}
	
		$sql_ingresar = mysql_query("update concepto_tributario set codigo = '".$codigo."',
																		anio = '".$anio."',
																		denominacion = '".$denominacion."',
																		tipo_base_aforo = '".$tipo_base_aforo."',
																		tipo_calculo_aforo = '".$tipo_calculo_aforo."',
																		valor_aforo = '".$valor_aforo."',
																		divisor_aforo = '".$divisor_aforo."',
																		tipo_base_minimo = '".$tipo_base_minimo."',
																		tipo_calculo_minimo = '".$tipo_calculo_minimo."',
																		valor_minimo = '".$valor_minimo."',
																		divisor_minimo = '".$divisor_minimo."',
																		monto_variable = '".$monto_variable."',
																		idclasificador_presupuestario = '".$idclasificador_presupuestario."',
																		idtabla_constantes_recaudacion = '".$idtabla_constantes_recaudacion."'
																		where
																		idconcepto_tributario = '".$idconcepto_tributario."'")or die(mysql_error());
	break;
	case "eliminarConcepto":
	$sql_eliminar = mysql_query("delete from concepto_tributario where idconcepto_tributario = '".$idconcepto_tributario."'");
	break;


// ******************************************************************************************************************************
// ******************************************************************************************************************************
// ********************************************************** MORA **************************************************************
// ******************************************************************************************************************************
// ******************************************************************************************************************************


	case "ingresarMora":
		$sql_ingresar = mysql_query("insert into moras_conceptos_tributarios(tipo_mora,
																			denominacion,
																			sobre,
																			frecuencia_calculo,
																			condicion_tipo,
																			condicion_operador,
																			condicion_factor,
																			condicion_valor,
																			valor_calculo,
																			tipo_valor_mora,
																			idconcepto_tributario)VALUES('".$tipo_mora."',
																									'".$denominacion_mora."',
																									'".$sobre_mora."',
																									'".$frecuencia_calculo_mora."',
																									'".$condicion_tipo_mora."',
																									'".$condicion_operador_mora."',
																									'".$condicion_factor_mora."',
																									'".$condicion_valor_mora."',
																									'".$valor_calculo."',
																									'".$tipo_valor_mora."'
																									'".$idconcepto_tributario."')");
	break;
	case "modificarMora":
		$sql_modificar = mysql_query("UPDATE moras_conceptos_tributarios set tipo_mora = '".$tipo_mora."',
																			denominacion = '".$denominacion_mora."',
																			sobre = '".$sobre_mora."',
																			frecuencia_calculo = '".$frecuencia_calculo_mora."',
																			condicion_tipo = '".$condicion_tipo_mora."',
																			condicion_operador = '".$condicion_operador_mora."',
																			condicion_factor = '".$condicion_factor_mora."',
																			condicion_valor = '".$condicion_valor_mora."',
																			valor_calculo = '".$valor_calculo."',
																			tipo_valor_mora = '".$tipo_valor_mora."'
																				WHERE
																			idmoras_conceptos_tributarios = '".$idmoras_conceptos_tributarios."'")or die(mysql_error());
	break;
	case "eliminarMora":
		$sql_eliminar = mysql_query("DELETE FROM  
											moras_conceptos_tributarios 
												WHERE  
											idmoras_conceptos_tributarios = '".$idmoras_conceptos_tributarios."'");
	break;
	
	
	case "mostrarListaMoras":
	$sql_consulta = mysql_query("select * from moras_conceptos_tributarios where idconcepto_tributario = '".$idconcepto_tributario."'");
	
	
	
	?>
	<table align="center" cellpadding="0" cellspacing="0" border="0" width="100%">
        <thead>
        <tr>
        	<td align="center" class="Browse">Tipo Mora</td>
            <td align="center" class="Browse">Denominacion</td>
            <td align="center" class="Browse">Sobre</td>
            <td align="center" class="Browse">Fre. de Cal.</td>
            <td align="center" class="Browse">Condicion</td>
            <td align="center" class="Browse" colspan="2">Acciones</td>
        </tr>
        </thead>
        <?
        while($bus_consulta = mysql_fetch_array($sql_consulta)){
		?>
        <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
        	<td class='Browse'><?=$bus_consulta["tipo_mora"]?></td>
            <td class='Browse'><?=$bus_consulta["denominacion"]?></td>
            <td class='Browse'><?=$bus_consulta["sobre"]?></td>
            <td class='Browse'><?=$bus_consulta["frecuencia_calculo"]?></td>
            <td class='Browse'><?=$bus_consulta["condicion_tipo"]?>&nbsp;<?=$bus_consulta["condicion_operador"]?>&nbsp;<?=$bus_consulta["condicion_factor"]?>&nbsp;<?=$bus_consulta["condicion_valor"]?></td>
            <td class='Browse' align="center"><img src="imagenes/modificar.png" onclick="seleccionarMora('<?=$bus_consulta["idmoras_conceptos_tributarios"]?>', '<?=$bus_consulta["tipo_mora"]?>', '<?=$bus_consulta["denominacion"]?>', '<?=$bus_consulta["sobre"]?>', '<?=$bus_consulta["frecuencia_calculo"]?>', '<?=$bus_consulta["condicion_tipo"]?>', '<?=$bus_consulta["condicion_operador"]?>', '<?=$bus_consulta["condicion_factor"]?>', '<?=$bus_consulta["condicion_valor"]?>', 'modificar', '<?=$bus_consulta["valor_calculo"]?>')" border="0" style="cursor:pointer"></td>
            <td class='Browse' align="center"><img src="imagenes/delete.png" style="cursor:pointer" onclick="seleccionarMora('<?=$bus_consulta["idmoras_conceptos_tributarios"]?>', '<?=$bus_consulta["tipo_mora"]?>', '<?=$bus_consulta["denominacion"]?>', '<?=$bus_consulta["sobre"]?>', '<?=$bus_consulta["frecuencia_calculo"]?>', '<?=$bus_consulta["condicion_tipo"]?>', '<?=$bus_consulta["condicion_operador"]?>', '<?=$bus_consulta["condicion_factor"]?>', '<?=$bus_consulta["condicion_valor"]?>', 'eliminar', '<?=$bus_consulta["valor_calculo"]?>')"></td>
        </tr>
        <?
        }
		?>
    </table>
	
	<?
	
	
	break;

}
?>
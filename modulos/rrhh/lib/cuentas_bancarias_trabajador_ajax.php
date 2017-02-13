<?
include("../../../conf/conex.php");
Conectarse();
extract($_POST);


if($ejecutar == "consultarAsociados"){
	$sql_consultar = mysql_query("select 
								 t.nombres,
								 t.apellidos,
								 cb.nro_cuenta,
								 cb.tipo,
								 mc.denominacion as motivo,
								 mc.idmotivos_cuentas,
								 b.denominacion,
								 b.idbanco,
								 t.idtrabajador,
								 cb.idcuentas_bancarias_trabajador
								 	from 
								 trabajador t,
								 banco b,
								 cuentas_bancarias_trabajador cb,
								 motivos_cuentas mc
								 	where 
								 cb.idtrabajador = '".$idtrabajador."'
								 and t.idtrabajador = cb.idtrabajador
								 and b.idbanco = cb.banco
								 and mc.idmotivos_cuentas = cb.motivo");
	
	?>
	<table border="0" class="Browse" cellpadding="0" cellspacing="0" width="80%" align="center">
        	<thead>
            <tr>
            	<td width="26%" class="Browse" align="center">Trabajador</td>
                <td width="29%" class="Browse" align="center">Nro. de Cuenta</td>
                <td width="15%" class="Browse" align="center">Tipo de Cuneta</td>
                <td width="19%" class="Browse" align="center">Motivo de la Cuenta</td>
                <td width="11%" class="Browse" align="center">Banco</td>
                <td width="11%" class="Browse" align="center" colspan="2">Acci&oacute;n</td>
            </tr>
            </thead>
            <? while($bus_consultar = mysql_fetch_array($sql_consultar)){?>
            	<tr bordercolor="#000000" bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                <td align="left" class="Browse"><?=$bus_consultar["nombres"]." ".$bus_consultar["apellidos"]?></td>
                <td align="left" class="Browse"><?=$bus_consultar["nro_cuenta"]?></td>
                <td align="left" class="Browse"><?=$bus_consultar["tipo"]?></td>
                <td align="left" class="Browse"><?=$bus_consultar["motivo"]?></td>
                <td align="left" class="Browse"><?=$bus_consultar["denominacion"]?></td>
                
                
                
                <td class="Browse" align="center">
                 <img src="imagenes/modificar.png" style="cursor:pointer" alt='Modificar' title='Modificar' onclick="seleccionar('<?=$bus_consultar["nombres"]." ".$bus_consultar["apellidos"]?>','<?=$bus_consultar["nro_cuenta"]?>','<?=$bus_consultar["tipo"]?>', '<?=$bus_consultar["idmotivos_cuentas"]?>', '<?=$bus_consultar["idbanco"]?>', '<?=$bus_consultar["idcuentas_bancarias_trabajador"]?>')">                </td>
                 <td class="Browse" align="center">
                 	<img src="imagenes/delete.png" style="cursor:pointer" alt='Eliminar' title='Eliminar' onclick="seleccionar('<?=$bus_consultar["nombres"]." ".$bus_consultar["apellidos"]?>','<?=$bus_consultar["nro_cuenta"]?>','<?=$bus_consultar["tipo"]?>', '<?=$bus_consultar["idmotivos_cuentas"]?>', '<?=$bus_consultar["idbanco"]?>', '<?=$bus_consultar["idcuentas_bancarias_trabajador"]?>')">
                 </td>
          </tr>
            <? } ?>
        </table>
	<?
	
}




if($ejecutar == "ingresarCunetaBancaria"){
	$sql_ingresar = mysql_query("insert into cuentas_bancarias_trabajador (idtrabajador,
																		   nro_cuenta,
																		   tipo,
																		   motivo,
																		   banco)VALUES('".$idtrabajador."',
																		   				'".$numero_cuenta."',
																						'".$tipo_cuenta."',
																						'".$motivo_cuenta."',
																						'".$banco."')");
}




if($ejecutar =="modificarCunetaBancaria"){
		$sql_ingresar = mysql_query("update cuentas_bancarias_trabajador set nro_cuenta = '".$numero_cuenta."',
																		   tipo = '".$tipo_cuenta."',
																		   motivo = '".$motivo_cuenta."',
																		   banco = '".$banco."'
																		   	where 
																		   idcuentas_bancarias_trabajador='".$idcuenta_bancaria."'");
}



if($ejecutar=="eliminarCuentaBancaria"){
	$sql_eliminar = mysql_query("delete from cuentas_bancarias_trabajador where idcuentas_bancarias_trabajador = '".$idcuenta_bancaria."'");
}
?>
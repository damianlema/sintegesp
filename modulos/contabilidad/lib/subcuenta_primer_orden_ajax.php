<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
Conectarse();
extract($_POST);

if($ejecutar == "ingresarGrupo"){
	$sql_consultar = mysql_query("select * from subcuenta_primer_cuentas_contables where idcuenta = '".$idcuenta."' and codigo = '".$codigo."'");
	$num_consultar = mysql_fetch_array($sql_consultar);
	if($num_consultar == 0){
		$sql_ingresar = mysql_query("insert into subcuenta_primer_cuentas_contables(idcuenta, codigo, denominacion)values('".$idcuenta."', '".$codigo."', '".$denominacion."')");
	}else{
		echo "existe";
	}
}

if($ejecutar == "editarGrupo"){
	$sql_ingresar = mysql_query("update subcuenta_primer_cuentas_contables set idcuenta = '".$idcuenta."', codigo = '".$codigo."', denominacion = '".$denominacion."' where idsubcuenta_primer_cuentas_contables = '".$idsubcuenta_primer."'")or die(mysql_error());
}

if($ejecutar == "eliminarGrupo"){
	$sql_validar = mysql_query("select * from subcuenta_segundo_cuentas_contables where idsubcuenta_primer= '".$idsubcuenta_primer."'");
	$num_validar = mysql_num_rows($sql_validar);
	if($num_validar > 0){
		echo "usado";
	}else{
	$sql_ingresar = mysql_query("delete from subcuenta_primer_cuentas_contables where idsubcuenta_primer_cuentas_contables = '".$idsubcuenta_primer."'");
	}
}



if($ejecutar == "listarGrupos"){
?>
<table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="78%">
          <thead>
          <tr>
            <td width="35%" align="center" class="Browse" style="font-size:9px">Cuenta</td>
            <td width="8%" align="center" class="Browse" style="font-size:9px">Codigo</td>
            <td width="58%" align="center" class="Browse" style="font-size:9px">Denominacion</td>
            <td align="center" class="Browse" style="font-size:9px" colspan="2">Seleccionar</td>
          </tr>
          </thead>
          <?
		  $query = "SELECT grupo_cuentas_contables.codigo as codigo_grupo,
		  										subgrupo_cuentas_contables.codigo as codigo_subgrupo,
												rubro_cuentas_contables.codigo as codigo_rubro,
												cuenta_cuentas_contables.idcuenta_cuentas_contables as idcuenta,
												cuenta_cuentas_contables.codigo as codigo_cuenta,
												cuenta_cuentas_contables.denominacion as denominacion_cuenta,
												subcuenta_primer_cuentas_contables.codigo as codigo_subcuenta_primer,
												subcuenta_primer_cuentas_contables.denominacion as denominacion_subcuenta_primer,
												subcuenta_primer_cuentas_contables.idsubcuenta_primer_cuentas_contables as idsubcuenta_primer
													FROM 
												grupo_cuentas_contables,
												subgrupo_cuentas_contables,
												rubro_cuentas_contables,
												cuenta_cuentas_contables,
												subcuenta_primer_cuentas_contables
													WHERE
												cuenta_cuentas_contables.idcuenta_cuentas_contables = subcuenta_primer_cuentas_contables.idcuenta
												and rubro_cuentas_contables.idrubro_cuentas_contables = cuenta_cuentas_contables.idrubro
												and subgrupo_cuentas_contables.idsubgrupo_cuentas_contables = rubro_cuentas_contables.idsubgrupo
												and grupo_cuentas_contables.idgrupos_cuentas_contables = subgrupo_cuentas_contables.idgrupo";
			if($cuentaBuscar != "0"){
				$query .= " and subcuenta_primer_cuentas_contables.idcuenta = '".$cuentaBuscar."'";
			}
			
			$query .= " and subcuenta_primer_cuentas_contables.denominacion like '%".$campoBuscar."%'
			order by codigo_grupo, codigo_subgrupo, codigo_rubro, codigo_cuenta, codigo_subcuenta_primer";
		  //echo $query;
		  $sql_consultar = mysql_query($query);
          while($bus_consultar = mysql_fetch_array($sql_consultar)){
		  ?>
                   <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                  	
                    <td class='Browse' align='left' width="35%" style="font-size:10px"><?=$bus_consultar["codigo_grupo"].".".$bus_consultar["codigo_subgrupo"].".".$bus_consultar["codigo_rubro"].".".$bus_consultar["codigo_cuenta"].". ".$bus_consultar["denominacion_cuenta"]?></td>
                    <td class='Browse' align='left' width="8%" style="font-size:10px"><?=$bus_consultar["codigo_grupo"].".".$bus_consultar["codigo_subgrupo"]?>.<?=$bus_consultar["codigo_rubro"]?>.<?=$bus_consultar["codigo_cuenta"]?>.<?=$bus_consultar["codigo_subcuenta_primer"]?></td>
                    <td class='Browse' align='left' width="58%" style="font-size:10px"><?=$bus_consultar["denominacion_subcuenta_primer"]?></td>
                    <td width="3%" align="center" valign="middle" class='Browse'>
                      <img src="imagenes/modificar.png"
                            style="cursor:pointer"
                            onClick="seleccinarModificar('<?=$bus_consultar["codigo_subcuenta_primer"]?>', '<?=$bus_consultar["denominacion_subcuenta_primer"]?>', '<?=$bus_consultar["idcuenta"]?>', '<?=$bus_consultar["idsubcuenta_primer"]?>')">                    
                    </td>
                    <td width="5%" align="center" valign="middle" class='Browse'>
                      <img src="imagenes/delete.png"
                            style="cursor:pointer"
                            onClick="seleccinarEliminar('<?=$bus_consultar["codigo_subcuenta_primer"]?>', '<?=$bus_consultar["denominacion_subcuenta_primer"]?>', '<?=$bus_consultar["idcuenta"]?>', '<?=$bus_consultar["idsubcuenta_primer"]?>')">                    
                    </td>
                            
   		        </tr>
          <?
          }
          ?>
  </table>
<?
}

?>
<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
Conectarse();
extract($_POST);

if($ejecutar == "ingresarGrupo"){
	$sql_consultar = mysql_query("select * from subcuenta_segundo_cuentas_contables where idsubcuenta_primer = '".$idsubcuenta_primer."' and codigo = '".$codigo."'");
	$num_consultar = mysql_fetch_array($sql_consultar);
	if($num_consultar == 0){
		$sql_ingresar = mysql_query("insert into subcuenta_segundo_cuentas_contables(idsubcuenta_primer, codigo, denominacion)values('".$idsubcuenta_primer."', '".$codigo."', '".$denominacion."')");
	}else{
		echo "existe";
	}
}

if($ejecutar == "editarGrupo"){
	$sql_ingresar = mysql_query("update subcuenta_segundo_cuentas_contables set idsubcuenta_primer = '".$idsubcuenta_primer."', codigo = '".$codigo."', denominacion = '".$denominacion."' where idsubcuenta_segundo_cuentas_contables = '".$idsubcuenta_segundo."'")or die(mysql_error());
}

if($ejecutar == "eliminarGrupo"){
	$sql_validar = mysql_query("select * from desagregacion_cuentas_contables where idsubcuenta_segundo= '".$idsubcuenta_segundo."'");
	$num_validar = mysql_num_rows($sql_validar);
	if($num_validar > 0){
		echo "usado";
	}else{
	$sql_ingresar = mysql_query("delete from subcuenta_segundo_cuentas_contables where idsubcuenta_segundo_cuentas_contables = '".$idsubcuenta_segundo."'");
	}
}



if($ejecutar == "listarGrupos"){
?>
<table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="78%">
          <thead>
          <tr>
            <td width="35%" align="center" class="Browse" style="font-size:9px">Sub cuenta de Primer Orden</td>
            <td width="8%" align="center" class="Browse" style="font-size:9px">C&oacute;digo</td>
            <td width="58%" align="center" class="Browse" style="font-size:9px">Denominaci&oacute;n</td>
            <td align="center" class="Browse" style="font-size:9px" colspan="2">Seleccionar</td>
          </tr>
          </thead>
          <?
		  $query = "SELECT grupo_cuentas_contables.codigo as codigo_grupo,
				subgrupo_cuentas_contables.codigo as codigo_subgrupo,
				rubro_cuentas_contables.codigo as codigo_rubro,
				cuenta_cuentas_contables.codigo as codigo_cuenta,
				subcuenta_primer_cuentas_contables.codigo as codigo_subcuenta_primer,
				subcuenta_primer_cuentas_contables.denominacion as denominacion_subcuenta_primer,
				subcuenta_primer_cuentas_contables.idsubcuenta_primer_cuentas_contables as idsubcuenta_primer,
				subcuenta_segundo_cuentas_contables.codigo as codigo_subcuenta_segundo,
				subcuenta_segundo_cuentas_contables.denominacion as denominacion_subcuenta_segundo,
				subcuenta_segundo_cuentas_contables.idsubcuenta_segundo_cuentas_contables as idsubcuenta_segundo
					FROM 
				grupo_cuentas_contables,
				subgrupo_cuentas_contables,
				rubro_cuentas_contables,
				cuenta_cuentas_contables,
				subcuenta_primer_cuentas_contables,
				subcuenta_segundo_cuentas_contables
					WHERE
				subcuenta_primer_cuentas_contables.idsubcuenta_primer_cuentas_contables = subcuenta_segundo_cuentas_contables.idsubcuenta_primer
				and cuenta_cuentas_contables.idcuenta_cuentas_contables = subcuenta_primer_cuentas_contables.idcuenta
				and rubro_cuentas_contables.idrubro_cuentas_contables = cuenta_cuentas_contables.idrubro
				and subgrupo_cuentas_contables.idsubgrupo_cuentas_contables = rubro_cuentas_contables.idsubgrupo
				and grupo_cuentas_contables.idgrupos_cuentas_contables = subgrupo_cuentas_contables.idgrupo";
			if($subcuenta_primerBuscar != "0"){
				$query .= " and subcuenta_segundo_cuentas_contables.idsubcuenta_primer = '".$subcuenta_primerBuscar."'";
			}
			
			$query .= " and subcuenta_segundo_cuentas_contables.denominacion like '%".$campoBuscar."%'
			order by codigo_grupo, codigo_subgrupo, codigo_rubro, codigo_cuenta, codigo_subcuenta_primer, codigo_subcuenta_segundo";
		  //echo $query;
		  $sql_consultar = mysql_query($query);
          while($bus_consultar = mysql_fetch_array($sql_consultar)){
		  ?>
                  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                  	
                    <td class='Browse' align='left' width="35%" style="font-size:10px"><?=$bus_consultar["codigo_grupo"].".".$bus_consultar["codigo_subgrupo"].".".$bus_consultar["codigo_rubro"].".".$bus_consultar["codigo_cuenta"].".".$bus_consultar["codigo_subcuenta_primer"].". ".$bus_consultar["denominacion_subcuenta_primer"]?></td>
                    <td class='Browse' align='left' width="8%" style="font-size:10px"><?=$bus_consultar["codigo_grupo"].".".$bus_consultar["codigo_subgrupo"]?>.<?=$bus_consultar["codigo_rubro"]?>.<?=$bus_consultar["codigo_cuenta"]?>.<?=$bus_consultar["codigo_subcuenta_primer"]?>.<?=$bus_consultar["codigo_subcuenta_segundo"]?></td>
                    <td class='Browse' align='left' width="58%" style="font-size:10px"><?=$bus_consultar["denominacion_subcuenta_segundo"]?></td>
                    <td width="3%" align="center" valign="middle" class='Browse'>
                      <img src="imagenes/modificar.png"
                            style="cursor:pointer"
                            onClick="seleccinarModificar('<?=$bus_consultar["codigo_subcuenta_segundo"]?>', '<?=$bus_consultar["denominacion_subcuenta_segundo"]?>', '<?=$bus_consultar["idsubcuenta_primer"]?>', '<?=$bus_consultar["idsubcuenta_segundo"]?>')">                    
                    </td>

                    <td width="5%" align="center" valign="middle" class='Browse'>
                      <img src="imagenes/delete.png"
                            style="cursor:pointer"
                            onClick="seleccinarEliminar('<?=$bus_consultar["codigo_subcuenta_segundo"]?>', '<?=$bus_consultar["denominacion_subcuenta_segundo"]?>', '<?=$bus_consultar["idsubcuenta_primer"]?>', '<?=$bus_consultar["idsubcuenta_segundo"]?>')">                    
                    </td>
                            
   		        </tr>
          <?
          }
          ?>
  </table>
<?
}

?>
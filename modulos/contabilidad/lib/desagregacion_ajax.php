<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
Conectarse();
extract($_POST);

if($ejecutar == "ingresarGrupo"){
	$sql_consultar = mysql_query("select * from desagregacion_cuentas_contables where idsubcuenta_segundo = '".$idsubcuenta_segundo."' and codigo = '".$codigo."'");
	$num_consultar = mysql_fetch_array($sql_consultar);
	if($num_consultar == 0){
		$sql_ingresar = mysql_query("insert into desagregacion_cuentas_contables(idsubcuenta_segundo, codigo, denominacion)values('".$idsubcuenta_segundo."', '".$codigo."', '".$denominacion."')");
	}else{
		echo "existe";
	}
}

if($ejecutar == "editarGrupo"){
	$sql_ingresar = mysql_query("update desagregacion_cuentas_contables set idsubcuenta_segundo = '".$idsubcuenta_segundo."', codigo = '".$codigo."', denominacion = '".$denominacion."' where iddesagregacion_cuentas_contables = '".$iddesagregacion."'")or die(mysql_error());
}

if($ejecutar == "eliminarGrupo"){
	$sql_ingresar = mysql_query("delete from desagregacion_cuentas_contables where iddesagregacion_cuentas_contables = '".$iddesagregacion."'");
}



if($ejecutar == "listarGrupos"){
?>
<table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="85%">
          <thead>
          <tr>
            <td width="40%" align="center" class="Browse" style="font-size:9px">Sub cuenta de Segundo Orden</td>
            <td width="10%" align="center" class="Browse" style="font-size:9px">C&oacute;digo</td>
            <td width="50%" align="center" class="Browse" style="font-size:9px">Denominaci&oacute;n</td>
            <td align="center" class="Browse" style="font-size:9px" colspan="2">Seleccionar</td>
          </tr>
          </thead>
          <?
		  $query = "SELECT grupo_cuentas_contables.codigo as codigo_grupo,
			subgrupo_cuentas_contables.codigo as codigo_subgrupo,
			rubro_cuentas_contables.codigo as codigo_rubro,
			cuenta_cuentas_contables.codigo as codigo_cuenta,
			subcuenta_primer_cuentas_contables.codigo as codigo_subcuenta_primer,
			subcuenta_segundo_cuentas_contables.codigo as codigo_subcuenta_segundo,
			subcuenta_segundo_cuentas_contables.denominacion as denominacion_subcuenta_segundo,
			subcuenta_segundo_cuentas_contables.idsubcuenta_segundo_cuentas_contables as idsubcuenta_segundo,
			desagregacion_cuentas_contables.codigo as codigo_desagregacion,
			desagregacion_cuentas_contables.denominacion as denominacion_desagregacion,
			desagregacion_cuentas_contables.iddesagregacion_cuentas_contables as iddesagregacion
				FROM 
			grupo_cuentas_contables,
			subgrupo_cuentas_contables,
			rubro_cuentas_contables,
			cuenta_cuentas_contables,
			subcuenta_primer_cuentas_contables,
			subcuenta_segundo_cuentas_contables,
			desagregacion_cuentas_contables
				WHERE
			subcuenta_segundo_cuentas_contables.idsubcuenta_segundo_cuentas_contables = desagregacion_cuentas_contables.idsubcuenta_segundo
			and subcuenta_primer_cuentas_contables.idsubcuenta_primer_cuentas_contables = subcuenta_segundo_cuentas_contables.idsubcuenta_primer
			and cuenta_cuentas_contables.idcuenta_cuentas_contables = subcuenta_primer_cuentas_contables.idcuenta
			and rubro_cuentas_contables.idrubro_cuentas_contables = cuenta_cuentas_contables.idrubro
			and subgrupo_cuentas_contables.idsubgrupo_cuentas_contables = rubro_cuentas_contables.idsubgrupo
			and grupo_cuentas_contables.idgrupos_cuentas_contables = subgrupo_cuentas_contables.idgrupo";
			
			if($subcuenta_segundoBuscar != "0"){
				$query .= " and desagregacion_cuentas_contables.idsubcuenta_segundo = '".$subcuenta_segundoBuscar."'";
			}
			
			$query .= " and desagregacion_cuentas_contables.denominacion like '%".$campoBuscar."%'
			order by codigo_grupo, codigo_subgrupo, codigo_rubro, codigo_cuenta, codigo_subcuenta_primer, codigo_subcuenta_segundo, codigo_desagregacion";
		  //echo $query;
		  $sql_consultar = mysql_query($query)or die(mysql_error());
          while($bus_consultar = mysql_fetch_array($sql_consultar)){
		  ?>
                  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                  	
                    <td class='Browse' align='left' width="40%" style="font-size:10px"><?=$bus_consultar["codigo_grupo"].".".$bus_consultar["codigo_subgrupo"].".".$bus_consultar["codigo_rubro"].".".$bus_consultar["codigo_cuenta"].".".$bus_consultar["codigo_subcuenta_primer"].".".$bus_consultar["codigo_subcuenta_segundo"].". ".$bus_consultar["denominacion_subcuenta_segundo"]?></td>
                    <td class='Browse' align='left' width="10%" style="font-size:10px"><?=$bus_consultar["codigo_grupo"].".".$bus_consultar["codigo_subgrupo"]?>.<?=$bus_consultar["codigo_rubro"]?>.<?=$bus_consultar["codigo_cuenta"]?>.<?=$bus_consultar["codigo_subcuenta_primer"]?>.<?=$bus_consultar["codigo_subcuenta_segundo"]?>.<?=$bus_consultar["codigo_desagregacion"]?></td>
                    <td class='Browse' align='left' width="50%" style="font-size:10px"><?=$bus_consultar["denominacion_desagregacion"]?></td>
                    <td width="3%" align="center" valign="middle" class='Browse'>
                      <img src="imagenes/modificar.png"
                            style="cursor:pointer"
                            onClick="seleccinarModificar('<?=$bus_consultar["codigo_desagregacion"]?>', '<?=$bus_consultar["denominacion_desagregacion"]?>', '<?=$bus_consultar["idsubcuenta_segundo"]?>', '<?=$bus_consultar["iddesagregacion"]?>')">                    
                    </td>

                    <td width="5%" align="center" valign="middle" class='Browse'>
                      <img src="imagenes/delete.png"
                            style="cursor:pointer"
                            onClick="seleccinarEliminar('<?=$bus_consultar["codigo_desagregacion"]?>', '<?=$bus_consultar["denominacion_desagregacion"]?>', '<?=$bus_consultar["idsubcuenta_segundo"]?>', '<?=$bus_consultar["iddesagregacion"]?>')">                    
                    </td>
                            
   		        </tr>
          <?
          }
          ?>
  </table>
<?
}

?>
<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
$conexion = Conectarse();
extract($_POST);

switch($ejecutar){
	case "ingresarNomenclatura":
	$sql_ingresar = mysql_query("insert into nomenclatura_fichas(descripcion,
																		numero)VALUES('".$descripcion."',
																						'".$numero."')");
	break;
	case "listarNomenclatura":
		$sql_consulta = mysql_query("select * from nomenclatura_fichas");
		?>
		<table width="30%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
        <thead>
          <tr>
            <td width="69%" class="Browse"><div align="center">Descripcion</div></td>
            <td width="20%" class="Browse"><div align="center">Numero</div></td>
            <td class="Browse" colspan="2"><div align="center">Acciones</div></td>
          </tr>
          </thead>
		<?
		while($bus_consulta = mysql_fetch_array($sql_consulta)){
			?>
			<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                <td class='Browse' align='left'>&nbsp;<?=$bus_consulta["descripcion"]?></td>
                <td class='Browse' align='right'>&nbsp;<?=$bus_consulta["numero"]?></td>
                <td width="1%" align='center' class='Browse'><img src="imagenes/modificar.png" style="cursor:pointer" onclick="seleccionarDatos('<?=$bus_consulta["descripcion"]?>','<?=$bus_consulta["numero"]?>', '<?=$bus_consulta["idnomenclatura_fichas"]?>')"></td>
              <td width="1%" align='center' class='Browse'><img src="imagenes/delete.png" style="cursor:pointer" onclick="seleccionarDatos('<?=$bus_consulta["descripcion"]?>','<?=$bus_consulta["numero"]?>', '<?=$bus_consulta["idnomenclatura_fichas"]?>')"></td>
          </tr>
			<?
		}
		?>
		</table>
		<?
	break;
	case "modificarNomenclatura":
		$sql_modificar = mysql_query("update nomenclatura_fichas 
												set descripcion = '".$descripcion."',
												numero = '".$numero."'
												where 
												idnomenclatura_fichas = '".$idnomenclatura_fichas."'")or die(mysql_error());																		
	break;
	case "eliminarNomenclatura":
		$sql_eliminar = mysql_query("delete from nomenclatura_fichas where idnomenclatura_fichas ='".$idnomenclatura_fichas."'");		
	break;
}
?>
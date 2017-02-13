<?
include "../conf/conex.php";
conectarse();
extract($_GET);
extract($_POST);

if ($ejecutar == "mostrarLista") {
    $sql_beneficiarios = mysql_query("select * from beneficiarios where nombre like '%" . $valor . "%'");
    $num_beneficiarios = mysql_num_rows($sql_beneficiarios);

    if ($num_beneficiarios > 0) {
        ?>
		<table width="100%" border="0" cellpadding="3" cellspacing="0">
	<?
        $sql_beneficiarios = mysql_query("select * from beneficiarios where nombre like '%" . $valor . "%'");
        while ($bus_beneficiarios = mysql_fetch_array($sql_beneficiarios)) {
            ?>
        <tr id="lineaBeneficiarios" style="cursor:pointer" onClick="document.form1.buscarBeneficiario.value='<?=$bus_beneficiarios["rif"]?>', document.form1.submit()">
        	<td style="border-bottom:#EFEFEF 1px solid; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px" valign="top">
				<strong>-</strong>&nbsp;<?=$bus_beneficiarios["rif"];?>
                </td>
            <td style="border-bottom:#EFEFEF 1px solid; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px" valign="top">
			<?=$bus_beneficiarios["nombre"];?>
            </td>
        </tr>
	<?
        }
        ?>
    	</table>
<?
    }
}

if ($ejecutar == "seleccionarTipo") {
    $sql   = "SELECT idtipos_documentos, descripcion FROM tipos_documentos WHERE modulo = '" . $idmodulo . "' ORDER BY descripcion";
    $query = mysql_query($sql) or die($sql . mysql_error());
    ?>
    <select id="idtipo" name="idtipo">
    <?
    while ($field = mysql_fetch_array($query)) {

        ?>
        <option value=<?=$field[0]?>><?=$field[1]?></option>
        <?
    }?>
    </select>
	<?
    return;
}

if ($ejecutar == "mostrarListaMateriales") {
    $sql_articulos = mysql_query("select * from articulos_servicios where descripcion like '%" . $valor . "%'");
    $num_articulos = mysql_num_rows($sql_articulos);

    if ($num_articulos > 0) {
        ?>
		<table width="100%" border="0" cellpadding="3" cellspacing="0">
	<?
        $sql_articulos = mysql_query("select * from articulos_servicios where descripcion like '%" . $valor . "%'");
        while ($bus_articulos = mysql_fetch_array($sql_articulos)) {
            ?>
        <tr id="celdaMateriales" style="cursor:pointer" onClick="document.form1.id_material.value=<?=$bus_articulos["idarticulos_servicios"]?>, document.form1.submit()">
        	<td width="28%" valign="top" style="border-bottom:#EFEFEF 1px solid; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px">
				<strong>-</strong>&nbsp;<?=$bus_articulos["codigo"];?>                </td>
            <td width="72%" valign="top" style="border-bottom:#EFEFEF 1px solid; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px">
			<?=$bus_articulos["descripcion"];?>            </td>
        </tr>
	<?
        }
        ?>
    	</table>
<?
    }
}
?>
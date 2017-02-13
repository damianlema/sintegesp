<?
include("../../../conf/conex.php");
conectarse();
extract($_GET);
extract($_POST);

if($ejecutar == "seleccionarTipo"){
	$sql="SELECT idtipos_documentos, descripcion FROM tipos_documentos WHERE modulo like '%-".$idmodulo."-%' ORDER BY descripcion";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	?>
    <select id="idtipo" name="idtipo">
    <option value="0">.:: Seleccione ::.</option>
    <?
	while ($field=mysql_fetch_array($query)) {
	
		?>
        <option value=<?=$field[0]?>><?=$field[1]?></option>
        <? 
	} ?>
    </select>
	<?
	return;
}


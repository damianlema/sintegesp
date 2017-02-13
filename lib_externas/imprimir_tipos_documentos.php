<?
include("conf/conex.php");
Conectarse();
$sql_consultar = mysql_query("select * from tipos_documentos where compromete = 'si' and causa = 'no' order by descripcion");
?>
<table>
<tr>
    <td><strong>Codigo</strong></td>
    <td><strong>Descripcion</strong></td>
    </tr>
<?
while($bus_consultar = mysql_fetch_array($sql_consultar)){
	?>
    <tr>
    <td><?=$bus_consultar["siglas"]?></td>
    <td><?=$bus_consultar["descripcion"]?></td>
    </tr>
	<?
}
?>
</table>
<script>
window.print();
</script>
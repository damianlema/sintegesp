<?
include_once("../../conf/conex.php");
Conectarse();
?>
	<link href="../../css/theme/green/main.css" rel="stylesheet" type="text/css">
	<script src="../../js/function.js" type="text/javascript" language="javascript"></script>

	<br>
	<h4 align=center>Listado de Tablas de Constantes</h4>
	<h2 class="sqlmVersion"></h2>
	<br>

<table width="80%" align="center" cellpadding="0" cellspacing="0" class="Browse" >
        <thead>
            <tr>
                <td width="10%" align="center" class="Browse">Codigo</td>
                <td width="23%" align="center" class="Browse">Descripcion</td>
                <td width="25%" align="center" class="Browse">Desde</td>
                <td width="26%" align="center" class="Browse">Hasta</td>
                <td width="16%" align="center" class="Browse">Unidad</td>
                <td width="16%" align="center" class="Browse">Accion</td>
            </tr>
        </thead>
        <?
        $sql_consulta = mysql_query("select * from tabla_constantes");
		$n = 0;
		while($bus_consulta = mysql_fetch_array($sql_consulta)){
		$n++;
		?>
		<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" onclick="opener.consultarTablaConstantes('<?=$bus_consulta["idtabla_constantes"]?>'), window.close()" style="cursor:pointer">
       		<td align="center" class='Browse'><?=$bus_consulta["codigo"]?></td>
			<td align="center" class='Browse'><?=$bus_consulta["descripcion"]?></td>
            <td align="center" class='Browse'><?=$bus_consulta["desde"]?></td>
            <td align="center" class='Browse'><?=$bus_consulta["hasta"]?></td>
            <td align="center" class='Browse'><?=$bus_consulta["unidad"]?></td>
            <td align="center" class='Browse'><img src="../../imagenes/validar.png" onclick="opener.consultarTablaConstantes('<?=$bus_consulta["idtabla_constantes"]?>'), window.close()" style="cursor:pointer"></td>
		</tr>
        <?
		}
		?>
        </table>
<?
session_start();
include("../../conf/conex.php");
Conectarse();

?>
	<link href="../../css/theme/green/main.css" rel="stylesheet" type="text/css">
	<script src="../../js/function.js" type="text/javascript" language="javascript"></script>
	<br>
	<h4 align=center>Estructura Organizativa</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
<form method="post" action="">
<table align="center">
    <tr>
    	<td >Texto a Buscar</td>
        <td><input type="text" id="texto_buscar" name="texto_buscar"></td>
        <td><input type="submit" id="buscar" name="buscar" value="Buscar" class="button"></td>
    </tr>
</table>     
</form>

<?

if($_POST){
	
	
$sql_consulta = mysql_query("select niveles_organizacionales.organizacion,
									niveles_organizacionales.codigo,
									niveles_organizacionales.denominacion,
									niveles_organizacionales.sub_nivel,
									niveles_organizacionales.idniveles_organizacionales,
									organizacion.denominacion as denominacion_organizacion,
									organizacion.codigo as codigo_organizacion 
										from 
									niveles_organizacionales, organizacion
										where 
									organizacion.idorganizacion = niveles_organizacionales.organizacion
									and niveles_organizacionales.modulo= '".$_SESSION["modulo"]."'
									and niveles_organizacionales.denominacion like '%".$_POST["texto_buscar"]."%'
										order by niveles_organizacionales.organizacion, niveles_organizacionales.codigo ")or die(mysql_error());


?>

<table width="100%" align="center" cellpadding="0" cellspacing="0" class="Main">
	<tr>
        <td align="center">
            <table width="80%" border="0" align=center cellpadding="0" cellspacing="0" class="Browse">
                <thead>
                <tr>
                	<td width="12%" align="center" class="Browse">C&oacute;digo</td>
                    <td width="76%" align="center" class="Browse">Denominaci&oacute;n</td>
                    <td align="center" class="Browse">Acci&oacute;n</td>
                </tr>
                </thead>
                <?
                while($bus_consulta = mysql_fetch_array($sql_consulta)){
                ?>
                <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onclick="opener.document.getElementById('idniveles_organizacionales').value = '<?=$bus_consulta["idniveles_organizacionales"]?>', opener.document.getElementById('unidad_destino').value = '<?=$bus_consulta["codigo_organizacion"]."-".$bus_consulta["codigo"]."-".$bus_consulta["denominacion"]?>', window.close()">
                    <td class='Browse'><?=$bus_consulta["codigo_organizacion"]."-".$bus_consulta["codigo"]?></td>
                    <td class='Browse' align="left"><?=$bus_consulta["denominacion"]?></td>
                    <td width="6%" align="center" class='Browse'>
                    <img src="../../imagenes/validar.png" 
                    style="cursor:pointer"
                    onclick="opener.document.getElementById('idniveles_organizacionales').value = '<?=$bus_consulta["idniveles_organizacionales"]?>', opener.document.getElementById('unidad_destino').value = '<?=$bus_consulta["codigo_organizacion"]."-".$bus_consulta["codigo"]."-".$bus_consulta["denominacion"]?>', window.close()">                                
                	</td>
                </tr>
                <?
                }
                ?>
            </table>
                        
					
      </td>
    </tr>
  </table>
  <?
  }
  ?>
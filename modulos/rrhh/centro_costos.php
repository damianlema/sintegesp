<script src="modulos/rrhh/js/centro_costos_ajax.js"></script>
  <br>
<h4 align=center>
Centro de Costos
</h4>
<h2 class="sqlmVersion"></h2>
<br>
<br>

<?
	function listar_foros($padre, $titulo) {
	global $foros;
	foreach($foros[$padre] as $foro => $datos) {			
		if(isset($foros[$foro])) {
			$nuevo_titulo = ($titulo == '') ? $datos['denominacion'] : "$titulo - {$datos['denominacion']} -";
			listar_foros($foro, $nuevo_titulo);
		}else{
		?>
		<option value="<?=$datos['idniveles_organizacionales']?>" onClick="consultarCategoria('<?=$datos['idniveles_organizacionales']?>')">
			<?=$titulo ." - ". $datos['denominacion']?>
		</option>
		<?
		}
	}
	}

?>
<br>
<br>
<table width="73%" border="0" align="center" cellpadding="2" cellspacing="0">
  <tr>
    <td width="23%" align="right" class='viewPropTitle'>Unidad Funcional</td>
    <td width="77%">
      <?
      /*$foros = array();
	  $result = mysql_query("SELECT idniveles_organizacionales, 
									denominacion, 
									sub_nivel 
										FROM 
									niveles_organizacionales
										WHERE
									modulo = 1") or die(mysql_error());
	  while($row = mysql_fetch_assoc($result)) {
		  $foro = $row['idniveles_organizacionales'];
		  $padre = $row['sub_nivel'];
		  if(!isset($foros[$padre]))
			  $foros[$padre] = array();
		  $foros[$padre][$foro] = $row;
	  }
*/	

			$sql_ubicacion_funcional = mysql_query("select * from niveles_organizacionales where modulo = '1' order by codigo");
			?> 
            <select id="ubicacion_funcional" name="ubicacion_funcional">
                <option value="0">.:: Seleccione ::.</option>
					<?
                    while($bus_ubicacion_funcional = mysql_fetch_array($sql_ubicacion_funcional)){
                    ?>
                    <option value="<?=$bus_ubicacion_funcional["idniveles_organizacionales"]?>" onClick="consultarCategoria('<?=$bus_ubicacion_funcional['idniveles_organizacionales']?>')">(<?=$bus_ubicacion_funcional["codigo"]?>)&nbsp;<?=$bus_ubicacion_funcional["denominacion"]?>
                    </option>
                    <?	
                    }
                    ?>
            </select>    </td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Categoria Programatica</td>
    <td><table width="664" border="0" align="left" cellpadding="0" cellspacing="0">
      <tr>
        <td>
            <input type="text" name="nombre_categoria" id="nombre_categoria" size="100" readonly="readonly" value="<?=$bus_configuracion_categoria["denominacion"]?>"/>
            <input type="hidden" name="id_categoria_programatica" id="id_categoria_programatica" value="<?=$bus_configuracion_categoria["idcategoria_programatica"]?>"/>
        </td>
        <td align="left"><img style="display:block; cursor:pointer"
                                                src="imagenes/search0.png" 
                                                title="Buscar Categoria Programatica" 
                                                id="buscarCategoriaProgramatica" 
                                                name="buscarCategoriaProgramatica"
                                                onclick="window.open('lib/listas/lista_categorias_programaticas.php?destino=orden_compra','listar Categorias programaticas','resizable = no, scrollbars=yes, width=900, height = 500')" 
                                                 /></td>
        <td width="216" align="right">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><table border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td>
          <input type="submit" name="boton_actualizar" id="boton_actualizar" value="Actualizar" class="button" onClick="actualizarCentroCostos()">
        </td>
      </tr>
    </table></td>
  </tr>
</table>

<script src="modulos/nomina/js/hoja_tiempo_ajax.js"></script>
	<br>
	<h4 align=center>Hoja de Tiempo</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
	<br>
    
    
    <input type="hidden" id="idhoja_tiempo" name="idhoja_tiempo">
    <div align="center">
    	<img src="imagenes/search0.png" style="cursor:pointer" onClick="window.open('lib/listas/listar_hoja_tiempo.php','','width=900, height=600, resizable=no, scrollbars=yes')" title="Buscar Hoja de Tiempo Creada">
        &nbsp;
        <img src="imagenes/nuevo.png" style="cursor:pointer" onClick="window.location.href= 'principal.php?modulo=13&accion=945'" title="Crear una Nueva Hoja de Tiempo">
        &nbsp;
       <img src="imagenes/imprimir.png" style="cursor:pointer; visibility:hidden;" id="btImprimir" title="Imprimir" onclick="document.getElementById('divImprimir').style.display='block'; pdf.location.href='lib/reportes/nomina/reportes.php?nombre=nomina_hoja_tiempo&idhoja_tiempo='+document.getElementById('idhoja_tiempo').value; document.getElementById('pdf').style.display='block';">
    
        <div id="divImprimir" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid; width:50%; left:25%">
            <div align="right">
                <a href="#" onClick="document.getElementById('divImprimir').style.display='none'; document.getElementById('pdf').style.display='none';">X</a>
            </div>
            <iframe name="pdf" id="pdf" style="display:none; width:99%; height:550px;"></iframe>   
        </div>
    </div>
    <br>
<br>
<table width="90%" border="0" align="center">
  <tr>
    <td align="right" class='viewPropTitle'>Tipo de Hoja de Tiempo</td>
    <td>
      
      <?
    $sql_consultar_tipo_hoja = mysql_query("select * from tipo_hoja_tiempo");
	?>
      <select name="tipo_hoja_tiempo" id="tipo_hoja_tiempo">
        <option value="0">.:: Seleccione ::.</option>
		<?
        while($bus_consultar_tipo_hoja = mysql_fetch_array($sql_consultar_tipo_hoja)){
			?>
        <option value="<?=$bus_consultar_tipo_hoja["idtipo_hoja_tiempo"]?>"><?=$bus_consultar_tipo_hoja["descripcion"]?></option>
        <?	
		}
		?>
      </select>
    </td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Tipo de Nomina</td>
    <td>
      <?
    $sql_consultar_tipo_nomina = mysql_query("select * from tipo_nomina");
	?>
      <select name="tipo_nomina" id="tipo_nomina">
        <option value="0">.:: Seleccione ::.</option>
		<?
        while($bus_consultar_tipo_nomina = mysql_fetch_array($sql_consultar_tipo_nomina)){
			?>
        <option value="<?=$bus_consultar_tipo_nomina["idtipo_nomina"]?>" onClick="seleccionarPeriodo('<?=$bus_consultar_tipo_nomina["idtipo_nomina"]?>')"><?=$bus_consultar_tipo_nomina["titulo_nomina"]?></option>
        <?	
		}
		?>
      </select>
    </td>
  </tr>
    <? /*
    <tr>
    <td align="right" class='viewPropTitle'>Centro de Costos</td>
    <td>
     <?
      function listar_foros($padre, $titulo){
		global $foros;
		foreach($foros[$padre] as $foro => $datos) {			
			if(isset($foros[$foro])) {
				$nuevo_titulo = ($titulo == '') ? $datos['denominacion'] : "$titulo - {$datos['denominacion']} -";
				listar_foros($foro, $nuevo_titulo);
			}else{
			?>
			<option value="<?=$datos['idniveles_organizacionales']?>">
				<?=$titulo ." - ". $datos['denominacion']?>
			</option>
			<?
			}
		}
	  }
	  
	  
	  $foros = array();
	  $result = mysql_query("SELECT idniveles_organizacionales, 
									denominacion, 
									sub_nivel 
										FROM 
									niveles_organizacionales
										WHERE
									modulo = 1
									and idcategoria_programatica != 0") or die(mysql_error());
	  while($row = mysql_fetch_assoc($result)) {
		  $foro = $row['idniveles_organizacionales'];
		  $padre = $row['sub_nivel'];
		  if(!isset($foros[$padre]))
			  $foros[$padre] = array();
		  $foros[$padre][$foro] = $row;
	  }
	
		$sql_centro_costo = mysql_query("select no.idniveles_organizacionales,
												un.denominacion,
												ct.codigo
												from 
												niveles_organizacionales no,
												unidad_ejecutora un,
												categoria_programatica ct
												where 
												no.modulo='1' 
												and no.idcategoria_programatica != '0'
												and ct.idcategoria_programatica = no.idcategoria_programatica
												and ct.idunidad_ejecutora = un.idunidad_ejecutora
												order by ct.codigo");
		
		?>
            <select id="centro_costo" name="centro_costo">
              <option value="0">.:: Seleccione ::.</option>
              <?
              while($bus_centro_costo = mysql_fetch_array($sql_centro_costo)){
                  ?>
                  <option value="<?=$bus_centro_costo["idniveles_organizacionales"]?>">(<?=$bus_centro_costo["codigo"]?>)&nbsp;<?=$bus_centro_costo["denominacion"]?></option>
                  <?
                }
              ?>
            </select>
    
    </td>
  </tr>
 */ ?>
  <tr>
    <td align="right" class='viewPropTitle'>Periodo</td>
    <td id="celda_centro_costo">
      <select name="periodo" id="periodo">
      <option value="0">.:: Seleccione un tipo de Nomina ::.</option>
      </select>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      
      
        <table>
        <tr>
          <td><input type="submit" name="boton_ingresar" id="boton_ingresar" value="Ingresar" class="button" onClick="ingresarDatos()" style="display:block"><td>
          <td><input type="submit" name="boton_modificar" id="boton_modificar" value="Modificar" class="button" onClick="modificarDatos()" style="display:none"><td>
          <td><input type="submit" name="boton_eliminar" id="boton_eliminar" value="Eliminar" class="button" onClick="eliminarDatos()" style="display:none"><td>
          <td><input type="submit" name="boton_duplicar" id="boton_duplicar" value="Duplicar" class="button" onClick="duplicarDatos()" style="display:none"><td>

        </tr>
        </table>

        <table align="left" id="tabla_prefijar" style="display: none">
            <tr>
                <td>&nbsp;<td>
                <td>&nbsp;<td>
                <td align="left">Prefijar Valor<td>
                <td align="left"><input type="text"
                                        name="prefijar_valor"
                                        id="prefijar_valor"
                                        style="text-align:right"
                                        size="8"
                                        value=""
                                        ><td>
                <td><img src="imagenes/fast_forward.png" style="cursor:pointer" onClick="guardarHorasPrefijadas()" title="Prefijar Valor">
                <td>
            </tr>
        </table>
    </td>
  </tr>
</table>


<br>
<div id="listaTrabajadores">

</div>

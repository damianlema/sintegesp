<script src="modulos/almacen/js/inventario_materia_ajax.js"></script>


<?php
if($_POST["ingresar"]){
	$_GET["accion"] = 173;
}
?>



 <style type="text/css">
 
 
<!--
    body {
        margin:0;
        padding:0;
        font: bold 10px/1.5em Verdana;
}

h2 {
        font: bold 12px Verdana, Arial, Helvetica, sans-serif;
        color: #000;
        margin: 0px;
        padding: 0px 0px 0px 15px;
}

/*- Menu Tabs F--------------------------- */

    #tabsF {
      float:left;
      width:100%;
      background:;
      font-size:93%;
      line-height:normal;
          border-bottom:1px solid #666;
      }
    #tabsF ul {
        margin:0;
        padding:2px 2px 0 10px;
        list-style:none;
      }
    #tabsF li {
      display:inline;
      margin:0;
      padding:0;
      }
    #tabsF a {
      float:left;
      background:url("imagenes/tableftF.gif") no-repeat left top;
      margin:0;
      padding:0 0 0 4px;
      text-decoration:none;
      }
    #tabsF a span {
      float:left;
      display:block;
      background:url("imagenes/tabrightF.gif") no-repeat right top;
      padding:5px 15px 4px 6px;
      color:#666;
      }
    /* Commented Backslash Hack hides rule from IE5-Mac \*/
    #tabsF a span {float:none;}
    /* End IE5-Mac hack */
    #tabsF a:hover span {
      color:#FFF;
      }
    #tabsF a:hover {
      background-position:0% -42px;
      }
    #tabsF a:hover span {
      background-position:100% -42px;
      }

        #tabsF #current a {
                background-position:0% -42px;
        }
        #tabsF #current a span {
                background-position:100% -42px;
        }
-->
</style>


<SCRIPT language=JavaScript>

document.oncontextmenu=new Function("return false")
<!--- oculta el script para navegadores antiguos
var miPopUp
var w=0

function solonumeros(e){
var key;
if(window.event)
	{key=e.keyCode;}
else if(e.which)
	{key=e.which;}
if (key < 48 || key > 57)
	{return false;}
return true;
}
// end hiding from old browsers -->
</SCRIPT>


	<body >
    <table align="center">
    	<tr><td>
			<h4 align=center>Materiales</h4></td>
    
    	<td>
    		<button name="listado" type="button" style="background-color:white;border-style:none;cursor:pointer;" onClick="abreVentana()"><img src='imagenes/search0.png' title="Buscar Materiales"></button>
                    
                    &nbsp;<a href="principal.php?modulo=19&accion=1088"><img src="imagenes/nuevo.png" border="0" title="Nuevo Material"></a>
                    <a href="#" onClick="document.getElementById('divTipoOrden').style.display='block';"><img src="imagenes/imprimir.png" border="0" title="Imprimir"></a>
                    <div id="divTipoOrden" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
                    <div align="right"><a href="#" onClick="document.getElementById('divTipoOrden').style.display='none'; document.getElementById('pdf').style.display='none'; document.getElementById('tableImprimir').style.display='block';">X</a></div>
                    <table id="tableImprimir">
                        <tr>
                            <td>Ordenar Por: </td>
                            <td>
                                <select name="ordenarPor" id="ordenarPor">
                                                    <option value="cedula">C&eacute;dula</option>
                                                    <option value="nombres">Nombres</option>
                                                    <option value="apellidos">Apellidos</option>
                                </select>               
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="pdf.location.href='lib/reportes/recursos_humanos/reportes.php?nombre=listatrab&orden='+document.getElementById('ordenarPor').value; document.getElementById('pdf').style.display='block'; document.getElementById('tableImprimir').style.display='none';">
                            </td>
                        </tr>
                    </table>
          			<iframe name="pdf" id="pdf" style="display:none" height="500" width="500"></iframe>          
          			</div>
              
    
    </td></tr></table>
    
    
    
	<h2 class="sqlmVersion"></h2>
    
    <input type="hidden" name="idinventario_materia" id="idinventario_materia">
    
	    
        <table align="center" width="80%" >
            <tr>
              <td align='center' width="15%" bgcolor="#3399FF" ><strong>C&oacute;digo</strong></td>
              <td align='center' width="75%"bgcolor="#3399FF" ><strong>Descripci&oacute;n</strong> </td>
              <td align='center' width="10%"bgcolor="#3399FF" ><strong>Existencia </strong></td>
            </tr>
            <tr>
              <td width="18%" align='center' bgcolor="#ccc" id="codigo_buscar" style="font-weight:bold; font-size: 16px; border:#666666 solid 1px"><strong>&nbsp;</strong></td>
              <td width="55%" align='left' bgcolor="#ccc" id="descripcion_buscar" style="font-weight:bold; font-size: 14px; border:#666666 solid 1px"><strong>&nbsp;</strong></td>
              <td width="27%" align='center' bgcolor="#ccc" id="existencia" style="font-weight:bold; font-size: 16px; border:#666666 solid 1px"><strong>&nbsp;</strong></td>
           </tr>
        </table>
<br>

<script>
function mostrarPestana(div){
  if(div == 'div_existencia' && document.getElementById('div_existencia').style.display == 'none'){
    updateExistencia();
  }
  if(div == 'div_ubicacion' && document.getElementById('div_ubicacion').style.display == 'none'){
    seleccionarUbicacion(document.getElementById("almacen").value,document.getElementById("iddistribucion_almacen").value);
  }
	document.getElementById('div_datosBasicos').style.display = 'none';
	document.getElementById('div_registroFotografico').style.display = 'none';
	document.getElementById('div_ubicacion').style.display = 'none';
	document.getElementById('div_existencia').style.display = 'none';
	document.getElementById('div_desagregaUnidad').style.display = 'none';
	document.getElementById('div_reemplazoMateria').style.display = 'none';
	document.getElementById('div_equivalenciaMateria').style.display = 'none';
	document.getElementById('div_accesoriosMateria').style.display = 'none';
	document.getElementById('div_compra_materia').style.display = 'none';
	document.getElementById('div_materia_articulos_servicios').style.display = 'none';
	document.getElementById(div).style.display = 'block';
}
</script>

<?
//var_dump($privilegios);
?>
<div id="tabsF">
    <ul>
        <li>
        	<a href="javascript:;" onClick="mostrarPestana('div_datosBasicos')">
        		<span>Datos B&aacute;sicos</span>
            </a>
        </li>
    </ul>
</div>

<br>
  <br>
<br>
        
       
       <!-- ********************************************* DATOS BASICOS *********************************************-->      
        
          <!-- <form name='trabajador' action='' method='POST'> -->
<div id="div_datosBasicos" style="display:block">


 	 <h4 align=center>Datos B&aacute;sicos</h4>
	<h2 class="sqlmVersion"></h2>
	<br>


	<div id="resultado">
        <form name='materia' action='' method='POST'>
		<table align=center cellpadding=2 cellspacing=0 width="80%">
			<tr>
            	<td align='right' class='viewPropTitle' >Tipo de Movimiento:</td>
            	<td colspan="5">
                <select name="tipo_movimiento_almacen" id="tipo_movimiento_almacen">
				  <?
                  $sql_tipo = mysql_query("select * from tipo_movimiento_almacen where afecta = '3' order by codigo")or die(mysql_error());
                  while($bus_tipo = mysql_fetch_array($sql_tipo)){
                  ?>
                    <option value="<?=$bus_tipo["id_tipo_movimiento_almacen"]?>"><?=$bus_tipo["codigo"]." - ".$bus_tipo["descripcion"]?></option>
                  <?
                  }
                  ?>
              </select>
                </td>
            </tr>        
        
			<tr >
			  <td width="20%" align='right' bgcolor="#3399FF" class='viewPropTitle'>C&oacute;digo:</td>
			  <td colspan="1" class='' >
              <input type="hidden" name="id_materia" id="id_materia">
              <input type="hidden" id="codigo_materia_automatico" name="codigo_materia_automatico" maxlength="20" size="20">
              
              <input type="text" id="codigo_materia" name="codigo_materia" maxlength="20" size="20">
              
              <img id='idgenerar_codigo' src='imagenes/refrescar.png' title="Generar C&oacute;digo" onClick="generar_codigo()" style="cursor:pointer" style="display:block"> 
             
              </td>
              <td colspan = "2" align='left' id="estado" style="font-weight:bold; font-size: 14px; "><strong>&nbsp;</strong></td>
              
			  <td width="25%"  rowspan="7" class='' ><div id="cuadroFoto" style="width:140px; height:160px; border:#666666 solid 2px"> <br><br><br>
  &nbsp;&nbsp;Sin Imagen </div></td>
  				
		    </tr>
          
			<tr>
				<td align='right' class='viewPropTitle' >Descripci&oacute;n:</td>
				<td class='' colspan="5" >
                 <textarea name="descripcion_materia" cols="80" rows="3" id="descripcion_materia" autocomplete="OFF" ></textarea>
                </td>
			</tr>
			<tr>
			  <td align='right' class='viewPropTitle'>Unidad:</td>
			  <td width="21%" class='' >
              <select name="unidad_medida" id="unidad_medida">
				  <?
                  $sql_unidad = mysql_query("select * from unidad_medida order by idunidad_medida")or die(mysql_error());
                  while($bus_unidad = mysql_fetch_array($sql_unidad)){
                  ?>
                    <option <?php if($bus_busqueda["idunidad_medida"] == $bus_unidad["idunidad_medida"]) { echo "selected"; }?> value="<?=$bus_unidad["idunidad_medida"]?>"><?=$bus_unidad["abreviado"]." - ".$bus_unidad["descripcion"]?></option>
                  <?
                  }
                  ?>
              </select>
              <td width="16%" align='right' class='viewPropTitle'>Cant. x Unidad:</td> 
              <td colspan="2"><input type="text" name="cantidad_unidad" maxlength="11" size="11" id="cantidad_unidad"></td>
            </tr>
		    <tr>
			  <td align='right' class='viewPropTitle'>C&oacute;digo de Catalogo:</td>
		      <td class='' colspan="5"> 
              		<input type="hidden" name="iddetalle_materias_almacen" id="iddetalle_materias_almacen">
                    <input name="detalle_materias_almacen" type="text" id="detalle_materias_almacen" size="80"> 
                    <img src="imagenes/search0.png" onClick="window.open('modulos/almacen/lib/lista_detalle_materia.php','','resizable = no, scrollbars=yes, width=900, height=600')" style="cursor:pointer"> 
              </td>
            </tr>
			<tr>
              <td align='right' class='viewPropTitle'>Tipo:</td>
			  <td class='' colspan="5" id="celda_tipo_detalle">
              <select name="idtipo_detalle" id="idtipo_detalle" style="width:50%">
				<option value="0">.:: Seleccione el C&oacute;digo de Catalogo ::.</option>
     		  </select>
              </td>
			 
		    </tr>
			<tr>
				<td align='right' class='viewPropTitle'>Marca:</td>
				<td class='viewProp' id="celda_marca" >
				<label><select name="marca_materia" id="marca_materia">
					<?php
						$marca_materia = mysql_query("select * from marca_materia");
						while($regmarca_materia = mysql_fetch_array($marca_materia))
							{ ?>
								<option  <?php if($regmarca_materia["seleccionada"] == '1') { echo "selected"; }?> value="<?=$regmarca_materia["idmarca_materia"]?>"> 
								 <?php 
								 echo $regmarca_materia["denominacion"];
								 echo "</option>";
								} ?>
				</select> </label>
				<img src='imagenes/add.png' onClick="window.open('principal.php?modulo=19&accion=1074&pop=si','agregar marca','resisable = no, scrollbars = yes, width=900, height = 500')" style="cursor:pointer">				</td>			
			    <td align='right' class='viewPropTitle'>Modelo:</td>
				<td colspan="2"><input name="modelo" type="text" id="modelo" size="20" maxlength="30"></td>
			 
			</tr>
          </table>
          <table align=center cellpadding=2 cellspacing=0 width="80%"> 
			<tr>
                <td width="20%" align='right' class='viewPropTitle'>Producto con seriales:</td>
				<td width="17%"  ><input type="checkbox" name="serializado" id="serializado" ></td>
                <td width="25%" align='right' class='viewPropTitle'>Tiene Fecha de Vencimiento:</td>
				<td width="38%"  ><input type="checkbox" name="fecha_vencimiento" id="fecha_vencimiento" ></td>
		    </tr>
            <tr>
                <td align='right' class='viewPropTitle'>Utilidad:</td>
				<td >
                	<select name="utilidad" id="utilidad">
                        <option value="">.: Seleccione :.</option>
                        <option value="activo_fijo">Activo Fijo</option>
                        <option value="bien_consumo">Bien de Consumo</option>
                        <option value="complemento">Complemento</option>
                    </select></td>
               <td width="25%" align='right' class='viewPropTitle'>Garantia (si aplica):</td>
				<td width="38%"  ><input type="text" name="garantia" id="garantia" size="20" ></td> 
		    </tr>
           <? /* <div id="activo" style="display:none">
            <tr> 
            <td width="20%" align="right" class='viewPropTitle'>C&oacute;digo de Catalogo</td>
            <td colspan="3">
                <table>
                    <tr>
                        <td>
                            <input type="hidden" name="idcatalogo_bienes" id="idcatalogo_bienes">
                            <input name="catalogo_bienes" type="text" id="catalogo_bienes" size="100">                </td>
                        <td>
                            <img src="imagenes/search0.png" onClick="window.open('modulos/bienes/lib/listar_detalles.php','','resizable = no, scrollbars=yes, width=900, height=600')" style="cursor:pointer">                </td>
                    </tr>
                </table>    </td>
            </tr>
            </div> */ ?>
            <tr>
				<td colspan="6" align="center">&nbsp;</td>
      		</tr>
            <tr>
            	<td colspan="6" align="center">
          
                <table align="center" cellpadding=2 cellspacing=0 id="tabla_botones">
                  <tr>
                    <td align="center"><input align=center class='button' name='ingresar_Materia' id="ingresar_Materia" type='button' value='Ingresar Datos B&aacute;sicos' onClick="ingresarMateria()"></td>
                    <td align="center"><input align=center class='button' name='modificar_Materia' id="modificar_Materia" type='button' value='Modificar' onClick="modificarMateria()" style="display:none"></td>
                    <td align="center"><input align=center class='button' name='eliminar_Materia' id="eliminar_Materia" type='button' value='Bloquear' onClick="eliminarMateria()" style="display:none"></td>
                    <td align="center"><input align=center class='button' name='desbloquear_Materia' id="desbloquear_Materia" type='button' value='Desbloquear' onClick="desbloquearMateria()" style="display:none"></td>
                    <td align="center"><input type="reset" value="Reiniciar" class="button" style="display:none"></td>
                  </tr>
                </table>
                
                </td>
			</tr>
		</table>
		
        </form>
	</div>
</div>
    
	  <!-- ********************************************* DATOS BASICOS *********************************************-->  
  
       
     
       
       
       <!-- ************************************************* UBICACION ***************************************-->
       
       
     
       <div id="div_ubicacion" style="display:none">
       		
            
               
        <h4 align=center>Ubicaci&oacute;n</h4>
        <h2 class="sqlmVersion"></h2>
        <br>
        <input name="idinventario_materia" type="hidden" id="idinventario_materia"/>
    <table width="52%" border="0" align="center">
      <tr>
        <td width="34%" align="right" class="viewPropTitle">Almacen</td>
        <td width="66%" colspan="3" id="celda_almacen">
            <?
          $sql_almacen = mysql_query("select * from almacen");
          ?>
          <select name="almacen" id="almacen" onchange="seleccionarUbicacion(this.value)">
              <option value="0">.:: Seleccione ::.</option>
              <?
              while($bus_almacen = mysql_fetch_array($sql_almacen)){
                ?>
                <option value="<?=$bus_almacen["idalmacen"]?>" <?php if ($bus_almacen["defecto"] == 1) echo "selected"?>><?=$bus_almacen["codigo"]." - ".$bus_almacen["denominacion"]?></option>
                <?
              }
              ?>
          </select>
        </td>
      </tr>
      <tr>
        <td align="right" class="viewPropTitle">Ubicaci&oacute;n Interna</td>
        <td colspan="3" id="celda_ubicacion">
        <?
			$sql_almacen = mysql_query("select * from almacen where defecto = '1'");
			$bus_almacen = mysql_fetch_array($sql_almacen);
          	$sql_distribucion_almacen = mysql_query("select * from distribucion_almacen where idalmacen = '".$bus_almacen["idalmacen"]."'");
          ?>
           <select name="iddistribucion_almacen" id="iddistribucion_almacen" style="width:90%" onchange="updateExistencia()">
            <?
              while($bus_distribucion_almacen = mysql_fetch_array($sql_distribucion_almacen)){
                ?>
				<option value="<?=$bus_distribucion_almacen["iddistribucion_almacen"]?>" ><?=$bus_distribucion_almacen["codigo"]." - ".$bus_distribucion_almacen["denominacion"]?></option>
                <?
              }
              ?>
     		  </select>
       </td>
      </tr>
      <tr>
        <td align="right" class="viewPropTitle">Condici&oacute;n de Almacenaje</td>
        <td colspan="3" id="celda_condicion_almacenaje">
            <?
          $sql_condicion_almacenaje = mysql_query("select * from condicion_almacenaje_materia");
          ?>
          <select name="condicion_almacenaje" id="condicion_almacenaje">
              <option value="0">.:: Seleccione ::.</option>
              <?
              while($bus_condicion_almacenaje = mysql_fetch_array($sql_condicion_almacenaje)){
                ?>
                <option value="<?=$bus_condicion_almacenaje["idcondicion_almacenaje_materia"]?>" <?php if ($bus_condicion_almacenaje["seleccionada"] == 1) echo "selected"?>><?=$bus_condicion_almacenaje["denominacion"]?></option>
                <?
              }
              ?>
          </select>
          <img src='imagenes/add.png' onClick="window.open('principal.php?modulo=19&accion=1053&pop=si','agregar almacenaje','resisable = no, scrollbars = yes, width=900, height = 500')" style="cursor:pointer">
        </td>
      </tr>
      <tr>
        <td align="right" class="viewPropTitle">Condici&oacute;n de Conservaci&oacute;n</td>
        <td colspan="3" id="celda_condicion_conservacion">
            <?
          $sql_condicion_conservacion = mysql_query("select * from condicion_conservacion_materia");
          ?>
          <select name="condicion_conservacion_materia" id="condicion_conservacion_materia">
              <option value="0">.:: Seleccione ::.</option>
              <?
              while($bus_condicion_conservacion = mysql_fetch_array($sql_condicion_conservacion)){
                ?>
                <option value="<?=$bus_condicion_conservacion["idcondicion_conservacion_materia"]?>" <?php if ($bus_condicion_conservacion["seleccionada"] == 1) echo "selected"?>><?=$bus_condicion_conservacion["denominacion"]?></option>
                <?
              }
              ?>
          </select>
          <img src='imagenes/add.png' onClick="window.open('principal.php?modulo=19&accion=1057&pop=si','agregar almacenaje','resisable = no, scrollbars = yes, width=900, height = 500')" style="cursor:pointer">
        </td>
      </tr>
       <tr>
        <td align="right" class="viewPropTitle">Forma</td>
        <td colspan="3" id="celda_forma">
            <?
          $sql_forma = mysql_query("select * from forma_materia");
          ?>
          <select name="forma_materia" id="forma_materia">
              <option value="0">.:: Seleccione ::.</option>
              <?
              while($bus_forma = mysql_fetch_array($sql_forma)){
                ?>
                <option value="<?=$bus_forma["idforma_materia"]?>" <?php if ($bus_forma["seleccionada"] == 1) echo "selected"?>><?=$bus_forma["denominacion"]?></option>
                <?
              }
              ?>
          </select>
          <img src='imagenes/add.png' onClick="window.open('principal.php?modulo=19&accion=1066&pop=si','agregar almacenaje','resisable = no, scrollbars = yes, width=900, height = 500')" style="cursor:pointer">
        </td>
      </tr>
      <tr>
        <td align="right" class="viewPropTitle">Volumen</td>
        <td colspan="3" id="celda_volumen">
            <?
          $sql_volumen = mysql_query("select * from volumen_materia");
          ?>
          <select name="volumen_materia" id="volumen_materia">
              <option value="0">.:: Seleccione ::.</option>
              <?
              while($bus_volumen = mysql_fetch_array($sql_volumen)){
                ?>
                <option value="<?=$bus_volumen["idvolumen_materia"]?>" <?php if ($bus_volumen["seleccionada"] == 1) echo "selected"?>><?=$bus_volumen["denominacion"]?></option>
                <?
              }
              ?>
          </select>
          <img src='imagenes/add.png' onClick="window.open('principal.php?modulo=19&accion=1062&pop=si','agregar almacenaje','resisable = no, scrollbars = yes, width=900, height = 500')" style="cursor:pointer">
        </td>
      </tr>
      <tr>
        <td align="right" class="viewPropTitle"><p>Color</p></td>
        <td colspan="3">
        	<input name="color_materia" type="text" id="color_materia" size="20">
        </td>
      </tr>
      <tr>
      	<td align="right" class="viewPropTitle"><p>Peso</p></td>
        <td colspan="3"><input name="peso_materia" type="text" id="peso_materia" size="10">
      	
            <?
          $sql_unidad_peso = mysql_query("select * from unidad_medida where tipo_unidad = 'Peso'");
          ?>
          <select name="unidad_peso" id="unidad_peso">
              <option value="0">.:: Seleccione ::.</option>
              <?
              while($bus_unidad_peso = mysql_fetch_array($sql_unidad_peso)){
                ?>
                <option value="<?=$bus_unidad_peso["idunidad_medida"]?>" ><?=$bus_unidad_peso["abreviado"]." - ".$bus_unidad_peso["descripcion"]?></option>
                <?
              }
              ?>
          </select>
        </td>
      </tr>
      
      <tr>
      	<td align="right" class="viewPropTitle"><p>Capacidad</p></td>
        <td colspan="3"><input name="capacidad_materia" type="text" id="capacidad_materia" size="10">
      	
            <?
          $sql_unidad_capacidad = mysql_query("select * from unidad_medida where tipo_unidad = 'Capacidad'");
          ?>
          <select name="unidad_capacidad" id="unidad_capacidad">
              <option value="0">.:: Seleccione ::.</option>
              <?
              while($bus_unidad_capacidad = mysql_fetch_array($sql_unidad_capacidad)){
                ?>
                <option value="<?=$bus_unidad_capacidad["idunidad_medida"]?>" ><?=$bus_unidad_capacidad["abreviado"]." - ".$bus_unidad_capacidad["descripcion"]?></option>
                <?
              }
              ?>
          </select>
        </td>
      </tr>
      
      <tr>
      	<td align="right" class="viewPropTitle"><p>Alto</p></td>
        <td colspan="3"><input name="alto_materia" type="text" id="alto_materia" size="10">
      	
            <?
          $sql_unidad_alto = mysql_query("select * from unidad_medida where tipo_unidad = 'Longitud'");
          ?>
          <select name="unidad_alto" id="unidad_alto">
              <option value="0">.:: Seleccione ::.</option>
              <?
              while($bus_unidad_alto = mysql_fetch_array($sql_unidad_alto)){
                ?>
                <option value="<?=$bus_unidad_alto["idunidad_medida"]?>" ><?=$bus_unidad_alto["abreviado"]." - ".$bus_unidad_alto["descripcion"]?></option>
                <?
              }
              ?>
          </select>
        </td>
      </tr>
      
       <tr>
      	<td align="right" class="viewPropTitle"><p>Largo</p></td>
        <td colspan="3"><input name="largo_materia" type="text" id="largo_materia" size="10">
      	
            <?
          $sql_unidad_largo = mysql_query("select * from unidad_medida where tipo_unidad = 'Longitud'");
          ?>
          <select name="unidad_largo" id="unidad_largo">
              <option value="0">.:: Seleccione ::.</option>
              <?
              while($bus_unidad_largo = mysql_fetch_array($sql_unidad_largo)){
                ?>
                <option value="<?=$bus_unidad_largo["idunidad_medida"]?>" ><?=$bus_unidad_largo["abreviado"]." - ".$bus_unidad_largo["descripcion"]?></option>
                <?
              }
              ?>
          </select>
        </td>
      </tr>
      
      <tr>
      	<td align="right" class="viewPropTitle"><p>Ancho</p></td>
        <td colspan="3"><input name="ancho_materia" type="text" id="ancho_materia" size="10">
      	
            <?
          $sql_unidad_ancho = mysql_query("select * from unidad_medida where tipo_unidad = 'Longitud'");
          ?>
          <select name="unidad_ancho" id="unidad_ancho">
              <option value="0">.:: Seleccione ::.</option>
              <?
              while($bus_unidad_ancho = mysql_fetch_array($sql_unidad_ancho)){
                ?>
                <option value="<?=$bus_unidad_ancho["idunidad_medida"]?>" ><?=$bus_unidad_ancho["abreviado"]." - ".$bus_unidad_ancho["descripcion"]?></option>
                <?
              }
              ?>
          </select>
        </td>
      </tr>
      
      <tr>
        <td>&nbsp;</td>
        <td colspan="3">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="6" align="center">
        <table border="0">
          <tr>
            <td><label>
              <input type="submit" name="boton_ingresar_ubicacion" id="boton_ingresar_ubicacion" value="Actualizar Ubicaci&oacute;n" class="button" onClick="ingresarUbicacion()">
            </label></td>
            
           
          </tr>
        </table></td>
      </tr>
    </table>
    <br />
    <br />
 </div>   
       
       <!-- ************************************************* UBICACION ***************************************-->
       
       
       
       
       
<script>
 function abrirCerrarSeriales(){
	if(document.getElementById('id_seriales').style.display == 'none'){
		document.getElementById('id_seriales').style.display = 'block'
		document.getElementById('td_signo_mas_seriales').innerHTML = "-";
	}else{
		document.getElementById('id_seriales').style.display = 'none';
		document.getElementById('td_signo_mas_seriales').innerHTML = "+";
	}	 
}

function abrirCerrarFechaVencimiento(){
	if(document.getElementById('id_fecha_vencimiento').style.display == 'none'){
		document.getElementById('id_fecha_vencimiento').style.display = 'block'
		document.getElementById('td_signo_mas_fechas').innerHTML = "-";
	}else{
		document.getElementById('id_fecha_vencimiento').style.display = 'none';
		document.getElementById('td_signo_mas_fechas').innerHTML = "+";
	}		
}

</script>
       
       
       
       
       
       
       
       <!-- ************************************************* EXISTENCIA ***************************************-->
       
       <div id="div_existencia" style="display:none">
      
            <h4 align=center>Existencia</h4>
            <h2 class="sqlmVersion"></h2>
            <br>
            <br>
       <table width="30%" align="center">
   		<tr>
			<td align='right' class='viewPropTitle' width="50%">Inventario Inicial</td>
			<td class='' width="48%"><input name="inventario_inicial" type="text" align="right" onFocus="javascript:select()" id="inventario_inicial" size="20" onBlur="formatoNumero(this.name)" style="text-align:right">&nbsp; 
           <img src='imagenes/validar.png'  style="cursor:pointer" onClick="cerrarInventarioInicial();" style="display:block" title="Cerrar Inventario Inicial" id="imagen_cerrar_inventario">
            </td>
            <input name="idinventario_inicial" type="hidden" id="idinventario_inicial" >
            
		</tr>
        
        <tr>
			<td align='right' class='viewPropTitle'>Total Entradas</td>
			<td class=''><input name="total_entradas" type="text" align="right" id="total_entradas" size="20" value="" disabled style="text-align:right"></td>
		</tr>
        <tr>
			<td align='right' class='viewPropTitle'>Total Despachadas</td>
			<td class=''><input name="total_despachadas" type="text" align="right" id="total_despachadas" size="20" value="" disabled style="text-align:right"></td>
		</tr>
        <tr>
			<td align='right' class='viewPropTitle'>Existencia Actual</td>
			<td class=''><input name="existencia_actual" type="text" align="right" id="existencia_actual" size="20" value="" disabled style="text-align:right"></td>
		</tr>
         <tr>
        	<td colspan="2">&nbsp;</td>
        </tr>
        
       </table> 
        <table width="45%" align="center">
         <tr>
        	<td colspan="4"><h2 class="sqlmVersion"></h2></td>
        </tr>
          <tr>
        	<td colspan="4">&nbsp;</td>
        </tr>
        <tr>
       		<td width="20%" align='right' class='viewPropTitle'>Stock Minimo:</td>
        	<td width="26%" class=''><input name="stock_minimo" type="text" align="right" onFocus="javascript:select();" id="stock_minimo" size="20" onBlur="formatoNumero(this.name)" style="text-align:right"></td>
            <input name="idstock_minimo" type="hidden" id="idstock_minimo" >
            <td width="30%" align='right' class='viewPropTitle'>Stock M&aacute;ximo:</td>
        	<td width="24%" class=''><input name="stock_maximo" type="text" align="right" onFocus="javascript:select();" id="stock_maximo" size="20" onBlur="formatoNumero(this.name)" style="text-align:right"> </td>
            <input name="idstock_maximo" type="hidden" id="idstock_maximo" >
	    </tr>
		<tr>
       		<td width="20%" align='right' class='viewPropTitle'>Ultima Entrada:</td>
        	<td class='' id="celda_ultima_entrada"></td>
            <td width="30%" align='right' class='viewPropTitle'>Fecha:</td>
        	<td class='' id="celda_fecha_ultima_entrada"></td>
	    </tr>	
        <tr>
       		<td width="20%" align='right' class='viewPropTitle'>Ultimo Costo:</td>
        	<td class='' id="celda_ultimo_costo"></td>
            <td width="30%" align='right' class='viewPropTitle'>Costo Promedio:</td>
        	<td class='' id="celda_conto_promedio"></td>
	    </tr>  
       <tr>
       <td colspan="4" align="center"><input type="button" name="boton_modificar_Existencia" id="boton_modificar_Existencia" value="Actualizar Valores" class="button" onClick="modificarExistencia()"></td>
       </tr>
       
          
       </table>
       <br>
<div id="barra_seriales" style="display:none">       
    <table align="center" width="90%" style="color:#FFF; background-color:#09F;font-weight:bold">
    <tr>
    	<td style="color:#FFF">Seriales</td>
    	<td align="right" onClick="abrirCerrarSeriales()" style="cursor:pointer; color:#FFF" id="td_signo_mas_seriales">+</td>
    </tr>
	</table>
</div>

<div id="id_seriales" style="display:none">    
    <form name="formulario_seriales" id="formulario_seriales">
        <table width="78%" border="0" align="center" cellpadding="0" cellspacing="0" id="formularioMateriales" style="display:block">
        <input name="idrelacion_serial_materia" type="hidden" id="idrelacion_serial_materia">
        
        <table align="center" width="50%">
            <tr>
                <td align="right" class='viewPropTitle'>Serial</td>
                <td id="serial" ><input type="text" id="serialMateria" name="serialMateria" size="16" >&nbsp;<img src="imagenes/validar.png" style="cursor:pointer;display:inline" onClick="ingresarSerial();" title="Ingresar Serial" id="imagen_carga_serial">    
                </td>
                <td align="center" id="van_serial">&nbsp;</td>
                <td class="Entrada" width="15%" align="center" >Disponible</td>
                <td class="Entrada" width="15%" style="background:#FC3" align="center">Entregado</td>
            </tr>
        </table>
    </form>
 
    <br>
    
    <div id="lista_seriales">
    	
    </div>
</div>
    
    <br>
       
 <div id="barra_fecha_vencimiento" style="display:none">       
    <table align="center" width="90%" style="color:#FFF; background-color:#09F;font-weight:bold">
    <tr>
    	<td style="color:#FFF">Fechas de Vencimiento</td>
    	<td align="right" onClick="abrirCerrarFechaVencimiento()" style="cursor:pointer; color:#FFF" id="td_signo_mas_fechas">+</td>
    </tr>
	</table>
</div>

<div id="id_fecha_vencimiento" style="display:none">    
    <form name="formulario_fecha_vencimiento" method="post" action="" id="formulario_fecha_vencimiento">
        <input name="idrelacion_vencimiento_materia" type="hidden" id="idrelacion_vencimiento_materia">
        <table align="center" width="50%">
            <tr>
             	<td align="right" class='viewPropTitle'>Lote</td>
                <td id="celda_lote">
                  	<input type="text" id="lote" name="lote" size="12" >
                </td>
                <td align="right" class='viewPropTitle'>Fecha de Vencimiento</td>
                <td id="fecha_vencimiento">
                  	<input name="fecha_vencimiento_materia" type="text" id="fecha_vencimiento_materia" size="13" maxlength="10">
					<img src="imagenes/jscalendar0.gif" name="f_trigger_cfv" width="16" height="16" id="f_trigger_cfv" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
				  <script type="text/javascript">
							Calendar.setup({
							inputField    : "fecha_vencimiento_materia",
							button        : "f_trigger_cfv",
							align         : "Tr",
							ifFormat    	: "%Y-%m-%d"
							});
						</script>		
                 </td>
               
                <td align="right" class='viewPropTitle'>Cantidad</td>
                <td id="cantidad_fecha">
                  	<input type="text" id="cantidad_fecha_vencimiento" name="cantidad_fecha_vencimiento" size="12" >
               
              		&nbsp;<img src='imagenes/validar.png' onClick="ingresarFVencimiento();" style="cursor:pointer" title="Ingresar Lote" id="imagen_carga_fecha_vencimiento">
                </td>
                <td align="center" id="van_fecha">&nbsp;</td>
            </tr>
        </table>
        </form>
 
    <br>
    
    <div id="lista_fecha_vencimiento">
    	<table class="Browse" cellpadding="0" cellspacing="0" width="50%" align="center">
          <thead>
            <tr>
              <td width="20%" align="center" class="Browse">Lote</td>	
              <td width="20%" align="center" class="Browse">Fecha Vencimiento</td>
              <td width="20%" align="center" class="Browse">Cantidad del Lote</td>
              <td width="20%" align="center" class="Browse">Disponibles</td>
              <td width="20%" align="center" class="Browse">Acci&oacute;n</td>
            </tr>
         </thead>
       </table>
    </div>
    </div>
    
    <br>      
       
       
 </div>
       <!-- ************************************************* EXISTENCIA **************************************-->
       
       
       
       
        
        
        
         <!-- ****************************************** REGISTRO FOTOGRAFICO ************************************************ -->
  
    <div id="div_registroFotografico" style="display:none">
        	<br>
            <h4 align=center>Registro Fotogr&aacute;fico</h4>
            <h2 class="sqlmVersion"></h2>
            <br>
            <br>
		<div id="mostrarImagen" align="center"></div>
        <table align="center" width="60%">
            <tr>
            	<td align='right' class='viewPropTitle'>Imagen:</td>
            	<td>
                <form method="post" id="formImagen" name="formImagen" enctype="multipart/form-data" action="modulos/almacen/lib/inventario_materia_ajax.php" target="iframeUpload">
                  <input type="file" name="foto_registroFotografico" id="foto_registroFotografico" size="60" align="left" onChange="document.getElementById('formImagen').submit()">
                  <!--<input type="submit" name="boton_subir" id="boton_subir" value="Subir">-->
                  <input type="hidden" id="ejecutar" name="ejecutar" value="cargarImagen">
              </form>
              <iframe id="iframeUpload" name="iframeUpload" style="display:none"></iframe>
              <input type="hidden" id="nombre_imagen" name="nombre_imagen">
                </td>
            </tr>
            <tr>
            <td align='right' class='viewPropTitle'>Descripci&oacute;n: </td>
            <td><textarea name="descripcion_foto" id="descripcion_foto" rows="2" cols="60"></textarea></td>
            </tr>
            <tr>
            	<td colspan="2" align="center"><input type="button" id="boton_registroFotografico" name="boton_registroFotografico" value="Subir Imagen" class="button" onClick="subirRegistroFotografico()"></td>
            </tr>
        </table>
    <br>
	<br>

	<div id="lista_registroFotografico"></div>    
    <h2 class="sqlmVersion"></h2>
    
    </div>
    
    <!-- ****************************************** REGISTRO FOTOGRAFICO ************************************************ -->
    
    
    
    <!-- ****************************************** DESAGREGA UNIDAD DE MEDIDA ****************************************************** -->
    <div id="div_desagregaUnidad" style="display:none">
    
    
	<br>
	<h4 align=center>Desagregar Unidad de Medida</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
	<form method='POST' enctype="multipart/form-data" name='formulario_desagregaUnidad'>
	<input type="hidden" name="idrelacion_desagrega_unidad_materia" maxlength="25" size="25" id="idrelacion_desagrega_unidad_materia">
    <input type="hidden" name="idunidad_principal" maxlength="25" size="25" id="idunidad_principal">
	<table align=center cellpadding=2 cellspacing=0 width="40%">
    
        <tr>
            <td align='right' class='viewPropTitle'>Unidad Principal del Producto:</td>
            <td><input type="text" id="descripcion_unidad_principal" name="descripcion_unidad_principal" size="40" disabled value""></td>
        </tr>
        <tr><td colspan="4">&nbsp;</td></tr>
    </table>
    <h2 class="sqlmVersion"></h2>
    <div align="center" id="lista_Desagregada">
    <table class="Browse" cellpadding="0" cellspacing="0" width="30%" align="center">
          <thead>
            <tr>
              <td width="60%" align="center" class="Browse">Unidad Desagregada</td>	
              <td width="10%" align="center" class="Browse">Cantidad</td>
              <td width="10%" align="center" class="Browse">Acci&oacute;n</td>
            </tr>
         </thead>
       </table>
    </div>
    </form>
    </div>
    <!-- ****************************************** DESAGREGA UNIDAD ****************************************************** -->
    
    
    
    
    <!-- ******************************************** REEMPLAZOS DE LA MATERIA **********************************************-->
    <div id="div_reemplazoMateria" style="display:none">
    
	<br>
	<h4 align=center>Reemplazos</h4>
	<h2 class="sqlmVersion"></h2>
	<p>&nbsp;</p>
    
	<form action='' method='POST' enctype="multipart/form-data" name='reemplazo_materia' id="reemplazo_materia">
    <input type="hidden" name="idrelacion_reemplazo_materia" id="idrelacion_reemplazo_materia" value="">
	<input type="hidden" name="idmateria_reemplazo" id="idmateria_reemplazo" value="">
	<table align=center cellpadding=2 cellspacing=0 width="80%">
			<tr>
			<td width="20%" align='right' class='viewPropTitle'>Materia de reemplazo:</td>
			  <td width="60%" class='viewProp'> 
              	<textarea name="descripcion_reemplazo" cols="100" rows="3" id="descripcion_reemplazo" disabled >Seleccione de la lista un Producto que pudiera reemplazar al este producto</textarea>
              </td>
			  <td width="35%">	<button name="listado" type="button" style="background-color:white;border-style:none;cursor:pointer;" onClick="abreVentanaReemplazo()"><img src='imagenes/search0.png'></button> </td>	
	  		</tr>	
            <tr><td colspan="3">&nbsp;</td></tr>
			<tr><td colspan="3" align="center">
              	<input type="button" value="Ingresar Reemplazo" class="button" name="boton_ingresar_reemplazoMateria" id="boton_ingresar_reemplazoMateria" onClick="ingresarReemplazoMateria()"/>
            </td></tr>
            
    </table>
    <br><br>
	<h2 class="sqlmVersion"></h2>	
	<div align="center" id="lista_Reemplazos">
    	<table class="Browse" cellpadding="0" cellspacing="0" width="50%" align="center">
          <thead>
            <tr>
              <td width="10%" align="center" class="Browse">C&oacute;digo</td>	
              <td width="70%" align="center" class="Browse">Denominaci&oacute;n</td>
              <td width="10%" align="center" class="Browse">Acci&oacute;n</td>
            </tr>
         </thead>
       </table>
    </div>
	<br>
    </form>
    </div>
    
    <!-- ********************************************* REEMPLAZOS DE LA MATERIA *********************************************-->
    
    
    <!-- ********************************************* EQUIVALENCIA DE LA MATERIA *********************************************-->
    
<div id="div_equivalenciaMateria" style="display:none">
    
<br>
<h4 align="center">Equivalentes</h4><br>
<h2 class="sqlmVersion"></h2>	
<p>&nbsp;</p>
	
    <form method="post" name="equivalente_materia" id="equivalente_materia" action="">

	<input name="idrelacion_equivalencia_materia" type="hidden" readonly id="idrelacion_equivalencia_materia" value=""/>
    <input type="hidden" name="idmateria_equivalente" id="idmateria_equivalente" value="">
	<table width="80%" border="0" align="center">
  		<tr>
    		<td width="25" align='right' class='viewPropTitle'>Materia Equivalente:</td>
     		<td width="60%" class='viewProp'> 
              	<textarea name="descripcion_equivalencia" cols="100" rows="3" id="descripcion_equivalencia" disabled >Seleccione de la lista un Producto que pueda ser equivalente y describa la equivalencia, por ejemplo: Ampicilina 100mg tiene como equivalente a 2 Ampicilina 50mg</textarea>
            </td>
			<td width="15%">	<button name="listado" type="button" style="background-color:white;border-style:none;cursor:pointer;" onClick="abreVentanaEquivalencia()"><img src='imagenes/search0.png'></button>  </td>	
  		</tr>
  		<tr>
    		<td align='right' class='viewPropTitle'>Describir Equivalencia:</td>
		    <td colspan="2">
		    	<input type="text" name="describir_equivalencia" id="describir_equivalencia" size="100" value=""/>
		    </td>
	  	</tr>
        <tr><td colspan="3">&nbsp;</td></tr>
  		<tr>
		    <td colspan="3" align="center">
		    	<input type="button" value="Ingresar Equivalencia" class="button" name="boton_ingresar_equivalenciaMateria" id="boton_ingresar_equivalenciaMateria" onClick="ingresarEquivalenciaMateria()"/>
            </td>
    	</tr>
	</table>
    
    <br><br>
	<h2 class="sqlmVersion"></h2>	
	<div align="center" id="lista_Equivalencias">
    	<table class="Browse" cellpadding="0" cellspacing="0" width="70%" align="center">
          <thead>
            <tr>
              <td width="10%" align="center" class="Browse">C&oacute;digo</td>	
              <td width="60%" align="center" class="Browse">Denominaci&oacute;n</td>
              <td width="20%" align="center" class="Browse">Equivalencia</td>
              <td width="10%" align="center" class="Browse">Acci&oacute;n</td>
            </tr>
         </thead>
       </table>
	<br>
    </div>
    </form>
</div>
    
    <!-- ********************************************* EQUIVALENCIA DE LA MATERIA *********************************************-->
    
    
    
    <!-- ********************************************* ACCESORIOS MATERIAS *********************************************-->
<div id="div_accesoriosMateria" style="display:none">

<br>
	<h4 align=center>Accesorios o Complementos</h4>
    <h2 class="sqlmVersion"></h2>
	<p>&nbsp;</p>
    
    <form method="post" name="accesorios_materia" id="accesorios_materia" action="">
	<input type="hidden" name="idrelacion_accesorios_materia" id="idrelacion_accesorios_materia">
	<input type="hidden" name="idmateria_accesorios" id="idmateria_accesorios" value="">
	<table width="80%" border="0" align="center" cellpadding="4" cellspacing="0">
		<tr>
	        <td width="25%" align='right' class='viewPropTitle'>Materia Accesoria:</td>
        	<td width="60%" class='viewProp'> 
              	<textarea name="descripcion_accesorio" cols="100" rows="3" id="descripcion_accesorio" disabled ></textarea>
            </td>
			<td width="15%">	<button name="listado" type="button" style="background-color:white;border-style:none;cursor:pointer;" onClick="abreVentanaAccesorios()"><img src='imagenes/search0.png'></button> </td>	
        </tr>
  		<tr>
    		<td align='right' class='viewPropTitle'>Describir Accesorio:</td>
    		<td colspan="2">
		    	<input type="text" name="describir_accesorio" id="describir_accesorio" size="100" value=""/>
   			</td>
 		</tr>
		<tr><td>&nbsp;</td></tr>
        <tr>
    		<td colspan="3" align="center">
            <input type="button" value="Ingresar Equivalencia" class="button" name="boton_ingresar_accesorio" id="boton_ingresar_accesorio" onClick="ingresarAccesorioMateria()"/>
        	</td>
        </tr>
    </table>


<br />
<br />
<h2 class="sqlmVersion"></h2>	

	<div id="listaAccesorios">
    <table class="Browse" cellpadding="0" cellspacing="0" width="70%" align="center">
          <thead>
            <tr>
              <td width="10%" align="center" class="Browse">C&oacute;digo</td>	
              <td width="60%" align="center" class="Browse">Denominaci&oacute;n</td>
              <td width="20%" align="center" class="Browse">Utilidad</td>
              <td width="10%" align="center" class="Browse">Acci&oacute;n</td>
            </tr>
         </thead>
       </table>
       </div>
       </form>
   	</div>
    <!-- ********************************************* ACCESORIOS MATERIA *********************************************-->
    
    
    
    <!-- ********************************************* RELACION DE COMPRAS DE LA MATERIA *********************************************-->
<div id="div_compra_materia" style="display:none">
<br> 
     <h4 align=center>Relaci&oacute;n de Compras de la Materia</h4>


	<h2 class="sqlmVersion"></h2>
	<br>
    <div id="listaCompras">
    <table class="Browse" cellpadding="0" cellspacing="0" width="60%" align="center">
   	  <thead>
        <tr>
          <td width="20%" align="center" class="Browse">Documento</td>	
          <td width="15%" align="center" class="Browse">Fecha</td>	
          <td width="65%" align="center" class="Browse">Proveedor</td>
        </tr>
     </thead>
   </table>
    
    </div>
    </div>
    <!-- ********************************************* RELACION DE COMPRAS DE LA MATERIA *********************************************-->
    
  
   <!-- ***************************** RELACION DENOMINACION ARTIUCULOS CON QUE SE COMPRO LA MATERIA *********************************************-->
   
<div id="div_materia_articulos_servicios" style="display:none">
<br>
    
    <h4 align=center>Relaci&oacute;n de Articulos con los que se Compro la Materia</h4>


	<h2 class="sqlmVersion"></h2>
	<br>
    <div id="listaArticulos">
    <table class="Browse" cellpadding="0" cellspacing="0" width="50%" align="center">
   	  <thead>
        <tr>
          <td width="10%" align="center" class="Browse">C&oacute;digo</td>	
          <td width="70%" align="center" class="Browse">Denominaci&oacute;n</td>
        </tr>
     </thead>
   </table>
    
    
    </div>
 </div>
   <!-- ***************************** RELACION DENOMINACION ARTIUCULOS CON QUE SE COMPRO LA MATERIA *********************************************-->
    
    
    <br><br>
    
	<script>     
    document.getElementById('codigo_materia').focus();
  </script>
	 
	<br>
</body>
</html>


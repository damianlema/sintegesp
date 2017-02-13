<script src="modulos/recaudacion/js/datos_basicos_contribuyente_ajax.js"></script>
<?
      $sql_configuracion = mysql_query("select * from configuracion_recaudacion");
	  $bus_configuracion = mysql_fetch_array($sql_configuracion);
?>


<input type="hidden" id="idcontribuyente" name="idcontribuyente" value="">
    <br>
<h4 align=center>Datos Basicos Contribuyente</h4>
	<h2 class="sqlmVersion"></h2>
 <br>
 <br>

<div align="center">
<img src="imagenes/search0.png" style="cursor:pointer" onClick="window.open('lib/listas/listar_contribuyentes.php', 'contribuyentes', 'width=900, height=600, scrollbars=yes, resizable=no')">&nbsp;&nbsp;
<img src="imagenes/nuevo.png" onClick="window.location.href='principal.php?accion=<?=$_GET["accion"]?>&modulo=<?=$_GET["modulo"]?>'" style="cursor:pointer">&nbsp;&nbsp;
<img id="btImprimir" src="imagenes/imprimir.png" style="cursor:pointer; visibility:hidden;" onClick="document.getElementById('divPDF').style.display='block'; iPDF.location.href='lib/reportes/recaudacion/reportes.php?nombre=contribuyente&idcontribuyente='+document.getElementById('idcontribuyente').value;">
</div>

<table width="50%" align="center">
	<tr>
    	<td>
            <div id="divPDF" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
            <div align="right"><a href="#" onClick="document.getElementById('divPDF').style.display='none'; document.getElementById('iPDF').src='';">X</a></div>
            <iframe name="iPDF" id="iPDF" height="500" width="500"></iframe>
            </div>
			</script>
        </td>
    </tr>
</table>

<br>
<table align="center">
<tr>
<td align="right" class='viewPropTitle'>Tipo</td>
<td>
<select id="tipo_contribuyente" name="tipo_contribuyente">
	<option value="0">.:: Seleccione ::.</option>
    <option value="pj" onClick="document.getElementById('datos_basicos_vehiculo').style.display = 'none', document.getElementById('datos_basicos_natural_juridica').style.display = 'block'">Persona Juridica</option>
    <option value="pn" onClick="document.getElementById('datos_basicos_natural_juridica').style.display = 'block', document.getElementById('datos_basicos_vehiculo').style.display = 'none'">Persona Natural</option>
    <option value="ve" onClick="document.getElementById('datos_basicos_vehiculo').style.display = 'block', document.getElementById('datos_basicos_natural_juridica').style.display = 'none'">Vehiculo</option>
</select>
</td>
<td></td>
<td></td>
</tr>
<td align="right" class='viewPropTitle'>Nombre / Razon Social</td>
<td><input type="text" name="nombre_buscar" id="nombre_buscar" size="45"></td>
<td align="right" class='viewPropTitle'>RIF</td>
<td><input type="text" name="rif_buscar" id="rif_buscar" size="16"></td>
</tr>
</table>

 
 
 
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
        padding:10px 10px 0 50px;
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
<body onLoad="consultarImagenes(), verificarId(), cargarListaSocios(), cargarListaActividades(), consultarRequisitos(), consultarRegistroMercantil(), cargarSelect('municipios', 'celda_municipio', '<?=$bus_configuracion["idestado"]?>', 'idestado')">
 <br>
 

 
 <div id="tabsF">
    <ul>
        <li>
        	<a href="javascript:;" onClick="mostrarPestana('div_datosBasicos'), document.getElementById('tabla_botones').style.display = 'block'">
        		<span>Datos Basicos</span>
            </a>
        </li>
        <li style="display:none" id="li_registroMercantil">
        	<a href="javascript:;" onClick="mostrarPestana('div_registroMercantil'), document.getElementById('tabla_botones').style.display = 'block'">
        		<span>Registro Mercantil</span>
            </a>
        </li>
        <li style="display:none" id="li_registroFotografico">
        	<a href="javascript:;" onClick="mostrarPestana('div_registroFotografico'), document.getElementById('tabla_botones').style.display = 'none'">
        		<span>Registro Fotografico</span>
            </a>
        </li>
        <li style="display:none" id="li_actividadComercial">
        	<a href="javascript:;" onClick="mostrarPestana('div_actividadComercial'), document.getElementById('tabla_botones').style.display = 'none'">
        		<span>Actividad Comercial</span>
            </a>
        </li>
        <li style="display:none" id="li_requisitos">
        	<a href="javascript:;" onClick="mostrarPestana('div_requisitos'), document.getElementById('tabla_botones').style.display = 'none', consultarRequisitos()">
        		<span>Requisitos</span>
            </a>
        </li>

    </ul>
</div>
 
 
 <br>
<br>
<br>
<br>

 
 
 
 <div id="div_requisitos" style="display:none">
 
 </div>
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
<div id="div_datosBasicos">
 
 <div id="datos_basicos_natural_juridica" style="display:none">
<table width="90%" border="0" align="center">
  <tr>
    <td colspan="4" style="">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" style="background-color:#CCCCCC"><strong>DATOS GENERALES</strong></td>
  </tr>
  <tr>
  <td colspan="4" style="">&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Nombre o Razon Social</td>
    <td colspan="3"><input name="razon_social" type="text" id="razon_social" size="70" onKeyUp="document.getElementById('nombre_buscar').value =  this.value"></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>RIF / CI</td>
    <td>
      <input name="rif" type="text" id="rif" size="16" onKeyUp="document.getElementById('rif_buscar').value =  this.value" onBlur="consultarRif(this.value)">
    <label id="divMensaje" style="border:#990000 1px solid; color:#990000; font-weight:bold; font-size:11px; display:none; width:150px"></label></td>
    <td>&nbsp;</td>
    <td><label></label></td>
  </tr>
  
  <tr>
    <td align="right" class='viewPropTitle'>Telefono</td>
    <td><label>
      <input type="text" name="telefono" id="telefono">
    </label></td>
    <td align="right" class='viewPropTitle'>Email</td>
    <td><label>
      <input name="email" type="text" id="email" size="30">
    </label></td>
  </tr>
  
  <tr>
    <td colspan="4" style="">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" style="background-color:#CCCCCC"><strong>DIRECCION</strong></td>
  </tr>
  <tr>
  <td colspan="4" style="">&nbsp;</td>
  </tr>
  
  <tr>
    <td align="right" class='viewPropTitle'>Estado</td>
    <td>
    <?

	  $sql_estado = mysql_query("select * from estado");
	  ?>
      <select name="estado" id="estado" style="width:250px">
        <option value="0">.:: Seleccione ::.</option>
		<?
      while($bus_estado = mysql_fetch_array($sql_estado)){
	  	?>
        <option <? if($bus_configuracion["idestado"] == $bus_estado["idestado"]){echo "selected";}?> value="<?=$bus_estado["idestado"]?>" onClick="cargarSelect('municipios', 'celda_municipio', '<?=$bus_estado["idestado"]?>', 'idestado')">
          <?=$bus_estado["denominacion"]?>
        </option>
        <?
	  }
	  ?>
      </select>    </td>
    <td align="right" class='viewPropTitle'>Municipio</td>
    <td id="celda_municipio">
     <select name="municipios" id="municipios" style="width:250px">
     <option value="0">.:: SELECCIONE EL ESTADO ::.</option>
     </select>    </td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Parroquia</td>
    <td >
        <table>
        <tr>
        <td id="celda_parroquia">
        <label>
          <select name="parroquia" id="parroquia" style="width:250px">
          <option value="0">.:: SELECCIONE EL MUNICIPIO ::.</option>
          </select>
        </label>
        </td>
        <td><img src="imagenes/add.png" onClick="window.open('principal.php?accion=973&modulo=17&externo=si','','resizabled=no, scrollbars=yes, width=600, height=600')"></td>
        </tr>
        </table>
    </td>
    <td align="right" class='viewPropTitle'>Sector</td>
    <td >
      <table>
        <tr>
        <td id="celda_sector">
      <select name="sectores" id="sectores" style="width:250px">
      <option value="0">.:: SELECCIONE LA PARROQUIA ::.</option>
      </select> 
      </td>
    <td><img src="imagenes/add.png"  onClick="window.open('principal.php?accion=974&modulo=17&externo=si','','resizabled=no, scrollbars=yes, width=600, height=600')"></td>
    </tr>
    </table>
      
      
    </td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Urb / Barrio</td>
    <td >
    
    
    <table>
        <tr>
        <td id="celda_urb">
    <select name="urbanizacion" id="urbanizacion" style="width:250px">
      <option value="0">.:: SELECCIONE EL SECTOR ::.</option>
    </select>
     </td>
        <td><img src="imagenes/add.png" onClick="window.open('principal.php?accion=975&modulo=17&externo=si','','resizabled=no, scrollbars=yes, width=600, height=600')"></td>
    </tr>
    </table>

    
    
    </td>
    <td align="right" class='viewPropTitle'>Calle</td>
    <td >
    
    <table>
        <tr>
        <td id="celda_calle">
    <select name="calle" id="calle"style="width:250px">
      <option value="0">.:: SELECCIONE LA URBANIZACION ::.</option>
    </select>
     </td>
        <td><img src="imagenes/add.png" onClick="window.open('principal.php?accion=976&modulo=17&externo=si','','resizabled=no, scrollbars=yes, width=600, height=600')"></td>
    </tr>
    </table>

    
    
    </td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Carrera</td>
    <td >
    
    
    <table>
        <tr>
        <td id="celda_carrera">
    <select name="carrera" id="carrera" style="width:250px">
      <option value="0">.:: SELECCIONE LA CALLE ::.</option>
    </select>
    </td>
    <td><img src="imagenes/add.png" onClick="window.open('principal.php?accion=977&modulo=17&externo=si','','resizabled=no, scrollbars=yes, width=600, height=600')"></td>
    </tr>
    </table>

    
    
    </td>
    <td align="right" class='viewPropTitle'>Nro Casa / Local</td>
    <td><input name="nro_casa" type="text" id="nro_casa"></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Punto Referencia</td>
    <td colspan="3"><label>
      <input name="punto_referencia" type="text" id="punto_referencia" size="70">
    </label></td>
  </tr>
</table>
</div>
<!-- DATOS BASICOS DE VEHICULO **************************************************************************************************************
*********************************************************************************************************************************************
*********************************************************************************************************************************************
*********************************************************************************************************************************************
*********************************************************************************************************************************************
-->
<div id="datos_basicos_vehiculo" style="display:none">
<table width="80%" border="0" align="center">
  
  
  <tr>
  <td colspan="4" style="">&nbsp;</td>
  </tr>

<tr>
    <td colspan="4" style="background-color:#CCCCCC"><strong>DATOS GENERALES</strong></td>
  </tr>
  <tr>
  <td colspan="4" style="">&nbsp;</td>
  </tr>
  
  
  
  <tr>
    <td align="right" class='viewPropTitle'>Nro de Placa</td>
    <td><label>
      <input type="text" name="nro_placa" id="nro_placa">
    </label></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Vehiculo</td>
    <td>
    <select id="vehiculo" name="vehiculo">
        <option value="0">.:: Seleccione ::.</option>
        <option value="automovil">Automovil</option>
        <option value="camion">Camion</option>
        <option value="camioneta">Camioneta</option>
        <option value="transporte_colectivo">Transporte Colectivo</option>
        <option value="autobus">Autobus</option>
        <option value="minibus">Minibus</option>
        <option value="gandola">Gandola</option>
        <option value="remolque">Remolque</option>
        <option value="moto">Moto</option>
        <option value="bicicleta">Bicicleta	</option>
    </select>
    </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Marca</td>
    <td><label>
      <input type="text" name="marca" id="marca">
    </label></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Modelo</td>
    <td><label>
      <input type="text" name="modelo" id="modelo">
    </label></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Tipo</td>
    <td><label>
      <input type="text" name="tipo" id="tipo">
    </label></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Color</td>
    <td><label>
      <input type="text" name="color" id="color">
    </label></td>
    <td align="right" class='viewPropTitle'>Peso</td>
    <td><label>
      <input type="text" name="peso" id="peso">
    </label></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Capacidad</td>
    <td><input type="text" name="capacidad" id="capacidad"></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Nro de Matricula</td>
    <td><label>
      <input type="text" name="nro_matricula" id="nro_matricula">
    </label></td>
    <td align="right" class='viewPropTitle'>Fecha de Matricula</td>
    <td><label>
      <input type="text" name="fecha_matricula" id="fecha_matricula">
    </label></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Serial del Motor</td>
    <td><label>
      <input type="text" name="serial_motor" id="serial_motor">
    </label></td>
    <td align="right" class='viewPropTitle'>Serial de la Carroceria</td>
    <td><label>
      <input type="text" name="serial_carroceria" id="serial_carroceria">
    </label></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Uso del Vechiculo</td>
    <td>
    <select name="uso_vehiculo" id="uso_vehiculo">
    <option value="0">.:: Seleccione ::.</option>
    <option value="particular">Particular</option>
    <option value="alquiler">Alquiler</option>
    <option value="carga">Carga</option>
    <option value="colectivo">Colectivo</option>
    <option value="reparto">Reparto</option>
    <option value="otro">Otro</option>
    </select>
    
    </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Propietario</td>
    <td><label>
      <input type="text" name="propietario" id="propietario">
    </label></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Cedula del Propietario</td>
    <td><label>
      <input type="text" name="cedula_propietario" id="cedula_propietario">
    </label></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>



<tr>
  <td colspan="4" style="">&nbsp;</td>
  </tr>

<tr>
    <td colspan="4" style="background-color:#CCCCCC"><strong>DIRECCION</strong></td>
  </tr>
  <tr>
  <td colspan="4" style="">&nbsp;</td>
  </tr>
  
  <tr>
    <td align="right" class='viewPropTitle'>Estado</td>
    <td>
    <?

	  $sql_estado = mysql_query("select * from estado");
	  ?>
      <select name="estado_vehiculo" id="estado_vehiculo" style="width:250px">
        <option value="0">.:: Seleccione ::.</option>
		<?
      while($bus_estado = mysql_fetch_array($sql_estado)){
	  	?>
        <option <? if($bus_configuracion["idestado"] == $bus_estado["idestado"]){echo "selected";}?> value="<?=$bus_estado["idestado"]?>" onClick="cargarSelect('municipios_vehiculo', 'celda_municipio_vehiculo', '<?=$bus_estado["idestado"]?>', 'idestado')">
          <?=$bus_estado["denominacion"]?>
        </option>
        <?
	  }
	  ?>
      </select>    </td>
    <td align="right" class='viewPropTitle'>Municipio</td>
    <td id="celda_municipio_vehiculo">
     <select name="municipios_vehiculo" id="municipios_vehiculo" style="width:250px">
     <option value="0">.:: SELECCIONE EL ESTADO ::.</option>
     </select>    </td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Parroquia</td>
    <td >
        <table>
        <tr>
        <td id="celda_parroquia_vehiculo">
        <label>
          <select name="parroquia_vehiculo" id="parroquia_vehiculo" style="width:250px">
          <option value="0">.:: SELECCIONE EL MUNICIPIO ::.</option>
          </select>
        </label>
        </td>
        <td><img src="imagenes/add.png" onClick="window.open('principal.php?accion=973&modulo=17&externo=si','','resizabled=no, scrollbars=yes, width=600, height=600')"></td>
        </tr>
        </table>
    </td>
    <td align="right" class='viewPropTitle'>Sector</td>
    <td >
      <table>
        <tr>
        <td id="celda_sector_vehiculo">
      <select name="sectores_vehiculo" id="sectores_vehiculo" style="width:250px">
      <option value="0">.:: SELECCIONE LA PARROQUIA ::.</option>
      </select> 
      </td>
    <td><img src="imagenes/add.png"  onClick="window.open('principal.php?accion=974&modulo=17&externo=si','','resizabled=no, scrollbars=yes, width=600, height=600')"></td>
    </tr>
    </table>
      
      
    </td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Urb / Barrio</td>
    <td >
    
    
    <table>
        <tr>
        <td id="celda_urb_vehiculo">
    <select name="urbanizacion_vehiculo" id="urbanizacion_vehiculo" style="width:250px">
      <option value="0">.:: SELECCIONE EL SECTOR ::.</option>
    </select>
     </td>
        <td><img src="imagenes/add.png" onClick="window.open('principal.php?accion=975&modulo=17&externo=si','','resizabled=no, scrollbars=yes, width=600, height=600')"></td>
    </tr>
    </table>

    
    
    </td>
    <td align="right" class='viewPropTitle'>Calle</td>
    <td >
    
    <table>
        <tr>
        <td id="celda_calle_vehiculo">
    <select name="calle_vehiculo" id="calle_vehiculo"style="width:250px">
      <option value="0">.:: SELECCIONE LA URBANIZACION ::.</option>
    </select>
     </td>
        <td><img src="imagenes/add.png" onClick="window.open('principal.php?accion=976&modulo=17&externo=si','','resizabled=no, scrollbars=yes, width=600, height=600')"></td>
    </tr>
    </table>

    
    
    </td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Carrera</td>
    <td >
    
    
    <table>
        <tr>
        <td id="celda_carrera_vehiculo">
    <select name="carrera_vehiculo" id="carrera_vehiculo" style="width:250px">
      <option value="0">.:: SELECCIONE LA CALLE ::.</option>
    </select>
    </td>
    <td><img src="imagenes/add.png" onClick="window.open('principal.php?accion=977&modulo=17&externo=si','','resizabled=no, scrollbars=yes, width=600, height=600')"></td>
    </tr>
    </table>

    
    
    </td>
    <td align="right" class='viewPropTitle'>Nro Casa / Local</td>
    <td><input name="nro_casa_vehiculo" type="text" id="nro_casa_vehiculo"></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Punto Referencia</td>
    <td colspan="3"><label>
      <input name="punto_referencia_vehiculo" type="text" id="punto_referencia_vehiculo" size="70">
    </label></td>
  </tr>





</table>

</div>

</div>


















<div id="div_registroMercantil" style="display:none">
<table align="center" width="90%">
    <tr>
      <td>&nbsp;</td>
      <td></td>
      <td>&nbsp;</td>
      <td></td>
    </tr>
    <tr>
      <td colspan="4" style="background-color:#CCCCCC"><strong>DATOS GENERALES</strong></td>
    </tr>
    <tr>
      <td><input id="idregistro_mercantil" type="hidden" name="idregistro_mercantil"></td>
      <td></td>
      <td>&nbsp;</td>
      <td></td>
    </tr>
    <tr>
    <td align="right" class='viewPropTitle'>Tipo de Persona</td>
    <td>
    <label id="celda_tipo_persona">
    <select name="tipo_persona" id="tipo_persona" style="width:250px">
      	<option value="0">.:: Seleccione ::.</option>
		<?php
        $sql_tipos_persona = mysql_query("select * from tipos_persona order by idtipos_persona ASC");
		while($bus_tipos_persona=mysql_fetch_array($sql_tipos_persona)){
			?>
			<option <?php if($bus_busqueda["idtipos_persona"] == $bus_tipos_persona["idtipos_persona"]){ echo "selected='selected'";} ?> value="<?php echo $bus_tipos_persona["idtipos_persona"]?>"><?php echo $bus_tipos_persona["descripcion"]?></option>
			<?php
		}
		?>
       </select>    
     </label>
      <!-- <a href="#" onClick="window.open('principal.php?modulo=17&accion=981&pop=true','Tipo de Persona','width=900, height = 500, scrollbars = yes')">
      <img src="imagenes/add.png">
      </a> -->       </td>
    <td align="right" class='viewPropTitle'>Tipo de Empresa</td>
    <td>
    <select name="tipo_empresa" id="tipo_empresa" style="width:250px">
      	<option value="0">.:: Seleccione ::.</option>
		<?php
        $sql_tipo_empresa = mysql_query("select * from tipo_empresa order by idtipo_empresa ASC");
		while($bus_tipo_empresa=mysql_fetch_array($sql_tipo_empresa)){
			?>
			<option <?php if($bus_busqueda["idtipo_empresa"] == $bus_tipo_empresa["idtipo_empresa"]){ echo "selected='selected'";} ?> value="<?php echo $bus_tipo_empresa["idtipo_empresa"]?>"><?php echo $bus_tipo_empresa["descripcion"]?></option>
			<?php
		}
		?>
       </select>    </td>
    </tr>
    
    <tr>
      <td align="right" class='viewPropTitle'>Tipo de Sociedad</td>
      <td><select name="tipo_sociedad" id="tipo_sociedad" style="width:250px">
        <option value="0">.:: Seleccione ::.</option>
		<?php
        $sql_tipo_sociedad = mysql_query("select * from tipo_sociedad order by  idtipo_sociedad ASC");
		while($bus_tipo_sociedad=mysql_fetch_array($sql_tipo_sociedad)){
			?>
        <option <?php if($bus_busqueda["idtipo_sociedad"] == $bus_tipo_sociedad["idtipo_sociedad"]){ echo "selected='selected'";} ?> value="<?php echo $bus_tipo_sociedad["idtipo_sociedad"]?>"><?php echo $bus_tipo_sociedad["descripcion"]?></option>
        <?php
		}
		?>
      </select></td>
      <td align="right">Fecha de Registro</td>
      <td><input name="fecha_registro" type="text" id="fecha_registro" size="12" readonly="readonly">
        <img src="imagenes/jscalendar0.gif" name="f_trigger_c" width="16" height="16" id="f_trigger_c" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
        <script type="text/javascript">
                                Calendar.setup({
                                inputField    : "fecha_registro",
                                button        : "f_trigger_c",
                                align         : "Tr",
                                ifFormat      : "%Y-%m-%d"
                                });
                            </script></td>
    </tr>
    <tr>
      <td align="right" class='viewPropTitle'>Objeto</td>
      <td colspan="3"><label>
        <textarea name="objeto" cols="70" rows="3" id="objeto"></textarea>
      </label></td>
    </tr>
    <tr>
      <td align="right" class='viewPropTitle'>Libro</td>
      <td><label>
        <input type="text" name="libro" id="libro">
      </label></td>
      <td align="right" class='viewPropTitle'>Folio</td>
      <td><label>
        <input type="text" name="folio" id="folio">
      </label></td>
    </tr>
    <tr>
      <td align="right" class='viewPropTitle'>Capital Social</td>
      <td><label>
        <input type="text" name="capital_social" id="capital_social" style="text-align:right">
      </label></td>
      <td align="right" class='viewPropTitle'>Capital Suscrito</td>
      <td><label>
        <input type="text" name="capital_suscrito" id="capital_suscrito" style="text-align:right">
      </label></td>
    </tr>
    <tr>
      <td colspan="4" align="center">
      <input name="boton_ingresar_registro_mercantil" type="button" id="boton_ingresar_registro_mercantil" value="Ingresar Regsitro" class="button" onClick="ingresarRegistroMercantil()">
      <input name="boton_modificar_registro_mercantil" type="button" id="boton_modificar_registro_mercantil" value="Modificar Regsitro" class="button" onClick="modificarRegistroMercantil()" style="display:none">
      </td>
    </tr>
    <tr>
      <td colspan="4" id="lista_registro_mercantil"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td></td>
      <td>&nbsp;</td>
      <td></td>
    </tr>
    <tr>
      <td colspan="4" style="background-color:#CCCCCC"><strong>SOCIOS DE LA EMPRESA</strong></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td></td>
      <td>&nbsp;</td>
      <td></td>
    </tr>
    <tr>
    <td colspan="4">
    
    
    <table cellpadding="0" cellspacing="0" style="margin:0px; padding:0px; width:100%">
    <tr>
      <td align="right" class='viewPropTitle'>Nombre y Apellido</td>
      <td><input name="nombre_socio" type="text" id="nombre_socio" size="40"></td>
      <td align="right" class='viewPropTitle'>CI</td>
      <td><input type="text" name="ci_socio" id="ci_socio"></td>
      <td rowspan="2" align="left"><input type="button" name="boton_cargar_socios" id="boton_cargar_socios" value="Cargar Socio" class="button" onClick="cargarSocio()"></td>
    </tr>
    <tr>
      <td align="right" class='viewPropTitle'>Cargo en la Empresa</td>
      <td><input type="text" name="cargo_socio" id="cargo_socio"></td>
      <td align="right" class='viewPropTitle'>Acciones</td>
      <td><input name="acciones_socio" type="text" id="acciones_socio" size="5"></td>
    </tr>
    </table>    </td>
    </tr>
    
    
    <tr>
    <td colspan="4" id="listaSocios" align="center"></td>
    </tr>
    
    <tr>
    <td>&nbsp;</td>
    <td></td>
    <td>&nbsp;</td>
    <td></td>
    </tr>
</table>
</div>















<div id="div_registroFotografico" style="display:none">
<form method="post" id="formImagen" enctype="multipart/form-data" action="modulos/recaudacion/lib/datos_basico_contribuyente_ajax.php" target="iframeUpload">
<input type="hidden" id="idcontribuyente_imagen" name="idcontribuyente_imagen">
<table align="center" width="90%">
    <tr>
      <td width="11%"><input type="hidden" name="ejecutar" id="ejecutar" value="cargarArchivo"></td>
      <td width="39%"></td>
      <td width="16%">&nbsp;</td>
      <td width="34%"></td>
    </tr>
    <tr>
      <td colspan="4" style="background-color:#CCCCCC"><strong>REGISTRO FOTOGRAFICO</strong></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td></td>
      <td>&nbsp;</td>
      <td></td>
    </tr>
    <tr>
      <td id="celda_imagenesExistentes" colspan="4"></td>
    </tr>
    <tr>
      <td align="right" class='viewPropTitle'>Archivo</td>
      <td><input name="imagen" type="file" id="imagen" size="40"></td>
      <td align="right" class='viewPropTitle'>Descripcion</td>
      <td><label>
        <textarea name="descripcion_imagen" cols="50" rows="3" id="descripcion_imagen"></textarea>
      </label></td>
    </tr>
    <tr>
      <td colspan="4" align="center"><input type="button" class="button" id="boton_cargar_imagen" value="Cargar Imagen" onClick="document.getElementById('formImagen').submit()"></td>
    </tr>
    <tr>
      <td colspan="4">
      <div id="formUpload" style="font-weight:bold; text-align:center"> </div>
      <br>
	<br>
    <iframe name="iframeUpload" style="display:none"></iframe></td>
    </tr>
    
    
</table>
</form>
</div>











<div id="div_actividadComercial" style="display:none">
<table align="center" width="90%">
        <tr>
      <td width="24%">&nbsp;</td>
      <td width="23%"></td>
      <td width="19%">&nbsp;</td>
      <td width="34%"></td>
    </tr>
    <tr>
      <td colspan="4" style="background-color:#CCCCCC"><strong>ACTIVIDAD COMERCIAL</strong></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td></td>
      <td>&nbsp;</td>
      <td></td>
    </tr>
    <tr>
      <td align="right" class='viewPropTitle'>Actividad Comercial</td>
      <td colspan="2"><label>
        <?
        $sql_consulta = mysql_query("select * from actividades_comerciales");
		?>
        <select name="actividad_comercial" id="actividad_comercial" style="width:450px">
        <option value="0">.:: Seleccione ::.</option>
		<?
        while($bus_consulta = mysql_fetch_array($sql_consulta)){
			?>
			<option value="<?=$bus_consulta["idactividades_comerciales"]?>"><?=$bus_consulta["denominacion"]?></option>
			<?
		}
		?>
        </select>
      </label>
      </td>
      <td><input type="button" id="boton_ingresar_actividad" class="button" value="Agregar Actividad" onClick="agregarActividad()"></td>
    </tr>
    <tr>
      <td colspan="4" id="listaActividades" align="center"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td></td>
      <td>&nbsp;</td>
      <td></td>
    </tr>
  </table>
</div>








<table align="center">
<tr>
<td align="center">

<table align="center" id="tabla_botones" style="display:block">
<tr>
<td><input type="submit" name="boton_ingresar" id="boton_ingresar" value="Ingresar" class="button" onClick="ingresarDatosBasicos()"></td>
<td><input type="submit" name="boton_modificar" id="boton_modificar" value="Modificar" class="button" style="display:none" onClick="modificarDatosBasicos()"></td>
<td><input type="submit" name="boton_eliminar" id="boton_eliminar" value="Eliminar" class="button" style="display:none" onClick="eliminarContribuyente()"></td>
</tr>
</table>

</td>
</tr>
</table>


</body>
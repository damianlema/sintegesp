<?php
include "../../../funciones/funciones.php";
?>
<script src="modulos/nomina/js/tipos_nomina_ajax.js"></script>
<script>
function abrirCerrarCuadros(idrango){
	if(document.getElementById("div_"+idrango).style.display == 'none'){
		document.getElementById("div_"+idrango).style.display = 'block';
		document.getElementById('imagenAbrir_'+idrango).src = 'imagenes/cerrar.gif';	
	}else{
		document.getElementById("div_"+idrango).style.display = 'none';	
		document.getElementById('imagenAbrir_'+idrango).src = 'imagenes/abrir.gif';
	}
}

function activarDesactivarCampos(campo, idperiodo, idconcepto){
	if(document.getElementById(campo).disabled == true){
		document.getElementById(campo).disabled = false;
		
		document.getElementById(campo).focus();
	}else{
		document.getElementById(campo).value = '';
		document.getElementById(campo).disabled = true;
		var idtipo_nomina = document.getElementById('idtipo_nomina').value;
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/nomina/lib/tipos_nomina_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){}
			if (ajax.readyState==4){} 
		}
		ajax.send("idtipo_nomina="+idtipo_nomina+"&idperiodo="+idperiodo+"&idconcepto="+idconcepto+"&ejecutar=eliminarConcepto");
		
		
	}
}

</script>
<link href="css/estilos_solicitud.css" rel="stylesheet" type="text/css">
	<style type="text/css">
<!--
.Estilo1 {
	color: #FFFFFF;
	font-weight: bold;
}
-->
    </style>
	<br>
	<h4 align=center>Tipo de Nomina</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
    <br>
<br>
    <input type="hidden" id="idtipo_nomina" name="idtipo_nomina">

<input type="hidden" value="0" id="pestana_seleccionada" name="pestana_seleccionada">

<table width="60%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="4" align='center'>
    	<img src="imagenes/search0.png" style=" cursor:pointer" onclick="window.open('lib/listas/listar_tipos_nomina.php', '', 'resizable =no, scrollbars =yes, width = 900, height=600')">&nbsp;
        <img src="imagenes/nuevo.png" style=" cursor:pointer" onclick="window.location.href='principal.php?accion=875&modulo=13'">&nbsp;
        
       <img src="imagenes/imprimir.png" style="cursor:pointer;" id="btImprimir" title="Imprimir" onclick="document.getElementById('divFiltro').style.display='block';">
    
        <div id="divImprimir" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid; width:50%; left:25%">
            <div align="right">
                <a href="#" onClick="document.getElementById('divImprimir').style.display='none'; document.getElementById('pdf').style.display='none';">X</a>
            </div>
            <iframe name="pdf" id="pdf" style="display:none; width:99%; height:550px;"></iframe>   
        </div>
        
        <div id="divFiltro" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid; width:30%; left:30%">
            <div align="right">
                <a href="#" onClick="document.getElementById('divFiltro').style.display='none';">X</a>
            </div>
            <div>
            	<table>
                	<tr><th>Seleccione el tipo de reporte</th></tr>
                    <tr>
                    	<td>
                        	<input type="radio" name="tipo" id="listado" checked="checked" /> Listado &nbsp;
                        	<input type="radio" name="tipo" id="ficha" /> Ficha
                        </td>
                    </tr>
                    <tr><td align="center"><input type="button" value="Procesar" onclick="document.getElementById('divFiltro').style.display='none'; document.getElementById('divImprimir').style.display='block'; pdf.location.href='lib/reportes/nomina/reportes.php?nombre=nomina_tipo_nomina&idtipo_nomina='+document.getElementById('idtipo_nomina').value+'&listado='+document.getElementById('listado').checked+'&ficha='+document.getElementById('ficha').checked; document.getElementById('pdf').style.display='block';" /></td></tr>
                </table>
            </div>
        </div>
        
        
        
    </td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>&nbsp;</td>
    <td colspan="3"></td>
  </tr>
  <tr>
    <td width="23%" align='right' class='viewPropTitle'>Titulo de la Nomina:</td>
    <td colspan="3">
    <label>
      <input name="titulo_nomina" type="text" id="titulo_nomina" size="80" />
    </label></td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Motivo de Cuenta</td>
    <td colspan="3">
    <select name="motivo_cuenta" id="motivo_cuenta">
        <?
        $sql_motivos_cuentas = mysql_query("select * from motivos_cuentas");
		while($bus_motivos_cuentas = mysql_fetch_array($sql_motivos_cuentas)){
		?><option value="<?=$bus_motivos_cuentas["idmotivos_cuentas"]?>"><?=$bus_motivos_cuentas["denominacion"]?></option><?	
		}
		?>
      </select>
    </td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Tipo de Documento</td>
    <td colspan="3">
   
    <select id="idtipo_documento" name="idtipo_documento">
    	<?
        $sql_tipo_documento = mysql_query("select * from tipos_documentos where modulo like '%-13-%' and compromete = 'si'");
		while($bus_tipo_documento = mysql_fetch_array($sql_tipo_documento)){
		?>
        <option value="<?=$bus_tipo_documento["idtipos_documentos"]?>"><?=$bus_tipo_documento["descripcion"]?></option>
        <?
		}
		?>
    </select>
    
    </td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Activa:</td>
    <td width="5%">
      <input type="checkbox" name="activa" id="activa" value="si"/>    </td>
    <td width="24%">&nbsp;</td>
    <td width="48%">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" align="center">
      <table>
      <tr>
      	<td>
      <input type="button" name="boton_ingresar" id="boton_ingresar" value="Ingresar" class="button" onclick="registrarDatosGenerales(), this.style.display = 'none'">
      	</td>
        <td>
    	<input type="button" name="boton_actualizar" id="boton_actualizar" style="display:none" value="Actualizar" class="button" onclick="actualizarDatosGenerales()">
        </td>
        <td>
        <input type="button" name="boton_eliminar" id="boton_eliminar" style="display:none" value="Eliminar" class="button" onclick="eliminarTipoNomina()">    </td>
        </td>
      </tr>
      </table>
  </tr>
</table>
<br>
<br>



 <div id="tabsF" style="display:none">
  <ul>
      <li>
        <a href="javascript:;" 
        	onClick="document.getElementById('periodos').style.display = 'block',
            		document.getElementById('jornadas').style.display = 'none',
                    document.getElementById('fracciones').style.display = 'none'">
        <span style="font-size:9px; font-family:Verdana, Arial, Helvetica, sans-serif;">
            Periodos
        </span>
        </a>
      </li>
      <!-- <li>
        <a href="javascript:;" 
        	onClick="document.getElementById('fracciones').style.display = 'block',
            		document.getElementById('periodos').style.display = 'none',
                    document.getElementById('jornadas').style.display = 'none'">
        <span style="font-size:9px; font-family:Verdana, Arial, Helvetica, sans-serif;">
            Fracciones
        </span>
        </a>
      </li> -->
      <li>
        <a href="javascript:;" 
        	onClick="document.getElementById('jornadas').style.display = 'block',
            		document.getElementById('periodos').style.display = 'none',
                    document.getElementById('fracciones').style.display = 'none'">
        <span style="font-size:9px; font-family:Verdana, Arial, Helvetica, sans-serif;">
            Jornadas
        </span>
        </a>
      </li>
  </ul>
</div>






<div id="periodos" style="display:none">
<br><br>
<br>
	<br>
	<h4 align=center>Periodos</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
    <input type="hidden" id="idperiodo_nomina" name="idperiodo_nomina">
<table width="60%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align='right' class='viewPropTitle'>Año</td>
    <td colspan="3"><select name="anio" id="anio" disabled="disabled">
                        <?
anio_fiscal();
?>
    </select></td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Descripcion del Periodo</td>
    <td colspan="3">
      <input name="descripcion_periodo" type="text" id="descripcion_periodo" size="80">
    </td>
  </tr>
      <tr>
      <td align='right' class='viewPropTitle'>Fecha de Inicio</td>
          <td>
          <input type="text" name="fecha_inicio" id="fecha_inicio" size="12" readonly="readonly"/>
      <img src="imagenes/jscalendar0.gif" name="f_trigger_c" width="16" height="16" id="f_trigger_c" style="cursor: pointer;" title="Selector de Fecha" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" />
    <script type="text/javascript">
                                Calendar.setup({
                                inputField    : "fecha_inicio",
                                button        : "f_trigger_c",
                                align         : "Tr",
                                ifFormat      : "%Y-%m-%d"
                                });
                            </script>
          </td>
      </tr>
   <tr>
    <td align='right' class='viewPropTitle'>Cierre de mes:&nbsp;</td>
    <td colspan="3">
    &nbsp;30
      <input name="cierre_mes" type="radio" id="cierre_mes_0" value="30">
      31
      <input name="cierre_mes" type="radio" id="cierre_mes_1" value="31">
    </td>
  </tr>
  <tr>
    <td valign="top" align='right' class='viewPropTitle'>Numero de Periodos</td>
    <td>
      <table>
      <tr>
      <td>
        <input type="radio" name="numero_periodo" value="52" id="numero_periodo_0" onclick="document.getElementById('otro_periodo').style.display = 'none', document.getElementById('dia_semana_comienza').style.display = 'block'">
        Semanal
        
       </td>
       <td>
       <select name="dia_semana_comienza" id="dia_semana_comienza" style="display:none">
        <option value="1">Domingo</option>
        <option value="2">Lunes</option>
        <option value="3">Martes</option>
        <option value="4">Miercoles</option>
        <option value="5">Jueves</option>
        <option value="6">Viernes</option>
        <option value="7">Sabado</option>
    </select>
       
       </td>
       </tr>
      
      <tr>
      <td colspan="2">
        <input type="radio" name="numero_periodo" value="24" id="numero_periodo_1" onclick="document.getElementById('otro_periodo').style.display = 'none',document.getElementById('dia_semana_comienza').style.display = 'none'">
        Quincenal
      </td>
      </tr>
      <tr>
      <td colspan="2">
        <input type="radio" name="numero_periodo" value="12" id="numero_periodo_2" onclick="document.getElementById('otro_periodo').style.display = 'none', document.getElementById('dia_semana_comienza').style.display = 'none'">
        Mensual
      </td>
      </tr>
      <tr>
      <td colspan="2">
        <input type="radio" name="numero_periodo" value="0" id="numero_periodo_3" onclick="document.getElementById('otro_periodo').style.display = 'block', document.getElementById('otro_periodo').value = '',document.getElementById('dia_semana_comienza').style.display = 'none'">
        Otro
      </td>
      <td><input type="text" name="otro_periodo" id="otro_periodo" style="display:none"></td>
      </tr>
    </table>
    </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
    <td align='right' class='viewPropTitle'>Activo</td>
    <td>
    <input type="checkbox" name="periodo_activo" id="periodo_activo">
    </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="boton_ingresar_periodo" id="boton_ingresar_periodo" value="Guardar Periodo" class="button" onclick="guardarPeriodo()"/></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5" id="celda_lista_pestanas">&nbsp;</td>
    </tr>
  <tr>
  <tr>
    <td colspan="5"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="5" align="center" bgcolor="#0066CC"><span class="Estilo1">TABLA DE PERIODOS</span></td>
        </tr>
      <tr>
        <td width="24%" align="center" bgcolor="#FFFFCC"><strong>Nro. Semana</strong></td>
        <td width="24%" align="center" bgcolor="#FFFFCC"><strong>Nro. Periodo</strong></td>
        <td width="25%" align="center" bgcolor="#FFFFCC"><strong>Desde</strong></td>
        <td width="27%" align="center" bgcolor="#FFFFCC"><strong>Hasta</strong></td>
        <td width="24%" align="center" bgcolor="#FFFFCC"><strong>Sugiere Pago</strong></td>
      </tr>
      <tr>
        <td colspan="5" id="celda_resultados_periodo">&nbsp;</td>
        </tr>
    </table></td>
  </tr>
</table>

</div>



<!-- ********************************************************************************************************************************* -->
<!-- ********************************************************************************************************************************* -->
<!-- ********************************************************************************************************************************* -->
<!-- ********************************************************************************************************************************* -->


<div id="fracciones" style="display:none">

<style type="text/css">
<!--
.Estilo1 {
	color: #FFFFFF;
	font-weight: bold;
}
-->
</style>
<br><br>
<br>
	<br>
	<h4 align=center>Fracciones</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
<table width="60%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="28%" valign="top" align='right' class='viewPropTitle'>Tipo de Fraccion:</td>
    <td width="64%"><p>
      <label>
        <input type="radio" name="tipo_fraccion" value="no aplica" id="tipo_fraccion_0" onclick="guardarTipoFraccion(this.value)">
        No Aplica</label>
      <br>
      <label>
        <input type="radio" name="tipo_fraccion" value="porcentual" id="tipo_fraccion_1" onclick="guardarTipoFraccion(this.value)">
        Porcentual</label>
      <br>
      <label>
        <input type="radio" name="tipo_fraccion" value="valor" id="tipo_fraccion_2" onclick="guardarTipoFraccion(this.value)">
        Valor Fijo</label>
      <br>
    </p></td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Numero de Fracciones:</td>
    <td><label>
      <input name="numero_fracciones" type="text" id="numero_fracciones" size="4" onkeyup="guardarNumeroFraccion(this.value)">
    </label></td>
  </tr>
  
  <tr>
    <td colspan="2">&nbsp;</td>
    </tr>
  <tr>
    <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="3" align="center" bgcolor="#0066CC"><span class="Estilo1">TABLE DE FRACCIONES</span></td>
        </tr>
      <tr>
        <td width="33%" align="center" bgcolor="#FFFFCC"><strong>Nro. Fraccion</strong></td>
        <td width="37%" align="center" bgcolor="#FFFFCC"><strong>% o Valor</strong></td>
        <td width="30%" align="center" bgcolor="#FFFFCC"><strong>Aplica a</strong></td>
      </tr>
      <tr>
        <td colspan="3" id="celda_datos_fraccion">&nbsp;</td>
        </tr>
    </table></td>
  </tr>
</table>

</div>





<!-- ********************************************************************************************************************************* -->
<!-- ********************************************************************************************************************************* -->
<!-- ********************************************************************************************************************************* -->
<!-- ********************************************************************************************************************************* -->






<div id="jornadas" style="display:none">

<br><br>
<br>
	<br>
	<h4 align=center>Jornada</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
<table width="80%" align="center" cellpadding="0" cellspacing="0" border="1">
  <tr>
    <td bgcolor="#FFFFCC">&nbsp;</td>
    <td align="center" bgcolor="#FFFFCC"><strong>Jornada Completa</strong></td>
    <td align="center" bgcolor="#FFFFCC"><strong>Media Jornada</strong></td>
    <td align="center" bgcolor="#FFFFCC"><strong>No Laborable</strong></td>
  </tr>
  <tr>
    <td align="right" bgcolor="#FFFFCC"><strong>Lunes</strong></td>
    <td align="center"><input type="radio" name="jornada_lunes" value="completa" id="jornada_lunes_0" onclick="guardarJornada('c', 'lunes')"></td>
    <td align="center"><input type="radio" name="jornada_lunes" value="media" id="jornada_lunes_1" onclick="guardarJornada('m', 'lunes')"></td>
    <td align="center"><input type="radio" name="jornada_lunes" value="no_laborable" id="jornada_lunes_2" onclick="guardarJornada('n', 'lunes')"></td>
  </tr>
  <tr>
    <td align="right" bgcolor="#FFFFCC"><strong>Martes</strong></td>
    <td align="center"><input type="radio" name="jornada_martes" value="completa" id="jornada_martes_0" onclick="guardarJornada('c', 'martes')"></td>
    <td align="center"><input type="radio" name="jornada_martes" value="media" id="jornada_martes_1" onclick="guardarJornada('m', 'martes')"></td>
    <td align="center"><input type="radio" name="jornada_martes" value="no_laborable" id="jornada_martes_2" onclick="guardarJornada('n', 'martes')"></td>
  </tr>
  <tr>
    <td align="right" bgcolor="#FFFFCC"><strong>Miercoles</strong></td>
    <td align="center"><input type="radio" name="jornada_miercoles" value="completa" id="jornada_miercoles_0" onclick="guardarJornada('c', 'miercoles')"></td>
    <td align="center"><input type="radio" name="jornada_miercoles" value="media" id="jornada_miercoles_1" onclick="guardarJornada('m', 'miercoles')"></td>
    <td align="center"><input type="radio" name="jornada_miercoles" value="no_laborable" id="jornada_miercoles_2" onclick="guardarJornada('n', 'miercoles')"></td>
  </tr>
  <tr>
    <td align="right" bgcolor="#FFFFCC"><strong>Jueves</strong></td>
    <td align="center"><input type="radio" name="jornada_jueves" value="completa" id="jornada_jueves_0" onclick="guardarJornada('c', 'jueves')"></td>
    <td align="center"><input type="radio" name="jornada_jueves" value="media" id="jornada_jueves_1" onclick="guardarJornada('m', 'jueves')"></td>
    <td align="center"><input type="radio" name="jornada_jueves" value="no_laborable" id="jornada_jueves_2" onclick="guardarJornada('n', 'jueves')"></td>
  </tr>
  <tr>
    <td align="right" bgcolor="#FFFFCC"><strong>Viernes</strong></td>
    <td align="center"><input type="radio" name="jornada_viernes" value="completa" id="jornada_viernes_0" onclick="guardarJornada('c', 'viernes')"></td>
    <td align="center"><input type="radio" name="jornada_viernes" value="media" id="jornada_viernes_1" onclick="guardarJornada('m', 'viernes')"></td>
    <td align="center"><input type="radio" name="jornada_viernes" value="no_laborable" id="jornada_viernes_2" onclick="guardarJornada('n', 'viernes')"></td>
  </tr>
  <tr>
    <td align="right" bgcolor="#FFFFCC"><strong>Sabado</strong></td>
    <td align="center"><input type="radio" name="jornada_sabado" value="completa" id="jornada_sabado_0" onclick="guardarJornada('c', 'sabado')"></td>
    <td align="center"><input type="radio" name="jornada_sabado" value="media" id="jornada_sabado_1" onclick="guardarJornada('m', 'sabado')"></td>
    <td align="center"><input type="radio" name="jornada_sabado" value="no_laborable" id="jornada_sabado_2" onclick="guardarJornada('n', 'sabado')"></td>
  </tr>
  <tr>
    <td align="right" bgcolor="#FFFFCC"><strong>Domingo</strong></td>
    <td align="center"><input type="radio" name="jornada_domingo" value="completa" id="jornada_domingo_0" onclick="guardarJornada('c', 'domingo')"></td>
    <td align="center"><input type="radio" name="jornada_domingo" value="media" id="jornada_domingo_1" onclick="guardarJornada('m', 'domingo')"></td>
    <td align="center"><input type="radio" name="jornada_domingo" value="no_laborable" id="jornada_domingo_2" onclick="guardarJornada('n', 'domingo')"></td>
  </tr>
</table>

</div>



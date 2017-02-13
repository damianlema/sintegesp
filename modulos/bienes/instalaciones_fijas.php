<script src="modulos/bienes/js/instalaciones_fijas_ajax.js"></script>
<br>
<h4 align=center>
	Instalaciones Fijas
<br>
<br>
</h4>
					<table width="542" border="0" align="center" cellpadding="0" cellspacing="4">
<tr>
                            <td width="30%" align="right" class='viewPropTitle'>Seleccione el Inmueble</td>
							<td width="65%">
<input type="text" size="60" name="denominacion_inmueble" id="denominacion_inmueble">
                            <img src="imagenes/search0.png" 
                            	onClick="window.open('modulos/bienes/lib/listar_inmuebles.php','listar_inmuebles','resizabled = no, width = 900, height = 500, scrolbars=yes')"
                                style="cursor:pointer">
                            </td>
							<td width="5%">
                            <img src="imagenes/delete.png" 
                            	onClick="eliminarInstalacion()"
                                style="cursor:pointer; display:none"
                                id="botonEliminar"
                                title="Eliminar Instalaciones Fijas de Este Inmueble">
                            </td>
                          </tr>
                        </table>


	<form method="POST" action="" id="formulario">
    <input type="hidden" id="tipo_inmueble" name="tipo_inmueble">
    <input type="hidden" id="idinmueble" name="idinmueble">
						
                        <table align="center">
                        <tr>
                        <td align="center">
                       		 
                        
                        
                        <table align="center" id="tablaIngresarModificar" style="display:none">
                        	<tr>
                        		<td>
                                    <table border="0" align="center" cellpadding="0" cellspacing="4" id="base" style="display:block;">
                                        <tr id="example" class="celda">
                                          <td align="right">Descripcion:</td>
                                            <td>
                                              <input type="hidden" name="idrelacion" id="idrelacion">
                                              <input type="text" name="descripcion" id="descripcion" size="80">                                            </td>
                                            <td align="right">Bs</td>
                                            <td>
                                              <input type="text" 
                                              		name="valor_mostrado" 
                                                    id="valor_mostrado" 
                                                    style="text-align:right" 
                                                    size="12"
                                                    onblur="formatoNumero(this.name, 'valor')"
                                                    value="0">
                                            	<input type="hidden" name="valor" id="valor" value="0">
                                            </td>
                                            <td align="right">Fecha</td>
                                            <td>
                                            <input type="text" name="fecha" id="fecha" size="12" readonly="readonly">
                                            <img src="imagenes/jscalendar0.gif" name="imagen_fecha_fin" width="16" height="16" id="imagen_fecha_fin" style="cursor: pointer;" title="Selector de Fecha" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" border="0" />
                                            <script>
											  Calendar.setup({
																inputField    : 'fecha',
																button        : 'imagen_fecha_fin',
																align         : 'Tr',
																ifFormat      : '%Y-%m-%d'
																});
											</script>
                                            </td>
                                            <td>
                                              <input type="button" 
                                              			name="botonGuardar" 
                                                        id="botonGuardar" 
                                                        value="Guardar" 
                                                        class="button" 
                                                        onClick="ingresarDatos()" 
                                                        style="display:block">
                                              
                                              <input type="button" 
                                              			name="botonModificar" 
                                                        id="botonModificar" 
                                                        value="Modificar" 
                                                        class="button" 
                                                        onClick="modificarDatos()" 
                                                        style="display:none">
                                            </td>
                                        </tr>
                                    </table>
                        		</td>
                        	</tr>
                        </table>
                        
                        
                          </td>
                          </tr>
                          </table>  
</form>

<br>
<br>
<h2 align="center">Lista de Instalaciones Fijas Para este Inmueble</h2>
<br>
<br>
<div id="listaInstalaciones"></div>
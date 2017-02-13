<script src="modulos/rrhh/js/leyes_prestaciones_ajax.js"></script>
	<body>
	<br>
	<h4 align=center>Leyes Aplicaci&oacute;n Prestaciones Sociales</h4>
	<h2 class="sqlmVersion"></h2>
    <input type="hidden" name="idleyes_prestaciones" id="idleyes_prestaciones">
    <br>
    <table align="center">
    	<tr>
        	<td align='right' class='viewPropTitle'>Siglas: </td>
            <td><input name="siglas" type="text" id="siglas" size="6" maxlength="6"></td>
        </tr>
	    <tr>
	        <td align='right' class='viewPropTitle'>Denominacion: </td>
	        <td><input type="text" name="denominacion" id="denominacion" size="50" maxlength="250"></td>
	    </tr>
	    <tr>
        	<td align='right' class='viewPropTitle'>Se calcula: </td>
            <td>
            	<select id="calcula" name="calcula">
            		<option value = 'mensual'>Mensual</option>
            		<option value = 'trimestral'>Trimestral</option>
            		<option value = 'anual'>Anual</option>
            		<option value = 'final'>Fin de Aplicaci&oacute;n de la Ley</option>
            	</select>
            </td>
        </tr>
    	<tr>
        	<td align='right' class='viewPropTitle'>Abona: </td>
            <td>
            	<select id="tipo_abono" name="tipo_abono">
            		<option value = 'mensual'>Mensual</option>
            		<option value = 'trimestral'>Trimestral</option>
            		<option value = 'anual'>Anual</option>
            	</select>
            </td>
        </tr>
       	<tr>
        	<td align='right' class='viewPropTitle'>Mes inicial abono: </td>
            <td>
            	<select name="mes_inicio_abono" id="mes_inicio_abono">
		             <option value="01">01</option>
		             <option value="02">02</option>
		             <option value="03">03</option>
		             <option value="04">04</option>
		             <option value="05">05</option>
		             <option value="06">06</option>
		             <option value="07">07</option>
		             <option value="08">08</option>
		             <option value="09">09</option>
		             <option value="10">10</option>
		             <option value="11">11</option>
		             <option value="12">12</option>
		         </select>
            </td>
        </tr>
        <tr>
        	<td align='right' class='viewPropTitle'>Valor abono: </td>
            <td><input name="valor_abono" type="text" id="valor_abono" size="4" maxlength="3"></td>
        </tr>
       
        <tr>
        	<td align='right' class='viewPropTitle'>Abono Adicional: </td>
            <td>
            	<select id="tipo_abono_adicional" name="tipo_abono_adicional">
            		<option value = 'NA'>No Aplica</option>
            		<option value = 'mensual'>Mensual</option>
            		<option value = 'trimestral'>Trimestral</option>
            		<option value = 'anual'>Anual</option>
            	</select>
            </td>
        </tr>
        <tr>
        	<td align='right' class='viewPropTitle'>Valor de abono adicional: </td>
            <td><input name="valor_abono_adicional" type="text" id="valor_abono_adicional" size="4" maxlength="3"></td>
        </tr>
        <tr>
        	<td align='right' class='viewPropTitle'>Tope abono adicional: </td>
            <td><input name="tope_abono_adicional" type="text" id="tope_abono_adicional" size="4" maxlength="3"></td>
        </tr>
        <tr>
        	<td align='right' class='viewPropTitle'>Aplica desde: </td>
            <td>
            	<select name="mes_inicio_aplica" id="mes_inicio_aplica">
		             <option value="01">01</option>
		             <option value="02">02</option>
		             <option value="03">03</option>
		             <option value="04">04</option>
		             <option value="05">05</option>
		             <option value="06">06</option>
		             <option value="07">07</option>
		             <option value="08">08</option>
		             <option value="09">09</option>
		             <option value="10">10</option>
		             <option value="11">11</option>
		             <option value="12">12</option>
		         </select>
		         <select name="anio_inicio_aplica" id="anio_inicio_aplica">
		         	<option>.:: Seleccione ::.</option>
                    <?
                    for($i=1930; $i<=(date("Y")+30);$i++){
                        ?>
                        <option value="<?=$i?>"><?=$i?></option>
                        <?	
                    }
                    ?>
		         </select>

            </td>
        </tr>
        <tr>
        	<td align='right' class='viewPropTitle'>Aplica hasta: </td>
            <td>
            	<select name="mes_fin_aplica" id="mes_fin_aplica">
		             <option value="01">01</option>
		             <option value="02">02</option>
		             <option value="03">03</option>
		             <option value="04">04</option>
		             <option value="05">05</option>
		             <option value="06">06</option>
		             <option value="07">07</option>
		             <option value="08">08</option>
		             <option value="09">09</option>
		             <option value="10">10</option>
		             <option value="11">11</option>
		             <option value="12">12</option>
		         </select>
		        <select name="anio_fin_aplica" id="anio_fin_aplica">
		         	<option>.:: Seleccione ::.</option>
                    <?
                    for($i=1930; $i<=(date("Y")+30);$i++){
                        ?>
                        <option value="<?=$i?>"><?=$i?></option>
                        <?	
                    }
                    ?>
		         </select> 
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
            <input type="button" name="ingresar" value="Ingresar" id="ingresar" onClick="registrarLeyesPrestaciones()" class="button">
            <input type="button" name="modificar" value="Modificar" id="modificar" onClick="modificarLeyesPrestaciones()" class="button" style="display:none">
        	<input type="button" name="limpiar" value="Limpiar" id="limpiar" onClick="limpiarFormulario()" class="button"></td>
        </tr>
    </table>
    
    
    
    <br>
<br>

    <div id="listaLeyesPrestaciones">
    <?
    $sql_leyes_prestaciones = mysql_query("select * from leyes_prestaciones");
	$num_leyes_prestaciones = mysql_num_rows($sql_leyes_prestaciones);
	if($num_leyes_prestaciones > 0){
	?>
    <table class="Browse" cellpadding="0" cellspacing="0" width="30%" align="center">
        <thead>
            <tr>
            	<td align="center" class="Browse">Siglas</td>
                <td align="center" class="Browse">Denominacion</td>
                <td align="center" class="Browse" colspan="2">Acci&oacute;n</td>
            </tr>
        </thead>
        <?
		while($bus_leyes_prestaciones = mysql_fetch_array($sql_leyes_prestaciones)){
		?>
        <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
				<td align='left' class='Browse'><?=$bus_leyes_prestaciones["siglas"]?></td>
                <td align='left' class='Browse'><?=$bus_leyes_prestaciones["denominacion"]?></td>
				<td align='center' class='Browse' width='7%'>
                    	<img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar' 
                    	onClick="mostrarParaModificar('<?=$bus_leyes_prestaciones["idleyes_prestaciones"]?>', 
                    										'<?=$bus_leyes_prestaciones["denominacion"]?>', 
                    										'<?=$bus_leyes_prestaciones["siglas"]?>',
                    										'<?=$bus_leyes_prestaciones["calcula"]?>',
                    										'<?=$bus_leyes_prestaciones["tipo_abono"]?>', 
                    										'<?=$bus_leyes_prestaciones["mes_inicial_abono"]?>',
                    										'<?=$bus_leyes_prestaciones["valor_abono"]?>', 
                    										'<?=$bus_leyes_prestaciones["valor_abono_adicional"]?>',
                    										'<?=$bus_leyes_prestaciones["tipo_abono_adicional"]?>', 
                    										'<?=$bus_leyes_prestaciones["valor_tope_adicional"]?>',
                    										'<?=$bus_leyes_prestaciones["mes_desde"]?>', 
                    										'<?=$bus_leyes_prestaciones["anio_desde"]?>',
                    										'<?=$bus_leyes_prestaciones["mes_hasta"]?>', 
                    										'<?=$bus_leyes_prestaciones["anio_hasta"]?>'
                    										)" style="cursor:pointer">
                </td>
				<td align='center' class='Browse' width='7%'>
                    	<img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar' onClick="eliminarLeyesPrestaciones('<?=$bus_leyes_prestaciones["idleyes_prestaciones"]?>')" style="cursor:pointer">
               	</td>
         </tr>
         <?
         }
		 ?>
    </table>
    <?
    }
	?>
    </div>
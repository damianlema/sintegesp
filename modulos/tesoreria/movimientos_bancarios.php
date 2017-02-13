<script src="modulos/tesoreria/js/movimientos_bancarios_ajax.js"></script>
	<body>
	<br>
	<h4 align=center>Tipos Movientos Bancarios</h4>
	<h2 class="sqlmVersion"></h2>
    <input type="hidden" name="id_tipo_movimiento" id="id_tipo_movimiento">
    <br>
    <table align="center">
      <tr>
        <td align='right' class='viewPropTitle'>Denominacion: </td>
        <td><input type="text" name="denominacion" id="denominacion" size="40"></td>
      </tr>
    	<tr>
        	<td align='right' class='viewPropTitle'>Siglas: </td>
            <td><input name="siglas" type="text" id="siglas" size="4" maxlength="3"></td>
        </tr>
    	<tr>
        	<td align='right' class='viewPropTitle'>Afecta: </td>
            <td>
                Debita 
                  <input type="radio" name="afecta" id="debita" value="d"> 
                Acredita <input type="radio" name="afecta" id="acredita" value="a">            </td>
        </tr>
        <tr>
        	<td align='right' class='viewPropTitle'>Sin asiento Contable: </td>
            <td>
                 <input type="checkbox" name="excluir_contabilidad" id="excluir_contabilidad"/>
            </td>
        </tr>
        <tr>
      	<td colspan="2" style="background:#09F; text-align:center; font-family:Verdana, Geneva, sans-serif; font-size:12px"><strong>AFECTACI&Oacute;N CONTABLE</strong></td>
    	</tr>
         <tr>
    <td class="viewPropTitle" align="right">Afecta por el Debe:</td>
      <td colspan="3">
       <label> 
       <select name="cuenta_deudora" id="cuenta_deudora">
       <option value='0-0' onClick="document.getElementById('tabla_deudora').value = '0', document.getElementById('idcuenta_deudora').value = '0'">..:: Cuenta Contable de la cuenta bancaria ::..</option>
        <?
       		$sql_consultar = mysql_query("(SELECT
						  d.iddesagregacion_cuentas_contables as idcuenta,
						  CONCAT(g.codigo, '.', s.codigo, '.', r.codigo, '.', c.codigo, '.', sc.codigo, '.', sc2.codigo, '.', d.codigo) AS codigo,
						  d.denominacion,
						  'desagregacion_cuentas_contables' AS tabla
					FROM
						  desagregacion_cuentas_contables d
						  INNER JOIN subcuenta_segundo_cuentas_contables sc2 ON (d.idsubcuenta_segundo = sc2.idsubcuenta_segundo_cuentas_contables)
						  INNER JOIN subcuenta_primer_cuentas_contables sc ON (sc2.idsubcuenta_primer = sc.idsubcuenta_primer_cuentas_contables)
						  INNER JOIN cuenta_cuentas_contables c ON (sc.idcuenta= c.idcuenta_cuentas_contables)
						  INNER JOIN rubro_cuentas_contables r ON (c.idrubro = r.idrubro_cuentas_contables)
						  INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables)
						  INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables)
					)

						UNION

					(SELECT
							  sc2.idsubcuenta_segundo_cuentas_contables as idcuenta,
							  CONCAT(g.codigo, s.codigo, r.codigo, '.', c.codigo, '.', sc.codigo, '.', sc2.codigo) AS codigo,
							  sc2.denominacion,
							  'subcuenta_segundo_cuentas_contables' AS tabla
					FROM
							  subcuenta_segundo_cuentas_contables sc2
							  INNER JOIN subcuenta_primer_cuentas_contables sc ON (sc2.idsubcuenta_primer = sc.idsubcuenta_primer_cuentas_contables)
							  INNER JOIN cuenta_cuentas_contables c ON (sc.idcuenta= c.idcuenta_cuentas_contables)
							  INNER JOIN rubro_cuentas_contables r ON (c.idrubro = r.idrubro_cuentas_contables)
							  INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables)
							  INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables)
					WHERE (
							  sc2.idsubcuenta_segundo_cuentas_contables not in(select idsubcuenta_segundo from desagregacion_cuentas_contables)

					)
					)
					UNION
					(SELECT
							  sc.idsubcuenta_primer_cuentas_contables as idcuenta,
							  CONCAT(g.codigo, s.codigo, r.codigo, '.', c.codigo, '.', sc.codigo) AS codigo,
							  sc.denominacion,
							  'subcuenta_primer_cuentas_contables' AS tabla
					FROM
							  subcuenta_primer_cuentas_contables sc
							  INNER JOIN cuenta_cuentas_contables c ON (sc.idcuenta= c.idcuenta_cuentas_contables)
							  INNER JOIN rubro_cuentas_contables r ON (c.idrubro = r.idrubro_cuentas_contables)
							  INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables)
							  INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables)
					WHERE (
							  sc.idsubcuenta_primer_cuentas_contables not in(select idsubcuenta_primer from subcuenta_segundo_cuentas_contables)
					
					)
					)
					UNION
					(SELECT
							  cc.idcuenta_cuentas_contables as idcuenta,
							  CONCAT(g.codigo, s.codigo, r.codigo, '.', cc.codigo) AS codigo,
							  cc.denominacion,
							  'cuenta_cuentas_contables' AS tabla
					FROM
							  cuenta_cuentas_contables cc
							  INNER JOIN rubro_cuentas_contables r ON (cc.idrubro = r.idrubro_cuentas_contables)
							  INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables)
							  INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables)

					)
          			UNION
					(SELECT
							  r.idrubro_cuentas_contables as idcuenta,
							  CONCAT(g.codigo, s.codigo, r.codigo) AS codigo,
							  r.denominacion,
							  'rubro_cuentas_contables' AS tabla
					FROM
							  rubro_cuentas_contables r
							  INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables)
							  INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables)

					) ORDER BY codigo")or die(mysql_error());
			
			while($bus_cuenta_deudora = mysql_fetch_array($sql_consultar)){?>
				<option value="<?=$bus_cuenta_deudora["idcuenta"]?>-<?=$bus_cuenta_deudora["tabla"]?>" onClick="document.getElementById('tabla_deudora').value = '<?=$bus_cuenta_deudora["tabla"]?>', document.getElementById('idcuenta_deudora').value = '<?=$bus_cuenta_deudora["idcuenta"]?>'"><?=$bus_cuenta_deudora["codigo"]?>- <?=utf8_decode($bus_cuenta_deudora["denominacion"])?></option>
			<? }?>
      </select>
      </label>
      <input name="tabla_deudora" type="hidden" id="tabla_deudora" size="100" value="0">
      <input name="idcuenta_deudora" type="hidden" id="idcuenta_deudora" size="100" value="0">
      <input name="idcuenta_deudora_modificar" type="hidden" id="idcuenta_deudora_modificar" size="100">
        </td>
    </tr>
    <tr>
      
      <td class="viewPropTitle" align="right">Afecta por el Haber:</td>
      <td colspan="3">
       <label> 
       <select name="cuenta_acreedora" id="cuenta_acreedora">
       <option value='0-0' onClick="document.getElementById('tabla_acreedora').value = '0', document.getElementById('idcuenta_acreedora').value = '0'">..:: Cuenta Contable de la cuenta bancaria ::..</option>
        <?
       		$sql_consultar = mysql_query("(SELECT
						  d.iddesagregacion_cuentas_contables as idcuenta,
						  CONCAT(g.codigo, '.', s.codigo, '.', r.codigo, '.', c.codigo, '.', sc.codigo, '.', sc2.codigo, '.', d.codigo) AS codigo,
						  d.denominacion,
						  'desagregacion_cuentas_contables' AS tabla
					FROM
						  desagregacion_cuentas_contables d
						  INNER JOIN subcuenta_segundo_cuentas_contables sc2 ON (d.idsubcuenta_segundo = sc2.idsubcuenta_segundo_cuentas_contables)
						  INNER JOIN subcuenta_primer_cuentas_contables sc ON (sc2.idsubcuenta_primer = sc.idsubcuenta_primer_cuentas_contables)
						  INNER JOIN cuenta_cuentas_contables c ON (sc.idcuenta= c.idcuenta_cuentas_contables)
						  INNER JOIN rubro_cuentas_contables r ON (c.idrubro = r.idrubro_cuentas_contables)
						  INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables)
						  INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables)
					)

						UNION

					(SELECT
							  sc2.idsubcuenta_segundo_cuentas_contables as idcuenta,
							  CONCAT(g.codigo, s.codigo, r.codigo, '.', c.codigo, '.', sc.codigo, '.', sc2.codigo) AS codigo,
							  sc2.denominacion,
							  'subcuenta_segundo_cuentas_contables' AS tabla
					FROM
							  subcuenta_segundo_cuentas_contables sc2
							  INNER JOIN subcuenta_primer_cuentas_contables sc ON (sc2.idsubcuenta_primer = sc.idsubcuenta_primer_cuentas_contables)
							  INNER JOIN cuenta_cuentas_contables c ON (sc.idcuenta= c.idcuenta_cuentas_contables)
							  INNER JOIN rubro_cuentas_contables r ON (c.idrubro = r.idrubro_cuentas_contables)
							  INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables)
							  INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables)
					WHERE (
							  sc2.idsubcuenta_segundo_cuentas_contables not in(select idsubcuenta_segundo from desagregacion_cuentas_contables)

					)
					)
					UNION
					(SELECT
							  sc.idsubcuenta_primer_cuentas_contables as idcuenta,
							  CONCAT(g.codigo, s.codigo, r.codigo, '.', c.codigo, '.', sc.codigo) AS codigo,
							  sc.denominacion,
							  'subcuenta_primer_cuentas_contables' AS tabla
					FROM
							  subcuenta_primer_cuentas_contables sc
							  INNER JOIN cuenta_cuentas_contables c ON (sc.idcuenta= c.idcuenta_cuentas_contables)
							  INNER JOIN rubro_cuentas_contables r ON (c.idrubro = r.idrubro_cuentas_contables)
							  INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables)
							  INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables)
					WHERE (
							  sc.idsubcuenta_primer_cuentas_contables not in(select idsubcuenta_primer from subcuenta_segundo_cuentas_contables)
					
					)
					)
					UNION
					(SELECT
							  cc.idcuenta_cuentas_contables as idcuenta,
							  CONCAT(g.codigo, s.codigo, r.codigo, '.', cc.codigo) AS codigo,
							  cc.denominacion,
							  'cuenta_cuentas_contables' AS tabla
					FROM
							  cuenta_cuentas_contables cc
							  INNER JOIN rubro_cuentas_contables r ON (cc.idrubro = r.idrubro_cuentas_contables)
							  INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables)
							  INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables)

					)
         			UNION
					(SELECT
							  r.idrubro_cuentas_contables as idcuenta,
							  CONCAT(g.codigo, s.codigo, r.codigo) AS codigo,
							  r.denominacion,
							  'rubro_cuentas_contables' AS tabla
					FROM
							  rubro_cuentas_contables r
							  INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables)
							  INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables)

					) ORDER BY codigo")or die(mysql_error());
			while($bus_cuenta_acreedora = mysql_fetch_array($sql_consultar)){?>
				<option value="<?=$bus_cuenta_acreedora["idcuenta"]?>-<?=$bus_cuenta_acreedora["tabla"]?>" onClick="document.getElementById('tabla_acreedora').value = '<?=$bus_cuenta_acreedora["tabla"]?>', document.getElementById('idcuenta_acreedora').value = '<?=$bus_cuenta_acreedora["idcuenta"]?>'"><?=$bus_cuenta_acreedora["codigo"]?> - <?=utf8_decode($bus_cuenta_acreedora["denominacion"])?></option>
			<? }?>
      </select>
      </label>
      <input name="tabla_acreedora" type="hidden" id="tabla_acreedora" size="100" value="0">
      <input name="idcuenta_acreedora" type="hidden" id="idcuenta_acreedora" size="100" value="0">
      <input name="idcuenta_acreedora_modificar" type="hidden" id="idcuenta_acreedora_modificar" size="100">
        </td>
    </tr>
        
        
        <tr>
            <td colspan="2" align="center">
            <input type="button" name="ingresar" value="Ingresar" id="ingresar" onClick="registrarTiposMovimientos()" class="button">
            <input type="button" name="modificar" value="Modificar" id="modificar" onClick="modificarTiposMovimientos()" class="button" style="display:none">
        	<input type="button" name="limpiar" value="Limpiar" id="limpiar" onClick="limpiarFormulario()" class="button"></td>
        </tr>
    </table>
    
    
    
    <br>
<br>

    <div id="listaTiposMovimientos">
    <?
    $sql_tipos_movimientos = mysql_query("select * from tipo_movimiento_bancario");
	$num_tipos_movimientos = mysql_num_rows($sql_tipos_movimientos);
	if($num_tipos_movimientos > 0){
	?>
    <table class="Browse" cellpadding="0" cellspacing="0" width="30%" align="center">
        <thead>
            <tr>
                <td align="center" class="Browse">Denominacion</td>
                <td align="center" class="Browse">Siglas</td>
                <td align="center" class="Browse">Afecta</td>
                <td align="center" class="Browse" colspan="2">Acci&oacute;n</td>
            </tr>
        </thead>
        <?
		while($bus_tipos_movimientos = mysql_fetch_array($sql_tipos_movimientos)){
		?>
        <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
				<td align='left' class='Browse'><?=$bus_tipos_movimientos["denominacion"]?></td>
                <td align='left' class='Browse'><?=$bus_tipos_movimientos["siglas"]?></td>
                    <td align='left' class='Browse'>
                    <?
                    if($bus_tipos_movimientos["afecta"] == "d"){
                        echo "Debita";
                    }else{
                        echo "Acredita";
                    }
                    ?>
                    </td>
				<td align='center' class='Browse' width='7%'>
                    	<img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar' onClick="mostrarParaModificar('<?=$bus_tipos_movimientos["idtipo_movimiento_bancario"]?>', '<?=$bus_tipos_movimientos["denominacion"]?>', '<?=$bus_tipos_movimientos["siglas"]?>', '<?=$bus_tipos_movimientos["afecta"]?>', '<?=$bus_tipos_movimientos["idcuenta_debe"]?>', '<?=$bus_tipos_movimientos["tabla_debe"]?>', '<?=$bus_tipos_movimientos["idcuenta_haber"]?>', '<?=$bus_tipos_movimientos["tabla_haber"]?>', '<?=$bus_tipos_movimientos["excluir_contabilidad"]?>')" style="cursor:pointer">
                </td>
				<td align='center' class='Browse' width='7%'>
                    	<img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar' onClick="eliminarTipoMovimiento('<?=$bus_tipos_movimientos["idtipo_movimiento_bancario"]?>')" style="cursor:pointer">
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
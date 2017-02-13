<script src="modulos/tesoreria/js/cuentas_bancarias_ajax.js"></script>
	<body>
	<br>
	<h4 align=center>Cuentas Bancarias</h4>
	<h2 class="sqlmVersion"></h2>
    
    
    
    
    
    
   <!-- ************************************************************************************************************************************* 
    ****************************************************** TABLA DE DATOS BASICOS DE LA CUENTA ***********************************************
    *******************************************************************************************************************************************--> 
    
    
    
    
<table width="95%" border="0" align="center" cellpadding="2" cellspacing="0">
  <tr>
    <td colspan="6">
    
      <div align="center">
  	<img src="imagenes/search0.png" title="Buscar Cuentas Bancarias" style="cursor:pointer" onClick="window.open('modulos/tesoreria/lib/listar_cuentas_bancarias.php','cuentas_bancarias','resisable = no, scrollbars = yes, width = 900, height = 400')" /> 
    <img src="imagenes/nuevo.png" title="Ingresar nueva Orden de Pago" onClick="window.location.href='principal.php?modulo=7&accion=416'" style="cursor:pointer" /> 
    <img src="imagenes/imprimir.png" title="Imprimir Cuentas Bancarias"  style="cursor:pointer" />  
    </div>
    <input type="hidden" name="id_cuenta_bancaria" id="id_cuenta_bancaria">
    </td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Numero de Cuenta</td>
    <td>
      <input name="nro_cuenta" type="text" id="nro_cuenta" size="27" maxlength="20">    </td>
    <td align='right' class='viewPropTitle'>Banco</td>
    <td colspan="2">
      <?
      $sql_bancos = mysql_query("select * from banco");
	  ?>
      <input type="hidden" name="denominacion_banco" id="denominacion_banco">
      <select name="banco" id="banco">
      <option value="0">.:: Seleccione ::.</option>
	  <?
      while($bus_bancos = mysql_fetch_array($sql_bancos)){
	  	?>
		<option value="<?=$bus_bancos["idbanco"]?>" onClick="document.getElementById('denominacion_banco').value = '<?=$bus_bancos["denominacion"]?>'"><?=$bus_bancos["denominacion"]?></option>
		<?
	  }
	  ?>
      </select>    </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Tipo de Cuenta</td>
    <td>
      <?
      $sql_tipo_cuenta = mysql_Query("select * from tipo_cuenta_bancaria");
	  ?>
      <select name="tipo_cuenta" id="tipo_cuenta">
      <option value="0">.:: Seleccione ::.</option>
      <?
      while($bus_tipo_cuenta = mysql_fetch_array($sql_tipo_cuenta)){
	  	?>
			<option value="<?=$bus_tipo_cuenta["idtipo_cuenta"]?>"><?=$bus_tipo_cuenta["denominacion"]?></option>
		<?
	  }
	  ?>
      </select>    </td>
    <td align='right' class='viewPropTitle'>Validez del Documento</td>
    <td>
      <input name="validez_documento" type="text" id="validez_documento" size="3">    </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Fecha Apertura de la Cuenta</td>
    <td>
      <input name="fecha_apertura" type="text" id="fecha_apertura" size="11" readonly>
        <img src="imagenes/jscalendar0.gif" name="imagen_fecha_apertura" width="16" height="16" id="imagen_fecha_apertura" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" onClick="Calendar.setup({
                        inputField    : 'fecha_apertura',
                        button        : 'imagen_fecha_apertura',
                        align         : 'Tr',
                        ifFormat      : '%Y-%m-%d'
                        });"/>    </td>
    <td align='right' class='viewPropTitle'>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Fecha Inicio del Periodo</td>
    <td>
      <input name="fecha_inicio_periodo" type="text" id="fecha_inicio_periodo" size="11" readonly value="<?=$_SESSION["anio_fiscal"]."-01-01"?>">
        <img src="imagenes/jscalendar0.gif" name="imagen_fecha_inicio" width="16" height="16" id="imagen_fecha_inicio" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" onClick="Calendar.setup({
							inputField    : 'fecha_inicio_periodo',
							button        : 'imagen_fecha_inicio',
							align         : 'Tr',
							ifFormat      : '%Y-%m-%d'
							});"/>    </td>
    <td align='right' class='viewPropTitle'>Fecha Final del Periodo</td>
    <td>
      <input name="fecha_fin_periodo" type="text" id="fecha_fin_periodo" size="11" readonly value="<?=$_SESSION["anio_fiscal"]."-12-31"?>">
        <img src="imagenes/jscalendar0.gif" name="imagen_fecha_fin" width="16" height="16" id="imagen_fecha_fin" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" onClick="Calendar.setup({
                        inputField    : 'fecha_fin_periodo',
                        button        : 'imagen_fecha_fin',
                        align         : 'Tr',
                        ifFormat      : '%Y-%m-%d'
                        });"/>    </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
   <tr>
    <td align='right' class='viewPropTitle'>Saldo Apertura/Inicio Periodo Banco</td>
    <td>
      <input name="monto_apertura" type="text" id="monto_apertura" size="16">    </td>

    <td align='right' class='viewPropTitle'>Saldo Apertura/Inicio Periodo Libro</td>
    <td>
      <input name="monto_apertura_libro" type="text" id="monto_apertura_libro" size="16">    </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Uso de Cuenta</td>
    <td colspan="3"><input name="uso_cuenta" type="text" id="uso_cuenta" size="80"></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Estado</td>
    <td>
      <select name="estado_cuenta" id="estado_cuenta">
        <option value="0">.:: Seleccione ::.</option>
        <option value="Activa">Activa</option>
        <option value="Bloqueada">Bloqueada</option>
      </select>    </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Firma Autorizada 1</td>
    <td>
      <input name="firma_autorizada" type="text" id="firma_autorizada" size="30">    </td>
    <td align='right' class='viewPropTitle'>CI de la Fima Autorizada 1</td>
    <td>
      <input name="ci_firma_autorizada" type="text" id="ci_firma_autorizada" size="12">    </td>
    <td align='right' class='viewPropTitle'>Cargo 1</td>
    <td>
      <input name="cargo_firma_autorizada" type="text" id="cargo_firma_autorizada" size="30">    </td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Firma Autorizada 2</td>
    <td>
      <input name="firma_autorizada_2" type="text" id="firma_autorizada_2" size="30">    </td>
    <td align='right' class='viewPropTitle'>CI de la firma Autorizada 2</td>
    <td>
      <input name="ci_firma_autorizada_2" type="text" id="ci_firma_autorizada_2" size="12">    </td>
    <td align='right' class='viewPropTitle'>Cargo 2</td>
    <td>
      <input name="cargo_firma_autorizada_2" type="text" id="cargo_firma_autorizada_2" size="30">    </td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Firma Autorizada 3</td>
    <td>
      <input name="firma_autorizada_3" type="text" id="firma_autorizada_3" size="30">    </td>
    <td align='right' class='viewPropTitle'>CI de la firma Autorizada 3</td>
    <td>
      <input name="ci_firma_autorizada_3" type="text" id="ci_firma_autorizada_3" size="12">    </td>
    <td align='right' class='viewPropTitle'>Cargo 3</td>
    <td>
      <input name="cargo_firma_autorizada_3" type="text" id="cargo_firma_autorizada_3" size="30">    </td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Conjuntas</td>
    <td>
      <input type="checkbox" name="conjuntas" id="conjuntas">    </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
      <td colspan="6" style="background:#09F; text-align:center; font-family:Verdana, Geneva, sans-serif; font-size:12px"><strong>AFECTACI&Oacute;N CONTABLE</strong></td>
  </tr>
  <tr>
    <td class="viewPropTitle" align="right" width="20%">Cuenta Mayor a la que pertenece esta sub-cuenta:</td>
      <td colspan="5">
       <label> 
       <select name="cuenta_deudora" id="cuenta_deudora">
       	<option value='0'>..:: Seleccione la Cuenta ::..</option>
        <?
       		$sql_consultar = mysql_query("(SELECT
							  sc2.idsubcuenta_segundo_cuentas_contables as idcuenta,
							  CONCAT(g.codigo, s.codigo, r.codigo, '.', c.codigo, '.', sc.codigo, '.', sc2.codigo) AS codigo,
							  sc2.denominacion,
							  'subcuenta_segundo' AS tabla
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
							  'subcuenta_primer' AS tabla
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
							  'cuenta_cuentas' AS tabla
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
							  'rubro_cuentas' AS tabla
					FROM
							  rubro_cuentas_contables r
							  INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables)
							  INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables)

					) ORDER BY codigo")or die(mysql_error());
			
			while($bus_cuenta_deudora = mysql_fetch_array($sql_consultar)){?>
				<option value="<?=$bus_cuenta_deudora["idcuenta"]?>-<?=$bus_cuenta_deudora["tabla"]?>" onClick="document.getElementById('sub_cuenta').value = 'Banco '+document.getElementById('denominacion_banco').value+' '+document.getElementById('nro_cuenta').value, document.getElementById('tabla').value = '<?=$bus_cuenta_deudora["tabla"]?>', document.getElementById('idcuenta').value = '<?=$bus_cuenta_deudora["idcuenta"]?>'"><?=$bus_cuenta_deudora["codigo"]?>- <?=utf8_decode($bus_cuenta_deudora["denominacion"])?></option>
			<? }?>
      </select>
      </label>
        
        </td>
    </tr>
  
  <tr>
    <td align='right' class='viewPropTitle'>Nueva sub-cuenta</td>
    <td colspan="5">
      <input name="tabla" type="hidden" id="tabla" size="100">
      <input name="idcuenta" type="hidden" id="idcuenta" size="100">
      <input name="idcuenta_modificar" type="hidden" id="idcuenta_modificar" size="100">
      <input name="sub_cuenta" type="text" id="sub_cuenta" size="100">    </td>
   
  </tr>
  <tr>
    <td><label></label></td>
    <td><label></label></td>
    <td colspan="2" align="center">
    <input type="button" name="ingresar_cuenta" id="ingresar_cuenta" value="Ingresar" class="button" onClick="ingresarCuentaBancaria()">
	
    <table align="center">
    <tr>
        <td>
            <input type="button" name="modificar_cuenta" id="modificar_cuenta" value="Modificar" class="button" onClick="modificarCuentaBancaria()" style="display:none">
        </td>
        <td>
            <input type="button" name="eliminar_cuenta" id="eliminar_cuenta" value="Eliminar" class="button" onClick="eliminarCuentaBancaria()" style="display:none">
        </td>
    </tr>
    </table>

    </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
	<br>
    <br>
    <h2 class="sqlmVersion" align="center">Chequeras de la Cuenta Bancaria</h2>
    
    
    
    <!-- ************************************************************************************************************************************* 
    ****************************************************** TABLA DE CHEQUERAS DE LA CUENTA ***************************************************
    *******************************************************************************************************************************************-->
    
  <br>
  
   <div id="tablaChequeras" style="display:none"> 

    <table width="70%" border="0" align="center" cellpadding="0" cellspacing="0" >
      <tr>
      	<td colspan="4" align="center"><img src="imagenes/nuevo.png" title="Ingresar Nueva Chequera" onClick="limpiarFormularioChequera()" style="cursor:pointer" /> </td>
      </tr>
      
      <tr>
        <td width="30%" align='right' class='viewPropTitle'>Numero de Chequera</td>
        <td width="22%">
          <input name="numero_chequera" type="text" id="numero_chequera" size="10">        </td>
        <td width="24%" align='right' class='viewPropTitle'>Cantidad de Cheques</td>
        <td width="24%">
        <input name="cantidad_cheques" type="text" id="cantidad_cheques" size="5" onBlur="sumarCheques()" value="0">       </td>
      </tr>
      <tr>
        <td align='right' class='viewPropTitle'>Digitos Consecutivos</td>
        <td><input type="radio" name="digitos_consecutivos" value="inicio" id="digitos_consecutivos_inicio">
		Inicio
            &nbsp;
            <input name="digitos_consecutivos" type="radio" id="digitos_consecutivos_fin" value="final">
		Final 
        </td>
        <td align='right' class='viewPropTitle'>Cantidad de Digitos Aleatorios</td>
        <td>
          <input name="cantidad_digitos" type="text" id="cantidad_digitos" onKeyUp="cambiarCantidadNumeroInicial(this.value)" value="" size="2" maxlength="1">
        </td>
      </tr>
      
      <tr>
        <td align='right' class='viewPropTitle'>Numero Inicial</td>
        <td>
          <input name="numero_inicial" type="text" id="numero_inicial" size="4" maxlength="2" onBlur="sumarCheques()" value="0">          </td>
        <td align='right' class='viewPropTitle'>Numero Final</td>
        <td>
          <input name="numero_final" type="text" id="numero_final" size="4" maxlength="3" disabled>        </td>
      </tr>
      <tr>
        <td align='right' class='viewPropTitle'>Fecha Inicial de Uso</td>
        <td>
          <input name="fecha_inicial_uso" type="text" id="fecha_inicial_uso" size="12" disabled>        </td>
        <td align='right' class='viewPropTitle'>Fecha Final de Uso</td>
        <td>
          <input name="fecha_final_uso" type="text" id="fecha_final_uso" size="12" disabled>        </td>
      </tr>
      <tr>
        <td align='right' class='viewPropTitle'>Estado</td>
        <td>
          <select name="estado" id="estado">
            <option value="0">.:: Seleccione ::.</option>
            <option value="Activa">Activa</option>
            <option value="Bloqueada">Bloqueada</option>
          </select>        </td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><input type="hidden" id="idchequeras" name="idchequeras"></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="2" align="center">
          <input type="button" name="ingresar_chequera" id="ingresar_chequera" value="Ingresar"  class="button" onClick="ingresarChequera()">
           <input type="button" name="modificar_chequeras" id="modificar_chequeras" value="Modificar"  class="button" onClick="modificarChequeras()" style="display:none">       </td>
        <td>&nbsp;</td>
      </tr>
    </table>
	</div>
    <br>



    <!-- ************************************************************************************************************************************* 
    ****************************************************** LISTA DE CHEQUERAS PARA LA CUENTA BANCARIA ****************************************
    *******************************************************************************************************************************************-->
    
    
    
    <div id="listaChequeras" style=" display:none"></div>	
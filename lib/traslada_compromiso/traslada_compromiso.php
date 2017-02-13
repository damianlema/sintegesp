<script src="lib/traslada_compromiso/js/traslada_compromiso_ajax.js" type="text/javascript" language="javascript"></script>
<br />
<?
$sql_configuracion = mysql_query("select * from configuracion");
$bus_configuracion = mysql_fetch_array($sql_configuracion);
$anio_fijo         = $bus_configuracion["anio_fiscal"];
include "../../../funciones/funciones.php";
?>
<h2 align="center">
A&ntilde;o a trasladar los compromisos por causar
</h2>
<br />
<table width="100%" id="datosExtra">
        <tr>
        <td align="right" >A&ntilde;o</td>
             <td >
              <select name="anio_receptor" id="anio_receptor" disabled="disabled">
                        <?
anio_fiscal();
?>
              </select>
          </td>
            <td align="right"  width="21%">Fuente de Financiamiento</td>
            <td colspan="2">
                <select name="fuente_financiamiento" id="fuente_financiamiento">
                  <option value = 0>.:: Seleccione ::.</option>
                  <?php
$sql_fuente_financiamiento = mysql_query("select * from fuente_financiamiento
                                                        where status='a'"
    , $conexion_db);
while ($rowfuente_financiamiento = mysql_fetch_array($sql_fuente_financiamiento)) {
    ?>
                  <option onclick="document.getElementById('cofinanciamiento').value = 'no'" <?php echo 'value="' . $rowfuente_financiamiento["idfuente_financiamiento"] . '"';
    if ($rowfuente_financiamiento["idfuente_financiamiento"] == $idfuente_financiamiento_fijo) {echo ' selected';} ?>> <?php echo $rowfuente_financiamiento["denominacion"]; ?> </option>
                  <?php
}

$sql_cofinanciamiento = mysql_query("select * from cofinanciamiento");
while ($bus_cofinanciamiento = mysql_fetch_array($sql_cofinanciamiento)) {
    ?>
                                <option onclick="document.getElementById('cofinanciamiento').value = 'si'" value="<?=$bus_cofinanciamiento["idcofinanciamiento"]?>"><?=$bus_cofinanciamiento["denominacion"]?></option>
                                <?
}
?>

                </select>
                <input type="hidden" id="cofinanciamiento" name="cofinanciamiento" value="">
            </td>
            <td align="right" >Tipo Presupuesto</td>
            <td align="right" ><select name="tipo_presupuesto" id="tipo_presupuesto">
                <option value = 0>.:: Seleccione ::.</option>
                <?php
$sql_tipo_presupuesto = mysql_query("select * from tipo_presupuesto
                                                    where status='a'"
    , $conexion_db);
while ($rowtipo_presupuesto = mysql_fetch_array($sql_tipo_presupuesto)) {
    ?>
                <option <?php echo 'value="' . $rowtipo_presupuesto["idtipo_presupuesto"] . '"';
    if ($rowtipo_presupuesto["idtipo_presupuesto"] == $idtipo_presupuesto_fijo) {echo ' selected';} ?>> <?php echo $rowtipo_presupuesto["denominacion"]; ?> </option>
                <?php
}
?>
                </select>
             </td>
             <td align='right' >Categoria Programatica:</td>
			<td >
				<select name="categoria_programatica" id="categoria_programatica" style="width:95%">
					<option value = 0>&nbsp;</option>
					<?php
$sql_categoria_programatica = mysql_query("select * from categoria_programatica
												order by codigo");
while ($rowcategoria_programatica = mysql_fetch_array($sql_categoria_programatica)) {
    ?>
									<option <?php echo 'value="' . $rowcategoria_programatica["idcategoria_programatica"] . '"'; ?>>
										<?php echo $rowcategoria_programatica["codigo"]; ?>									</option>
					<?php
}
?>
				</select>			</td>
      </tr>
  </table>


<br /><br />

<h2 align="center">
Lista de Compromisos a Trasladar
</h2>
<br />
     <h2 class="sqlmVersion"></h2>
<br>
<form action="" method="post">
<table width="80%" id="datosExtra">
     <tr>
 		<td align="right" >A&ntilde;o de los compromisos a trasladar</td>
        <td >
          <select name="anio" id="anio">
            <option value="2008" <?php if ("2008" == $_POST["anio"]) {echo ' selected';}?>>2008</option>
            <option value="2009" <?php if ("2009" == $_POST["anio"]) {echo ' selected';}?>>2009</option>
            <option value="2010" <?php if ("2010" == $_POST["anio"]) {echo ' selected';}?>>2010</option>
            <option value="2011" <?php if ("2011" == $_POST["anio"]) {echo ' selected';}?>>2011</option>
            <option value="2012" <?php if ("2012" == $_POST["anio"]) {echo ' selected';}?>>2012</option>
            <option value="2013" <?php if ("2013" == $_POST["anio"]) {echo ' selected';}?>>2013</option>
            <option value="2014" <?php if ("2014" == $_POST["anio"]) {echo ' selected';}?>>2014</option>
            <option value="2015" <?php if ("2015" == $_POST["anio"]) {echo ' selected';}?>>2015</option>
            <option value="2016" <?php if ("2016" == $_POST["anio"]) {echo ' selected';}?>>2016</option>
            <option value="2017" <?php if ("2017" == $_POST["anio"]) {echo ' selected';}?>>2017</option>
          </select>
      </td>
      <tr>
 		<td align="right" >Tipo de Documento a trasladar</td>
        <td >
          <select name="tipo_documento" id="tipo_documento">
          <?
$tipo_documento = mysql_query("select * from tipos_documentos where compromete = 'si' and causa = 'no' and paga = 'no'");
while ($bus_tipo = mysql_fetch_array($tipo_documento)) {
    ?>
            <option value="<?=$bus_tipo["idtipos_documentos"]?>" <?php if ($bus_tipo["idtipos_documentos"] == $_POST["tipo_documento"]) {echo ' selected';}?>><?=$bus_tipo["descripcion"]?></option>

           <?}
?>
          </select>
      </td>

      <td>
      	<input type="submit" name="buscar" id="buscar" value="Buscar" class="button">
      </td>
  </tr>
</table>
</form>


  <?
if ($_POST) {
    //echo $_POST["tipo_documento"];

    $query = "select * from gestion_" . $_POST["anio"] . ".orden_compra_servicio,
									gestion_" . $_POST["anio"] . ".beneficiarios
								where
									beneficiarios.idbeneficiarios = orden_compra_servicio.idbeneficiarios
									and orden_compra_servicio.estado != 'elaboracion'
									and orden_compra_servicio.estado != 'anulado'
									and orden_compra_servicio.tipo = " . $_POST["tipo_documento"] . "

						 order by orden_compra_servicio.codigo_referencia";

    //echo $query;

    $sql_ordenes = mysql_query($query) or die(" error este " . mysql_error());

    $num_ordenes = mysql_num_rows($sql_ordenes);
    if ($num_ordenes != 0) {

        while ($bus_ordenes = mysql_fetch_array($sql_ordenes)) {

            //if ($bus_ordenes["id_siguiente"] == 0){

            $sql_partidas = mysql_query("select * from gestion_" . $_POST["anio"] . ".partidas_orden_compra_servicio where idorden_compra_servicio = " . $bus_ordenes["idorden_compra_servicio"] . "");

            $num_partidas = mysql_num_rows($sql_partidas);
            $entro        = 0;
            if ($num_partidas != 0) {

                while ($bus_partidas = mysql_fetch_array($sql_partidas)) {

                    $sql_maestro = mysql_query("select * from gestion_" . $_POST["anio"] . ".maestro_presupuesto where idRegistro = " . $bus_partidas["idmaestro_presupuesto"] . "");
                    $bus_maestro = mysql_fetch_array($sql_maestro);

                    $sql_consultar = mysql_query("select SUM(par.monto) as total_causados
																			 FROM
																			gestion_" . $_POST["anio"] . ".orden_compra_servicio oc
																			INNER JOIN gestion_" . $_POST["anio"] . ".relacion_pago_compromisos rpc ON (oc.idorden_compra_servicio = rpc.idorden_compra_servicio)
																			INNER JOIN gestion_" . $_POST["anio"] . ".orden_pago op ON (rpc.idorden_pago = op.idorden_pago)
																			INNER JOIN gestion_" . $_POST["anio"] . ".partidas_orden_pago par ON (par.idorden_pago = op.idorden_pago
																							and par.idmaestro_presupuesto = '" . $bus_partidas["idmaestro_presupuesto"] . "')
																			WHERE
																			oc.idorden_compra_servicio = '" . $bus_ordenes["idorden_compra_servicio"] . "'
																			and (op.estado = 'parcial' or
																												op.estado = 'pagada' or
																												op.estado = 'procesado')") or die(mysql_error());
                    $bus_consultar = mysql_fetch_array($sql_consultar);

                    $resta = $bus_partidas["monto"] - $bus_consultar["total_causados"];
                    if ($resta > 0) {
                        $entro = 1;
                    }

                }
            }

            if ($entro > 0) {

                ?>
          	<br />
  			<br />
            <table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="96%">
              <thead>
              <tr>
                <td class="Browse" width="8%"><div align="center">N&uacute;mero</div></td>
                <td class="Browse" width="12%"><div align="center">Fecha Orden</div></td>
                <td class="Browse" width="31%"><div align="center">Proveedor</div></td>
                <td class="Browse" width="34%"><div align="center">Justificacion</div></td>
                <td class="Browse" width="7%"><div align="center">Estado</div></td>
                <td class="Browse" width="6%"><div align="center">Acciones</div></td>
              </tr>
              </thead>
                  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                    <td class='Browse' align='left'>&nbsp;<?=$bus_ordenes["numero_orden"]?></td>
                    <td class='Browse' align='center'>
						<?
                if ($bus_ordenes["fecha_orden"] == '0000-00-00') {
                    echo "<strong>No Procesada</strong>";
                } else {
                    echo $bus_ordenes["fecha_orden"];
                }
                ?>
                       	<input type="hidden" name="idorden_compra_trasladar" id="idorden_compra_trasladar" value="<?=$bus_ordenes["idorden_compra_servicio"]?>">

                    </td>
                    <td class='Browse' align='left'><?=utf8_decode($bus_ordenes["nombre"])?></td>
                    <td class='Browse' align='left'><?=utf8_decode($bus_ordenes["justificacion"])?></td>
                    <td class='Browse' align='left'><?=$bus_ordenes["estado"]?></td>

                        <td class='Browse' align="center">
                        <?
                if ($bus_ordenes["id_siguiente"] == 0) {
                    ?>
                        		<img src="imagenes/validar.png"
                            		title="Trasladar Compromiso"
                            		onclick="trasladarCompromiso('<?=$bus_ordenes["idorden_compra_servicio"]?>')">
        				 <?
                } else {
                    ?>
                        		<strong>Ya trasladado</strong>
                        <?
                }

                ?>
                        </td>
                  </tr>
                  <tr>
                  	<td colspan="6">


                    <?
                $sql_partidas = mysql_query("select * from gestion_" . $_POST["anio"] . ".partidas_orden_compra_servicio where idorden_compra_servicio = " . $bus_ordenes["idorden_compra_servicio"] . "");

                $num_partidas = mysql_num_rows($sql_partidas);
                if ($num_partidas != 0) {
                    ?>
							<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
								<thead>
								  	<tr>
                                        <td class="Browse" colspan="4"><div align="center">Partida</div></td>
                                        <td width="21%" class="Browse"><div align="center">Fuente de Financiamiento</div></td>
                                        <td width="41%" class="Browse"><div align="center">Descripci&oacute;n</div></td>
                                        <td width="14%" class="Browse"><div align="center">Compromiso</div></td>
                                        <td width="14%" class="Browse"><div align="center">Causado</div></td>
                                        <td width="14%" class="Browse"><div align="center">Por Causar</div></td>
									</tr>
								</thead>
								  <?
                    while ($bus_partidas = mysql_fetch_array($sql_partidas)) {

                        $sql_maestro = mysql_query("select * from gestion_" . $_POST["anio"] . ".maestro_presupuesto where idRegistro = " . $bus_partidas["idmaestro_presupuesto"] . "");
                        $bus_maestro = mysql_fetch_array($sql_maestro);
                        ?>
								  	<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
										<?

                        $sql_clasificador = mysql_query("select * from gestion_" . $_POST["anio"] . ".clasificador_presupuestario where idclasificador_presupuestario = '" . $bus_maestro["idclasificador_presupuestario"] . "'") or die(mysql_error());
                        $bus_clasificador = mysql_fetch_array($sql_clasificador);
                        ?>
										<td width="3%" align='center' class='Browse'><?=$bus_clasificador["partida"]?></td>
										<td width="3%" align='center' class='Browse'><?=$bus_clasificador["generica"]?></td>
										<td width="3%" align='center' class='Browse'><?=$bus_clasificador["especifica"]?></td>
										<td width="5%" align='center' class='Browse'><?=$bus_clasificador["sub_especifica"]?></td>

										<?
                        $sql_fuente = mysql_query("select * from gestion_" . $_POST["anio"] . ".fuente_financiamiento where idfuente_financiamiento = '" . $bus_maestro["idfuente_financiamiento"] . "'");
                        $bus_fuente = mysql_fetch_array($sql_fuente);
                        ?>
										<td class='Browse' align='left'>&nbsp;<?=$bus_fuente["denominacion"]?></td>
										<td class='Browse' align='left'><?=utf8_decode($bus_clasificador["denominacion"])?></td>
										<?

                        $sql_consultar = mysql_query("select SUM(par.monto) as total_causados
																			 FROM
																			gestion_" . $_POST["anio"] . ".orden_compra_servicio oc
																			INNER JOIN gestion_" . $_POST["anio"] . ".relacion_pago_compromisos rpc ON (oc.idorden_compra_servicio = rpc.idorden_compra_servicio)
																			INNER JOIN gestion_" . $_POST["anio"] . ".orden_pago op ON (rpc.idorden_pago = op.idorden_pago)
																			INNER JOIN gestion_" . $_POST["anio"] . ".partidas_orden_pago par ON (par.idorden_pago = op.idorden_pago
																							and par.idmaestro_presupuesto = '" . $bus_partidas["idmaestro_presupuesto"] . "')
																			WHERE
																			oc.idorden_compra_servicio = '" . $bus_ordenes["idorden_compra_servicio"] . "'
																			and (op.estado = 'parcial' or
																												op.estado = 'pagada' or
																												op.estado = 'procesado')") or die(mysql_error());
                        $bus_consultar = mysql_fetch_array($sql_consultar);

                        $resta  = $bus_partidas["monto"] - $bus_consultar["total_causados"];
                        $resta2 = $resta - $resta - $resta;
                        $id     = 'por_causar' . $bus_partidas["idpartidas_orden_compra_servicio"];
                        ?>

                                         <input type="hidden" name="<?=$id?>" id="<?=$id?>" value="<?=$resta?>">

										 <td class='Browse' align='right'><?=number_format($bus_partidas["monto"], 2, ',', '.')?></td>
										 <td class='Browse' align='right'><?=number_format($bus_consultar["total_causados"], 2, ',', '.')?></td>
										 <td class='Browse' align='right'><?=number_format($resta, 2, ',', '.')?></td>

									  <?

                    }

                    ?>

							  </tr>
								</table>







                    </td>
                  </tr>
          <?
                }
            }

            ?>

        </table>
 <?
            // }
        }
    } else {
        echo "<center><strong>No se encontraron resultados</strong></center>";
    }

}

?>
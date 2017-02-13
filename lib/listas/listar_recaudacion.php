<?
include("../../conf/conex.php");
Conectarse();
?>
	<link href="../../css/theme/green/main.css" rel="stylesheet" type="text/css">
	<script src="../../js/function.js" type="text/javascript" language="javascript"></script>
	<br>
	<h4 align=center>Listado de Detalles</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
<form method="post" action="">
<table align="center">
  <tr>
    	<td>Tipo Solicitud / Razon Social / Descripcion</td>
        <td><input type="text" id="campo_buscar" name="campo_buscar"></td>
        <td><input type="submit" id="buscar" name="buscar" value="Buscar" class="button"></td>
    </tr>
</table>     
</form>

<?

if($_POST){

	$sql_consulta = mysql_query("select pr.idpagos_recaudacion,
									pr.fecha_pago,
									pr.numero_planilla,
									c.razon_social,
									pr.estado,
									pr.total,
									pr.descuento,
									pr.mora,
									pr.total_cancelar
										from 
									pagos_recaudacion pr,
									contribuyente c
										where
									pr.idcontribuyente = c.idcontribuyente
									and (c.razon_social like '%".$_POST["campo_buscar"]."%'
										or pr.numero_planilla like '%".$_POST["campo_buscar"]."%')");

}else{
$sql_consulta = mysql_query("select pr.idpagos_recaudacion,
									pr.fecha_pago,
									pr.numero_planilla,
									c.razon_social,
									pr.estado,
									pr.total,
									pr.descuento,
									pr.mora,
									pr.total_cancelar
										from 
									pagos_recaudacion pr,
									contribuyente c
										where
									pr.idcontribuyente = c.idcontribuyente")or die(mysql_error());


}

?>

<table width="100%" align="center" cellpadding="0" cellspacing="0" class="Main">
<tr>
					<td align="center">
                    
                    
						<table width="98%" border="0" align=center cellpadding="0" cellspacing="0" class="Browse">
			  				<thead>
								<tr>
								  <td width="12%" align="center" class="Browse">Numero de Planilla</td>
                                  <td width="10%" align="center" class="Browse">Fecha de Pago</td>
                                    <td width="19%" align="center" class="Browse">Contribuyente</td>
                                    <td width="19%" align="center" class="Browse">Total</td>
                                    <td width="18%" align="center" class="Browse">Descuento</td>
                                    <td width="27%" align="center" class="Browse">Mora</td>
                                    <td width="9%" align="center" class="Browse">Total Cancelar</td>
                                    <td width="9%" align="center" class="Browse">Estado</td>
                                  <td align="center" class="Browse">Acci&oacute;n</td>
								</tr>
							</thead>
							<?
                            while($bus_consulta = mysql_fetch_array($sql_consulta)){
							?>
						  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onClick="opener.consultarRecaudacion(<?=$bus_consulta["idpagos_recaudacion"]?>), window.close()">
                           <td width="12%" align="center" class="Browse">&nbsp;
						   <?
                           if($bus_consulta["numero_planilla"] == "0" or $bus_consulta["numero_planilla"] == ""){
						   	echo "<strong>No Generado</strong>";
						   }else{
						   	echo $bus_consulta["numero_planilla"];
						   }
						   ?></td>
                            <td width="10%" align="center" class="Browse">&nbsp;<?=$bus_consulta["fecha_pago"]?></td>
                            <td width="10%" align="left" class="Browse">&nbsp;<?=$bus_consulta["razon_social"]?></td>
                            <td width="19%" align="right" class="Browse">&nbsp;<?=number_format($bus_consulta["total"],2,",",".")?></td>
                            <td width="19%" align="right" class="Browse">&nbsp;<?=number_format($bus_consulta["descuento"],2,",",".")?></td>
                            <td width="18%" align="right" class="Browse">&nbsp;<?=number_format($bus_consulta["mora"],2,",",".")?></td>
                            <td width="27%" align="right" class="Browse">&nbsp;<?=number_format($bus_consulta["total_cancelar"],2,",",".")?></td>
                                    <td width="9%" align="center" class="Browse">&nbsp;
									<?
                                    if($bus_consulta["estado"]== "anulado"){
										echo "<strong>".$bus_consulta["estado"]."</strong>";
									}else{
										echo $bus_consulta["estado"];
									}
									?></td>
                            <td width="5%" align="center" class='Browse'>
                              <img src="../../imagenes/validar.png" 
                                style="cursor:pointer"
                                onclick="opener.consultarRecaudacion(<?=$bus_consulta["idpagos_recaudacion"]?>), window.close()">                            </td>
                          </tr>
							<?
							}
							?>
					  </table>
                        
					
      </td>
    </tr>
  </table>
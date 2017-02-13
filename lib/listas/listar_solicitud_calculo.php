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

if($_POST["idtipo_solicitud"] == 0){
	$sql_consulta = mysql_query("select sc.idsolicitud_calculo,
									sc.fecha_solicitud,
									sc.numero_solicitud,
									sc.descripcion,
									c.razon_social,
									sc.estado,
									ts.descripcion as tipo_solicitud
										from 
									solicitud_calculo sc,
									contribuyente c,
									tipo_solicitud ts
										where
									sc.idcontribuyente = c.idcontribuyente
									and sc.idtipo_solicitud = ts.idtipo_solicitud
									and (c.razon_social like '%".$_POST["campo_buscar"]."%'
										or sc.descripcion like '%".$_POST["campo_buscar"]."%')");
	}else{
		$sql_consulta = mysql_query("select sc.idsolicitud_calculo,
									sc.fecha_solicitud,
									sc.numero_solicitud,
									sc.descripcion,
									c.razon_social,
									sc.estado,
									ts.descripcion as tipo_solicitud
										from 
									solicitud_calculo sc,
									contribuyente c,
									tipo_solicitud ts
										where
									sc.idcontribuyente = c.idcontribuyente
									and sc.idtipo_solicitud = ts.idtipo_solicitud
									and (c.razon_social like '%".$_POST["campo_buscar"]."%'
										or sc.descripcion like '%".$_POST["campo_buscar"]."%')
									and ts.idtipo_solicitud = '".$_POST["idtipo_solicitud"]."'");
	}

}else{
$sql_consulta = mysql_query("select sc.idsolicitud_calculo,
									sc.fecha_solicitud,
									sc.numero_solicitud,
									sc.descripcion,
									sc.estado,
									c.razon_social,
									ts.descripcion as tipo_solicitud
										from 
									solicitud_calculo sc,
									contribuyente c,
									tipo_solicitud ts
										where
									sc.idcontribuyente = c.idcontribuyente
									and sc.idtipo_solicitud = ts.idtipo_solicitud");

}

?>

<table width="100%" align="center" cellpadding="0" cellspacing="0" class="Main">
<tr>
					<td align="center">
                    
                    
						<table width="98%" border="0" align=center cellpadding="0" cellspacing="0" class="Browse">
			  				<thead>
								<tr>
								  <td width="12%" align="center" class="Browse">Numero de Solicitud</td>
                                  <td width="10%" align="center" class="Browse">Fecha de Solicitud</td>
                                    <td width="19%" align="center" class="Browse">Tipo Solicitud</td>
                                    <td width="18%" align="center" class="Browse">Contribuyente</td>
                                    <td width="27%" align="center" class="Browse">Descripcion</td>
                                    <td width="9%" align="center" class="Browse">Estado</td>
                                  <td align="center" class="Browse">Acci&oacute;n</td>
								</tr>
							</thead>
							<?
                            while($bus_consulta = mysql_fetch_array($sql_consulta)){
							?>
						  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onClick="opener.consultarSolicitud(<?=$bus_consulta["idsolicitud_calculo"]?>), window.close()">
                           <td width="12%" align="center" class="Browse">&nbsp;
						   <?
                           if($bus_consulta["numero_solicitud"] == "0" or $bus_consulta["numero_solicitud"] == ""){
						   	echo "<strong>No Generado</strong>";
						   }else{
						   	echo $bus_consulta["numero_solicitud"];
						   }
						   ?></td>
                                    <td width="10%" align="center" class="Browse">&nbsp;<?=$bus_consulta["fecha_solicitud"]?></td>
                                    <td width="19%" align="center" class="Browse">&nbsp;<?=$bus_consulta["tipo_solicitud"]?></td>
                            <td width="18%" align="center" class="Browse">&nbsp;<?=$bus_consulta["razon_social"]?></td>
                            <td width="27%" align="center" class="Browse">&nbsp;<?=$bus_consulta["descripcion"]?></td>
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
                                onclick="opener.consultarSolicitud(<?=$bus_consulta["idsolicitud_calculo"]?>), window.close()">                            </td>
                          </tr>
							<?
							}
							?>
					  </table>
                        
					
      </td>
    </tr>
  </table>
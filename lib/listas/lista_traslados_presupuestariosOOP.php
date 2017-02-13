<?php
/**
*
*	"lista_traslados_presupuestarios.php" Listado de Traslados Presupuestarios
*	Version: 1.0.1
*	Fecha Ultima Modificacion: 13/02/2009
*	Autor: Hector Lema
*
*/
session_start();
include_once("../../conf/conex.php");

$conexion_db=conectarse();

$sql_traslados=mysql_query("select * from traslados_presupuestarios where status='a' order by nro_solicitud",$conexion_db);

?>

<link rel="stylesheet" type="text/css" href="../../css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="../../css/datatables.min.css"/>

<?php
/*
<SCRIPT language=JavaScript>
document.oncontextmenu=new Function("return false")
function ponTraslado(idTraslado){
	m=document.buscar.modoactual.value
	opener.document.forms[0].idtraslados_emergente.value=idTraslado
	opener.document.forms[0].idtraslados_presupuestarios.value=idTraslado
	opener.document.forms[0].modoactual.value=1
	opener.document.forms[0].juntos.value=document.buscar.juntos.value
	opener.document.forms[0].guardo.value=true
	opener.document.forms[0].emergente.value="true"
	opener.document.forms[0].submit()
	window.close()
}
</SCRIPT>
*/
?>


<body>
<div class = "container-fluid">
  <div class="panel panel-primary">
    <div class="panel-heading">
      <h3 class="panel-title" style="font-size:14px;">Traslados Presupuestarios</h3>
    </div>
	<div class="panel-body">
        <div class="col-lg-12">
            <table data-page-length='5' align="center" id="lista_traslados" class="table table-striped table=hover display" width="98%">
              	<thead>
	                <tr>
	                	<th><h6><strong>Nro. Solicitud</strong></h6></th>
	                  	<th><h6><strong>Nro. Resoluci&oacute;n</strong></h6></th>
	                  	<th><h6><strong>Fecha Resoluci&oacute;n</strong></h6></th>
	                  	<th><h6><strong>Justificaci&oacute;n</strong></h6></th>
	                  	<th><h6><strong>Estado</strong></h6></th>
	                  	<th><h6><strong>Acciones</strong></h6></th>
	                </tr>
              	</thead>
              	<tbody>
              	<?
             	while($registros_grilla = mysql_fetch_array($sql_traslados)){
	      		    ?>
	                <tr style="cursor:pointer" >
	                  	<td align='left'><h6><?=$registros_grilla["nro_solicitud"]?></h6></td>
	                  	<td align='left'><h6><?=$registros_grilla["nro_resolucion"]?></h6></td>
	                  	<td align='center'><h6><?=$registros_grilla["fecha_resolucion"]?></h6></td>
	                  	<td align='left'><h6><?=$registros_grilla["justificacion"]?></h6></td>
	                  	<td align='left'><h6><?=$registros_grilla["estado"]?></h6></td>
	                  	<td align="center">
	                  		<button type="button" class="btn btn-default btn-circle" title="Seleccionar Traslado Presupuestario"
	                  	 			onclick="window.onUnload = window.opener.document.getElementById('numeroSolicitud').value="<?=$registros_grilla['idtraslados_presupuestarios']?>", window.close()";>
	                      		<i class="glyphicon glyphicon-ok"></i>
	                      	</button>
	                    </td>
	                </tr>
	            <?
	            }
	            ?>
            	</tbody>
        	</table>
        </div>
    </div>
</div>

<script type="text/javascript" src="../../js/function.js"></script>
<script type="text/javascript" src="../../js/jquery.min.js"></script>
<script type="text/javascript" src="../../js/bootstrap.min.js"></script>
<script type="text/javascript" src="../../js/datatables.min.js"></script>

<script type="text/javascript">
  TablaPaginada('lista_traslados');
</script>
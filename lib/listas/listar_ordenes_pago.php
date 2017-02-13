<?
session_start();
include("../../conf/conex.php");
$conection_db = conectarse();
include("../../funciones/funciones.php");




?>
<link rel="stylesheet" type="text/css" href="../../css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="../../css/datatables.min.css"/>


<div class = "container-fluid">
  <div class="panel panel-primary">
    <div class="panel-heading">
      <h3 class="panel-title">Ordenes de Pago</h3>
    </div>
    <div class="panel-body">
      <form action="" method="post">
        <div class="row">
          <div class="col-md-7">
            <label for="fuente_financiamiento" class="control-label">Fuente de Financiamiento</label><br>
              <select class="form-control input-sm" id="fuente_financiamiento" name="fuente_financiamiento">
              <option value="0">.:: Seleccione ::.</option>
              <?
      				  $sql_fuente_financiamiento = mysql_query("select * from fuente_financiamiento");
      					while ($bus_fuente_financiamiento = mysql_fetch_array($sql_fuente_financiamiento)){
      				  ?>
                            <option value="<?=$bus_fuente_financiamiento["idfuente_financiamiento"]?>"><?=$bus_fuente_financiamiento["denominacion"]?></option>
                        <?
      				  	}
      				?>
             </select>
          </div>
          <div class="col-md-2">
              <label for="estado_cotizacion" class="control-label">Estado</label>
              <select class="form-control input-sm" id="estado_cotizacion" name="estado_cotizacion">
                <option value="0">.:: Seleccione ::.</option>
                <option <? if($_POST["estado_cotizacion"] == "elaboracion"){echo "selected";}?> value="elaboracion">En Elaboracion</option>
                <option <? if($_POST["estado_cotizacion"] == "procesado"){echo "selected";}?> value="procesado">Procesado</option>
                <option <? if($_POST["estado_cotizacion"] == "conformado"){echo "selected";}?> value="conformado">Conformado</option>
                <option <? if($_POST["estado_cotizacion"] == "devuelto"){echo "selected";}?> value="devuelto">Devuelto</option>
                <option <? if($_POST["estado_cotizacion"] == "parcial"){echo "selected";}?> value="devuelto">Parcial</option>
                <option <? if($_POST["estado_cotizacion"] == "anulado"){echo "selected";}?> value="anulado">Anulado</option>
                <option <? if($_POST["estado_cotizacion"] == "pagada"){echo "selected";}?> value="pagada">Pagado</option>
              </select>
            </div>
          <div class="col-md-2">
            <label class="texto_busqueda-label" for="inputSmall">Buscar</label>
              <input type="text" class="form-control input-sm" size="20" placeholder="Ingrese texto a buscar" 
                      name="texto_busqueda" id="texto_busqueda">
          </div>

          <div class="col-md-1">
            <label for="textoBuscar" class="col-lg-2 control-label">&nbsp;</label>
              <input type="submit" name="buscar" id="buscar" value="Buscar" class="btn btn-primary input-sm">
          </div>
        </div>
      </form>
    </div>
  </div>

  <?
  if($_POST["buscar"]){
  ?>
    <div class="panel panel-primary">
      <div class="panel-body">
        <div class="col-lg-12">
          <?php
        	$filtro = '';
      		if ($_POST["estado_cotizacion"] != "0"){
      			$filtro = " and orden_pago.estado = '".$_POST["estado_cotizacion"]."' ";
      		}
      		if ($_POST["fuente_financiamiento"] != "0"){
      			$filtro = $filtro." and orden_pago.idfuente_financiamiento = '".$_POST["fuente_financiamiento"]."' ";
      		}

      		$sqlordenes = "select * from orden_pago, beneficiarios
      											where beneficiarios.idbeneficiarios = orden_pago.idbeneficiarios
      											and (orden_pago.numero_orden like '%".$_POST["texto_busqueda"]."%'
      											or beneficiarios.nombre like '%".$_POST["texto_busqueda"]."%'
      											or orden_pago.justificacion like '%".$_POST["texto_busqueda"]."%') ".$filtro."
                            ORDER BY orden_pago.codigo_referencia";

      		$sql_ordenes = mysql_query($sqlordenes);
      		$num_ordenes = mysql_num_rows($sql_ordenes);
      		if($num_ordenes != 0){
            ?>
            <table data-page-length='5' align="center" id="lista_op" class="table table-striped table=hover display" width="98%">
              <thead>
                <tr>
                	<th><h6><strong>N&uacute;mero</strong></h6></th>
                  <th><h6><strong>Proveedor / Beneficiario</strong></h6></th>
                  <th><h6><strong>Fecha</strong></h6></th>
                  <th><h6><strong>Justificaci&oacute;n</strong></h6></th>
                  <th><h6><strong>Estado</strong></h6></th>
                  <th><h6><strong>Acciones</strong></h6></th>
                </tr>
              </thead>

              <tbody>
              <?
              while($bus_ordenes = mysql_fetch_array($sql_ordenes)){
      		    ?>
                <tr style="cursor:pointer" onclick="window.onUnload = window.opener.consultarOrdenPago(<?=$bus_ordenes['idorden_pago']?>),  
                                                    window.close()">
                  <td align='left'><h6><?=$bus_ordenes["numero_orden"]?></h6></td>
                  <td align='left'><h6><?=$bus_ordenes["nombre"]?></h6></td>
                  <td align='center'><h6><?=$bus_ordenes["fecha_orden"]?></h6></td>
                  <td align='left'><h6><?=$bus_ordenes["justificacion"]?></h6></td>
                  <td align='left'><h6><?=$bus_ordenes["estado"]?></h6></td>
                  <td align="center"
                                      onClick="window.onUnload = window.opener.consultarOrdenPago(<?=$bus_ordenes['idorden_pago']?>),
                                              window.close()">
                      <img style="cursor:pointer" src="../../imagenes/validar.png"></td>

                </tr>
              <?
              }
              ?>
              </tbody>
            </table>
         <?php
          }else{
          ?>
            <div class="alert alert-dismissible alert-danger">
              <h4>Alerta!</h4>
              <p><strong>No se encontraron resultados,</strong> verifique los criterios de busqueda y reintentelo</p>
            </div>
        <?php
          }
          ?>
        </div>
      </div>
    </div>
  <?php
  }
  ?>
</div>

<script type="text/javascript" src="../../js/function.js"></script>
<script type="text/javascript" src="../../js/jquery.min.js"></script>
<script type="text/javascript" src="../../js/bootstrap.min.js"></script>
<script type="text/javascript" src="../../js/datatables.min.js"></script>

<script type="text/javascript">
  TablaPaginada('lista_op');
</script>

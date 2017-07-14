<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>SINTEGESP - Sistema Integrado de Gestión Pública</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" type="text/css" href="../../../css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../../../css/datatables.min.css">
    <link rel="stylesheet" type="text/css" href="../../../css/ajustes.css">
    <link rel="stylesheet" type="text/css" href="../../../css/bootstrap-datetimepicker.css">
    <link rel="stylesheet" type="text/css" href="../../../css/bootstrapValidator.css">
    <link rel="stylesheet" type="text/css" href="../../../css/bootstrap-modal.css">
    <link rel="stylesheet" type="text/css" href="../../../css/font-awesome.css">
  </head>
<body>
<?php
include '../../../templates/mensajes.php';
?>

<div class = "container-fluid">
  <div class="panel panel-primary">
    <div class="panel-heading">
      <h3 class="panel-title">Presupuesto de gastos</h3>
    </div>
    <div class="panel-body">
      <form action="" method="post">
        <div class="row">
          <div class="col-md-2">
              <label for="tipo_presupuesto" class="control-label">Tipo de Presupuesto</label><br>
                <select class="form-control input-sm" id="tipo_presupuesto" name="tipo_presupuesto">

                </select>
            </div>
            <div class="col-md-2">
              <label for="fuente_financiamiento" class="control-label">Fuente de Financiamiento</label><br>
                <select class="form-control input-sm" id="fuente_financiamiento" name="fuente_financiamiento">

                </select>
            </div>
            <div class="col-md-5">
              <label for="categoria_programatica" class="control-label">Categoría Programática</label><br>
                <select class="form-control input-sm" id="categoria_programatica" name="categoria_programatica">

                </select>
            </div>
            <div class="col-md-2">
              <label class="texto_busqueda-label" for="inputSmall">Buscar</label>
                <input type="text" class="form-control input-sm" size="30" placeholder="Ingrese texto a buscar"
                      name="texto_busqueda" id="texto_busqueda">
            </div>

            <div class="col-md-1">
              <label for="textoBuscar" class="col-lg-2 control-label">&nbsp;</label>
                <input type="submit" name="btnBuscar" id="btnBuscar" value="Buscar" class="btn btn-primary input-sm">
            </div>
        </div>
      </form>
    </div>
  </div>

  <div class="panel panel-primary" id="divResultado" style="display: block;">
    <div class="panel-body">
      <div class="col-lg-12" id="mostrarResultados">

        <table data-page-length='5' align="center" id="lista_presupuesto" class="table table-striped table=hover display" width="98%">
          <thead>
            <tr>
              <th style="width: 10%; height: 5px; padding: 0px;">
                <h5><small><strong>Tipo Ppto.</strong></small></h5>
              </th>
              <th style="width: 20%; height: 5px; padding: 0px;">
                <h5><small><strong>Fuente de Financiamiento</strong></small></h5>
              </th>
              <th style="width: 10%; height: 5px; padding: 0px;">
                <h5><small><strong>Categoría Prog.</strong></small></h5>
              </th>
              <th style="width: 10%; height: 5px; padding: 0px;">
                <h5><small><strong>Partida</strong></small></h5>
              </th>
              <th style="width: 50%; height: 5px; padding: 0px;">
                <h5><small><strong>Denominación</strong></small></h5>
              </th>
              <th style="width: 10%; height: 5px; padding: 0px;">
                <h5><small><strong>Disponible Bs.</strong></small></h5>
              </th>
            </tr>
          </thead>

          <tbody>

          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>



<script type="text/javascript" src="../../../js/function.js"></script>
<script type="text/javascript" src="../../../js/jquery.min.js"></script>
<script type="text/javascript" src="../../../js/moment.js"></script>
<script type="text/javascript" src="../../../js/es.js"></script>
<script type="text/javascript" src="../../../js/transition.js"></script>
<script type="text/javascript" src="../../../js/collapse.js"></script>
<script type="text/javascript" src="../../../js/bootstrap.min.js"></script>
<script type="text/javascript" src="../../../js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="../../../js/datatables.min.js"></script>
<script type="text/javascript" src="../../../js/bootstrapValidator.min.js"></script>
<script type="text/javascript" src="../../../js/bootstrap-modalmanager.js"></script>
<script type="text/javascript" src="../../../js/bootstrap-modal.js"></script>
<script type="text/javascript" src="../../../lib/listas/js/listaPresupuesto.Ajax.js" language="javascript"></script>

<script type="text/javascript">
  TablaPaginada('lista_presupuesto');
</script>
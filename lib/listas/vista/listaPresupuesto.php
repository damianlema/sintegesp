<?php
session_start();
include("../../../conf/conex.php");
$conection_db = conectarse();
include("../../../funciones/funciones.php");
?>
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
    include('../../../templates/mensajes.php');
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
              		<option value="0">.:: Seleccione ::.</option>
              		<?
      				$sql_tipo_presupuesto = mysql_query("select * from tipo_presupuesto");
      					while ($bus_tipo_presupuesto = mysql_fetch_array($sql_tipo_presupuesto)){
      				  ?>
                            <option value="<?=$bus_tipo_presupuesto["idtipo_presupuesto"]?>">
                            		<?=$bus_tipo_presupuesto["denominacion"]?>
                            </option>
                        <?
      				  	}
      				?>
             	</select>
          	</div>
          	<div class="col-md-2">
            	<label for="fuente_financiamiento" class="control-label">Fuente de Financiamiento</label><br>
              	<select class="form-control input-sm" id="fuente_financiamiento" name="fuente_financiamiento">
              		<option value="0">.:: Seleccione ::.</option>
              		<?
      				$sql_fuente_financiamiento = mysql_query("select * from fuente_financiamiento");
      					while ($bus_fuente_financiamiento = mysql_fetch_array($sql_fuente_financiamiento)){
      				  ?>
                            <option value="<?=$bus_fuente_financiamiento["idfuente_financiamiento"]?>">
                            		<?=$bus_fuente_financiamiento["denominacion"]?>
                            </option>
                        <?
      				  	}
      				?>
            	</select>
          	</div>
          	<div class="col-md-5">
            	<label for="categoria_programatica" class="control-label">Categoría Programática</label><br>
              	<select class="form-control input-sm" id="categoria_programatica" name="categoria_programatica">
              		<option value="0">.:: Seleccione ::.</option>
              		<?
      				$sql_categoria_programatica = mysql_query("SELECT 	categoria_programatica.idcategoria_programatica,
      																	categoria_programatica.codigo,
      																	unidad_ejecutora.denominacion,
      																	categoria_programatica.idunidad_ejecutora,
      																	unidad_ejecutora.idunidad_ejecutora
      																FROM categoria_programatica, unidad_ejecutora
      																WHERE categoria_programatica.idunidad_ejecutora = unidad_ejecutora.idunidad_ejecutora
      																ORDER BY categoria_programatica.codigo")or die(mysql_error());
      					while ($bus_categoria_programatica = mysql_fetch_array($sql_categoria_programatica)){
      				  ?>
                            <option value="<?=$bus_categoria_programatica["idcategoria_programatica"]?>">
                            		<?=$bus_categoria_programatica["codigo"]?> - <?=$bus_categoria_programatica["denominacion"]?>
                            </option>
                        <?
      				  	}
      				?>
            	</select>
          	</div>
          	<div class="col-md-2">
            	<label class="texto_busqueda-label" for="inputSmall">Buscar</label>
              	<input type="text" class="form-control input-sm" size="30" placeholder="Ingrese texto a buscar"
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


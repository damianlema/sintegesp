<?php

include('templates/header.php');

?>

<body>
<?php
    include('templates/mensajes.php');
?>
<input type="hidden" id="idTrasladoPresupuestario">
<div class='container-fluid'>
    <div class="panel panel-primary">
        <div class="panel-heading" style="height: 25px; padding: 5px 8px;">
            <h6 class="panel-title" style=" font-size: 12px;">Traslados Presupuestarios</h6>
        </div>
		<form class="form-inline" target="_blank" id="formCabecera">
		  <fieldset>
		    <div class="row">
		    	<div class="form-group col-md-4">
			    	<label for="numeroSolicitud" class="control-label col-md-6" style="font-weight: normal !important;">Número de Solicitud</label>
			    	<input type="text" class="form-control" id="numeroSolicitud" name="numeroSolicitud">
			    </div>
			   	<div class="form-group col-md-3">
			    	<label for="fechaSolicitud" class="control-label col-md-2" style="font-weight: normal !important;">Fecha</label>
	                <input type='text' class="form-control" id='datetimepicker1' name='datetimepicker1'
	                		style="width: 95px;" />
				    <div class="col-md-1" align="right">
				    </div>
			    </div>
			    <div class="form-group col-md-5">
			    	<label for="disminucionesBs" class="control-label col-md-7" style="font-weight: normal !important;"> Total Bs. Disminuciones</label>
			    	<input type="text" class="form-control" id="disminucionesBs" disabled="" placeholder="Total Bs. Disminunidos">
			    </div>
			</div>
			<div class="row">
			    <div class="form-group col-md-4">
			    	<label for="numeroResolucion" class="control-label col-md-6" style="font-weight: normal !important;">Número de Resolución</label>
			    	<input type="text" class="form-control"  id="numeroResolucion">
			    </div>
			    <div class="form-group col-md-3">
			    	<label for="fechaResolucion" class="control-label col-md-2" style="font-weight: normal !important;">Fecha</label>
			    	<input type='text' class="form-control" id='datetimepicker2' style="width: 95px;" />
				    <div class="col-md-1" align="right">
				    </div>
			    </div>
			    <div class="form-group col-md-5">
			    	<label for="aumentosBs" class="control-label col-md-7" style="font-weight: normal !important;">Total Bs. Aumentos</label>
			    	<input type="text" class="form-control" id="aumentosBs" disabled="" placeholder="Total Bs. Aumentos">
			    </div>
			</div>
		    <div class="row">
		    	<div class="col-md-2" align="right">
		      		<label for="concepto" class="control-label" style="font-weight: normal !important;">Concepto</label>
		      	</div>
		      	<div class="form-group col-md-10" align="left">
			        <textarea class="form-control" rows="2" id="concepto" name="concepto"
			         			style="font-size:11px; width: 100%; margin-left: -20px;"></textarea>
		      	</div>
		    </div>
		    <div class="row">
		      	<div class="col-md-1" align="center">
		      	</div>
		      	<div class="col-md-10" align="center">
		      		<table>
		      			<tr>
		      				<td>
					        	<button type="reset" class="btn btn-default btn-xs">Nuevo</button>
					        </td>
					        <td>
					        	<button type="button" class="btn btn-primary btn-xs" id="btnContinuar"
					        			onclick="ingresarDatosBasicos();"
					        			style="display:block;">Continuar</button>
					        </td>
					        <td>
					        	<button type="button" class="btn btn-primary btn-xs" id="btnActualizar"
					        			onclick="actualizarDatosBasicos();"
					        			style="display:none;">Actualizar Encabezado</button>
					        </td>
					    </tr>
			        </table>
		      	</div>
		      	<div class="ajax col-md-1" align="center">
		      		<a 	href="lib/listas/lista_traslados_presupuestarios.php" target="popup"
		      			onClick="window.open(this.href, this.target, 'width=1300,height=650,scrollbars=yes'); return false;">
                    	<button type="button" class="btn btn-default btn-circle" title="Buscar Traslados Presupuestarios">
                    		<i class="glyphicon glyphicon-search"></i>
                    	</button>
					</a>

					<button type="button" class="btn btn-default btn-circle" title="Imprimir Traslados Presupuestarios">
						<i class="glyphicon glyphicon-print"></i>
					</button>

		      	</div>
		    </div>
		  </fieldset>
		</form>
	</div>
</div>
<div class="container-fluid" id="divTablas" style="display: none">
	<ul class="nav nav-tabs" style="margin-top: -14px">
	  <li class="active">
	  	<a href="#disminuir" data-toggle="tab" aria-expanded="true">Disminuir</a>
	  </li>
	  <li class="">
	  	<a href="#aumentar" data-toggle="tab" aria-expanded="true">Aumentar</a>
	  </li>
	</ul>
	<div id="myTabContent" class="tab-content">
	  	<div class="tab-pane fade active in" id="disminuir">
			<div class="row">
				<div class="col-md-12" style="margin-top: 5px;">
					<label for="concepto" class="control-label" style="margin-top: -1px; margin-bottom: -3px;">
						Seleccione la partida presupuestaria a disminuir
					</label>
					<form width="100%" class="form-inline" style="margin-top: 3px">
						<input type="hidden" id="idmaestro_presupuesto_disminuir">
						<fieldset>
							<div class="form-group" style="width: 3%;" align="center">
								<button type="button" class="btn btn-default btn-circle" title="Buscar Partida Presupuestaria a Disminuir">
									<i class="glyphicon glyphicon-search"></i>
								</button>
							</div>
							<div class="form-group" style="width: 15%; margin-right: -2px;">
								<input type="text" class="form-control" placeholder="Fuente Financiamiento"
										style="width: 100%; height: 30px;" disabled id="fuente_financiamiento_disminuir">
							</div>
							<div class="form-group" style="width: 9%; margin-left: -2px; margin-right: -2px;">
								<input type="text" class="form-control" placeholder="Categoria"
										style="width: 100%; height: 30px;" disabled id="categoria_programatica_disminuir">
							</div>
							<div class="form-group" style="width: 12%; margin-left: -2px; margin-right: -2px;">
								<input type="text" class="form-control" placeholder="Partida"
										style="width: 100%; height: 30px;" disabled id="codigo_partida_disminuir">
							</div>
							<div class="form-group" style="width: 45%; margin-left: -2px; margin-right: -2px;">
								<input type="text" class="form-control" placeholder="Descripción"
										style="width: 100%; height: 30px;" disabled id="nombre_partida_disminuir">
							</div>
							<div class="form-group" style="width: 13%; margin-left: -2px; margin-right: -2px;">
								<input type="hidden" id="monto_disminuir_oculto">
								<input type="text" class="form-control" placeholder="Monto Bs."
									style="text-align: right; width: 100%; height: 30px;"	id="monto_disminuir"
									onblur="formatoNumero('monto_disminuir','monto_disminuir_oculto');">
							</div>
							<div class="form-group" style="width: 2%;">
								<button type="button" class="btn btn-default btn-circle" title="Registrar Partida a Disminuir">
									<i class="glyphicon glyphicon-ok"></i>
								</button>
							</div>
						</fieldset>
					</form>
				</div>
			</div>
			<div class="panel panel-primary" style="margin-top: -2px;">
		      	<div class="panel-body">
		        	<div class="col-lg-12">
						<table data-page-length='5' align="center" id="tabla_disminuir" class="table table-striped table=hover display" width="100%">
			              	<thead>
			                	<tr class="info">
			                		<th style="width: 10%; height: 5px; padding: 0px;">
			                			<h6 align="center"><small><strong>Fuente de Financiamiento</strong></small></h6>
			                		</th>
			                		<th style="width: 8%; height: 5px; padding: 0px;">
			                			<h6 align="center"><small><strong>Categoria Programática</strong></small></h6>
			                		</th>
			                  		<th style="width: 15%; height: 5px; padding: 0px;">
			                  			<h6 align="center"><small><strong>Partida Presupuestaria</strong></small></h6>
			                  		</th>
			                  		<th style="width: 45%; height: 5px; padding: 0px;">
			                  			<h6 align="center"><small><strong>Descripción</strong></small></h6>
			                  		</th>
			                  		<th style="width: 15%; height: 5px; padding: 0px;">
			                  			<h6 align="center"><small><strong>Monto Bs.</strong></small></h6>
			                  		</th>
			                  		<th style="width: 7%; height: 5px; padding: 0px;">
			                  			<h6 align="center"><small><strong></strong></small></h6>
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
	  	<div class="tab-pane fade active" id="aumentar">
	    	<div class="row">
				<div class="col-md-12" style="margin-top: 5px;">
					<label for="concepto" class="control-label" style="margin-top: -1px; margin-bottom: -3px;">
						Seleccione la partida presupuestaria a aumentar
					</label>
					<form width="100%" class="form-inline" style="margin-top: 3px">
						<input type="hidden" id="idmaestro_presupuesto_aumentar">
						<fieldset>
							<div class="form-group" style="width: 3%;" align="center">
								<button type="button" class="btn btn-default btn-circle" title="Buscar Partida Presupuestaria a Aumentar">
									<i class="glyphicon glyphicon-search"></i>
								</button>
							</div>
							<div class="form-group" style="width: 15%; margin-right: -2px;">
								<input type="text" class="form-control" placeholder="Fuente Financiamiento"
										style="width: 100%; height: 30px;" disabled id="fuente_financiamiento_aumentar">
							</div>
							<div class="form-group" style="width: 9%; margin-left: -2px; margin-right: -2px;">
								<input type="text" class="form-control" placeholder="Categoria"
										style="width: 100%; height: 30px;" disabled id="categoria_programatica_aumentar">
							</div>
							<div class="form-group" style="width: 12%; margin-left: -2px; margin-right: -2px;">
								<input type="text" class="form-control" placeholder="Partida"
										style="width: 100%; height: 30px;" disabled id="codigo_partida_aumentar">
							</div>
							<div class="form-group" style="width: 45%; margin-left: -2px; margin-right: -2px;">
								<input type="text" class="form-control" placeholder="Descripción"
										style="width: 100%; height: 30px;" disabled id="nombre_partida_aumentar">
							</div>
							<div class="form-group" style="width: 13%; margin-left: -2px; margin-right: -2px;">
								<input type="hidden" id="monto_aumentar_oculto">
								<input type="text" class="form-control" placeholder="Monto Bs."
									style="text-align: right; width: 100%; height: 30px;"	id="monto_aumentar"
									onblur="formatoNumero('monto_aumentar','monto_aumentar_oculto');">
							</div>
							<div class="form-group" style="width: 2%;">
								<button type="button" class="btn btn-default btn-circle" title="Registrar Partida a Aumentar">
									<i class="glyphicon glyphicon-ok"></i>
								</button>
							</div>
						</fieldset>
					</form>
				</div>
			</div>

			<div class="panel panel-primary" style="margin-top: -3px;">
		      	<div class="panel-body">
		        	<div class="col-lg-12">
						<table data-page-length='5' align="center" id="tabla_aumentar" class="table table-striped table=hover display" width="100%">
			              	<thead>
			                	<tr class="info">
			                		<th style="width: 10%; height: 5px; padding: 0px;">
			                			<h6 align="center"><small><strong>Fuente de Financiamiento</strong></small></h6>
			                		</th>
			                		<th style="width: 8%; height: 5px; padding: 0px;">
			                			<h6 align="center"><small><strong>Categoria Programática</strong></small></h6>
			                		</th>
			                  		<th style="width: 15%; height: 5px; padding: 0px;">
			                  			<h6 align="center"><small><strong>Partida Presupuestaria</strong></small></h6>
			                  		</th>
			                  		<th style="width: 45%; height: 5px; padding: 0px;">
			                  			<h6 align="center"><small><strong>Descripción</strong></small></h6>
			                  		</th>
			                  		<th style="width: 15%; height: 5px; padding: 0px;">
			                  			<h6 align="center"><small><strong>Monto Bs.</strong></small></h6>
			                  		</th>
			                  		<th style="width: 7%; height: 5px; padding: 0px;">
			                  			<h6 align="center"><small><strong></strong></small></h6>
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
	</div>
</div>

<?php

include('templates/footer.php');

?>

<script src="modulos/presupuesto/js/trasladoPresupuesto.Ajax.js" type="text/javascript" language="javascript"></script>

<script type="text/javascript">
  TablaPaginada('tabla_disminuir');
  TablaPaginada('tabla_aumentar');
</script>

<script type="text/javascript">
    $(function () {
        $('#datetimepicker1').datetimepicker({
        	viewMode: 'days',
            format: 'DD-MM-YYYY',
            locale: 'es'
        })
    });
    $(function () {
        $('#datetimepicker2').datetimepicker({
        	viewMode: 'days',
            format: 'DD-MM-YYYY',
            locale: 'es'
        });
    });


    $(document).ready(function() {
        $('#formCabecera').bootstrapValidator({
			icon: {
	            valid: 'glyphicon glyphicon-ok',
	            invalid: 'glyphicon glyphicon-remove',
	            validating: 'glyphicon glyphicon-refresh'
	        },

			 fields: {
				 numeroSolicitud: {
					 validators: {
						 notEmpty: {
							 message: 'El Numero de Solicitud es requerido'
						 }
					 }
				 },

				 datetimepicker1: {
					 validators: {
						 notEmpty: {
							message: 'La Fecha de Solicitud es requerida'
						 },
					 }
				 },

				  concepto: {
					 validators: {
						 notEmpty: {
							 message: 'El Concepto es requerido'
						 }
					 }
				 }
			}
		});
	});
</script>

</body>
<br>
<h4 align=center>Nuevo A&ntilde;o de Trabajo</h4>
<h2 class="sqlmVersion"></h2>
<br>
<script src="js/jquery.js"></script>




<table width="30%" border="0" align="center">
  <tr>
    <td>A&ntilde;o a crear:</td>
    <td><select name="anio" id="anio">
      <?
    $inicio = (date("Y"));
	for(; $inicio<=2100;$inicio++){
		?>
      <option value="<?=$inicio?>">
        <?=$inicio?>
        </option>
      <?	
	}
	?>
    </select></td>
  </tr>
  <tr>
    <td>Fecha de Cierre</td>
    <td><input name="fecha_cierre" type="text" id="fecha_cierre" size="12" readonly="readonly" />
      <img src="imagenes/jscalendar0.gif" name="f_trigger_f" width="16" height="16" id="f_trigger_f" style="cursor: pointer;" title="Selector de Fecha" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" />
      <script type="text/javascript">
                                Calendar.setup({
                                inputField    : "fecha_cierre",
                                button        : "f_trigger_f",
                                align         : "Tr",
                                ifFormat      : "%Y-%m-%d"
                                });
                            </script>
      
      </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input type="submit" name="boton_ingresar" id="boton_ingresar" value="Crear" class="button">    </td>
  </tr>
</table>
<br />
<br />
<center><div id="divResultado" style="border:#000 solid 0px; width:0px; background-color:#CCC; vertical-align:middle" align="center">&nbsp;</div></center>


<script>

$("#boton_ingresar").click(function(){
var anio_a_cambiar= document.getElementById("anio").value;
var fecha_cierre= document.getElementById("fecha_cierre").value;
	if(confirm("Realmente desea generar el Periodo seleccionado?")){
				
				$("#divResultado").animate({ 
					width: "0%",
				 },1);
				$("#divResultado").animate({ 
					height: "0px",
					verticalAlign: "middle",
				 },1);
				$("#divResultado").animate({ 
					//width: "0%",
					width: "80%",
					opacity: 0.9,
					//marginLeft: "0.6in",
					fontSize: "12px", 
					borderWidth: "1px",
					//this.innerHTML = 'prueba';
				 },500);
				$("#divResultado").animate({ 
					height: "100px",
					verticalAlign: "middle",
				 },500);
					

				$.ajax({
					url: "servidor/lib/anio_trabajo_ajax.php",
					data: "anio_a_cambiar="+anio_a_cambiar+"&fecha_cierre="+fecha_cierre,
					async:true,
					beforeSend: function(objeto){
						document.getElementById("divResultado").innerHTML = "<br><img src='imagenes/cargando.gif' width = '16' height = '16'> Por favor espere, este proceso puede tardar <b>VARIOS MINUTOS!</b><br><br>Por favor no ejecute ninguna otra accion en el sistema hasta que el proceso culmine<br><br>";
					},
					/*complete: function(objeto, exito){
						alert("Me acabo de completar")
						if(exito=="success"){
							alert("Y con éxito");
						}
					},*/
					/*contentType: "application/x-www-form-urlencoded",
					dataType: "html",*/
					error: function(objeto, quepaso, otroobj){
						document.getElementById("divResultado").innerHTML = "Disculpe ha ocurrido un Error y no se ha podido realizar la operacion";
						document.getElementById("divResultado").innerHTML = "Motivo del Error: "+quepaso;
					},
					/*global: true,
					ifModified: false,
					processData:true,
					*/
					success: function(datos){
						document.getElementById("divResultado").innerHTML = datos;
					},
					//timeout: 3000,  // ESTO DICE CUANTO TIEMPO MAXIMO DEBE EJECUTARSE LA FUNCION
					type: "GET"
				});
		}
		});

		
</script>

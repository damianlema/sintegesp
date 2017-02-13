<style>
.menu{
color:#000000;
text-decoration:none;
}

.menu:hover{
color:#FFF;
background-color:#CCC;
text-decoration:underline;
cursor:pointer;
}

.link{
color:#0066CC;
text-decoration:none;
}

.link:hover{
color:#666666;
font-weight:bold;
text-decoration:underline;
cursor:pointer;
}

.button{
background-color:#0066CC;
color:#fff;
font-weight:bold;
font-family:Arial, Helvetica, sans-serif;
font-size:11px;
border:2px solid #FFFFFF;
margin:2px;
}


.button:hover{
background-color:#666666;
color:#FFF;
cursor:pointer;
}



#cuadroMensajes{
	position:fixed;
	top: 0px;
	width:100%;
	text-align:center;
	margin-top:0px;
}




</style>

<script>
function verificarNuevosCorreos(){
//setInterval("consultarRecibidos()", 30000);
}


function seleccionarTodos(){
	
	var form = document.getElementById('formulario_listaCorreos');
	for(i=0; i<form.length; i++){
	
		if(form.elements[i].type == "checkbox"){
			if(document.getElementById('check_seleccionar_todos').checked == true){
				form.elements[i].checked = true;
			}else{
				form.elements[i].checked = false;
			}
		}
	}
	
}
</script>
<script src="../../js/function.js"></script>
<script src="js/funcionesBandeja_ajax.js"></script>

<body onLoad="consultarRecibidos(), verificarNuevosCorreos()">
<div id="cuadroMensajes" style="display:none"></div>
<div style="font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#000000; font-weight:bold">&nbsp;&nbsp;&nbsp;SINTEGESP MAIL</div><br>

<table width="95%"  align="center" style="border:#999999 solid 3px; font-family:Arial, Helvetica, sans-serif;" cellpadding="0" cellspacing="0">
<tr>
    <td width="15%" valign="top" style="border-right:#999999 solid 2px; background-color:#EAEAEA">
    
        <!-- MENU LATERAL -->
        <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#EAEAEA">
		  
          <tr>
  	        <td class="menu" style="padding:5px">
            <table width="100%" cellpadding="0" cellspacing="0" onClick="mostrarEnviar()">
           	  <tr>
                <td align="center"><img src="img/redactar.png" width="14" height="16">
               	<td style="font-size:12px" class="link">Redactar</td>
                </tr>
            </table>
            </td>
          </tr>
          
          
          
          <tr>
  	        <td class="menu" style="padding:5px">
            <table width="100%" cellpadding="0" cellspacing="0" onClick="consultarRecibidos()">
           	  <tr>
                <td align="center"><img src="img/entrada.png" width="14" height="14">
               	<td style="font-size:12px" class="link">&nbsp;Recibidos</td>
                </tr>
            </table>
            </td>
          </tr>
          
          
           <tr>
  	        <td class="menu" style="padding:5px">
            <table width="100%" cellpadding="0" cellspacing="0" onClick="consultarEnviados()">
           	  <tr>
                <td align="center"><img src="img/enviados.png" width="14" height="14">
               	<td style="font-size:12px" class="link">Enviados</td>
                </tr>
            </table>
            </td>
          </tr>
            
            
            
            <tr>
  	        <td class="menu" style="padding:5px">
            <table width="100%" cellpadding="0" cellspacing="0" onClick="consultarPapelera()">
           	  <tr>
                <td align="center"><img src="img/papelera.png" width="14" height="15">
               	<td style="font-size:12px" class="link">Papelera</td>
                </tr>
            </table>
            </td>
          </tr>
        </table>
      <!-- MENU LATERAL -->
    
    </td>

<td width="85%">

<!-- CENTRO -->
    <table width="100%" cellpadding="0" cellspacing="0">
    <tr>
            <td>
            <!-- MENU SUPERIOR -->
            	<table width="100%" cellpadding="0" cellspacing="0">
   	  <tr>
                    	<td colspan="2" style="border-bottom:#999999 solid 1px; padding:5px">
                        	<table cellpadding="0" cellspacing="0">
                            	<tr>
                                <td>
                                    <table>
                                    <tr>
                                    <td><img src="img/buscar.png" width="14" height="16"></td>
                                    <td style="font-size:12px">Buscar:</td>
                                    </tr>
                                    </table>
                                </td> 
                                <td><input type="text" name="campoBuscar" id="campoBuscar" size="40"></td>
                                <td><input type="button" name="boton_buscar_mensajes" id="boton_buscar_mensajes" value="Buscar" class="button" onClick="buscarMensajes()"></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    </table>
                    <table id="cuadro_eliminar" cellspacing="0" cellpadding="0" width="100%">
                    <tr>
                    	<td width="3%" style="border-bottom:#999999 solid 1px; background-color:#0066FF">
                        <input type="checkbox" name="check_seleccionar_todos" id="check_seleccionar_todos" onClick="seleccionarTodos()">
                        </td>
                      	<td width="97%" style="border-bottom:#999999 solid 1px; font-size:12px; background-color:#0066FF"><div class="link" style="color:#FFFFFF" onClick="eliminarMensajes()">Eliminar</div></td>
                  </tr>
                  </table>
                
            <!-- MENU SUPERIOR -->
            </td>
        </tr>
        <tr>
        <td>
        
        <!-- MENSAJES -->
        <div id="cuadro_mensajes" style="width:100%; height:400px; overflow:auto"></div>
        <!-- MENSAJES -->
        
        </td>
        </tr>
        <tr>
        	<td>
            		
                 <!-- MENU INFERIROR -->   
                <table id="cuadro_eliminar_inferior" cellspacing="0" cellpadding="0" width="100%">
                <tr>
                    <td width="3%" style="border-top:#999999 solid 1px; background-color:#0066FF"></td>
                  <td width="97%" style="border-top:#999999 solid 1px; font-size:12px; background-color:#0066FF;" ></td>
              </tr>
              </table>
                <table width="100%" cellpadding="0" cellspacing="0" id="cuadro_paginacion">
              <tr>
                  <td colspan="2" align="right" style="border-top:#999999 solid 1px; font-size:12px"></td>
              </tr>
            </table>
            <!-- MENU INFERIROR -->
            
                    
            </td>
        </tr>
    </table>
<!-- CENTRO -->


</td>
</tr>
</table>
</body>
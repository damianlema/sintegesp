<?
session_start();
include("../../conf/conex.php");
Conectarse();
?>
<link href="../../css/theme/green/main.css" rel="stylesheet" type="text/css">
<script src="../../js/function.js" type="text/javascript" language="javascript"></script>
<script src="listado_ajax.js"></script>
<script type="text/javascript">



var listadoOrganizacion=new Array();
listadoOrganizacion[0]="organizacion";
listadoOrganizacion[1]="nivel_organizacion";

function nuevoAjax() { 
	var xmlhttp=false;
	try{
		xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");
	}
	catch(e){
		try{
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		catch(E){
			if (!xmlhttp && typeof XMLHttpRequest!='undefined') xmlhttp=new XMLHttpRequest();
		}
	}
	return xmlhttp; 
}

function buscarEnArray(array, dato) {
	// Retorna el indice de la posicion donde se encuentra el elemento en el array o null si no se encuentra
	var x=0;
	while(array[x])
	{
		if(array[x]==dato) return x;
		x++;
	}
	return null;
}

function cargaContenido(idSelectOrigen, listado) {
	// Obtengo la posicion que ocupa el select que debe ser cargado en el array declarado mas arriba
	if (listado=="listadoSNC") var posicionSelectDestino=buscarEnArray(listadoSNC, idSelectOrigen)+1;
	else if (listado=="listadoMovimientos") var posicionSelectDestino=buscarEnArray(listadoMovimientos, idSelectOrigen)+1;
	else if (listado=="listadoCuentas") var posicionSelectDestino=buscarEnArray(listadoCuentas, idSelectOrigen)+1;
	else if (listado=="listadoCatalogo") var posicionSelectDestino=buscarEnArray(listadoCatalogo, idSelectOrigen)+1;
	else if (listado=="listadoOrganizacion") var posicionSelectDestino=buscarEnArray(listadoOrganizacion, idSelectOrigen)+1;
	
	// Obtengo el select que el usuario modifico
	var selectOrigen=document.getElementById(idSelectOrigen);
	// Obtengo la opcion que el usuario selecciono
	var opcionSeleccionada=selectOrigen.options[selectOrigen.selectedIndex].value;
	// Si el usuario eligio la opcion "Elige", no voy al servidor y pongo los selects siguientes en estado "Selecciona opcion..."
	if(opcionSeleccionada==0)
	{
		var x=posicionSelectDestino, selectActual=null;
		
		if (listado=="listadoSNC") var num = listadoSNC.length;
		else if (listado=="listadoMovimientos") var num = listadoMovimientos.length;
		else if (listado=="listadoCuentas") var num = listadoCuentas.length;
		else if (listado=="listadoCatalogo") var num = listadoCatalogo.length;
		else if (listado=="listadoOrganizacion") var num = listadoOrganizacion.length;
		
		// Busco todos los selects siguientes al que inicio el evento onChange y les cambio el estado y deshabilito		
		while(x <= num-1)
		{
			if (listado=="listadoSNC") selectActual=document.getElementById(listadoSNC[x]);
			else if (listado=="listadoMovimientos") selectActual=document.getElementById(listadoMovimientos[x]);
			else if (listado=="listadoCuentas") selectActual=document.getElementById(listadoCuentas[x]);
			else if (listado=="listadoCatalogo") selectActual=document.getElementById(listadoCatalogo[x]);
			else if (listado=="listadoOrganizacion") selectActual=document.getElementById(listadoOrganizacion[x]);
			selectActual.length=0;
			
			var nuevaOpcion=document.createElement("option"); 
			nuevaOpcion.value=0; 
			nuevaOpcion.innerHTML="Selecciona Opción...";
			selectActual.appendChild(nuevaOpcion);	
			selectActual.disabled=true;
			x++;
		}
	}
	// Compruebo que el select modificado no sea el ultimo de la cadena
	else if((listado=="listadoSNC" && idSelectOrigen!=listadoSNC[listadoSNC.length-1]) || (listado=="listadoMovimientos" && idSelectOrigen!=listadoMovimientos[listadoMovimientos.length-1]) || (listado=="listadoCuentas" && idSelectOrigen!=listadoCuentas[listadoCuentas.length-1]) || (listado=="listadoCatalogo" && idSelectOrigen!=listadoCatalogo[listadoCatalogo.length-1]) || (listado=="listadoOrganizacion" && idSelectOrigen!=listadoOrganizacion[listadoOrganizacion.length-1]))
	{
		// Obtengo el elemento del select que debo cargar
		if (listado=="listadoSNC") var idSelectDestino=listadoSNC[posicionSelectDestino];
		else if (listado=="listadoMovimientos") var idSelectDestino=listadoMovimientos[posicionSelectDestino];
		else if (listado=="listadoCuentas") var idSelectDestino=listadoCuentas[posicionSelectDestino];
		else if (listado=="listadoCatalogo") var idSelectDestino=listadoCatalogo[posicionSelectDestino];
		else if (listado=="listadoOrganizacion") var idSelectDestino=listadoOrganizacion[posicionSelectDestino];
		var selectDestino='nivel_organizacion';
		var idSelectDestino=selectDestino;
		alert(selectDestino);
		// Creo el nuevo objeto AJAX y envio al servidor el ID del select a cargar y la opcion seleccionada del select origen
		var ajax=nuevoAjax();
		ajax.open("GET", "../../modulos/tablas_comunes/lib/select_dependientes_proceso.php?select="+idSelectDestino+"&opcion="+opcionSeleccionada+"&listado="+listado, true);
		ajax.onreadystatechange=function() 
		{ 
			if (ajax.readyState==1)
			{
				// Mientras carga elimino la opcion "Selecciona Opcion..." y pongo una que dice "Cargando..."
				selectDestino.length=0;
				var nuevaOpcion=document.createElement("option"); nuevaOpcion.value=0; nuevaOpcion.innerHTML="Cargando...";
				selectDestino.appendChild(nuevaOpcion); selectDestino.disabled=true;	
			}
			if (ajax.readyState==4)
			{
				selectDestino.parentNode.innerHTML=ajax.responseText;
				//document.getElementById("selectActividad").innerHTML=ajax.responseText;
				//selectDestino.disabled = false;
			} 
		}
		ajax.send(null);
	}
}

	</script>
	<br>
	<h4 align=center>Listado de Bienes Muebles</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
<form method="post" action="">
<table align="center">
 	<tr>
                <td align="right">Organizaci&oacute;n: </td>
                <td>
                    <select name="organizacion" id="organizacion" style="width:450px;">
                        <option value="0">Elige</option>
                        <?
                        $query = mysql_query("SELECT * FROM organizacion") or die ($sql.mysql_error());
                        while ($field = mysql_fetch_array($query)) {
                            ?> <option value="<?=$field['idorganizacion']?>" onClick="seleccionarNivel('<?=$field["idorganizacion"]?>')"><?=$field['denominacion']?></option> <?
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">Nivel Organizacional: </td>
                <td>
                    <select name="nivel_organizacion" id="nivel_organizacion" style="width:450px;">
                        <option value="0">Selecciona Opci&oacute;n...</option>                                    
                    </select>
                </td>
            </tr>
  	<tr>
  
    	<td >C&oacute;digo del Bien / C&oacute;digo Anterior / Especificaciones:</td>
        <td><input type="text" id="codigo_bien" name="codigo_bien" size="50"></td>
        
    </tr>
    <tr>
  		<td align="right">Ordenar por:</td>
        <td align="left"><input type="radio" name="tipo" id="codigo" value="codigo" checked /> C&oacute;digo
                <input type="radio" name="tipo" id="especificaciones" value="especificaciones" /> Especificaciones</td>
    </tr>
    <tr>
  
        <td colspan="2" align="center"><input type="submit" id="buscar" name="buscar" value="Buscar" class="button"></td>
    </tr>
</table>     
</form>

<?

if($_POST){
	$buscar='';
	if ($_POST["organizacion"] != '0'){ $buscar = " and idorganizacion = '".$_POST["organizacion"]."' ";}
	if ($_POST["nivel_organizacion"] != '0' and $_POST["nivel_organizacion"] != ''){ $buscar = $buscar." and idnivel_organizacion = '".$_POST["nivel_organizacion"]."' ";}
	if ($_POST["tipo"] == 'codigo'){ $ordenar = " ORDER BY muebles.codigo_bien"; }
	if ($_POST["tipo"] == 'especificaciones'){ $ordenar = " ORDER BY muebles.especificaciones"; }
	
	/*echo $buscar;
	echo " ORGA ".$_POST["organizacion"];
	echo " NIVEL ".$_POST["nivel_organizacion"];*/
$sql_consulta = mysql_query("select detalle_catalogo_bienes.codigo,
									detalle_catalogo_bienes.denominacion,
									muebles.codigo_bien,
									muebles.estado,
									muebles.especificaciones,
									muebles.idmuebles,
									muebles.idorganizacion,
									muebles.idnivel_organizacion
										from 
									detalle_catalogo_bienes, muebles
								where
									detalle_catalogo_bienes.iddetalle_catalogo_bienes = muebles.idcatalogo_bienes
									and (muebles.codigo_bien like '%".$_POST["codigo_bien"]."%'
											or
										muebles.codigo_anterior_bien like '%".$_POST["codigo_bien"]."%'
											or
										muebles.especificaciones like '%".$_POST["codigo_bien"]."%')
									".$buscar.$ordenar)or die(mysql_error());
									

?>

<table width="100%" align="center" cellpadding="0" cellspacing="0" class="Main">
<tr>
					<td align="center">
                    
                    
						<table width="95%" border="0" align=center cellpadding="0" cellspacing="0" class="Browse">
			  				<thead>
								<tr>
									<td width="12%" align="center" class="Browse">C&oacute;digo</td>
									<td width="76%" align="center" class="Browse">Especificaciones</td>
                                  <td align="center" class="Browse">Acci&oacute;n</td>
								</tr>
							</thead>
							<?
                            while($bus_consulta = mysql_fetch_array($sql_consulta)){
							
							if($_REQUEST["destino"] == "desincorporacion"){
							?>
							<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onclick="opener.document.getElementById('nombre_mueble').value = '<?=$bus_consulta["denominacion"]?>', opener.document.getElementById('codigo_mueble').value = '<?=$bus_consulta["codigo_bien"]?>', opener.document.getElementById('idmueble').value = '<?=$bus_consulta["idmuebles"]?>', window.close()">
                            <td class='Browse'><?=$bus_consulta["codigo_bien"]?></td>
                            <td class='Browse' align="left"><?=$bus_consulta["especificaciones"]?></td>
                            <td width="6%" align="center" class='Browse'>
                                <img src="../../imagenes/validar.png" 
                                style="cursor:pointer"
                                onclick="opener.document.getElementById('nombre_mueble').value = '<?=$bus_consulta["denominacion"]?>', opener.document.getElementById('idmueble').value = '<?=$bus_consulta["codigo_bien"]?>', opener.document.getElementById('idmueble').value = '<?=$bus_consulta["idmuebles"]?>', window.close()">                                
                            </td>
                          </tr>
							<?
							}else{
								if ($bus_consulta["estado"] == 'activo'){
							?>
									<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onclick="opener.consultarMueble('<?=$bus_consulta["idmuebles"]?>'), window.close()">
                            <?
								}else{
							?>
                            		<tr bgcolor='#FF0000' onMouseOver="setRowColor(this, 0, 'over', '#FF0000', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FF0000', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onclick="opener.consultarMueble('<?=$bus_consulta["idmuebles"]?>'), window.close()">
                            <? } ?>
                            <td class='Browse'><?=$bus_consulta["codigo_bien"]?></td>
                            <td class='Browse' align="left"><?=$bus_consulta["especificaciones"]?></td>
                            <td width="6%" align="center" class='Browse'>
                                <img src="../../imagenes/validar.png" 
                                style="cursor:pointer"
                                onclick="opener.consultarMueble('<?=$bus_consulta["idmuebles"]?>'), window.close()">                                
                            </td>
                          </tr>
							<?
							}
							}
							?>
						</table>
                        
					
      </td>
    </tr>
  </table>
  <?
  }
  ?>
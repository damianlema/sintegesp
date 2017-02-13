<?
extract($_POST);
include("../../../conf/conex.php");
$conexion_db = Conectarse();

if($ejecutar== "ingresar"){
	//echo "Ingresar";
	if($codigo == "" || $descripcion=="" || $desde=="" || $hasta==""){
		//echo "Campos Vacios";
		$accion = "0|.|0";
	}else{
		$str_sql = "insert into tabla_constantes_recaudacion(codigo,
												 descripcion,
												 desde,
												 hasta,
												 unidad,
												 usuario,
												 fechayhora,
												 status) values(
												 '".$codigo."',
												 '".$descripcion."',
												 '".$desde."',
												 '".$hasta."',
												 '".$unidad."',
												 '".$login."',
												 '".$fh."',
												 'a');";
		
		$result = mysql_query($str_sql);
		
		if(!$result){
			//echo "Fallo Registro";
			$accion = "1|.|0";
		}else{
			//echo "Registro Exitoso";
			$accion = "2|.|".mysql_insert_id();;
		}
	}
	echo $accion;
}




if($ejecutar == "consultarRango"){
	?>
    <h4 align="center">Rango</h4>
    <p align="center">&nbsp;</p>
    
     <input type="hidden" name="idrango" id="idrango">
     <table width="30%" border="0" align="center">
      <tr>
        <td width="44" align="right" class="viewPropTitle">Desde:</td>
        <td width="103">
        <input type="text" name="desdeRango" id="desdeRango" size="13" /></td>
        <td width="38" align="right" class="viewPropTitle">Hasta:</td>
        <td width="100"><input type="text" name="hastaRango" id="hastaRango" size="13" /></td>
        <td width="42" align="right" class="viewPropTitle">Valor:</td>
        <td width="90"><input name="valor" type="text" id="valor" style="text-align:right" size="15" autocomplete="OFF"/></td>
        <td width="22">
        <input type="image" src="imagenes/accept.gif" width="16" height="16" id="boton_ingresar_rango" style="display:block" onClick="guardarRango( document.getElementById('desdeRango').value,document.getElementById('hastaRango').value,document.getElementById('valor').value)"/>
        <input type="image" src="imagenes/modificar.png" width="16" height="16" id="boton_modificar_rango" style="display:none" onClick="modificarRango()"/>
        </td>
      </tr>
    </table>
    
    
    
    
	<br />
<br />
		<table width="40%" align="center" cellpadding="0" cellspacing="0" class="Browse" >
        <thead>
            <tr>
                <td width="10%" align="center" class="Browse">N&ordm;</td>
                <td width="23%" align="center" class="Browse">Desde</td>
                <td width="25%" align="center" class="Browse">Hasta</td>
                <td width="26%" align="center" class="Browse">Valor</td>
                <td width="16%" align="center" class="Browse" colspan="2">Accion</td>
            </tr>
        </thead>
        <?
        $sql_consulta = mysql_query("select * from rango_tabla_constantes_recaudacion where idtabla_constantes = '".$idtabla_constantes."' order by desde, hasta");
		$n = 0;
		while($bus_consulta = mysql_fetch_array($sql_consulta)){
		$n++;
		?>
		<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
       		<td align="center" class='Browse'><?=$n?></td>
            <td align="center" class='Browse'><?=$bus_consulta["desde"]?></td>
            <td align="center" class='Browse'><?=$bus_consulta["hasta"]?></td>
            <td align="right" class='Browse'><?=number_format($bus_consulta["valor"],2,",",".")?></td>
            <td align="center" class='Browse'><img src="imagenes/delete.png" onClick="eliminarRango('<?=$bus_consulta["idrango_tabla_constantes"]?>')" style="cursor:pointer"></td>
            <td align="center" class='Browse'><img src="imagenes/modificar.png" onClick="seleccionarModificarRango('<?=$bus_consulta["idrango_tabla_constantes"]?>', '<?=$bus_consulta["desde"]?>', '<?=$bus_consulta["hasta"]?>', '<?=$bus_consulta["valor"]?>')" style="cursor:pointer"></td>
		</tr>
        <?
		}
		?>
        </table>
	
	<?
	}
	
if($ejecutar == "guardarRango"){
		//echo $idtabla;
		
		$sql_consulta = mysql_Query("select * from rango_tabla_constantes_recaudacion where (desde = '".$desdeRango."' or hasta = '".$hastaRango."') and idtabla_constantes = '".$idtabla_constantes."'");
		$num_consulta = mysql_num_rows($sql_consulta);
		if($num_consulta == 0){
		
		if($desdeRango == "" || $hastaRango == "" || $valor == ""){
			$accion = 0;
			echo $accion;
			}else{
		
		
				$str_guardar_rango = "insert into rango_tabla_constantes_recaudacion (
																		  idtabla_constantes,
																		  desde,
																		  hasta,
																		  valor)values(
																		  '".$idtabla_constantes."',
																		  '".$desdeRango."',
																		  '".$hastaRango."',
																		  '".$valor."')";
				
				$result_agregar_rango = mysql_query($str_guardar_rango,$conexion_db);
				
				if(!$result_agregar_rango){
					//echo "Fallo Registro";
					$accion = 1;
					echo $accion;
					$error = mysql_error();
					echo $error;
				}else{
					//echo "Registro Exitoso";
					$accion = 2;
					echo $accion;
				}
			}
			
			
		}else{
			echo "desde_duplicado";	
		}
			
	}
	
	
	
	
	
	
	
	if($ejecutar == "eliminarRango"){
		$sql_eliminar = mysql_query("delete from rango_tabla_constantes_recaudacion where idrango_tabla_constantes = '".$idrango_tabla_constantes."'");
	}
	
	
	
	
	
	
	
	
	if($ejecutar == "consultarTablaConstantes"){
		$sql_consultar = mysql_query("select * from tabla_constantes_recaudacion where idtabla_constantes = '".$idtabla_constantes."'");
		$bus_consultar = mysql_fetch_array($sql_consultar);
		
		echo $bus_consultar["codigo"]."|.|".
		$bus_consultar["descripcion"]."|.|".
		$bus_consultar["desde"]."|.|".
		$bus_consultar["hasta"]."|.|".
		$bus_consultar["unidad"];
			
	}
	
	
	
	
	
	
	if($ejecutar== "modificarTablaConstantes"){
	//echo "Ingresar";
	if($codigo == "" || $descripcion=="" || $desde=="" || $hasta==""){
		//echo "Campos Vacios";
		$accion = 0;
	}else{
		$str_sql = "update tabla_constantes_recaudacion set codigo = '".$codigo."',
												 descripcion = '".$descripcion."',
												 desde = '".$desde."',
												 hasta = '".$hasta."',
												 unidad = '".$unidad."'
												 where idtabla_constantes = '".$idtabla_constantes."';";
		
		$result = mysql_query($str_sql);
		
		if(!$result){
			//echo "Fallo Registro";
			$accion = 1;
		}else{
			//echo "Registro Exitoso";
			$accion = 2;
		}
	}
	echo $accion;
}





	if($ejecutar == "eliminarTablaConstantes"){
		$sql_eliminar = mysql_query("delete from tabla_constantes_recaudacion where idtabla_constantes = '".$idtabla_constantes."'");
		$sql_eliminar_rango = mysql_query("delete from rango_tabla_constantes_recaudacion where idtabla_constantes = '".$idtabla_constantes."'");
		
	}
	
	
	
	
	
	
	
	
	if($ejecutar == "modificarRango"){
		$sql_actualizar = mysql_query("update rango_tabla_constantes_recaudacion set desde = '".$desde."',
									  									hasta = '".$hasta."',
																		valor = '".$valor."'
																		where idrango_tabla_constantes ='".$id."'");	
	}
	
	
	
	
	
?>
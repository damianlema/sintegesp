<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
Conectarse();

extract($_POST);

//*******************************************************************************************************************************************
//********************************************* PASAR USUARIO DE UN SELECT AL OTRO ************************************
//*******************************************************************************************************************************************

if($ejecutar == "pasarUsuario"){
	$sql_pasar_usuario = mysql_query("insert into usuarios_fuente_financiamiento (idfuente_financiamiento,cedula) 
																	values
																		('".$idfuente."','".$cedula_usuario_seleccionado."')")or die(mysql_error()); 
	registra_transaccion('Asignar Usuario('.$cedula_usuario_seleccionado.') a Fuente de Financiamiento ('.$idfuente.')',$login,$fh,$pc,'categoria_programatica',$conexion_db);										?>
                                                                           
    <table align="center" width="50%">
    	<tr>
    		<td align="center" class="viewPropTitle" width="45%"><strong>Usuarios</strong></td>
            <td align="center" class="viewPropTitle" width="5%">&nbsp;</td>
            <td align="center" class="viewPropTitle" width="45%"><strong>Usuarios Asignados</strong></td>
    	</tr>
        <tr>
        	<td align="center">
            	<? 	$sql_usuarios = mysql_query("select usuarios.cedula, usuarios.apellidos, usuarios.nombres from usuarios
														where usuarios.status = 'a'
															and usuarios.cedula Not in 
														(select cedula from usuarios_fuente_financiamiento where idfuente_financiamiento = '".$idfuente."')
																				 order by nombres, apellidos")or die(mysql_error()); 
				?>
					<select name="usuarios_activos" id="usuarios_activos" multiple size="6">
                    	<? while ($bus_usuarios = mysql_fetch_array($sql_usuarios)){ ?>
	                    	<option value="<?=$bus_usuarios["cedula"];?>"><? echo $bus_usuarios["nombres"]." ".$bus_usuarios["apellidos"];?></option>
                    	<? } ?>
                    </select>
            </td>
            <td align="center" >
            	<img style="display:block; cursor:pointer"
                                        src="imagenes/fast_forward.png" 
                                        title="Asignar Usuario a Categoria" 
                                        id="botonPasarUsuario" 
                                        name="botonPasarUsuario" 
                                        onclick="pasarUsuario()"/>
                  <br>
                  <img style="display:block; cursor:pointer"
                                        src="imagenes/rewind.png" 
                                        title="Quitar Usuario de Categoria" 
                                        id="botonRegresarUsuario" 
                                        name="botonRegresarUsuario"
                                        onclick="regresarUsuario()"/>
            </td>
            <td align="center">
	            <? 		$sql_usuarios_asignados = mysql_query("select * from usuarios_fuente_financiamiento, usuarios, privilegios_modulo 
																				where usuarios_fuente_financiamiento.cedula = usuarios.cedula
																			and usuarios_fuente_financiamiento.idfuente_financiamiento = '".$idfuente."'
																				 group by usuarios_fuente_financiamiento.cedula
																				 order by nombres, apellidos"); ?>
            	<select name="usuarios_asignados" id="usuarios_asignados" multiple size="6">
                	<? while ($bus_usuarios_asignados = mysql_fetch_array($sql_usuarios_asignados)){ ?>
	                    	<option value="<?=$bus_usuarios_asignados["cedula"];?>"><? echo $bus_usuarios_asignados["nombres"]." ".$bus_usuarios_asignados["apellidos"];?></option>
                    	<? } ?>
                </select>
            </td>
        </tr>
    </table>
    
    

	
<? }

if($ejecutar == "regresarUsuario"){
	$sql_pasar_usuario = mysql_query("delete from usuarios_fuente_financiamiento where idfuente_financiamiento = ".$idfuente." 
																		and cedula = ".$cedula_usuario_seleccionado."")or die(mysql_error()); 
	registra_transaccion('Eliminar Usuario Asignado('.$cedula_usuario_seleccionado.') a Fuente de Financiamiento ('.$idfuente.')',$login,$fh,$pc,'fuente_financiamiento',$conexion_db);											?>
                                                                           
    <table align="center" width="50%">
    	<tr>
    		<td align="center" class="viewPropTitle" width="45%"><strong>Usuarios</strong></td>
            <td align="center" class="viewPropTitle" width="5%">&nbsp;</td>
            <td align="center" class="viewPropTitle" width="45%"><strong>Usuarios Asignados</strong></td>
    	</tr>
        <tr>
        	<td align="center">
            	<? 	$sql_usuarios = mysql_query("select usuarios.cedula, usuarios.apellidos, usuarios.nombres from usuarios
														where usuarios.status = 'a'
															and usuarios.cedula Not in 
														(select cedula from usuarios_fuente_financiamiento where idfuente_financiamiento = '".$idfuente."')
																				 order by nombres, apellidos")or die(mysql_error()); 
				?>
					<select name="usuarios_activos" id="usuarios_activos" multiple size="6">
                    	<? while ($bus_usuarios = mysql_fetch_array($sql_usuarios)){ ?>
	                    	<option value="<?=$bus_usuarios["cedula"];?>"><? echo $bus_usuarios["nombres"]." ".$bus_usuarios["apellidos"];?></option>
                    	<? } ?>
                    </select>
            </td>
            <td align="center" >
            	<img style="display:block; cursor:pointer"
                                        src="imagenes/fast_forward.png" 
                                        title="Asignar Usuario a Categoria" 
                                        id="botonPasarUsuario" 
                                        name="botonPasarUsuario" 
                                        onclick="pasarUsuario()"/>
                  <br>
                  <img style="display:block; cursor:pointer"
                                        src="imagenes/rewind.png" 
                                        title="Quitar Usuario de Categoria" 
                                        id="botonRegresarUsuario" 
                                        name="botonRegresarUsuario"
                                        onclick="regresarUsuario()"/>
            </td>
            <td align="center">
	            <? 		$sql_usuarios_asignados = mysql_query("select * from usuarios_fuente_financiamiento, usuarios, privilegios_modulo 
																				where usuarios_fuente_financiamiento.cedula = usuarios.cedula
																			and usuarios_fuente_financiamiento.idfuente_financiamiento = '".$idfuente."'
																				 group by usuarios_fuente_financiamiento.cedula
																				 order by nombres, apellidos"); ?>
            	<select name="usuarios_asignados" id="usuarios_asignados" multiple size="6">
                	<? while ($bus_usuarios_asignados = mysql_fetch_array($sql_usuarios_asignados)){ ?>
	                    	<option value="<?=$bus_usuarios_asignados["cedula"];?>"><? echo $bus_usuarios_asignados["nombres"]." ".$bus_usuarios_asignados["apellidos"];?></option>
                    	<? } ?>
                </select>
            </td>
        </tr>
    </table>
    
    

	
<? }?>
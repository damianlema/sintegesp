<?php
/**
*
*	  "modifica.php" Realiza todas las modificaciones sobre los registros
*	Version: 1.0.1
*	Fecha Ultima Modificacion: 21/11/2007
*	Autor: Hector Lema
*
*/
session_start();
$path=$_SESSION['path'];
include_once("../../../conf/conex.php");
include_once("../../../lib/registra.php");
$conexion_db=conectarse();
$tabla=$_POST["tabla"];
$login=$_SESSION['login'];  // IDENTIFICA EL USUARIO ACTIVO
$fh=date("Y-m-d H:i:s");
$pc=gethostbyaddr($_SERVER['REMOTE_ADDR']);

	if ($tabla==0){ // modificando un registro de la tabla GRUPOS  DE CARGOS
			$codigo_grupo=$_POST["id_grupo"];
			$denominacion=$_POST["denominacion"];
			mysql_query("update grupos set  denominacion='".strtoupper($denominacion)."',
											usuario='".$login."', 
											fechayhora='".$fh."' 
												where idgrupo like '$codigo_grupo' 
													and status='a'"
													,$conexion_db);	
			registra_transaccion('m',$login,$fh,$pc,'grupos',$conexion_db);
			header("location:../grupos_cargos.php?modo=0&busca=0");
	}
	
	if ($tabla==1){ // modificando un registro de la tabla SERIES DE CARGOS
			$codigo_serie=$_POST["id_serie"];
			$denominacion=$_POST["denominacion"];
			$fh=date("Y-m-d H:i:s");
			$login=$_POST["user"];
			$idgrupo=$_POST["grupo"];
			$pc=gethostbyaddr($_SERVER['REMOTE_ADDR']); 
			mysql_query("update series set denominacion='".strtoupper($denominacion)."', 
											usuario='".$login."', 
											fechayhora='".$fh."', 
											idgrupo='".$idgrupo."' 
												where idserie like '$codigo_serie' 
													and status='a'",$conexion_db);	
			registra_transaccion('m',$login,$fh,$pc,'series',$conexion_db);													
			header("location:../series_cargos.php?modo=0&busca=0");
	}
	
	if ($tabla==2){ // modificando un registro de la tabla CARGOS
			$idcargo=$_POST["idcargo"];
			$denominacion=$_POST["denominacion"];
			$fh=date("Y-m-d H:i:s");
			$login=$_POST["user"];
			$idserie=$_POST["serie"];
			$grado=$_POST["grado"];
			$paso=$_POST["paso"];
			$pc=gethostbyaddr($_SERVER['REMOTE_ADDR']); 			
			mysql_query("update cargos set denominacion='".strtoupper($denominacion)."', 
										usuario='".$login."', 
										fechayhora='".$fh."', 
										idserie='".$idserie."', 
										grado='".$grado."',
										paso='".$paso."'
											where idcargo = '$idcargo' 
												and status = 'a'",$conexion_db);
			registra_transaccion('m',$login,$fh,$pc,'cargos',$conexion_db);
			header("location:../cargos.php?modo=0&busca=0");
	}
	
	if ($tabla==3){ // modificando un registro de la tabla NIVEL ESTUDIO
			$codigo_nivel_estudio=$_POST["id_nivelestudio"];
			$denominacion=$_POST["denominacion"];
			$fh=date("Y-m-d H:i:s");
			$login=$_POST["user"];
			$pc=gethostbyaddr($_SERVER['REMOTE_ADDR']); 
			mysql_query("update nivel_estudio set 	denominacion='".strtoupper($denominacion)."', 
											usuario='".$login."', 
											fechayhora='".$fh."' 
												where idnivel_estudio like '$codigo_nivel_estudio'"
													,$conexion_db);	
			registra_transaccion('m',$login,$fh,$pc,'nivel_estudio',$conexion_db);
			header("location:../nivelestudio.php?modo=0&busca=0");
	}
	
	if ($tabla==4){ // modificando un registro de la tabla PROFESION
			$codigo_profesion=$_POST["id_profesion"];
			$denominacion=strtoupper($_POST["denominacion"]);
			$abreviatura=$_POST["abreviatura"];
			$fh=date("Y-m-d H:i:s");
			$login=$_POST["user"];
			$pc=gethostbyaddr($_SERVER['REMOTE_ADDR']); 
			mysql_query("update profesion set 	denominacion='".strtoupper($denominacion)."', 
											abreviatura='".$abreviatura."',
											usuario='".$login."', 
											fechayhora='".$fh."' 
												where idprofesion like '$codigo_profesion'"
													,$conexion_db);	
			registra_transaccion('m',$login,$fh,$pc,'profesion',$conexion_db);
			header("location:../profesiones.php?modo=0&busca=0");
	}
	
	if ($tabla==5){ // modificando un registro de la tabla MENSIONES
			$codigo_mension=$_POST["id_mension"];
			$denominacion=$_POST["denominacion"];
			$fh=date("Y-m-d H:i:s");
			$login=$_POST["user"];
			$pc=gethostbyaddr($_SERVER['REMOTE_ADDR']); 
			mysql_query("update mension set 	denominacion='".strtoupper($denominacion)."', 
											usuario='".$login."', 
											fechayhora='".$fh."' 
												where idmension like '$codigo_mension'"
													,$conexion_db);	
			registra_transaccion('m',$login,$fh,$pc,'mension',$conexion_db);
			header("location:../mension.php?modo=0&busca=0");
	}
	
	if ($tabla==6){ // modificando un registro de la tabla  EDO_CIVIL
			$idedocivil=$_POST["id_edocivil"];
			$denominacion=$_POST["denominacion"];
			$fh=date("Y-m-d H:i:s");
			$login=$_POST["user"];
			$pc=gethostbyaddr($_SERVER['REMOTE_ADDR']); 
			mysql_query("update edo_civil set 	denominacion='".strtoupper($denominacion)."',
												usuario='".$login."', 
												fechayhora='".$fh."' 
													where idedo_civil like '$idedocivil' 
														and status='a'"
															,$conexion_db);
			registra_transaccion('m',$login,$fh,$pc,'edocivil',$conexion_db);
			header("location:../edocivil.php?modo=0&busca=0");
	}
	
	if ($tabla==7){ // modificando un registro de la tabla  PARENTEZCO
			$idparentezco=$_POST["id_parentezco"];
			$denominacion=$_POST["denominacion"];
			$fh=date("Y-m-d H:i:s");
			$login=$_POST["user"];
			$pc=gethostbyaddr($_SERVER['REMOTE_ADDR']); 
			mysql_query("update parentezco set 	denominacion='".strtoupper($denominacion)."',
												usuario='".$login."', 
												fechayhora='".$fh."' 
													where idparentezco like '$idparentezco' 
														and status='a'"
															,$conexion_db);
			registra_transaccion('m',$login,$fh,$pc,'parentezco',$conexion_db);
			header("location:../parentezco.php?modo=0&busca=0");
	}
	
	if ($tabla==8){ // modificando un registro de la tabla GRUPOS SANGUINEOS
			$codigo_grupo_sanguineo=$_POST["id_gruposanguineo"];
			$denominacion=$_POST["denominacion"];
			$fh=date("Y-m-d H:i:s");
			$login=$_POST["user"];
			$pc=gethostbyaddr($_SERVER['REMOTE_ADDR']); 
			mysql_query("update grupo_sanguineo set 	denominacion='".strtoupper($denominacion)."', 
											usuario='".$login."', 
											fechayhora='".$fh."' 
												where idgrupo_sanguineo like '$codigo_grupo_sanguineo'"
													,$conexion_db);	
			registra_transaccion('m',$login,$fh,$pc,'grupo_sanguineo',$conexion_db);
			header("location:../gruposanguineo.php?modo=0&busca=0");
	}

	if ($tabla==9){ // modificando un registro de la tabla TIPO MOVIMIENTO
			$codigo_tipo_movimiento=$_POST["id_tipomovimiento"];
			$denominacion=$_POST["denominacion"];
			$fh=date("Y-m-d H:i:s");
			$login=$_POST["user"];
			$pc=gethostbyaddr($_SERVER['REMOTE_ADDR']); 
			mysql_query("update tipo_movimiento set 	denominacion='".strtoupper($denominacion)."', 
											usuario='".$login."', 
											fechayhora='".$fh."' 
												where idtipo_movimiento like '$codigo_tipo_movimiento'"
													,$conexion_db);	
			registra_transaccion('m',$login,$fh,$pc,'tipo_movimiento',$conexion_db);
			header("location:../tipos_movimientos.php?modo=0&busca=0");
	}
	
?>
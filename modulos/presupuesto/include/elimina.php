<?php
/**
*
*	  "elimina.php" Realiza todas las eliminaciones logicas sobre los registros
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

	if ($tabla==0){ // eliminando un registro de la tabla GRUPOS DE CARGOS
			$codigo_grupo=$_POST["id_grupo"];
			$fh=date("Y-m-d H:i:s");
			$login=$_POST["user"];
			$pc=gethostbyaddr($_SERVER['REMOTE_ADDR']);  
			mysql_query("delete from grupos where idgrupo = '$codigo_grupo'"
													,$conexion_db);
			registra_transaccion('e',$login,$fh,$pc,'grupos',$conexion_db);
			header("location:../grupos_cargos.php?modo=0&busca=0");
	}
	
	if ($tabla==1){ // eliminando un registro de la tabla SERIES DE CARGOS
			$codigo_serie=$_POST["id_serie"];
			$fh=date("Y-m-d H:i:s");
			$login=$_POST["user"];
			$pc=gethostbyaddr($_SERVER['REMOTE_ADDR']);  
			mysql_query("delete from series where idserie = '$codigo_serie'"
													,$conexion_db);	
			registra_transaccion('e',$login,$fh,$pc,'series',$conexion_db);
			header("location:../series_cargos.php?modo=0&busca=0");
	}
	
	if ($tabla==2){ // eliminando un registro de la tabla CARGOS
			$idcargo=$_POST["idcargo"];
			$fh=date("Y-m-d H:i:s");
			$login=$_POST["user"];
			$pc=gethostbyaddr($_SERVER['REMOTE_ADDR']);  
			mysql_query("delete from cargos where idcargo = '$idcargo'"
													,$conexion_db);	
			registra_transaccion('e',$login,$fh,$pc,'cargos',$conexion_db);
			header("location:../cargos.php?modo=0&busca=0");
	}

	if ($tabla==3){ // eliminando un registro de la tabla NIVEL DE ESTUDIO
			$codigo_nivel_estudio=$_POST["id_nivelestudio"];
			$fh=date("Y-m-d H:i:s");
			$login=$_POST["user"];
			$pc=gethostbyaddr($_SERVER['REMOTE_ADDR']);  
			mysql_query("delete from nivel_estudio where idnivel_estudio = '$codigo_nivel_estudio'"
													,$conexion_db);	
			registra_transaccion('e',$login,$fh,$pc,'nivel_estudio',$conexion_db);
			header("location:../nivelestudio.php?modo=0&busca=0");
	}
	
	if ($tabla==4){ // eliminando un registro de la tabla PROFESIONES
			$codigo_profesion=$_POST["id_profesion"];
			$fh=date("Y-m-d H:i:s");
			$login=$_POST["user"];
			$pc=gethostbyaddr($_SERVER['REMOTE_ADDR']);  
			mysql_query("delete from profesion where idprofesion = '$codigo_profesion'"
													,$conexion_db);	
			registra_transaccion('e',$login,$fh,$pc,'profesion',$conexion_db);
			header("location:../profesiones.php?modo=0&busca=0");
	}
	
	if ($tabla==5){ // eliminando un registro de la tabla MENSION
			$codigo_mension=$_POST["id_mension"];
			$fh=date("Y-m-d H:i:s");
			$login=$_POST["user"];
			$pc=gethostbyaddr($_SERVER['REMOTE_ADDR']);  
			mysql_query("delete from mension where idmension = '$codigo_mension'"
													,$conexion_db);	
			registra_transaccion('e',$login,$fh,$pc,'mension',$conexion_db);
			header("location:../mension.php?modo=0&busca=0");
	}
	
	if ($tabla==6){ // eliminando un registro de la tabla EDOCIVIL
			$idedocivil=$_POST["id_edocivil"];
			$fh=date("Y-m-d H:i:s");
			$login=$_POST["user"];
			$pc=gethostbyaddr($_SERVER['REMOTE_ADDR']);  
			mysql_query("delete from edo_civil where idedo_civil = '$idedocivil'"
													,$conexion_db);
			
			registra_transaccion('e',$login,$fh,$pc,'edo_civil',$conexion_db);
			header("location:../edocivil.php?modo=0&busca=0");
	}
	
	if ($tabla==7){ // eliminando un registro de la tabla PARENTEZCO
			$idparentezco=$_POST["id_parentezco"];
			$fh=date("Y-m-d H:i:s");
			$login=$_POST["user"];
			$pc=gethostbyaddr($_SERVER['REMOTE_ADDR']);  
			mysql_query("delete from parentezco where idparentezco = '$idparentezco'"
													,$conexion_db);
			registra_transaccion('e',$login,$fh,$pc,'parentezco',$conexion_db);
			header("location:../parentezco.php?modo=0&busca=0");
	}
	
	if ($tabla==8){ // eliminando un registro de la tabla GRUPO SANGUINEO
			$codigo_grupo_sanguineo=$_POST["id_gruposanguineo"];
			$fh=date("Y-m-d H:i:s");
			$login=$_POST["user"];
			$pc=gethostbyaddr($_SERVER['REMOTE_ADDR']);  
			mysql_query("delete from grupo_sanguineo where idgrupo_sanguineo = '$codigo_grupo_sanguineo'"
													,$conexion_db);	
			registra_transaccion('e',$login,$fh,$pc,'grupo_sanguineo',$conexion_db);
			header("location:../gruposanguineo.php?modo=0&busca=0");
	}
	
	if ($tabla==9){ // eliminando un registro de la tabla TIPO MOVIMIENTO
			$codigo_tipo_movimiento=$_POST["id_tipomovimiento"];
			$fh=date("Y-m-d H:i:s");
			$login=$_POST["user"];
			$pc=gethostbyaddr($_SERVER['REMOTE_ADDR']);  
			mysql_query("delete from tipo_movimiento where idtipo_movimiento = '$codigo_tipo_movimiento'"
													,$conexion_db);	
			registra_transaccion('e',$login,$fh,$pc,'tipo_movimiento',$conexion_db);
			header("location:../tipos_movimientos.php?modo=0&busca=0");
	}
?>
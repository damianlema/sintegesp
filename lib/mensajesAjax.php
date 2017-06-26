<?php
//Establece conexion con la base de datos
include ("../conf/conex.php");
$conex=conectarse();

//Se crea la consulta que traera a todos los mensajes existentes
$consulta=mysql_query("SELECT * FROM microbloging ORDER BY id_mensaje DESC",$conex);

//Se obtiene la cantidad de mensajes que seran presentados al usuario
$cantidad = $_POST['cantidad'];
$cantidadRegistro = mysql_num_rows($consulta);

$i=0;

//Si existen mensaje en la base de datos
if($reg=mysql_fetch_array($consulta))
{
	do
	{
		//Consulta los datos del propietario del mensaje
		$consulta2 = "SELECT * FROM usuarios WHERE cedula = '".$reg[5]."'";
		$registro2 = mysql_query($consulta2,$conex);
		$reg2 = mysql_fetch_array($registro2);

		//Consulta los datos del usuario del mensaje
		$consulta3 = "SELECT * FROM usuarios WHERE cedula = '".$reg[1]."'";
		$registro3 = mysql_query($consulta3,$conex);
		$reg3 = mysql_fetch_array($registro3);

		//Si el usuario en session es el usuario del mensaje
		if(($_SESSION['cedula_usuario']==$reg[1]))
		{
			$opciones = 1;
		}
		else
		{
			$opciones = 2;
		}

		//Si el usuario es distinto del propietario del mensaje
		if($reg[1]!=$reg[5])
		{
			$usuario = $reg3[2]." ".$reg3[1];
		}
		else
		{
			$usuario =	'';
		}

		//Cambio de la fecha a presentar
		$fecha = $reg[3];
		$nuevaFecha = $fecha[8].$fecha[9]."/".$fecha[5].$fecha[6]."/".$fecha[0].$fecha[1].$fecha[2].$fecha[3];

		//Comprueba que el usuario tenga avatar
		if(file_exists("../imagenes/avatar/".md5($reg[5]).".png"))
		{
			$imagen = md5($reg[5]).".png";
		}
		else
		{
			$imagen = 'userDefault.png';
		}


		//Se cargan los datos en Array
		$datos[$i] = array('id_mensaje'=>$reg[0],'imagen'=>$imagen,'id_usuario'=>$reg[1],'nombre'=>$reg2[2],'apellido'=>$reg2[1],'usuario'=>$reg2[3],'mensaje'=>$reg[2],'id_propietario'=>$reg[5],'opciones'=>$opciones, 'reenviante'=>$usuario,'fecha'=>$nuevaFecha);
		$i++;

		//Si i a llegado a la cantidad pedida
		if($i==$cantidad)
		{
			break;
		}
	} while ($reg=mysql_fetch_array($consulta));

	//Si es exitoso
	$estado = TRUE;
	$msn = '';
	$cantidad = $i;

	if($cantidadRegistro<=$cantidad)
	{
		$msnAnterior = 'no';
	}
	else
	{
		$msnAnterior = 'si';
	}

}
else
{
	//Si no es exitoso
	$estado = FALSE;
	$msn = 'No hay mensajes';
	$datos = '';
	$cantidad = '';
	$msnAnterior = '';
}
//Se imprime el formato JSON que retorna los valores
 echo json_encode(array("estado"=>$estado,"data"=>$datos,'msn'=>$msn,'cantidad'=>$cantidad,'msnAnterior'=>$msnAnterior));

?>
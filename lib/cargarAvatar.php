<?php
		
		$destination_path = '../imagenes/tmp/';
		$tipo = $_FILES['archivoAvatar']['type'];
		$result = 0;
		
		//Si el archivo es de tipo image
		if( ($tipo=='image/jpg')||($tipo=='image/png')||($tipo=='image/jpeg'));
		{
			//Si el campo nombre esta vacio
			if($_POST['nombreAvatar']==NULL)
			{
				$nombreFoto = basename($_FILES['archivoAvatar']['name']); 
				$target_path = $destination_path . basename($_FILES['archivoAvatar']['name']);
			}
			//Si el campo nombre posee valor
			else
			{
				$nombreFoto = $_POST['nombreAvatar'];
				$target_path = $destination_path . $_POST['nombreAvatar'];
			}
			
			//Si la carga al directorio es exitosa
	   		if(@move_uploaded_file($_FILES['archivoAvatar']['tmp_name'], $target_path)) 
	   		{
	   			//Carga result = 1
	      		$result = 1;
				$estado = TRUE;
	   		}
			else 
			{
				//Carga result = 0
				$result = 0; 
				$estado = FALSE;	
			}
	   
	   		sleep(1);
		}
?>
<!-- JS insertado en el iframe de carga de imagen -->
<script language="javascript" type="text/javascript">window.top.main.stopUpload(<?php echo $result; ?>);</script>
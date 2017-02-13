<?php

class Acceso{

	private $email;
	private $usuario;
	private $clave;

	private function Encrypt($string){
		$sizeof = strlen($string) +1;
		$result = '';
		for($x = $sizeof; $x >= 0; $x--){
			$result .= $string[$x];
		}
		$result = md5($result);
		return $result;
	}
	public function Login(){
		try{
			if(!empty($_POST['usuario']) and !empty($_POST['clave'])and !empty($_POST['recuerdame'])){
				$db = new Conexion();
				$this->usuario = $db->real_escape_string($_POST['usuario']);
				$this->clave = $this->Encrypt($_POST['clave']);
				$sql = $db->query("SELECT * FROM users WHERE user = '$this->usuario' AND password = '$this->clave'");
				if($db->rows($sql) > 0){
					$datos = $db->recorrer($sql);
					$_SESSION['iduser'] = $datos['idusers'];
					$_SESSION['usuario'] = $datos['user'];
					$_SESSION['email'] = $datos['email'];
					if($_POST['recuerdame'] == true) {
						ini_set('session.cookie_lifetime', 60*60*24*20);
					}
					echo 1;
				}else{
					throw new Exception(2);
				}
				$db->liberar($sql);
				$db->close();
			}else{
				throw new Exception('Error: Datos vacios.');
			}
		} catch(Exception $loginCatch){
			echo $loginCatch->getMessage();
		}
	}

	public function Recuperar(){

	}

	public function Registro(){
		try{
			if(!empty($_POST['usuario']) and !empty($_POST['clave']) and !empty($_POST['email'])){
				$db = new Conexion();
				$this->usuario = $db->real_escape_string($_POST['usuario']);
				$this->email = $db->real_escape_string($_POST['email']);
				$this->clave = $this->Encrypt($_POST['clave']);
				$sql = $db->query("SELECT * FROM users WHERE user = '$this->usuario' OR email = '$this->email'");
				if($db->rows($sql) == 0){
					$sql_insertar = $db->query("INSERT INTO users (user,password,email) 
												VALUES ('$this->usuario','$this->clave','$this->email')");
					$id = $db->insert_id;
					$_SESSION['iduser'] = $id;
					$_SESSION['usuario'] = $this->usuario;
					$_SESSION['email'] = $this->email;
					echo 1;
					$db->liberar($sql_insertar);
				}else{
					$datos = $db->recorrer($sql);
					if(strtolower($this->usuario) == strtolower($datos['user'])){
						throw new Exception(2);
					}else{
						throw new Exception(3);
					}
				}
				$db->liberar($sql);
				$db->close();
			}else{
				throw new Exception('Error: Datos vacios.');
			}
		} catch(Exception $registroCatch){
			echo $registroCatch->getMessage();
		}
	}
}
?>
<?php
require_once("../constantes.php");

class Persona
{
    private $idUsuario;
    private $usuario;
    private $contrasena;
	
	/*Constructor*/
	public function __construct(){
	}
	public function _construct($idUsuario, $usuario, $contrasena){
		$this->idUsuario= $idUsuario;
		$this->usuario= $usuario;
		$this->contrasena= $contrasena;
	}
	public function getIdUsuario(){
		return $this->idUsuario;
	}
	public function setIdUsuario($idUsuario){
		$this->idUsuario = $idUsuario;
	}
	public function getUsuario(){
		return $this->usuario;
	}
	public function setUsuario($usuario){
		$this->usuario = $usuario;
	}
	public function getContrasena(){
		return $this->contrasena;
	}
	public function setContrasena($contrasena){
		$this->contrasena = $contrasena;
	}
	public function getUsuarios(): array {
		$usuarios = [0];
		$cn = $this->conectar();
		$sql = "SELECT Nombre, `Password` FROM usuarios;";
		$res = $cn->query($sql);
		//$usuarios = $res->fetch_all(); // --> no hay claves, solo posiciones
		$row = $res->fetch_assoc();
		while ($row = $res->fetch_assoc()) {
			$usuarios[$row['Nombre']] = $row; // --> cada fila ya está armada con las claves y valores
			
		}
	
		return $usuarios;
	}

	public function validarLogin(): bool {
		$usuario = $this->getUsuario();
		$contrasena = $this->getContrasena();

		$cn = $this->conectar();
		$sql = "SELECT * FROM `usuarios` WHERE `Nombre`= '$usuario' and 'Password' = '$contrasena'";
		$res = $cn->query($sql);
		$usuarioEncontrado = $res->fetch_all(); // --> no hay claves, solo posiciones
		
		return count($usuarioEncontrado) > 0;
	}
		
	//*******************************************************
	function conectar(){
		//echo "<br> CONEXION A LA BASE DE DATOS<br>";
		$c = new mysqli(SERVER,USER,PASS,BD);
		
		if($c->connect_errno) {
			die("Error de conexión: " . $c->connect_error);
		}else{
			//echo "La conexión tuvo éxito .......<br><br>";
		}
		
		$c->set_charset("utf8");
		return $c;
	}
	//**********************************************************	
}
		
?>	
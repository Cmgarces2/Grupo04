<?php
	require("Persona.php");

	// echo "<br>VARIABLE SESSION: <br>";
	// echo "<pre>";
	// 	session_start();
	// 	print_r($_SESSION);
	// echo "</pre>";	
	
	$usuario = $_POST['usuario'];
	$clave = $_POST['clave'];   	
    	
	$u = new Persona();
	$u->setUsuario($usuario);
	$u->setContrasena($clave);

	$validacion = $u->validarLogin();

	if($validacion){
		header("location:VerPersona.php?op=".$usuario); // redirect
	}
	else{
		echo "<br>VARIABLE USUARIO: ";
		echo $usuario;	
		echo "<br>VARIABLE CLAVE: ";
		echo $clave;		
		//echo "Error en la autentificación";
		header("location:ErrorAutentificacion.php"); // redirect
	}

	mysqli_free_result($resultado);	
	mysqli_close($conexion);
?>
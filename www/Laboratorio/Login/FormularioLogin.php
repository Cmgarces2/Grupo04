<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Formulario de Login</title>
	<style>
		body {
			font-family: Arial, sans-serif;
			background-color: #f4f4f4;
			margin: 0;
			display: flex;
			justify-content: center;
			align-items: center;
			height: 100vh;
		}

		form {
			background-color: #fff;
			border-radius: 8px;
			box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
			padding: 20px;
			width: 300px;
		}

		h2 {
			text-align: center;
			color: #333;
		}

		select,
		input {
			width: 100%;
			padding: 10px;
			margin: 8px 0;
			box-sizing: border-box;
			border: 1px solid #ccc;
			border-radius: 4px;
		}

		input[type="submit"] {
			background-color: #4caf50;
			color: #fff;
			cursor: pointer;
		}

		input[type="submit"]:hover {
			background-color: #45a049;
		}
	</style>
</head>
<body>
	<?php
		require("Persona.php");
		$u = new Persona();
		$usuariosLista = $u->getUsuarios();
		session_start();
		$_SESSION['usuarios']=$usuariosLista;

		/*
		echo "<pre>";
		print_r($usuariosLista);
		echo "</pre>";
		*/

		echo '<form action="validar.php" method="POST">';
		echo '<h2>Formulario de Login</h2>';
		//echo '<input type="text" placeholder="&#128272; Usuario" name="usuario">';
		echo '<select name="usuario">';
		echo "<option selected>" . "Escoje un usuario...." . "</option>";	
		foreach($usuariosLista as $usuario){
			echo "<pre>";
			print_r($usuario);
			echo "</pre>";
			echo "<option value=".$usuario['Nombre'].">".$usuario['Nombre']."</option>";
		}
		echo "</select>";
		echo '<input type="password" placeholder="&#128272; Contraseña" name="Password">';	
		echo '<input type="submit" value="LOGIN">';
		echo '<input type="button" value="CANCELAR" onclick="window.location.href=\'../index.html\'">';
		echo "</form>"; 
	?>
</body>
</html>
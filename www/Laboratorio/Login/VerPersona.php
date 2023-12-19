<html>

<head>
	<title>Usuarios Veris</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" crossorigin="anonymous" />

</head>

<body style="padding-top: 75px;">

	<?php
	require_once("Persona.php");
	require_once("../constantes.php");
	//include_once("Persona.php");


	session_start();
	$usuariosLista = $_SESSION['Nombre'];

	
echo "<pre>";
print_r($usuariosLista);
echo "</pre>";


	if (isset($_POST['op']))
		$op = $_POST['op'];
	else
    if (isset($_GET['op']))
		$op = $_GET['op'];

	$obj = $usuariosLista[$op];

	echo "<h1>" . $obj['Nombre'] . "</h1>";
	echo "</br>";


	$cn = conectar();
	$v = new Persona($cn);

	
				echo'<table border=1 alingn="center" style="width:100%">
				<tr>
					<th colspan="3">BIENVENIDO!</th>
					<th colspan="2"> <a href="FormularioLogin.php">Cerrar Sesión</a> </th>
				</tr>
				<tr>
					<th colspan="3">Hola Usuario: '. $obj['Nombre'] .' !</th>
				</tr>
	
				</table>';
			
	
			//require_once("class/class.estudiantes.php");
		  /* //if()
			   $cn = conectar();
			   $v = new Estudiantes($cn);
			   // PARTE 1.1
			   if(isset($_GET['d'])){
					 
				   echo "<br>PETICION GET <br>";
				   echo "<pre>";
					   print_r($_GET);
				   echo "</pre>";
				 
				   // 2.1 PETICION GET
				   // $dato = $_GET['d'];
				   
				   // 2.2 DETALLE id
				   $dato = base64_decode($_GET['d']);
				   $tmp = explode("/", $dato);
				   
				   
				   echo "<br>VARIABLE TEMP <br>";
				   echo "<pre>";
					   print_r($tmp);
				   echo "</pre>";
						   
				   
				   $op = $tmp[0];
				   $id = $tmp[1];
				   
				   switch ($op) {
						case "det":
							   echo $v->get_detail($id);
							   break;
					   
					   case "act":
							   echo $v->get_form($id);
							   break;
					   
					   case "new":
							   echo $v->get_form();
							   break;
							   
					   case "del":
							   echo $v->delete_vehiculo($id); // BORRAR TODOS LOS REGISTROS DE LA BASE DE DATOS
							   break;
					   default:
						   echo "Opción no válida";
						   break;
					   }
					   
				   
			   }else{
						  
				   echo "<br>PETICION POST <br>";
				   echo "<pre>";
					   print_r($_POST);
				   echo "</pre>";
				 
			   if(isset($_POST['Guardar']) && $_POST['op']=="new"){
				   $v->save_vehiculo();
			   }elseif(isset($_POST['Guardar']) && $_POST['op']=="update"){
				   $v->update_vehiculo();
			   }else{
				   echo $v->get_list();
			   }
				   
			   }
		   */
				
		
	//*******************************************************
	function conectar()
	{
		//echo "<br> CONEXION A LA BASE DE DATOS<br>";
		$c = new mysqli(SERVER, USER, PASS, BD);

		if ($c->connect_errno) {
			die("Error de conexión: " . $c->connect_error);
		} else {
			//echo "La conexión tuvo éxito .......<br><br>";
		}

		$c->set_charset("utf8");
		return $c;
	}
	//**********************************************************

	?>

	
</body>

</html>
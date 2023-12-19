<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>Recetas</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
</head>
<body>
	<?php
		require_once("../constantes.php");
		include_once("../Clases/class.recetas.php");
		
		$cn = conectar();
		$m = new recetas($cn);
		
		if(isset($_GET['d'])){
			$dato = base64_decode($_GET['d']);
		//	echo $dato;exit;
			$tmp = explode("/", $dato);
			$op = $tmp[0];
			$RecetaID = $tmp[1];
			
			if($op == "del"){
				echo $m->delete_receta($RecetaID);
			}elseif($op == "det"){
				echo $m->get_detail_receta($RecetaID);
			}elseif($op == "new"){
				echo $m->get_form();
			}elseif($op == "act"){
				echo $m->get_form($RecetaID);
			}
			
       // PARTE III	
		}else{
			   /*
				echo "<br>PETICION POST <br>";
				echo "<pre>";
					print_r($_POST);
				echo "</pre>";
				*/
			if(isset($_POST['Guardar']) && $_POST['op']=="new"){
				$m->save_receta();
			}elseif(isset($_POST['Guardar']) && $_POST['op']=="update"){
				$m->update_receta();
			}else{
				echo $m->get_list();
			}	
		}
		
	//*******************************************************
		function conectar(){
			//echo "<br> CONEXION A LA BASE DE DATOS<br>";
			$c = new mysqli(SERVER,USER,PASS,BD);
			
			if($c->connect_errno) {
				die("Error de conexión: " . $c->mysqli_connect_errno() . ", " . $c->connect_error());
			}else{
				//echo "La conexión tuvo éxito .......<br><br>";
			}
			
			$c->set_charset("utf8");
			return $c;
		}
	//**********************************************************	

		
	?>	
</body>
</html>

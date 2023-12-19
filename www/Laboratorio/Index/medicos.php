<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>Médicos</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
</head>
<body>
	<?php
		require_once("../constantes.php");
		include_once("../Clases/class.medicos.php");
		
		$cn = conectar();
		$m = new medicos($cn);
		
		if(isset($_GET['d'])){
			$dato = base64_decode($_GET['d']);
		//	echo $dato;exit;
			$tmp = explode("/", $dato);
			$op = $tmp[0];
			$MedicoID = $tmp[1];
			
			if($op == "del"){
				echo $m->delete_medicos($MedicoID);
			}elseif($op == "det"){
				echo $m->get_detail_medicos($MedicoID);
			}elseif($op == "new"){
				echo $m->get_form();
			}elseif($op == "act"){
				echo $m->get_form($MedicoID);
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
				$m->save_medicos();
			}elseif(isset($_POST['Guardar']) && $_POST['op']=="update"){
				$m->update_medicos();
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

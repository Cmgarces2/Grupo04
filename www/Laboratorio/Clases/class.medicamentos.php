<?php
class medicamentos{
	private $IdMedicamento;
	private $Nombre;
	private $Tipo;
	private $con;
	
	function __construct($cn){
		$this->con = $cn;
	}
		
	
//*********************** 3.1 METODO update_consulta() **************************************************	
	
public function update_medicamentos(){
	$this->IdMedicamento = $_POST['IdMedicamento'];
	$this->Nombre = $_POST['Nombre'];
	$this->Tipo = $_POST['Tipo'];
	
	$sql = "UPDATE medicamentos SET Nombre='$this->Nombre',
								Tipo='$this->Tipo'							

			WHERE IdMedicamento=$this->IdMedicamento;";
	//echo $sql;
	//exit;
	if($this->con->query($sql)){
		echo $this->_message_ok("modificó");
	}else{
		echo $this->_message_error("al modificar");
	}								
									
}
	

//*********************** 3.2 METODO save_consulta() **************************************************	

	public function save_medicamentos(){
		
		$this->Nombre = $_POST['Nombre'];
        $this->Tipo = $_POST['Tipo'];
		
		 /*
				echo "<br> FILES <br>";
				echo "<pre>";
					print_r($_FILES);
				echo "</pre>";
		      
		*/
				
		$sql = "INSERT INTO medicamentos VALUES(NULL,
											'$this->Nombre',
											'$this->Tipo');";
		//echo $sql;
		//exit;
		if($this->con->query($sql)){
			echo $this->_message_ok("guardó");
		}else{
			echo $this->_message_error("guardar");
		}								
										
	}


//*********************** 3.3 METODO _get_name_File() **************************************************	
	
	private function _get_name_file($nombre_original, $tamanio){
		$tmp = explode(".",$nombre_original); //Divido el nombre por el punto y guardo en un arreglo
		$numElm = count($tmp); //cuento el número de elemetos del arreglo
		$ext = $tmp[$numElm-1]; //Extraer la última posición del arreglo.
		$cadena = "";
			for($i=1;$i<=$tamanio;$i++){
				$c = rand(65,122);
				if(($c >= 91) && ($c <=96)){
					$c = NULL;
					 $i--;
				 }else{
					$cadena .= chr($c);
				}
			}
		return $cadena . "." . $ext;
	}
	
	
//*************************************** PARTE I ************************************************************
	
	    
	 /*Aquí se agregó el parámetro:  $defecto*/
	
	
//************************************* PARTE II ****************************************************	



public function get_form($IdMedicamento=NULL){
		
	if($IdMedicamento == NULL){
		$this->Nombre = NULL;
		$this->Tipo = NULL;
		
		$flag = "enabled";
		$op = "new";
		
	}else{

		$sql = "SELECT * FROM medicamentos WHERE IdMedicamento=$IdMedicamento;";
		$res = $this->con->query($sql);
		$row = $res->fetch_assoc();
		
		$num = $res->num_rows;
		if($num==0){
			$mensaje = "tratar de actualizar el medicamento con id= ".$IdMedicamento;
			echo $this->_message_error($mensaje);
		}else{   
		
		  // ** TUPLA ENCONTRADA **
			/*
			echo "<br>TUPLA <br>";
			echo "<pre>";
				print_r($row);
			echo "</pre>";
			*/
			$this->Nombre = $row['Nombre'];
			$this->Tipo = $row['Tipo'];
			
			$flag = "enabled";
			$op = "update";
		}
	}
	
	$html = '
<form name="medicamento" method="POST" id="form" action="medicamentos.php" enctype="multipart/form-data" onsubmit="return validarFormulario()">
  
  <input type="hidden" name="IdMedicamento" value="' . $IdMedicamento  . '">
  <input type="hidden" name="op" value="' . $op  . '">
  
  <table align="center" class="table table-striped">
    <thead class="thead-dark">
      <tr>
        <th colspan="2">DATOS Medicamento</th>
      </tr>
    </thead>
      <tr>
        <th>Nombre:</td>
        <td><input type="text" size="15" name="Nombre" value="' . $this->Nombre . '" required></td>
      </tr>
      <tr>
        <th>Tipo:</td>
        <td><input type="text" size="15" name="Tipo" value="' . $this->Tipo . '" required></td>
      </tr>
      <tr>
        <th colspan="2" class="text-center"><input type="submit" name="Guardar" value="GUARDAR"></th>
      </tr>
  </table>

  <div id="error-message" style="color: red;"></div>

</form>';
return $html;

}



	
	

	public function get_list(){
		$d_new = "new/0";
		$d_new_final = base64_encode($d_new);
		$html = '
		<table class="table table-dark table-striped" align="center">
			<tr>
				<th colspan="8" class="text-center">Lista de Medicamentos</th>
			</tr>
			<tr>
				<th colspan="8" class="text-center"><a href="medicamentos.php?d=' . $d_new_final . '" class="btn btn-secondary">Nuevo</a></th>
			</tr>
			<tr>
				<th class="text-center">Medicamento</th>
				<th class="text-center">Nombre</th>
				<th colspan="3" class="text-center">Acciones</th>
			</tr>';
		$sql = "SELECT * FROM medicamentos;";	
		
		$res = $this->con->query($sql);
		
		// Sin codificar <td><a href="index.php?op=del&IdMedicamento=' . $row['IdMedicamento'] . '">Borrar</a></td>
		while($row = $res->fetch_assoc()){
			$d_del = "del/" . $row['IdMedicamento'];
			$d_del_final = base64_encode($d_del);
			$d_act = "act/" . $row['IdMedicamento'];
			$d_act_final = base64_encode($d_act);
			$d_det = "det/" . $row['IdMedicamento'];
			$d_det_final = base64_encode($d_det);					
			$html .= '
				<tr>
					<td class="text-center">' . $row['Nombre'] . '</td>
					<td><a href="medicamentos.php?d=' . $d_del_final . '" class="btn btn-danger">Borrar</a></td>
					<td><a href="medicamentos.php?d=' . $d_act_final . '" class="btn btn-warning">Actualizar</a></td>
					<td><a href="medicamentos.php?d=' . $d_det_final . '" class="btn btn-primary">Detalle</a></td>
				</tr>';
		}
		$html .= '  
		</table>';
		
		return $html;
		
	}
	
	
	public function get_detail_medicamentos($IdMedicamento){
		$sql = "SELECT * FROM medicamentos;";	
		
		$res = $this->con->query($sql);
		$row = $res->fetch_assoc();
		
		$num = $res->num_rows;

        //Si es que no existiese ningun registro debe desplegar un mensaje 
        //$mensaje = "tratar de eliminar el consulta con IdMedicamento= ".$IdMedicamento;
        //echo $this->_message_error($mensaje);
        //y no debe desplegarse la tablas
        
        if($num==0){
            $mensaje = "tratar de editar el medicamento con IdMedicamento= ".$IdMedicamento;
            echo $this->_message_error($mensaje);
        }else{ 
				$html = ' 
				<table align="center" class="table table-striped">
					<thead class="thead-dark">
						<tr>
							<th colspan="2" class="text-center">DATOS DEL MEDICAMENTO</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th scope="row">Medicamento: </td>
							<td>'. $row['Nombre'] .'</td>
						</tr>
						<tr>
                        <th scope="row">Tipo: </td>
                        <td>'. $row['Tipo'] .'</td>
                         </tr>	
						<tr>
							<th colspan="2" class="text-center"><a href="medicamentos.php" class="btn btn-dark">Regresar</a></th>
					</tr>
				</tbody
																						
				</table>';
				
				return $html;
		}
	}
	
	
	public function delete_medicamentos($IdMedicamento){
		$sql = "DELETE FROM medicamentos WHERE IdMedicamento=$IdMedicamento;";

		if($this->con->query($sql)){
			echo $this->_message_ok("ELIMINÓ");
		}else{
			echo $this->_message_error("eliminar");
		}	
	}
	
	private function _message_error($tipo){
		$html = '
		<table border="0" align="center">
			<tr>
				<th>Error al ' . $tipo . '. Favor contactar a .................... </th>
			</tr>
			<tr>
				<th><a href="medicamentos.php">Regresar</a></th>
			</tr>
		</table>';
		return $html;
	}
	
	
	private function _message_ok($tipo){
		$html = '
		<table border="0" align="center">
			<tr>
				<th>El registro se  ' . $tipo . ' correctamente</th>
			</tr>
			<tr>
				<th><a href="medicamentos.php">Regresar</a></th>
			</tr>
		</table>';
		return $html;
	}
	
//****************************************************************************	
	
} // FIN SCRPIT
?>


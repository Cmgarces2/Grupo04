<?php
class pacientes {
    private $IdPaciente;
    private $IdUsuario;
    private $Nombre;
    private $Cedula;
    private $Edad;
    private $Genero;
    private $Estatura;
    private $Peso;
    private $con;
	
	function __construct($cn){
		$this->con = $cn;
	}
		
	
//*********************** 3.1 METODO update_paciente() **************************************************	
public function update_paciente() {
    $this->IdPaciente = $_POST['IdPaciente'];
    $this->Cedula = $_POST['Cedula']; // Agregar la propiedad Cedula
    $this->Nombre = $_POST['Nombre'];
    $this->Edad = $_POST['Edad'];
    $this->Genero = $_POST['Genero'];
    $this->Estatura = $_POST['Estatura'];
    $this->Peso = $_POST['Peso'];

    $sql = "UPDATE pacientes SET Cedula='$this->Cedula', 
                                Nombre='$this->Nombre',
                                Edad='$this->Edad',
                                Genero='$this->Genero',
                                Estatura='$this->Estatura',
                                Peso='$this->Peso'
            WHERE IdPaciente=$this->IdPaciente;";

    if ($this->con->query($sql)) {
        echo $this->_message_ok("modificó");
    } else {
        echo $this->_message_error("al modificar");
    }
}


	

//*********************** 3.2 METODO save_paciente() **************************************************	

public function save_paciente() {
    $this->Nombre = $_POST['Nombre'];
    $this->Edad = $_POST['Edad'];
    $this->Genero = $_POST['Genero'];
    $this->Cedula = $_POST['Cedula']; // Agregar la propiedad Cedula
    $this->Estatura = $_POST['Estatura']; // Agregar la propiedad Estatura
    $this->Peso = $_POST['Peso']; // Agregar la propiedad Peso

    $sql = "INSERT INTO pacientes (IdPaciente, Nombre, Edad, Genero, Cedula, Estatura, Peso)
            VALUES (NULL,
                    '$this->Nombre',
                    '$this->Edad',
                    '$this->Genero',
                    '$this->Cedula',
                    '$this->Estatura',
                    '$this->Peso');";

    if ($this->con->query($sql)) {
        echo $this->_message_ok("guardó");
    } else {
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



public function get_form($IdPaciente = NULL) {
    if ($IdPaciente == NULL) {
        $this->Nombre = NULL;
        $this->Edad = NULL;
        $this->Genero = NULL;
        $this->Cedula = NULL; // Agregar la propiedad Cedula
        $this->Estatura = NULL; // Agregar la propiedad Estatura
        $this->Peso = NULL; // Agregar la propiedad Peso

        $flag = "enabled";
        $op = "new";
    } else {
        $sql = "SELECT * FROM pacientes WHERE IdPaciente = $IdPaciente;";
        $res = $this->con->query($sql);
        $row = $res->fetch_assoc();

        $num = $res->num_rows;
        if ($num == 0) {
            $mensaje = "tratar de actualizar el paciente con IdPaciente = " . $IdPaciente;
            echo $this->_message_error($mensaje);
        } else {

            $this->Nombre = $row['Nombre'];
            $this->Edad = $row['Edad'];
            $this->Genero = $row['Genero'];
            $this->Cedula = $row['Cedula']; // Agregar Cedula
            $this->Estatura = $row['Estatura']; // Agregar Estatura
            $this->Peso = $row['Peso']; // Agregar Peso

            $flag = "enabled";
            $op = "update";
        }
    }

    $html = ' 
    <form name="paciente" method="POST" id="form" action="pacientes.php" enctype="multipart/form-data" onsubmit="return validarFormulario()">
  
    <input type="hidden" name="IdPaciente" value="' . $IdPaciente . '">
    <input type="hidden" name="op" value="' . $op . '">
  
    <table align="center" class="table table-striped">
        <thead class="thead-dark">
            <tr>
                <th colspan="2">DATOS PACIENTE</th>
            </tr>
        </thead>
        <tr>
            <th>Nombre:</th>
            <td><input type="text" size="15" name="Nombre" value="' . $this->Nombre . '" required></td>
        </tr>
        <tr>
            <th>Edad:</th>
            <td><input type="text" size="15" name="Edad" value="' . $this->Edad . '" required></td>
        </tr>
        <tr>
            <th>Genero:</th>
            <td><input type="text" size="15" name="Genero" value="' . $this->Genero . '" required></td>
        </tr>
        <tr>
            <th>Cedula:</th> <!-- Agregar el campo Cedula -->
            <td><input type="text" size="15" name="Cedula" value="' . $this->Cedula . '" required></td>
        </tr>
        <tr>
            <th>Estatura:</th> <!-- Agregar el campo Estatura -->
            <td><input type="text" size="15" name="Estatura" value="' . $this->Estatura . '" required></td>
        </tr>
        <tr>
            <th>Peso:</th> <!-- Agregar el campo Peso -->
            <td><input type="text" size="15" name="Peso" value="' . $this->Peso . '" required></td>
        </tr>
        <tr>
            <th colspan="2" class="text-center"><input type="submit" name="Guardar" value="GUARDAR"></th>
        </tr>
    </table>

    <div id="error-message" style="color: red;"></div>

</form>';
return $html;
}


public function get_list() {
    $d_new = "new/0";
    $d_new_final = base64_encode($d_new);
    $html = '
    <table class="table table-dark table-striped" align="center">
        <tr>
            <th colspan="8" class="text-center">Lista de Pacientes</th>
        </tr>
        <tr>
            <th colspan="8" class="text-center"><a href="pacientes.php?d=' . $d_new_final . '" class="btn btn-secondary">Nuevo</a></th>
        </tr>
        <tr>
            <th class="text-center">Paciente</th>
            <th class="text-center">Edad</th> <!-- Agregar el campo Edad -->
            <th class="text-center">Genero</th> <!-- Agregar el campo Genero -->
            <th colspan="3" class="text-center">Acciones</th>
        </tr>';
    $sql = "SELECT * FROM pacientes;";

    $res = $this->con->query($sql);

    // Sin codificar <td><a href="index.php?op=del&IdPaciente=' . $row['IdPaciente'] . '">Borrar</a></td>
    while ($row = $res->fetch_assoc()) {
        $d_del = "del/" . $row['IdPaciente'];
        $d_del_final = base64_encode($d_del);
        $d_act = "act/" . $row['IdPaciente'];
        $d_act_final = base64_encode($d_act);
        $d_det = "det/" . $row['IdPaciente'];
        $d_det_final = base64_encode($d_det);
        $html .= '
            <tr>
                <td class="text-center">' . $row['Nombre'] . '</td>
                <td class="text-center">' . $row['Edad'] . '</td> <!-- Agregar el campo Edad -->
                <td class="text-center">' . $row['Genero'] . '</td> <!-- Agregar el campo Genero -->
                <td><a href="pacientes.php?d=' . $d_del_final . '" class="btn btn-danger">Borrar</a></td>
                <td><a href="pacientes.php?d=' . $d_act_final . '" class="btn btn-warning">Actualizar</a></td>
                <td><a href="pacientes.php?d=' . $d_det_final . '" class="btn btn-primary">Detalle</a></td>
            </tr>';
    }
    $html .= '  
    </table>';

    return $html;
}

	
	
public function get_detail_paciente($IdPaciente) {
    $sql = "SELECT * FROM pacientes WHERE IdPaciente = $IdPaciente;";

    $res = $this->con->query($sql);
    $row = $res->fetch_assoc();
    $num = $res->num_rows;

    if ($num == 0) {
        $mensaje = "tratar de editar el paciente con IdPaciente= " . $IdPaciente;
        echo $this->_message_error($mensaje);
    } else {
        $html = ' 
        <table align="center" class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th colspan="2" class="text-center">DATOS DEL PACIENTE</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">Paciente:</th>
                    <td>' . $row['Nombre'] . '</td>
                </tr>
                <tr>
                    <th scope="row">Edad:</th>
                    <td>' . $row['Edad'] . '</td>
                </tr>
                <tr>
                    <th scope="row">Genero:</th>
                    <td>' . $row['Genero'] . '</td>
                </tr>
                <!-- Agregar los campos Cedula, Estatura y Peso -->
                <tr>
                    <th scope="row">Cedula:</th>
                    <td>' . $row['Cedula'] . '</td>
                </tr>
                <tr>
                    <th scope="row">Estatura:</th>
                    <td>' . $row['Estatura'] . '</td>
                </tr>
                <tr>
                    <th scope="row">Peso:</th>
                    <td>' . $row['Peso'] . '</td>
                </tr>
                <tr>
                    <th colspan="2" class="text-center"><a href="pacientes.php" class="btn btn-dark">Regresar</a></th>
                </tr>
            </tbody>
        </table>';

        return $html;
    }
}

	
	
	public function delete_paciente($IdPaciente){
		$sql = "DELETE FROM pacientes WHERE IdPaciente=$IdPaciente;";

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
				<th>Error al ' . $tipo . '. El registro esta asociado a una consulta </th>
			</tr>
			<tr>
				<th><a href="pacientes.php">Regresar</a></th>
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
				<th><a href="pacientes.php">Regresar</a></th>
			</tr>
		</table>';
		return $html;
	}
	
//****************************************************************************	
	
} // FIN SCRPIT
?>


<?php
class medicos {
    private $IdMedico;
    private $Nombre;
    private $Especialidad;
    private $IdUsuario;
    private $con;
    
    public function __construct($cn) {
        $this->con = $cn;
    }
		
	
//*********************** 3.1 METODO update_consulta() **************************************************	
	
public function update_medicos() {
    // Obtener los datos del formulario
    $this->IdMedico = $_POST['IdMedico'];
    $this->Nombre = $_POST['Nombre'];
    $this->Especialidad = $_POST['Especialidad'];
    $this->IdUsuario = $_POST['IdUsuario']; // Agregar la propiedad IdUsuario

    // Validar los datos (puedes agregar validación aquí)

    // Preparar la consulta SQL
    $sql = "UPDATE medicos SET Nombre='$this->Nombre', Especialidad='$this->Especialidad', IdUsuario=$this->IdUsuario WHERE IdMedico=$this->IdMedico";

    // Ejecutar la consulta
    if ($this->con->query($sql)) {
        // La actualización fue exitosa
        echo $this->_message_ok("modificó");
    } else {
        // Error al ejecutar la consulta
        echo $this->_message_error("al modificar");
    }
}

	

//*********************** 3.2 METODO save_consulta() **************************************************	

public function save_medicos() {
    $this->Nombre = $_POST['Nombre'];
    $this->Especialidad = $_POST['Especialidad'];
    $this->IdUsuario = $_POST['IdUsuario']; // Agregar la propiedad IdUsuario

    /*
    echo "<br> FILES <br>";
    echo "<pre>";
    print_r($_FILES);
    echo "</pre>";
    */

    $sql = "INSERT INTO medicos (Nombre, Especialidad, IdUsuario) VALUES ('$this->Nombre', '$this->Especialidad', $this->IdUsuario)";
    
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



public function get_form($IdMedico = NULL) {
    if ($IdMedico == NULL) {
        $this->Nombre = NULL;
        $this->Especialidad = NULL;
        $this->IdUsuario = NULL; // Agregar la propiedad IdUsuario

        $flag = "enabled";
        $op = "new";
    } else {
        $sql = "SELECT * FROM medicos WHERE IdMedico=$IdMedico;";
        $res = $this->con->query($sql);
        $row = $res->fetch_assoc();

        $num = $res->num_rows;
        if ($num == 0) {
            $mensaje = "tratar de actualizar el médico con id= " . $IdMedico;
            echo $this->_message_error($mensaje);
        } else {
            $this->Nombre = $row['Nombre'];
            $this->Especialidad = $row['Especialidad'];
            $this->IdUsuario = $row['IdUsuario']; // Agregar la propiedad IdUsuario

            $flag = "enabled";
            $op = "update";
        }
    }

    $html = ' 
    <form name="medico" method="POST" id="form" action="medicos.php" enctype="multipart/form-data" onsubmit="return validarFormulario()">
    
        <input type="hidden" name="IdMedico" value="' . $IdMedico  . '">
        <input type="hidden" name="op" value="' . $op  . '">
        
        <table align="center" class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th colspan="2">DATOS MÉDICO</th>
                </tr>
            </thead>
            <tr>
                <th>Nombre:</td>
                <td><input type="text" size="15" name="Nombre" value="' . $this->Nombre . '" required></td>
            </tr>
            <tr>
                <th>Especialidad:</td>
                <td><input type="text" size="15" name="Especialidad" value="' . $this->Especialidad . '" required></td>
            </tr>
            <tr>
                <th>IdUsuario:</td>
                <td><input type="text" size="15" name="IdUsuario" value="' . $this->IdUsuario . '" required></td>
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
            <th colspan="8" class="text-center">Lista de Médicos</th>
        </tr>
        <tr>
            <th colspan="8" class="text-center"><a href="medicos.php?d=' . $d_new_final . '" class="btn btn-secondary">Nuevo</a></th>
        </tr>
        <tr>
            <th class="text-center">Médico</th>
            <th class="text-center">Especialidad</th>
            <th colspan="3" class="text-center">Acciones</th>
        </tr>';
    $sql = "SELECT m.IdMedico, m.Nombre, e.Descripcion AS Especialidad
	FROM medicos m
	JOIN especialidades e ON m.Especialidad = e.IdEsp;";

    $res = $this->con->query($sql);

    while ($row = $res->fetch_assoc()) {
        $d_del = "del/" . $row['IdMedico'];
        $d_del_final = base64_encode($d_del);
        $d_act = "act/" . $row['IdMedico'];
        $d_act_final = base64_encode($d_act);
        $d_det = "det/" . $row['IdMedico'];
        $d_det_final = base64_encode($d_det);
        $html .= '
            <tr>
                <td class="text-center">' . $row['Nombre'] . '</td>
                <td class="text-center">' . $row['Especialidad'] . '</td>
                <td><a href="medicos.php?d=' . $d_del_final . '" class="btn btn-danger">Borrar</a></td>
                <td><a href="medicos.php?d=' . $d_act_final . '" class="btn btn-warning">Actualizar</a></td>
                <td><a href="medicos.php?d=' . $d_det_final . '" class="btn btn-primary">Detalle</a></td>
            </tr>';
    }
    $html .= '  
    </table>';

    return $html;
}

	
	
public function get_detail_medicos($IdMedico) {
    $sql = "SELECT m.Nombre AS NombreMedico, e.Descripcion AS Especialidad, m.IdUsuario
            FROM medicos m
            JOIN especialidades e ON m.Especialidad = e.IdEsp
            WHERE m.IdMedico = $IdMedico;";

    $res = $this->con->query($sql);
    $row = $res->fetch_assoc();
    $num = $res->num_rows;

    if ($num == 0) {
        $mensaje = "tratar de editar el médico con IdMedico= " . $IdMedico;
        echo $this->_message_error($mensaje);
    } else {
        $html = ' 
        <table align="center" class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th colspan="2" class="text-center">DATOS DEL MÉDICO</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">Médico: </td>
                    <td>' . $row['NombreMedico'] . '</td>
                </tr>
                <tr>
                    <th scope="row">Especialidad: </td>
                    <td>' . $row['Especialidad'] . '</td>
                </tr>
                <tr>
                    <th scope="row">Usuario: </td>
                    <td>' . $row['IdUsuario'] . '</td>
                </tr>
                <tr>
                    <th colspan="2" class="text-center"><a href="medicos.php" class="btn btn-dark">Regresar</a></th>
                </tr>
            </tbody>
        </table>';

        return $html;
    }
}


	
	
	public function delete_medicos($IdMedico){
		$sql = "DELETE FROM medicos WHERE IdMedico=$IdMedico;";

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
				<th>Error al ' . $tipo . '. El registro esta asociado a registros en otras tablas </th>
			</tr>
			<tr>
				<th><a href="medicos.php">Regresar</a></th>
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
				<th><a href="medicos.php">Regresar</a></th>
			</tr>
		</table>';
		return $html;
	}
	
//****************************************************************************	
	
} // FIN SCRPIT
?>


<?php
class Recetas {
    private $idReceta;
    private $idConsulta;
    private $idMedicamento;
    private $cantidad;
    private $conexion;

    public function __construct($cn) {
        $this->conexion = $cn;
    }
		
	
//*********************** 3.1 METODO update_consulta() **************************************************	
	
public function update_receta() {
    $this->idReceta = $_POST['IdReceta'];
    $this->idConsulta = $_POST['IdConsulta'];
    $this->idMedicamento = $_POST['IdMedicamento'];
    $this->cantidad = $_POST['Cantidad'];

    $sql = "UPDATE recetas SET IdConsulta='$this->idConsulta',
                               IdMedicamento='$this->idMedicamento',
                               Cantidad='$this->cantidad'
            WHERE IdReceta='$this->idReceta';";
    
    // echo $sql; // Puedes descomentar esta línea para depurar la consulta SQL si es necesario.
    
    if ($this->conexion->query($sql)) {
        echo $this->_message_ok("modificó");
    } else {
        echo $this->_message_error("al modificar");
    }
}

	

//*********************** 3.2 METODO save_consulta() **************************************************	

public function save_receta() {

	echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    $this->idConsulta = $_POST['IdConsulta'];
    $this->idMedicamento = $_POST['IdMedicamento'];
    $this->cantidad = $_POST['Cantidad'];

    $sql = "INSERT INTO recetas (IdConsulta, IdMedicamento, Cantidad) VALUES (
             '$this->idConsulta',
             '$this->idMedicamento',
             '$this->cantidad');";
    
    // echo $sql; // Puedes descomentar esta línea para depurar la consulta SQL si es necesario.
    
    if ($this->conexion->query($sql)) {
        echo $this->_message_ok("guardó");
    } else {
        echo $this->_message_error("guardar");
    }
}



//*********************** 3.3 METODO _get_name_File() **************************************************	
	
	
	
//*************************************** PARTE I ************************************************************
	
	    
	 /*Aquí se agregó el parámetro:  $defecto*/
	 private function _get_combo_db($tabla,$valor,$etiqueta,$nombre,$defecto){
		$html = '<select name="' . $nombre . '"id="pacienteSelect">';
		$sql = "SELECT $valor,$etiqueta FROM $tabla;";
		$res = $this->conexion->query($sql);
		while($row = $res->fetch_assoc()){
			//ImpResultQuery($row);
			$html .= ($defecto == $row[$valor])?'<option value="' . $row[$valor] . '" selected>' . $row[$etiqueta] . '</option>' . "\n" : '<option value="' . $row[$valor] . '">' . $row[$etiqueta] . '</option>' . "\n";
		}
		$html .= '</select>';
		return $html;
	}
	
	//$paciente para controlar las especialidades
	
	
	
	
//************************************* PARTE II ****************************************************	



public function get_form($IdReceta = NULL){
    if($IdReceta === NULL){
        $this->idConsulta = NULL;
        $this->idMedicamento = NULL;
        $this->cantidad = NULL;
        
        $flag = "enabled";
        $op = "new";
        
    }else{
        $sql = "SELECT * FROM recetas WHERE IdReceta = $IdReceta;";
        $res = $this->conexion->query($sql);
        $row = $res->fetch_assoc();
        
        $num = $res->num_rows;
        if($num == 0){
            $mensaje = "tratar de actualizar la receta con id = " . $IdReceta;
            echo $this->_message_error($mensaje);
        }else{
            // ** TUPLA ENCONTRADA **
            $this->idConsulta = $row['IdConsulta'];
            $this->idMedicamento = $row['IdMedicamento'];
            $this->cantidad = $row['Cantidad'];
            
            $flag = "enabled";
            $op = "update";
        }
    }
    
    $html = '
    <form name="receta" method="POST" id="form" action="recetas.php" enctype="multipart/form-data" onsubmit="return validarFormulario()">
        <input type="hidden" name="IdReceta" value="' . $IdReceta  . '">
        <input type="hidden" name="op" value="' . $op  . '">
        
        <table align="center" class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th colspan="2">DATOS RECETAS</th>
                </tr>
            </thead>
            <tr>
                <th>Paciente:</th>
                <td>' . $this->_get_combo_db("consultas", "IdConsulta", "Diagnostico", "IdConsulta", $this->idConsulta) . '</td>
            </tr>
            <tr>
                <th>Médicamento:</th>
                <td>' . $this->_get_combo_db("medicamentos", "IdMedicamento", "Nombre", "IdMedicamento", $this->idMedicamento) . '</td>
            </tr>
            <tr>
                <th>Cantidad:</th>
                <td><input type="text" size="15" name="Cantidad" value="' . $this->cantidad . '" required></td>
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
    <table border="1" align="center">
        <tr>
            <th colspan="6">Lista de Recetas</th>
        </tr>
        <tr>
            <th colspan="6"><a href="recetas.php?d=' . $d_new_final . '">Nueva Receta</a></th>
        </tr>
        <tr>
            <th>Paciente</th>
            <th>Medicamento</th>
            <th>Cantidad</th>
            <th colspan="3">Acciones</th>
        </tr>';
    
    $sql = "SELECT r.IdReceta, c.Diagnostico as Paciente, m.Nombre as Medicamento, r.Cantidad
            FROM recetas r
            JOIN consultas c ON r.IdConsulta = c.IdConsulta
            JOIN medicamentos m ON r.IdMedicamento = m.IdMedicamento;";
    
    $res = $this->conexion->query($sql);

    while ($row = $res->fetch_assoc()) {
        $d_del = "del/" . $row['IdReceta'];
        $d_del_final = base64_encode($d_del);
        $d_act = "act/" . $row['IdReceta'];
        $d_act_final = base64_encode($d_act);
        $d_det = "det/" . $row['IdReceta'];
        $d_det_final = base64_encode($d_det);

        $html .= '
        <tr>
            <td>' . $row['Paciente'] . '</td>
            <td>' . $row['Medicamento'] . '</td>
            <td>' . $row['Cantidad'] . '</td>
            <td><a href="recetas.php?d=' . $d_del_final . '">Borrar</a></td>
            <td><a href="recetas.php?d=' . $d_act_final . '">Actualizar</a></td>
            <td><a href="recetas.php?d=' . $d_det_final . '">Detalle</a></td>
        </tr>';
    }

    $html .= '  
    </table>';

    return $html;
}




	
	
public function get_detail_receta($idReceta) {
    $sql = "SELECT c.Nombre AS Paciente, m.Nombre AS Medicamento, r.Cantidad
            FROM recetas r
            JOIN consultas c ON r.IdConsulta = c.IdConsulta
            JOIN medicamentos m ON r.IdMedicamento = m.IdMedicamento
            WHERE r.IdReceta = $idReceta;";
    
    $res = $this->conexion->query($sql);
    $row = $res->fetch_assoc();
    
    $num = $res->num_rows;

    if ($num == 0) {
        $mensaje = "tratar de editar la receta con id= " . $idReceta;
        echo $this->_message_error($mensaje);
    } else {
        $html = '
        <table border="1" align="center">
            <tr>
                <th colspan="2">DATOS DE LA RECETA</th>
            </tr>
            <tr>
                <td>Paciente: </td>
                <td>'. $row['Paciente'] .'</td>
            </tr>
            <tr>
                <td>Medicamento: </td>
                <td>'. $row['Medicamento'] .'</td>
            </tr>
            <tr>
                <td>Cantidad: </td>
                <td>'. $row['Cantidad'] .'</td>
            </tr>
            <tr>
                <th colspan="2"><a href="recetas.php">Regresar</a></th>
            </tr>
        </table>';

        return $html;
    }
}

public function delete_receta($idReceta) {
    $sql = "DELETE FROM recetas WHERE IdReceta = $idReceta;";
    if ($this->conexion->query($sql)) {
        echo $this->_message_ok("ELIMINÓ");
    } else {
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
				<th><a href="recetas.php">Regresar</a></th>
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
				<th><a href="recetas.php">Regresar</a></th>
			</tr>
		</table>';
		return $html;
	}
	
//****************************************************************************	
	
} // FIN SCRPIT
?>


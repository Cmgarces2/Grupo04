<?php
class consulta {
    private $idConsulta;
    private $idMedico;
    private $idPaciente;
    private $fechaConsulta;
    private $horaInicio;
    private $horaFin;
    private $diagnostico;
    private $con;
	
	function __construct($cn){
		$this->con = $cn;
	}
		
	
//*********************** 3.1 METODO update_consulta() **************************************************	
	
public function update_consulta()
{
    $idConsulta = $_POST['IdConsulta'];
    $idMedico = $_POST['IdMedico'];
    $idPaciente = $_POST['IdPaciente'];
    $fechaConsulta = $_POST['FechaConsulta'];
    $horaInicio = $_POST['HI'];
    $horaFin = $_POST['HF'];
    $diagnostico = $_POST['Diagnostico'];

    $sql = "UPDATE consultas SET IdMedico = $idMedico,
                                IdPaciente = $idPaciente,
                                FechaConsulta = '$fechaConsulta',
                                HI = '$horaInicio',
                                HF = '$horaFin',
                                Diagnostico = '$diagnostico'
            WHERE IdConsulta = $idConsulta";

    if ($this->con->query($sql)) {
        echo $this->_message_ok("modificó");
    } else {
        echo $this->_message_error("al modificar");
    }
}

	

public function save_consulta()
{
    // Agrega una prueba de escritorio para verificar los datos del formulario
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    // Recopila los datos del formulario POST
    $idMedico = $_POST['IdMedicoCMB'];
    $idPaciente = $_POST['IdPacienteCMB'];
    $fechaConsulta = $_POST['FechaConsulta'];
    $horaInicio = $_POST['HI'];
    $horaFin = $_POST['HF'];
    $diagnostico = $_POST['Diagnostico'];

    // Prepara la consulta SQL para insertar una nueva consulta
    $sql = "INSERT INTO consultas (IdMedico, IdPaciente, FechaConsulta, HI, HF, Diagnostico) 
            VALUES ($idMedico, $idPaciente, '$fechaConsulta', '$horaInicio', '$horaFin', '$diagnostico')";

    if ($this->con->query($sql)) {
        echo $this->_message_ok("guardó");
    } else {
        echo $this->_message_error("guardar");
    }
}





	 /*Aquí se agregó el parámetro:  $defecto*/
	 private function _get_combo_db($tabla,$valor,$etiqueta,$nombre,$defecto){
		$html = '<select name="' . $nombre . '"id="pacienteSelect">';
		$sql = "SELECT $valor,$etiqueta FROM $tabla;";
		$res = $this->con->query($sql);
		while($row = $res->fetch_assoc()){
			//ImpResultQuery($row);
			$html .= ($defecto == $row[$valor])?'<option value="' . $row[$valor] . '" selected>' . $row[$etiqueta] . '</option>' . "\n" : '<option value="' . $row[$valor] . '">' . $row[$etiqueta] . '</option>' . "\n";
		}
		$html .= '</select>';
		return $html;
	}
	

public function get_form($id = NULL)
{
    if ($id == NULL) {
        $this->idConsulta = NULL;
        $this->idMedico = NULL;
        $this->idPaciente = NULL;
        $this->fechaConsulta = NULL;
        $this->horaInicio = NULL;
        $this->horaFin = NULL;
        $this->diagnostico = NULL;

        $flag = NULL;
        $op = "new";
    } else {

        $sql = "SELECT * FROM consultas WHERE IdConsulta = $id;";
        $res = $this->con->query($sql);
        $row = $res->fetch_assoc();

        $num = $res->num_rows;
        if ($num == 0) {
            $mensaje = "tratar de actualizar la consulta con IdConsulta = " . $id;
            echo $this->_message_error($mensaje);
        } else {
            // ***** TUPLA ENCONTRADA *****
            echo "<br>TUPLA <br>";
            echo "<pre>";
            print_r($row);
            echo "</pre>";

            $this->idMedico = $row['IdMedico'];
            $this->idPaciente = $row['IdPaciente'];
            $this->fechaConsulta = $row['FechaConsulta'];
            $this->horaInicio = $row['HI'];
            $this->horaFin = $row['HF'];
            $this->diagnostico = $row['Diagnostico'];

            $flag = "enabled";
            $op = "update";
        }
    }

    // Obtener opciones para el combo de pacientes (sustituye 'nombre_tabla_pacientes' por el nombre real de tu tabla)
    $optionsPacientes = $this->_get_combo_db('pacientes', 'IdPaciente', 'Nombre', 'IdPacienteCMB', $this->idPaciente);

    // Obtener opciones para el combo de médicos (sustituye 'nombre_tabla_medicos' por el nombre real de tu tabla)
    $optionsMedicos = $this->_get_combo_db('medicos', 'IdMedico', 'Nombre', 'IdMedicoCMB', $this->idMedico);

    $html = '
    <form name="consulta" method="POST" action="consultas.php">
    
    <input type="hidden" name="IdConsulta" value="' . $id  . '">
    <input type="hidden" name="op" value="' . $op  . '">
    
    <table border="1" align="center">
        <tr>
            <th colspan="2">DATOS CONSULTA</th>
        </tr>
        <tr>
            <td>ID Medico:</td>
            <td>' . $optionsMedicos . '</td>
        </tr>
        <tr>
            <td>ID Paciente:</td>
            <td>' . $optionsPacientes . '</td>
        </tr>
        <tr>
            <td>Fecha Consulta:</td>
            <td><input type="text" size="10" name="FechaConsulta" value="' . $this->fechaConsulta . '" required></td>
        </tr>   
        <tr>
            <td>Hora Inicio:</td>
            <td><input type="text" size="8" name="HI" value="' . $this->horaInicio . '" required></td>
        </tr>
        <tr>
            <td>Hora Fin:</td>
            <td><input type="text" size="8" name="HF" value="' . $this->horaFin . '" required></td>
        </tr>
        <tr>
            <td>Diagnóstico:</td>
            <td><input type="text" size="50" name="Diagnostico" value="' . $this->diagnostico . '" required></td>
        </tr>
        <tr>
            <th colspan="2"><input type="submit" name="Guardar" value="GUARDAR"></th>
        </tr>                                                
    </table>
    
    </form>';

    return $html;
}

public function get_list()
{
    $d_new = "new/0";
    $d_new_final = base64_encode($d_new);
    $html = '
    <table border="1" align="center">
        <tr>
            <th colspan="8">Lista de Consultas Médicas</th>
        </tr>
        <tr>
            <th colspan="8"><a href="consultas.php?d=' . $d_new_final . '">Nueva Consulta</a></th>
        </tr>
        <tr>
            <th>ID Consulta</th>
            <th>ID Médico</th>
            <th>ID Paciente</th>
            <th>Fecha Consulta</th>
            <th>Hora Inicio</th>
            <th>Hora Fin</th>
            <th>Diagnóstico</th>
            <th colspan="3">Acciones</th>
        </tr>';
    $sql = "SELECT c.IdConsulta, c.IdMedico, c.IdPaciente, c.FechaConsulta, c.HI, c.HF, c.Diagnostico, m.nombre as nombre_medico, p.nombre as nombre_paciente FROM consultas c, medicos m, pacientes p WHERE c.IdMedico=m.IdMedico AND c.IdPaciente=p.IdPaciente;";
    $res = $this->con->query($sql);
    while ($row = $res->fetch_assoc()) {
        $d_del = "del/" . $row['IdConsulta'];
        $d_del_final = base64_encode($d_del);
        $d_act = "act/" . $row['IdConsulta'];
        $d_act_final = base64_encode($d_act);
        $d_det = "det/" . $row['IdConsulta'];
        $d_det_final = base64_encode($d_det);
        $html .= '
            <tr>
                <td>' . $row['IdConsulta'] . '</td>
                <td>' . $row['nombre_medico'] . '</td>
                <td>' . $row['nombre_paciente'] . '</td>
                <td>' . $row['FechaConsulta'] . '</td>
                <td>' . $row['HI'] . '</td>
                <td>' . $row['HF'] . '</td>
                <td>' . $row['Diagnostico'] . '</td>
                <td><a href="consultas.php?d=' . $d_del_final . '">Borrar</a></td>
                <td><a href="consultas.php?d=' . $d_act_final . '">Actualizar</a></td>
                <td><a href="consultas.php?d=' . $d_det_final . '">Detalle</a></td>
            </tr>';
    }
    $html .= '  
    </table>';

    return $html;
}

	
public function get_detail_consulta($id)
{
    $sql = "SELECT c.IdConsulta, c.IdMedico, c.IdPaciente, c.FechaConsulta, c.HI, c.HF, c.Diagnostico, m.nombre as nombre_medico, p.nombre as nombre_paciente
            FROM consultas c, medicos m, pacientes p
            WHERE c.IdConsulta=$id AND c.IdMedico=m.IdMedico AND c.IdPaciente=p.IdPaciente;";
    $res = $this->con->query($sql);
    $row = $res->fetch_assoc();

    $num = $res->num_rows;

    if ($num == 0) {
        $mensaje = "tratar de editar la consulta médica con ID= " . $id;
        echo $this->_message_error($mensaje);
    } else {
        $html = '
        <table border="1" align="center">
            <tr>
                <th colspan="2">DETALLE DE CONSULTA MÉDICA</th>
            </tr>
            <tr>
                <td>ID Consulta: </td>
                <td>' . $row['IdConsulta'] . '</td>
            </tr>
            <tr>
                <td>Médico: </td>
                <td>' . $row['nombre_medico'] . '</td>
            </tr>
            <tr>
                <td>Paciente: </td>
                <td>' . $row['nombre_paciente'] . '</td>
            </tr>
            <tr>
                <td>Fecha de Consulta: </td>
                <td>' . $row['FechaConsulta'] . '</td>
            </tr>
            <tr>
                <td>Hora de Inicio: </td>
                <td>' . $row['HI'] . '</td>
            </tr>
            <tr>
                <td>Hora Fin: </td>
                <td>' . $row['HF'] . '</td>
            </tr>
            <tr>
                <td>Diagnóstico: </td>
                <td>' . $row['Diagnostico'] . '</td>
            </tr>
            <tr>
                <th colspan="2"><a href="consultas.php">Regresar</a></th>
            </tr>																						
        </table>';

        return $html;
    }
}

	
	
public function delete_consulta($id)
{
    $sql = "DELETE FROM consultas WHERE IdConsulta=$id;";
    if ($this->con->query($sql)) {
        echo $this->_message_ok("ELIMINÓ");
    } else {
        echo $this->_message_error("eliminar");
    }
}

	
	private function _message_error($tipo){
		$html = '
		<table border="0" align="center">
			<tr>
				<th>Error al ' . $tipo . '. El registro tiene asociada una consulta </th>
			</tr>
			<tr>
				<th><a href="consultas.php">Regresar</a></th>
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
				<th><a href="consultas.php">Regresar</a></th>
			</tr>
		</table>';
		return $html;
	}
	
//****************************************************************************	
	
} // FIN SCRPIT
?>


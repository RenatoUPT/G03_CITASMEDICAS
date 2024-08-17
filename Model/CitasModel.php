<?php

include_once __DIR__ . '../../conexion.php';

class CitasModel {
    private $conn;

    public function __construct() {
        $this->conn = conectarse();
    }

    public function agendarCita($especialidadID, $medicoID, $pacienteID, $fecha, $hora) {
        $estado = 'Programada';
        $activo = 1;

        $stmt = $this->conn->prepare("INSERT INTO citas (EspecialidadID, MedicoID, PacienteID, FechaAtencion, InicioAtencion, Estado, Activo) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiisssi", $especialidadID, $medicoID, $pacienteID, $fecha, $hora, $estado, $activo);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function cancelarCita($citaID) {
        $stmt = $this->conn->prepare("UPDATE citas SET Estado = 'Cancelada', Activo = 0 WHERE ID = ?");
        $stmt->bind_param("i", $citaID);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function ListarCitasMedicas() {
        $query = "SELECT citas.ID, especialidades.Nombre AS NombreEspecialidad, 
                         medicos.Nombres AS MedicoNombre, medicos.Apellidos AS MedicoApellido, 
                         pacientes.Nombres AS NombrePaciente, pacientes.Apellidos AS ApellidoPaciente, 
                         citas.FechaAtencion, citas.InicioAtencion, citas.Estado, citas.Activo
                  FROM citas
                  INNER JOIN especialidades ON especialidades.ID = citas.EspecialidadID
                  INNER JOIN medicos ON medicos.ID = citas.MedicoID
                  INNER JOIN pacientes ON pacientes.ID = citas.PacienteID
                  WHERE citas.Estado = 'Programada' AND citas.Activo = 1";
    
        $result = $this->conn->query($query);
        if ($result) {
            return $result;
        } else {
            return null;
        }
    }
    
    

    public function cerrarConexion() {
        $this->conn->close();
    }
}
?>

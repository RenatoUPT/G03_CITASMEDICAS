<?php
include_once __DIR__ . '/../conexion.php';

class CronogramaPacienteModel {
    private $conn;

    public function __construct() {
        $this->conn = conectarse();
    }

    public function obtenerCitasPorPaciente($pacienteID) {
        $stmt = $this->conn->prepare("SELECT c.FechaAtencion, e.Nombre AS Especialidad, CONCAT(m.Nombres, ' ', m.Apellidos) AS Doctor, c.InicioAtencion 
                                      FROM citas c 
                                      JOIN especialidades e ON c.EspecialidadID = e.ID 
                                      JOIN medicos m ON c.MedicoID = m.ID 
                                      WHERE c.PacienteID = ? AND c.Activo = 1");
        $stmt->bind_param("i", $pacienteID);
        $stmt->execute();
        $result = $stmt->get_result();
        $citas = [];
        while ($row = $result->fetch_assoc()) {
            $citas[] = $row;
        }
        $stmt->close();
        return $citas;
    }

    public function cerrarConexion() {
        $this->conn->close();
    }
}
?>

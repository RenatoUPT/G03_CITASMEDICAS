<?php
include_once __DIR__ . '/../conexion.php';

class PacienteModel {
    private $conn;
    private $table_name = "tbusuario";
    private $pacientes_table = "pacientes";

    public function __construct() {
        $this->conn = conectarse();
    }

    public function obtenerPacientePorDni($dni) {
        $query = "SELECT DNI, Nombres, Apellidos, Direccion, Telefono, Sexo, FechaNacimiento FROM $this->table_name WHERE DNI = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $dni);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }

    public function actualizarPaciente($data) {
        $dni = $data['ID'];
        $direccion = $data['Direccion'];
        $telefono = $data['Telefono'];
        $query = "UPDATE $this->pacientes_table SET Direccion = ?, Telefono = ? WHERE DNI = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sss", $direccion, $telefono, $dni);
        if ($stmt->execute()) {
            return true;
        } else {
            return $stmt->error;
        }
    }

    public function actualizarUsuario($data) {
        $dniusuario = $data['dniusuario'];
        $direccionusuario = $data['direccionusuario'];
        $telefonousuario = $data['telefonousuario'];
        $query = "UPDATE $this->table_name SET direccion = ?, telefono = ? WHERE dniusuario = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sss", $direccionusuario, $telefonousuario, $dniusuario);
        if ($stmt->execute()) {
            return true;
        } else {
            return $stmt->error;
        }
    }
}

?>

<?php
include_once __DIR__ . '/../conexion.php';

class PersonalModel {
    private $conn;
    private $table_name = "tbusuario";

    public function __construct() {
        $this->conn = conectarse();
    }

    public function obtenerPersonalPorDni($dni) {
        $query = "SELECT dniusuario, nombres, apellidos, contrasenia, correo, telefono, fechanacimiento, fk_idrol, direccion, sexo 
                  FROM $this->table_name WHERE dniusuario = ?";
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

    public function actualizarPersonal($dni, $direccion, $telefono) {
        $query = "UPDATE $this->table_name SET direccion = ?, telefono = ? WHERE dniusuario = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sss", $direccion, $telefono, $dni);
        return $stmt->execute();
    }
}
?>

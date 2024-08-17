<?php
include_once __DIR__ . '/../conexion.php';

class UsuarioModel {
    private $db;

    public function __construct() {
        $this->db = conectarse();
    }

    public function obtenerUsuarioPorDNIyContrasenia($dni, $contrasenia) {
        $stmt = $this->db->prepare("SELECT * FROM tbusuario WHERE dniusuario = ? AND contrasenia = ?");
        $stmt->bind_param("ss", $dni, $contrasenia);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }

    public function cerrarConexion() {
        $this->db->close();
    }
}
?>

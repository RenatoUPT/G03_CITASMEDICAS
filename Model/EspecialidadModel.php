<?php
    include_once __DIR__ . '/../conexion.php';

    class EspecialidadModel {
        private $conn;
        private $table_name = "medicos_especialidades";

        public function __construct() {
            $this->conn = conectarse();
        }

        public function insertEspecialidad($data) {
            $medicoid = $data['MedicoID'];
            $especialidadid = $data['EspecialidadID'];
            $fecha_reg = $data['FechaRegistro'];

            $query = "INSERT INTO " . $this->table_name . " 
                    (MedicoID, EspecialidadID, FechaRegistro, Activo)
                    VALUES (?, ?, ?, 1)";

            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("sss", $medicoid, $especialidadid, $fecha_reg);

            if ($stmt->execute()) {
                return true;
            } else {
                return json_encode(["success" => false, "message" => "Error: " . $this->conn->error]);
            }
        }

        public function updateEspecialidad($data) {
            $ID = $data['ID'];
            $especialidad = $data['EspecialidadID'];
            $fecha_modi = $data['FechaModificacion'];
            $estado = $data['Activo'];
        
            $query = "UPDATE " . $this->table_name . " SET 
                    EspecialidadID = ?,
                    FechaModificacion = ?,
                    Activo = ?
                    WHERE ID = ?";
        
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ssss", $especialidad, $fecha_modi, $estado, $ID);
        
            if ($stmt->execute()) {
                return true;
            } else {
                return json_encode(["success" => false, "message" => "Error: " . $this->conn->error]);
            }
        }

        public function listarEspecialidad() {
            $query = "SELECT medicos_especialidades.ID,medicos.Nombres,medicos.Apellidos,especialidades.Nombre,especialidades.Descripcion,medicos_especialidades.FechaRegistro,medicos_especialidades.FechaModificacion,medicos_especialidades.Activo FROM medicos_especialidades
                    INNER JOIN medicos
                    ON medicos.ID = medicos_especialidades.MedicoID
                    INNER JOIN especialidades
                    ON especialidades.ID = medicos_especialidades.EspecialidadID";
            $result = $this->conn->query($query);
        
            $especialidades = array();
        
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $especialidades[] = $row;
                }
            }
        
            return $especialidades;
        }

        public function obtenerMedicos() {
            $query = "SELECT Nombres, Apellidos,ID FROM medicos";
            $result = $this->conn->query($query);
        
            $medicos = array();
        
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $medicos[] = $row;
                }
            }
        
            return $medicos;
        }

        public function obtenerEspecialidades() {
            $query = "SELECT Nombre,Descripcion,ID FROM especialidades";
            $result = $this->conn->query($query);
        
            $especialidades = array();
        
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $especialidades[] = $row;
                }
            }
        
            return $especialidades;
        }
    }
?>

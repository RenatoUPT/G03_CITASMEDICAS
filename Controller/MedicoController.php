<?php
include_once '../../Model/MedicoModel.php';

class MedicoController {
    private $medicoModel;

    public function __construct() {
        $this->medicoModel = new MedicoModel();
    }

    public function insertarDoctor($data) {
        return $this->medicoModel->insertarDoctor($data);
    }

    public function obtenerDetalleDoctor($id) {
        return $this->medicoModel->obtenerDetalleDoctor($id);
    }

    public function updateDoctor($data) {
        return $this->medicoModel->updateDoctor($data);
    }

    public function listarDoctores() {
        return $this->medicoModel->listarDoctores();
    }

    //METODO DE HORARIOS

    public function listarHorarios(){
        return $this->medicoModel->listarHorarios();
    }

    public function horarioDoctor($data){
        return $this->medicoModel->horarioDoctor($data);
    }

    public function eliminarHorario($horarioID){
        return $this->medicoModel->eliminarHorario($horarioID);
    }

    public function obtenerDetalleHorario($id){
        return $this->medicoModel->obtenerDetalleHorario($id);
    }

}

// Manejo de la solicitud AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'getDoctorDetails') {
    $id = $_POST['id'];
    $controller = new MedicoController();
    $doctorDetails = $controller->obtenerDetalleDoctor($id);
    echo json_encode($doctorDetails);
    exit();
}else if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'getHorarioDetails'){
    $id = $_POST['id'];
    $controller = new MedicoController();
    $horarioDetails = $controller->obtenerDetalleHorario($id);
    echo json_encode($horarioDetails);
    exit();
}
?>

<?php
include_once '../Model/PersonalModel.php';

class PersonalController {
    private $model;

    public function __construct() {
        $this->model = new PersonalModel();
    }

    public function actualizarPersonal() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $dni = $_POST['dni'];
            $direccion = $_POST['direccion'];
            $telefono = $_POST['telefono'];

            if (empty($dni) || empty($direccion) || empty($telefono)) {
                $message = "Todos los campos son obligatorios.";
            } else {
                $result = $this->model->actualizarPersonal($dni, $direccion, $telefono);
                if ($result == true) {
                    header("Location: ../Views/PersonalMedico/indexPersonal.php");
                    exit();
                } else {
                    echo $result;
                }
            }

        }
    }
}
$controller = new PersonalController();
$controller->actualizarPersonal();
?>

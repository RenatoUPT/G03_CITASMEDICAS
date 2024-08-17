<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . '/../Model/CitasModel.php';

class CitaController {
    private $citasModel;

    public function __construct()
    {
        $this->citasModel = new CitasModel();
    }

    public function ListarCitasMedicas(){
        return $this->citasModel->ListarCitasMedicas();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $citasModel = new CitasModel();

    if (isset($_POST['especialidad']) && isset($_POST['doctor']) && isset($_POST['fecha']) && isset($_POST['hora'])) {
        // Agendar cita
        $especialidad = $_POST['especialidad'];
        $doctor = $_POST['doctor'];
        $fecha = $_POST['fecha'];
        $hora = $_POST['hora'];
        $pacienteID = $_SESSION['userId']; 

        $resultado = $citasModel->agendarCita($especialidad, $doctor, $pacienteID, $fecha, $hora);

        if ($resultado) {
            header("Location: ../Views/Paciente/agendarCita.php?success=Cita agendada correctamente");
        } else {
            header("Location: ../Views/Paciente/agendarCita.php?error=Error al agendar la cita");
        }
    } elseif (isset($_POST['cancelarCita'])) {
        // Cancelar cita
        $citaID = $_POST['cancelarCita'];
        $resultado = $citasModel->cancelarCita($citaID);

        if ($resultado) {
            header("Location: ../Views/Paciente/agendarCita.php?success=Cita cancelada correctamente");
        } else {
            header("Location: ../Views/Paciente/agendarCita.php?error=Error al cancelar la cita");
        }
    } else {
        header("Location: ../Views/Paciente/agendarCita.php?error=Datos incompletos");
    }

    $citasModel->cerrarConexion();
}
?>

<?php
include_once '../Model/EspecialidadModel.php';

$especialidadModel = new EspecialidadModel();

// Procesar el formulario de registro o actualización
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['registrar'])) {
        // Si se envió el formulario de registro
        $medicoid = $_POST['medico'];
        $especialidadID = $_POST['especialidad'];
        $fechaRegistro = $_POST['fecha_reg'];
        
        $data = array(
            "MedicoID" => $medicoid,
            "EspecialidadID" => $especialidadID,
            "FechaRegistro" => $fechaRegistro
        );

        // Insertar la especialidad
        $resultado = $especialidadModel->insertEspecialidad($data);

        if ($resultado === true) {
            header("Location: ../Views/PersonalMedico/administrarespecialidad.php");
            exit(); // Asegurarse de que el script se detenga después de redirigir
        } else {
            echo "Error al registrar la especialidad: " . $resultado;
        }
    } elseif (isset($_POST['accion']) && $_POST['accion'] === 'editar') {
        // Si se envió el formulario de edición
        $ID = $_POST['ID'];
        $especialidadID = $_POST['especialidad'];
        $fechaModificacion = $_POST['fecha_modi'];
        $estado = $_POST['estado'];
        
        $data = array(
            "ID" => $ID,
            "EspecialidadID" => $especialidadID,
            "FechaModificacion" => $fechaModificacion,
            "Activo" => $estado
        );

        // Actualizar la especialidad
        $resultado = $especialidadModel->updateEspecialidad($data);

        if ($resultado === true) {
            header("Location: ../Views/PersonalMedico/administrarespecialidad.php");
            exit(); // Asegurarse de que el script se detenga después de redirigir
        } else {
            echo "Error al actualizar la especialidad: " . $resultado;
        }
    }
}

$especialidades = $especialidadModel->listarEspecialidad();
?>

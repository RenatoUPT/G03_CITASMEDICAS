<?php
include_once '../Model/PacienteModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pacienteModel = new PacienteModel();

    $dataUsuario = [
        'dniusuario' => $_POST['dniusuario'],
        'direccionusuario' => $_POST['direccionusuario'],
        'telefonousuario' => $_POST['telefonousuario']
    ];
    $resultadoUsuario = $pacienteModel->actualizarUsuario($dataUsuario);

    if($resultadoUsuario === true){   // Datos para la tabla pacientes
        $dataPaciente = [
            'ID' => $_POST['dniusuario'], // Usando DNI como clave principal
            'Direccion' => $_POST['direccionusuario'],
            'Telefono' => $_POST['telefonousuario']
        ];
        $resultadoPaciente = $pacienteModel->actualizarPaciente($dataPaciente);

        if ($resultadoPaciente === true) {
            header('Location: ../Views/Paciente/indexPaciente.php');
            exit();
        } else {
            echo $resultadoPaciente;
        }
    }else{
        echo $resultadoUsuario;
    }
}
?>

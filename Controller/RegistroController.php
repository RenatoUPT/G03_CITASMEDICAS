<?php
include("../Model/RegistroModel.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $registroModel = new RegistroModel();

    // Datos para la tabla tbusuario
    $dataUsuario = [
        'dniusuario' => $_POST['dniusuario'],
        'nombreusuario' => $_POST['nombreusuario'],
        'apellidousuario' => $_POST['apellidousuario'],
        'direccionusuario' => $_POST['direccionusuario'],
        'telefonousuario' => $_POST['telefonousuario'],
        'fechanacimientousuario' => $_POST['fechanacimientousuario'],
        'sexousuario' => $_POST['sexousuario'],
        'correousuario' => $_POST['correousuario'],
        'contrasenia' => $_POST['contrasenia']
    ];

    $resultadoUsuario = $registroModel->insertUser($dataUsuario);

    if ($resultadoUsuario === true) {
        // Datos para la tabla pacientes
        $dataPaciente = [
            'ID' => $_POST['dniusuario'], // Asegurando que el ID también sea el DNI
            'nombres' => $_POST['nombreusuario'],
            'apellidos' => $_POST['apellidousuario'],
            'dni' => $_POST['dniusuario'],
            'direccion' => $_POST['direccionusuario'],
            'telefono' => $_POST['telefonousuario'],
            'sexo' => $_POST['sexousuario'],
            'fechaNacimiento' => $_POST['fechanacimientousuario'],
            'activo' => 1 // Suponiendo que siempre se inserta como activo
        ];

        $resultadoPaciente = $registroModel->insertPaciente($dataPaciente);

        if ($resultadoPaciente === true) {
            header('Location: ../index.php');
            exit();
        } else {
            echo $resultadoPaciente;
        }
    } else {
        echo $resultadoUsuario;
    }
}
?>

<?php
session_start();
include("../Model/UsuarioModel.php");

function login($dniusuario, $contrasenia) {
    $usuarioModel = new UsuarioModel();
    $usuario = $usuarioModel->obtenerUsuarioPorDNIyContrasenia($dniusuario, $contrasenia);

    if ($usuario) {
        $_SESSION['userId'] = $usuario['dniusuario'];
        $_SESSION['rol'] = $usuario['fk_idrol'];

        switch ($usuario['fk_idrol']) {
            case 1:
                header('Location: ../Views/PersonalMedico/indexPersonal.php');
                break;
            case 2: 
                header('Location: ../Views/Paciente/indexPaciente.php');
                break;
            default:
                header('Location: ../error.php?msg=Rol no reconocido');
                break;
        }
    } else {
        header('Location: ../index.php?error=Usuario o contraseña incorrecta');
    }
    $usuarioModel->cerrarConexion();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['dniusuario']) && isset($_POST['contrasenia'])) {
        login($_POST['dniusuario'], $_POST['contrasenia']);
    } else {
        header('Location: ../index.php?error=Datos incompletos');
    }
}
?>

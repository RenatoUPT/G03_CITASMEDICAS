<?php
session_start();
include_once '../Model/CronogramaPacienteModel.php';

$pacienteID = $_SESSION['userId'];
$model = new CronogramaPacienteModel();
$citas = $model->obtenerCitasPorPaciente($pacienteID);

echo json_encode($citas);

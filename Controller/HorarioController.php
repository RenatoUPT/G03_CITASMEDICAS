<?php
include_once("../conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $doctor_id = $_POST['doctor_id'];
    $fecha = $_POST['fecha'];

    $conn = conectarse();
    $result = $conn->query("SELECT * FROM horarios WHERE MedicoID = $doctor_id AND FechaAtencion = '$fecha' AND Activo = 1");

    if ($result->num_rows > 0) {
        echo '<option value="">Seleccione una hora</option>';
        while ($row = $result->fetch_assoc()) {
            echo '<option value="' . $row['InicioAtencion'] . '">' . $row['InicioAtencion'] . ' - ' . $row['FinAtencion'] . '</option>';
        }
    } else {
        echo '<option value="">No hay horarios disponibles</option>';
    }

    $conn->close();
}
?>

<?php
include_once("../conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $especialidad_id = $_POST['especialidad_id'];

    $conn = conectarse();
    $result = $conn->query("SELECT m.ID, m.Nombres, m.Apellidos FROM medicos m JOIN medicos_especialidades me ON m.ID = me.MedicoID WHERE me.EspecialidadID = $especialidad_id AND m.Activo = 1");

    if ($result->num_rows > 0) {
        echo '<option value="">Seleccione un doctor</option>';
        while ($row = $result->fetch_assoc()) {
            echo '<option value="' . $row['ID'] . '">' . $row['Nombres'] . ' ' . $row['Apellidos'] . '</option>';
        }
    } else {
        echo '<option value="">No hay doctores disponibles</option>';
    }

    $conn->close();
}
?>
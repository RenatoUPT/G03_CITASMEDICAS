<?php
function conectarse() {
    $link = new mysqli("161.132.48.189", "renato", "renatomardinez", "essaludbd1");
    if ($link->connect_errno) {
        die("Error al conectar a la base de datos: " . $link->connect_error);
    }
    return $link;
}
?>

<?php

$archivo = 'datos.txt';
$datos = file($archivo, FILE_IGNORE_NEW_LINES);

echo '<table border="1">';
echo '<tr><th>Nombre</th><th>Correo Electrónico</th></tr>';

foreach ($datos as $linea) {
    list($nombre, $correo) = explode(', ', $linea);
    echo "<tr><td>$nombre</td><td>$correo</td></tr>";
}

echo '</table>';
?>

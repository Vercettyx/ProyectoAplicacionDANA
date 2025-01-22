<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "formulario_dana";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$mensaje = isset($_POST['mensaje']) ? $_POST['mensaje'] : '';

$sql = "INSERT INTO mensajes (nombre, email, mensaje) VALUES ('$nombre', '$email', '$mensaje')";

if ($conn->query($sql) === TRUE) {
    echo "<h2>Gracias por tu mensaje. Se ha enviado correctamente.</h2>";
    echo "<a href='contacto.html'>Volver al formulario</a>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>

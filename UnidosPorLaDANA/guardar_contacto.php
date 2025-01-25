<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "";
$database = "formulario_dana";

$ip = $_SERVER['REMOTE_ADDR'];
$captcha = isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : '';
$secretkey = "6LeLRMIqAAAAAMXNvVwBuvW-3P6OXRk0kphP9jnB";

$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretkey&response=$captcha&remoteip=$ip");
$atributos = json_decode($response, true);

if (!$atributos['success']) {
    header("Location: activa_captcha.html");
    exit;
}

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("<h3 style='color: red; text-align: center;'>Error de conexión: " . $conn->connect_error . "</h3>");
}

$nombre = $conn->real_escape_string(trim($_POST['nombre'] ?? ''));
$email = $conn->real_escape_string(trim($_POST['email'] ?? ''));
$mensaje = $conn->real_escape_string(trim($_POST['mensaje'] ?? ''));

if (empty($nombre) || empty($email) || empty($mensaje)) {
    echo "<h3 style='color: red; text-align: center;'>Por favor, completa todos los campos.</h3>";
    echo "<a href='contacto.html' style='display: block; text-align: center; margin-top: 10px;'>Volver al formulario</a>";
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<h3 style='color: red; text-align: center;'>Por favor, proporciona un correo electrónico válido.</h3>";
    echo "<a href='contacto.html' style='display: block; text-align: center; margin-top: 10px;'>Volver al formulario</a>";
    exit;
}

$stmt = $conn->prepare("INSERT INTO mensajes (nombre, email, mensaje) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $nombre, $email, $mensaje);

if ($stmt->execute()) {
    header("Location: formulario_enviado.html");
    exit;
} else {
    error_log("Error SQL: " . $stmt->error);
    echo "<h3 style='color: red; text-align: center;'>Hubo un problema al enviar tu mensaje. Inténtalo más tarde.</h3>";
}

$stmt->close();
$conn->close();
?>

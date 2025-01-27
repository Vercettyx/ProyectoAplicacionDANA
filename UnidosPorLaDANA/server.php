<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = 'localhost';
$dbname = 'usuarios';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Error de conexión a la base de datos: ' . $conn->connect_error]));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'register') {

        $user = $_POST['username'];
        $mail = $_POST['email'];
        $pass = password_hash($_POST['password'], PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO usuarios (username, email, password) VALUES (?, ?, ?)");
        if (!$stmt) {
            die(json_encode(['status' => 'error', 'message' => 'Error al preparar la consulta: ' . $conn->error]));
        }

        $stmt->bind_param("sss", $user, $mail, $pass);

        if ($stmt->execute()) {
            header("Location: login.html");
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al registrar usuario: ' . $conn->error]);
        }

        $stmt->close();
    } elseif ($action === 'login') {

        $mail = $_POST['email'];
        $pass = $_POST['password'];

        $stmt = $conn->prepare("SELECT password FROM usuarios WHERE email = ?");
        if (!$stmt) {
            die(json_encode(['status' => 'error', 'message' => 'Error al preparar la consulta: ' . $conn->error]));
        }

        $stmt->bind_param("s", $mail);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($hashedPassword);
            $stmt->fetch();

            if (password_verify($pass, $hashedPassword)) {
                header("Location: index.html");
            } else {
                header("Location: contrasena_incorrecta.html");
            }
        } else {
            header("Location: correo_no_encontrado.html");
        }

        $stmt->close();
    }
}

$conn->close();
?>

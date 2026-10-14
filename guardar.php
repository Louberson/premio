<?php

require_once "conexion.php";

// Solo aceptar solicitudes POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    die("Método no permitido.");
}


// ========================================
// RECIBIR DATOS
// ========================================

$nombre = trim($_POST["nombre"] ?? "");
$correo = trim($_POST["correo"] ?? "");
$telefono = trim($_POST["telefono"] ?? "");
$password = $_POST["password"] ?? "";


// ========================================
// VALIDAR CAMPOS
// ========================================

if ($nombre === "" || $correo === "" || $password === "") {
    header("Location: premio.html?error=campos");
    exit;
}


// ========================================
// VALIDAR CORREO
// ========================================

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    header("Location: premio.html?error=correo");
    exit;
}


// ========================================
// VALIDAR CONTRASEÑA
// ========================================

if (strlen($password) < 8) {
    header("Location: premio.html?error=password");
    exit;
}


// ========================================
// CREAR HASH SEGURO
// ========================================


// ========================================
// INSERTAR EN LA BASE DE DATOS
// ========================================

try {

    $sql = "
        INSERT INTO usuario
        (
            nombre,
            correo,
            telefono,
            contrasena
        )
        VALUES
        (
            :nombre,
            :correo,
            :telefono,
            :contrasena
        )
    ";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([
        ":nombre" => $nombre,
        ":correo" => $correo,
        ":telefono" => $telefono,
        ":contrasena" => $password
    ]);


    // Registro correcto
    header("Location: premio.html?guardado=1");
    exit;


} catch (PDOException $e) {

    // Correo ya registrado
    if ($e->getCode() === "23000") {
        header("Location: premio.html?error=duplicado");
        exit;
    }


    // Mostrar el error real mientras estamos probando
    http_response_code(500);

    echo "<h2>Error al guardar el registro</h2>";
    echo "<p>MySQL respondió:</p>";

    echo "<pre>";
    echo htmlspecialchars($e->getMessage());
    echo "</pre>";

    exit;
}

?>


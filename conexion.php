<?php
$host = getenv("MYSQLHOST") ?: "127.0.0.1";
$port = getenv("MYSQLPORT") ?: "3306";
$dbname = getenv("MYSQL_DATABASE") ?: "premio";
$user = getenv("MYSQLUSER") ?: "root";
$password = getenv("MYSQLPASSWORD") ?: "";


try {
    $conexion = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
        $user,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    error_log("Error de conexión PDO: " . $e->getMessage());
    http_response_code(500);
    die("Error de conexión con la base de datos.");
}
?>
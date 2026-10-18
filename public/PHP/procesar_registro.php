<?php
// Recibe los datos de registro.html y crea el usuario nuevo
session_start();
include "includes/conexion.php";

$nombre = mysqli_real_escape_string($conexion, trim($_POST["nombre"]));
$correo = mysqli_real_escape_string($conexion, trim($_POST["correo"]));
$institucion = mysqli_real_escape_string($conexion, trim($_POST["institucion"]));
$password = $_POST["password"];

// Validación (el formulario ya controla que no haya campos vacíos y que el correo sea válido)
if (strlen($password) < 6) {
    mostrar_error("La contraseña debe tener al menos 6 caracteres");
}

// Revisamos que el correo no esté usado
$sql = "SELECT id FROM usuarios WHERE correo = '$correo'";
$resultado = mysqli_query($conexion, $sql);
if (mysqli_num_rows($resultado) > 0) {
    mostrar_error("Ese correo ya está registrado");
}

// La contraseña NUNCA se guarda tal cual: se guarda "encriptada"
$password_hash = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO usuarios (nombre, correo, institucion, password_hash)
        VALUES ('$nombre', '$correo', '$institucion', '$password_hash')";
mysqli_query($conexion, $sql);

// Iniciamos sesión directamente
$_SESSION["usuario_id"] = mysqli_insert_id($conexion);
$_SESSION["usuario_nombre"] = $nombre;
header("Location: inicio.php");
exit;
?>

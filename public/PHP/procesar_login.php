<?php
// Recibe los datos de login.html y revisa si el usuario existe
session_start();
include "includes/conexion.php";

$correo = mysqli_real_escape_string($conexion, trim($_POST["correo"]));
$password = $_POST["password"];

// Buscamos el usuario por su correo
$sql = "SELECT * FROM usuarios WHERE correo = '$correo'";
$resultado = mysqli_query($conexion, $sql);
$usuario = mysqli_fetch_assoc($resultado);

// Si existe y la contraseña coincide, guardamos sus datos en la sesión
if ($usuario && password_verify($password, $usuario["password_hash"])) {
    $_SESSION["usuario_id"] = $usuario["id"];
    $_SESSION["usuario_nombre"] = $usuario["nombre"];
    header("Location: inicio.php");
    exit;
} else {
    mostrar_error("Correo o contraseña incorrectos");
}
?>

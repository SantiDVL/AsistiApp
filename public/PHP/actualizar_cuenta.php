<?php
// =====================================================
// actualizar_cuenta.php: recibe el formulario de cuenta_editar.php
// y modifica los datos del usuario (UPDATE).
// No muestra pantalla: guarda y redirige a cuenta.php.
// =====================================================

// Conexión a la base de datos y funciones comunes
include "includes/conexion.php";
// Si no hay sesión iniciada, manda al login; también crea $usuario_id
include "includes/verificar_sesion.php";

// Leemos los datos del formulario ($_POST). trim() saca espacios sobrantes y
// mysqli_real_escape_string() prepara el texto para el SQL (evita que comillas rompan la consulta).
$nombre = mysqli_real_escape_string($conexion, trim($_POST["nombre"]));
$correo = mysqli_real_escape_string($conexion, trim($_POST["correo"]));
$institucion = mysqli_real_escape_string($conexion, trim($_POST["institucion"]));
$telefono = mysqli_real_escape_string($conexion, trim($_POST["telefono"]));

// El correo no puede ser el de otro usuario.
// "id <> $usuario_id" significa "con un id distinto al mío": así no choca con mi propio correo actual.
$sql = "SELECT id FROM usuarios WHERE correo = '$correo' AND id <> $usuario_id";
// Si la consulta devuelve alguna fila, otro usuario ya usa ese correo
if (mysqli_num_rows(mysqli_query($conexion, $sql)) > 0) {
    mostrar_error("Ese correo ya está en uso");
}

// Modificamos al usuario (UPDATE cambia los datos de una fila que ya existe).
// WHERE id = $usuario_id asegura que solo se modifique la cuenta de quien inició sesión.
$sql = "UPDATE usuarios
        SET nombre = '$nombre', correo = '$correo', institucion = '$institucion', telefono = '$telefono'
        WHERE id = $usuario_id";
mysqli_query($conexion, $sql);

// Actualizamos el nombre guardado en la sesión, para que el saludo de inicio.php cambie enseguida
$_SESSION["usuario_nombre"] = $nombre;

// Volvemos a la pantalla de la cuenta
header("Location: cuenta.php");
// exit corta la página para que no siga ejecutando código
exit;
?>

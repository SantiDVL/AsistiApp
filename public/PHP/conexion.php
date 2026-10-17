<?php
// Conexión a la base de datos (se incluye en todas las páginas que usan MySQL)
// Si tu MySQL tiene contraseña, cambiala en el tercer parámetro.
$conexion = mysqli_connect("localhost", "root", "", "asistiapp");

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
mysqli_set_charset($conexion, "utf8mb4");

// Función para mostrar texto de forma segura en la página
// (evita que alguien meta código HTML o JavaScript en un nombre)
function escapar($texto) {
    return htmlspecialchars($texto);
}

// Función para mostrar un mensaje de error y cortar la página
function mostrar_error($mensaje) {
    echo "<link rel='stylesheet' href='style.css'>";
    echo "<div class='error-box'>";
    echo "<div class='error'>" . $mensaje . "</div>";
    echo "<a class='btn btn-outline' href='javascript:history.back()'>Volver</a>";
    echo "</div>";
    exit;
}
?>

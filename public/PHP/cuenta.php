<?php
// Conecta con la base de datos (crea la variable $conexion) y trae las funciones comunes (escapar, mostrar_error)
include "includes/conexion.php";
// Si no hay sesión iniciada manda al login; si la hay, crea $usuario_id con el número del docente
include "includes/verificar_sesion.php";

// Traemos los datos del usuario que inició sesión (WHERE id = $usuario_id)
$sql = "SELECT * FROM usuarios WHERE id = $usuario_id";
// Guardamos sus datos como array: $usuario["nombre"], $usuario["correo"], etc.
$usuario = mysqli_fetch_assoc(mysqli_query($conexion, $sql));

// La primera letra del nombre, para el círculo del avatar
// substr($texto, 0, 1) toma 1 letra desde la posición 0 (la primera); strtoupper() la pasa a mayúscula
$inicial = strtoupper(substr($usuario["nombre"], 0, 1));

// Datos que usa header.php: $titulo es el texto de la pestaña y $activo marca la opción seleccionada del menú
$titulo = "Cuenta";
$activo = "cuenta";
// Dibuja la parte de arriba de la página (la estructura y el menú lateral)
include "includes/header.php";
?>

<!-- Título de la página -->
<div class="page-head"><h2>Cuenta</h2></div>

<!-- Tarjeta de perfil: círculo con la inicial, nombre y correo -->
<div class="card profile">
    <div class="avatar"><?php echo $inicial; ?></div>
    <div>
        <h3><?php echo $usuario["nombre"]; ?></h3>
        <small><?php echo $usuario["correo"]; ?></small>
    </div>
</div>

<!-- Fila que lleva al formulario para editar los datos personales; muestra la institución -->
<a class="card row" href="cuenta_editar.php">
    <span>
        <span class="name">Editar datos personales</span><br>
        <small><?php echo $usuario["institucion"]; ?></small>
    </span>
    <span class="pill">Editar</span>
</a>

<!-- Botón para cerrar sesión: onclick con confirm() pide confirmación y, si se acepta, abre logout.php -->
<div class="btn-row">
    <a class="btn btn-outline" href="logout.php" onclick="return confirm('¿Cerrar sesión?');">Cerrar sesión</a>
</div>

<!-- Cierra la página: termina el contenido y carga app.js -->
<?php include "includes/footer.php"; ?>

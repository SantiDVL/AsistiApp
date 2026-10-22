<?php
// Conecta con la base de datos (crea la variable $conexion) y trae las funciones comunes (escapar, mostrar_error)
include "includes/conexion.php";
// Si no hay sesión iniciada manda al login; si la hay, crea $usuario_id con el número del docente
include "includes/verificar_sesion.php";

// Número del alumno que viene en la dirección (alumno.php?id=5). (int) lo convierte a número
$id = (int) $_GET["id"];

// Buscamos el alumno y el nombre de su curso
// INNER JOIN une cada alumno con su curso para traer también el nombre del curso (AS curso_nombre).
// WHERE alumnos.id = $id deja solo al alumno pedido.
$sql = "SELECT alumnos.*, cursos.nombre AS curso_nombre
        FROM alumnos
        INNER JOIN cursos ON cursos.id = alumnos.curso_id
        WHERE alumnos.id = $id";
// Ejecuta la consulta y guarda al alumno como array: $alumno["nombre"], $alumno["dni"], etc.
$alumno = mysqli_fetch_assoc(mysqli_query($conexion, $sql));

// Contamos sus asistencias y faltas
// SUM(estado = 'presente') suma 1 por cada asistencia presente (la comparación da 1 o 0); lo mismo con "ausente".
// WHERE alumno_id = $id cuenta solo los registros de este alumno.
$sql = "SELECT
            SUM(estado = 'presente') AS presentes,
            SUM(estado = 'ausente') AS ausentes
        FROM asistencias WHERE alumno_id = $id";
$conteo = mysqli_fetch_assoc(mysqli_query($conexion, $sql));
// (int) convierte el resultado en número: si el alumno no tiene registros, SUM da vacío y queda en 0
$presentes = (int) $conteo["presentes"];
$ausentes = (int) $conteo["ausentes"];

// Datos que usa header.php: $titulo es el texto de la pestaña (acá, el nombre del alumno)
// y $activo marca la opción seleccionada del menú
$titulo = $alumno["nombre"];
$activo = "cursos";
// Dibuja la parte de arriba de la página (la estructura y el menú lateral)
include "includes/header.php";
?>

<!-- Encabezado: nombre y curso del alumno, con botón para editarlo -->
<div class="page-head">
    <div>
        <h2><?php echo $alumno["nombre"]; ?></h2>
        <small><?php echo $alumno["curso_nombre"]; ?></small>
    </div>
    <a class="btn btn-outline" href="alumno_editar.php?id=<?php echo $id; ?>">Editar</a>
</div>

<!-- Dos tarjetas con los totales: asistencias (presentes) y faltas (ausentes) -->
<div class="grid-2">
    <div class="card"><div class="stat-label">Asistencias</div><div class="stat"><?php echo $presentes; ?></div></div>
    <div class="card"><div class="stat-label">Faltas</div><div class="stat"><?php echo $ausentes; ?></div></div>
</div>

<!-- Datos del alumno: DNI, correo y curso -->
<div class="grid-2 datos">
    <div class="field"><label>DNI</label><div class="card"><?php echo $alumno["dni"]; ?></div></div>
    <div class="field"><label>Correo del alumno</label><div class="card"><?php echo $alumno["correo_alumno"]; ?></div></div>
    <div class="field"><label>Curso</label><div class="card"><?php echo $alumno["curso_nombre"]; ?></div></div>
</div>

<!-- Botones: volver al curso y eliminar al alumno -->
<div class="btn-row">
    <a class="btn btn-outline" href="curso_detalle.php?id=<?php echo $alumno["curso_id"]; ?>">Volver</a>

    <!-- confirm() muestra un cuadro "Aceptar / Cancelar": el formulario solo se envía si se acepta -->
    <form action="eliminar_alumno.php" method="post"
        onsubmit="return confirm('¿Seguro que querés eliminar a este alumno?');">
        <!-- Campo oculto con el número del alumno a eliminar -->
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <button class="btn btn-outline">Eliminar alumno</button>
    </form>
</div>

<!-- Cierra la página: termina el contenido y carga app.js -->
<?php include "includes/footer.php"; ?>

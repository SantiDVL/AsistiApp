<?php
// Carga la conexión y verifica que el usuario haya iniciado sesión.
include "includes/conexion.php";
include "includes/verificar_sesion.php";

// Obtiene el identificador del curso enviado por el formulario; si falta, usa 0.
$curso_id = (int) ($_POST["curso_id"] ?? 0);
// Un identificador menor o igual a cero no es válido, así que vuelve a la lista.
if ($curso_id <= 0) {
    header("Location: cursos.php");
    exit;
}

// Busca el curso y comprueba que pertenezca al usuario actual.
$sql = "SELECT * FROM cursos WHERE id = $curso_id AND usuario_id = $usuario_id";
$resultado = mysqli_query($conexion, $sql);
// Si el curso no existe o es de otra cuenta, vuelve a la lista de cursos.
if (mysqli_num_rows($resultado) == 0) {
    header("Location: cursos.php");
    exit;
}
// Guarda los datos del curso encontrado.
$curso = mysqli_fetch_assoc($resultado);

// Lee los estados recibidos y prepara los totales y la lista para la confirmación.
$estados = $_POST["estado"] ?? [];
$presentes = 0;
$ausentes = 0;
$lista = [];

// Obtiene los alumnos del curso en orden alfabético.
$sql = "SELECT id, nombre FROM alumnos WHERE curso_id = $curso_id ORDER BY nombre";
$resultado = mysqli_query($conexion, $sql);
// Revisa a cada alumno y decide si figura presente o ausente.
while ($alumno = mysqli_fetch_assoc($resultado)) {
    // Si el formulario especificó "ausente", conserva ese estado; de lo contrario, usa "presente".
    $estado = "presente";
    if (isset($estados[$alumno["id"]]) && $estados[$alumno["id"]] == "ausente") {
        $estado = "ausente";
    }

    // Añade a la lista los datos necesarios para mostrar y confirmar la asistencia.
    $lista[] = [
        "id" => $alumno["id"],
        "nombre" => $alumno["nombre"],
        "estado" => $estado,
    ];

    // Actualiza el contador correspondiente al estado del alumno.
    if ($estado == "presente") {
        $presentes++;
    } else {
        $ausentes++;
    }
}

// Define los datos que usará el encabezado común.
$titulo = "Confirmar asistencia";
$activo = "cursos";
// Muestra el encabezado antes del contenido de confirmación.
include "includes/header.php";
?>

<!-- Contenedor de la confirmación con el resumen de presentes y ausentes. -->
<div class="ok-box" style="max-width: 640px;">
    <div class="modal-icon danger" style="width:46px;height:46px">!</div>
    <h2>Confirmá la asistencia</h2>
    <p style="margin-bottom: 20px; color: var(--muted);">
        Revisá los datos antes de continuar. Una vez confirmada, no podrás cambiar la asistencia de hoy.
    </p>

    <div class="grid-2" style="margin-bottom: 18px;">
        <div class="card">
            <div class="stat-label">Presentes</div>
            <div class="stat"><?php echo $presentes; ?></div>
        </div>
        <div class="card">
            <div class="stat-label">Ausentes</div>
            <div class="stat"><?php echo $ausentes; ?></div>
        </div>
    </div>

    <!-- Al confirmar, envía al servidor el curso y el estado de cada alumno. -->
    <form action="guardar_asistencia.php" method="post">
        <input type="hidden" name="curso_id" value="<?php echo $curso_id; ?>">
        <!-- Los campos ocultos conservan los estados elegidos en el formulario anterior. -->
        <?php foreach ($lista as $alumno) { ?>
            <input type="hidden" name="estado[<?php echo $alumno["id"]; ?>]" value="<?php echo $alumno["estado"]; ?>">
        <?php } ?>

        <!-- Permite confirmar la asistencia o volver para corregirla. -->
        <div class="btn-row">
            <button class="btn btn-primary">Confirmar asistencia</button>
            <a class="btn btn-outline" href="asistencia.php?id=<?php echo $curso_id; ?>">Volver a corregir</a>
        </div>
    </form>
</div>

<!-- Incluye el pie de página compartido. -->
<?php include "includes/footer.php"; ?>

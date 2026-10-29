<?php
// Conecta con la base de datos (crea la variable $conexion) y trae las funciones comunes (escapar, mostrar_error)
include "includes/conexion.php";
// Si no hay sesión iniciada manda al login; si la hay, crea $usuario_id con el número del docente
include "includes/verificar_sesion.php";

// Número del curso que viene en la dirección (asistencia.php?id=3). (int) lo convierte a número
$id = (int) $_GET["id"];

// Buscamos el curso
$sql = "SELECT * FROM cursos WHERE id = $id";
$curso = mysqli_fetch_assoc(mysqli_query($conexion, $sql));

// Alumnos del curso
// ORDER BY nombre los ordena alfabéticamente
$sql = "SELECT * FROM alumnos WHERE curso_id = $id ORDER BY nombre";
$alumnos = mysqli_query($conexion, $sql);

// Si ya se tomó asistencia hoy, la cargamos en un array para mostrarla marcada
// Ejemplo del array:  $guardado[5] = "ausente";   (el alumno 5 estuvo ausente)
$hoy = date("Y-m-d");
// array() crea una lista vacía; se va llenando con lo que ya esté guardado hoy
$guardado = array();
// Pedimos el estado guardado hoy de cada alumno de este curso
$sql = "SELECT alumno_id, estado FROM asistencias WHERE curso_id = $id AND fecha = '$hoy'";
$resultado = mysqli_query($conexion, $sql);
// Recorremos las filas encontradas: cada vuelta toma una
while ($fila = mysqli_fetch_assoc($resultado)) {
    // Anotamos en la lista: la clave es el número del alumno y el valor, su estado
    $guardado[$fila["alumno_id"]] = $fila["estado"];
}

// Datos que usa header.php: $titulo es el texto de la pestaña y $activo marca la opción seleccionada del menú
$titulo = "Tomar asistencia";
$activo = "cursos";
// Dibuja la parte de arriba de la página (la estructura y el menú lateral)
include "includes/header.php";
?>

<!-- Estilos del botón Presente/Ausente. Están en este archivo para que funcione con solo
     reemplazarlo; si preferís, se pueden mover a style.css -->
<style>
    /* Todo el botón es una etiqueta (label): tocar en cualquier parte lo cambia */
    .toggle-asistencia {
        position: relative;
        flex: none;              /* el botón no se achica aunque el nombre sea largo */
        cursor: pointer;
    }

    /* El casillero real queda invisible, pero sigue funcionando (también con el teclado) */
    .toggle-asistencia input[type="checkbox"] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    /* Aspecto común de los dos textos: grande y fácil de tocar (unos 44 px de alto) */
    .toggle-asistencia span {
        display: block;
        min-width: 130px;
        padding: 12px 18px;
        border: 1px solid;
        border-radius: 8px;
        text-align: center;
        font-size: 14px;
        font-weight: 700;
    }

    /* Presente: verde (es lo que se ve cuando el casillero NO está marcado) */
    .toggle-asistencia .t-presente {
        background: rgba(62, 207, 142, 0.15);
        border-color: var(--ok);
        color: var(--ok);
    }

    /* Ausente: rojo, escondido hasta que el casillero se marque */
    .toggle-asistencia .t-ausente {
        display: none;
        background: rgba(229, 72, 77, 0.15);
        border-color: var(--danger);
        color: #ffb4b6;
    }

    /* Con el casillero marcado se intercambian: se oculta Presente y se muestra Ausente */
    .toggle-asistencia input:checked ~ .t-presente {
        display: none;
    }
    .toggle-asistencia input:checked ~ .t-ausente {
        display: block;
    }

    /* Borde blanco cuando se llega con la tecla Tab */
    .toggle-asistencia input:focus-visible ~ span {
        outline: 2px solid #fff;
        outline-offset: 2px;
    }
</style>

<!-- Encabezado: título, curso, fecha de hoy e indicación de uso -->
<div class="page-head">
    <div>
        <h2>Tomar asistencia</h2>
        <small><?php echo escapar($curso["nombre"]); ?> · <?php echo date("d/m/Y"); ?></small>
        <small>Tocá el botón de cada alumno para cambiar entre Presente y Ausente.</small>
    </div>
</div>

<!-- Si el curso no tiene alumnos no hay nada que marcar: mostramos un aviso. Si tiene, el formulario -->
<?php if (mysqli_num_rows($alumnos) == 0) { ?>
    <div class="empty">Agregá alumnos al curso para tomar asistencia.</div>
<?php } else { ?>

<!-- Al enviar, los estados viajan por POST a guardar_asistencia.php -->
<form action="guardar_asistencia.php" method="post">
    <!-- Campo oculto con el número del curso, para que guardar_asistencia.php sepa cuál es -->
    <input type="hidden" name="curso_id" value="<?php echo $id; ?>">

    <!-- Lista con una fila por alumno -->
    <div class="list">
        <!-- while repite la fila por cada alumno del curso -->
        <?php while ($alumno = mysqli_fetch_assoc($alumnos)) { ?>
            <?php
            // Por defecto todos "presente", salvo que ya haya algo guardado hoy
            $estado = "presente";
            if (isset($guardado[$alumno["id"]])) {
                $estado = $guardado[$alumno["id"]];
            }
            ?>
            <!-- Fila de un alumno: el nombre a la izquierda y el botón a la derecha -->
            <div class="row">
                <span class="name"><?php echo escapar($alumno["nombre"]); ?></span>

                <!-- Un solo botón por alumno. Tocarlo marca o desmarca el casillero:
                     - sin marcar = Presente
                     - marcado    = Ausente
                     Un casillero sin marcar no envía nada al servidor, por eso va antes
                     un campo oculto con "presente". Los dos tienen el mismo name, y
                     cuando el casillero está marcado PHP se queda con el último valor
                     ("ausente"). Así siempre llega un estado para cada alumno. -->
                <label class="toggle-asistencia">
                    <input type="hidden" name="estado[<?php echo $alumno["id"]; ?>]" value="presente">
                    <input type="checkbox" name="estado[<?php echo $alumno["id"]; ?>]" value="ausente"
                        <?php if ($estado == "ausente") { echo "checked"; } ?>>
                    <span class="t-presente">Presente</span>
                    <span class="t-ausente">Ausente</span>
                </label>
            </div>
        <?php } ?>
    </div>

    <!-- Botones: guardar (envía el formulario) o cancelar (vuelve al curso sin guardar) -->
    <div class="btn-row">
        <button class="btn btn-primary">Guardar asistencia</button>
        <a class="btn btn-outline" href="curso_detalle.php?id=<?php echo $id; ?>">Cancelar</a>
    </div>
</form>

<?php } ?>

<!-- Cierra la página: termina el contenido y carga app.js -->
<?php include "includes/footer.php"; ?>

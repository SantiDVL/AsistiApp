<?php
include "includes/conexion.php";
include "includes/verificar_sesion.php";

$sql = "SELECT * FROM usuarios WHERE id = $usuario_id";
$usuario = mysqli_fetch_assoc(mysqli_query($conexion, $sql));

$titulo = "Editar datos personales";
$activo = "cuenta";
include "includes/header.php";
?>

<div class="page-head"><h2>Editar datos personales</h2></div>

<form class="form-card" action="actualizar_cuenta.php" method="post">
    <div class="form-grid">
        <div class="field">
            <label for="nombre">Nombre completo</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo $usuario["nombre"]; ?>" required>
        </div>
        <div class="field">
            <label for="correo">Correo</label>
            <input type="email" id="correo" name="correo" value="<?php echo $usuario["correo"]; ?>" required>
        </div>
        <div class="field">
            <label for="institucion">Institución</label>
            <input type="text" id="institucion" name="institucion" value="<?php echo $usuario["institucion"]; ?>">
        </div>
        <div class="field">
            <label for="telefono">Número de teléfono</label>
            <input type="text" id="telefono" name="telefono" value="<?php echo $usuario["telefono"]; ?>">
        </div>
    </div>
    <div class="btn-row">
        <button class="btn btn-primary">Guardar</button>
        <a class="btn btn-outline" href="cuenta.php">Cancelar</a>
    </div>
</form>

<?php include "includes/footer.php"; ?>

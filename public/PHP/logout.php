<?php
// Cierra la sesión y vuelve a la pantalla de bienvenida
session_start();
session_destroy();
header("Location: index.html");
exit;
?>

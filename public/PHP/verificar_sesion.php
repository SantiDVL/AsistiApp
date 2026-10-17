<?php
// Se incluye al principio de cada página privada.
// Si el usuario no inició sesión, lo manda al login.
session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.html");
    exit;
}

$usuario_id = $_SESSION["usuario_id"];
?>

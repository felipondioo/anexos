<?php
    session_start();
    if (!isset($_SESSION['dniPadre'])) {
        header('Location: ../../index.php');
        exit;
    } else {
        include('conexion.php');
    }
?>
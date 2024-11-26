<?php
    session_start();
    if (!isset($_SESSION['dniProfesor'])) {
        header('Location: ../../index.php');
        exit;
    } else {
        include('../../php/conexion.php');
    }
?>
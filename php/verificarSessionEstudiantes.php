<?php
    session_start();
    $edad = $_SESSION['edad'];
    if (!isset($_SESSION['dniEstudiante']) || $edad <= 17) {
        header('Location: ../../index.php');
        exit;
    } else {
        $dniEstudiante = $_SESSION['dniEstudiante'];
        include('../../php/conexion.php');
    }
?>
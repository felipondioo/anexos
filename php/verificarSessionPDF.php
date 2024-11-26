<?php
    session_start();
    if (!isset($_SESSION['dniProfesor']) && !isset($_SESSION['dniPadre']) && !isset($_SESSION['dniDirector']) && !isset($_SESSION['dniEstudiante'])) {
        header('Location: ../../index.php');
        exit;
    } else {
        include('conexion.php');
    }
?>
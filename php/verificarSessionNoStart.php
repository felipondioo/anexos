<?php
    if (!isset($_SESSION['dniProfesor'])) {
        header('Location: ../../index.php');
        exit;
    }
    else{
        include("conexion.php");
    }
?>
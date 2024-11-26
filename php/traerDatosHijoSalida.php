<?php
    session_start();
    include 'conexion.php';

    if (isset($_GET['dniAlumno'])) {
        $_SESSION['dniAlumno'] = $_GET['dniAlumno'];
        $dniAlumno = $_SESSION['dniAlumno'];
        if ($dniAlumno) {
            header('Location: ../indexs/salidasHijos.php');
            exit;
        } else {
            echo "Error";
        }

    } else {
        echo ('Hubo un error al capturar el dni...');
    }

    $conexion->close();
?>
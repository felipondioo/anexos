<?php
    if (!$_SESSION['dniProfesor']) {
        header('Location: ../../../index.php');
    } else {
        include 'conexion.php';
        $idSalida = $_SESSION['idSalida'];

        $sql = "SELECT * FROM anexoiv WHERE idAnexoIV = $idSalida";
        $resultado = mysqli_query($conexion, $sql);
        $fila = mysqli_fetch_assoc($resultado);
    }
?>
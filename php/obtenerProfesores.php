<?php
    session_start();
    $idSalida = $_SESSION['idSalida'];
    $dniProfesor = $_SESSION['dniProfesor'];
    include 'conexion.php';

    // Obtener los profesores de la base de datos
    $sqlProfesSalida = "SELECT `dni`, `apellidoNombre` FROM `anexov` WHERE fkAnexoIV = $idSalida AND cargo IN (2, 4) AND dni != $dniProfesor";
    $resultado = mysqli_query($conexion, $sqlProfesSalida);
    $profesores = [];
    while ($filaProfe = $resultado->fetch_assoc()) {
        $profesores[] = $filaProfe; // Guardamos los profesores en un array
    }

    // Retornar la lista de profesores en formato JSON
    echo json_encode($profesores);
?>

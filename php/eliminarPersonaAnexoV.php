<?php
    session_start();
    include 'verificarSessionNoStart.php';

    if (isset($_POST['dniList'])) {
        $dniList = $_POST['dniList'];
        $idSalida = $_SESSION['idSalida'];

        include ('conexion.php');

        $dniListString = implode(",", array_map('intval', $dniList));

        $sqlDelete = "DELETE FROM anexov WHERE dni IN ($dniListString) AND fkAnexoIV = $idSalida";
        if ($conexion->query($sqlDelete) === TRUE) {
            echo "Personas eliminadas correctamente.";
        } else {
            echo "Error al eliminar: " . $conexion->error;
        }

        $conexion->close();
    }
?>

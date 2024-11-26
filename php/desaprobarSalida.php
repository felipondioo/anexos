<?php
    session_start();
    if (!isset($_SESSION['dniDirector'])) {
        header('Location: ../index.php');
        exit;
    }

    include('conexion.php');

    $idAnexoIV = $_POST['idAnexoIV'];

    $sqlDesaprobar = "UPDATE anexoiv SET estado = 0 WHERE idAnexoIV = ?";
    $stmt = mysqli_prepare($conexion, $sqlDesaprobar);

    mysqli_stmt_bind_param($stmt, 'i', $idAnexoIV);
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['status' => 'success', 'message' => 'Salida desaprobada exitosamente']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al desaprobar la salida']);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conexion);
?>

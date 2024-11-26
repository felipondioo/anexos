<?php
    session_start();
    if (!isset($_SESSION['dniDirector'])) {
        header('Location: ../index.php');
        exit;
    }

    include('conexion.php');

    $idAnexoIV = $_POST['idAnexoIV'];

    $sqlAprobar = "UPDATE anexoiv SET estado = 3 WHERE idAnexoIV = ?";
    $stmt = mysqli_prepare($conexion, $sqlAprobar);

    mysqli_stmt_bind_param($stmt, 'i', $idAnexoIV);
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['status' => 'success', 'message' => 'Salida aprobada exitosamente']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al aprobar la salida']);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conexion);
?>

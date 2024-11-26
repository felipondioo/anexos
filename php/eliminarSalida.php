<?php
session_start();
if (!isset($_SESSION['dniDirector'])) {
    header('Location: ../index.php');
    exit;
}

include('conexion.php');

$idAnexoIV = $_POST['idAnexoIV'];

// Iniciar la transacción
mysqli_begin_transaction($conexion);

try {
    // Eliminar de la tabla anexov
    $sqlEliminarAnexoV = "DELETE FROM anexov WHERE fkAnexoIV = ?";
    $stmtAnexoV = mysqli_prepare($conexion, $sqlEliminarAnexoV);
    mysqli_stmt_bind_param($stmtAnexoV, 'i', $idAnexoIV);
    if (!mysqli_stmt_execute($stmtAnexoV)) {
        throw new Exception("Error al eliminar en anexov: " . mysqli_error($conexion));
    }
    mysqli_stmt_close($stmtAnexoV);

    // Eliminar de la tabla anexovi
    $sqlEliminarAnexoVI = "DELETE FROM anexovi WHERE fkAnexoIV = ?";
    $stmtAnexoVI = mysqli_prepare($conexion, $sqlEliminarAnexoVI);
    mysqli_stmt_bind_param($stmtAnexoVI, 'i', $idAnexoIV);
    if (!mysqli_stmt_execute($stmtAnexoVI)) {
        throw new Exception("Error al eliminar en anexovi: " . mysqli_error($conexion));
    }
    mysqli_stmt_close($stmtAnexoVI);

    // Eliminar de la tabla anexovii
    $sqlEliminarAnexoVII = "DELETE FROM anexovii WHERE fkAnexoIV = ?";
    $stmtAnexoVII = mysqli_prepare($conexion, $sqlEliminarAnexoVII);
    mysqli_stmt_bind_param($stmtAnexoVII, 'i', $idAnexoIV);
    if (!mysqli_stmt_execute($stmtAnexoVII)) {
        throw new Exception("Error al eliminar en anexovii: " . mysqli_error($conexion));
    }
    mysqli_stmt_close($stmtAnexoVII);

    // Eliminar de la tabla anexoviii
    $sqlEliminarAnexoVIII = "DELETE FROM anexoviii WHERE fkAnexoIV = ?";
    $stmtAnexoVIII = mysqli_prepare($conexion, $sqlEliminarAnexoVIII);
    mysqli_stmt_bind_param($stmtAnexoVIII, 'i', $idAnexoIV);
    if (!mysqli_stmt_execute($stmtAnexoVIII)) {
        throw new Exception("Error al eliminar en anexoviii: " . mysqli_error($conexion));
    }
    mysqli_stmt_close($stmtAnexoVIII);

    // Eliminar de la tabla anexoix
    $sqlEliminarAnexoIX = "DELETE FROM anexoix WHERE fkAnexoIV = ?";
    $stmtAnexoIX = mysqli_prepare($conexion, $sqlEliminarAnexoIX);
    mysqli_stmt_bind_param($stmtAnexoIX, 'i', $idAnexoIV);
    if (!mysqli_stmt_execute($stmtAnexoIX)) {
        throw new Exception("Error al eliminar en anexoix: " . mysqli_error($conexion));
    }
    mysqli_stmt_close($stmtAnexoIX);

    // Eliminar de la tabla anexox
    $sqlEliminarAnexoX = "DELETE FROM anexox WHERE fkAnexoIV = ?";
    $stmtAnexoX = mysqli_prepare($conexion, $sqlEliminarAnexoX);
    mysqli_stmt_bind_param($stmtAnexoX, 'i', $idAnexoIV);
    if (!mysqli_stmt_execute($stmtAnexoX)) {
        throw new Exception("Error al eliminar en anexox: " . mysqli_error($conexion));
    }
    mysqli_stmt_close($stmtAnexoX);
    
    // Eliminar de la tabla anexoiv
    $sqlEliminarAnexoIV = "DELETE FROM anexoiv WHERE idAnexoIV = ?";
    $stmtAnexoIV = mysqli_prepare($conexion, $sqlEliminarAnexoIV);
    mysqli_stmt_bind_param($stmtAnexoIV, 'i', $idAnexoIV);
    if (!mysqli_stmt_execute($stmtAnexoIV)) {
        throw new Exception("Error al eliminar en anexoiv: " . mysqli_error($conexion));
    }
    mysqli_stmt_close($stmtAnexoIV);
    mysqli_commit($conexion);

    echo json_encode(['status' => 'success', 'message' => 'Salida y todos los anexos eliminados exitosamente']);
} catch (Exception $e) {
    // Revertir la transacción si hay un error
    mysqli_rollback($conexion);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

mysqli_close($conexion);
?>

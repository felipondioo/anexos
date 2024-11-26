<?php
    session_start();
    include 'verificarSessionNoStart.php';

    include('conexion.php');

    $dni = $_SESSION['dniProfesor'];

    $sqlSalidasPendientes = "SELECT idAnexoIV, fechaLimite FROM anexoiv WHERE dniEncargado = ? AND estado = 1";
    $stmt = $conexion->prepare($sqlSalidasPendientes);

    if ($stmt) {
        $stmt->bind_param('s', $dni); 
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $salidas = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($salidas);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al ejecutar la consulta.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error en la consulta.']);
    }

    $conexion->close();
?>

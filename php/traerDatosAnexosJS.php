<?php
    session_start();
    include '../../php/verificarSessionNoStart.php';

    $idSalida = $_SESSION['idSalida'];
    include('conexion.php');
    header('Content-Type: application/json');
    $tabId = $_GET['tabId'];

    switch ($tabId) {
        case 'anexo8':
            $tabla = 'anexoviii';
            break;
        case 'anexo9':
            $tabla = 'anexoix';
            break;
        case 'anexo10':
            $tabla = 'anexox';
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'ID de pestaña no válido']);
            exit;
    }

    $sql = "SELECT * FROM $table WHERE fkAnexoIV = $idSalida";
    $result = $conexion->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode(['success' => true, 'fields' => $row]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se encontraron datos']);
    }

    $conexion->close();
?>

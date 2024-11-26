<?php
    session_start();
    include ('conexion.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['idAnexoIV'])) {

            $idAnexoIV = $_POST['idAnexoIV'];
            $sql = "UPDATE anexoiv SET estado = 2 WHERE idAnexoIV = ?";
            $stmt = $conexion->prepare($sql);

            if ($stmt) {
                $stmt->bind_param('i', $idAnexoIV);
                if ($stmt->execute()) {
                    echo json_encode(['status' => 'success', 'message' => 'Salida cancelada. La salida se ha cancelado automáticamente porque usted ha excedido la fecha límite de entrega. Se ha guardado en el histórico.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Error al cancelar la salida.']);
                }
                $stmt->close();
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error en la consulta.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ID de salida no proporcionado.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Método de solicitud no permitido.']);
    }

    $conexion->close();
?>

<?php
    session_start();
    include 'verificarSessionNoStart.php';

    include ('conexion.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['idAnexoIV'])) {

            $idAnexoIV = $_POST['idAnexoIV'];
            $sql = "SELECT fechaLimite FROM anexoiv WHERE idAnexoIV = ?"; // Asegúrate de que el nombre de la columna sea correcto.
            $stmt = $conexion->prepare($sql);

            if ($stmt) {
                $stmt->bind_param('i', $idAnexoIV); // Asegúrate de que el tipo de parámetro sea correcto.
                if ($stmt->execute()) {
                    $result = $stmt->get_result(); // Obtener el resultado de la consulta.
                    if ($result->num_rows > 0) {
                        $fecha = $result->fetch_assoc(); // Obtener el primer resultado como un array asociativo.
                        echo json_encode(['status' => 'success', 'fechaLimite' => $fecha['fechaLimite']]); // Devolver la fecha límite.
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'No se encontró el registro.']); // Mensaje si no se encuentra el registro.
                    }
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Error al ejecutar la consulta.']);
                }
                $stmt->close();
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error en la preparación de la consulta.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ID de salida no proporcionado.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Método de solicitud no permitido.']);
    }

    $conexion->close();
?>

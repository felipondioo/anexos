<?php
    include 'verificarSessionNoStart.php';
    include('conexion.php');

    if (isset($idSalida)) {
        // Consulta inicial para verificar 'anexoviiiHabil' en la tabla anexoiv
        $sql = "SELECT anexoviiiHabil FROM anexoiv WHERE idAnexoIV = ?";
        $stmt = $conexion->prepare($sql);
        if ($stmt === false) {
            die('Error preparando la consulta: ' . $conexion->error);
        }

        $stmt->bind_param('i', $idSalida);

        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $anexoviiiHabil = $row['anexoviiiHabil'];

                // Verificación para contar docentes con cargo 2 y 5
                $sqlCountCargo2 = "SELECT COUNT(*) as count FROM anexov WHERE cargo = 2";
                $stmtCountCargo2 = $conexion->prepare($sqlCountCargo2);
                $stmtCountCargo2->execute();
                $resultCountCargo2 = $stmtCountCargo2->get_result();
                $countCargo2 = $resultCountCargo2->fetch_assoc()['count'];

                $sqlCountCargo5 = "SELECT COUNT(*) as count FROM anexov WHERE cargo = 5";
                $stmtCountCargo5 = $conexion->prepare($sqlCountCargo5);
                $stmtCountCargo5->execute();
                $resultCountCargo5 = $stmtCountCargo5->get_result();
                $countCargo5 = $resultCountCargo5->fetch_assoc()['count'];

                // Verifica si hay suficientes docentes suplentes (cargo 5)
                if ($countCargo5 > 0) {
                    echo '<li><a href="../pdf/plantillaAnexoIV.php" class="btn form-control botones w-100 mb-3">Anexo IV</a></li>';
                } else {
                    echo '<li><a href="../pdf/plantillaAnexoIV.php" class="btn form-control botones w-100 mb-3">Anexo IV (Atención: no hay suficientes suplentes)</a></li>';
                }

                // Verificación del total de registros en anexov para mostrar Anexo V
                $sqlCountAnexov = "SELECT COUNT(*) as total FROM anexov WHERE fkAnexoIV = $idSalida";
                $stmtCountAnexov = $conexion->prepare($sqlCountAnexov);
                $stmtCountAnexov->execute();
                $resultCountAnexov = $stmtCountAnexov->get_result();
                $countAnexov = $resultCountAnexov->fetch_assoc()['total'];

                if ($countAnexov > 1) {
                    echo '<li><a href="../pdf/plantillaAnexoV.php" class="btn form-control botones w-100 mb-3">Anexo V</a></li>';
                } else {
                    echo '<li><a class="btn form-control botones w-100 mb-3" disabled>Anexo V (Sin Completar)</a></li>';
                }

                // Verificación para el Anexo VIII
                if ($anexoviiiHabil == 1) {
                    $sqlAnexoVIII = "SELECT fkAnexoIV FROM anexoviii WHERE fkAnexoIV = ?";
                    $stmtAnexoVIII = $conexion->prepare($sqlAnexoVIII);
                    $stmtAnexoVIII->bind_param('i', $idSalida);
                    $stmtAnexoVIII->execute();
                    $resultAnexoVIII = $stmtAnexoVIII->get_result();

                    if ($resultAnexoVIII->num_rows > 0) {
                        echo '<li><a href="../pdf/plantillaAnexoVIII.php" class="btn form-control botones w-100 mb-3">Anexo VIII</a></li>';
                    } else {
                        echo '<li><a class="btn form-control botones w-100 mb-3" disabled>Anexo VIII (Sin completar)</a></li>';
                    }
                    $stmtAnexoVIII->close();
                }
            } else {
                die('Error: No se encontraron resultados en anexoiv.');
            }
        } else {
            die('Error al ejecutar la consulta en anexoiv: ' . $stmt->error);
        }

        // Cierre de la consulta inicial y conexión
        $stmt->close();
        $conexion->close();
    } else {
        die('Error: idSalida no está definido.');
    }
?>

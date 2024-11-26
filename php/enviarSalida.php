<?php
    session_start();
    include('conexion.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['idAnexoIV'])) {

            $idAnexoIV = $_POST['idAnexoIV'];

            // Verificar si el anexo VIII y la planilla estan habilitados
            $sqlHabiles = "SELECT anexoviiiHabil, planillaHabilitada FROM anexoiv WHERE idAnexoIV = ?";
            $stmtHabiles = $conexion->prepare($sqlHabiles);

            if ($stmtHabiles) {
                $stmtHabiles->bind_param('i', $idAnexoIV);
                $stmtHabiles->execute();
                $resultHabiles = $stmtHabiles->get_result();
            
                $rowHabiles = $resultHabiles->fetch_assoc();
                $anexoVIIIHabilitado = $rowHabiles['anexoviiiHabil'];
                $planillaHabilitada = $rowHabiles['planillaHabilitada'];

                $stmtHabiles->close();
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error en la consulta de habilitación del Anexo VIII.']);
                exit;
            }

            // Consultar anexos
            $sqlAnexoV = "SELECT COUNT(*) as count FROM anexov WHERE fkAnexoIV = ?";
            $sqlAnexoVIII = "SELECT COUNT(*) as count FROM anexoviii WHERE fkAnexoIV = ?";
            $sqlAnexoPlanilla = "SELECT COUNT(*) as count FROM planillainfoanexo WHERE fkAnexoIV = ?";

            $stmtAnexoV = $conexion->prepare($sqlAnexoV);

            if ($stmtAnexoV) {
                // Ejecutar y verificar Anexo V
                $stmtAnexoV->bind_param('i', $idAnexoIV);
                $stmtAnexoV->execute();
                $resultAnexoV = $stmtAnexoV->get_result();
                $anexoVCompleto = $resultAnexoV->fetch_assoc()['count'] > 1;
                $stmtAnexoV->close();

                // Verificar Anexo VIII solo si está habilitado
                if ($anexoVIIIHabilitado == 1) {
                    $stmtAnexoVIII = $conexion->prepare($sqlAnexoVIII);
                    if ($stmtAnexoVIII) {
                        $stmtAnexoVIII->bind_param('i', $idAnexoIV);
                        $stmtAnexoVIII->execute();
                        $resultAnexoVIII = $stmtAnexoVIII->get_result();
                        $anexoVIIICompleto = $resultAnexoVIII->fetch_assoc()['count'] > 0;
                        $stmtAnexoVIII->close();
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Error en la consulta del Anexo VIII.']);
                        exit;
                    }
                } else {
                    // Si el Anexo VIII no está habilitado, lo consideramos como completo
                    $anexoVIIICompleto = true;
                }

                if ($planillaHabilitada == 1) {
                    $stmtAnexoPlanilla = $conexion->prepare($sqlAnexoPlanilla);
                    if ($stmtAnexoPlanilla) {
                        $stmtAnexoPlanilla->bind_param('i', $idAnexoIV);
                        $stmtAnexoPlanilla->execute();
                        $resultAnexoPlanilla = $stmtAnexoPlanilla->get_result();
                        $planillaCompleta = $resultAnexoPlanilla->fetch_assoc()['count'] > 0;
                        $stmtAnexoPlanilla->close();
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Error en la consulta de la Planilla Informativa.']);
                        exit;
                    }
                } else {
                    $planillaCompleta = true;
                }
                
                // Si todos los anexos están completos, proceder con el UPDATE
                if ($anexoVCompleto && $anexoVIIICompleto && $planillaCompleta) {
                    $sqlUpdate = "UPDATE anexoiv SET estado = 4 WHERE idAnexoIV = ?";
                    $stmtUpdate = $conexion->prepare($sqlUpdate);
                    if ($stmtUpdate) {
                        $stmtUpdate->bind_param('i', $idAnexoIV);
                        if ($stmtUpdate->execute()) {
                            echo json_encode(['status' => 'success', 'message' => 'Solicitud enviada correctamente. Tenga en cuenta que ya no podrá editar los anexos, pero podrá descargarlos luego de su aprobación.']);
                        } else {
                            echo json_encode(['status' => 'error', 'message' => 'Error al enviar solicitud.']);
                        }
                        $stmtUpdate->close();
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Error en la consulta de actualización.']);
                    }
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Faltan anexos por completar. Revise la cantidad de itegrantes en la salida o busque formularios incompletos en la sección de formularios.']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error en la preparación de las consultas de verificación.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ID de salida no proporcionado.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Método de solicitud no permitido.']);
    }

    $conexion->close();
?>

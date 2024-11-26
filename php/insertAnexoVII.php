<?php
session_start();

// Verifica si se ha iniciado sesión y si la variable de sesión está definida
if (!isset($_SESSION['idSalida'])) {
    echo json_encode(['status' => 'error', 'message' => 'No se encontró la sesión.']);
    exit();
}

include 'conexion.php';

// Recepción de datos de la solicitud
$data = json_decode(file_get_contents("php://input"), true);

// Asignación de datos
$dniEstudiante = $_SESSION['dniEstudiante'] ?? null;
$altura = $data['altura'] ?? null;
$domicilio = $data['domicilio'] ?? null;
$localidad = $data['localidad'] ?? null;
$observaciones = $data['observaciones'];
if (empty($observaciones)) {
    $observaciones = "-";
}
$obraSocial = $data['obraSocial'] ?? 0;
$telefonos = is_array($data['telefonos']) ? implode(',', $data['telefonos']) : $data['telefonos'];

// Validación de obra social
if ($obraSocial == 1) {
    $nombreObraSocial = !empty($data['nombreObraSocial']) ? $data['nombreObraSocial'] : null;
    $numeroAfiliado = !empty($data['numeroAfiliado']) ? $data['numeroAfiliado'] : null;

    if (is_null($nombreObraSocial) || is_null($numeroAfiliado)) {
        echo json_encode(['status' => 'error', 'message' => 'Debe completar los datos de la obra social.']);
        exit();
    }
} else {
    $nombreObraSocial = '-';
    $numeroAfiliado = '-';
}

// Verificación de existencia del registro
$checkSql = "SELECT COUNT(*) AS count FROM anexovii WHERE fkAnexoIV = ? AND dniEstudiante = ?";
$stmt = $conexion->prepare($checkSql);
$stmt->bind_param("ii", $_SESSION['idSalida'], $dniEstudiante);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

if ($row['count'] > 0) {
    // Si existe, actualiza el registro
    $updateSql = "UPDATE anexovii SET domicilio = ?, altura = ?, localidad = ?, observaciones = ?, obraSocial = ?, nombreObraSocial = ?, numeroAfiliado = ?, telefonos = ? 
                  WHERE fkAnexoIV = ? AND dniEstudiante = ?";
    $stmt = $conexion->prepare($updateSql);
    $stmt->bind_param("sississsii", $domicilio, $altura, $localidad, $observaciones, $obraSocial, $nombreObraSocial, $numeroAfiliado, $telefonos, $_SESSION['idSalida'], $dniEstudiante);
} else {
    // Si no existe, inserta un nuevo registro
    $insertSql = "INSERT INTO anexovii (fkAnexoIV, dniEstudiante, domicilio, altura, localidad, observaciones, obraSocial, nombreObraSocial, numeroAfiliado, telefonos) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($insertSql);
    $stmt->bind_param("iisississs", $_SESSION['idSalida'], $dniEstudiante, $domicilio, $altura, $localidad, $observaciones, $obraSocial, $nombreObraSocial, $numeroAfiliado, $telefonos);
}

// Ejecución de la consulta (inserción o actualización)
if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Anexo VII cargado correctamente.']);
} else {
    error_log("Error al ejecutar la operación: " . $stmt->error);
    echo json_encode(['status' => 'error', 'message' => 'Error al ejecutar la operación: ' . $stmt->error]);
}

// Cierre de conexión
$stmt->close();
$conexion->close();
exit(); // Salida para evitar contenido adicional
?>

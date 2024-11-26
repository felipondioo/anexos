<?php
$idSalida = $_SESSION['idSalida'] ?? null;
include('conexion.php');

if (isset($idSalida)) {
    $sql = "SELECT * FROM planillainfoanexo WHERE fkAnexoIV = ?";
    $stmt = $conexion->prepare($sql);

    if ($stmt === false) {
        error_log('Error preparando la consulta: ' . $conexion->error);
        die('Error interno del servidor.');
    }

    $stmt->bind_param('i', $idSalida);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        } 

        echo '
            <div class="form-group">
                <label for="empresas" class="form-label">Empresa y/o empresas contratadas (nombre, dirección, teléfonos):</label>
                <textarea class="form-control item" id="empresas" name="empresas" rows="2" placeholder="Ingrese empresas contradadas (nombre, direccion, telefono)..." required>'.htmlspecialchars($row['empresas'], ENT_QUOTES, 'UTF-8').'</textarea>
            </div>
            <div class="form-group">
                <label for="datosInfraestructura" class="form-label">Datos de la Infraestructura disponible:</label>
                <textarea class="form-control item" id="datosInfraestructura" name="datosInfraestructura" rows="2" placeholder="Ingrese los datos de la Infraestructura disponible..." required>'.htmlspecialchars($row['datosInfraestructura'], ENT_QUOTES, 'UTF-8').'</textarea>
            </div>
            <div class="form-group">
                <label for="hospitalesCercanos" class="form-label">Hospitales y centros asistenciales cercanos (direcciones y teléfonos):</label>
                <textarea class="form-control item" id="hospitalesCercanos" name="hospitalesCercanos" rows="2" placeholder="Ingrese los datos de hospitales y centros de asistencia cercanos (nombre, direccion, telefono)..." required>'.htmlspecialchars($row['hospitalesCercanos'], ENT_QUOTES, 'UTF-8').'</textarea>
            </div>
            <div class="form-group">
                <label for="datosInteres" class="form-label">Dirección de los hospital/es:</label>
                <textarea class="form-control item" id="datosInteres" name="datosInteres" rows="2" placeholder="Ingrese datos de interes..." required>'.htmlspecialchars($row['datosInteres'], ENT_QUOTES, 'UTF-8').'</textarea>
            </div>
        ';
    } else {
        error_log('Error al ejecutar la consulta: ' . $stmt->error);
        die('Error interno del servidor.');
    }

    $stmt->close();
    $conexion->close();
} else {
    die('Error: idSalida no está definido.');
}
?>

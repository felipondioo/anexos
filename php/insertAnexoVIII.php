<?php
session_start();
$idSalida = $_SESSION['idSalida'];
include('conexion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombreEmpresa = $_POST['nombreEmpresa'];
    $nombreGerente = $_POST['nombreGerente'];
    $domicilioEmpresa = $_POST['domicilioEmpresa'];
    $telefonoEmpresa = $_POST['telefonoEmpresa'];
    $domicilioGerente = $_POST['domicilioGerente'];
    $telefono = $_POST['telefono'];
    $telefonoMovil = $_POST['telefonoMovil'];
    $titularidadVehiculo = $_POST['titularidadVehiculo'];
    $aseguradora = $_POST['aseguradora'];

    $nroRegistro = $_POST['numeroRegistroArray'];
    $fechaHabilitacion = $_POST['fechaHabilitacionArray'];
    $tipoHabilitacion = $_POST['tipoHabilitacionArray'];
    $cantAsientos = $_POST['cantidadAsientosArray'];
    $vigenciaVTV = $_POST['vigenciaVTVArray'];
    $nroPoliza = $_POST['polizaArray'];
    $tipoSeguro = $_POST['tipoSeguroArray'];
    $nombreConductor = $_POST['nombresConductoresArray'];
    $dniConductor = $_POST['dnisConductoresArray'];
    $carnetConducir = $_POST['carnetConductoresArray'];
    $vigenciaConductor = $_POST['vigenciaConductoresArray'];

    $rutaArchivo = null;
    if (isset($_FILES['pdfFile']) && $_FILES['pdfFile']['error'] == 0) {
        $uploadDir = '../archivosPDFAnexoVIII/';
        $filename = "adjuntoPDFsalida".$idSalida.".pdf";
        
        // Verificar si el archivo es un PDF basado en la extensión
        $fileExtension = strtolower(pathinfo($_FILES['pdfFile']['name'], PATHINFO_EXTENSION));
        if ($fileExtension !== 'pdf') {
            echo "Error: El archivo debe estar en formato PDF.";
            exit;
        }

        $rutaArchivo = $uploadDir . $filename;
        
        // Elimina el archivo existente si ya hay uno con el mismo nombre
        if (file_exists($rutaArchivo)) {
            unlink($rutaArchivo); 
        }
        
        if (!move_uploaded_file($_FILES['pdfFile']['tmp_name'], $rutaArchivo)) {
            die("Error al mover el archivo al directorio de destino.");
        }
    }

    // Código para verificar si ya existe un registro y realizar inserción o actualización
    $sqlVerificacion = "SELECT * FROM anexoviii WHERE fkAnexoIV = ?";
    $stmtVerificacion = $conexion->prepare($sqlVerificacion);
    $stmtVerificacion->bind_param("i", $idSalida);
    $stmtVerificacion->execute();
    $result = $stmtVerificacion->get_result();

    if ($result->num_rows > 0) {
        $sql = "UPDATE anexoviii SET 
                nombreEmpresa = ?, 
                nombreGerente = ?, 
                domicilioEmpresa = ?, 
                telefonoEmpresa = ?, 
                domicilioGerente = ?, 
                telefono = ?, 
                telefonoMovil = ?, 
                titularidadVehiculo = ?, 
                rutaPDF = COALESCE(?, rutaPDF),
                nroRegistro = ?, 
                fechaHabilitacion = ?, 
                tipoHabilitacion = ?, 
                cantAsientos = ?, 
                vigenciaVTV = ?, 
                aseguradora = ?, 
                nroPoliza = ?, 
                tipoSeguro = ?, 
                nombreConductor = ?, 
                dniConductor = ?,
                carnetConducir = ?, 
                vigenciaConductor = ?
                WHERE fkAnexoIV = ?";

        $stmt = $conexion->prepare($sql);

        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $conexion->error);
        }

        $stmt->bind_param("sssssssssssssssssssssi", 
            $nombreEmpresa, 
            $nombreGerente, 
            $domicilioEmpresa, 
            $telefonoEmpresa, 
            $domicilioGerente, 
            $telefono, 
            $telefonoMovil, 
            $titularidadVehiculo, 
            $rutaArchivo,
            $nroRegistro, 
            $fechaHabilitacion, 
            $tipoHabilitacion, 
            $cantAsientos, 
            $vigenciaVTV, 
            $aseguradora, 
            $nroPoliza, 
            $tipoSeguro, 
            $nombreConductor, 
            $dniConductor, 
            $carnetConducir,
            $vigenciaConductor,
            $idSalida
        );

        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $sql = "INSERT INTO `anexoviii`
            (`fkAnexoIV`, `nombreEmpresa`, `nombreGerente`, `domicilioEmpresa`, `telefonoEmpresa`, `domicilioGerente`, `telefono`, `telefonoMovil`, `titularidadVehiculo`, `rutaPDF`, `nroRegistro`, `fechaHabilitacion`, `tipoHabilitacion`, `cantAsientos`, `vigenciaVTV`, `aseguradora`, `nroPoliza`, `tipoSeguro`, `nombreConductor`, `dniConductor`, `carnetConducir`, `vigenciaConductor`) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conexion->prepare($sql);

        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $conexion->error);
        }

        $stmt->bind_param("isssssssssssssssssssss", 
            $idSalida, 
            $nombreEmpresa, 
            $nombreGerente, 
            $domicilioEmpresa, 
            $telefonoEmpresa, 
            $domicilioGerente, 
            $telefono, 
            $telefonoMovil, 
            $titularidadVehiculo, 
            $rutaArchivo,
            $nroRegistro, 
            $fechaHabilitacion, 
            $tipoHabilitacion, 
            $cantAsientos, 
            $vigenciaVTV, 
            $aseguradora, 
            $nroPoliza, 
            $tipoSeguro, 
            $nombreConductor, 
            $dniConductor,
            $carnetConducir,
            $vigenciaConductor            
        );

        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }

    $stmtVerificacion->close();
    $conexion->close();
}
?>

<?php
    // session_start();
    $idSalida = $_SESSION['idSalida'];
    $dniEncargado = $_SESSION['dniProfesor'];
    $apellidoNombreEncargado = $_SESSION['apellidoDoc'] . ' ' . $_SESSION['nombreDoc'];
    $cargo = 2;
    
    include 'verificarSessionNoStart.php';

    include('conexion.php');

    $sqlVIPAV = "SELECT * FROM `anexov` WHERE fkAnexoIV = ? AND dni = ?";
    $stmtVIPAV = $conexion->prepare($sqlVIPAV);
    $stmtVIPAV->bind_param("ii", $idSalida, $dniEncargado);
    $stmtVIPAV->execute();
    $resultVIPAV = $stmtVIPAV->get_result();

    if ($resultVIPAV->num_rows > 0) {
        $resultVIPAV->free();
        $stmtVIPAV->close();
        $conexion->close();
    } else {
        $resultVIPAV->free();
        $stmtVIPAV->close();

        $sqlEdadDoc = "SELECT `fechan` FROM `personal` WHERE dni = ?";
        $stmtEdad = $conexion->prepare($sqlEdadDoc);
        $stmtEdad->bind_param("i", $dniEncargado);
        $stmtEdad->execute();
        $resultEdad = $stmtEdad->get_result();
        
        if ($resultEdad->num_rows > 0) {
            $rowEdad = $resultEdad->fetch_assoc();
            $fechan = $rowEdad['fechan']; 

            $fechaNacimiento = new DateTime($fechan);
            $fechaHoy = new DateTime(date("Y-m-d"));
            $edad = $fechaHoy->diff($fechaNacimiento)->y;
        } else {
            echo "Error: Fecha de nacimiento no encontrada.";
            exit();
        }
        
        $resultEdad->free();
        $stmtEdad->close();

        $sqlAnexoV = "INSERT INTO `anexov`(`fkAnexoIV`, `dni`, `apellidoNombre`, `edad`, `cargo`) VALUES (?, ?, ?, ?, ?)";
        $stmtAnexoV = $conexion->prepare($sqlAnexoV);
        $stmtAnexoV->bind_param("iisii", $idSalida, $dniEncargado, $apellidoNombreEncargado, $edad, $cargo);
        
        if ($stmtAnexoV->execute()) {
            // echo "Datos insertados correctamente";
        } else {
            echo "Error: " . $stmtAnexoV->error;
        }

        $stmtAnexoV->close();
        $conexion->close();
    }
?>

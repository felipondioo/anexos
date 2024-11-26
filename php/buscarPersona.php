<?php
    include 'conexion.php';
    ob_start();

    $dniPersona = $_POST['dniPersona'];
    $personas = [];

    if(isset($dniPersona)) {
        //perparar la consulta para la tabla personal
        $sqlPersonal = "SELECT dni, apellido, nombre, fechan FROM personal WHERE dni LIKE ?";
        $stmtPersonal = $conexion->prepare($sqlPersonal);
        $dniLike = "%$dniPersona%";
        $stmtPersonal->bind_Param('s', $dniLike);
        $stmtPersonal->execute();
        $resultPersonal = $stmtPersonal->get_result();
        
        while ($row = $resultPersonal->fetch_assoc()) {
            $row['cargo'] = 2;
            $personas[] = $row;
        }

        $stmtPersonal->close();

        $sqlAlumnos = "SELECT dni, apellido, nombre, fechan FROM alumnos WHERE dni LIKE ?";
        $stmtAlumnos = $conexion->prepare($sqlAlumnos);
        $dniLike = "%$dniPersona%";
        $stmtAlumnos->bind_Param('s', $dniLike);
        $stmtAlumnos->execute();
        $resultAlumnos = $stmtAlumnos->get_result();
        
        while ($row = $resultAlumnos->fetch_assoc()) {
            $row['cargo'] = 3;
            $personas[] = $row;
        }

        $stmtAlumnos->close();

        ob_end_clean();

        echo json_encode($personas);

    } else {
        echo json_encode(['error' => 'No se envió el DNI']);
    }
    $conexion->close();
?>
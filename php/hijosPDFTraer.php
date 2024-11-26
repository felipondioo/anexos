<?php
    include ('conexion.php');

    if (isset($idSalida) && isset($dniAlumno)) {
        $sqlAnexoVI = "SELECT fkAnexoIV FROM anexovi WHERE fkAnexoIV = ? AND dniAlumno = ?";
        $stmtAnexoVI = $conexion->prepare($sqlAnexoVI);
        $stmtAnexoVI->bind_param('is', $idSalida, $dniAlumno); 
        $stmtAnexoVI->execute();
        $stmtAnexoVI = $stmtAnexoVI->get_result();
        
        if ($stmtAnexoVI->num_rows > 0) {
            echo '<li><a href="../pdf/plantillaAnexoVI.php" class="btn form-control botones w-100 mb-3">Anexo VI</a></li>';
        } else {
            echo '<li><a class="btn form-control botones w-100 mb-3" disabled>Anexo VI (Sin completar)</a></li>';
        }
        $stmtAnexoVI->close();
        $conexion->close();
    } else {
        die('Error: idSalida o dniAlumno no están definidos.');
    }
?>

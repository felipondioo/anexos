<?php
    include 'verificarSessionNoStart.php';
    include ('conexion.php');
    
    $sqlGrupos = "SELECT * FROM `grupos` WHERE nombre NOT IN ('0', '999') ORDER BY nombre ASC";
    $resultGrupos = $conexion->query($sqlGrupos);

    if ($resultGrupos === false) {
        echo "Error en la consulta: " . $conexion->error;
    } else {
        if ($resultGrupos->num_rows > 0) {
            while ($grupo = $resultGrupos->fetch_assoc()) {
                echo '<option value="'.$grupo['id'].'">'.$grupo['nombre'].'</option>';
            }
        } else {
            echo '<option value="">No hay grupos disponibles</option>';
        }
        $resultGrupos->close();
    }
    $conexion->close();
?>

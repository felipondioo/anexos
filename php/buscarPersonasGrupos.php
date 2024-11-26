<?php
    if($_POST['grupo']) {
        include ('conexion.php');

        $idGrupo = $_POST['grupo'];
        $fechaActual = date("Y");

        $query = "SELECT al.dni, al.apellido, al.nombre, al.fechan
            FROM alumnos AS al
            INNER JOIN asignacionesalumnos a ON al.dni = a.dni_alumnos
            INNER JOIN grupos g ON a.id_grupos = g.id
            INNER JOIN cursosciclolectivo c ON a.id_cursosciclolectivo = c.id
            WHERE g.id = $idGrupo AND c.estado = 'H' AND c.ciclolectivo = $fechaActual";

        $result = mysqli_query($conexion, $query);
        
        if ($result) {
            $personasGrupos = array();
            while($row = mysqli_fetch_assoc($result)) {
                $datos = array(
                    'dni' => $row['dni'],
                    'apellido' => $row['apellido'],
                    'nombre' => $row['nombre'],
                    'fechan' => $row['fechan'],
                );
                $personasGrupos[] = $datos;
            }
            
            echo json_encode($personasGrupos);
        }

    } else {
        echo json_encode("Error en la consulta: " . mysqli_error($conexion));

        mysqli_close($conexion);
    }

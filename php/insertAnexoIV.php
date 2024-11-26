<?php
    session_start();
    include('conexion.php');

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $estado = 1;
        $tipoSolicitud = intval($_POST['tipoSalida']);
        $distanciaSalida = intval($_POST['distanciaSalida']);
        $planillaHabilitada = ($distanciaSalida == 1 || $distanciaSalida == 2) ? 2 : 1; // Nueva lógica
        $region = 18;
        $distrito = "La Costa";
        $institucionEducativa = "E.E.S.T.";
        $numeroInstitucion = 1;
        $domicilioInstitucion = "Calle 104 y 124";
        $telefonoInstitucion = 2246420535;
        $denominacionProyecto = $_POST['denominacionProyecto'];
        $lugarVisita = $_POST['lugarVisita'];
        $direccionVisita = $_POST['direccionVisita'];
        $localidadVisita = $_POST['localidadVisita'];
        $regionVisita = $_POST['regionVisita'];
        $fechaSalida = $_POST['fechaSalida'];
        $lugarSalida = $_POST['lugarSalida'];
        $horaSalida = $_POST['horaSalida'];
        $fechaRegreso = $_POST['fechaRegreso'];
        $lugarRegreso = $_POST['lugarRegreso'];
        $horaRegreso = $_POST['horaRegreso'];
        $itinerario = $_POST['itinerario'];
        $actividades = $_POST['actividades'];
        $objetivosSalida = $_POST['objetivosSalida'];
        $cronograma = $_POST['cronograma'];
        $dniEncargado = $_POST['dniEncargado'];
        $apellidoNombreEncargado = $_POST['nombreEncargado'];
        $cargo = 2;
        $nombreHospedaje = !empty($_POST['nombreHospedaje']) ? $_POST['nombreHospedaje'] : '-';
        $domicilioHospedaje = !empty($_POST['domicilioHospedaje']) ? $_POST['domicilioHospedaje'] : '-';
        $telefonoHospedaje = !empty($_POST['telefonoHospedaje']) ? $_POST['telefonoHospedaje'] : '-';
        $localidadHospedaje = !empty($_POST['localidadHospedaje']) ? $_POST['localidadHospedaje'] : '-';
        $gastosEstimativos = $_POST['gastosEstimativos'];
        $anexo8 = intval($_POST['anexoVIII']);
        $fechaLimite = $_POST['fechaLimite'];

        $sql = "INSERT INTO anexoiv (
            estado, tipoSolicitud, distanciaSalida, region, distrito, institucionEducativa, numeroInstitucion, domicilioInstitucion,
            telefonoInstitucion, denominacionProyecto, lugarVisita, direccionVisita, localidadVisita, regionVisita,
            fechaSalida, lugarSalida, horaSalida, fechaRegreso, lugarRegreso, horaRegreso, itinerario, actividades, objetivosSalida,
            cronograma, dniEncargado, apellidoNombreEncargado, cargo, nombreHospedaje, domicilioHospedaje, telefonoHospedaje, 
            localidadHospedaje, gastosEstimativos, anexoviiiHabil, planillaHabilitada, fechaLimite
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = $conexion->prepare($sql)) {
            $stmt->bind_param(
                "iiiissisisssssssssssssssisississiis",
                $estado, $tipoSolicitud, $distanciaSalida, $region, $distrito, $institucionEducativa, $numeroInstitucion, $domicilioInstitucion,
                $telefonoInstitucion, $denominacionProyecto, $lugarVisita, $direccionVisita, $localidadVisita, $regionVisita, $fechaSalida, 
                $lugarSalida, $horaSalida, $fechaRegreso, $lugarRegreso, $horaRegreso, $itinerario, $actividades, $objetivosSalida, $cronograma,
                $dniEncargado, $apellidoNombreEncargado, $cargo, $nombreHospedaje, $domicilioHospedaje, $telefonoHospedaje, 
                $localidadHospedaje,$gastosEstimativos, $anexo8, $planillaHabilitada, $fechaLimite
            );

            if ($stmt->execute()) {
                echo 'success';
                exit();
            } else {
                echo "Error: " . $stmt->error;
                echo "<br> SQL: " . $sql;
            }
            

            $stmt->close();
        } else {
            echo "Error en la preparación de la consulta: " . $conexion->error;
        }

        $conexion->close();
    } else {
        echo "Método de solicitud no permitido.";
    }
    ob_end_flush();
?>

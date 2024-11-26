<?php
    session_start();
    if (!isset($_SESSION['dniDirector'])) {
        header('Location: ../index.php');
        exit;
    }

    include('conexion.php');

    $sql = "SELECT * FROM anexoiv WHERE estado = 4";
    $anexoiv = mysqli_query($conexion, $sql);

    $salidas = [];

    while ($resp = mysqli_fetch_assoc($anexoiv)) {
        // Distancia (más de 24 horas)
        $distanciaHoras = "SELECT distanciaSalida FROM anexoiv WHERE idAnexoIV = '".$resp['idAnexoIV']."'";
        $distanciaHorasRespuesta = mysqli_query($conexion, $distanciaHoras);
        $distancia = mysqli_fetch_assoc($distanciaHorasRespuesta)['distanciaSalida'];
        $masDe24hs = in_array($distancia, [1, 2, 4, 6]) ? 'Si' : 'No';

        // Anexo IX habilitación
        $anexoixHabilsql = "SELECT anexoviiiHabil FROM anexoiv WHERE idAnexoIV = '".$resp['idAnexoIV']."'";
        $anexoixHabilresponse = mysqli_query($conexion, $anexoixHabilsql);
        $anexoixHabil = mysqli_fetch_assoc($anexoixHabilresponse)['anexoviiiHabil'] == 1 ? 'Si' : 'No';

        // Verificación de anexos completos
        $sqlAnexoV = "SELECT * FROM anexov WHERE fkAnexoIV = '".$resp['idAnexoIV']."'";
        $anexoVResponse = mysqli_query($conexion, $sqlAnexoV);
        $sqlAnexoVIII = "SELECT * FROM anexoviii WHERE fkAnexoIV = '".$resp['idAnexoIV']."'";
        $anexoVIIIResponse = mysqli_query($conexion, $sqlAnexoVIII);

        $anexosCompletos = (mysqli_num_rows($anexoVResponse) >= 1 && mysqli_num_rows($anexoVIIIResponse) == 0) ? 'Si' : 'No';

        if($resp['tipoSolicitud'] == 1){
            $solicitud = "Representación Institucional";
        }elseif($resp['tipoSolicitud'] == 2){
            $solicitud = "Educativa";
        }else{
            $solicitud = "Error";
        }
        $salidas[] = [
            'idAnexoIV' => $resp['idAnexoIV'],
            'denominacionProyecto' => $resp['denominacionProyecto'],
            'tipoSolicitud' => $solicitud,
            'lugarVisita' => $resp['lugarVisita'],
            'fechaSalida' => $resp['fechaSalida'],
            'fechaRegreso' => $resp['fechaRegreso'],
            'masDe24hs' => $masDe24hs,
            'anexoixHabil' => $anexoixHabil,
            'fechaSolicitud' => $resp['fechaModificacion'],
            'fechaLimite' => $resp['fechaLimite'],
        ];
    }

    echo json_encode($salidas);
?>

<?php
    require ('fpdf/fpdf.php');
    include ('../../php/verificarSessionPDF.php');

    $dniAlumno = $_SESSION['dniEstudiante'];
    $idSalida = $_SESSION['idSalida'];

    $sqlAnexoIV = "SELECT * FROM anexoiv WHERE idAnexoIV = $idSalida";
    $sqlAnexoV = "SELECT * FROM anexov WHERE fkAnexoIV = $idSalida AND cargo IN(1, 2, 4, 5)";
    $sqlAnexoVII = "SELECT * FROM anexovii WHERE fkAnexoIV = $idSalida";
    $sqlPlanilla = "SELECT * FROM planillainfoanexo WHERE fkAnexoIV = $idSalida";
    
    $resultadoAnexoIV = mysqli_query($conexion, $sqlAnexoIV);
    $filaAnexoIV = mysqli_fetch_assoc($resultadoAnexoIV);

    $resultadoAnexoV = mysqli_query($conexion, $sqlAnexoV);

    $resultadoAnexoVI = mysqli_query($conexion, $sqlAnexoVII);
    $filaAnexoVII = mysqli_fetch_assoc($resultadoAnexoVI);

    $resultadoPlantilla = mysqli_query($conexion, $sqlPlanilla);
    $filasPlantilla = mysqli_fetch_assoc($resultadoPlantilla);

    $sql = "SELECT 
            CONCAT(a.nombre, ' ', a.apellido) AS nombreCompleto,
            c.ano,
            c.division
        FROM 
            alumnos a
        INNER JOIN 
            asignacionesalumnos aa ON a.dni = aa.dni_alumnos
        INNER JOIN 
            cursosciclolectivo cl ON cl.id = aa.id_cursosciclolectivo
        INNER JOIN 
            cursos c ON c.id = cl.id_cursos
        WHERE 
            a.dni = ?
            AND cl.estado = 'H'
        ORDER BY 
            aa.id_cursosciclolectivo DESC
        LIMIT 1;";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $dniAlumno);
    $stmt->execute();
    $resultadoAlumno = $stmt->get_result();

    if ($fila = $resultadoAlumno->fetch_assoc()) {
        $nombreCompleto = $fila['nombreCompleto'];
        $nombre = ucwords(strtolower($nombreCompleto));
        $ano = $fila['ano'];
        $division = $fila['division'];
    } else {
        echo "No se encontraron resultados.";
    }

    setlocale(LC_TIME, 'es_ES.UTF-8');
    $fechaSalidaObj = DateTime::createFromFormat('Y-m-d', $filaAnexoIV['fechaSalida']);

    $fechaSalida = $fechaSalidaObj->format('d/m/y');
    $horaSalida = DateTime::createFromFormat('H:i:s', $filaAnexoIV['horaSalida'])->format('H:i');
    $salida = $filaAnexoIV['lugarSalida'] . ', ' . $fechaSalida . ', ' . $horaSalida;

    $formatter = new IntlDateFormatter('es_ES', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
    $formatter->setPattern('MMMM');  

    $diaSalida = $fechaSalidaObj->format('d');
    $mesSalida = ucfirst($formatter->format($fechaSalidaObj));
    $anoSalida = $fechaSalidaObj->format('Y');

    $fechaRegreso = DateTime::createFromFormat('Y-m-d', $filaAnexoIV['fechaRegreso'])->format('d/m/y');
    $horaRegreso = DateTime::createFromFormat('H:i:s', $filaAnexoIV['horaRegreso'])->format('H:i');
    $regreso = $filaAnexoIV['lugarRegreso'] . ', ' . $fechaRegreso . ', ' . $horaRegreso;

    $telefonoHospedaje = !empty($filaAnexoIV['telefonoHospedaje']) ? $filaAnexoIV['telefonoHospedaje'] : '-';

    $pdf = new FPDF();
    
    $pdf->SetMargins(20, 20, 20);
    $pdf->SetAutoPageBreak(true, 20);
    
    $pdf->AddPage();

    // Encabezado
    $pdf->Image('../../imagenes/eest.png', 20, 8, 20);
    $pdf->Image('../../imagenes/logoprovincia.jpg', 102, 8, 90); // Logo

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 40, mb_convert_encoding('IF-2024-35030478-GDEBA-CGCYEDG', 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');
    
    $pdf->Ln(-10);

    $pdf->SetFont('Arial', 'B', 15);
    $pdf->Cell(0, 10, mb_convert_encoding('ANEXO VII', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

    $pdf->SetFont('Arial', 'B', 12); 
    $pdf->MultiCell(0, 7, mb_convert_encoding('DECLARACIÓN JURADA DE LA Y EL ESTUDIANTE MAYOR DE 18 AÑOS O EMANCIPADA/O', 'ISO-8859-1', 'UTF-8'), 0, 'C');
    
    $pdf->Ln(5);
    
    if ($filaAnexoIV['tipoSolicitud'] == 1) {
        $tipoSalida = "Salida de Representación Institucional";
    } else {
        $tipoSalida = "Salida Educativa";
    }

    $pdf->SetFont('Arial', '', 12);
    $pdf->MultiCell(0, 8, mb_convert_encoding("Quien suscribe, " . ucwords(strtolower($nombreCompleto)) . " DNI " . $dniAlumno . " domiciliada/o en la calle " . $filaAnexoVII['domicilio'] . " N°" . $filaAnexoVII['altura'] . " de la localidad de " . $filaAnexoVII['localidad'] . " a que concurre a la institución EEST N°1, participará de la ".$tipoSalida." a realizarse en localidad de " . $filaAnexoIV['localidadVisita'] . " el/los dia/s " . $diaSalida . " del mes de " . $mesSalida . " del año " . $anoSalida.".", 'ISO-8859-1', 'UTF-8'), 0);   
    
    $pdf->Ln(5);
    
    $pdf->MultiCell(0, 8, mb_convert_encoding("Dejo constancia de que he sido informada/o de las características de la salida, las actividades a realizar, el modo de traslado, docentes responsables y los lugares donde se desarrollarán las actividades.", 'ISO-8859-1', 'UTF-8'), 0);
    
    $pdf->Ln(5);
    
    $pdf->MultiCell(0, 8, mb_convert_encoding("Autorizo a las y los responsables de la salida a disponer cambios con relación a la planificación de las actividades en aspectos acotados, que resulten necesarios, a su solo criterio y sin previo aviso, sobre lo cual seré informada/o durante el desarrollo de la salida.", 'ISO-8859-1', 'UTF-8'), 0);
    
    $pdf->Ln(5);

    $pdf->MultiCell(0, 8, mb_convert_encoding("Tomo conocimiento de que las y los docentes a cargo de la organización de la salida no son responsables de los objetos que llevo conmigo.", 'ISO-8859-1', 'UTF-8'), 0);
    
    $pdf->Ln(5);
    
    $pdf->MultiCell(0, 8, mb_convert_encoding("Tomo conocimiento de que las y los docentes a cargo de la organización de la salida no son responsables de los objetos que llevo conmigo.", 'ISO-8859-1', 'UTF-8'), 0);
    
    $pdf->Ln(5);
    
    $pdf->MultiCell(0, 8, mb_convert_encoding("Dejo aquí constancia de cualquier indicación necesaria deba conocer el personal docente a cargo y personal médico: ".$filaAnexoVII['observaciones'], 'ISO-8859-1', 'UTF-8'), 0);
    
    $pdf->AddPage();

    $pdf->MultiCell(0, 8, mb_convert_encoding("Asimismo autorizo, en caso de necesidad y urgencia, a que se realice una consulta médica y la adopción de las prescripciones que las y los profesionales de la salud indiquen.", 'ISO-8859-1', 'UTF-8'), 0);

    $pdf->Ln(5);

    $pdf->MultiCell(35, 10, mb_convert_encoding("¿Tiene Obra\nSocial/Prepaga?", 'ISO-8859-1', 'UTF-8'), 1, 'C');
    $pdf->SetXY($pdf->GetX() + 35, $pdf->GetY() - 20);

    $pdf->SetFillColor(0, 0, 0);

    if ($filaAnexoVII['obraSocial'] == 0){
        $pdf->Cell(10, 20, mb_convert_encoding('Sí', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', true);
        $pdf->Cell(10, 20, mb_convert_encoding('No', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C');
    }
    else{
        $pdf->Cell(10, 20, mb_convert_encoding('Sí', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C');
        $pdf->Cell(10, 20, mb_convert_encoding('No', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', true);
    }

    $pdf->Cell(50, 10, 'Nombre (Obra Social):', 1, 0, 'C');
    $pdf->Cell(0, 10, ' ' . $filaAnexoVII['nombreObraSocial'], 1, 1, 'L'); 
    $pdf->Cell(55, 10, '', 0, 0, 'C'); 
    $pdf->Cell(50, 10, mb_convert_encoding('Nº Socio:', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C');
    $pdf->Cell(0, 10, ' ' . $filaAnexoVII['numeroAfiliado'], 1, 1, 'L');

    $pdf->Ln(10);

    $pdf->Cell(34, 10, mb_convert_encoding('Teléfonos de contacto en caso de urgencia (Consignar varios):', 'ISO-8859-1', 'UTF-8'), 0, 1);

    $telefArray = explode(',', $filaAnexoVII['telefonos']);
    $arrayValores = array_map('trim', $telefArray);
    
    foreach ($arrayValores as $fila => $telefono) {
        $pdf->Cell(5, 8, chr(149), 0, 0);  // Viñeta
        $pdf->Cell(0, 8, mb_convert_encoding($telefono, 'ISO-8859-1', 'UTF-8'), 0, 1);
    }
    
    $pdf->Ln(10);

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 5, mb_convert_encoding('Firma, aclaración y DNI (madre, padre o adulto responsable):', 'ISO-8859-1', 'UTF-8'), 0, 1);

    $pdf->Ln(50);

    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(85, 5, mb_convert_encoding('.......................................................................', 'ISO-8859-1', 'UTF-8'), 0, 0, 'C');
    $pdf->Cell(85, 5, mb_convert_encoding('.......................................................................', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

    $pdf->Cell(85, 5, mb_convert_encoding('Firma', 'ISO-8859-1', 'UTF-8'), 0, 0, 'C');
    $pdf->Cell(85, 5, mb_convert_encoding('Aclaración', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

    $pdf->Ln(15);

    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(85, 5, mb_convert_encoding('.......................................................................', 'ISO-8859-1', 'UTF-8'), 0, 0, 'C');
    $pdf->Cell(85, 5, mb_convert_encoding('.......................................................................', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

    $pdf->Cell(85, 5, mb_convert_encoding('DNI', 'ISO-8859-1', 'UTF-8'), 0, 0, 'C');
    $pdf->Cell(85, 5, mb_convert_encoding('Vinculo', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
    
    $pdf->Ln(15); 

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell('0', 10, mb_convert_encoding('Fecha: ....../....../..........', 'ISO-8859-1', 'UTF-8'), 0, 1);
    
    $pdf->Output('I', 'anexoVI.pdf');
?>

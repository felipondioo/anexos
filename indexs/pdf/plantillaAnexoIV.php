<?php
    require ('fpdf/fpdf.php'); // Asegúrate de que esta ruta sea correcta
    include ('../../php/verificarSessionPDF.php');
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $idSalida = $_SESSION['idSalida'];

    $sql = "SELECT 
            aiv.*, 
            GROUP_CONCAT(av.apellidoNombre SEPARATOR ',\n') AS nombresConcatenados 
            FROM anexoiv aiv 
            LEFT JOIN anexov av ON av.fkAnexoIV = aiv.idAnexoIV AND av.cargo = 2 
            WHERE aiv.idAnexoIV = $idSalida 
            GROUP BY aiv.idAnexoIV";

    $resultado = mysqli_query($conexion, $sql);
    $fila = mysqli_fetch_assoc($resultado);

    // Obtener nombres de docentes titulares y suplentes por separado
    $sqlTitulares = "SELECT apellidoNombre FROM anexov WHERE fkAnexoIV = $idSalida AND cargo = 2";
    $resultadoTitulares = mysqli_query($conexion, $sqlTitulares);

    $sqlSuplentes = "SELECT apellidoNombre FROM anexov WHERE fkAnexoIV = $idSalida AND cargo = 5";
    $resultadoSuplentes = mysqli_query($conexion, $sqlSuplentes);

    // Formatear fechas de regreso y salida de YY-MM-DD a DD-MM-YY
    $fechaSalida = date('d/m/Y', strtotime($fila['fechaSalida']));
    $fechaRegreso = date('d/m/Y', strtotime($fila['fechaRegreso']));

    // Contar cantidad de personas
    $sqlContarPersonas = "SELECT cargo, COUNT(*) as cantidad FROM anexov WHERE fkAnexoIV = $idSalida GROUP BY cargo";
    $resultadoContar = mysqli_query($conexion, $sqlContarPersonas);

    $cantidadAlumnos = 0;
    $cantidadDocentes = 0;
    $cantidadAcompañantes = 0;

    while ($row = mysqli_fetch_assoc($resultadoContar)) {
        switch ($row['cargo']) {
            case 2: $cantidadDocentes = $row['cantidad']; break;
            case 3: $cantidadAlumnos = $row['cantidad']; break;
            case 4: $cantidadAcompañantes = $row['cantidad']; break;
        }
    }

    $totalPersonas = $cantidadAlumnos + $cantidadDocentes + $cantidadAcompañantes;

    // Crear PDF
    $pdf = new FPDF();
    $pdf->SetMargins(20, 20, 20);
    // Establecer margen inferior
    $pdf->SetAutoPageBreak(true, 20);
    
    $pdf->AddPage();

    // Encabezado
    $pdf->Image('../../imagenes/eest.png', 20, 15, 20); // Logo
    $pdf->Image('../../imagenes/logoprovincia.jpg', 102, 15, 90); // Logo

    // Ajusta el texto del encabezado usando mb_convert_encoding para convertir a ISO-8859-1
    $pdf->SetFont('Arial', '', 12);
    
    $pdf->Ln(10);

    $pdf->Cell(0, 30, mb_convert_encoding('IF-2024-35029395-GDEBA-CGCYEDGCYE', 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');

    // Texto ANEXO IV
    $pdf->SetFont('Arial', 'B', 15);
    $pdf->Cell(0, 10, mb_convert_encoding('ANEXO IV', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

    // Subtítulo
    $pdf->SetFont('Arial', 'B', 13);
    $pdf->Cell(0, 10, mb_convert_encoding('Presentación y solicitud para la realización de:', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

    $texto = 'Salida Educativa / Salida de Representación Institucional';
    if ($fila['tipoSolicitud'] == 1) {
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, mb_convert_encoding($texto, 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

        $pdf->SetLineWidth(0.4); // Ancho de la línea
        // Tachar el texto
        $pdf->Line(82, 85 , 46, 85);
    } else {
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, mb_convert_encoding($texto, 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

        $pdf->SetLineWidth(0.4); // Ancho de la línea
        // Tachar el texto
        $pdf->Line(163, 85 , 84, 85);
    }

    $pdf->Cell(0, 10, mb_convert_encoding('FORMULARIO DE ITINERARIO, ACTIVIDADES, OBJETIVOS Y CRONOGRAMA', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

    $pdf->Ln(3);

    $pdf->SetFont('Arial', '', 12);

    $pdf->Cell(0, 10, mb_convert_encoding('Región: '.$fila['region'], 'ISO-8859-1', 'UTF-8'), 0, 1);

    $pdf->Cell(0, 10, mb_convert_encoding('Distrito: '.$fila['distrito'], 'ISO-8859-1', 'UTF-8'), 0, 1);

    $pdf->Cell(125, 10, mb_convert_encoding('Institución Educativa: '.$fila['institucionEducativa'], 'ISO-8859-1', 'UTF-8'), 0, 0);

    $pdf->Cell(0, 10, mb_convert_encoding('N°: '.$fila['numeroInstitucion'], 'ISO-8859-1', 'UTF-8'), 0, 1);

    $pdf->Cell(125, 10, mb_convert_encoding('Domicilio: '.$fila['domicilioInstitucion'], 'ISO-8859-1', 'UTF-8'), 0, 0);

    $pdf->Cell(0, 10, mb_convert_encoding('Teléfono: '.$fila['telefonoInstitucion'], 'ISO-8859-1', 'UTF-8'), 0, 1);

    $pdf->Cell(0, 10, mb_convert_encoding('Denominacion del Proyecto: '.$fila['denominacionProyecto'], 'ISO-8859-1', 'UTF-8'), 0, 1);

    $pdf->Cell(0, 10, mb_convert_encoding('Lugar a visitar: '.$fila['lugarVisita'], 'ISO-8859-1', 'UTF-8'), 0, 1);

    $pdf->Cell(0, 10, mb_convert_encoding('Dirección a visitar: '.$fila['direccionVisita'], 'ISO-8859-1', 'UTF-8'), 0, 1);

    $pdf->Cell(0, 10, mb_convert_encoding('Localidad a visitar: '.$fila['localidadVisita'], 'ISO-8859-1', 'UTF-8'), 0, 1);

    $pdf->Cell(0, 10, mb_convert_encoding('Región a visitar: '.$fila['regionVisita'], 'ISO-8859-1', 'UTF-8'), 0, 1);
    
    $pdf->SetFont('Arial', '', 13);
    $pdf->Cell(20, 15, mb_convert_encoding('SALIDA', 'ISO-8859-1', 'UTF-8'), 0, 1);

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(15, 5, mb_convert_encoding('Fecha:', 'ISO-8859-1', 'UTF-8'), 0, 0);
    $pdf->Cell(25, 5, $fechaSalida, 0, 0);

    $pdf->Cell(12, 5, mb_convert_encoding('Hora:', 'ISO-8859-1', 'UTF-8'), 0, 0);
    $pdf->Cell(20, 5, mb_convert_encoding($fila['horaSalida'], 'ISO-8859-1', 'UTF-8'), 0, 0);

    $pdf->Cell(15, 5, mb_convert_encoding('Lugar:', 'ISO-8859-1', 'UTF-8'), 0, 0);
    $pdf->Cell(86, 5, mb_convert_encoding($fila['lugarSalida'], 'ISO-8859-1', 'UTF-8'), 0, 0);
    
    $pdf->Ln(5);

    $pdf->SetFont('Arial', '', 13);
    $pdf->Cell(20, 15, mb_convert_encoding('REGRESO', 'ISO-8859-1', 'UTF-8'), 0, 1);

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(15, 5, mb_convert_encoding('Fecha:', 'ISO-8859-1', 'UTF-8'), 0, 0);
    $pdf->Cell(25, 5, $fechaRegreso, 0, 0);

    $pdf->Cell(12, 5, mb_convert_encoding('Hora:', 'ISO-8859-1', 'UTF-8'), 0, 0);
    $pdf->Cell(20, 5, mb_convert_encoding($fila['horaRegreso'], 'ISO-8859-1', 'UTF-8'), 0, 0);

    $pdf->Cell(15, 5, mb_convert_encoding('Lugar:', 'ISO-8859-1', 'UTF-8'), 0, 0);
    $pdf->Cell(20, 5, mb_convert_encoding($fila['lugarRegreso'], 'ISO-8859-1', 'UTF-8'), 0, 1);
    
    $pdf->AddPage();

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(15, 5, mb_convert_encoding('Observaciones respecto a las fechas (en caso de corresponder):', 'ISO-8859-1', 'UTF-8'), 0, 1);
    
    $pdf->Ln(40);

    $pdf->SetFont('Arial', '', 12);
    $pdf->MultiCell(0, 10, mb_convert_encoding('Itinerario (detalle pormenorizado del mismo): '.$fila['itinerario'], 'ISO-8859-1', 'UTF-8'));
    $pdf->Ln(5);

    $pdf->MultiCell(0, 10, mb_convert_encoding('Actividades: '.$fila['actividades'], 'ISO-8859-1', 'UTF-8'));
    $pdf->Ln(5);

    $pdf->MultiCell(0, 10, mb_convert_encoding('Objetivos de la salida: '.$fila['objetivosSalida'], 'ISO-8859-1', 'UTF-8'));
    $pdf->Ln(5);

    $pdf->MultiCell(0, 10, mb_convert_encoding('Cronograma diario: '.$fila['cronograma'], 'ISO-8859-1', 'UTF-8'));
    $pdf->Ln(5);

    $pdf->Cell(0, 10, mb_convert_encoding('Datos del/los docente/s responsables titulares', 'ISO-8859-1', 'UTF-8'), 0, 1);
    
    while ($row = mysqli_fetch_assoc($resultadoTitulares)) {
        $nombre = ucwords(strtolower($row['apellidoNombre']));
        $pdf->Cell(120, 8, mb_convert_encoding("Apellido y Nombre: ".$nombre.", ".$_SESSION['dniProfesor'], 'ISO-8859-1', 'UTF-8'), 0, 0);
        $pdf->Cell(0, 8, mb_convert_encoding('Cargo: Docente titular', 'ISO-8859-1', 'UTF-8'), 0, 1);
    }

    // Datos de los docentes suplentes
    $pdf->Ln(5);
    $pdf->Cell(0, 10, mb_convert_encoding('Datos del/los docente/s reemplazantes', 'ISO-8859-1', 'UTF-8'), 0, 1);

    while ($row = mysqli_fetch_assoc($resultadoSuplentes)) {
        $nombre = ucwords(strtolower($row['apellidoNombre']));
        $pdf->Cell(120, 8, mb_convert_encoding("Apellido y Nombre: $nombre", 'ISO-8859-1', 'UTF-8'), 0, 0);
        $pdf->Cell(0, 8, mb_convert_encoding('Cargo: Docente suplente', 'ISO-8859-1', 'UTF-8'), 0, 1);
    }

    $pdf->Ln(5);

    $pdf->Cell(0, 10, mb_convert_encoding('Cantidad de alumnos: '.$cantidadAlumnos, 'ISO-8859-1', 'UTF-8'), 0, 1);

    $pdf->Cell(0, 10, mb_convert_encoding('Cantidad de docentes acompañantes: '.$cantidadDocentes, 'ISO-8859-1', 'UTF-8'), 0, 1);

    $pdf->Cell(0, 10, mb_convert_encoding('Cantidad de no docentes acompañantes: '.$cantidadAcompañantes, 'ISO-8859-1', 'UTF-8'), 0, 1);

    $pdf->Cell(0, 10, mb_convert_encoding('Total de Personas: '.$totalPersonas, 'ISO-8859-1', 'UTF-8'), 0, 1);

    $pdf->AddPage();
    
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, mb_convert_encoding('Sólo para salidas de más de 24 horas', 'ISO-8859-1', 'UTF-8'), 0, 1);
    
    $pdf->Ln(2);

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(25, 10, mb_convert_encoding('Hospedaje:', 'ISO-8859-1', 'UTF-8'), 0, 0);
    $pdf->Cell(90, 10, mb_convert_encoding($fila['nombreHospedaje'], 'ISO-8859-1', 'UTF-8'), 0, 0);

    $pdf->Cell(21, 10, mb_convert_encoding('Teléfono:', 'ISO-8859-1', 'UTF-8'), 0, 0);

    $value = ($fila['telefonoHospedaje'] == 0) ? '-' : $fila['telefonoHospedaje'];
    $pdf->Cell(0, 10, mb_convert_encoding($value, 'ISO-8859-1', 'UTF-8'), 0, 1);
    
    $pdf->Ln(5);    

    $pdf->Cell(22, 10, mb_convert_encoding('Domicilio:', 'ISO-8859-1', 'UTF-8'), 0, 0);
    $pdf->Cell(93, 10, mb_convert_encoding($fila['domicilioHospedaje'], 'ISO-8859-1', 'UTF-8'), 0, 0);

    $pdf->Cell(23, 10, mb_convert_encoding('Localidad:', 'ISO-8859-1', 'UTF-8'), 0, 0);
    $pdf->Cell(0, 10, mb_convert_encoding($fila['localidadHospedaje'], 'ISO-8859-1', 'UTF-8'), 0, 1);

    $pdf->Ln(5);

    $pdf->MultiCell(0, 10, mb_convert_encoding('Gastos estimativos de la actividad y modo de solventarlos: '.$fila['gastosEstimativos'], 'ISO-8859-1', 'UTF-8'));

    $pdf->Ln(40);

    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(85, 5, mb_convert_encoding('.......................................................................', 'ISO-8859-1', 'UTF-8'), 0, 0, 'C');
    $pdf->Cell(85, 5, mb_convert_encoding('.......................................................................', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

    $pdf->Cell(85, 5, mb_convert_encoding('Lugar y fecha', 'ISO-8859-1', 'UTF-8'), 0, 0, 'C');
    $pdf->Cell(85, 5, mb_convert_encoding('Lugar y fecha', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

    $pdf->Ln(5);

    $pdf->Cell(85, 5, mb_convert_encoding('Firma y alcaración de la Autoridad del Establecimiento', 'ISO-8859-1', 'UTF-8'), 0, 0, 'C');
    $pdf->Cell(85, 5, mb_convert_encoding('Firma del Inspector (si correspondiere):', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

    $pdf->Cell(85, 5, mb_convert_encoding('que completó este formulario:', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

    $pdf->Ln(50);

    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(85, 5, mb_convert_encoding('.......................................................................', 'ISO-8859-1', 'UTF-8'), 0, 0, 'C');
    $pdf->Cell(85, 5, mb_convert_encoding('.......................................................................', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

    $pdf->Cell(85, 5, mb_convert_encoding('Lugar y fecha', 'ISO-8859-1', 'UTF-8'), 0, 0, 'C');
    $pdf->Cell(85, 5, mb_convert_encoding('Lugar y fecha', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

    $pdf->Ln(5);

    $pdf->Cell(85, 5, mb_convert_encoding('Firma del Jefe Distrital (si correspondiere):', 'ISO-8859-1', 'UTF-8'), 0, 0, 'C');
    $pdf->Cell(85, 5, mb_convert_encoding('Firma del Jefe Distrital (si correspondiere):', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
    
    $pdf->Ln(10);

    $pdf->SetFont('Arial', '', 12);
    $pdf->MultiCell(0, 10, mb_convert_encoding('El presente formulario debe ser completado de forma digital por un integrante del Equipo Directivo, y enviado al/la Inspector/a en este formato.', 'ISO-8859-1', 'UTF-8'));
    $pdf->MultiCell(0, 10, mb_convert_encoding('El presente formulario deberá estar completo por duplicado (Uno para la institución otro la para la instancia de Supervisión)', 'ISO-8859-1', 'UTF-8'));

    $pdf->Output('I', 'anexoIV.pdf');
?>

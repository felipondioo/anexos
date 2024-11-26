<?php 
require('fpdf/fpdf.php');
include('../../php/verificarSessionPDF.php');

    $pdf = new FPDF();
    $pdf->SetMargins(20, 20, 20, 20);
    // Establecer margen inferior
    $pdf->SetAutoPageBreak(true, 20);
    $pdf->AddPage();
        
    //datos
    $nombreDirector = ucwords(strtolower($_SESSION['nombreDir'] . ' ' . $_SESSION['apellidoDir']));
    $idSalida = $_SESSION['idSalida'];
    $sql = "SELECT 
        tipoSolicitud, 
        denominacionProyecto, 
        DATE_FORMAT(fechaSalida, '%d/%m/%Y') AS fechaSalida, 
        lugarVisita,
        direccionVisita, 
        localidadVisita 
    FROM 
        anexoiv 
    WHERE 
        idAnexoIV =  $idSalida;
    "
    ;

    $result = $conexion->query($sql);

    // Verificar si se encontraron resultados
    if ($result->num_rows > 0) {
        // Obtener la fila de resultados
        $rowAnexoIX = $result->fetch_assoc();

        // Concatenar los valores en el formato solicitado
        $textoAnexoIX = $rowAnexoIX['fechaSalida'] . ', ' . $rowAnexoIX['lugarVisita'] . ', ' . $rowAnexoIX['localidadVisita'] . ', ' . $rowAnexoIX['direccionVisita'];

    } else {
        echo "No se encontraron resultados para el id proporcionado.";
    }

    // Consulta para obtener los datos principales
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

    $sqlInfoAIV = "SELECT * FROM anexoiv WHERE idAnexoIV = $idSalida";
    $resultado = mysqli_query($conexion, $sqlInfoAIV);
    $fila = mysqli_fetch_assoc($resultado);

    $sqlIntegrantes = "SELECT * FROM anexov WHERE cargo != 5";
    $resultadoIntegrantes = mysqli_query($conexion, $sqlIntegrantes);

    $fechaSalida = date('d/m/Y', strtotime($fila['fechaSalida']));

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
        $pdf->Cell(120, 8, mb_convert_encoding("Apellido y Nombre: $nombre", 'ISO-8859-1', 'UTF-8'), 0, 0);
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

    $pdf->Ln(10);    

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

    $pdf->SetFont('Arial', '', 11);
    $pdf->MultiCell(0, 10, mb_convert_encoding('El presente formulario debe ser completado de forma digital por un integrante del Equipo Directivo, y enviado al/la Inspector/a en este formato.', 'ISO-8859-1', 'UTF-8'));
    $pdf->MultiCell(0, 10, mb_convert_encoding('El presente formulario deberá estar completo por duplicado (Uno para la institución otro la para la instancia de Supervisión)', 'ISO-8859-1', 'UTF-8'));

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////Anexo  V///////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////

    $pdf->AddPage();

    $pdf->Image('../../imagenes/eest.png', 20, 15, 20); // Logo
    $pdf->Image('../../imagenes/logoprovincia.jpg', 102, 15, 90); // Logo

    $pdf->SetFont('Arial', '', 12);
    $pdf->Ln(10);

    $pdf->Cell(0, 30, mb_convert_encoding('IF-2024-35029666-GDEBA-CGCYEDGCYE', 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');
    $pdf->SetFont('Arial', 'B', 15);
    $pdf->Cell(0, 10, mb_convert_encoding('ANEXO V', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
    $pdf->Ln(2);

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 5, mb_convert_encoding('PLANILLA DE ESTUDIANTES Y ACOMPAÑANTES', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
    $pdf->Cell(0, 10, mb_convert_encoding('PLANILLA DE ASISTENCIA', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
    $pdf->Ln(5);

    $pdf->SetFont('Arial', '', 12);
    $pdf->MultiCell(0, 5, mb_convert_encoding('(La presente deberá incorporarse al libro de Registro de Actas Institucionales, antes de producirse la salida).', 'ISO-8859-1', 'UTF-8'), 0);
    $pdf->Ln(5);
    
    $pdf->Cell(133, 10, mb_convert_encoding('Institución Educativa: '.$fila['institucionEducativa'], 'ISO-8859-1', 'UTF-8'), 0, 0);

    $pdf->Cell(8, 10, mb_convert_encoding('N°: '.$fila['numeroInstitucion'], 'ISO-8859-1', 'UTF-8'), 0, 1);

    $pdf->Cell(18, 10, mb_convert_encoding('Distrito: '.$fila['distrito'], 'ISO-8859-1', 'UTF-8'), 0, 1);

    $pdf->Cell(133, 10, mb_convert_encoding('Lugar a visitar: '.$fila['lugarVisita'], 'ISO-8859-1', 'UTF-8'), 0, 0);

    $pdf->Cell(15, 10, mb_convert_encoding('Fecha: '.$fechaSalida, 'ISO-8859-1', 'UTF-8'), 0, 1);

    $pdf->SetMargins(5, 15, 0);

    $pdf->Ln(5);
    $header = array('N°', 'Apellido y Nombre', 'DNI', 'Edad', 'Estudiante', 'Docente', 'No docente', 'Asistencia (P/A)');
    $widths = array(8, 73, 18, 12, 21, 16, 22, 30);

    $pdf->SetFont('Arial', 'B', 10);
    foreach ($header as $key => $column) {
        $pdf->Cell($widths[$key], 10, mb_convert_encoding($column, 'ISO-8859-1', 'UTF-8'), 1, 0, 'C');
    }
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 10);
    $data = array();

    $counter = 1;
    while ($registro = mysqli_fetch_assoc($resultadoIntegrantes)) {
        switch($registro['cargo']){
            case 0:
                $alumnoX = "Indefinido";
                $docenteX = "Indefinido";
                $noDOcenteX = "Indefinido";
            break;
            case 2:
                $alumnoX = '';
                $docenteX = 'X';
                $noDOcenteX = '';
            break;
            case 3:
                $alumnoX = 'X';
                $docenteX = '';
                $noDOcenteX = '';
            break;
            case 4:
                $alumnoX = '';
                $docenteX = '';
                $noDOcenteX = 'X';
            break;
        }
        $data[] = array(
            $counter,
            ucwords(strtolower($registro['apellidoNombre'])),
            $registro['dni'],
            $registro['edad'],
            $alumnoX,
            $docenteX,
            $noDOcenteX,
            ''
        );
        $counter++;
    }

    foreach ($data as $row) {
        foreach ($row as $key => $column) {
            $pdf->Cell($widths[$key], 10, mb_convert_encoding($column, 'ISO-8859-1', 'UTF-8'), 1, 0, 'C');
        }
        $pdf->Ln();
    }

    $pdf->SetMargins(20, 20, 20);

    $pdf->AddPage();

    $pdf->SetFont('Arial', '', 12);
    $pdf->MultiCell(0, 8, mb_convert_encoding('Observaciones (para ser completado ante cualquier eventualidad):', 'ISO-8859-1', 'UTF-8'));

    $pdf->Ln(50);
    
    $pdf->Cell(0, 5, mb_convert_encoding('La presente planilla tendrá validez para toda tramitación oficial que se realice.', 'ISO-8859-1', 'UTF-8'), 0, 1);

    $pdf->Ln(50);

    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(82, 5, mb_convert_encoding('.......................................................................', 'ISO-8859-1', 'UTF-8'), 0, 0, 'C');
    $pdf->Cell(95, 5, mb_convert_encoding('.......................................................................', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

    $pdf->Cell(82, 5, mb_convert_encoding('Lugar y fecha', 'ISO-8859-1', 'UTF-8'), 0, 0, 'C');
    $pdf->Cell(95, 5, mb_convert_encoding('Lugar y fecha', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

    $pdf->Ln(2);

    $pdf->Cell(82, 5, mb_convert_encoding('Firma y alcaración de la Autoridad del Establecimiento', 'ISO-8859-1', 'UTF-8'), 0, 0, 'C');
    $pdf->Cell(95, 5, mb_convert_encoding('Firma del Inspector:', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
    $pdf->Cell(82, 5, mb_convert_encoding('que completó este formulario:', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

    $pdf->Ln(20);

    $pdf->SetFont('Arial', '', 11);
    $pdf->MultiCell(0, 8, mb_convert_encoding('El presente formulario deberá estar completo por duplicado (Uno para la Institución y otro para la instancia de Supervisión).', 'ISO-8859-1', 'UTF-8'));
    $pdf->MultiCell(0, 8, mb_convert_encoding('El presente formulario puede ser completado de forma digital y enviado al/la Inspector/a en este formato.', 'ISO-8859-1', 'UTF-8'));
    $pdf->MultiCell(0, 8, mb_convert_encoding('El mismo debe ser impreso y firmado para la Salida Educativa, registrando ese mismo día las asistencias o inasistencias, tanto de estudiantes como de docentes o acompañantes no docentes, así como cualquier modificación de último momento (docentes reemplazantes, etc.).', 'ISO-8859-1', 'UTF-8'));

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////Anexo  IX///////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////

    $pdf->SetMargins(20, 20, 20);
    $pdf->SetAutoPageBreak(true, 20);
    $pdf->AddPage();

    // Encabezado
    $pdf->Image('../../imagenes/eest.png', 20, 15, 20); // Logo
    $pdf->Image('../../imagenes/logoprovincia.jpg', 102, 15, 90); // Logo

    $pdf->Ln(10);

    $pdf->Cell(0, 30, mb_convert_encoding('IF-2024-35029666-GDEBA-CGCYEDGCYE', 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');
    $pdf->SetFont('Arial', 'B', 15);
    $pdf->Cell(0, 10, mb_convert_encoding('ANEXO IX', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
    
    $pdf->Ln(2);

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, mb_convert_encoding('DECLARACIÓN JURADA DE DIRECTIVOS Y/O REPRESENTANTES LEGALES', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
    $pdf->Cell(0, 10, mb_convert_encoding('SALIDAS EDUCATIVAS / DE REPRESENTACIÓN INSTITUCIONAL', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

    $pdf->Ln(5);

    $pdf->SetFont('Arial', '', 12);

    $pdf->MultiCell(0, 10, mb_convert_encoding('Nombre del Establecimiento: Escuela de Educación Raul Scalabrini Ortiz Nº1', 'ISO-8859-1', 'UTF-8'), 0, 1);
   
    $pdf->Cell(0, 10, mb_convert_encoding('CUE: 061806600', 'ISO-8859-1', 'UTF-8'), 0, 1);

    $pdf->Cell(0, 10, mb_convert_encoding('Domicilio: Calle 124 N°345', 'ISO-8859-1', 'UTF-8'), 0, 1);

    $pdf->Cell(0, 10, mb_convert_encoding('Distrito: La Costa', 'ISO-8859-1', 'UTF-8'), 0, 1);

    $pdf->Cell(0, 10, mb_convert_encoding('Director/a del Establecimiento: '.$nombreDirector, 'ISO-8859-1', 'UTF-8'), 0, 1);

    $pdf->Cell(0, 10, mb_convert_encoding('Representante Legal (DIEGEP): ___________________________________________', 'ISO-8859-1', 'UTF-8'), 0, 1);

    $pdf->Cell(0, 10, mb_convert_encoding('Entidad Propietaria (DIEGEP): ___________________________________________', 'ISO-8859-1', 'UTF-8'), 0, 1);

    if ($rowAnexoIX['tipoSolicitud'] == 1) {
        $tipoSalida = "Salida de Representación Institucional";
    } else {
        $tipoSalida = "Salida Educativa";
    }

    $pdf->MultiCell(0, 10, mb_convert_encoding('Fecha y lugar de la realización de la '.$tipoSalida.': '.$textoAnexoIX, 'ISO-8859-1', 'UTF-8'), 0, 1);
        
    $anioActual = date("Y");

    $pdf->Ln(5);

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, mb_convert_encoding('DATOS DEL SEGURO ESCOLAR:', 'ISO-8859-1', 'UTF-8'), 0, 1);
    
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, mb_convert_encoding('Compañía Aseguradora: Sancor Seguros', 'ISO-8859-1', 'UTF-8'), 0, 1);

    $pdf->Cell(0, 10, mb_convert_encoding('Tipo (1): Escolar', 'ISO-8859-1', 'UTF-8'), 0, 1);
    
    $pdf->Cell(0, 10, mb_convert_encoding('Póliza N°: 11966714', 'ISO-8859-1', 'UTF-8'), 0, 1);
    
    $pdf->Cell(0, 10, mb_convert_encoding('Vigencia: '.$anioActual, 'ISO-8859-1', 'UTF-8'), 0, 1);
    
    $pdf->Cell(0, 10, mb_convert_encoding('Compañía Aseguradora: Provincia Seguros', 'ISO-8859-1', 'UTF-8'), 0, 1);
    
    $pdf->AddPage();

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, mb_convert_encoding('Tipo de seguro (1) Responsabilidad civil para alumnos menores:', 'ISO-8859-1', 'UTF-8'), 0, 1);
    
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, mb_convert_encoding('Póliza N°: 121966', 'ISO-8859-1', 'UTF-8'), 0, 1);

    $pdf->Cell(0, 10, mb_convert_encoding('Vigencia: '.$anioActual, 'ISO-8859-1', 'UTF-8'), 0, 1);

    $pdf->Cell(0, 10, mb_convert_encoding('Compañía Aseguradora: Provincia Seguros: ', 'ISO-8859-1', 'UTF-8'), 0, 1);
    
    $pdf->Ln(5);

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, mb_convert_encoding('Tipo de seguro (1) Responsabilidad civil para mayores de 18 años:', 'ISO-8859-1', 'UTF-8'), 0, 1);

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, mb_convert_encoding('Póliza N°: 121713', 'ISO-8859-1', 'UTF-8'), 0, 1);
    
    $pdf->Cell(0, 10, mb_convert_encoding('Vigencia: '.$anioActual, 'ISO-8859-1', 'UTF-8'), 0, 1);
    
    $pdf->Cell(0, 10, mb_convert_encoding('Observaciones (2):', 'ISO-8859-1', 'UTF-8'), 0, 1);
    $guion = "__________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________";
    $pdf->MultiCell(0, 10, mb_convert_encoding($guion, 'ISO-8859-1', 'UTF-8'), 0, 1);

    $pdf->Ln(5);

    $pdf->MultiCell(0, 10, mb_convert_encoding("En nuestro carácter de Director/a y/o Representante Legal del establecimiento educativo de referencia declaramos bajo juramento haber dado cumplimiento a los requerimientos del Anexo II de la Resolución de Salidas Educativas/ de Representación Institucional referidos a: Lugar de realización de la Salida Educativa y Salida de Representación Institucional, transporte, autorizaciones de madre, padre, tutor o responsable de los/as estudiantes con menos de 18 años de edad y Declaración Jurada de las/los estudiantes mayores de 18 años; garantizando el cumplimiento de la relación docente/estudiante y la información a los responsables legales de la Salida Educativa / Salida de Representación Institucional que realizará la institución educativa en el marco de su Proyecto Institucional.", 'ISO-8859-1', 'UTF-8'), 0, 1);
    
    $pdf->AddPage();

    $pdf->MultiCell(0, 10, mb_convert_encoding("Ponemos asimismo a disposición de la autoridad educativa que lo requiera los Anexos III, IV, V, VI, VII, VIII y IX completos (según corresponda, conforme lo estipulado en el Anexo II), como así también el libro de Actas Institucionales.", 'ISO-8859-1', 'UTF-8'), 0, 1);

    $pdf->Ln(5);

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, mb_convert_encoding('Aclaración:', 'ISO-8859-1', 'UTF-8'), 0, 1);
    $pdf->SetFont('Arial', '', 12);
    $declaracion = "El presente formulario debe ser completado por el/la directora/a. Y en el caso de las Escuelas Públicas de Gestión Privada, también por el/la Representante Legal. Este Anexo debe ser transcripto en puño y letra en el Libro de Actas Institucional de Salidas Educativas, con firma original. Para ser enviado al/la Inspector/a y al Seguro Escolar puede hacerse con el formato digital o bien sacarle una foto a la transcripción realizada en el Libro de Actas y enviar en formato PDF.";
    $pdf->MultiCell(0, 7, mb_convert_encoding($declaracion, 'ISO-8859-1', 'UTF-8'), 0, 1);

    $pdf->Ln(10);
    
    $pdf->Cell(0, 10, mb_convert_encoding('1) Deberá constar en la póliza que el tipo de seguro es de responsabilidad civil.', 'ISO-8859-1', 'UTF-8'), 0, 1);
    $pdf->MultiCell(0, 7, mb_convert_encoding('2) En este rubro se dejará constancia de todas las situaciones que puedan surgir y no estén previstas en el presente formulario.', 'ISO-8859-1', 'UTF-8'), 0, 1);
    
    $pdf->Cell(0, 40, mb_convert_encoding('Lugar y fecha:..........................................................................................', 'ISO-8859-1', 'UTF-8'), 0, 1);
    
    $pdf->Ln(40);

    $halfPageWidth = $pdf->GetPageWidth() / 2 - 20; // Resta márgenes si los hay

    // Línea de "FIRMA Y SELLO"
    $pdf->Cell($halfPageWidth, 5, mb_convert_encoding('FIRMA Y SELLO', 'ISO-8859-1', 'UTF-8'), 0, 0, 'C');
    $pdf->Cell($halfPageWidth, 5, mb_convert_encoding('FIRMA Y SELLO', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

    // Línea de "DIRECTOR/A" y "REPRESENTANTE LEGAL"
    $pdf->Cell($halfPageWidth, 5, mb_convert_encoding('DIRECTOR/A', 'ISO-8859-1', 'UTF-8'), 0, 0, 'C');
    $pdf->Cell($halfPageWidth, 5, mb_convert_encoding('REPRESENTANTE LEGAL', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

    $pdf->Output('I', 'anexoIX.pdf');
?>

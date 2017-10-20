<?php
//    Sectores...
function sector($pdf)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    //setLogo($pdf);
    $pdf->Ln(8);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(190, 10, 'Sector', 0, 1, 'C');
    //Colores de los bordes, fondo y texto
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetFillColor(250, 250, 250);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(20, 5, 'CODIGO', 1, 0, 'C', 1);
    $pdf->Cell(175, 5, 'DENOMINACION', 1, 0, 'C', 1);
    $pdf->Ln();
    $pdf->SetAligns(array('C', 'L'));
    $pdf->SetWidths(array(20, 175));
}

//    Programas...
function programa($pdf)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    $pdf->Ln(8);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(190, 10, 'Programa', 0, 1, 'C');
    //Colores de los bordes, fondo y texto
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetFillColor(250, 250, 250);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(85, 5, 'SECTOR', 1, 0, 'C', 1);
    $pdf->Cell(25, 5, 'CODIGO', 1, 0, 'C', 1);
    $pdf->Cell(85, 5, 'DENOMINACION', 1, 0, 'C', 1);
    $pdf->Ln();
    $pdf->SetAligns(array('L', 'C', 'L'));
    $pdf->SetWidths(array(85, 25, 85));
}

//    Sub-Programas...
function sprograma($pdf)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    $pdf->Ln(8);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(190, 10, 'Sub - Programa', 0, 1, 'C');
    //Colores de los bordes, fondo y texto
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetFillColor(250, 250, 250);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(60, 5, 'SECTOR', 1, 0, 'C', 1);
    $pdf->Cell(60, 5, 'PROGRAMA', 1, 0, 'C', 1);
    $pdf->Cell(15, 5, 'CODIGO', 1, 0, 'C', 1);
    $pdf->Cell(60, 5, 'DENOMINACION', 1, 0, 'C', 1);
    $pdf->Ln();
    $pdf->SetAligns(array('L', 'L', 'C', 'L'));
    $pdf->SetWidths(array(60, 60, 15, 60));
}

//    Proyectos...
function proyecto($pdf)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    $pdf->Ln(8);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(260, 10, 'Proyecto', 0, 1, 'C');
    //Colores de los bordes, fondo y texto
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetFillColor(250, 250, 250);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->Cell(60, 5, 'SECTOR', 1, 0, 'C', 1);
    $pdf->Cell(60, 5, 'PROGRAMA', 1, 0, 'C', 1);
    $pdf->Cell(60, 5, 'SUB-PROGRAMA', 1, 0, 'C', 1);
    $pdf->Cell(20, 5, 'CODIGO', 1, 0, 'C', 1);
    $pdf->Cell(60, 5, 'DENOMINACION', 1, 0, 'C', 1);
    $pdf->Ln();
    $pdf->SetAligns(array('L', 'L', 'L', 'C', 'L'));
    $pdf->SetWidths(array(60, 60, 60, 20, 60));
}

//    Actividades...
function actividad($pdf)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    $pdf->Ln(8);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(335, 10, 'Actividad', 0, 1, 'C');
    //Colores de los bordes, fondo y texto
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetFillColor(250, 250, 250);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->Cell(60, 5, 'SECTOR', 1, 0, 'C', 1);
    $pdf->Cell(60, 5, 'PROGRAMA', 1, 0, 'C', 1);
    $pdf->Cell(60, 5, 'SUB-PROGRAMA', 1, 0, 'C', 1);
    $pdf->Cell(60, 5, 'PROYECTO', 1, 0, 'C', 1);
    $pdf->Cell(20, 5, 'CODIGO', 1, 0, 'C', 1);
    $pdf->Cell(75, 5, 'DENOMINACION', 1, 0, 'C', 1);
    $pdf->Ln();
    $pdf->SetAligns(array('L', 'L', 'L', 'L', 'C', 'L'));
    $pdf->SetWidths(array(60, 60, 60, 60, 20, 75));
}

//    Unidad Ejecutora...
function unidade($pdf)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    $pdf->Ln(8);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(190, 10, 'Unidad Ejecutora', 0, 1, 'C');
    //Colores de los bordes, fondo y texto
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetFillColor(250, 250, 250);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(20, 5, 'CODIGO', 1, 0, 'C', 1);
    $pdf->Cell(100, 5, 'DENOMINACION', 1, 0, 'C', 1);
    $pdf->Cell(75, 5, 'RESPONSABLE', 1, 0, 'C', 1);
    $pdf->Ln();
    $pdf->SetAligns(array('C', 'L', 'L'));
    $pdf->SetWidths(array(20, 100, 75));
}

//    Categoria Programatica...
function catprog($pdf)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    $pdf->Ln(8);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(190, 10, utf8_decode('Categorías Programáticas'), 0, 1, 'C');
    //Colores de los bordes, fondo y texto
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetFillColor(250, 250, 250);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->Cell(25, 5, 'CODIGO', 1, 0, 'C', 1);
    $pdf->Cell(100, 5, 'DENOMINACION', 1, 0, 'C', 1);
    $pdf->Cell(70, 5, 'RESPONSABLE', 1, 0, 'C', 1);
    $pdf->Ln();
    $pdf->SetAligns(array('C', 'L', 'L'));
    $pdf->SetWidths(array(25, 100, 70));
}

//    Indice de Categorias Programaticas...
function icatprog($pdf)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    $pdf->Ln(8);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(190, 10, utf8_decode('Indice de Categorías Programáticas'), 0, 1, 'C');
    //Colores de los bordes, fondo y texto
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetFillColor(250, 250, 250);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->Cell(8, 5, 'SECT', 1, 0, 'C', 1);
    $pdf->Cell(8, 5, 'PROG', 1, 0, 'C', 1);
    $pdf->Cell(8, 5, 'SUBP', 1, 0, 'C', 1);
    $pdf->Cell(8, 5, 'PROY', 1, 0, 'C', 1);
    $pdf->Cell(8, 5, 'ACTI', 1, 0, 'C', 1);
    $pdf->Cell(55, 5, 'DENOMINACION', 1, 0, 'C', 1);
    $pdf->Cell(50, 5, 'UNIDAD EJECUTORA', 1, 0, 'C', 1);
    $pdf->Cell(50, 5, 'FUNCIONARIO RESPONSABLE', 1, 0, 'C', 1);
    $pdf->Ln();
    $pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'L', 'L', 'L'));
    $pdf->SetWidths(array(8, 8, 8, 8, 8, 55, 50, 50));
}

//    Clasificador Presupuestario...
function clapre($pdf)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    $pdf->Ln(8);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(196, 10, 'Clasificador Presupuestario', 0, 1, 'C');
    //Colores de los bordes, fondo y texto
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetFillColor(250, 250, 250);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->Cell(9, 5, 'PAR', 1, 0, 'C', 1);
    $pdf->Cell(9, 5, 'GEN', 1, 0, 'C', 1);
    $pdf->Cell(9, 5, 'ESP', 1, 0, 'C', 1);
    $pdf->Cell(9, 5, 'SESP', 1, 0, 'C', 1);
    $pdf->Cell(160, 5, 'DENOMINACION', 1, 0, 'C', 1);
    $pdf->Ln(6);
    $pdf->SetAligns(array('C', 'C', 'C', 'C', 'L'));
    $pdf->SetWidths(array(9, 9, 9, 9, 160));
}

//    Ordinales...
function ordinal($pdf)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    $pdf->Ln(8);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(190, 10, 'Ordinales', 0, 1, 'C');
    //Colores de los bordes, fondo y texto
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetFillColor(250, 250, 250);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(30, 5, 'CODIGO', 1, 0, 'C', 1);
    $pdf->Cell(166, 5, 'DENOMINACION', 1, 0, 'C', 1);
    $pdf->Ln();
    $pdf->SetAligns(array('C', 'L', 'L'));
    $pdf->SetWidths(array(30, 166));
}

//    Tipos de Presupuesto...
function tipopre($pdf)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    $pdf->Ln(8);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(190, 10, 'Tipos de Presupuesto', 0, 1, 'C');
    //Colores de los bordes, fondo y texto
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetFillColor(250, 250, 250);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(196, 5, 'DENOMINACION', 1, 0, 'C', 1);
    $pdf->Ln();
    $pdf->SetAligns(array('L'));
    $pdf->SetWidths(array(196));
}

//    Fuentes de Financiamiento...
function fuentes($pdf)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    $pdf->Ln(8);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(190, 10, 'Fuentes de Financiamento', 0, 1, 'C');
    //Colores de los bordes, fondo y texto
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetFillColor(250, 250, 250);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(196, 5, 'DENOMINACION', 1, 0, 'C', 1);
    $pdf->Ln();
    $pdf->SetAligns(array('L'));
    $pdf->SetWidths(array(196));
}

//    Presupuesto Original...
function preori($pdf, $anio_fiscal, $fuente_financiamiento, $tipo_presupuesto)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    $pdf->Ln(8);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(205, 10, 'Presupuesto Original', 0, 1, 'C');
    /////////////////////////////
    if ($fuente_financiamiento != "") {
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(35, 4, 'Fuente de Financiamiento: ', 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(50, 4, $fuente_financiamiento, 0, 1, 'L');
    }
    if ($tipo_presupuesto != "") {
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(35, 4, 'Tipo de Presupuesto: ', 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(50, 4, $tipo_presupuesto, 0, 1, 'L');
    }
    if ($anio_fiscal != "") {
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(35, 4, utf8_decode('Año Fiscal: '), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(50, 4, $anio_fiscal, 0, 1, 'L');
    }
    //Colores de los bordes, fondo y texto
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetFillColor(250, 250, 250);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(20, 5, 'PARTIDA', 1, 0, 'C', 1);
    $pdf->Cell(150, 5, 'DESCRIPCION', 1, 0, 'C', 1);
    $pdf->Cell(35, 5, 'MONTO ORIGINAL', 1, 0, 'C', 1);
    $pdf->Ln(6);
    $pdf->SetAligns(array('C', 'L', 'R'));
    $pdf->SetWidths(array(20, 150, 35));
}

//    Resumen por Categorias...
function preres($pdf, $anio_fiscal, $fuente_financiamiento, $tipo_presupuesto, $campos)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    $pdf->Ln(8);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(345, 10, utf8_decode('Resumen por Categoría Programática'), 0, 1, 'C');
    //    --------------------
    if ($fuente_financiamiento != "") {
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(35, 4, 'Fuente de Financiamiento: ', 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(50, 4, $fuente_financiamiento, 0, 1, 'L');
    }
    if ($tipo_presupuesto != "") {
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(35, 4, 'Tipo de Presupuesto: ', 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(50, 4, $tipo_presupuesto, 0, 1, 'L');
    }
    if ($anio_fiscal != "") {
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(35, 4, utf8_decode('Año Fiscal: '), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(50, 4, $anio_fiscal, 0, 1, 'L');
    }
    //    --------------------
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(100, 100, 100);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('Arial', 'B', 5);
    $pdf->Cell(20, 4, 'Partida', 0, 0, 'C', 1);
    $pdf->Cell(27, 4, 'Descripcion', 0, 0, 'L', 1);
    if ($campos[0]) {
        $pdf->Cell(23, 4, 'Asig. Inicial', 0, 0, 'R', 1);
    }

    if ($campos[1]) {
        $pdf->Cell(23, 4, 'Aumento', 0, 0, 'R', 1);
    }

    if ($campos[2]) {
        $pdf->Cell(23, 4, 'Disminucion', 0, 0, 'R', 1);
    }

    if ($campos[3]) {
        $pdf->Cell(23, 4, 'Modificado', 0, 0, 'R', 1);
    }

    if ($campos[4]) {
        $pdf->Cell(23, 4, 'Asig. Ajustada', 0, 0, 'R', 1);
    }

    if ($campos[10]) {
        $pdf->Cell(23, 4, 'Reservado Dis.', 0, 0, 'R', 1);
    }

    if ($campos[5]) {
        $pdf->Cell(23, 4, 'Pre-Compromiso', 0, 0, 'R', 1);
    }

    if ($campos[6]) {
        $pdf->Cell(23, 4, 'Compromiso', 0, 0, 'R', 1);
        $pdf->Cell(11, 4, '% Comp.', 0, 0, 'R', 1);}
    if ($campos[7]) {
        $pdf->Cell(23, 4, 'Causado', 0, 0, 'R', 1);
        $pdf->Cell(11, 4, '% Cau.', 0, 0, 'R', 1);}
    if ($campos[8]) {
        $pdf->Cell(23, 4, 'Pagado', 0, 0, 'R', 1);
        $pdf->Cell(11, 4, '% Pag.', 0, 0, 'R', 1);}
    if ($campos[9]) {
        $pdf->Cell(23, 4, 'Disponible', 0, 0, 'R', 1);
        $pdf->Cell(11, 4, '% Disp.', 0, 0, 'R', 1);}
    $pdf->Ln(4);
}

//    Resumen Consolidado...
function consolidado($pdf, $anio_fiscal, $fuente_financiamiento, $tipo_presupuesto, $campos, $fdesde, $fhasta)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    $pdf->Ln(8);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(345, 6, utf8_decode('Resumen Estadístico de Partidas Consolidado'), 0, 1, 'C');
    $pdf->Cell(345, 6, 'Desde: ' . formatoFecha($fdesde) . ' Hasta: ' . formatoFecha($fhasta), 0, 1, 'C');
    //    --------------------
    if ($fuente_financiamiento != "") {
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(35, 4, 'Fuente de Financiamiento: ', 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(50, 4, $fuente_financiamiento, 0, 1, 'L');
    }
    if ($tipo_presupuesto != "") {
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(35, 4, 'Tipo de Presupuesto: ', 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(50, 4, $tipo_presupuesto, 0, 1, 'L');
    }
    if ($anio_fiscal != "") {
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(35, 4, utf8_decode('Año Fiscal: '), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(50, 4, $anio_fiscal, 0, 1, 'L');
    }
    //    --------------------
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(100, 100, 100);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('Arial', 'B', 5);
    $pdf->Cell(20, 4, 'Partida', 0, 0, 'C', 1);
    $pdf->Cell(50, 4, 'Descripcion', 0, 0, 'L', 1);
    if ($campos[0]) {
        $pdf->Cell(23, 4, 'Asig. Inicial', 0, 0, 'R', 1);
    }

    if ($campos[1]) {
        $pdf->Cell(23, 4, 'Aumento', 0, 0, 'R', 1);
    }

    if ($campos[2]) {
        $pdf->Cell(23, 4, 'Disminucion', 0, 0, 'R', 1);
    }

    if ($campos[3]) {
        $pdf->Cell(23, 4, 'Modificado', 0, 0, 'R', 1);
    }

    if ($campos[4]) {
        $pdf->Cell(23, 4, 'Asig. Ajustada', 0, 0, 'R', 1);
    }

    if ($campos[10]) {
        $pdf->Cell(23, 4, 'Reservado Disminuir', 0, 0, 'R', 1);
    }

    if ($campos[6]) {
        $pdf->Cell(23, 4, 'Compromiso', 0, 0, 'R', 1);
        $pdf->Cell(11, 4, '% Comp.', 0, 0, 'R', 1);}
    if ($campos[7]) {
        $pdf->Cell(23, 4, 'Causado', 0, 0, 'R', 1);
        $pdf->Cell(11, 4, '% Cau.', 0, 0, 'R', 1);}
    if ($campos[8]) {
        $pdf->Cell(23, 4, 'Pagado', 0, 0, 'R', 1);
        $pdf->Cell(11, 4, '% Pag.', 0, 0, 'R', 1);}
    if ($campos[9]) {
        $pdf->Cell(23, 4, 'Disponible', 0, 0, 'R', 1);
        $pdf->Cell(11, 4, '% Disp.', 0, 0, 'R', 1);}
    $pdf->Ln(4);
}

//    Consolidado por Categorias...
function porsector($pdf, $anio_fiscal, $fuente_financiamiento, $tipo_presupuesto, $fdesde, $fhasta)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    $pdf->Ln(8);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(270, 6, utf8_decode('Consolidado por Categorías'), 0, 1, 'C');
    $pdf->Cell(270, 6, 'Desde: ' . formatoFecha($fdesde) . ' Hasta: ' . formatoFecha($fhasta), 0, 1, 'C');
    //    --------------------
    if ($fuente_financiamiento != "") {
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(35, 4, 'Fuente de Financiamiento: ', 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(50, 4, $fuente_financiamiento, 0, 1, 'L');
    }
    if ($tipo_presupuesto != "") {
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(35, 4, 'Tipo de Presupuesto: ', 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(50, 4, $tipo_presupuesto, 0, 1, 'L');
    }
    if ($anio_fiscal != "") {
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(35, 4, utf8_decode('Año Fiscal: '), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(50, 4, $anio_fiscal, 0, 1, 'L');
    }
}

//    Consolidado por Sector...
function consolidado_sector($pdf, $anio_fiscal, $fuente_financiamiento, $tipo_presupuesto, $fdesde, $fhasta)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    $pdf->Ln(8);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(270, 6, 'Consolidado por Sector', 0, 1, 'C');
    $pdf->Cell(270, 6, 'Desde: ' . formatoFecha($fdesde) . ' Hasta: ' . formatoFecha($fhasta), 0, 1, 'C');
    //    --------------------
    if ($fuente_financiamiento != "") {
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(35, 4, 'Fuente de Financiamiento: ', 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(50, 4, $fuente_financiamiento, 0, 1, 'L');
    }
    if ($tipo_presupuesto != "") {
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(35, 4, 'Tipo de Presupuesto: ', 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(50, 4, $tipo_presupuesto, 0, 1, 'L');
    }
    if ($anio_fiscal != "") {
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(35, 4, utf8_decode('Año Fiscal: '), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(50, 4, $anio_fiscal, 0, 1, 'L');
    }
}

//    Detalle por Partida...
function detalle_por_partida($pdf, $idcategoria, $idpartida, $anio_fiscal, $financiamiento, $tipo_presupuesto, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $idordinal, $campos)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    $pdf->Ln(8);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(270, 20, 'Detalle Por Partida', 0, 1, 'C');
    //----------------------------------------------------
    if ($idordinal != "") {
        $where_ordinal = " AND maestro_presupuesto.idordinal = '" . $idordinal . "'";
    }
    //----------------------------------------------------
    //    CONSULTO
    $sql                       = "SELECT * FROM rendicion_cuentas WHERE idcategoria_programatica = '" . $idcategoria . "' AND idtipo_presupuesto = '" . $tipo_presupuesto . "' AND idfuente_financiamiento = '" . $financiamiento . "' AND anio = '" . $anio_fiscal . "'";
    $query_rendicion_comprobar = mysql_query($sql) or die($sql . mysql_error());
    if (mysql_num_rows($query_rendicion_comprobar) != 0) {
        $sql = "SELECT
                 categoria_programatica.codigo,
                 unidad_ejecutora.denominacion as denounidad,
                 clasificador_presupuestario.denominacion,
                 clasificador_presupuestario.partida,
                 clasificador_presupuestario.generica,
                 clasificador_presupuestario.especifica,
                 clasificador_presupuestario.sub_especifica,
                 ordinal.codigo as codordinal,
                 ordinal.denominacion AS nomordinal,
                 maestro_presupuesto.monto_original,
                 SUM(rendicion_cuentas_partidas.disminucion_periodo) As total_disminucion,
                 '0' AS reservado_disminuir,
                 '0' AS pre_compromiso,
                 '0' AS solicitud_aumento,
                 SUM(rendicion_cuentas_partidas.aumento_periodo) as total_aumento,
                 SUM(rendicion_cuentas_partidas.total_compromisos_periodo) as total_compromisos,
                 SUM(rendicion_cuentas_partidas.total_causados_periodo) as total_causados,
                 SUM(rendicion_cuentas_partidas.total_pagados_periodo) as total_pagados,
                 rendicion_cuentas_partidas.idmaestro_presupuesto AS idRegistro
            FROM
                 rendicion_cuentas_partidas,
                 categoria_programatica,
                 unidad_ejecutora,
                 clasificador_presupuestario,
                 ordinal,
                 maestro_presupuesto
            WHERE
                (rendicion_cuentas_partidas.idmaestro_presupuesto = maestro_presupuesto.idRegistro) AND
                 (categoria_programatica.idcategoria_programatica='" . $idcategoria . "' AND
                 categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND
                 (clasificador_presupuestario.idclasificador_presupuestario='" . $idpartida . "') AND
                 (maestro_presupuesto.idcategoria_programatica='" . $idcategoria . "' AND
                 maestro_presupuesto.idclasificador_presupuestario='" . $idpartida . "' AND
                    (clasificador_presupuestario.sub_especifica='00') AND
                 maestro_presupuesto.idordinal=ordinal.idordinal)
                 $where_ordinal AND
                 (maestro_presupuesto.anio='" . $anio_fiscal . "' AND
                 maestro_presupuesto.idfuente_financiamiento='" . $financiamiento . "' AND
                 maestro_presupuesto.idtipo_presupuesto='" . $tipo_presupuesto . "')
            GROUP BY (categoria_programatica.codigo), (clasificador_presupuestario.partida), (clasificador_presupuestario.generica), (clasificador_presupuestario.especifica), (clasificador_presupuestario.sub_especifica)
            ";
        $query        = mysql_query($sql) or die($sql . mysql_error());
        $field        = mysql_fetch_array($query);
        $total_actual = $field['monto_original'] + $field['total_aumento'] - $field['total_disminucion'];
        $total_actual = number_format($total_actual, 2, ',', '.');
    } else {
        $sql = "SELECT categoria_programatica.codigo,
                 unidad_ejecutora.denominacion denounidad,
                 clasificador_presupuestario.denominacion,
                 clasificador_presupuestario.partida,
                 clasificador_presupuestario.generica,
                 clasificador_presupuestario.especifica,
                 clasificador_presupuestario.sub_especifica,
                 ordinal.codigo as codordinal,
                 ordinal.denominacion AS nomordinal,
                 maestro_presupuesto.monto_original,
                 maestro_presupuesto.total_disminucion,
                 maestro_presupuesto.reservado_disminuir,
                 maestro_presupuesto.pre_compromiso,
                 maestro_presupuesto.solicitud_aumento,
                 maestro_presupuesto.total_aumento,
                 maestro_presupuesto.monto_actual,
                 maestro_presupuesto.total_compromisos,
                 maestro_presupuesto.total_causados,
                 maestro_presupuesto.total_pagados,
                 maestro_presupuesto.idRegistro
            FROM
                 categoria_programatica,
                 unidad_ejecutora,
                 clasificador_presupuestario,
                 ordinal,
                 maestro_presupuesto
            WHERE
                 (categoria_programatica.idcategoria_programatica='" . $idcategoria . "' AND
                 categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND
                 (clasificador_presupuestario.idclasificador_presupuestario='" . $idpartida . "') AND
                 (maestro_presupuesto.idcategoria_programatica='" . $idcategoria . "' AND
                 maestro_presupuesto.idclasificador_presupuestario='" . $idpartida . "' AND
                 maestro_presupuesto.idordinal=ordinal.idordinal)
                 $where_ordinal AND
                 (maestro_presupuesto.anio='" . $anio_fiscal . "' AND
                 maestro_presupuesto.idfuente_financiamiento='" . $financiamiento . "' AND
                 maestro_presupuesto.idtipo_presupuesto='" . $tipo_presupuesto . "')";
        $query = mysql_query($sql) or die($sql . mysql_error());
        $field = mysql_fetch_array($query);

        $total_actual = number_format($field[15], 2, ',', '.');
    }

    $partida = $field['partida'] . "." . $field['generica'] . "." . $field['especifica'] . "." . $field['sub_especifica'] . " " . $field['codordinal'] . " - ";
    if ($idordinal != "") {
        $partida .= $field['nomordinal'];
    } else {
        $partida .= $field['denominacion'];
    }

    $monto_original              = number_format($field[9], 2, ',', '.');
    $total_disminucion           = number_format($field[10], 2, ',', '.');
    $total_reservado_disminucion = number_format($field[11], 2, ',', '.');

    // PRE COMPROMISO

    $total_pre_compromiso = number_format($field[12], 2, ',', '.');
    if ($field[12] == 0) {
        $ptotal_precompromiso = 0;
    } else {
        $ptotal_precompromiso = (float) ($field[12] * 100 / $field[15]);
    }

    $ptotal_precompromiso = number_format($ptotal_precompromiso, 2, ',', '.');
    $dtotal_precompromiso = (float) ($field[15] - $field[12] - $field[11] - $field[16]);
    $dtotal_precompromiso = number_format($dtotal_precompromiso, 2, ',', '.');
    if ($field[12] == 0) {
        $pdtotal_precompromiso = 0;
    } else {
        $pdtotal_precompromiso = (float) (($field[15] - $field[12] - $field[11]) * 100 / $field[15]);
    }

    $pdtotal_precompromiso = number_format($pdtotal_precompromiso, 2, ',', '.');

    $total_solicitud_aumento = number_format($field[13], 2, ',', '.');
    $total_aumento           = number_format($field[14], 2, ',', '.');

    // COMPROMISO

    $total_compromiso = number_format($field[16], 2, ',', '.');
    if ($field[16] == 0) {
        $ptotal_compromiso = 0;
    } else {
        $ptotal_compromiso = (float) ($field[16] * 100 / $field[15]);
    }

    $ptotal_compromiso = number_format($ptotal_compromiso, 2, ',', '.');
    $dtotal_compromiso = (float) ($field[15] - $field[16] - $field[12] - $field[11]);
    $dtotal_compromiso = number_format($dtotal_compromiso, 2, ',', '.');
    if ($field[16] == 0) {
        $ptotal_compromiso = 0;
    } else {
        $pdtotal_compromiso = (float) (($field[15] - $field[16]) * 100 / $field[15]);
    }

    $pdtotal_compromiso = number_format($pdtotal_compromiso, 2, ',', '.');

    // CAUSADO

    $total_causado = number_format($field[17], 2, ',', '.');
    if ($field[17] == 0) {
        $ptotal_causado = 0;
    } else {
        $ptotal_causado = (float) ($field[17] * 100 / $field[15]);
    }

    $ptotal_causado = number_format($ptotal_causado, 2, ',', '.');
    $dtotal_causado = (float) ($field[15] - $field[17]);
    $dtotal_causado = number_format($dtotal_causado, 2, ',', '.');
    if ($field[17] == 0) {
        $pdtotal_causado = 0;
    } else {
        $pdtotal_causado = (float) (($field[15] - $field[17]) * 100 / $field[15]);
    }

    $pdtotal_causado = number_format($pdtotal_causado, 2, ',', '.');

    // PAGADO

    $total_pagado = number_format($field[18], 2, ',', '.');
    if ($field[18] == 0) {
        $ptotal_pagado = 0;
    } else {
        $ptotal_pagado = (float) ($field[18] * 100 / $field[15]);
    }

    $ptotal_pagado = number_format($ptotal_pagado, 2, ',', '.');
    $dtotal_pagado = (float) ($field[15] - $field[18]);
    $dtotal_pagado = number_format($dtotal_pagado, 2, ',', '.');
    if ($field[18] == 0 || $field[15] == 0) {
        $pdtotal_pagado = 0;
    } else {
        $pdtotal_pagado = (float) (($field[15] - $field[18]) * 100 / $field[15]);
    }

    $pdtotal_pagado = number_format($pdtotal_pagado, 2, ',', '.');

    /////////////////////////////
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 5, 'Fuente de Financiamiento: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(50, 4, $nom_fuente_financiamiento, 0, 1, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 5, 'Tipo de Presupuesto: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(50, 4, $nom_tipo_presupuesto, 0, 1, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 5, utf8_decode('Año Fiscal: '), 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(50, 4, $anio_fiscal, 0, 1, 'L');
    //    IMPRIMO EL HEAD SECUNDARIO
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 5, utf8_decode('Categoría Programática: '), 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(160, 5, $field['codigo'] . ' ' . $field['denounidad'], 0, 1, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 5, 'Partida: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(160, 5, utf8_decode($partida), 0, 1, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 5, 'Monto Original: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(30, 5, $monto_original, 0, 1, 'R');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 5, 'Total Aumentos: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(30, 5, $total_aumento, 0, 1, 'R');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 5, 'Total Solicitud Aumentos: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(30, 5, '(' . $total_solicitud_aumento . ')', 0, 1, 'R');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 5, 'Total Disminuciones: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(30, 5, $total_disminucion, 0, 1, 'R');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 5, 'Total Reservado Para Disminuir: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(30, 5, '(' . $total_reservado_disminucion . ')', 0, 1, 'R');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 5, 'Monto Actual: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(30, 5, $total_actual, 0, 1, 'R');

    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 5, 'Total Pre Compromisos: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(30, 5, $total_pre_compromiso, 0, 0, 'R');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(20, 5, "(" . $ptotal_precompromiso . "%)", 0, 0, 'L');

    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(15, 5, 'Disponible: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(30, 5, $dtotal_precompromiso, 0, 0, 'R');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(20, 5, "(" . $pdtotal_precompromiso . "%)", 0, 1, 'L');

    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 5, 'Total Compromisos: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(30, 5, $total_compromiso, 0, 0, 'R');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(20, 5, "(" . $ptotal_compromiso . "%)", 0, 0, 'L');

    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(15, 5, 'Disponible: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(30, 5, $dtotal_compromiso, 0, 0, 'R');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(20, 5, "(" . $pdtotal_compromiso . "%)", 0, 1, 'L');

    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 5, 'Total Causado: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(30, 5, $total_causado, 0, 0, 'R');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(20, 5, "(" . $ptotal_causado . "%)", 0, 0, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(15, 5, 'Disponible: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(30, 5, $dtotal_causado, 0, 0, 'R');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(20, 5, "(" . $pdtotal_causado . "%)", 0, 1, 'L');

    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 5, 'Total Pagado: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(30, 5, $total_pagado, 0, 0, 'R');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(20, 5, "(" . $ptotal_pagado . "%)", 0, 0, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(15, 5, 'Disponible: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(30, 5, $dtotal_pagado, 0, 0, 'R');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(20, 5, "(" . $pdtotal_pagado . "%)", 0, 10, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 5, 'Disponible Para Compromisos = Monto Actual - Total Pre Compromisos - Total Compromisos - Reservado Para Disminuir', 0, 0, 'R');
    $pdf->Ln(4);
    //----------------------------------------------------
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(100, 100, 100);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('Arial', 'B', 5);
    $pdf->Cell(15, 4, 'Documento', 0, 0, 'C', 1);
    $pdf->Cell(15, 4, 'Fecha', 0, 0, 'L', 1);
    $pdf->Cell(40, 4, 'Descripcion', 0, 0, 'L', 1);
    if ($campos[0]) {
        $pdf->Cell(23, 4, 'Asig. Inicial', 0, 0, 'R', 1);
    }

    if ($campos[1]) {
        $pdf->Cell(23, 4, 'Aumento', 0, 0, 'R', 1);
    }

    if ($campos[2]) {
        $pdf->Cell(23, 4, 'Disminucion', 0, 0, 'R', 1);
    }

    if ($campos[3]) {
        $pdf->Cell(23, 4, 'Modificado', 0, 0, 'R', 1);
    }

    if ($campos[4]) {
        $pdf->Cell(23, 4, 'Asig. Ajustada', 0, 0, 'R', 1);
    }

    if ($campos[5]) {
        $pdf->Cell(23, 4, 'Pre-Compromiso', 0, 0, 'R', 1);
    }

    if ($campos[6]) {
        $pdf->Cell(23, 4, 'Compromiso', 0, 0, 'R', 1);
        $pdf->Cell(11, 4, '% Comp.', 0, 0, 'R', 1);}
    if ($campos[7]) {
        $pdf->Cell(23, 4, 'Causado', 0, 0, 'R', 1);
        $pdf->Cell(11, 4, '% Cau.', 0, 0, 'R', 1);}
    if ($campos[8]) {
        $pdf->Cell(23, 4, 'Pagado', 0, 0, 'R', 1);
        $pdf->Cell(11, 4, '% Pag.', 0, 0, 'R', 1);}
    if ($campos[9]) {
        $pdf->Cell(23, 4, 'Disponible', 0, 0, 'R', 1);
        $pdf->Cell(11, 4, '% Disp.', 0, 0, 'R', 1);}
    $pdf->Ln(4);
}

//    Detalle por Partida...
function detalle_por_partida_tablas($pdf, $campos)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    $pdf->Ln(8);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(270, 20, 'Detalle Por Partida', 0, 1, 'C');
    //----------------------------------------------------
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(100, 100, 100);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('Arial', 'B', 5);
    $pdf->Cell(15, 4, 'Documento', 0, 0, 'C', 1);
    $pdf->Cell(15, 4, 'Fecha', 0, 0, 'L', 1);
    $pdf->Cell(40, 4, 'Descripcion', 0, 0, 'L', 1);
    if ($campos[0]) {
        $pdf->Cell(23, 4, 'Asig. Inicial', 0, 0, 'R', 1);
    }

    if ($campos[1]) {
        $pdf->Cell(23, 4, 'Aumento', 0, 0, 'R', 1);
    }

    if ($campos[2]) {
        $pdf->Cell(23, 4, 'Disminucion', 0, 0, 'R', 1);
    }

    if ($campos[3]) {
        $pdf->Cell(23, 4, 'Modificado', 0, 0, 'R', 1);
    }

    if ($campos[4]) {
        $pdf->Cell(23, 4, 'Asig. Ajustada', 0, 0, 'R', 1);
    }

    if ($campos[5]) {
        $pdf->Cell(23, 4, 'Pre-Compromiso', 0, 0, 'R', 1);
    }

    if ($campos[6]) {
        $pdf->Cell(23, 4, 'Compromiso', 0, 0, 'R', 1);
        $pdf->Cell(11, 4, '% Comp.', 0, 0, 'R', 1);}
    if ($campos[7]) {
        $pdf->Cell(23, 4, 'Causado', 0, 0, 'R', 1);
        $pdf->Cell(11, 4, '% Cau.', 0, 0, 'R', 1);}
    if ($campos[8]) {
        $pdf->Cell(23, 4, 'Pagado', 0, 0, 'R', 1);
        $pdf->Cell(11, 4, '% Pag.', 0, 0, 'R', 1);}
    if ($campos[9]) {
        $pdf->Cell(23, 4, 'Disponible', 0, 0, 'R', 1);
        $pdf->Cell(11, 4, '% Disp.', 0, 0, 'R', 1);}
    $pdf->Ln(4);
}

//    Ejecucion Detallada...
function ejecucion_detallada($pdf, $anio_fiscal, $fuente_financiamiento, $tipo_presupuesto, $campos)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    $pdf->Ln(8);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(270, 10, utf8_decode('Ejecución Detallada'), 0, 1, 'C');
    /////////////////////////////
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 4, 'Fuente de Financiamiento: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(50, 4, $fuente_financiamiento, 0, 1, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 4, 'Tipo de Presupuesto: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(50, 4, $tipo_presupuesto, 0, 1, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 4, utf8_decode('Año Fiscal: '), 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(50, 4, $anio_fiscal, 0, 1, 'L');
    //----------------------------------------------------
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(100, 100, 100);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('Arial', 'B', 5);
    $pdf->Cell(15, 4, 'Documento', 0, 0, 'C', 1);
    $pdf->Cell(15, 4, 'Fecha', 0, 0, 'L', 1);
    $pdf->Cell(63, 4, 'Descripcion', 0, 0, 'L', 1);
    if ($campos[0]) {
        $pdf->Cell(23, 4, 'Asig. Inicial', 0, 0, 'R', 1);
    }

    if ($campos[1]) {
        $pdf->Cell(23, 4, 'Aumento', 0, 0, 'R', 1);
    }

    if ($campos[2]) {
        $pdf->Cell(23, 4, 'Disminucion', 0, 0, 'R', 1);
    }

    if ($campos[3]) {
        $pdf->Cell(23, 4, 'Modificado', 0, 0, 'R', 1);
    }

    if ($campos[4]) {
        $pdf->Cell(23, 4, 'Asig. Ajustada', 0, 0, 'R', 1);
    }

    //if ($campos[5]) $pdf->Cell(23, 4, 'Pre-Compromiso', 0, 0, 'R', 1);
    if ($campos[6]) {
        $pdf->Cell(23, 4, 'Compromiso', 0, 0, 'R', 1);
        $pdf->Cell(11, 4, '% Comp.', 0, 0, 'R', 1);}
    if ($campos[7]) {
        $pdf->Cell(23, 4, 'Causado', 0, 0, 'R', 1);
        $pdf->Cell(11, 4, '% Cau.', 0, 0, 'R', 1);}
    if ($campos[8]) {
        $pdf->Cell(23, 4, 'Pagado', 0, 0, 'R', 1);
        $pdf->Cell(11, 4, '% Pag.', 0, 0, 'R', 1);}
    if ($campos[9]) {
        $pdf->Cell(23, 4, 'Disponible', 0, 0, 'R', 1);
        $pdf->Cell(11, 4, '% Disp.', 0, 0, 'R', 1);}
    $pdf->Ln(4);
}

//    Disponibilidad Presupuestaria...
function disponibilidad_presupuestaria($pdf, $anio_fiscal, $fuente_financiamiento, $tipo_presupuesto)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    $pdf->Ln(8);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Ln(10);
    $pdf->Cell(195, 10, 'Disponibilidad Presupuestaria', 0, 1, 'C');
    //    --------------------
    if ($fuente_financiamiento != "") {
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(35, 4, 'Fuente de Financiamiento: ', 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(50, 4, $fuente_financiamiento, 0, 1, 'L');
    }
    if ($tipo_presupuesto != "") {
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(35, 4, 'Tipo de Presupuesto: ', 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(50, 4, $tipo_presupuesto, 0, 1, 'L');
    }
    if ($anio_fiscal != "") {
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(35, 4, utf8_decode('Año Fiscal: '), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(50, 4, $anio_fiscal, 0, 1, 'L');
    }
    //    --------------------
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetFillColor(250, 250, 250);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(30, 4, 'Partida', 1, 0, 'C', 1);
    $pdf->Cell(85, 4, utf8_decode('Denominación'), 1, 0, 'C', 1);
    $pdf->Cell(35, 4, 'Monto Actual', 1, 0, 'C', 1);
    $pdf->Cell(35, 4, 'Monto Disponible', 1, 0, 'C', 1);
    $pdf->Cell(20, 4, '%', 1, 0, 'C', 1);
    $pdf->Ln(5);
}

//    Disponibilidad Presupuestaria por Periodo...
function disponibilidad_presupuestaria_periodo($pdf, $anio_fiscal, $fuente_financiamiento, $tipo_presupuesto, $campos, $fdesde, $fhasta)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    $pdf->Ln(8);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(345, 6, utf8_decode('Disponibilidad Presupuestaria por Período'), 0, 1, 'C');
    $pdf->Cell(345, 6, 'Desde: ' . formatoFecha($fdesde) . ' Hasta: ' . formatoFecha($fhasta), 0, 1, 'C');
    //    --------------------
    if ($fuente_financiamiento != "") {
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(35, 4, 'Fuente de Financiamiento: ', 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(50, 4, $fuente_financiamiento, 0, 1, 'L');
    }
    if ($tipo_presupuesto != "") {
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(35, 4, 'Tipo de Presupuesto: ', 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(50, 4, $tipo_presupuesto, 0, 1, 'L');
    }
    if ($anio_fiscal != "") {
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(35, 4, utf8_decode('Año Fiscal: '), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(50, 4, $anio_fiscal, 0, 1, 'L');
    }
    //    --------------------
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(100, 100, 100);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('Arial', 'B', 5);
    $pdf->Cell(15, 4, 'Partida', 0, 0, 'C', 1);
    $pdf->Cell(55, 4, 'Descripcion', 0, 0, 'L', 1);
    if ($campos[0]) {
        $pdf->Cell(23, 4, 'Asig. Inicial', 0, 0, 'R', 1);
    }

    if ($campos[1]) {
        $pdf->Cell(23, 4, 'Aumento', 0, 0, 'R', 1);
    }

    if ($campos[2]) {
        $pdf->Cell(23, 4, 'Disminucion', 0, 0, 'R', 1);
    }

    if ($campos[3]) {
        $pdf->Cell(23, 4, 'Modificado', 0, 0, 'R', 1);
    }

    if ($campos[4]) {
        $pdf->Cell(23, 4, 'Asig. Ajustada', 0, 0, 'R', 1);
    }

    if ($campos[5]) {
        $pdf->Cell(23, 4, 'Pre-Compromiso', 0, 0, 'R', 1);
    }

    if ($campos[6]) {
        $pdf->Cell(23, 4, 'Compromiso', 0, 0, 'R', 1);
        $pdf->Cell(11, 4, '% Comp.', 0, 0, 'R', 1);}
    if ($campos[7]) {
        $pdf->Cell(23, 4, 'Causado', 0, 0, 'R', 1);
        $pdf->Cell(11, 4, '% Cau.', 0, 0, 'R', 1);}
    if ($campos[8]) {
        $pdf->Cell(23, 4, 'Pagado', 0, 0, 'R', 1);
        $pdf->Cell(11, 4, '% Pag.', 0, 0, 'R', 1);}
    if ($campos[9]) {
        $pdf->Cell(23, 4, 'Disponible', 0, 0, 'R', 1);
        $pdf->Cell(11, 4, '% Disp.', 0, 0, 'R', 1);}
    $pdf->Ln(5);
}

//    Resumen por Actividades...
function resumen_actividades($pdf, $anio_fiscal, $fuente_financiamiento, $tipo_presupuesto, $campos)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    $pdf->Ln(8);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(270, 10, utf8_decode('Resumen Estadístico por Actividades'), 0, 1, 'C');
    //    --------------------
    if ($fuente_financiamiento != "") {
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(35, 4, 'Fuente de Financiamiento: ', 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(50, 4, $fuente_financiamiento, 0, 1, 'L');
    }
    if ($tipo_presupuesto != "") {
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(35, 4, 'Tipo de Presupuesto: ', 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(50, 4, $tipo_presupuesto, 0, 1, 'L');
    }
    if ($anio_fiscal != "") {
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(35, 4, utf8_decode('Año Fiscal: '), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(50, 4, $anio_fiscal, 0, 1, 'L');
    }
    //    --------------------
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(100, 100, 100);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('Arial', 'B', 5);
    $pdf->Cell(15, 4, 'Partida', 0, 0, 'C', 1);
    $pdf->Cell(55, 4, 'Descripcion', 0, 0, 'L', 1);
    if ($campos[0]) {
        $pdf->Cell(23, 4, 'Asig. Inicial', 0, 0, 'R', 1);
    }

    if ($campos[1]) {
        $pdf->Cell(23, 4, 'Aumento', 0, 0, 'R', 1);
    }

    if ($campos[2]) {
        $pdf->Cell(23, 4, 'Disminucion', 0, 0, 'R', 1);
    }

    if ($campos[3]) {
        $pdf->Cell(23, 4, 'Modificado', 0, 0, 'R', 1);
    }

    if ($campos[4]) {
        $pdf->Cell(23, 4, 'Asig. Ajustada', 0, 0, 'R', 1);
    }

    if ($campos[5]) {
        $pdf->Cell(23, 4, 'Pre-Compromiso', 0, 0, 'R', 1);
    }

    if ($campos[6]) {
        $pdf->Cell(23, 4, 'Compromiso', 0, 0, 'R', 1);
        $pdf->Cell(11, 4, '% Comp.', 0, 0, 'R', 1);}
    if ($campos[7]) {
        $pdf->Cell(23, 4, 'Causado', 0, 0, 'R', 1);
        $pdf->Cell(11, 4, '% Cau.', 0, 0, 'R', 1);}
    if ($campos[8]) {
        $pdf->Cell(23, 4, 'Pagado', 0, 0, 'R', 1);
        $pdf->Cell(11, 4, '% Pag.', 0, 0, 'R', 1);}
    if ($campos[9]) {
        $pdf->Cell(23, 4, 'Disponible', 0, 0, 'R', 1);
        $pdf->Cell(11, 4, '% Disp.', 0, 0, 'R', 1);}
    $pdf->Ln(5);
}

//    Movimientos por Partidas...
function movimientos_por_partida($pdf, $idcategoria, $idpartida, $anio_fiscal, $financiamiento, $tipo_presupuesto, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $idordinal)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    $pdf->Ln(8);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(270, 20, 'Movimientos por Partida', 0, 1, 'C');
    //----------------------------------------------------
    if ($idordinal != "") {
        $where_ordinal = " AND maestro_presupuesto.idordinal = '" . $idordinal . "'";
    }
    //    CONSULTO
    $sql = "SELECT categoria_programatica.codigo,
                 unidad_ejecutora.denominacion denounidad,
                 clasificador_presupuestario.denominacion,
                 clasificador_presupuestario.partida,
                 clasificador_presupuestario.generica,
                 clasificador_presupuestario.especifica,
                 clasificador_presupuestario.sub_especifica,
                 ordinal.codigo as codordinal,
                 ordinal.denominacion as nomordinal,
                 maestro_presupuesto.monto_original,
                 maestro_presupuesto.total_disminucion,
                 maestro_presupuesto.reservado_disminuir,
                 maestro_presupuesto.pre_compromiso,
                 maestro_presupuesto.solicitud_aumento,
                 maestro_presupuesto.total_aumento,
                 maestro_presupuesto.monto_actual,
                 maestro_presupuesto.total_compromisos,
                 maestro_presupuesto.total_causados,
                 maestro_presupuesto.total_pagados,
                 maestro_presupuesto.idRegistro
            FROM
                 categoria_programatica,
                 unidad_ejecutora,
                 clasificador_presupuestario,
                 ordinal,
                 maestro_presupuesto
            WHERE
                 (categoria_programatica.idcategoria_programatica='" . $idcategoria . "' AND
                 categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) AND
                 (clasificador_presupuestario.idclasificador_presupuestario='" . $idpartida . "') AND
                 (maestro_presupuesto.idcategoria_programatica='" . $idcategoria . "' AND
                 maestro_presupuesto.idclasificador_presupuestario='" . $idpartida . "' AND
                 maestro_presupuesto.idordinal=ordinal.idordinal)
                 $where_ordinal AND
                 (maestro_presupuesto.anio='" . $anio_fiscal . "' AND
                 maestro_presupuesto.idfuente_financiamiento='" . $financiamiento . "' AND
                 maestro_presupuesto.idtipo_presupuesto='" . $tipo_presupuesto . "')";
    $query = mysql_query($sql) or die($sql . mysql_error());
    $field = mysql_fetch_array($query);

    $partida = $field['partida'] . "." . $field['generica'] . "." . $field['especifica'] . "." . $field['sub_especifica'] . " " . $field['codordinal'] . " - ";
    if ($idordinal != "") {
        $partida .= $field['nomordinal'];
    } else {
        $partida .= $field['denominacion'];
    }

    $monto_original              = number_format($field[9], 2, ',', '.');
    $total_disminucion           = number_format($field[10], 2, ',', '.');
    $total_reservado_disminucion = number_format($field[11], 2, ',', '.');

    // PRE COMPROMISO

    $total_pre_compromiso = number_format($field[12], 2, ',', '.');
    if ($field[12] == 0) {
        $ptotal_precompromiso = 0;
    } else {
        $ptotal_precompromiso = (float) ($field[12] * 100 / $field[15]);
    }

    $ptotal_precompromiso = number_format($ptotal_precompromiso, 2, ',', '.');
    $dtotal_precompromiso = (float) ($field[15] - $field[12] - $field[11] - $field[16]);
    $dtotal_precompromiso = number_format($dtotal_precompromiso, 2, ',', '.');
    if ($field[12] == 0) {
        $pdtotal_precompromiso = 0;
    } else {
        $pdtotal_precompromiso = (float) (($field[15] - $field[12] - $field[11]) * 100 / $field[15]);
    }

    $pdtotal_precompromiso = number_format($pdtotal_precompromiso, 2, ',', '.');

    $total_solicitud_aumento = number_format($field[13], 2, ',', '.');
    $total_aumento           = number_format($field[14], 2, ',', '.');
    $total_actual            = number_format($field[15], 2, ',', '.');

    // COMPROMISO

    $total_compromiso = number_format($field[16], 2, ',', '.');
    if ($field[16] == 0) {
        $ptotal_compromiso = 0;
    } else {
        $ptotal_compromiso = (float) ($field[16] * 100 / $field[15]);
    }

    $ptotal_compromiso = number_format($ptotal_compromiso, 2, ',', '.');
    $dtotal_compromiso = (float) ($field[15] - $field[16] - $field[12] - $field[11]);
    $dtotal_compromiso = number_format($dtotal_compromiso, 2, ',', '.');
    if ($field[16] == 0) {
        $ptotal_compromiso = 0;
    } else {
        $pdtotal_compromiso = (float) (($field[15] - $field[16]) * 100 / $field[15]);
    }

    $pdtotal_compromiso = number_format($pdtotal_compromiso, 2, ',', '.');

    // CAUSADO

    $total_causado = number_format($field[17], 2, ',', '.');
    if ($field[17] == 0) {
        $ptotal_causado = 0;
    } else {
        $ptotal_causado = (float) ($field[17] * 100 / $field[15]);
    }

    $ptotal_causado = number_format($ptotal_causado, 2, ',', '.');
    $dtotal_causado = (float) ($field[15] - $field[17]);
    $dtotal_causado = number_format($dtotal_causado, 2, ',', '.');
    if ($field[17] == 0) {
        $pdtotal_causado = 0;
    } else {
        $pdtotal_causado = (float) (($field[15] - $field[17]) * 100 / $field[15]);
    }

    $pdtotal_causado = number_format($pdtotal_causado, 2, ',', '.');

    // PAGADO

    $total_pagado = number_format($field[18], 2, ',', '.');
    if ($field[18] == 0) {
        $ptotal_pagado = 0;
    } else {
        $ptotal_pagado = (float) ($field[18] * 100 / $field[15]);
    }

    $ptotal_pagado = number_format($ptotal_pagado, 2, ',', '.');
    $dtotal_pagado = (float) ($field[15] - $field[18]);
    $dtotal_pagado = number_format($dtotal_pagado, 2, ',', '.');
    if ($field[18] == 0) {
        $pdtotal_pagado = 0;
    } else {
        $pdtotal_pagado = (float) (($field[15] - $field[18]) * 100 / $field[15]);
    }

    $pdtotal_pagado = number_format($pdtotal_pagado, 2, ',', '.');

    /////////////////////////////
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 5, 'Fuente de Financiamiento: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(50, 4, $nom_fuente_financiamiento, 0, 1, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 5, 'Tipo de Presupuesto: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(50, 4, $nom_tipo_presupuesto, 0, 1, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 5, utf8_decode('Año Fiscal: '), 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(50, 4, $anio_fiscal, 0, 1, 'L');
    //    IMPRIMO EL HEAD SECUNDARIO
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 5, utf8_decode('Categoría Programática: '), 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(160, 5, $field['codigo'] . ' ' . $field['denounidad'], 0, 1, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 5, 'Partida: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(160, 5, utf8_decode($partida), 0, 1, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 5, 'Monto Original: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(30, 5, $monto_original, 0, 1, 'R');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 5, 'Total Aumentos: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(30, 5, $total_aumento, 0, 1, 'R');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 5, 'Total Solicitud Aumentos: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(30, 5, '(' . $total_solicitud_aumento . ')', 0, 1, 'R');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 5, 'Total Disminuciones: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(30, 5, $total_disminucion, 0, 1, 'R');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 5, 'Total Reservado Para Disminuir: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(30, 5, '(' . $total_reservado_disminucion . ')', 0, 1, 'R');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 5, 'Monto Actual: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(30, 5, $total_actual, 0, 1, 'R');
    $pdf->Ln(4);
    //----------------------------------------------------
    //Colores de los bordes, fondo y texto
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(100, 100, 100);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('Arial', 'B', 5);
    $pdf->SetAligns(array('C', 'C', 'L', 'R', 'R', 'R', 'R'));
    $pdf->SetWidths(array(30, 20, 100, 30, 30, 30, 30));
    $pdf->SetHeight(array(5));
    $pdf->Row(array('DOCUMENTO', 'FECHA', 'DESCRIPCION', 'AUMENTO', 'SOLICITUD AUMENTO', 'DISMINUCION', 'RESERVADO DISMINUCION'));
    $pdf->Ln(1);
    $pdf->SetHeight(array(3));
}

//    Movimientos por Partidas...
function movimientos_por_partida_tablas($pdf)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    $pdf->Ln(8);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(270, 20, 'Movimientos por Partida', 0, 1, 'C');
    //Colores de los bordes, fondo y texto
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(100, 100, 100);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('Arial', 'B', 5);
    $pdf->SetAligns(array('C', 'C', 'L', 'R', 'R', 'R', 'R'));
    $pdf->SetWidths(array(30, 20, 100, 30, 30, 30, 30));
    $pdf->SetHeight(array(5));
    $pdf->Row(array('DOCUMENTO', 'FECHA', 'DESCRIPCION', 'AUMENTO', 'SOLICITUD AUMENTO', 'DISMINUCION', 'RESERVADO DISMINUCION'));
    $pdf->Ln(1);
    $pdf->SetHeight(array(3));
}

//    Movimientos por Categoria...
function movimientos_por_categoria($pdf, $anio_fiscal, $fuente_financiamiento, $tipo_presupuesto)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    $pdf->Ln(8);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(270, 10, utf8_decode('Movimientos por Categoría'), 0, 1, 'C');
    //    --------------------
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 4, 'Fuente de Financiamiento: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(50, 4, $fuente_financiamiento, 0, 1, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 4, 'Tipo de Presupuesto: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(50, 4, $tipo_presupuesto, 0, 1, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 4, utf8_decode('Año Fiscal: '), 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(50, 4, $anio_fiscal, 0, 1, 'L');
    //Colores de los bordes, fondo y texto
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(100, 100, 100);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('Arial', 'B', 5);
    $pdf->SetAligns(array('C', 'L', 'R', 'R', 'R', 'R', 'R', 'R'));
    $pdf->SetWidths(array(30, 60, 30, 30, 30, 30, 30, 30));
    $pdf->SetHeight(array(5));
    $pdf->Row(array('PARTIDA', 'DESCRIPCION', 'ASIG. INICIAL', 'AUMENTO', 'SOLICITUD AUMENTO', 'DISMINUCION', 'RESERVADO DISMINUCION', 'ASIG. AJUSTADA'));
    $pdf->Ln(1);
    $pdf->SetAligns(array('C', 'L', 'R', 'R', 'R', 'R', 'R', 'R'));
    $pdf->SetWidths(array(30, 60, 30, 30, 30, 30, 30, 30));
    $pdf->SetHeight(array(3));
}

//    Proyeccion Presupuestaria...
function proyeccion_presupuestaria($pdf, $anio_fiscal, $fuente_financiamiento, $tipo_presupuesto)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    $pdf->Ln(8);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(270, 10, utf8_decode('Proyección Presupuestaria'), 0, 1, 'C');
    //    --------------------
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 4, 'Fuente de Financiamiento: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(50, 4, $fuente_financiamiento, 0, 1, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 4, 'Tipo de Presupuesto: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(50, 4, $tipo_presupuesto, 0, 1, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 4, utf8_decode('Año Fiscal: '), 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(50, 4, $anio_fiscal, 0, 1, 'L');
    //Colores de los bordes, fondo y texto
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(100, 100, 100);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('Arial', 'B', 5);
    $pdf->SetAligns(array('C', 'L', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R'));
    $pdf->SetWidths(array(20, 90, 25, 25, 12, 25, 12, 25, 12, 25, 12));
    $pdf->SetHeight(array(5));
    $pdf->Row(array('PARTIDA', 'DESCRIPCION', 'ASIG. AJUSTADA', 'COMPROMISO AL DIA', '% COMP', 'DISPONIBLE', '%DISP', 'PROYECCION', '%PROY', 'D.PROYECTADO', '%DP'));
    $pdf->Ln(1);
    $pdf->SetHeight(array(3));
}

//    Simular Solicitud Presupuesto...
function simular_solicitud_presupuesto($pdf, $anio_fiscal, $fuente_financiamiento, $tipo_presupuesto, $porcentaje)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    $pdf->Ln(8);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(190, 10, utf8_decode('Simular Solicitud de Presupuesto'), 0, 1, 'C');
    /////////////////////////////
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 4, 'Fuente de Financiamiento: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(50, 4, $fuente_financiamiento, 0, 1, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 4, 'Tipo de Presupuesto: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(50, 4, $tipo_presupuesto, 0, 1, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 4, utf8_decode('Año Fiscal: '), 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(50, 4, $anio_fiscal, 0, 1, 'L');
    //Colores de los bordes, fondo y texto
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(100, 100, 100);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('Arial', 'B', 5);
    $pdf->SetAligns(array('C', 'L', 'R', 'R'));
    $pdf->SetWidths(array(20, 90, 40, 40));
    $pdf->SetHeight(array(5));
    $pdf->Row(array('PARTIDA', 'DESCRIPCION', utf8_decode('MONTO AÑO ' . $anio_fiscal), utf8_decode('MONTO SIMULADO ' . $porcentaje . '%')));
    $pdf->Ln(1);
    $pdf->SetHeight(array(3));
}

//    Documentos por Partida...
function documentos_por_partida($pdf, $idcategoria, $idpartida, $anio_fiscal, $financiamiento, $tipo_presupuesto, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $categoria, $partida)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    $pdf->Ln(8);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(205, 20, 'Documentos Por Partida', 0, 1, 'C');
    //----------------------------------------------------
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 5, 'Fuente de Financiamiento: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(50, 4, $nom_fuente_financiamiento, 0, 1, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 5, 'Tipo de Presupuesto: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(50, 4, $nom_tipo_presupuesto, 0, 1, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 5, utf8_decode('Año Fiscal: '), 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(50, 4, $anio_fiscal, 0, 1, 'L');

    $categoria_programatica      = $categoria['codigo'] . ' ' . $categoria['denominacion'];
    $clasificador_presupuestario = $partida['codigo'] . ' ' . $partida['denominacion'];
    $ordinal                     = $partida['codigo'] . ' ' . $partida['codordinal'] . ' ' . $partida['nomordinal'];

    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 5, utf8_decode('Categoria Programatica'), 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(50, 4, utf8_decode($categoria_programatica), 0, 1, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 5, 'Partida: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(50, 4, utf8_decode($clasificador_presupuestario), 0, 1, 'L');

    if ($partida['codordinal'] != "0000") {
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(35, 5, '', 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(50, 4, utf8_decode($ordinal), 0, 1, 'L');
    }

    $pdf->Ln(4);
    //----------------------------------------------------
    //Colores de los bordes, fondo y texto
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(100, 100, 100);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->SetAligns(array('L', 'C', 'C', 'L', 'R'));
    $pdf->SetWidths(array(25, 25, 25, 105, 25));
    $pdf->SetHeight(array(5));
    $pdf->Row(array(utf8_decode('Número'), 'Fecha', 'Estado', 'Beneficiario', 'Monto'));
    $pdf->Ln(1);
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', '', 6);
    $pdf->SetAligns(array('L', 'C', 'C', 'L', 'R'));
    $pdf->SetHeight(array(5));
}

//    Resumen por Partida...
function resumen_por_partida($pdf, $anio_fiscal, $fuente_financiamiento, $tipo_presupuesto, $partida, $campos)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    $pdf->Ln(8);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(270, 10, utf8_decode('Resumen por Partidas'), 0, 1, 'C');
    /////////////////////////////
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 4, 'Fuente de Financiamiento: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(50, 4, $fuente_financiamiento, 0, 1, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 4, 'Tipo de Presupuesto: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(50, 4, $tipo_presupuesto, 0, 1, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 4, utf8_decode('Año Fiscal: '), 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(50, 4, $anio_fiscal, 0, 1, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 4, utf8_decode('Clasificador: '), 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(50, 4, utf8_decode($partida), 0, 1, 'L');
    //----------------------------------------------------
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(100, 100, 100);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('Arial', 'B', 5);
    $pdf->Cell(15, 4, 'Codigo', 0, 0, 'C', 1);
    $pdf->Cell(55, 4, 'Categoria', 0, 0, 'L', 1);
    if ($campos[0]) {
        $pdf->Cell(23, 4, 'Asig. Inicial', 0, 0, 'R', 1);
    }

    if ($campos[1]) {
        $pdf->Cell(23, 4, 'Aumento', 0, 0, 'R', 1);
    }

    if ($campos[2]) {
        $pdf->Cell(23, 4, 'Disminucion', 0, 0, 'R', 1);
    }

    if ($campos[3]) {
        $pdf->Cell(23, 4, 'Modificado', 0, 0, 'R', 1);
    }

    if ($campos[4]) {
        $pdf->Cell(23, 4, 'Asig. Ajustada', 0, 0, 'R', 1);
    }

    if ($campos[5]) {
        $pdf->Cell(23, 4, 'Pre-Compromiso', 0, 0, 'R', 1);
    }

    if ($campos[6]) {
        $pdf->Cell(23, 4, 'Compromiso', 0, 0, 'R', 1);
        $pdf->Cell(11, 4, '% Comp.', 0, 0, 'R', 1);}
    if ($campos[7]) {
        $pdf->Cell(23, 4, 'Causado', 0, 0, 'R', 1);
        $pdf->Cell(11, 4, '% Cau.', 0, 0, 'R', 1);}
    if ($campos[8]) {
        $pdf->Cell(23, 4, 'Pagado', 0, 0, 'R', 1);
        $pdf->Cell(11, 4, '% Pag.', 0, 0, 'R', 1);}
    if ($campos[9]) {
        $pdf->Cell(23, 4, 'Disponible', 0, 0, 'R', 1);
        $pdf->Cell(11, 4, '% Disp.', 0, 0, 'R', 1);}
    $pdf->Ln(5);
}

//    Ejecucion Trimestral...
function ejecucion_trimestral($pdf, $anio_fiscal, $fuente_financiamiento, $tipo_presupuesto)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    $pdf->Ln(8);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(270, 10, utf8_decode('Ejecución por Trimestre'), 0, 1, 'C');
    /////////////////////////////
    $pdf->SetFont('Arial', '', 6);
    $pdf->Cell(35, 4, 'Fuente de Financiamiento: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->Cell(50, 4, $fuente_financiamiento, 0, 1, 'L');
    $pdf->SetFont('Arial', '', 6);
    $pdf->Cell(35, 4, 'Tipo de Presupuesto: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->Cell(50, 4, $tipo_presupuesto, 0, 1, 'L');
    $pdf->SetFont('Arial', '', 6);
    $pdf->Cell(35, 4, utf8_decode('Año Fiscal: '), 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->Cell(50, 4, $anio_fiscal, 0, 1, 'L');
    $pdf->Ln(3);
    //Colores de los bordes, fondo y texto
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(240, 240, 240);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 4);
    $pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
    $pdf->SetWidths(array(12, 48, 16, 16, 16, 16, 16, 16, 16, 16, 16, 16, 16, 16, 16, 16, 16, 16, 16, 16));
    $pdf->SetHeight(array(2));
    $pdf->Row(array('PARTIDA', 'DESCRIPCION', 'PRESUPUESTO INICIAL', 'MODIFICACION', 'PRESUPUESTO FINAL', 'COMPROMETIDO', 'CAUSADO', 'ING. Y EGRE.', 'COMPROMETIDO', 'CAUSADO', 'ING. Y EGRE.', 'COMPROMETIDO', 'CAUSADO', 'ING. Y EGRE.', 'COMPROMETIDO', 'CAUSADO', 'ING. Y EGRE.', 'COMPROMETIDO', 'CAUSADO', 'ING. Y EGRE.'));
    $pdf->Ln(1);
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetAligns(array('C', 'L', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R'));
    $pdf->SetHeight(array(2));
}

//    Ejecucion por Trimestre...
function ejecucion_por_trimestre($pdf, $anio_fiscal, $fuente_financiamiento, $tipo_presupuesto, $trimestre)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    $pdf->Ln(8);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(270, 6, utf8_decode('Ejecución por Trimestre'), 0, 1, 'C');
    $pdf->Cell(270, 6, $trimestre . ' Trimestre', 0, 1, 'C');
    /////////////////////////////
    $pdf->SetFont('Arial', '', 6);
    $pdf->Cell(35, 4, 'Fuente de Financiamiento: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->Cell(50, 4, $fuente_financiamiento, 0, 1, 'L');
    $pdf->SetFont('Arial', '', 6);
    $pdf->Cell(35, 4, 'Tipo de Presupuesto: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->Cell(50, 4, $tipo_presupuesto, 0, 1, 'L');
    $pdf->SetFont('Arial', '', 6);
    $pdf->Cell(35, 4, utf8_decode('Año Fiscal: '), 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->Cell(50, 4, $anio_fiscal, 0, 1, 'L');
    $pdf->Ln(3);

    //Colores de los bordes, fondo y texto
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(240, 240, 240);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 7);

    $pdf->SetHeight(array(4));

    if ($trimestre == "I") {
        $pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
        $pdf->SetWidths(array(20, 135, 25, 25, 25, 25, 25, 25, 25));
        $pdf->Row(array('PARTIDA', 'DESCRIPCION', 'PRESUPUESTO INICIAL', 'MODIFICACION', 'PRESUPUESTO FINAL', 'COMPROMETIDO', 'CAUSADO', 'PAGADO', 'DISPONIBLE'));
        $pdf->SetAligns(array('C', 'L', 'R', 'R', 'R', 'R', 'R', 'R', 'R'));
        $pdf->SetHeight(array(4));
    } else {
        $pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
        $pdf->SetWidths(array(20, 128, 25, 25, 25, 25, 25, 25, 25, 25, 25));
        $pdf->Row(array('PARTIDA', 'DESCRIPCION', 'PRESUPUESTO INICIAL', 'MODIFICACION', 'PRESUPUESTO FINAL', 'COMPROMISO ACUMULADO', 'COMPROMETIDO', 'CAUSADO', 'PAGADO', 'DISPONIBLE'));
        $pdf->SetAligns(array('C', 'L', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R'));
        $pdf->SetHeight(array(4));
    }
    $pdf->Ln(1);
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetTextColor(0, 0, 0);
}

//    Traslados...
function traslado_presupuestario($pdf, $nsolicitud, $fsolicitud, $pag, $tipo_partida, $estado)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(15, 5);
    $pdf->Cell(120, 5, '', 0, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(50, 5, 'Fecha: ', 0, 0, 'R');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(35, 5, $fsolicitud, 0, 1, 'L');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(15, 5);
    $pdf->Cell(120, 5, '', 0, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(50, 5, utf8_decode('Página: '), 0, 0, 'R');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(35, 5, $pag, 0, 1, 'L');
    /////////////////////////////
    if ($estado == "elaboracion") {
        $titulo = "Solicitud de Traslados Presupuestarios";
    } elseif ($estado == "procesado") {
        $titulo = "Traslados Presupuestarios";
    } elseif ($estado == "anulado") {
        $titulo = "Traslados Presupuestarios (Anulado)";
    }

    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(205, 8, utf8_decode($titulo), 0, 1, 'C');
    /////////////////////////////
    $pdf->SetXY(5, 35);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(25, 7, 'Nro. Solicitud: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(50, 7, $nsolicitud, 0, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(28, 7, 'Fecha Solicitud: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(102, 7, $fsolicitud, 0, 0, 'L');
    /////////////////////////////
    $pdf->SetXY(5, 108);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(205, 5, $tipo_partida, 0, 1, 'C');
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(25, 5, 'CAT.PROGR.', 1, 0, 'C', 1);
    $pdf->Cell(35, 5, 'PARTIDA', 1, 0, 'C', 1);
    $pdf->Cell(100, 5, 'DESCRIPCION', 1, 0, 'C', 1);
    $pdf->Cell(45, 5, 'MONTO', 1, 0, 'C', 1);
    $pdf->Ln();
}

//    Traslados...
function traslado_presupuestario_simulado($pdf, $nsolicitud, $fsolicitud, $pag, $tipo_partida, $estado)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(15, 5);
    $pdf->Cell(120, 5, '', 0, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(50, 5, 'Fecha: ', 0, 0, 'R');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(35, 5, $fsolicitud, 0, 1, 'L');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(15, 5);
    $pdf->Cell(120, 5, '', 0, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(50, 5, utf8_decode('Página: '), 0, 0, 'R');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(35, 5, $pag, 0, 1, 'L');
    /////////////////////////////
    if ($estado == "elaboracion") {
        $titulo = "Solicitud de Traslados Presupuestarios";
    } elseif ($estado == "procesado") {
        $titulo = "Traslados Presupuestarios";
    } elseif ($estado == "anulado") {
        $titulo = "Traslados Presupuestarios (Anulado)";
    }

    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(205, 8, utf8_decode($titulo), 0, 1, 'C');
    /////////////////////////////
    $pdf->SetXY(5, 35);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(25, 7, 'Nro. Solicitud: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(50, 7, $nsolicitud, 0, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(28, 7, 'Fecha Solicitud: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(102, 7, $fsolicitud, 0, 0, 'L');
    /////////////////////////////
    $pdf->SetXY(5, 108);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(205, 5, $tipo_partida, 0, 1, 'C');
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->Cell(17, 5, 'C.Prog', 1, 0, 'C', 1);
    $pdf->Cell(20, 5, 'Partida', 1, 0, 'C', 1);
    $pdf->Cell(68, 5, utf8_decode('Descripción'), 1, 0, 'C', 1);
    $pdf->Cell(20, 5, 'M.Actual', 1, 0, 'C', 1);
    $pdf->Cell(20, 5, 'M.Ajuste', 1, 0, 'C', 1);
    $pdf->Cell(20, 5, 'M.Ajustado', 1, 0, 'C', 1);
    $pdf->Cell(20, 5, 'Disponible', 1, 0, 'C', 1);
    $pdf->Cell(20, 5, 'D.Ajustado', 1, 0, 'C', 1);
    $pdf->Ln(2);
}

//    Traslados...
function anexo_traslado_presupuestario($pdf, $nsolicitud, $fsolicitud, $pag, $tipo_partida, $estado)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(15, 5);
    $pdf->Cell(120, 5, '', 0, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(50, 5, 'Fecha: ', 0, 0, 'R');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(35, 5, $fsolicitud, 0, 1, 'L');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(15, 5);
    $pdf->Cell(120, 5, '', 0, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(50, 5, utf8_decode('Página: '), 0, 0, 'R');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(35, 5, $pag, 0, 1, 'L');
    /////////////////////////////
    if ($estado == "elaboracion") {
        $titulo = "Solicitud de Traslados Presupuestarios";
    } elseif ($estado == "procesado") {
        $titulo = "Traslados Presupuestarios";
    } elseif ($estado == "anulado") {
        $titulo = "Traslados Presupuestarios (Anulado)";
    }

    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(205, 8, utf8_decode($titulo), 0, 1, 'C');
    /////////////////////////////
    $pdf->SetXY(5, 35);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(75, 7, 'Nro. Solicitud: ' . $nsolicitud, 0, 0, 'L');
    $pdf->Cell(130, 7, 'Fecha Solicitud: ' . $fsolicitud, 0, 0, 'L');
    /////////////////////////////
    $pdf->SetXY(5, 50);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(205, 5, $tipo_partida, 0, 1, 'C');
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(25, 5, 'CAT.PROGR.', 1, 0, 'C', 1);
    $pdf->Cell(35, 5, 'PARTIDA', 1, 0, 'C', 1);
    $pdf->Cell(100, 5, 'DESCRIPCION', 1, 0, 'C', 1);
    $pdf->Cell(45, 5, 'MONTO', 1, 0, 'C', 1);
    $pdf->Ln(5);
}

//    Traslados...
function anexo_traslado_presupuestario_simulado($pdf, $nsolicitud, $fsolicitud, $pag, $tipo_partida, $estado)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(15, 5);
    $pdf->Cell(120, 5, '', 0, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(50, 5, 'Fecha: ', 0, 0, 'R');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(35, 5, $fsolicitud, 0, 1, 'L');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(15, 5);
    $pdf->Cell(120, 5, '', 0, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(50, 5, utf8_decode('Página: '), 0, 0, 'R');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(35, 5, $pag, 0, 1, 'L');
    /////////////////////////////
    if ($estado == "elaboracion") {
        $titulo = "Solicitud de Traslados Presupuestarios";
    } elseif ($estado == "procesado") {
        $titulo = "Traslados Presupuestarios";
    } elseif ($estado == "anulado") {
        $titulo = "Traslados Presupuestarios (Anulado)";
    }

    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(205, 8, utf8_decode($titulo), 0, 1, 'C');
    /////////////////////////////
    $pdf->SetXY(5, 35);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(75, 7, 'Nro. Solicitud: ' . $nsolicitud, 0, 0, 'L');
    $pdf->Cell(130, 7, 'Fecha Solicitud: ' . $fsolicitud, 0, 0, 'L');
    /////////////////////////////
    $pdf->SetXY(5, 50);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(205, 5, $tipo_partida, 0, 1, 'C');
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->Cell(17, 5, 'C.Prog', 1, 0, 'C', 1);
    $pdf->Cell(20, 5, 'Partida', 1, 0, 'C', 1);
    $pdf->Cell(68, 5, utf8_decode('Descripción'), 1, 0, 'C', 1);
    $pdf->Cell(20, 5, 'M.Actual', 1, 0, 'C', 1);
    $pdf->Cell(20, 5, 'M.Ajuste', 1, 0, 'C', 1);
    $pdf->Cell(20, 5, 'M.Ajustado', 1, 0, 'C', 1);
    $pdf->Cell(20, 5, 'Disponible', 1, 0, 'C', 1);
    $pdf->Cell(20, 5, 'D.Ajustado', 1, 0, 'C', 1);
    $pdf->Ln(2);
}

//    Disminucion Presupuesto...
function disminucion_presupuestaria($pdf, $nsolicitud, $fsolicitud, $pag, $tipo_partida, $y, $estado)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(15, 5);
    $pdf->Cell(120, 5, '', 0, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(50, 5, 'Fecha: ', 0, 0, 'R');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(35, 5, $fsolicitud, 0, 1, 'L');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(15, 5);
    $pdf->Cell(120, 5, '', 0, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(50, 5, utf8_decode('Página: '), 0, 0, 'R');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(35, 5, $pag, 0, 1, 'L');
    /////////////////////////////
    if ($estado == "elaboracion") {
        $titulo = "Solicitud de Disminucion Presupuestaria";
    } elseif ($estado == "procesado") {
        $titulo = "Disminucion Presupuestaria";
    } elseif ($estado == "anulado") {
        $titulo = "Disminucion Presupuestaria (Anulado)";
    }

    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(205, 8, utf8_decode($titulo), 0, 1, 'C');
    /////////////////////////////
    $pdf->SetXY(5, 35);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(25, 7, 'Nro. Solicitud: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(50, 7, $nsolicitud, 0, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(28, 7, 'Fecha Solicitud: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(102, 7, $fsolicitud, 0, 0, 'L');
    /////////////////////////////
    $pdf->SetXY(5, $y);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(205, 5, $tipo_partida, 0, 1, 'C');
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(25, 5, 'CAT.PROGR.', 1, 0, 'C', 1);
    $pdf->Cell(35, 5, 'PARTIDA', 1, 0, 'C', 1);
    $pdf->Cell(100, 5, 'DESCRIPCION', 1, 0, 'C', 1);
    $pdf->Cell(45, 5, 'MONTO', 1, 0, 'C', 1);
    $pdf->Ln(5);
}

//    Disminucion Presupuesto...
function disminucion_presupuestaria_simulado($pdf, $nsolicitud, $fsolicitud, $pag, $tipo_partida, $y, $estado)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(15, 5);
    $pdf->Cell(120, 5, '', 0, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(50, 5, 'Fecha: ', 0, 0, 'R');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(35, 5, $fsolicitud, 0, 1, 'L');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(15, 5);
    $pdf->Cell(120, 5, '', 0, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(50, 5, utf8_decode('Página: '), 0, 0, 'R');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(35, 5, $pag, 0, 1, 'L');
    /////////////////////////////
    if ($estado == "elaboracion") {
        $titulo = "Solicitud de Disminucion Presupuestaria";
    } elseif ($estado == "procesado") {
        $titulo = "Disminucion Presupuestaria";
    } elseif ($estado == "anulado") {
        $titulo = "Disminucion Presupuestaria (Anulado)";
    }

    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(205, 8, utf8_decode($titulo), 0, 1, 'C');
    /////////////////////////////
    $pdf->SetXY(5, 35);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(25, 7, 'Nro. Solicitud: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(50, 7, $nsolicitud, 0, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(28, 7, 'Fecha Solicitud: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(102, 7, $fsolicitud, 0, 0, 'L');
    /////////////////////////////
    $pdf->SetXY(5, $y);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(205, 5, $tipo_partida, 0, 1, 'C');
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->Cell(17, 5, 'C.Prog', 1, 0, 'C', 1);
    $pdf->Cell(20, 5, 'Partida', 1, 0, 'C', 1);
    $pdf->Cell(68, 5, utf8_decode('Descripción'), 1, 0, 'C', 1);
    $pdf->Cell(20, 5, 'M.Actual', 1, 0, 'C', 1);
    $pdf->Cell(20, 5, 'M.Ajuste', 1, 0, 'C', 1);
    $pdf->Cell(20, 5, 'M.Ajustado', 1, 0, 'C', 1);
    $pdf->Cell(20, 5, 'Disponible', 1, 0, 'C', 1);
    $pdf->Cell(20, 5, 'D.Ajustado', 1, 0, 'C', 1);
    $pdf->Ln(2);
}

//    Creditos Adicionales...
function credito_adicional($pdf, $nsolicitud, $fsolicitud, $pag, $tipo_partida, $y, $estado)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(15, 5);
    $pdf->Cell(120, 5, '', 0, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(50, 5, 'Fecha: ', 0, 0, 'R');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(35, 5, $fsolicitud, 0, 1, 'L');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(15, 5);
    $pdf->Cell(120, 5, '', 0, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(50, 5, utf8_decode('Página: '), 0, 0, 'R');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(35, 5, $pag, 0, 1, 'L');
    /////////////////////////////
    if ($estado == "elaboracion") {
        $titulo = "Solicitud de Créditos Adicionales";
    } elseif ($estado == "procesado") {
        $titulo = "Créditos Adicionales";
    } elseif ($estado == "anulado") {
        $titulo = "Créditos Adicionales (Anulado)";
    }

    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(205, 8, utf8_decode($titulo), 0, 1, 'C');
    /////////////////////////////
    $pdf->SetXY(5, 35);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(25, 7, 'Nro. Solicitud: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(50, 7, $nsolicitud, 0, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(28, 7, 'Fecha Solicitud: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(102, 7, $fsolicitud, 0, 0, 'L');
    /////////////////////////////
    $pdf->SetXY(5, $y);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(205, 5, $tipo_partida, 0, 1, 'C');
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(25, 5, 'CAT.PROGR.', 1, 0, 'C', 1);
    $pdf->Cell(35, 5, 'PARTIDA', 1, 0, 'C', 1);
    $pdf->Cell(100, 5, 'DESCRIPCION', 1, 0, 'C', 1);
    $pdf->Cell(45, 5, 'MONTO', 1, 0, 'C', 1);
    $pdf->Ln(5);
}

//    Creditos Adicionales...
function credito_adicional_simulado($pdf, $nsolicitud, $fsolicitud, $pag, $tipo_partida, $y, $estado)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(15, 5);
    $pdf->Cell(120, 5, '', 0, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(50, 5, 'Fecha: ', 0, 0, 'R');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(35, 5, $fsolicitud, 0, 1, 'L');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(15, 5);
    $pdf->Cell(120, 5, '', 0, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(50, 5, utf8_decode('Página: '), 0, 0, 'R');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(35, 5, $pag, 0, 1, 'L');
    /////////////////////////////
    if ($estado == "elaboracion") {
        $titulo = "Solicitud de Créditos Adicionales";
    } elseif ($estado == "procesado") {
        $titulo = "Créditos Adicionales";
    } elseif ($estado == "anulado") {
        $titulo = "Créditos Adicionales (Anulado)";
    }

    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(205, 8, utf8_decode($titulo), 0, 1, 'C');
    /////////////////////////////
    $pdf->SetXY(5, 35);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(25, 7, 'Nro. Solicitud: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(50, 7, $nsolicitud, 0, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(28, 7, 'Fecha Solicitud: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(102, 7, $fsolicitud, 0, 0, 'L');
    /////////////////////////////
    $pdf->SetXY(5, $y);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(205, 5, $tipo_partida, 0, 1, 'C');
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->Cell(17, 5, 'C.Prog', 1, 0, 'C', 1);
    $pdf->Cell(20, 5, 'Partida', 1, 0, 'C', 1);
    $pdf->Cell(68, 5, utf8_decode('Descripción'), 1, 0, 'C', 1);
    $pdf->Cell(20, 5, 'M.Actual', 1, 0, 'C', 1);
    $pdf->Cell(20, 5, 'M.Ajuste', 1, 0, 'C', 1);
    $pdf->Cell(20, 5, 'M.Ajustado', 1, 0, 'C', 1);
    $pdf->Cell(20, 5, 'Disponible', 1, 0, 'C', 1);
    $pdf->Cell(20, 5, 'D.Ajustado', 1, 0, 'C', 1);
    $pdf->Ln(2);
}

//    Rectificacion de Partidas...
function rectificacion_partida($pdf, $nsolicitud, $fsolicitud, $pag, $tipo_partida, $estado)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(15, 5);
    $pdf->Cell(120, 5, '', 0, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(50, 5, 'Fecha: ', 0, 0, 'R');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(35, 5, $fsolicitud, 0, 1, 'L');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(15, 5);
    $pdf->Cell(120, 5, '', 0, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(50, 5, utf8_decode('Página: '), 0, 0, 'R');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(35, 5, $pag, 0, 1, 'L');
    /////////////////////////////
    if ($estado == "elaboracion") {
        $titulo = "Solicitud de Rectificación Presupuestaria";
    } elseif ($estado == "procesado") {
        $titulo = "Rectificación Presupuestaria";
    } elseif ($estado == "anulado") {
        $titulo = "Rectificación Presupuestaria (Anulado)";
    }

    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(205, 8, utf8_decode($titulo), 0, 1, 'C');
    /////////////////////////////
    $pdf->SetXY(5, 35);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(25, 7, 'Nro. Solicitud: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(50, 7, $nsolicitud, 0, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(28, 7, 'Fecha Solicitud: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(102, 7, $fsolicitud, 0, 0, 'L');
    /////////////////////////////
    $pdf->SetXY(5, 108);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(205, 5, $tipo_partida, 0, 1, 'C');
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(25, 5, 'CAT.PROGR.', 1, 0, 'C', 1);
    $pdf->Cell(35, 5, 'PARTIDA', 1, 0, 'C', 1);
    $pdf->Cell(100, 5, 'DESCRIPCION', 1, 0, 'C', 1);
    $pdf->Cell(45, 5, 'MONTO', 1, 0, 'C', 1);
    $pdf->Ln(5);
}

//    Rectificacion de Partidas...
function rectificacion_partida_simulado($pdf, $nsolicitud, $fsolicitud, $pag, $tipo_partida, $estado)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(15, 5);
    $pdf->Cell(120, 5, '', 0, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(50, 5, 'Fecha: ', 0, 0, 'R');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(35, 5, $fsolicitud, 0, 1, 'L');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(15, 5);
    $pdf->Cell(120, 5, '', 0, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(50, 5, utf8_decode('Página: '), 0, 0, 'R');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(35, 5, $pag, 0, 1, 'L');
    /////////////////////////////
    if ($estado == "elaboracion") {
        $titulo = "Solicitud de Rectificación Presupuestaria";
    } elseif ($estado == "procesado") {
        $titulo = "Rectificación Presupuestaria";
    } elseif ($estado == "anulado") {
        $titulo = "Rectificación Presupuestaria (Anulado)";
    }

    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(205, 8, utf8_decode($titulo), 0, 1, 'C');
    /////////////////////////////
    $pdf->SetXY(5, 35);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(25, 7, 'Nro. Solicitud: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(50, 7, $nsolicitud, 0, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(28, 7, 'Fecha Solicitud: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(102, 7, $fsolicitud, 0, 0, 'L');
    /////////////////////////////
    $pdf->SetXY(5, 108);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(205, 5, $tipo_partida, 0, 1, 'C');
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->Cell(17, 5, 'C.Prog', 1, 0, 'C', 1);
    $pdf->Cell(20, 5, 'Partida', 1, 0, 'C', 1);
    $pdf->Cell(68, 5, utf8_decode('Descripción'), 1, 0, 'C', 1);
    $pdf->Cell(20, 5, 'M.Actual', 1, 0, 'C', 1);
    $pdf->Cell(20, 5, 'M.Ajuste', 1, 0, 'C', 1);
    $pdf->Cell(20, 5, 'M.Ajustado', 1, 0, 'C', 1);
    $pdf->Cell(20, 5, 'Disponible', 1, 0, 'C', 1);
    $pdf->Cell(20, 5, 'D.Ajustado', 1, 0, 'C', 1);
    $pdf->Ln(2);
}

//    Rectificacion de Partidas...
function anexo_rectificacion_partida($pdf, $nsolicitud, $fsolicitud, $pag, $tipo_partida, $estado)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(15, 5);
    $pdf->Cell(120, 5, '', 0, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(50, 5, 'Fecha: ', 0, 0, 'R');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(35, 5, $fsolicitud, 0, 1, 'L');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(15, 5);
    $pdf->Cell(120, 5, '', 0, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(50, 5, utf8_decode('Página: '), 0, 0, 'R');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(35, 5, $pag, 0, 1, 'L');
    /////////////////////////////
    if ($estado == "elaboracion") {
        $titulo = "Solicitud de Rectificación Presupuestaria";
    } elseif ($estado == "procesado") {
        $titulo = "Rectificación Presupuestaria";
    } elseif ($estado == "anulado") {
        $titulo = "Rectificación Presupuestaria (Anulado)";
    }

    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(205, 8, utf8_decode($titulo), 0, 1, 'C');
    /////////////////////////////
    $pdf->SetXY(5, 35);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(75, 7, 'Nro. Solicitud: ' . $nsolicitud, 0, 0, 'L');
    $pdf->Cell(130, 7, 'Fecha Solicitud: ' . $fsolicitud, 0, 0, 'L');
    /////////////////////////////
    $pdf->SetXY(5, 50);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(205, 5, $tipo_partida, 0, 1, 'C');
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(25, 5, 'CAT.PROGR.', 1, 0, 'C', 1);
    $pdf->Cell(35, 5, 'PARTIDA', 1, 0, 'C', 1);
    $pdf->Cell(100, 5, 'DESCRIPCION', 1, 0, 'C', 1);
    $pdf->Cell(45, 5, 'MONTO', 1, 0, 'C', 1);
    $pdf->Ln(5);
}

//    Rectificacion de Partidas...
function anexo_rectificacion_partida_simulado($pdf, $nsolicitud, $fsolicitud, $pag, $tipo_partida, $estado)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(15, 5);
    $pdf->Cell(120, 5, '', 0, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(50, 5, 'Fecha: ', 0, 0, 'R');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(35, 5, $fsolicitud, 0, 1, 'L');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(15, 5);
    $pdf->Cell(120, 5, '', 0, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(50, 5, utf8_decode('Página: '), 0, 0, 'R');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(35, 5, $pag, 0, 1, 'L');
    /////////////////////////////
    if ($estado == "elaboracion") {
        $titulo = "Solicitud de Rectificación Presupuestaria";
    } elseif ($estado == "procesado") {
        $titulo = "Rectificación Presupuestaria";
    } elseif ($estado == "anulado") {
        $titulo = "Rectificación Presupuestaria (Anulado)";
    }

    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(205, 8, utf8_decode($titulo), 0, 1, 'C');
    /////////////////////////////
    $pdf->SetXY(5, 35);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(75, 7, 'Nro. Solicitud: ' . $nsolicitud, 0, 0, 'L');
    $pdf->Cell(130, 7, 'Fecha Solicitud: ' . $fsolicitud, 0, 0, 'L');
    /////////////////////////////
    $pdf->SetXY(5, 50);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(205, 5, $tipo_partida, 0, 1, 'C');
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->Cell(17, 5, 'C.Prog', 1, 0, 'C', 1);
    $pdf->Cell(20, 5, 'Partida', 1, 0, 'C', 1);
    $pdf->Cell(68, 5, utf8_decode('Descripción'), 1, 0, 'C', 1);
    $pdf->Cell(20, 5, 'M.Actual', 1, 0, 'C', 1);
    $pdf->Cell(20, 5, 'M.Ajuste', 1, 0, 'C', 1);
    $pdf->Cell(20, 5, 'M.Ajustado', 1, 0, 'C', 1);
    $pdf->Cell(20, 5, 'Disponible', 1, 0, 'C', 1);
    $pdf->Cell(20, 5, 'D.Ajustado', 1, 0, 'C', 1);
    $pdf->Ln(2);
}

//    Compromisos en Transito...
function compromisos_en_transito($pdf)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    $pdf->Ln(8);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(190, 10, utf8_decode('Relación Compromisos en Tránsito'), 0, 1, 'C');
}

//    Movimientos de Presupuesto...
function movimientos_presupuesto($pdf, $anio_fiscal, $fuente_financiamiento, $tipo_presupuesto)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    //    --------------------
    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(205, 8, utf8_decode("Movimientos de Presupuesto"), 0, 1, 'C');
    //    --------------------
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 4, 'Fuente de Financiamiento: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(50, 4, $fuente_financiamiento, 0, 1, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 4, 'Tipo de Presupuesto: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(50, 4, $tipo_presupuesto, 0, 1, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 4, utf8_decode('Año Fiscal: '), 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(50, 4, $anio_fiscal, 0, 1, 'L');
    //    --------------------
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(25, 5, 'CAT.PROGR.', 1, 0, 'C', 1);
    $pdf->Cell(25, 5, 'PARTIDA', 1, 0, 'C', 1);
    $pdf->Cell(95, 5, 'DESCRIPCION', 1, 0, 'C', 1);
    $pdf->Cell(30, 5, 'TIPO', 1, 0, 'C', 1);
    $pdf->Cell(25, 5, 'MONTO', 1, 1, 'C', 1);
    $pdf->Ln(2);
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', '', 8);
    $pdf->SetAligns(array('C', 'C', 'L', 'L', 'R'));
    $pdf->SetWidths(array(25, 25, 95, 30, 25));
}

//    Consolidado Agrupado
function consolidado_agrupado($pdf, $anio_fiscal, $fuente_financiamiento, $tipo_presupuesto, $campos)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    $pdf->Ln(8);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(345, 10, utf8_decode('Consolidado Agrupado'), 0, 1, 'C');
    //    --------------------
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 4, 'Fuente de Financiamiento: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(50, 4, $fuente_financiamiento, 0, 1, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 4, 'Tipo de Presupuesto: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(50, 4, $tipo_presupuesto, 0, 1, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 4, utf8_decode('Año Fiscal: '), 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(50, 4, $anio_fiscal, 0, 1, 'L');
    //    --------------------
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(100, 100, 100);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('Arial', 'B', 5);
    $pdf->Cell(20, 4, 'Partida', 0, 0, 'C', 1);
    $pdf->Cell(50, 4, 'Descripcion', 0, 0, 'L', 1);
    if ($campos[0]) {
        $pdf->Cell(23, 4, 'Asig. Inicial', 0, 0, 'R', 1);
    }

    if ($campos[1]) {
        $pdf->Cell(23, 4, 'Aumento', 0, 0, 'R', 1);
    }

    if ($campos[2]) {
        $pdf->Cell(23, 4, 'Disminucion', 0, 0, 'R', 1);
    }

    if ($campos[3]) {
        $pdf->Cell(23, 4, 'Modificado', 0, 0, 'R', 1);
    }

    if ($campos[4]) {
        $pdf->Cell(23, 4, 'Asig. Ajustada', 0, 0, 'R', 1);
    }

    if ($campos[5]) {
        $pdf->Cell(23, 4, 'Pre-Compromiso', 0, 0, 'R', 1);
    }

    if ($campos[6]) {
        $pdf->Cell(23, 4, 'Compromiso', 0, 0, 'R', 1);
        $pdf->Cell(11, 4, '% Comp.', 0, 0, 'R', 1);}
    if ($campos[7]) {
        $pdf->Cell(23, 4, 'Causado', 0, 0, 'R', 1);
        $pdf->Cell(11, 4, '% Cau.', 0, 0, 'R', 1);}
    if ($campos[8]) {
        $pdf->Cell(23, 4, 'Pagado', 0, 0, 'R', 1);
        $pdf->Cell(11, 4, '% Pag.', 0, 0, 'R', 1);}
    if ($campos[9]) {
        $pdf->Cell(23, 4, 'Disponible', 0, 0, 'R', 1);
        $pdf->Cell(11, 4, '% Disp.', 0, 0, 'R', 1);}
    $pdf->Ln(4);
}

//    Consolidado Agrupado
function conlidado_por_categoria($pdf, $anio_fiscal, $fuente_financiamiento, $tipo_presupuesto, $campos, $periodo)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    $pdf->Ln(8);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(345, 10, utf8_decode('Consolidado Agrupado'), 0, 1, 'C');
    //    --------------------
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 4, 'Fuente de Financiamiento: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(50, 4, $fuente_financiamiento, 0, 1, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 4, 'Tipo de Presupuesto: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(50, 4, $tipo_presupuesto, 0, 1, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 4, utf8_decode('Año Fiscal: '), 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(50, 4, $anio_fiscal, 0, 1, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 4, utf8_decode('Periodo: '), 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(50, 4, $periodo, 0, 1, 'L');
    //    --------------------
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(100, 100, 100);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('Arial', 'B', 5);
    $pdf->Cell(20, 4, 'Partida', 0, 0, 'C', 1);
    $pdf->Cell(50, 4, 'Descripcion', 0, 0, 'L', 1);
    if ($campos[0]) {
        $pdf->Cell(23, 4, 'Asig. Inicial', 0, 0, 'R', 1);
    }

    if ($campos[1]) {
        $pdf->Cell(23, 4, 'Aumento', 0, 0, 'R', 1);
    }

    if ($campos[2]) {
        $pdf->Cell(23, 4, 'Disminucion', 0, 0, 'R', 1);
    }

    if ($campos[3]) {
        $pdf->Cell(23, 4, 'Modificado', 0, 0, 'R', 1);
    }

    if ($campos[4]) {
        $pdf->Cell(23, 4, 'Asig. Ajustada', 0, 0, 'R', 1);
    }

    if ($campos[5]) {
        $pdf->Cell(23, 4, 'Pre-Compromiso', 0, 0, 'R', 1);
    }

    if ($campos[6]) {
        $pdf->Cell(23, 4, 'Compromiso', 0, 0, 'R', 1);
        $pdf->Cell(11, 4, '% Comp.', 0, 0, 'R', 1);}
    if ($campos[7]) {
        $pdf->Cell(23, 4, 'Causado', 0, 0, 'R', 1);
        $pdf->Cell(11, 4, '% Cau.', 0, 0, 'R', 1);}
    if ($campos[8]) {
        $pdf->Cell(23, 4, 'Pagado', 0, 0, 'R', 1);
        $pdf->Cell(11, 4, '% Pag.', 0, 0, 'R', 1);}
    if ($campos[9]) {
        $pdf->Cell(23, 4, 'Disponible', 0, 0, 'R', 1);
        $pdf->Cell(11, 4, '% Disp.', 0, 0, 'R', 1);}
    $pdf->Ln(4);
}

//    Rendicion Mensual
function rendicion_mensual($pdf, $anio_fiscal, $fuente_financiamiento, $tipo_presupuesto, $periodo)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    $pdf->Ln(8);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(345, 10, utf8_decode('Rendición Mensual'), 0, 1, 'C');
    //    --------------------
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 4, 'Fuente de Financiamiento: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(50, 4, $fuente_financiamiento, 0, 1, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 4, 'Tipo de Presupuesto: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(50, 4, $tipo_presupuesto, 0, 1, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 4, utf8_decode('Año Fiscal: '), 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(50, 4, $anio_fiscal, 0, 1, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 4, utf8_decode('Mes: '), 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(50, 4, $periodo, 0, 1, 'L');
    //    --------------------
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(100, 100, 100);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('Arial', 'B', 5);
    $pdf->Cell(20, 4, 'Partida', 0, 0, 'C', 1);
    $pdf->Cell(50, 4, 'Descripcion', 0, 0, 'L', 1);
    $pdf->Cell(23, 4, 'Presupuestaria Anterior', 0, 0, 'R', 1);
    $pdf->Cell(23, 4, 'Financiera Anterior', 0, 0, 'R', 1);
    $pdf->Cell(23, 4, 'Aumento', 0, 0, 'R', 1);
    $pdf->Cell(23, 4, 'Disminucion', 0, 0, 'R', 1);
    $pdf->Cell(23, 4, 'Modificado', 0, 0, 'R', 1);
    $pdf->Cell(23, 4, 'Presupuesto Ajustado', 0, 0, 'R', 1);
    $pdf->Cell(23, 4, 'Compromiso', 0, 0, 'R', 1);
    $pdf->Cell(23, 4, 'Causado', 0, 0, 'R', 1);
    $pdf->Cell(23, 4, 'Disponibilidad Presupuestaria', 0, 0, 'R', 1);
    $pdf->Cell(23, 4, 'Pagado', 0, 0, 'R', 1);
    $pdf->Cell(23, 4, 'Disponibilidad Financiera', 0, 0, 'R', 1);
    $pdf->Ln(4);
}

//    Ejecucion MENSUAL ONAPRE
function mensual_onapre($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $idesde, $ihasta, $mes)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    $pdf->Ln(8);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(270, 6, utf8_decode('Ejecución Mensual'), 0, 1, 'C');
    $pdf->Cell(270, 6, 'Desde: ' . $idesde . ' Hasta:' . $ihasta, 0, 1, 'C');
    /////////////////////////////
    $pdf->SetFont('Arial', '', 6);
    $pdf->Cell(35, 4, 'Fuente de Financiamiento: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->Cell(50, 4, $nom_fuente_financiamiento, 0, 1, 'L');
    $pdf->SetFont('Arial', '', 6);
    $pdf->Cell(35, 4, 'Tipo de Presupuesto: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->Cell(50, 4, $nom_tipo_presupuesto, 0, 1, 'L');
    $pdf->SetFont('Arial', '', 6);
    $pdf->Cell(35, 4, utf8_decode('Año Fiscal: '), 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->Cell(50, 4, $anio_fiscal, 0, 1, 'L');
    $pdf->Ln(3);

    //Colores de los bordes, fondo y texto
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 7);

    $pdf->SetHeight(array(4));

    if ($mes == "01") {
        $pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
        $pdf->SetWidths(array(20, 88, 30, 30, 30, 30, 30, 30, 30, 30));
        $pdf->Row(array('PARTIDA', 'DENOMINACION', 'PRESUPUESTO INICIAL', 'MODIFICACION', 'PRESUPUESTO AJUSTADO', 'COMPROMETIDO', 'CAUSADO', 'DISPONIBILIDAD PRESUPUESTARIA', 'PAGADO', 'DISPONIBILIDAD FINANCIERA'));
        $pdf->SetAligns(array('C', 'L', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R'));
        $pdf->SetHeight(array(4));
    } else {
        $pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
        $pdf->SetWidths(array(20, 58, 30, 30, 30, 30, 30, 30, 30, 30, 30));
        $pdf->Row(array('PARTIDA', 'DENOMINACION', 'PRESUPUESTARIA ANTERIOR', 'FINANCIERA ANTERIOR', 'MODIFICACION', 'PRESUPUESTO AJUSTADO', 'COMPROMETIDO', 'CAUSADO', 'DISPONIBILIDAD PRESUPUESTARIA', 'PAGADO', 'DISPONIBILIDAD FINANCIERA'));
        $pdf->SetAligns(array('C', 'L', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R'));
        $pdf->SetHeight(array(4));
    }
    $pdf->Ln(1);
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetTextColor(0, 0, 0);
}

//    Ejecucion MENSUAL ONAPRE
function trimestral_onapre($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $idesde, $ihasta, $mes)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    $pdf->Ln(8);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(270, 6, utf8_decode('Ejecución Trimestral'), 0, 1, 'C');
    $pdf->Cell(270, 6, 'Desde: ' . $idesde . ' Hasta:' . $ihasta, 0, 1, 'C');
    if ($mes == "01") {
        $pdf->Cell(270, 6, 'TRIMESTRE I', 0, 1, 'C');
    } elseif ($mes == "02") {
        $pdf->Cell(270, 6, 'TRIMESTRE II', 0, 1, 'C');
    } elseif ($mes == "03") {
        $pdf->Cell(270, 6, 'TRIMESTRE III', 0, 1, 'C');
    } elseif ($mes == "04") {
        $pdf->Cell(270, 6, 'TRIMESTRE IV', 0, 1, 'C');
    }
    /////////////////////////////
    $pdf->SetFont('Arial', '', 6);
    $pdf->Cell(35, 4, 'Fuente de Financiamiento: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->Cell(50, 4, $nom_fuente_financiamiento, 0, 1, 'L');
    $pdf->SetFont('Arial', '', 6);
    $pdf->Cell(35, 4, 'Tipo de Presupuesto: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->Cell(50, 4, $nom_tipo_presupuesto, 0, 1, 'L');
    $pdf->SetFont('Arial', '', 6);
    $pdf->Cell(35, 4, utf8_decode('Año Fiscal: '), 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->Cell(50, 4, $anio_fiscal, 0, 1, 'L');
    $pdf->Ln(3);

    //Colores de los bordes, fondo y texto
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 7);

    $pdf->SetHeight(array(4));

    if ($mes == "01") {
        $pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
        $pdf->SetWidths(array(20, 112, 27, 27, 27, 27, 27, 27, 27, 27));
        $pdf->Row(array('PARTIDA', 'DENOMINACION', 'PRESUPUESTO INICIAL', 'MODIFICACION', 'PRESUPUESTO AJUSTADO', 'COMPROMETIDO', 'CAUSADO', 'DISPONIBILIDAD PRESUPUESTARIA', 'PAGADO', 'DISPONIBILIDAD FINANCIERA'));
        $pdf->SetAligns(array('C', 'L', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R'));
        $pdf->SetHeight(array(4));
    } else {
        $pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
        $pdf->SetWidths(array(20, 85, 27, 27, 27, 27, 27, 27, 27, 27, 27));
        $pdf->Row(array('PARTIDA', 'DENOMINACION', 'PRESUPUESTARIA ANTERIOR', 'FINANCIERA ANTERIOR', 'MODIFICACION', 'PRESUPUESTO AJUSTADO', 'COMPROMETIDO', 'CAUSADO', 'DISPONIBILIDAD PRESUPUESTARIA', 'PAGADO', 'DISPONIBILIDAD FINANCIERA'));
        $pdf->SetAligns(array('C', 'L', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R'));
        $pdf->SetHeight(array(4));
    }
    $pdf->Ln(1);
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetTextColor(0, 0, 0);
}

//    Ejecucion ANUAL ONAPRE
function anual_onapre($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $idesde, $ihasta)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    $pdf->Ln(8);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(270, 6, utf8_decode('Ejecución ANUAL'), 0, 1, 'C');
    $pdf->Cell(270, 6, 'Desde: ' . $idesde . ' Hasta:' . $ihasta, 0, 1, 'C');

    /////////////////////////////
    $pdf->SetFont('Arial', '', 6);
    $pdf->Cell(35, 4, 'Fuente de Financiamiento: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->Cell(50, 4, $nom_fuente_financiamiento, 0, 1, 'L');
    $pdf->SetFont('Arial', '', 6);
    $pdf->Cell(35, 4, 'Tipo de Presupuesto: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->Cell(50, 4, $nom_tipo_presupuesto, 0, 1, 'L');
    $pdf->SetFont('Arial', '', 6);
    $pdf->Cell(35, 4, utf8_decode('Año Fiscal: '), 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->Cell(50, 4, $anio_fiscal, 0, 1, 'L');
    $pdf->Ln(3);

    //Colores de los bordes, fondo y texto
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 7);

    $pdf->SetHeight(array(4));

    $pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
    $pdf->SetWidths(array(20, 78, 30, 30, 30, 30, 30, 30, 30, 30));
    $pdf->Row(array('PARTIDA', 'DENOMINACION', 'PRESUPUESTO INICIAL', 'MODIFICACION', 'PRESUPUESTO AJUSTADO', 'COMPROMETIDO', 'CAUSADO', 'DISPONIBILIDAD PRESUPUESTARIA', 'PAGADO', 'DISPONIBILIDAD FINANCIERA'));
    $pdf->SetAligns(array('C', 'L', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R'));
    $pdf->SetHeight(array(4));

    $pdf->Ln(1);
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetTextColor(0, 0, 0);
}

//    Consolidado por Sector...
function consolidado_sector_onapre($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $fdesde, $fhasta, $trimestre, $CodSector, $Sector)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    $pdf->Ln(8);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(270, 6, utf8_decode('Consolidado por Sector'), 0, 1, 'C');
    $pdf->Cell(270, 6, 'Desde: ' . $fdesde . ' Hasta:' . $fhasta, 0, 1, 'C');
    if ($trimestre == "00") {
        $pdf->Cell(270, 6, '', 0, 1, 'C');
    } elseif ($trimestre == "01") {
        $pdf->Cell(270, 6, 'TRIMESTRE I', 0, 1, 'C');
    } elseif ($trimestre == "02") {
        $pdf->Cell(270, 6, 'TRIMESTRE II', 0, 1, 'C');
    } elseif ($trimestre == "03") {
        $pdf->Cell(270, 6, 'TRIMESTRE III', 0, 1, 'C');
    } elseif ($trimestre == "04") {
        $pdf->Cell(270, 6, 'TRIMESTRE IV', 0, 1, 'C');
    }
    /////////////////////////////
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 4, 'Fuente de Financiamiento: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->Cell(50, 4, $nom_fuente_financiamiento, 0, 1, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 4, 'Tipo de Presupuesto: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->Cell(50, 4, $nom_tipo_presupuesto, 0, 1, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 4, utf8_decode('Año Fiscal: '), 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->Cell(50, 4, $anio_fiscal, 0, 1, 'L');
    $pdf->Ln(2);
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 4, 'SECTOR: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(50, 4, $CodSector . '    ' . utf8_decode($Sector), 0, 1, 'L');
    //$pdf->Cell(205, 7, $CodSector.'    '.utf8_decode($Sector), 1, 1, 'L', 1);
    //Colores de los bordes, fondo y texto
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 6);

    $pdf->SetHeight(array(4));

    if ($trimestre == '00' or $trimestre == "01") {
        $pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
        $pdf->SetWidths(array(20, 78, 30, 30, 30, 30, 30, 30, 30, 30));
        $pdf->Row(array('PARTIDA', 'DENOMINACION', 'PRESUPUESTO INICIAL', 'MODIFICACION', 'PRESUPUESTO AJUSTADO', 'COMPROMETIDO', 'CAUSADO', 'DISPONIBILIDAD PRESUPUESTARIA', 'PAGADO', 'DISPONIBILIDAD FINANCIERA'));
        $pdf->SetAligns(array('C', 'L', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R'));
        $pdf->SetHeight(array(4));
    } else {
        $pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
        $pdf->SetWidths(array(20, 61, 30, 30, 30, 30, 30, 30, 30, 30, 30));
        $pdf->Row(array('PARTIDA', 'DENOMINACION', 'PRESUPUESTARIA ANTERIOR', 'FINANCIERA ANTERIOR', 'MODIFICACION', 'PRESUPUESTO AJUSTADO', 'COMPROMETIDO', 'CAUSADO', 'DISPONIBILIDAD PRESUPUESTARIA', 'PAGADO', 'DISPONIBILIDAD FINANCIERA'));
        $pdf->SetAligns(array('C', 'L', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R'));
        $pdf->SetHeight(array(4));
    }
    $pdf->Ln(4);
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetTextColor(0, 0, 0);
}

//    Consolidado por Programa...
function consolidado_programa_onapre($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $fdesde, $fhasta, $trimestre, $CodSector, $Sector, $CodPrograma, $Programa)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    $pdf->Ln(8);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(270, 6, utf8_decode('Consolidado por Programa'), 0, 1, 'C');
    $pdf->Cell(270, 6, 'Desde: ' . $fdesde . ' Hasta:' . $fhasta, 0, 1, 'C');
    if ($trimestre == "00") {
        $pdf->Cell(270, 6, '', 0, 1, 'C');
    } elseif ($trimestre == "01") {
        $pdf->Cell(270, 6, 'TRIMESTRE I', 0, 1, 'C');
    } elseif ($trimestre == "02") {
        $pdf->Cell(270, 6, 'TRIMESTRE II', 0, 1, 'C');
    } elseif ($trimestre == "03") {
        $pdf->Cell(270, 6, 'TRIMESTRE III', 0, 1, 'C');
    } elseif ($trimestre == "04") {
        $pdf->Cell(270, 6, 'TRIMESTRE IV', 0, 1, 'C');
    }
    /////////////////////////////
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 4, 'Fuente de Financiamiento: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->Cell(50, 4, $nom_fuente_financiamiento, 0, 1, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 4, 'Tipo de Presupuesto: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->Cell(50, 4, $nom_tipo_presupuesto, 0, 1, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 4, utf8_decode('Año Fiscal: '), 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->Cell(50, 4, $anio_fiscal, 0, 1, 'L');
    $pdf->Ln(2);
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 4, 'SECTOR: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(50, 4, $CodSector . '    ' . utf8_decode($Sector), 0, 1, 'L');
    $pdf->Ln(1);
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 4, 'PROGRAMA: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(50, 4, $CodPrograma . '    ' . utf8_decode($Programa), 0, 1, 'L');
    //$pdf->Cell(205, 7, $CodSector.'    '.utf8_decode($Sector), 1, 1, 'L', 1);
    //Colores de los bordes, fondo y texto
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 6);

    $pdf->SetHeight(array(4));

    if ($trimestre == '00' or $trimestre == "01") {
        $pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
        $pdf->SetWidths(array(20, 78, 30, 30, 30, 30, 30, 30, 30, 30));
        $pdf->Row(array('PARTIDA', 'DENOMINACION', 'PRESUPUESTO INICIAL', 'MODIFICACION', 'PRESUPUESTO AJUSTADO', 'COMPROMETIDO', 'CAUSADO', 'DISPONIBILIDAD PRESUPUESTARIA', 'PAGADO', 'DISPONIBILIDAD FINANCIERA'));
        $pdf->SetAligns(array('C', 'L', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R'));
        $pdf->SetHeight(array(4));
    } else {
        $pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
        $pdf->SetWidths(array(20, 61, 30, 30, 30, 30, 30, 30, 30, 30, 30));
        $pdf->Row(array('PARTIDA', 'DENOMINACION', 'PRESUPUESTARIA ANTERIOR', 'FINANCIERA ANTERIOR', 'MODIFICACION', 'PRESUPUESTO AJUSTADO', 'COMPROMETIDO', 'CAUSADO', 'DISPONIBILIDAD PRESUPUESTARIA', 'PAGADO', 'DISPONIBILIDAD FINANCIERA'));
        $pdf->SetAligns(array('C', 'L', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R'));
        $pdf->SetHeight(array(4));
    }
    $pdf->Ln(4);
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetTextColor(0, 0, 0);
}

//    Consolidado por Categoria ONAPRE...
function consolidado_categoria_onapre($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $fdesde, $fhasta, $trimestre)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    $pdf->Ln(8);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(270, 6, utf8_decode('Consolidado por Programa'), 0, 1, 'C');
    $pdf->Cell(270, 6, 'Desde: ' . $fdesde . ' Hasta:' . $fhasta, 0, 1, 'C');
    if ($trimestre == "00") {
        $pdf->Cell(270, 6, '', 0, 1, 'C');
    } elseif ($trimestre == "01") {
        $pdf->Cell(270, 6, 'TRIMESTRE I', 0, 1, 'C');
    } elseif ($trimestre == "02") {
        $pdf->Cell(270, 6, 'TRIMESTRE II', 0, 1, 'C');
    } elseif ($trimestre == "03") {
        $pdf->Cell(270, 6, 'TRIMESTRE III', 0, 1, 'C');
    } elseif ($trimestre == "04") {
        $pdf->Cell(270, 6, 'TRIMESTRE IV', 0, 1, 'C');
    }
    /////////////////////////////
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 4, 'Fuente de Financiamiento: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->Cell(50, 4, $nom_fuente_financiamiento, 0, 1, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 4, 'Tipo de Presupuesto: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->Cell(50, 4, $nom_tipo_presupuesto, 0, 1, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 4, utf8_decode('Año Fiscal: '), 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->Cell(50, 4, $anio_fiscal, 0, 1, 'L');

    //Colores de los bordes, fondo y texto
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 6);

    $pdf->SetHeight(array(4));

    if ($trimestre == '00' or $trimestre == "01") {
        $pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
        $pdf->SetWidths(array(20, 78, 30, 30, 30, 30, 30, 30, 30, 30));
        $pdf->Row(array('PARTIDA', 'DENOMINACION', 'PRESUPUESTO INICIAL', 'MODIFICACION', 'PRESUPUESTO AJUSTADO', 'COMPROMETIDO', 'CAUSADO', 'DISPONIBILIDAD PRESUPUESTARIA', 'PAGADO', 'DISPONIBILIDAD FINANCIERA'));
        $pdf->SetAligns(array('C', 'L', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R'));
        $pdf->SetHeight(array(4));
    } else {
        $pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
        $pdf->SetWidths(array(20, 61, 30, 30, 30, 30, 30, 30, 30, 30, 30));
        $pdf->Row(array('PARTIDA', 'DENOMINACION', 'PRESUPUESTARIA ANTERIOR', 'FINANCIERA ANTERIOR', 'MODIFICACION', 'PRESUPUESTO AJUSTADO', 'COMPROMETIDO', 'CAUSADO', 'DISPONIBILIDAD PRESUPUESTARIA', 'PAGADO', 'DISPONIBILIDAD FINANCIERA'));
        $pdf->SetAligns(array('C', 'L', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R'));
        $pdf->SetHeight(array(4));
    }
    $pdf->Ln(4);
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetTextColor(0, 0, 0);
}

//    Consolidado General ONAPRE...
function consolidado_general_onapre($pdf, $anio_fiscal, $nom_fuente_financiamiento, $nom_tipo_presupuesto, $trimestre, $fdesde, $fhasta)
{
    //    NUEVA PAGINA CABECERA
    $pdf->AddPage();
    //    --------------------
    setLogo($pdf);
    $pdf->Ln(8);
    //    --------------------
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(270, 6, utf8_decode('Consolidado General'), 0, 1, 'C');
    $pdf->Cell(270, 6, 'Desde: ' . $fdesde . ' Hasta:' . $fhasta, 0, 1, 'C');
    if ($trimestre == "00") {
        $pdf->Cell(270, 6, '', 0, 1, 'C');
    } elseif ($trimestre == "01") {
        $pdf->Cell(270, 6, 'TRIMESTRE I', 0, 1, 'C');
    } elseif ($trimestre == "02") {
        $pdf->Cell(270, 6, 'TRIMESTRE II', 0, 1, 'C');
    } elseif ($trimestre == "03") {
        $pdf->Cell(270, 6, 'TRIMESTRE III', 0, 1, 'C');
    } elseif ($trimestre == "04") {
        $pdf->Cell(270, 6, 'TRIMESTRE IV', 0, 1, 'C');
    }
    /////////////////////////////
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 4, 'Fuente de Financiamiento: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->Cell(50, 4, $nom_fuente_financiamiento, 0, 1, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 4, 'Tipo de Presupuesto: ', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->Cell(50, 4, $nom_tipo_presupuesto, 0, 1, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(35, 4, utf8_decode('Año Fiscal: '), 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->Cell(50, 4, $anio_fiscal, 0, 1, 'L');

    //Colores de los bordes, fondo y texto
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 6);

    $pdf->SetHeight(array(4));

    if ($trimestre == "00" or $trimestre == "01") {
        $pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
        $pdf->SetWidths(array(20, 78, 30, 30, 30, 30, 30, 30, 30, 30));
        $pdf->Row(array('PARTIDA', 'DENOMINACION', 'PRESUPUESTO INICIAL', 'MODIFICACION', 'PRESUPUESTO AJUSTADO', 'COMPROMETIDO', 'CAUSADO', 'DISPONIBILIDAD PRESUPUESTARIA', 'PAGADO', 'DISPONIBILIDAD FINANCIERA'));
        $pdf->SetAligns(array('C', 'L', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R'));
        $pdf->SetHeight(array(4));
    } else {
        $pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
        $pdf->SetWidths(array(20, 61, 30, 30, 30, 30, 30, 30, 30, 30, 30));
        $pdf->Row(array('PARTIDA', 'DENOMINACION', 'PRESUPUESTARIA ANTERIOR', 'FINANCIERA ANTERIOR', 'MODIFICACION', 'PRESUPUESTO AJUSTADO', 'COMPROMETIDO', 'CAUSADO', 'DISPONIBILIDAD PRESUPUESTARIA', 'PAGADO', 'DISPONIBILIDAD FINANCIERA'));
        $pdf->SetAligns(array('C', 'L', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R'));
        $pdf->SetHeight(array(4));
    }
    $pdf->Ln(4);
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetTextColor(0, 0, 0);
}

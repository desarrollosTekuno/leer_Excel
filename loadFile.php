<?php
ini_set('max_execution_time', '300');
require_once 'php/ips.php';
require_once 'php/conexion.php';
require_once 'php/functions.php';
require __DIR__ . "/vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\IOFactory;

$no_proyecto = $_POST['no_proyecto'];
$file = $_FILES["adjunto"]["tmp_name"];
//echo $file;
//$rutaArchivo = "Prueba.xlsx";
$spreadsheet = IOFactory::load($file);
$totalDeHojas = $spreadsheet->getSheetCount();
$sheetNames = $spreadsheet->getSheetNames();
//var_dump($sheetNames);

$creados = 0;
$encontrados = 0;
$no_encontrados = 0;
$errores = 0;
foreach ($sheetNames as /*$indice => */ $sheetName) {
    $procesar =  proccessFile($conn, $sheetName, $no_proyecto);
    switch ($procesar) {
        case 'Registro encontrado':
            $encontrados++;
            break;
        case 'Registro creado':
            $creados++;
            break;
        case 'Error insert':
            $errores++;
            break;
        case 'No existe el documento':
            $no_encontrados++;
            break;
    }
    //echo trim($sheetName).'<br>';
}

$output = array(
    'creados' => $creados,
    'encontrados' => $encontrados,
    'no_encontrados' => $no_encontrados,
    'errores' => $errores,
    'lista_checklist' => checklistDocumentos($conn, $no_proyecto)
);
echo json_encode($output);
//echo $file;
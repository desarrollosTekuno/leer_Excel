<?php
function explodeSheetName($sheetName)
{
    $sheetName = trim($sheetName);
    if (strpos($sheetName, "-")) {
        $posiciones = explode("-", $sheetName);
        $numberOfDocument = $posiciones[0];
        return $numberOfDocument;
    }
}

function documentExists($conn, $numberOfDocument)
{
    try {
        $sql = "SELECT*FROM HAP_OPDOCUMENTOS WHERE CLAVE =:clave;";
        $res = $conn->prepare($sql);
        $res->bindParam(':clave', $numberOfDocument, PDO::PARAM_STR);
        $res->execute();
        if ($res->rowCount() != 0) {
            return true;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        echo "Error :" . $e->getMessage();
    }
}

function checklistDocumentosExcel($conn)
{
    try {
        $sql = "SELECT NOMBRE_DOCUMENTO FROM HAP_OPDOCUMENTOS WHERE ORIGEN='EXCEL';";
        $res = $conn->prepare($sql);
        $res->execute();
        return $res->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error :" . $e->getMessage();
    }
}

function checklistDocumentos($conn, $no_proyecto)
{
    try {
        $sql = "SELECT hod.NOMBRE_DOCUMENTO FROM HAP_OPSTATUS hos INNER JOIN HAP_OPDOCUMENTOS hod ON hos.ID_DOCUMENTO=hod.ID WHERE hod.ORIGEN='EXCEL' AND hos.NUM_PROYECTO=:no_proyecto;";
        $res = $conn->prepare($sql);
        $res->bindParam(':no_proyecto', $no_proyecto, PDO::PARAM_STR);
        $res->execute();
        $documentos = $res->fetchAll(PDO::FETCH_ASSOC);
        $arrayDocumentos= array();
        foreach($documentos as $documento){
            array_push($arrayDocumentos,$documento['NOMBRE_DOCUMENTO']);
        }
        $html = '';
        $documentosExcel = checklistDocumentosExcel($conn);

        foreach ($documentosExcel as $documento) {
            if (in_array($documento['NOMBRE_DOCUMENTO'], $arrayDocumentos)){
                $html .= '<li><span><i class="fa-solid fa-circle-check text-success"></i> ' . $documento['NOMBRE_DOCUMENTO'] . '</li></span></li>';
            }else{
                $html .= '<li><span><i class="fa-solid fa-circle-xmark text-danger"></i></i> ' . $documento['NOMBRE_DOCUMENTO'] . '</li></span></li>';
            }
        }
        return $html;
    } catch (PDOException $e) {
        echo "Error :" . $e->getMessage();
    }
}



function documentExistsInProject($conn, $documentID, $numberOfProject)
{
    try {
        $sql = "SELECT ID FROM HAP_OPSTATUS WHERE ID_DOCUMENTO =:id_documento AND NUM_PROYECTO=:num_proyecto;";
        $res = $conn->prepare($sql);
        $res->bindParam(':id_documento', $documentID, PDO::PARAM_STR);
        $res->bindParam(':num_proyecto', $numberOfProject, PDO::PARAM_STR);
        $res->execute();
        if ($res->rowCount() != 0) {
            return true;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        echo "Error :" . $e->getMessage();
    }
}

function getDocumentID($conn, $claveDocument)
{
    try {
        $sql = "SELECT ID FROM HAP_OPDOCUMENTOS WHERE CLAVE=:clave;";
        $res = $conn->prepare($sql);
        $res->bindParam(':clave', $claveDocument, PDO::PARAM_STR);
        $res->execute();
        $row = $res->fetch(PDO::FETCH_NUM);
        $idDocument = $row[0];
        return $idDocument;
    } catch (PDOException $e) {
        echo "Error :" . $e->getMessage();
    }
}

function createRegistro($conn, $fecha, $id_document, $proyecto, $estatus)
{
    try {
        $sql = "INSERT INTO HAP_OPSTATUS(FECHA,ID_DOCUMENTO,NUM_PROYECTO,STATUS) ";
        $sql .= "VALUES(:FECHA,:ID_DOCUMENTO,:NUM_PROYECTO,:STATUS);";
        $res = $conn->prepare($sql);
        $res->bindParam(':FECHA', $fecha, PDO::PARAM_STR);
        $res->bindParam(':ID_DOCUMENTO', $id_document, PDO::PARAM_INT);
        $res->bindParam(':NUM_PROYECTO', $proyecto, PDO::PARAM_STR);
        $res->bindParam(':STATUS', $estatus, PDO::PARAM_STR);
        return $res->execute();
    } catch (PDOException $e) {
        echo "Error :" . $e->getMessage();
    }
}

function proccessFile($conn, $sheetName, $numberOfProject)
{
    date_default_timezone_set('America/Mexico_City');
    $fecha = date('Y-m-d');
    $numberOfDocument = explodeSheetName($sheetName);
    if (is_numeric($numberOfDocument)) {
        if (documentExists($conn, $numberOfDocument) == true) {
            $documentID = getDocumentID($conn, $numberOfDocument);
            if (documentExistsInProject($conn, $documentID, $numberOfProject)) {
                return 'Registro encontrado';
            } else {
                if (createRegistro($conn, $fecha, $documentID, $numberOfProject, "ENTREGADO")) {
                    return 'Registro creado';
                } else {
                    return 'Error insert';
                }
            }
        } else {
            return 'No existe el documento';
        }
    }
}

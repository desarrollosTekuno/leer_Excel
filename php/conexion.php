<?php
//// Arhivo de conexion a la base de datos
//$usuario = "root";
$usuario = "admindb";
//$contrasena = "$3rv3r";
$contrasena = "Lfc#4yt0.2022";
try {
    $conn = new PDO("sqlsrv:Server=VM-LFC-SCU-BASE;Database=BDS_Pruebas", $usuario, $contrasena, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::SQLSRV_ATTR_ENCODING => PDO::SQLSRV_ENCODING_UTF8));
    //$conn = new PDO("sqlsrv:Server=LAPTOP-61EU4TK0\SA;Database=DBS_Prueba", $usuario, $contrasena, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::SQLSRV_ATTR_ENCODING => PDO::SQLSRV_ENCODING_UTF8));
    //$conn->exec("set names utf8");
    return $conn;
} catch (PDOException $e) {
    echo "Error :" . $e->getMessage();
}

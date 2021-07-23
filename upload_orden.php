<?php
//session_start();
//require_once('app/controller/wms.controller.php');
//$controller = new wms_controller;
//
//$target_dir = "C:\\xampp\\htdocs\\Cargas Ordenes\\";
//if(!file_exists($target_dir)){
//    mkdir($target_dir);
//}
$target_file = $target_dir.basename($_FILES["fileToUpload"]["name"]);
$uploadOk =0;
$fileType = pathinfo($target_file,PATHINFO_EXTENSION);
if ($_FILES["fileToUpload"]["size"] > ((1024*1024)*20)) {
    echo "El archivo dede medir menos de 20 MB.";
}else{
    //file_exists($target_file) or
    if(file_exists($target_file)){
        echo 'El Archivo ya existe';
        die();
    }
    if ( file_exists($target_file) or (strtoupper($fileType) != ("XLS") and strtoupper($fileType) != ("XLSX") and strtoupper($fileType) != ("CSV") and strtoupper($fileType) != ("TXT"))){
        echo "El Archivo ".$target_file." que intenta cargar, ya existen en el Sistema, se intenta subir un duplicado <p>";
        echo "o el archivo no es valido; solo se pueden subir arvhivos xls o csv. <p>".strtoupper($fileType);
        $tipo = 'duplicado';
        //$registro = $controller->guardaComprobante($target_file, $cotizacion, $mes, $anio, $dia, $tipo, $cl, $iddoc);
    }else{
        if(move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "El Archivo: ". basename( $_FILES["fileToUpload"]["name"]). " se ha cargado.<p>";
            $tipo = 'ok';
            $res=$controller->saveOrder($target_file, basename($_FILES["fileToUpload"]["name"]));
        } else {
            echo "Ocurrio un problema al subir su archivo, favor de revisarlo.";
        }
            echo 'Archivo: '.$target_file;
    }
}
?>
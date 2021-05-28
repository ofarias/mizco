<?php
session_start();
require_once('app/controller/wms.controller.php');
$controller = new wms_controller;
$target_dir = "C:\\xampp\\htdocs\\Cargas Ordenes\\";
if(!file_exists($target_dir)){
    mkdir($target_dir);
}
$ido=$_POST['ido'];
$mot=$_POST['mot'];
$target_file = $target_dir.basename($_FILES["fileToUpload"]["name"]);
$fileName = $target_dir.pathinfo($target_file, PATHINFO_FILENAME);
$fileType = pathinfo($target_file,PATHINFO_EXTENSION);

if ($_FILES["fileToUpload"]["size"] > ((1024*1024)*20)) {
    echo "El archivo dede medir menos de 20 MB.";
}else{
    if(file_exists($target_file)){
        rename($target_file, $fileName.'_'.date("d-m-Y H_i_s").'_'.$_SESSION['user']->NOMBRE.'.'.'_'.$ido.'.'.$fileType);
    }
    if ((strtoupper($fileType) != ("XLS") and strtoupper($fileType) != ("XLSX") and strtoupper($fileType) != ("CSV") and strtoupper($fileType) != ("TXT"))){
        echo "El Archivo ".$target_file." que intenta cargar, ya existen en el Sistema, se intenta subir un duplicado <p>";
        echo "o el archivo no es valido; solo se pueden subir arvhivos xls o csv. <p>".strtoupper($fileType);
    }else{   
        if(move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $res=$controller->chgFile($target_file, basename($_FILES["fileToUpload"]["name"]), $ido, $mot);
            //$controller->limpiaForm($param='wms_menu&opc=o');
        } else {
            echo "Ocurrio un problema al subir su archivo, favor de revisarlo.";
        }
            echo 'Archivo: '.$target_file;
    }
}
?>
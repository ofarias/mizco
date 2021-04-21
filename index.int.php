<?php
session_start();
date_default_timezone_set('America/Mexico_City');
require_once('app/controller/pegaso.controller.php');
require_once('app/controller/sql.controller.php');

$controller = new sql_controller;
if(isset($_GET['action'])){
$action = $_GET['action'];
}else{
	$action = '';
}
if(isset($_POST['UPLOAD_META_DATA'])){
	$files2upload = $_POST['files2upload'];
	$controller->cargaSQL($files2upload);
}elseif (isset($_POST['xmlExcel'])){
	$res=$controller->xmlExcel($_POST['mes'], $_POST['anio'], $_POST['ide'], $_POST['doc'], $_POST['t']);
	echo json_encode($res);
	exit();
}
else{
	switch ($_GET['action']){
	case 'login':
		$controller->Login();
		break;
	case 'cargaMetaDatos':
		$controller->cargaMetaDatos();
		break;
	default: 
		header('Location: index.php?action=login');
		break;
	}

}
?>
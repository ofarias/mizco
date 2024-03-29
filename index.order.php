<?php
session_start();
date_default_timezone_set('America/Mexico_City');
require_once('app/controller/pegaso.controller.php');
require_once('app/controller/sql.controller.php');
require_once('app/controller/wms.controller.php');
require_once('app/controller/order.controller.php');

$controller = new pegaso_controller;
$controller_int = new sql_controller;
$controller_wms = new wms_controller;
$controller_ord = new order_controller;
/*
if (isset($_GET['action'])) {
    $action = $_GET['action'];
} else {
    $action = '';
}*/
if (isset($_POST['enviarA'])) {
    $res=$controller_int->enviarA($_POST['enviarA']); echo json_encode($res); exit(); 
}elseif(isset($_POST['asigDet'])) {
    $res=$controller_ord->asigDet($_POST['asigDet'], $_POST['det'], $_POST['cte'],$_POST['comp']); echo json_encode($res); exit();
}elseif (isset($_POST['valInt'])) {
    $res=$controller_ord->valInt($_POST['valInt']); echo json_encode($res); exit();
}elseif (isset($_POST['articulos'])) {
    $res=$controller_ord->articulos($_POST['articulos']); echo json_encode($res); exit();
}elseif (isset($_POST['chgSta'])) {
    $res=$controller_ord->chgSta($_POST['file'], $_POST['chgSta']); echo json_encode($res); exit();
}elseif(isset($_POST['revPedido'])){
    $res=$controller_ord->revPedido($_POST['revPedido'], $i=0); echo json_encode($res);exit();
}
else{
    switch ($_GET['action']) {
        case 'login':
            $controller->Login();
            break;
        case 'ordenesW':
            $controller_ord->ordenesW($_GET['tipo'], $_GET['param']);
            break;
        default:
            header('Location: index.php?action=login');
            break;
    }
}
?>
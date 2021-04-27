<?php
session_start();
date_default_timezone_set('America/Mexico_City');
//session_cache_limiter('private_no_expire');
require_once('app/controller/wms.controller.php');
$controller_wms = new wms_controller;
if (isset($_GET['action'])) {
    $action = $_GET['action'];
} else {
    $action = '';
}
if (isset($_POST['usuario'])) {
    $controller->InsertaUsuarioN($_POST['usuario'], $_POST['contrasena'], $_POST['email'], $_POST['rol'], $_POST['letra']);
}elseif(isset($_POST['actProd'])) {
    $res=$controller_wms->actProd($_POST['actProd'], $_POST['lg'], $_POST['an'], $_POST['al'], $_POST['p'], $_POST['uo']);echo json_encode($res);exit();
}elseif(isset($_POST['addComp'])){
    $res=$controller_wms->addComp( $_POST['et'], $_POST['desc'], $_POST['selT'], $_POST['lg'], $_POST['an'], $_POST['al'], $_POST['alm'], $_POST['ob'],$_POST['fact']);echo json_encode($res);exit();
}elseif(isset($_POST['cpComp'])){
    $res=$controller_wms->cpComp($_POST['cns'], $_POST['can'], $_POST['id'], $_POST['ser'], $_POST['fol'], $_POST['sep']);echo json_encode($res);exit();
}elseif (isset($_POST['addMov'])) {
    $res=$controller_wms->addMov($_POST['tipo'], $_POST['alm'],$_POST['compP'],$_POST['compS'],$_POST['prod'],$_POST['uni'],$_POST['cant'],$_POST['col'],$_POST['mov'], $_POST['pza']);echo json_encode($res);exit();
}elseif (isset($_POST['delMov'])) {
    $res=$controller_wms->delMov($_POST['delMov'], $_POST['tp']); echo json_encode($res);exit();
}elseif (isset($_POST['asocia'])) {
    $res=$controller_wms->asocia($_POST['asocia'], $_POST['cp'], $_POST['t'], $_POST['e']); echo json_encode($res);exit();
}elseif (isset($_POST['cpLin'])){
    $res=$controller_wms->cpLin($_POST['base'], $_POST['cs']); echo json_encode($res);exit();
}elseif (isset($_POST['canMov'])) {
    $res=$controller_wms->canMov($_POST['mov'], $_POST['mot'], $_POST['t']);echo json_encode($res);exit();
}elseif (isset($_POST['xlsComp'])){
    $res=$controller_wms->wms_comp($_POST['op']='',$_POST['param']);echo json_encode($res);exit();
}elseif(isset($_GET['term']) && isset($_GET['producto'])){
        $buscar = $_GET['term'];
        $prods = $controller_wms->prodAuto($buscar);
        echo json_encode($prods);
        exit;
}elseif(isset($_GET['term']) && isset($_GET['componente'])){
        $buscar = $_GET['term'];
        $prods = $controller_wms->compAuto($buscar);
        echo json_encode($prods);
        exit;
}else{
    switch ($_GET['action']) {
        case 'login':
            $controller->Login();
            break;
        case 'wms_menu':
            $opc=isset($_GET['opc'])? $_GET['opc']:'';
            $controller_wms->wms_menu($opc);
            break;
        default:
            header('Location: index.php?action=login');
            break;
    }
}
?>
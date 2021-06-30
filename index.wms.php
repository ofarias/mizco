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
}elseif (isset($_POST['valProd'])){
    $res=$controller_wms->valProd($_POST['prod']);echo json_encode($res);exit();
}elseif (isset($_POST['report'])){
    $res=$controller_wms->report($_POST['t'],$_POST['out']);echo json_encode($res);exit();
}elseif (isset($_POST['delComp'])){
    $res=$controller_wms->delComp($_POST['id'], $_POST['t']);echo json_encode($res);exit();
}elseif (isset($_POST['envMail'])){
    $res=$controller_wms->envMail($_POST['dir'],  $_POST['msg'], $_POST['files'], $_POST['ids']);echo json_encode($res);exit();
}elseif (isset($_POST['upload_ordenes'])){
    $res=$controller_wms->cargaOrdenes($_POST['files2upload']);echo json_encode($res);exit();
}elseif (isset($_POST['asgProd'])){
    $res=$controller_wms->asgProd($_POST['asgProd'], $_POST['prod'], $_POST['pza'], $_POST['t'], $_POST['c'], $_POST['s']);echo json_encode($res);exit();
}elseif (isset($_POST['detLinOrd'])){
    $res=$controller_wms->detLinOrd($_POST['ord'], $_POST['prod']);echo json_encode($res);exit();
}elseif (isset($_POST['actProOrd'])){
    $res=$controller_wms->actProOrd($_POST['prod'], $_POST['oc'], $_POST['prodn']);echo json_encode($res);exit();
}elseif (isset($_POST['asgLn'])){
    $res=$controller_wms->asgLn($_POST['ln'], $_POST['c']);echo json_encode($res);exit();
}elseif (isset($_POST['chgProd'])){
    $res=$controller_wms->chgProd($_POST['p'], $_POST['nP'], $_POST['oc'], $_POST['t']);echo json_encode($res);exit();
}elseif (isset($_POST['asigCol'])){
    $res=$controller_wms->asigCol($_POST['nP'], $_POST['ln'], $_POST['col']);echo json_encode($res);exit();
}elseif (isset($_POST['finA'])){
    $res=$controller_wms->finA($_POST['p'], $_POST['ord'], $_POST['t']);echo json_encode($res);exit();
}elseif (isset($_POST['delOc'])){
    $res=$controller_wms->delOc($_POST['id']);echo json_encode($res);exit();
}elseif (isset($_POST['log'])){
    $res=$controller_wms->log($_POST['log'], $_POST['ido'], $_POST['d']);echo json_encode($res);exit();
}elseif (isset($_POST['chgComp'])){
    $res=$controller_wms->chgComp($_POST['idc'], $_POST['d'], $_POST['t']);echo json_encode($res);exit();
}elseif (isset($_POST['comPro'])){
    $res=$controller_wms->comPro($_POST['comPro'], $_POST['ordd']);echo json_encode($res);exit();
}elseif (isset($_POST['surte'])){
    $res=$controller_wms->surte($_POST['surte'], $_POST['ordd'], $_POST['comps']);echo json_encode($res);exit();
}elseif (isset($_POST['reasig'])){
    $res=$controller_wms->reasig($_POST['reasig'], $_POST['compp'], $_POST['comps'], $_POST['it']);echo json_encode($res);exit();
}elseif (isset($_POST['ingMap'])){
    $res=$controller_wms->ingMap($_POST['ingMap'], $_POST['prod'], $_POST['uni'], $_POST['cant'], $_POST['pzas'], $_POST['ft'], $_POST['t']);echo json_encode($res);exit();
}elseif (isset($_POST['dispLin'])){
    $res=$controller_wms->dispLin($_POST['dispLin']);echo json_encode($res);exit();
}elseif (isset($_POST['prods'])){
    $res=$controller_wms->prods($_POST['prods']);echo json_encode($res);exit();
}elseif(isset($_POST['reuMap'])){
    $res=$controller_wms->reuMap($_POST['reuMap'], $_POST['opc']);echo json_encode($res);exit();
}elseif(isset($_POST['usoComp'])){
    $res=$controller_wms->usoComp($_POST['usoComp'], $_POST['opc']);echo json_encode($res);exit();
}elseif(isset($_POST['asiSurt'])){
    $res=$controller_wms->asiSurt($_POST['asiSurt'], $_POST['cedis'], $_POST['nombre']);echo json_encode($res);exit();
}elseif(isset($_POST['finSurt'])){
    $res=$controller_wms->finSurt($_POST['finSurt'], $_POST['cedis']);echo json_encode($res);exit();
}elseif(isset($_POST['exeSal'])){
    $res=$controller_wms->exeSal($_POST['exeSal'], $_POST['fol']);echo json_encode($res);exit();
}elseif(isset($_POST['finSal'])){
    $res=$controller_wms->finSal($_POST['finSal']);echo json_encode($res);exit();
}else{
    switch ($_GET['action']) {
        case 'login':
            $controller->Login();
            break;
        case 'wms_menu':
            $opc=isset($_GET['opc'])? $_GET['opc']:'';
            $controller_wms->wms_menu($opc);
            break;
        case 'detOrden':
            $param= isset($_GET['param'])? $_GET['param']:'';
            $controller_wms->detOrden($_GET['orden'], $_GET['t'], $param, 'p');
            break;
        case 'impOrden':
            $param= isset($_GET['param'])? $_GET['param']:'';
            $controller_wms->detOrden($_GET['orden'], $_GET['t'], $param, 'i');
            break;
        case 'mapa':
            $controller_wms->mapa($_GET['opc'], $_GET['param']);
            break;
        default:
            header('Location: index.php?action=login');
            break;
    }
}
?>
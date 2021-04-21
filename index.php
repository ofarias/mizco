<?php
session_start();
//echo $_SESSION["unimed"];
date_default_timezone_set('America/Mexico_City');
//session_cache_limiter('private_no_expire');
require_once('app/controller/pegaso.controller.php');
require_once('app/controller/sql.controller.php');
require_once('app/controller/wms.controller.php');
$controller = new pegaso_controller;
$controller_int = new sql_controller;
$controller_wms = new wms_controller;
//echo $_POST['nombre'];
//echo $_POST['actualizausr'];
if (isset($_GET['action'])) {
    $action = $_GET['action'];
} else {
    $action = '';
}
if (isset($_POST['usuario'])) {
    //$usuario = $_SESSION['user'];
    $controller->InsertaUsuarioN($_POST['usuario'], $_POST['contrasena'], $_POST['email'], $_POST['rol'], $_POST['letra']);
} elseif (isset($_POST['faltaunidades'])) {
    $numero = $_POST['numero'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $placas = $_POST['placas'];
    $operador = $_POST['operador'];
    $tipo = $_POST['tipo'];
    $tipo2 = $_POST['tipo2'];
    $coordinador = $_POST['coordinador'];
    $controller->AltaUnidadesF($numero, $marca, $modelo, $placas, $operador, $tipo, $tipo2, $coordinador);
} elseif (isset($_POST['actualizaUnidades'])) {
    $numero = $_POST['numero'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $placas = $_POST['placas'];
    $operador = $_POST['operador'];
    $tipo = $_POST['tipo'];
    $tipo2 = $_POST['tipo2'];
    $coordinador = $_POST['coordinador'];
    $idu = $_POST['idu'];
    $controller->ActualizaUnidades($numero, $marca, $modelo, $placas, $operador, $tipo, $tipo2, $coordinador, $idu);
} elseif (isset($_POST['user']) && isset($_POST['contra'])) {
    //echo 'aqui estamos en el if';
    $controller->LoginA(strtolower($_POST['user']), $_POST['contra']);
} elseif (isset($_POST['actualizausr'])) {
    $controller->actualiza($_POST['mail'], $_POST['usuario1'], $_POST['contrasena1'], $_POST['email1'], $_POST['rol1'], $_POST['estatus']);
} elseif ($action == 'modifica') {
    $controller->ModificaU($_GET['e']);
} elseif (isset($_POST['ccomp'])) {
    $controller->InsertaCcomp($_POST['nombre'], $_POST['duracion'], $_POST['tipo']);
} elseif (isset($_POST['nombreflujo'])) {
    $componentes = $_POST["id"];
    $nombre = $_POST['nombreflujo'];
    $desc = $_POST['desc'];
    $controller->AsignaComp($componentes, $nombre, $desc);
} elseif (isset($_POST['INSRTORCOM'])) {
    if (!empty($_POST['seleccion'])) {
        $consecutivo2 = 0001;
        $proveedorPrevio = '';
        foreach ($_POST['seleccion'] as $check) {
            $TIME = time();
            $HOY = date("Y-m-d H:i:s", $TIME);
            //$HOY        = date("Y-m-d", $TIME);
            list($PROVEEDOR, $CVE_DOC, $TOTAL, $IdPreoco, $Consecutivo, $Doc, $Prod, $Costo, $Cantidad, $Rest) = explode("|", $check);
            //verificaCveArt
            $controller->verificaArticulo($Prod);
            $unimed = $_SESSION["unimed"];
            $facconv = $_SESSION["facconv"];
            $partida = array($PROVEEDOR, $CVE_DOC, $TOTAL, $Doc, $TIME, $HOY, $IdPreoco, $Rest, $Prod, $Cantidad, $Costo, $unimed, $facconv);
            $partidas[] = $partida;
            $consecutivo2 = $consecutivo2 + 1;
        }
        $controller->OrdCompAlt($partidas);
    }
} elseif (isset($_POST['altaunidadf'])) {
    $numero = $_POST['numero'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $placas = $_POST['placas'];
    $operador = $_POST['operador'];
    $controller->altaunidadesdata($numero, $marca, $modelo, $placas, $operador);
} elseif (isset($_POST['asignaruta'])) {
    $docu = $_POST['docu'];
    $unidad = $_POST['unidad'];
    $edo = $_POST['edo'];
    $controller->AsignaRuta($docu, $unidad, $edo);
} elseif (isset($_POST['asignacomposconfact'])) {
    $factura = $_POST["factura"];
    $compo = $_POST["compo"];

    for ($i = 0; $i < count($factura); $i++) {
        $fact = $factura[$i];
    }
    for ($i = 0; $i < count($compo); $i++) {
        $comp = $compo[$i];
    }
    $controller->AsignaAFactf($fact, $comp);
} elseif (isset($_POST['FORM_NAME'])) {
    $documento = $_POST['documento'];
//	$importe = $_POST['importe'];
//	$proveedor = $_POST['proveedor'];
//	$claveProveedor = $_POST['claveProveedor'];
//	$fecha = $_POST['fecha'];
    $controller->realizaPago($documento);
} elseif (isset($_POST['formpago'])) {
    $cuentaban = $_POST['cuentabanco'];
    $docu = $_POST['docu'];
    $importe = $_POST['importe'];
    $tipop = $_POST['tipopago'];
    $monto = $_POST['monto'];
    $nomprov = $_POST['nomprov'];
    $cveclpv = $_POST['cveprov'];
    $fechadoc = $_POST['fechadoc'];
    //$entregadoa = $_POST['entregadoa'];
    //if($monto !== $importe){
    //	$controller->PagoW();
    //}else{
    $controller->PagoCorrecto($cuentaban, $docu, $tipop, $monto, $nomprov, $cveclpv, $fechadoc);
    //}
} elseif (isset($_POST['formpago_gasto'])) {
    $cuentabanco = $_POST['cuentabanco'];
    $documento = $_POST['documento'];
    $importe = $_POST['importe'];
    $tipopago = $_POST['tipopago'];
    $monto = $_POST['monto'];
    $proveedor = $_POST['proveedor'];
    $claveProveedor = $_POST['claveProveedor'];
    $fechadocumento = $_POST['fechadocumento'];
    $controller->PagoGastoCorrecto($cuentabanco, $documento, $tipopago, $monto, $proveedor, $claveProveedor, $fechadocumento);
} elseif (isset($_POST['fomrpago_old'])) {
    $docuOLD = $_POST['docuold'];
    $importeOLD = $_POST['importeold'];
    $tipopOLD = $_POST['tipopagoold'];
    $montoOLD = $_POST['montoold'];
    $nomprovOLD = $_POST['nomprovold'];
    $cveclpvOLD = $_POST['cveprovold'];

    $controller->PagoCorrectoOld($docuOLD, $tipopOLD, $montoOLD, $nomprovOLD, $cveclpvOLD);
} elseif (isset($_POST['ped'])) {
    $ped = $_POST['ped'];
    $controller->MuestraPedidos($ped);
} elseif (isset($_POST['asignaSecuencia'])) {
    $docu = $_POST['doc'];
    $secu = $_POST['secuencia'];
    $unidad = $_POST['uni'];
    $fechai = $_POST['fechai'];
    $fechaf = $_POST['fechaf'];

    $controller->asignaSec($docu, $secu, $unidad, $fechai, $fechaf);
} elseif (isset($_POST['SecUnidad2'])) {
    $prove = $_POST['prov'];
    $secuencia = $_POST['secuencia'];
    $uni = $_POST['uni'];
    $fecha = $_POST['fecha'];
    $idu = $_POST['idu'];
    $controller->SecuenciaUnidad($prove, $secuencia, $uni, $fecha, $idu);
} elseif (isset($_POST['defRuta'])) {
    $doc = $_POST['doc'];
    $secuencia = $_POST['secuencia'];
    $uni = $_POST['uni'];
    $tipo = $_POST['tipo'];
    $idu = $_POST['idu'];
    $controller->DefRuta($doc, $secuencia, $uni, $tipo, $idu);
} elseif (isset($_POST['defRutaForaneo'])) {
    $doc = $_POST['doc'];
    $charnodeseados = array("-", "/");
    //$uni=$_POST['uni'];
    //$tipo=$_POST['tipo'];
    $idu = $_POST['idu'];

    $guia = $_POST['guia'];
    $fletera = $_POST['fletera'];
    $cpdestino = $_POST['cpdestino'];
    $destino = $_POST['destino'];
    $fechaestimada = str_replace($charnodeseados, '.', $_POST['fechaestimada']);

    $controller->DefRutaForaneo($doc, $idu, $guia, $fletera, $cpdestino, $destino, $fechaestimada);
} elseif (isset($_POST['finalizaRuta'])) {
    $doc = $_POST['doc'];
    $secuencia = $_POST['secuencia'];
    $uni = $_POST['uni'];
    $motivo = $_POST['motivo'];
    $idf = $_POST['idf'];
    $controller->FinalizaRuta($idf, $secuencia, $uni, $motivo, $doc);
} elseif (isset($_POST['finalizaReEnRuta'])) {
    $doc = $_POST['doc'];
    $idf = $_POST['idu'];
    $motivo = $_POST['motivo'];
    $controller->FinalizaReEnRuta($idf, $motivo, $doc); ///////////
} elseif (isset($_POST['defineHoraInicio'])) {
    $documento = $_POST['documento'];
    $controller->defineHoraInicio($documento);
} elseif (isset($_POST['defineHoraFin'])) {
    $documento = $_POST['documento'];
    $controller->defineHoraFin($documento);
} elseif (isset($_POST['imprimeRecepcion'])) {
    $doc = $_POST['doc'];
    $controller->imprimeRecepcion($doc);
} elseif (isset($_POST['corregirRuta'])) {  //22-03-2016 ICA
    $doc = $_POST['doc'];
    $tipo = $_POST['tipo'];
    $uni = $_POST['uni'];
    $tipoA = $_POST['tipoA'];
    $controller->CorregirRuta($doc, $tipo, $uni, $tipoA);
} elseif (isset($_POST['altaproductos'])) {
    $clave = $_POST['clave'];
    $descripcion = $_POST['descripcion'];
    $marca1 = $_POST['marca1'];
    $categoria = $_POST['categoria'];
    $desc1 = $_POST['desc1'];
    $desc2 = $_POST['desc2'];
    $desc3 = $_POST['desc3'];
    $desc4 = $_POST['desc4'];
    $desc5 = $_POST['desc5'];
    $iva = $_POST['impuesto'];
    $costo_total = $_POST['costo_total'];
    $prov1 = explode(":", $_POST['prov1']);
    $clave_prov = $prov1[0];
    $codigo_prov1 = $_POST['codigo_prov1'];
    $costo_prov1 = $_POST['costo_prov1'];
    $prov2 = $_POST['prov2'];
    $codigo_prov2 = $_POST['codigo_prov2'];
    $costo_prov2 = $_POST['costo_prov2'];
    $unidadcompra = $_POST['unidadcompra'];
    $factorcompra = $_POST['factorcompra'];
    $unidadventa = $_POST['unidadventa'];
    $factorventa = $_POST['factorventa'];
    $controller->AltaProductos($clave, $descripcion, $marca1, $categoria, $desc1, $desc2, $desc3, $desc4, $desc5, $iva, $costo_total, $clave_prov, $codigo_prov1, $costo_prov1, $prov2, $codigo_prov2, $costo_prov2, $unidadcompra, $factorcompra, $unidadventa, $factorventa);
} elseif (isset($_POST['editarproducto'])) {
    $id = $_POST['id'];
    $clave = $_POST['clave'];
    $descripcion = $_POST['descripcion'];
    $marca1 = $_POST['marca1'];
    $categoria = $_POST['categoria'];
    $desc1 = $_POST['desc1'];
    $desc2 = $_POST['desc2'];
    $desc3 = $_POST['desc3'];
    $desc4 = $_POST['desc4'];
    $desc5 = $_POST['desc5'];
    $iva = $_POST['impuesto'];
    $costo_total = $_POST['costo_total'];
    $prov1 = explode(":", $_POST['prov1']);
    $clave_prov = $prov1[0];
    $codigo_prov1 = $_POST['codigo_prov1'];
    $costo_prov1 = $_POST['costo_prov1'];
    $prov2 = $_POST['prov2'];
    $codigo_prov2 = $_POST['codigo_prov2'];
    $costo_prov2 = $_POST['costo_prov2'];
    $unidadcompra = $_POST['unidadcompra'];
    $factorcompra = $_POST['factorcompra'];
    $unidadventa = $_POST['unidadventa'];
    $factorventa = $_POST['factorventa'];
    $activo = (!empty($_POST['activo'])) ? "S" : "N";             //06/06/2016
    $controller->actualizarProducto($id, $clave, $descripcion, $marca1, $categoria, $desc1, $desc2, $desc3, $desc4, $desc5, $iva, $costo_total, $clave_prov, $codigo_prov1, $costo_prov1, $prov2, $codigo_prov2, $costo_prov2, $unidadcompra, $factorcompra, $unidadventa, $factorventa, $activo);
} elseif (isset($_POST['validar'])) {
    $docr = $_POST['docr'];
    $doco = $_POST['doco'];
    $controller->ValidaRecepcion($docr, $doco);
} elseif (isset($_POST['ValParOk'])) {
    $docr = $_POST['docr'];
    $doco = $_POST['doco'];
    $cantn = $_POST['cantn'];
    $coston = $_POST['coston'];
    $cantorig = $_POST['cantorig'];
    $costoorig = $_POST['costoorig'];
    $idpreoc = $_POST['idpreoc'];
    $idordencompra = $_POST['ordcomp'];
    $par = $_POST['par'];
    $fechadoco = $_POST['fechadoco'];
    $descripcion = $_POST['descripcion'];
    $cveart = $_POST['cveart'];
    $desc1 = $_POST['desc1'];
    $desc2 = $_POST['desc2'];
    $desc3 = $_POST['desc3'];
    $fval = $_POST['fval'];
    $controller->ValRecepOK($docr, $doco, $cantn, $coston, $cantorig, $costoorig, $idpreoc, $idordencompra, $par, $fechadoco, $descripcion, $cveart, $fval, $desc1, $desc2, $desc3);
} elseif (isset($_POST['ValParNo'])) {
    $docr = $_POST['docr'];
    $doco = $_POST['doco'];
    $cantn = $_POST['cantn'];
    $coston = $_POST['coston'];
    $cantorig = $_POST['cantorig'];
    $costoorig = $_POST['costoorig'];
    $idpreoc = $_POST['idpreoc'];
    $controller->ValRecepNo($docr, $doco, $cantn, $coston, $cantorig, $costoorig, $idpreoc);
} elseif (isset($_POST['ValRecepOK'])) {
    $docr = $_POST['docr'];
    $doco = $_POST['doco'];
    $cantn = $_POST['cantn'];
    $coston = $_POST['coston'];
    $cantorig = $_POST['cantorig'];
    $costoorig = $_POST['costoorig'];
    $idpreoc = $_POST['idpreoc'];
    $controller->valrecepok();
} elseif (isset($_GET['term']) && isset($_GET['proveedor'])) {
    $buscar = $_GET['term'];
    $nombres = $controller->TraeProveedores($buscar);
    echo json_encode($nombres);
    exit;
    //break;
} elseif (isset($_GET['term']) && isset($_GET['producto'])) {
    $buscar = $_GET['term'];
    $nombres = $controller->TraeProductos($buscar);
    echo json_encode($nombres);
    exit;
    //break;
} elseif (isset($_POST['motivonosuministra'])) {
    $id = $_POST['idorden'];
    $motivo = $_POST['motivo'];
    $controller->MotivoNS($id, $motivo);
} elseif (isset($_POST['impSadoRec'])) {
    $doco = $_POST['doco'];
    $doc = $_POST['docr'];
    $controller->ImprimirRecepcion($doco);
    //$controller->ImpSaldoRec($doc, $doco);
} elseif (isset($_POST['buscarArticulo'])) {
    $articulo = $_POST['articulo'];
    $descripcion = $_POST['descripcion'];
    $cliente = $_POST['clave'];
    $folio = $_POST['folio'];
    $partida = $_POST['partida'];
    $controller->consultarArticulo($cliente, $folio, $partida, $articulo, $descripcion);
} elseif (isset($_POST['actualizaCotizacionPartida'])) {
    $folio = $_POST['cotizacion'];
    $partida = $_POST['partida'];
    $articulo = $_POST['articulo'];
    $precio = $_POST['precio'];
    $descuento = $_POST['descuento'];
    $cantidad = $_POST['cantidad'];
    $controller->actualizaCotizacion($folio, $partida, $articulo, $precio, $descuento, $cantidad);
} elseif (isset($_POST['buscarCliente'])) {
    $clave = '';
    $cliente = '';
    if (isset($_POST['clave'])) {
        $clave = $_POST['clave'];
    }
    if (isset($_POST['cliente'])) {
        $cliente = $_POST['cliente'];
    }
    $controller->consultarClientes($clave, $cliente);
} elseif (isset($_POST['seleccionaCliente'])) {
    $cliente = $_POST['clave'];
    if (isset($_SESSION['cotizacion_mover_cliente']) && $_SESSION['cotizacion_mover_cliente'] == true) {
        $folio = $_SESSION["cotizacion_folio"];
        $_SESSION['cotizacion_mover_cliente'] = false;
        $_SESSION["cotizacion_folio"] = '';
        $controller->moverClienteCotizacion($folio, $cliente);
    } else {
        if (isset($_POST['identificadorDocumento'])) {
            $identificadorDocumento = $_POST['identificadorDocumento'];
        }
        $controller->insertaCotizacion($cliente, $identificadorDocumento);
    }
} elseif (isset($_POST['generaNuevaCotizacion'])) {
    $controller->consultarClientes('', '');
} elseif (isset($_POST['actualizaPedido'])) {
    $folio = $_POST['folio'];
    $pedido = $_POST['pedido'];
    $controller->actualizaPedidoCotizacion($folio, $pedido);
} elseif (isset($_POST['preparamaterial'])) {
    $docf = $_POST['doc'];
    $controller->VerCajas($docf);
} elseif (isset($_POST['prepararmateriales'])) {
    $docf = $_POST['clavefact'];
    $idcaja = $_POST['idcaja'];
    $controller->PreparaMaterial($docf, $idcaja);
} elseif (isset($_POST['nuevacaja'])) {
    $facturanuevacaja = $_POST['factucajanueva'];
    $controller->CrearNuevaCaja($facturanuevacaja);
} elseif (isset($_POST['modificapreorden'])) {
    $id = $_POST['id'];
    $controller->ModificaPreOrden($id);
} elseif (isset($_POST['formmodificapreorden'])) {
    $idPreorden = $_POST['idpreorden'];
    $claveNombreProd = explode(" : ", $_POST['producto']);
    $claveproducto = $claveNombreProd[0];
    $nombreproducto = $claveNombreProd[1];
    $costo = $_POST['costo'];
    $precio = $_POST['precio'];
    $marca = $_POST['marca'];
    $claveNombreProv = explode(" : ", $_POST['proveedor']);
    $claveproveedor = $claveNombreProv[0];
    $nombreproveedor = $claveNombreProv[1];
    $cotizacion = $_POST['cotizacion'];
    $partida = $_POST['partida'];
    $motivo = $_POST['motivo'];
    $controller->AlteraPedidoCotizacion($idPreorden, $claveproducto, $nombreproducto, $costo, $precio, $marca, $claveproveedor, $nombreproveedor, $cotizacion, $partida, $motivo);
} elseif (isset($_POST['formcancelapreorden'])) {
    $id = $_POST['id'];
    $controller->FormCancelaPreorden($id);
} elseif (isset($_POST['cancelapreorden'])) {
    $id = $_POST['idpreorden'];
    $cotizacion = $_POST['cotizacion'];
    $partida = $_POST['partida'];
    $motivo = $_POST['motivo'];

    $controller->CancelaPreorden($id, $cotizacion, $partida, $motivo);
} elseif (isset($_POST['AsignaEmpaque'])) {
    $docf = $_POST['docf'];
    $par = $_POST['par'];
    $canto = $_POST['canto'];
    $idpreoc = $_POST['idpreoc'];
    $cantn = $_POST['cantn'];
    $empaque = $_POST['empaque'];
    $art = $_POST['art'];
    $desc = $_POST['desc'];
    $idcaja = $_POST['idcaja'];
    $tipopaq = $_POST['tipopaq'];       //23062016
    $controller->AsignaEmpaque($docf, $par, $canto, $idpreoc, $cantn, $empaque, $art, $desc, $idcaja, $tipopaq);
} elseif (isset($_POST['embalaje'])) {
    $docf = $_POST['docf'];
    $controller->embalaje($docf);
} elseif (isset($_POST['impcontenidocaja'])) {
    $docf = $_POST['docf'];
    $caja = $_POST['caja'];
    $controller->ImpContenidoCaja($docf, $caja);
} elseif (isset($_POST['asignaembalaje'])) {       //230602016
    $docf = $_POST['docf'];
    $paquete1 = $_POST['paquete1'];
    $paquete2 = $_POST['paquete2'];
    $tipo = $_POST['tipo'];
    $peso = $_POST['peso'];
    $alto = $_POST['alto'];
    $largo = $_POST['largo'];
    $ancho = $_POST['ancho'];
    $pesovol = $_POST['pesovol'];
    $idc = $_POST['idc'];
    $idemp = $_POST['idemp'];
    $controller->asignaembalaje($docf, $paquete1, $paquete2, $tipo, $peso, $alto, $largo, $ancho, $pesovol, $idc, $idemp);
} elseif (isset($_POST['buscaoperadores']) || isset($_GET['formro'])) {
    isset($_POST['buscaoperadores']) ? $buscar = $_POST['buscar'] : $buscar = '@@@@';
    $controller->VerRegistroOperadores($buscar);
} elseif (isset($_POST['CancelarRecepcion'])) {
    $orden = $_POST['orden'];
    $recepcion = $_POST['recepcion'];
    $controller->FormCR($orden, $recepcion);
} elseif (isset($_POST['CancelRecepQuery'])) {
    $orden = $_POST['orden'];
    $recepcion = $_POST['recepcion'];
    $controller->CancelarRecepcion($orden, $recepcion);
} elseif (isset($_POST['cerrarcaja'])) {
    $idcaja = $_POST['idcaja'];
    $docf = $_POST['docf'];
    $controller->cerrarCaja($idcaja, $docf);
} elseif (isset($_POST['unidadentrega'])) {
    $idcaja = $_POST['idcaja'];
    $docf = $_POST['docf'];
    $estado = $_POST['edo'];
    $unidad = $_POST['unidad'];
    $controller->UnidadEntrega($idcaja, $docf, $estado, $unidad);
} elseif (isset($_POST['SecUnidadEntrega'])) {
    $clie = $_POST['clie'];
    $unidad = $_POST['uni'];
    $idu = $_POST['idu'];
    $secuencia = $_POST['secuencia'];
    $docf = $_POST['docf'];
    $idcaja = $_POST['idcaja'];
    $controller->SecUnidadEntrega($idu, $clie, $unidad, $secuencia, $docf, $idcaja);
} elseif (isset($_POST['AvanzarOrden'])) {     //orden AA
    $idorden = $_POST['orden'];
    $controller->FormAvanzarOrden($idorden);
} elseif (isset($_POST['avanzaroc'])) {     //orden AA
    $idorden = $_POST['idorden'];
    $idpreoc = $_POST['idpreoc'];
    $partida = $_POST['partida'];
    $controller->AvanzarOC($idorden, $idpreoc, $partida);
} elseif (isset($_POST['VerProdRFC2'])) {
    $rfc = $_POST['VerProdRFC2'];
    $controller->VerProdRFC2($rfc);
} elseif (isset($_POST['ImprimirSecuencia'])) {
    $unidad = $_POST['unidad'];
    $controller->ImprimirSecuencia($unidad);
} elseif (isset($_POST['ImprimirSecuenciaEntrega'])) {
    $unidad = $_POST['unidad'];
    $controller->ImprimirSecuenciaEnt($unidad);
} elseif (isset($_POST['ImpResultadosXDiaXunidad'])) {
    $unidad = $_POST['unidad'];
    $controller->ImpResultadosXdiaXuni($unidad);
} elseif (isset($_POST['docs'])) {
    $doc = $_POST['docu'];
    $controller->RecibeDocs($doc);
} elseif (isset($_POST['actDocs'])) {
    $doc = $_POST['doc'];
    $idr = $_POST['idu'];
    $docs = $_POST['docslog'];
    $controller->RecogeDocs($doc, $idr, $docs);
} elseif (isset($_POST['CerrarRuta'])) {
    $doc = $_POST['doc'];
    $idr = $_POST['idu'];
    $tipo = $_POST['tipo'];
    $idc = $_POST['idc'];
    $controller->CerrarRuta($doc, $idr, $tipo, $idc);
} elseif (isset($_POST['cerrargenrec'])) {
    $docs = $_POST['documentos'];
    $documentos = unserialize($docs);
    $controller->CerrarRec($documentos);
} elseif (isset($_POST['ImpRecepVal'])) {
    $orden = $_POST['docr'];
    $controller->ImprimirRecepcion($orden);
} elseif (isset($_POST['guardanuevacuenta'])) {
    $concepto = $_POST['concepto'];
    $descripcion = $_POST['descripcion'];
    $iva = $_POST['iva'];
    $cc = $_POST['centrocostos'];
    $cuenta = $_POST['cuentacontable'];
    $gasto = $_POST['gasto'];
    $presupuesto = $_POST['presupuesto'];
    @$retieneiva = $_POST['retieneiva'];
    @$retieneisr = $_POST['retieneisr'];
    @$retieneflete = $_POST['retieneflete'];

    $controller->GuardarNuevaCuenta($concepto, $descripcion, $iva, $cc, $cuenta, $gasto, $presupuesto, $retieneiva, $retieneisr, $retieneflete);
} elseif (isset($_POST['guardacambioscuenta'])) {
    /* Agregar nuevo campo GDELEON */
    $prov = $_POST['prov'];
    $concepto = $_POST['concepto'];
    $descripcion = $_POST['descripcion'];
    $iva = $_POST['iva'];
    $cc = $_POST['centrocostos'];
    $cuenta = $_POST['cuentacontable'];
    $gasto = $_POST['gasto'];
    $presupuesto = $_POST['presupuesto'];
    $id = $_POST['id'];
    $retieneiva = (!empty($_POST['retieneiva'])) ? $_POST['retieneiva'] : "0";
    $retieneisr = (!empty($_POST['retieneisr'])) ? $_POST['retieneisr'] : "0";
    $retieneflete = (!empty($_POST['retieneflete'])) ? $_POST['retieneflete'] : "0";
    $activo = (!empty($_POST['activo'])) ? $_POST['activo'] : "N";
    $cveprov = $_POST['proveedor'];

    $controller->GuardarCambiosCuenta($concepto, $descripcion, $iva, $cc, $cuenta, $gasto, $presupuesto, $id, $retieneiva, $retieneisr, $retieneflete, $activo, $cveprov);
} elseif (isset($_POST['editcuentagasto'])) {
    $id = $_POST['id'];
    $controller->EditCuentaGasto($id);
} elseif (isset($_POST['delcuentagasto'])) {
    $id = $_POST['id'];
    $controller->DelCuentaGasto($id);
} elseif (isset($_POST['liberaPendientes'])) {
    $doco = $_POST['doco'];
    $id_preoc = $_POST['id_preoc'];
    $pxr = $_POST['pxr'];
    echo $pxr;
    $controller->liberaPendientes($doco, $id_preoc, $pxr);
} elseif (isset($_POST['reEnrutar'])) {
    $doco = $_POST['doco'];
    $id_preoc = $_POST['id_preoc'];
    $pxr = $_POST['pxr'];
    $controller->reEnrutar($doco, $id_preoc, $pxr);
} elseif (isset($_POST['guardanuevogasto'])) {                ##### gastos
    $montoGasto = $_POST['monto'];
    $presupuesto = str_replace(",", "", $_POST['presupuesto']);
    $concepto = $_POST['concepto'];
    $proveedor = $_POST['proveedor'];
    $referencia = $_POST['referencia'];
    $autorizacion = ($montoGasto > $presupuesto);
    $tipopago = $_POST['tipopago'];
    $movpar = $_POST['movpar'];
    $numpar = $_POST['numpar'];
    $usuario = $_POST['usuariogastos'];
    $clasificacion = $_POST['clasificacion'];
    $charnodeseados = array("-", "/");
    $fechadoc = str_replace($charnodeseados, ".", $_POST['fechadoc']);
    $fechaven = str_replace($charnodeseados, ".", $_POST['fechaven']);
    $controller->GuardarNuevoGasto($concepto, $proveedor, $referencia, $autorizacion, $presupuesto, $tipopago, $montoGasto, $movpar, $numpar, $usuario, $fechadoc, $fechaven, $clasificacion);
} elseif (isset($_POST['editclasificaciongasto'])) {
    $id = $_POST['id'];
    $controller->EditClaGasto($id);
} elseif (isset($_POST['guardacambiosclasifgasto'])) {
    $id = $_POST['id'];
    $clasif = $_POST['clasificacion'];
    $descripcion = $_POST['descripcion'];
    $activo = (!empty($_POST['activo'])) ? $_POST['activo'] : "N";

    $controller->GuardaCambiosClasG($id, $clasif, $descripcion, $activo);
} elseif (isset($_POST['nuevaclasifgasto'])) {
    $clasif = $_POST['clasificacion'];
    $descripcion = $_POST['descripcion'];

    $controller->GuardaNuevaClaGasto($clasif, $descripcion);
} elseif (isset($_POST['guardacr'])) {
    $cr = $_POST['contra'];
    $idc = $_POST['idcaja'];
    $docf = $_POST['docf'];
    $controller->insContra($cr, $idc, $docf);
} elseif (isset($_POST['recDocFact'])) {
    $docf = $_POST['docf'];
    $docp = $_POST['docp'];
    $idcaja = $_POST['idcaja'];
    $tipo = $_POST['tipo'];
    $controller->recDocFact($docf, $docp, $idcaja, $tipo);
} elseif (isset($_POST['recDocFactNC'])) {
    $docf = $_POST['docf'];
    $docp = $_POST['docp'];
    $idcaja = $_POST['idcaja'];
    $tipo = $_POST['tipo'];
    $controller->recDocFactNC($docf, $docp, $idcaja, $tipo);
} elseif (isset($_POST['avanzaCobranza'])) {
    $docf = $_POST['docf'];
    $docp = $_POST['docp'];
    $idcaja = $_POST['idcaja'];
    $tipo = $_POST['tipo'];
    $nstatus = $_POST['nstatus'];
    $controller->avanzaCobranza($docf, $docp, $idcaja, $tipo, $nstatus);
} elseif (isset($_POST['impCompFact'])) {      //20062016
    $docf = $_POST['docf'];
    $docp = $_POST['docp'];
    $idcaja = $_POST['idcaja'];
    $tipo = $_POST['tipo'];
    $claveCli = $_POST['clavecli'];
    $controller->impCompFact($docf, $docp, $idcaja, $tipo, $claveCli);
} elseif (isset($_POST['delclasificaciongasto'])) {
    $id = $_POST['id'];
    $controller->DelClaGasto($id);
} elseif (isset($_POST['recmercancia'])) {
    $id = $_POST['idcaja'];
    $docf = $_POST['docf'];
    $tipo = $_POST['tipo'];
    if ($tipo == 'NC') {
        $controller->recmercancianc($id, $docf);
    } else {
        $controller->recmercancia($id, $docf);
    }
} elseif (isset($_POST['recibirCaja'])) {
    $id = $_POST['id'];
    $docf = $_POST['docf'];
    $idc = $_POST['idc'];
    $controller->recibirCaja($id, $docf, $idc);
} elseif (isset($_POST['recibirCajaNC'])) {
    $id = $_POST['id'];
    $docf = $_POST['docf'];
    $idc = $_POST['idc'];
    $idpreoc = $_POST['idpreoc'];
    $cantr = $_POST['cantr'];
    $controller->recibirCajaNC($id, $docf, $idc, $idpreoc, $cantr);
} elseif (isset($_POST['impRecMercancia'])) {
    $id = $_POST['idcaja'];
    $docf = $_POST['docf'];
    $docr = $_POST['docr'];
    $fact = $_POST['fact'];
    $controller->impRecMercancia($id, $docf, $docr, $fact);
} elseif (isset($_POST['guardaNuevoDocumentoC'])) {        //14062016
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $controller->GuardaNuevoDocC($nombre, $descripcion);
} elseif (isset($_POST['editadocumentoC'])) {
    $id = $_POST['id'];
    $controller->FormEditaDocumentoC($id);
} elseif (isset($_POST['guardaCambiosDocumentoC'])) {
    $activo = (empty($_POST['activo'])) ? "N" : $_POST['activo'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $id = $_POST['id'];
    $controller->EditaDocumentoC($activo, $nombre, $descripcion, $id);
} elseif (isset($_POST['AgregarDocumentoACliente'])) {
    $clave = $_POST['clave_cliente'];
    $controller->formNuevoDocCliente($clave);
} elseif (isset($_POST['asignaDocumentoC'])) {
    $cliente = $_POST['cliente'];
    $requerido = $_POST['requerido'];
    $copias = $_POST['copias'];
    $documento = $_POST['documento'];
    $controller->asignaNuevoDocCliente($cliente, $requerido, $copias, $documento);
} elseif (isset($_POST['contraReciboFact'])) {     //22062016
    $contrarecibo = $_POST['contraRecibo'];
    $idcaja = $_POST['idcaja'];
    $controller->guardaContraRecibo($contrarecibo, $idcaja);
} elseif (isset($_POST['reenviarcaja'])) {
    $factura = $_POST['factura'];
    $caja = $_POST['caja'];
    $controller->ReenviarCaja($factura, $caja);
} elseif (isset($_POST['datosCarteraCliente'])) {      //24062016
    $idCliente = $_POST['idcliente'];
    $controller->formDataCobranzaC($idCliente);
} elseif (isset($_POST['salvarDatoCobranza'])) {   //28062016
    $cliente = $_POST['cliente'];
    $carteraCob = $_POST['carteracobranza'];
    $carteraRev = $_POST['carterarevision'];
    for ($i = 1; $i <= 7; $i++) {
        if (count($_POST[('rev' . $i)]) > 0) {
            $diasRevision[] = $_POST[('rev' . $i)];
        }
    }
    for ($c = 1; $c <= 7; $c++) {
        if (count($_POST[('pag' . $c)]) > 0) {
            $diasPago[] = $_POST[('pag' . $c)];
        }
    }
    $dosPasos = $_POST['dospasos'];
    $plazo = $_POST['plazo'];
    $addenda = empty($_POST['addenda']) ? "N" : "S";
    $portal = $_POST['portal'];
    $usuario = $_POST['add_usuario'];
    $contrasena = $_POST['contrasena'];
    $observaciones = $_POST['observaciones'];
    $envio = $_POST['envio'];
    $cp = $_POST['cp'];
    $maps = $_POST['maps'];
    $tipo = $_POST['tipo'];
    $ln = $_POST['lincred'];
    $pc = $_POST['portalcob'];
    $controller->salvaDatosCob($cliente, $carteraCob, $carteraRev, $diasRevision, $diasPago, $dosPasos, $plazo, $addenda, $portal, $usuario, $contrasena, $observaciones, $envio, $cp, $maps, $tipo, $ln, $pc);
} elseif (isset($_POST['salvaCambiosDatoCobranza'])) {     //28062016
    $cliente = $_POST['cliente'];
    $carteraCob = $_POST['carteracobranza'];
    $carteraRev = $_POST['carterarevision'];
    for ($i = 1; $i <= 7; $i++) {
        if (count($_POST[('rev' . $i)]) > 0) {
            $diasRevision[] = $_POST[('rev' . $i)];
        }
    }
    for ($c = 1; $c <= 7; $c++) {
        if (count($_POST[('pag' . $c)]) > 0) {
            $diasPago[] = $_POST[('pag' . $c)];
        }
    }
    $dosPasos = $_POST['dospasos'];
    $plazo = $_POST['plazo'];
    $addenda = empty($_POST['addenda']) ? "N" : "S";
    $portal = $_POST['portal'];
    $usuario = $_POST['add_usuario'];
    $contrasena = $_POST['contrasena'];
    $observaciones = $_POST['observaciones'];
    $envio = $_POST['envio'];
    $cp = $_POST['cp'];
    $maps = $_POST['maps'];
    $tipo = $_POST['tipo'];
    $ln = $_POST['lincred'];
    $pc = $_POST['portalcob'];
    $controller->salvaCambiosDatosCob($cliente, $carteraCob, $carteraRev, $diasRevision, $diasPago, $dosPasos, $plazo, $addenda, $portal, $usuario, $contrasena, $observaciones, $envio, $cp, $maps, $tipo, $ln, $pc);
} elseif (isset($_POST['generarCierreEnt'])) { //27062016
    $controller->generarCierreEnt();
} elseif (isset($_POST['imprimeCierre'])) {
    $idu = $_POST['idu'];
    $controller->imprimeCierre($idu);
} elseif (isset($_POST['salvaContrarecibo'])) {    //3006
    $caja = $_POST['caja'];
    $cr = $_POST['cr'];
    $factura = $_POST['factura'];
    $remision = $_POST['remision'];
    $contraRecibo = $_POST['contraRecibo'];
    $status = $_POST['avanzarevision'];
    $controller->salvarContraRecibo($caja, $cr, $contraRecibo, $factura, $remision, $status);
} elseif (isset($_POST['imprimeContrarecibo'])) {  //3006
    $caja = $_POST['caja'];
    $factura = $_POST['factura'];
    $remision = $_POST['remision'];
    $cr = $_POST['cr'];
    $controller->emitirContraRecibo($caja, $factura, $remision, $cr);
} elseif (isset($_POST['RechazarPedido'])) {
    $docp = $_POST['docp'];
    $motivo = $_POST['motivoRechazo'];
    $controller->RechazarPedido($docp, $motivo);
} elseif (isset($_POST['liberarpedido'])) {
    $pedido = $_POST['docp'];
    $controller->LiberaPedido($pedido);
} elseif (isset($_POST['salvarMotivoSinCR'])) {        //06072016
    $motivo = $_POST['motivo'];
    $factura = $_POST['factura'];
    $remision = $_POST['remision'];
    $cr = $_POST['cr'];
    $controller->salvarMotivoSinCR($motivo, $factura, $remision, $cr);
} elseif (isset($_POST['GenerarCierreCarteraRevision'])) { //07072016
    $cr = $_POST['cr'];
    $controller->emitirCierreCR($cr);
} elseif (isset($_POST['info_foraneo'])) {
    $caja = $_POST['caja'];
    $doccaja = $_POST['doccaja'];
    $guia = $_POST['guia'];
    $fletera = $_POST['fletera'];
    $controller->info_foraneo($caja, $doccaja, $guia, $fletera);
} elseif (isset($_POST['asociarFactura'])) {
    $caja = $_POST['idcaja'];
    $docp = $_POST['docp'];
    $factura = $_POST['factura'];
    $controller->asociarFactura($caja, $docp, $factura);
} elseif (isset($_POST['asociarNC'])) {
    $caja = $_POST['idcaja'];
    $docp = $_POST['docp'];
    $nc = $_POST['nc'];
    $controller->asociarNC($caja, $docp, $nc);
} elseif (isset($_POST['avanzarDeslinde'])) {
    $caja = $_POST['idcaja'];
    $pedido = $_POST['docp'];
    $motivo = $_POST['motivodeslinde'];
    $controller->avanzaDeslinde($caja, $pedido, $motivo);
} elseif (isset($_POST['guardarAcuse'])) {
    $caja = $_POST['idcaja'];
    $pedido = $_POST['docp'];
    $guia = $_POST['guia'];
    $fletera = $_POST['fletera'];
    $controller->GuardaAcuse($caja, $pedido, $guia, $fletera);
} elseif (isset($_POST['imprmirfacturasnc'])) {
    $controller->imprimirFacturasNC();
} elseif (isset($_POST['imprmirfacturasdeslinde'])) {
    $controller->imprimirFacturasDeslinde();
} elseif (isset($_POST['imprmirfacturasacuse'])) {
    $controller->imprimirFacturasAcuse();
} elseif (isset($_POST['imprmirFacturasRemision'])) {
    $controller->imprimirFacturasRemision();
} elseif (isset($_POST['DetalleCliente'])) {
    if (isset($_POST['cliente'])) {
        $cliente = $_POST['cliente'];
    } else {
        $cliente = $_POST['cveclie'];
    }

    $controller->SaldosxDocumento($cliente);
} elseif (isset($_POST['deslindearevision'])) {
    $caja = $_POST['caja'];
    $docf = $_POST['factura'];
    $docr = $_POST['remision'];
    $sol = $_POST['sol'];
    $cr = $_POST['cr'];
    $controller->deslindearevision($caja, $docf, $docr, $sol, $cr);
} elseif (isset($_POST['deslindeConDosPasos'])) {      //05082016
    $caja = $_POST['caja'];
    $cr = $_POST['cr'];
    $controller->DeslindeConDosPasos($caja, $cr);
} elseif (isset($_POST['deslindeSinDosPasos'])) {      //05082016
    $cr = $_POST['cr'];
    $caja = $_POST['caja'];
    $numcr = $_POST['numcr'];
    $controller->DeslindeSinDosPasos($caja, $cr, $numcr);
} elseif (isset($_POST['salvaMotivoDeslindedp'])) {    //05082016
    $cr = $_POST['cr'];
    $caja = $_POST['caja'];
    $motivo = $_POST['motivodelinde'];
    $controller->salvaMotivoDeslindeDP($caja, $motivo, $cr);
} elseif (isset($_POST['salvaMotivoDeslindeNodp'])) {  //05082016
    $cr = $_POST['cr'];
    $caja = $_POST['caja'];
    $motivo = $_POST['motivodelinde'];
    $controller->salvaMotivoDeslindeNoDP($caja, $motivo, $cr);
} elseif (isset($_POST['avanzarCajaCobranza'])) {
    $caja = $_POST['caja'];
    $revdp = $_POST['revdp'];
    $numcr = $_POST['numcr'];
    $controller->avanzarCajaCobranza($caja, $revdp, $numcr);
} elseif (isset($_POST['CajaCobranza'])) {
    $caja = $_POST['caja'];
    $revdp = $_POST['revdp'];
    $numcr = $_POST['numcr'];
    $cr = $_POST['cr'];
    $controller->CajaCobranza($caja, $revdp, $numcr, $cr);
} elseif (isset($_POST['conceptoGasto'])) {
    //Modificado por GDELEON 3/Ago/2016
    //echo "simona la mona";
    $concept = $_POST['conceptoGasto'];
    $presupGasto = $controller->TraePresupuestoConceptGasto($concept);
    if ($presupGasto) {
        echo $presupGasto;
    }
    exit;
} elseif (isset($_POST['DesaAdu'])) {
    $caja = $_POST['idcaja'];
    $solucion = $_POST['soldesaduana'];
    $controller->DesaAdu($caja, $solucion);
} elseif (isset($_POST['MuestraCaja'])) {
    $docp = $_POST['MuestraCaja'];
    $controller->MuestraCaja($docp);
} elseif (isset($_POST['recDocCob'])) {
    $idc = $_POST['idcaja'];
    $controller->recDocCob($idc);
} elseif (isset($_POST['desDocCob'])) {
    $idc = $_POST['idcaja'];
    $controller->desDocCob($idc);
} elseif (isset($_POST['ImprimirDevolucion'])) {
    $idc = $_POST['idc'];
    $docf = $_POST['docf'];
    $controller->ImprimirDevolucion($idc, $docf);
} elseif (isset($_POST['cambiarStatus'])) {
    $idcaja = $_POST['idc'];
    $docp = $_POST['docp'];
    $secuencia = $_POST['secuencia'];
    $unidad = $_POST['uni'];
    $idu = $_POST['idu'];
    $ntipo = $_POST['tipo'];
    $tipoold = $_POST['tipoold'];
    $controller->cambiarStatus($idcaja, $docp, $secuencia, $unidad, $idu, $ntipo, $tipoold);
} elseif (isset($_POST['DesNC'])) {
    $idc = $_POST['idcaja'];
    $controller->DesNC($idc);
} elseif (isset($_POST['entaduana'])) {
    $idc = $_POST['idc'];
    $docf = $_POST['docf'];
    $docp = $_POST['docp'];
    $controller->entaduana($idc, $docf, $docp);
} elseif (isset($_POST['recbodega'])) {
    $idc = $_POST['idc'];
    $docf = $_POST['docf'];
    $docp = $_POST['docp'];
    $controller->recbodega($idc, $docf, $docp);
} elseif (isset($_POST['reclogistica'])) {
    $idc = $_POST['idc'];
    $docf = $_POST['docf'];
    $docp = $_POST['docp'];
    $controller->reclogistica($idc, $docf, $docp);
} elseif (isset($_POST['impLoteFact'])) {
    $controller->impLoteFact();
} elseif (isset($_POST['docfact'])) {
    $docfact = $_POST['docfact'];
    $idc = $_POST['idcaja'];
    $controller->docfact($docfact, $idc);
} elseif (isset($_POST['FORM_NAME_GASTO'])) {
    $identificador = $_POST['documento'];
    $fecha = $_POST['fecha'];
    $controller->pagoGasto($identificador);
} elseif (isset($_POST['CancelaFactura'])) {
    $docp = $_POST['factura'];
    $controller->CancelaFactura($docp);
} elseif (isset($_POST['CancelaF'])) {
    $docf = $_POST['factura'];
    $idc = $_POST['idc'];
    $controller->CancelarF($docf, $idc);
} elseif (isset($_POST['solAutoUB'])) {
    $docc = $_POST['cotizacion'];
    $par = $_POST['partida'];
    $controller->solAutoUB($docc, $par);
} elseif (isset($_POST['AutorizarUB'])) {
    $docc = $_POST['cotizacion'];
    $par = $_POST['partida'];
    $controller->AutorizarUB($docc, $par);
} elseif (isset($_POST['RechazoUB'])) {
    $docc = $_POST['cotizacion'];
    $par = $_POST['partida'];
    $controller->RechazoUB($docc, $par);
} elseif (isset($_POST['FORM_ACTION_XDICTAMINAR'])) {
    $identificador = $_POST['documento'];
    $tipo = $_POST['tipo'];
    $controller->xAutorizar($tipo, $identificador);
} elseif (isset($_POST['FORM_ACTION_DICTAMEN'])) {
    $identificador = $_POST['documento'];
    $tipo = $_POST['tipo'];
    $dictamen = $_POST['dictamen'];
    $comentarios = $_POST['comentarios'];
    $controller->xAutorizarDictamen($tipo, $identificador, $dictamen, $comentarios);
} elseif (isset($_POST['FORM_ACTION_IMPRIMIR'])) {
    $identificador = $_POST['identificador'];
    $tipo = $_POST['tipo'];
    $controller->impComprobantePago($identificador, $tipo);
} elseif (isset($_POST['impCheque'])) {
    $cheque = $_POST['cheque'];
    $banco = $_POST['banco'];
    $fecha = $_POST['fechapost'];
    $folio = $_POST['folion'];
    $banco = trim(substr($banco, 0, 8));
    if ($banco == 'Bancomer') {
        $controller->ImpChBancomer($cheque, $fecha, $folio);
    } elseif ($banco == 'Banamex') {
        $controller->ImpChBanamex($cheque, $fecha, $folio);
    } elseif (empty($banco)) {
        $controller->ImpSinBanco($cheque, $fecha, $folio);
    }
} elseif (isset($_POST['cancelaPedido'])) {
    $pedido = $_POST['pedido'];
    $motivo = $_POST['razon'];
    $controller->cancelaPedido($pedido, $motivo);
} elseif (isset($_POST['cargarPago'])) {
    $cliente = $_POST['cliente'];
    $controller->cargaPago($cliente);
} elseif (isset($_POST['FORM_ACTION_EDOCTA_DETALLE'])) {
    $identificador = $_POST['identificador'];
    $controller->estadoCuentaDetalle($identificador);
} elseif (isset($_POST['FORM_ACTION_EDOCTA_REGISTRO'])) {
    $identificador = $_POST['identificador'];
    $banco = $_POST['banco'];
    $cuenta = $_POST['numero_cuenta'];
    $dia = date("Y-m-d");
    $controller->estadoCuentaRegistro($identificador, $banco, $cuenta, $dia);
} elseif (isset($_POST['FORM_ACTION_EDOCTA_REGISTRAR'])) {
    $identificador = $_POST['idcuenta'];
    $banco = $_POST['banco'];
    $cuenta = $_POST['numero_cuenta'];
    $fecha = $_POST['fecha'];
    $descripcion = $_POST['descripcion'];
    $monto = $_POST['monto'];
    $controller->estadoCuentaRegistrar($identificador, $banco, $cuenta, $fecha, $descripcion, $monto);
} elseif (isset($_POST['FORM_ACTION_PAGOS_RECIBIR'])) {
    $identificador = $_POST['identificador'];
    $tipo = $_POST['tipo'];
    $fecha = $_POST['fecha'];
    $banco = $_POST['banco'];
    $monto = $_POST['monto'];
    //echo 'Este es el monto en el Index: '.$monto;
    $controller->pagosRecepcion($tipo, $identificador, $fecha, $banco, $monto);
} elseif (isset($_POST['FORM_ACTION_PAGOS_CONCILIAR'])) {
    $identificador = $_POST['identificador'];
    $tipo = $_POST['tipo'];
    $controller->pagoAConciliar($tipo, $identificador);
} elseif (isset($_POST['FORM_ACTION_PAGOS_CONCILIA'])) {
    $identificador = $_POST['identificador'];
    $tipo = $_POST['tipo'];
    $fecha = $_POST['fecha'];
    $controller->pagoConciliar($tipo, $identificador, $fecha);
} elseif (isset($_POST['guardaPago'])) {
    $cliente = $_POST['cliente'];
    $monto = $_POST['monto'];
    $fechaA = $_POST['fechaA'];
    $fechaR = $_POST['fechaR'];
    $banco = $_POST['banco'];
    $controller->guardaPago($cliente, $monto, $fechaA, $fechaR, $banco);
} elseif (isset($_POST['aplicarPago'])) {
    $cliente = $_POST['cliente'];
    $controller->aplicarPago($cliente);
} elseif (isset($_POST['ingresarPago'])) {
    $banco = $_POST['banco'];
    $monto = $_POST['monto'];
    $fecha = $_POST['fecha'];
    $ref = $_POST['ref1'];
    $banco2 = $_POST['banco2'];
    $cuenta = $_POST['cuenta'];
    $controller->ingresarPago($banco, $monto, $fecha, $ref, $banco2, $cuenta);
} elseif (isset($_POST['capturaPagosConta'])) {
    $banco = $_POST['banco'];
    $cuenta = $_POST['numero_cuenta'];
    $controller->capturaPagosConta($banco, $cuenta);
} elseif (isset($_POST['ESTADO_DE_CUENTA'])) {
    $banco = $_POST['banco'];
    $cuenta = $_POST['numero_cuenta'];
    $controller->estado_de_cuenta($banco, $cuenta);
} elseif (isset($_POST['FiltrarEdoCta'])) {
    $mes = $_POST['mes'];
    $banco = $_POST['banco'];
    $cuenta = $_POST['cuenta'];
    $anio = $_POST['anio'];
    if (isset($_GET['nvaFechComp'])) {
        $nvaFechComp = $_GET['nvaFechComp'];
    } else {
        $nvaFechComp = '01.01.2016';
    }
    $controller->estado_de_cuenta_mes($mes, $banco, $cuenta, $anio, $nvaFechComp);
} elseif (isset($_POST['traeFactura'])) {
    $docf = $_POST['docf'];
    $controller->traeFactura($docf);
} elseif (isset($_POST['cambiarFactura'])) {
    $docf1 = $_POST['docf1'];
    $tipo = $_POST['tipo'];
    $controller->cambiarFactura($docf1, $tipo);
} elseif (isset($_POST['cajaxembalar'])) {
    $docp = $_POST['pedido'];
    $controller->porFacturarEmbalar($docp);
} elseif (isset($_POST['comprasXmes'])) {
    $mes = $_POST['Mes'];
    $controller->comprasXmes($mes);
} elseif (isset($_POST['regCompEdoCta'])) {
    $fecha = $_POST['fechaedo'];
    $docc = $_POST['docc'];
    $mes = $_POST['mes'];
    $pago = $_POST['pago'];
    $banco = $_POST['banco'];
    $tptes = $_POST['tptes'];
    $controller->regCompEdoCta($fecha, $docc, $mes, $pago, $banco, $tptes);
} elseif (isset($_POST['FORM_ACTION_PAGO_CREDITO_CONTRARECIBO'])) {
    $identificador = $_POST['identificador'];
    $tipo = $_POST['tipo'];
    //echo "TIPO: -$tipo-";
    $controller->detallePagoCreditoContrarecibo($tipo, $identificador);
} elseif (isset($_POST['FORM_ACTION_PAGO_CREDITO_CONTRARECIBO_IMPRIMIR'])) {
    $identificador = $_POST['identificador'];
    $tipo = $_POST['tipo'];
    $montor = $_POST['montor'];
    $facturap = $_POST['facturap'];
    $controller->detallePagoCreditoContrareciboImprime($tipo, $identificador, $montor, $facturap);
} elseif (isset($_POST['FORM_ACTION_OC_ADUANA_LISTA'])) {
    $mes = $_POST['mes'];
    $anio = $_POST['anio'];
    $controller->verListadoOCAduana($mes, $anio);
} elseif (isset($_POST['FORM_ACTION_OC_ADUANA_REGISTRO'])) {
    $identificador = $_POST['identificador'];
    $aduana = $_POST['aduana'];
    $mes = $_POST['mes'];
    $anio = $_POST['anio'];
    $controller->registrarOCAduana($identificador, $aduana, $mes, $anio);
} elseif (isset($_POST['fallarOC'])) {
    $doco = $_POST['doco'];
    $controller->ImpresionFallido($doco);
} elseif (isset($_POST['FacturaPago'])) {
    $cveclie = $_POST['cveclie'];
    $controller->FacturaPago($cveclie);
} elseif (isset($_POST['PagoxFactura'])) {
    $docf = $_POST['docf'];
    $clie = $_POST['clie'];
    $rfc = $_POST['rfc'];
    $controller->PagoxFactura($docf, $clie, $rfc);
} elseif (isset($_POST['aplicaPagoxFactura'])) {
    $docf = $_POST['docf'];
    $idpago = $_POST['idpago'];
    $monto = $_POST['monto'];
    $saldof = $_POST['saldof'];
    $clie = $_POST['clie'];
    $rfc = $_POST['rfc'];
    $controller->aplicarPagoxFactura($docf, $idpago, $monto, $saldof, $clie, $rfc);
} elseif (isset($_POST['PagoFactura'])) {
    $clie = $_POST['cveclie'];
    $controller->PagoFactura($clie);
} elseif (isset($_POST['aplicaPago'])) {
    $clie = $_POST['clie'];
    $id = $_POST['id'];
    $controller->aplicaPago($clie, $id);
} elseif (isset($_POST['guardaCompra'])) {
    $fact = $_POST['fact'];
    $prov = $_POST['proveedor'];
    $monto = $_POST['monto'];
    $ref = $_POST['referencia'];
    $tipopago = $_POST['tipopago'];
    $fechadoc = $_POST['fechadoc'];
    $fechaedocta = $_POST['fechaEdoCta'];
    $banco = $_POST['banco'];
    $tipo = $_POST['tipo'];
    $idg = $_POST['idg'];
    $controller->guardaCompra($fact, $prov, $monto, $ref, $tipopago, $fechadoc, $fechaedocta, $banco, $tipo, $idg);
} elseif (isset($_POST['aplicaPagoFactura'])) {
    $clie = $_POST['clie'];
    $id = $_POST['idpago'];
    $docf = $_POST['docf'];
    $monto = $_POST['monto'];
    $saldof = $_POST['saldof'];
    $rfc = $_POST['rfc'];
    $controller->aplicaPagoFactura($clie, $id, $docf, $monto, $saldof, $rfc);
} elseif (isset($_POST['impAplicacion'])) {
    $ida = $_POST['ida'];
    $controller->impAplicacion($ida);
} elseif (isset($_POST['aplicaPagoDirecto'])) {
    $idp = $_POST['idpago'];
    $tipo = $_POST['tipo'];
    $controller->aplicaPagoDirecto($idp, $tipo);
} elseif (isset($_POST['PagoDirecto'])) {
    $idp = $_POST['idpago'];
    $docf = $_POST['docf'];
    $rfc = $_POST['rfc'];
    $monto = $_POST['monto'];
    $saldof = $_POST['saldof'];
    $clie = $_POST['clie'];
    $tipo = $_POST['tipo'];

    $controller->PagoDirecto($idp, $docf, $rfc, $monto, $saldof, $clie, $tipo);
} elseif (isset($_POST['traeFacturaPago'])) {
    $idp = $_POST['idpago'];
    $monto = $_POST['monto'];
    $docf = $_POST['docf'];
    $tipo = $_POST['tipo'];
    $controller->traeFacturaPago($idp, $monto, $docf, $tipo);
} elseif (isset($_POST['traeValidacion'])) {
    $doco = $_POST['doco'];
    $controller->traeValidacion($doco);
} elseif (isset($_POST['verPagosActivos'])) {
    $monto = $_POST['monto'];
    $controller->verPagosActivos($monto);
} elseif (isset($_POST['imprimirComprobante'])) {
    $idp = $_POST['idpago'];
    $controller->imprimirComprobante($idp);
} elseif (isset($_POST["FORM_ACTION_CR_PAGO"])) {
    $folios = $_POST["items"];
    $cantidad = $_POST["seleccion_cr"];
    $monto = $_POST["total"];
    $controller->pagarOCContrarecibos($cantidad, $folios, $monto);
} elseif (isset($_POST['FORM_ACTION_CR_PAGO_APLICAR'])) {
    $medio = $_POST['medio'];
    $cuentaBancaria = $_POST['cuentabanco'];
    $folios = $_POST['folios'];
    $monto = $_POST['monto'];
    $controller->pagarOCContrarecibosAplicar($folios, $cuentaBancaria, $medio, $monto);
} elseif (isset($_POST['IngresarBodega'])) {
    $desc = $_POST['des'];
    $cant = $_POST['cant'];
    $marca = $_POST['marca'];
    $proveedor = $_POST['proveedor'];
    $costo = $_POST['costo'];
    $unidad = $_POST['unidad'];
    $controller->IngresarBodega($desc, $cant, $marca, $proveedor, $costo, $unidad);
} elseif (isset($_POST['guardaCargoFinanciero'])) {
    $monto = $_POST['monto'];
    $fecha = $_POST['fecha'];
    $banco = $_POST['banco'];
    $controller->guardaCargoFinanciero($monto, $fecha, $banco);
} elseif (isset($_POST['aplicarPagoFactura'])) {
    $caja = $_POST['caja'];
    $docf = $_POST['docf'];
    $controller->aplicarPagoFactura($caja, $docf);
} elseif (isset($_POST['impRecCobranza'])) {
    $controller->impRecCobranza();
} elseif (isset($_POST['imprimeCierreEnt'])) {
    $controller->imprimeCierreEnt();
} elseif (isset($_POST['imprimeCierreEnt'])) {
    $controller->imprimeCierreEnt();
} elseif (isset($_POST['guardaFacturaProv'])) {
    $docr = $_POST['docr'];
    $factura = $_POST['factura'];
    $controller->guardaFacturaProv($docr, $factura);
} elseif (isset($_POST['impCierreVal'])) {
    $controller->impCierreVal();
} elseif (isset($_POST['asociarCF'])) {
    $idcf = $_POST['idcf'];
    $rfc = $_POST['rfc'];
    $banco = $_POST['banco'];
    $cuenta = $_POST['cuenta'];
    $controller->asociarCF($idcf, $rfc, $banco, $cuenta);
} elseif (isset($_POST['traePagos'])) {
    $monto = $_POST['monto'];
    $idcf = $_POST['idcf'];
    $controller->traePagos($idcf, $monto);
} elseif (isset($_POST['cargaCF'])) {
    $idcf = $_POST['idcf'];
    $idp = $_POST['idp'];
    $montoc = $_POST['montoc'];
    $controller->cargaCF($idcf, $idp, $montoc);
} elseif (isset($_POST['enviaAcreedor'])) {
    $idp = $_POST['idp'];
    $saldo = $_POST['saldo'];
    $rfc = $_POST['rfc'];
    $controller->enviaAcreedor($idp, $saldo, $rfc);
} elseif (isset($_POST['contabilizarAcreedor'])) {
    $ida = $_POST['ida'];
    $controller->contabilizarAcreedor($ida);
} elseif (isset($_POST['cancelaAplicacion'])) {
    $idp = $_POST['idp'];
    $docf = $_POST['docf'];
    $idap = $_POST['idap'];
    $montoap = $_POST['montoap'];
    $controller->cancelaAplicacion($idp, $docf, $idap, $montoap);
} elseif (isset($_POST['procesarPago'])) {
    $idp = $_POST['idpago'];
    $saldop = $_POST['saldopago'];
    $montop = $_POST['montopago'];
    $tipo = $_POST['tipoPago'];
    if ($saldop == $montop and $tipo != 'SS') {
        $controller->procesarPago($idp, $tipo);
    } else {
        $controller->errorPago($idp, $tipo);
    }
} elseif (isset($_POST['regEdoCta'])) {
    $idtrans = $_POST['idtrans'];
    $monto = $_POST['idmonto'];
    $tipo = $_POST['tipo'];
    $mes = $_POST['mes'];
    $banco = $_POST['banco'];
    $cuenta = $_POST['cuenta'];
    $cargo = $_POST['montoCargo'];
    $anio = $_POST['anio'];
    $nvaFechComp = $_POST['nvaFechComp'];
    $nf = '0';
    $controller->regEdoCta($idtrans, $monto, $tipo, $mes, $banco, $cuenta, $cargo, $anio, $nvaFechComp, $nf);
} elseif (isset($_POST['regEdoCta1'])) {
    $idtrans = $_POST['idtrans'];
    $monto = $_POST['idmonto'];
    $tipo = $_POST['tipo'];
    $mes = $_POST['mes'];
    $banco = $_POST['banco'];
    $cuenta = $_POST['cuenta'];
    $cargo = $_POST['montoCargo'];
    $anio = $_POST['anio'];
    $nvaFechComp = $_POST['nvaFechComp'];
    $nf = '1';
    $controller->regEdoCta($idtrans, $monto, $tipo, $mes, $banco, $cuenta, $cargo, $anio, $nvaFechComp, $nf);
} elseif (isset($_POST['imprimeValidacion'])) {
    $idval = $_POST['idval'];
    $controller->imprimeValidacion($idval);
} elseif (isset($_POST['ImpSolicitud'])) {
    $idsol = $_POST['idsol'];
    $controller->ImpSolicitud($idsol);
} elseif (isset($_POST['ImpSolPagada'])) {
    $idsol = $_POST['idsol'];
    $controller->ImpSolPagada($idsol);
} elseif (isset($_POST['recConta'])) {
    $folio = $_POST['folio'];
    $controller->recConta($folio);
} elseif (isset($_POST['regCompraEdoCta'])) {
    $folio = $_POST['folio'];
    $doc = $_POST['doc'];
    $fecha = $_POST['fecEdoCta'];
    $controller->regCompraEdoCta($folio, $doc, $fecha);
} elseif (isset($_POST['buscarPagos'])) {
    $campo = $_POST['campo'];
    $controller->buscarPagos($campo);
} elseif (isset($_POST['cancelarPago'])) {
    $idp = $_POST['idp'];
    $controller->cancelarPago($idp);
} elseif (isset($_POST['enviarConta'])) {
    $medio = $_POST['medio'];
    $cuentaBancaria = $_POST['cuentabanco'];
    $folios = $_POST['folios'];
    $monto = $_POST['monto'];
    $controller->enviarConta($folios, $cuentaBancaria, $medio, $monto);
} elseif (isset($_POST['buscarContrarecibos'])) {
    $campo = $_POST['campo'];
    $controller->buscarContrarecibos($campo);
} elseif (isset($_POST['impresionContrarecibo'])) {
    $tipo = $_POST['tipo'];
    $identificador = $_POST['identificador'];
    $controller->impresionContrarecibo($tipo, $identificador);
} elseif (isset($_POST['editIngresoBodega'])) {
    $idi = $_POST['idi'];
    $costo = $_POST['costo'];
    $proveedor = $_POST['proveedor'];
    $cant = $_POST['cantidad'];
    $unidad = $_POST['unidad'];
    $controller->editIngresoBodega($idi, $costo, $proveedor, $cant, $unidad);
} elseif (isset($_POST['filtroDirVerFacturas'])) {
    $mes = $_POST['mes'];
    $vend = $_POST['vendedor'];
    $anio = $_POST['anio'];
    $controller->dirVerFacturas($mes, $vend, $anio);
} elseif (isset($_POST['traeOC'])) {
    $campo = $_POST['campo'];
    $fechaedo = $_POST['fechaedo'];
    $controller->traeOC($campo, $fechaedo);
} elseif (isset($_POST['procesarOC'])) {
    $fechaedo = $_POST['fechaedo'];
    $doco = $_POST['doco'];
    $montof = $_POST['montof'];
    $factura = $_POST['factura'];
    $idb = $_POST['banco'];
    $tpf = $_POST['tpf'];
    $controller->procesarOC($doco, $idb, $fechaedo, $montof, $factura, $tpf);
} elseif (isset($_POST['guardaDeudor'])) {
    $fechaedo = $_POST['fechaedo'];
    $monto = $_POST['monto'];
    $proveedor = $_POST['proveedor'];
    $banco = $_POST['banco'];
    $tpf = $_POST['tpf'];
    $referencia = $_POST['referencia'];
    $destino = $_POST['destino'];
    $controller->guardaDeudor($fechaedo, $monto, $proveedor, $banco, $tpf, $referencia, $destino);
} elseif (isset($_POST['guardaTransPago'])) {
    $fechaedo = $_POST['fechaedo'];
    $monto = $_POST['monto'];
    $bancoO = $_POST['bancoO'];
    $bancoD = $_POST['bancoD'];
    $tpf = $_POST['tpf'];
    $TT = $_POST['TT'];
    $referencia = $_POST['referencia'];
    $controller->guardaTransPago($fechaedo, $monto, $bancoO, $bancoD, $tpf, $TT, $referencia);
} elseif (isset($_POST['SaldosxDocumentoH'])) {
    $cliente = $_POST['cveclie'];
    $controller->SaldosxDocumentoH($cliente);
} elseif (isset($_POST['facturapagomaestro'])) {
    $maestro = $_POST['maestro'];
    $controller->facturapagomaestro($maestro);
} elseif (isset($_POST['pagoFacturaMaestro'])) {
    $maestro = $_POST['maestro'];
    $docf = $_POST['docf'];
    $controller->pagoFacturaMaestro($maestro, $docf);
} elseif (isset($_POST['calendarCxC'])) {
    $cartera = $_POST['calendarCxC'];
    $controller->calendarCxC($cartera);
} elseif (isset($_POST['regnvafecha'])) {
    $idtrans = $_POST['iden'];
    $monto = 0;
    $tipo = 'NA';
    $mes = 99;
    $banco = 'NA';
    $cuenta = 'NA';
    $cargo = 0;
    $anio = 2017;
    $nvaFechComp = $_POST['fecha'];
    $nf = '1';
    $valor = $_POST['valor'];
    $controller->regEdoCta($idtrans, $monto, $tipo, $mes, $banco, $cuenta, $cargo, $anio, $nvaFechComp, $nf, $valor);
} elseif (isset($_POST['editarMaestro'])) {
    $idm = $_POST['idm'];
    $controller->editarMaestro($idm);
} elseif (isset($_POST['editaMaestro'])) {
    $idm = $_POST['idm'];
    for ($i = 1; $i <= 4; $i++) {
        if (count($_POST[('CC' . $i)]) > 0) {
            $cc[] = $_POST[('CC' . $i)];
        }
    }
    for ($c = 1; $c <= 4; $c++) {
        if (count($_POST[('CR' . $c)]) > 0) {
            $cr[] = $_POST[('CR' . $c)];
        }
    }
    $controller->editaMaestro($idm, $cc, $cr);
} elseif (isset($_POST['altaMaestro'])) {
    $nombre = $_POST['nombre'];
    for ($i = 1; $i <= 4; $i++) {
        if (count($_POST[('CC' . $i)]) > 0) {
            $cc[] = $_POST[('CC' . $i)];
        }
    }
    for ($c = 1; $c <= 4; $c++) {
        if (count($_POST[('CR' . $c)]) > 0) {
            $cr[] = $_POST[('CR' . $c)];
        }
    }
    $controller->altaMaestro($nombre, $cr, $cc);
} elseif (isset($_POST['costeoRecepcion'])) {
    $docr = $_POST['docr'];
    $controller->costeoRecepcion($docr);
} elseif (isset($_POST['calcularCosto'])) {
    $cimpuesto = $_POST['cimpuesto'];
    $cflete = $_POST['cflete'];
    $cseguro = $_POST['cseguro'];
    $caduana = $_POST['caduana'];
    $docr = $_POST['docr'];
    $pedimento = $_POST['pedimento'];
    $controller->calcularCosto($cimpuesto, $cflete, $cseguro, $caduana, $pedimento, $docr);
} elseif (isset($_POST['costoFOB'])) {
    $cfob = $_POST['cfob'];
    $tc = $_POST['tc'];
    $docr = $_POST['docr'];
    $par = $_POST['par'];
    $pedimento = $_POST['pedimento'];
    $controller->costoFOB($cfob, $tc, $docr, $par, $pedimento);
} elseif (isset($_POST['finalizaCosteo'])) {
    $docr = $_POST['docr'];
    $controller->finalizaCosteo($docr);
} elseif (isset($_POST['traeProductosCliente'])) {
    $cliente = $_POST['cliente'];
    $numdepto = $_POST['deptonumber'];
    $nomdepto = $_POST['deptoname'];
    $nombre = $_POST['nombre'];
    $controller->traeProductosCliente($cliente, $nombre, $nomdepto, $numdepto);
} elseif (isset($_POST['asociarSKU'])) {
    $cliente = $_POST['cliente'];
    $numdepto = $_POST['deptonumber'];
    $nomdepto = $_POST['deptoname'];
    $nombre = $_POST['nombre'];
    $cprod = $_POST['cprod'];
    $sku = $_POST['sku'];
    $controller->asociarelSKU($cliente, $numdepto, $nomdepto, $nombre, $cprod, $sku);
}  elseif (isset($_POST['selectFactura'])) {
    $docf = $_POST['docf'];
    $select = $_POST['select'];
    $response=$controller->selectFactura($docf, $select);
    echo json_encode($response);
    exit();
} elseif (isset($_POST['GeneraReporteSalida'])) {
    $controller->GeneraReporteSalida();
} elseif (isset($_POST['imprimirReporte'])) {
    $vehiculo = $_POST['vehiculo'];
    $cajas = $_POST['cajas'];
    $placas = $_POST['placas'];
    $operador = $_POST['operador'];
    $observaciones = $_POST['observaciones'];
    $fecha = $_POST['fecha'];
    $controller->generaEmbarque($vehiculo, $cajas, $placas, $operador, $observaciones, $fecha);
    //$controller->imprimirReporte($vehiculo,$cajas,$placas,$operador, $observaciones, $fecha);
} elseif (isset($_POST['reporteEmbarque'])) {
    $idr = $_POST['idr'];
    $controller->reporteEmbarque($idr);
} elseif (isset($_POST['cancelaEmbarque'])) {
    $idr = $_POST['idr'];
    $controller->cancelaEmbarque($idr);
} elseif (isset($_POST['cambiarReporte'])) {
    $vehiculo = $_POST['vehiculo'];
    $cajas = $_POST['cajas'];
    $placas = $_POST['placas'];
    $operador = $_POST['operador'];
    $observaciones = $_POST['observaciones'];
    $fecha = $_POST['fecha'];
    $idr = $_POST['idr'];
    $controller->cambiarReporte($vehiculo, $cajas, $placas, $operador, $observaciones, $fecha, $idr);
} elseif (isset($_POST['reimprimirReporte'])) {
    $idr = $_POST['idr'];
    $controller->reimprimirReporte($idr);
} elseif (isset($_POST['cambiarFecha'])) {
    $docf = $_POST['docf'];
    $nuevaFecha = $_POST['nuevaFecha'];
    $cliente = $_POST['cliente'];
    $controller->cambiarFecha($docf, $nuevaFecha, $cliente);
} elseif (isset($_POST['cerrarFecha'])) {
    $docf = $_POST['docf'];
    $controller->cerrarFecha($docf);
} elseif (isset($_POST['guardaObsPar'])) {
    $datos = $_POST['datos'];
    $response=$controller->guardaObsPar($datos);
    echo json_encode($response);
    exit();
} elseif (isset($_POST['liberarRecepcion'])) {
    $docr = $_POST['docr'];
    $controller->liberarRecepcion($docr);
} elseif (isset($_POST['guardaCaja'])) {
    $idr = $_POST['idr'];
    $docf = $_POST['docf'];
    $cajas = $_POST['cajasxp'];
    $response=$controller->guardaCaja($idr, $docf, $cajas);
    echo json_encode($response);
    exit();
} elseif (isset($_POST['verES'])) {
    $fechaini = $_POST['fechaini'];
    $fechafin = $_POST['fechafin'];
    $controller->verES($fechaini, $fechafin);
}elseif (isset($_POST['verAuxSaldosCxc'])) {
    $fechaini = $_POST['fechaini'];
    $fechafin = $_POST['fechafin'];
    $controller->verAuxSaldosCxc($fechaini, $fechafin);    
}elseif (isset($_POST['compruebaXml'])) {
    $response = $controller->compruebaXml($_POST['compruebaXml']);
    echo json_encode($response);
    exit();
}elseif(isset($_POST['verListaDePrecios'])){
    $cliente=$_POST['cliente'];
    $controller->verListaDePrecios($cliente);
}
elseif (isset($_POST['utilerias'])) {
    $opcion = $_POST['opcion'];
    if(isset($_POST['docp'])){
        $docp = $_POST['docp']; 
    }else{
        $docp='';   
    }
    if(isset($_POST['docf'])){
        $docf = $_POST['docf'];
    }else{
        $docf='';
    }
    if(isset($_POST['docd'])){
        $docd = $_POST['docd'];
    }else{
        $docd = '';
    }
    if(isset($_POST['fechaIni'])){
        $fechaIni = $_POST['fechaIni'];
    }else{
        $fechaIni = '';
    }
    if(isset($_POST['fechaFin'])){
        $fechaFin = $_POST['fechaFin'];
    }else{
        $fechaFin = '';
    }
    if(isset($_POST['maestro'])){
        $maestro =$_POST['maestro'];
    }else{
        $maestro = '';
    }
    $controller->utilerias($opcion, $docp, $docd, $docf, $fechaIni, $fechaFin, $maestro);
}elseif (isset($_POST['noenviar'])) {
    $docf=$_POST['noenviar'];
    $res=$controller->noenviar($docf);
    echo json_encode($res);
    exit();
}elseif (isset($_POST['correoApolo'])) {
    $res=$controller->correoApolo($_POST['id'], $_POST['opc']);
    echo json_encode($res);exit();
}
else {
    switch ($_GET['action']) {
        case 'login':
            $controller->Login();
            break;
        case 'madmin':
            $controller->MenuAdmin();
            break;
        case 'ResultDia':
            $controller->ResultDia();
            break;
        case 'cierreruta':
            $controller->CierreRuta();
            break;
        case 'CierraRuta':
            $idr = $_GET['idr'];
            $controller->CierraRutaUnidad($idr);
            break;
        case 'cierrerutagen':
            $controller->cierrerutagen();
            break;
        case 'ventVScobr':
            $charnodeseados = array("/", "-");
            @$fechaini = str_replace($charnodeseados, ".", $_GET['fechainicial']);
            @$fechafin = str_replace($charnodeseados, ".", $_GET['fechafinal']);
            @$vend = $_GET['vendedor'];
            $controller->RVentasVsCobrado($fechaini, $fechafin, $vend);
            break;
        case 'Catalogo_Gastos':
            $controller->VerCatalogoGastos();
            break;
        case 'nuevogasto':
            $controller->NuevaCtaGasto();
            break;
        case 'imprimircatgastos':
            $controller->ImpCatalogoCuentas();
            break;
        case 'form_capturagastos':
            $controller->FormCapturaGasto();
            break;
        case 'verEntregas':
            $controller->verEntregas();
            break;
        case'cierreReparto':
            $controller->cierreReparto();
            break;
        case'recibirMercancia':
            $controller->recibirMercancia();
            break;
        case 'verFacturas':
            $controller->verFacturas();
            break;
        case 'clasificacion_gastos':                #### Clasificación de gastos
            $controller->Clasificacion_gastos();
            break;
        case 'nuevaclagasto':
            $controller->NuevaClaGasto();
            break;
        case 'catalogo_documentos':
            $controller->CatalogoDocumentos();  //14062016
            break;
        case 'nuevo_documentoC':
            $controller->NuevoDocumentoC();
            break;
        case 'documentos_cliente':
            $controller->CatDocumentosXCliente();
            break;
        case 'documentosdelcliente':
            $clave = $_GET['clave'];
            $controller->VerDocumentosCliente($clave);
            break;
        case'mercanciaRecibidaImp':                     //21062016
            $controller->recibosMercanciaImp();
            break;
        case 'subcartera_revision':             //2806
            $controller->SMCarteraRevision();
            break;
        case 'verCR':                           //2806
            $cr = $_GET['cr'];
            $controller->VarCartera($cr);
            break;
        case 'subcartera_revm10':   //3006
            $controller->SMCarteraRev10();
            break;
        case 'verCR10':                           //3006
            $cr = $_GET['cr'];
            $controller->VarCartera10($cr);
            break;
        case 'ImprmirCarteraDia':
            $cr = $_GET['cr'];
            $controller->ImprimirCarteraDia($cr);
            break;
        case 'catCierreCr':      //07072016
            $cr = $_GET['cr'];
            $controller->catCierreCarteraR($cr);
            break;
        case 'SMCierreCartera':     //07072016
            $controller->SMCierreCartera();
            break;
        case 'SMCarteraCobranza':   //07072016
            $controller->SMCarteraCobranza();
            break;
        case 'catCobranza': //07072016
            $cc = $_GET['cc'];
            $controller->catCobranza($cc);
            break;
        case 'corteCredito':    //06072016
            $controller->catCorteCredito();
            break;
        case 'acuse_revision':
            $controller->acuse_revision();
            break;
        case 'FacturarRemision':
            $controller->FacturarRemision();
            break;
        case 'NCFactura':
            $controller->NCFactura();
            break;
        case 'VerFacturasDeslinde':
            $controller->VerFacturasDeslinde();
            break;
        case 'VerFacturasAcuse':
            $controller->VerFacturasAcuse();
            break;
        case 'CarteraCobranza':     //12062016
            $controller->verCarteraCobranza();
            break;
        case 'ContactosCliente':
            $cliente = $_GET['cliente'];
            $controller->ContactosCliente($cliente);
            break;
        case 'CarteraxCliente':
            $cve_maestro = $_GET['cve_maestro'];
            $controller->CarteraxCliente($cve_maestro);
            break;
        case 'PedidosAnticipados':
            $controller->PedidosAnticipados();
            break;
        case 'AnticipadosUrgencias':
            $controller->AnticipadosUrgencias();
            break;
        case 'submFacuracion':
            $controller->SubMenuCxCC();
            break;
        case 'facturashoy':
            $controller->FacturacionDia();
            break;
        case 'facturasayer':
            $controller->FacturacionAyer();
            break;
        case 'utilidadFacturas':
            $charnodeseados = array("-", "/");
            @$fechaini = str_replace($charnodeseados, ".", $_GET['fechaini']);
            @$fechafin = str_replace($charnodeseados, ".", $_GET['fechafin']);
            @$rango = $_GET['rangoutil'];
            @$utilidad = $_GET['utilidad'];
            @$letras = $_GET['letras'];
            @$status = $_GET['status'];
            $controller->utilidadFacturas($fechaini, $fechafin, $rango, $utilidad, $letras, $status);
            break;
        case 'utilidadXfactura':
            $fact = $_GET['fact'];
            $controller->utilidadXFactura($fact);
            break;
        case 'deslindecr':
            $controller->deslindecr();
            break;
        case 'RevConDosP':                      //05082016
            $cr = $_GET['cr'];
            $controller->revConDosPasos($cr);
            break;
        case 'RevSinDosP':                      //05082016
            $cr = $_GET['cr'];
            $controller->revSinDosPasos($cr);
            break;
        case 'DesRevConDosP':                   //05082016
            $cr = $_GET['cr'];
            $controller->DeslindeRevConDosP($cr);
            break;
        case 'DesRevSinDosP':                   //05082016
            $cr = $_GET['cr'];
            $controller->DeslindeRevSinDosP($cr);
            break;
        case 'RevisionDosPasos':        //05082016
            $controller->SMRevisionDosPasos();
            break;
        case 'RevisionSinDosPasos':     //05082016
            $controller->SMSinRevisionDosPasos();
            break;
        case 'SMDesRevisionDosPasos':        //05082016
            $controller->SMDesRevisionDosPasos();
            break;
        case 'SMRevisionSinDosPasos':     //05082016
            $controller->SMDesSinRevisionDosPasos();
            break;
        case 'deslindeaduana':
            $controller->deslindeaduana();
            break;
        case 'BuscarCajasxPedido':
            $controller->BuscarCajasxPedido();
            break;
        case 'RecibirDocsRevision':
            $controller->RecibirDocsRevision();
            break;
        case 'CCobranza':
            $controller->SMCCobranza();
            break;
        case 'VerCobranza':
            $cc = $_GET['cc'];
            $controller->VerCobranza($cc);
            break;
        case 'verCajasLogistica':
            $controller->verCajasLogistica();
            break;
        case 'VerLoteEnviar':
            $controller->VerLoteEnviar();
        case 'VerInventarioEmpaque':
            $controller->VerInventarioEmpaque();
            break;
        case 'verPedidosPendientes':
            $controller->verPedidosPendientes();
            break;
        case 'pago_gastos':
            $controller->pagoGastos();
            break;
        case 'CancelarFactura':
            $controller->CancelarFactura();
            break;
        case 'UtilidadBaja':
            $controller->UtilidadBaja();
            break;
        case 'verSolicitudesUB':
            $controller->verSolicitudesUB();
            break;
        case 'verpago1':
            $controller->verpago1();
            break;
        case 'listadoXautorizar':
            $controller->verXautorizar();
            break;
        case 'listado_pagos_rechazados':
            $controller->listadoRechazados();
            break;
        case 'Cheques':
            $controller->Cheques();
            break;
        case 'pagos_ximprimir':
            $controller->listadoPagosXImprimir();
            break;
        case 'cancelarPedidos':
            $controller->cancelarPedidos();
            break;
        case 'listaClientes':
            $controller->listaClientes();
            break;
        case 'edocta_cuentasbancarias':
            $controller->listadoCuentasBancarias();
            break;
        case 'listadoXrecibir':
            $controller->verXrecibir();
            break;
        case 'listadoXconciliar':
            $controller->verXconciliar();
            break;
        case 'selectBanco':
            $controller->selectBanco();
            break;
        case 'edoCta':
            $controller->listaCuentas();
            break;
        case 'buscaFactura':
            $controller->buscaFactura();
            break;
        case 'buscarCajaEmabalar':
            $controller->buscarCajaEmabalar();
            break;
        case 'filtrarCompras':
            $controller->filtrarCompras();
            break;
        case 'listadoCredito':
            $controller->verListadoPagosCredito();
            break;
        case 'listaOCAduana':
            $fecha = getdate();
            $mes = $fecha['mon'];
            $anio = $fecha['year'];
            $controller->verListadoOCAduana($mes, $anio);
            break;
        case 'verFallidas':
            $controller->verFallidas();
            break;
        case 'form_capruracrdirecto':
            $controller->form_capruracrdirecto();
            break;
        case 'verAplicaciones':
            $controller->verAplicaciones();
            break;
        case 'buscaPagosActivos':
            $controller->buscaPagosActivos();
            break;
        case 'IdvsComp':
            $controller->IdvsComp();
            break;
        case 'buscaValidacionOC':
            $controller->buscaValidacionOC();
            break;
        case 'verAplivsFact':
            $controller->verAplivsFact();
            break;
        case 'pagoContrarecibo':
            $controller->listarOCContrarecibos();
            break;
        case 'IngresoBodega':
            $controller->IngresoBodega();
            break;
        case 'verIngresoBodega':
            $controller->verIngresoBodega();
            break;
        case 'regCargosFinancieros':
            $controller->regCargosFinancieros();
            break;
        case 'verCierreVal':
            $controller->verCierreVal();
            break;
        case 'asociaCF':
            $controller->asociaCF();
            break;
        case 'verPagosConSaldo':
            $controller->verPagosConSaldo();
            break;
        case 'verAcreedores':
            $controller->verAcreedores();
            break;
        case 'aplicaPagoDirecto':
            $idp = $_GET['idp'];
            $tipo = $_GET['tipo'];
            $controller->aplicaPagoDirecto($idp, $tipo);
            break;
        case 'estado_de_cuenta_mes':
            $mes = $_GET['mes'];
            $cuenta = $_GET['cuenta'];
            $banco = $_GET['banco'];
            $anio = $_GET['anio'];
            $nvaFechComp = $_GET['nvaFechComp'];
            //echo $mes;
            //echo $cuenta;
            //echo $banco;
            //echo $anio;
            $controller->estado_de_cuenta_mes($mes, $banco, $cuenta, $anio, $nvaFechComp);
            break;
        case 'ValidaRecepcionConFolio';
            $docr = $_GET['docr'];
            $doco = $_GET['doco'];
            $fval = $_GET['fval'];
            $controller->validaRecepcionConFolio($docr, $doco, $fval);
            break;
        case 'verValidaciones':
            $controller->verValidaciones();
            break;
        case 'verSolicitudes':
            $controller->verSolicitudes();
            break;
        case 'verPagoSolicitudes':
            $controller->verPagoSolicitudes();
            break;
        case 'verCompras':
            $controller->verCompras();
            break;
        case 'verComprasRecibidas':
            $controller->verComprasRecibidas();
            break;
        case 'buscaPagos':
            $controller->buscaPagos();
            break;
        case 'pagoFacturas':
            $idp = $_GET['idp'];
            $controller->pagoFacturas($idp);
            break;
        case 'buscaContrarecibos':
            $controller->buscaContrarecibos();
            break;
        case 'revAplicaciones':
            $controller->revAplicaciones();
            break;
        case 'dirVerFacturas':
            $mes = '';
            $vend = '';
            $anio = '2016';
            $controller->dirVerFacturas($mes, $vend, $anio);
            break;
        case 'buscaOC':
            if (!isset($_GET['fechaedo'])) {
                $fechaedo = '01.01.2016';
            } else {
                $fechaedo = $_GET['fechaedo'];
            }
            $controller->buscaOC($fechaedo);
            break;
        case 'deudores':
            $fechaedo = '01.01.2016';
            $banco = 'Bancomer';
            $controller->deudores($fechaedo, $banco);
            break;
        case 'transfer':
            $fechaedo = '01.01.2016';
            $bancoO = 'Bancomer';
            $controller->transfer($fechaedo, $bancoO);
            break;
        case 'facturapagomaestro':
            $maestro = $_GET['maestro'];
            $controller->facturapagomaestro($maestro);
            break;
        case 'verMaestros':
            $controller->verMaestros();
            break;
        case 'nuevo_maestro':
            $controller->nuevo_maestro();
            break;
        case 'asociarSKU':
            $controller->asociarSKU();
            break;
        case 'verReportes':
            $controller->verReportes();
            break;
        case 'reporteEmbarque':
            $idr = $_GET['idr'];
            $controller->reporteEmbarque($idr);
            break;
        case 'verFacturasFecha':
            $controller->verFacturasFecha();
            break;
        case 'verCambiosFechas':
            $controller->verCambiosFechas();
            break;
        case 'verRecepProcesadas':
            $controller->verRecepProcesadas();
            break;
        case 'verES2';
            if (isset($_GET['fechaini'])) {
                $fechaini = $_GET['fechaini'];
                $fechafin = $_GET['fechafin'];
                $impresion = $_GET['impresion'];
            } else {
                $fechaini = '';
                $fechafin = '';
                $impresion = 'no';
            }

            if ($impresion == 'no') {
                $controller->verES($fechaini, $fechafin);
            } elseif ($impresion = 'si') {
                echo 'Manda a imprimir';
                $controller->imprimeES($fechaini, $fechafin);
            }
            break;
        case 'sendXml':
            $controller->sendXml();
            break;
        case 'utilerias':
            $opcion = 0;
            $docp = '';
            $docf = '';
            $docd = '';
            $fechaIni='';
            $fechaFin='';
            $maestro = '';
            $controller->utilerias($opcion, $docp, $docf, $docd, $fechaIni, $fechaFin, $maestro);
            break;
        case 'verAuxSaldosCxc':
            if (isset($_GET['fechaini'])) {
                $fechaini = $_GET['fechaini'];
                $fechafin = $_GET['fechafin'];
                $impresion = $_GET['impresion'];
            } else {
                $fechaini = '';
                $fechafin = '';
                $impresion = 'no';
            }

            if ($impresion == 'no') {
                $controller->verAuxSaldosCxc($fechaini, $fechafin);
            } elseif ($impresion = 'si') {
                echo 'Manda a imprimir';
                $controller->imprimeES($fechaini, $fechafin);
            }
            break;
        case 'verListaDePrecios':
            if(isset($_GET['cliente'])){
                $cliente= $_GET['cliente'];    
            }else{
                $cliente = 'Inicial';
            }
            $controller->verListaDePrecios($cliente);
            break;
        case 'imprimirListaPrecios':
            $cl = $_GET['cl'];
            $controller->imprimirListaPrecios($cl);
            break;
        case 'ventas':
            $tipo = '';
            $controller_int->ventas($tipo);
            break;
        case 'cargaXLS':
            $controller_int->cargaXLS();
            break;
        case 'apolo':
            $id= isset($_GET['id'])? isset($_GET['id']):''; 
            $controller->apolo($id);
            break;
        default:
            header('Location: index.php?action=login');
            break;
    }
}
?>
<?php

//session_start();
////session_cache_limiter('private_no_expire');
require_once('app/model/pegaso.model.php');
require_once('app/fpdf/fpdf.php');
require_once('app/views/unit/commonts/numbertoletter.php');
require_once ('app/model/database.php');
require_once('app/model/wms.model.php');
require_once 'app/model/model.sql.php';
require_once('app/Classes/PHPExcel.php');
require_once('app/model/order.model.php');

class order_controller {
    var $contexto = "http://SERVIDOR:8081/pegasoFTC/app/";

    function load_template($title){
        $pagina = $this->load_page('app/views/master.wms.php');
        $header = $this->load_page('app/views/sections/s.header.php');
        $pagina = $this->replace_content('/\#HEADER\#/ms', $header, $pagina);
        $pagina = $this->replace_content('/\#TITLE\#/ms', $title, $pagina);
        return $pagina;
    }

    function load_templateL($title) {
        $pagina = $this->load_page('app/views/master.php');
        $header = $this->load_page('app/views/sections/s2.header.php');
        $pagina = $this->replace_content('/\#HEADER\#/ms', $header, $pagina);
        $pagina = $this->replace_content('/\#TITLE\#/ms', $title, $pagina);
        return $pagina;
    }

    private function load_page($page) {
        return file_get_contents($page);
    }

    private function view_page($html) {
        echo $html;
    }

    private function replace_content($in = '/\#CONTENIDO\#/ms', $out, $pagina) {
        return preg_replace($in, $out, $pagina);
    }

    function valInt($idint){
        $data= new orders;
        $sql = new intelisis;
        $wms = new wms;
        $info = $data->datosOrden($idint);
        $infoInt = $sql->valInt($info);
        if($infoInt['valCab'][0]['suc']> 0){
            $valWms=$wms->valWms($infoInt);
            foreach($valWms as $insInt){
                $movID='';
                if($insInt->VAL == 1 and empty($insInt->MOVID)){
                    $res= $wms->traeDatosInt($insInt->ID_INT_F);
                    $resInt=$sql->insertaVtaInt($res); /// despues de insertar la venta se tiene que actualizar
                    $valCab = $sql->sincCab($insInt->ID_INT_F);
                    $movID=$valCab[0]['movID'];
                    $valCab = $wms->sincCab($valCab);
                    return array("status"=>'ok', "mensaje"=>'Se creo el pedido'.$movID);
                }else{
                    return array("status"=>'no', "mensaje"=>'Fallo Validacion de productos');
                }
            }
        }else{
            return array("status"=>'ok', "mensaje"=>'No se encontro la determinante');
        }
    }

    function asigDet($idwms, $det, $cte, $comp){
        $data = new orders;
        $int = new intelisis;
        $determinante = $int->enviarA($cte, $det);
        $actDetInt = $int->actDetInt($cte, $det, $comp);
        $actDetWms = $data->actDetWms($cte, $det, $comp, $determinante);
        $res=$data->asigDet($idwms, $determinante);

        return $res;
    }

    function articulos($id){
        $data = new orders;
        $res = $data->articulos($id);
        return $res;
    }

    function ordenesW($tipo, $param){
        if (isset($_SESSION['user']) and $_SESSION['user']->CR == 5) {
            $orders = new orders;
            $pagina = $this->load_template('Ordenes walmart');
            $html = $this->load_page('app/views/pages/intelisis/p.ordenesWalmart.php');
            ob_start();
            $archivos = $orders->archivos($tipo, $param);
            $ordenes = array();
            if($tipo == 'f'){
                $ordenes = $orders->ordenesWalmart($param);
            }
            include 'app/views/pages/intelisis/p.ordenesWalmart.php';
            $table = ob_get_clean();
            $pagina = $this->replace_content('/\#CONTENIDO\#/ms', $table, $pagina);
            $this->view_page($pagina);
        } else {
            $e = "Favor de Iniciar Sesión";
            header('Location: index.php?action=login&e=' . urlencode($e));
            exit;
        }
    }

    function chgSta($file, $sta){
        $orders = new orders;
        $res=$orders->chgSta($file, $sta);
        return $res;
    }

    function revPedido($movID, $i){
        $sql = new intelisis;
        $order = new orders;
        $info = $order->revPedido($movID);
        $intelisis =$sql->revPedido($movID, $info);
        $act=$order->actPartidas($movID, $intelisis);
        if(count($intelisis)<count($info) and $i==0){
            //echo '<br/>entra en la insercion';
            $insInt=$sql->insParInt($movID, $info);
            $i++;
            $this->revPedido($movID, $i);
        }
        return array("int"=>count($intelisis), "wms"=>count($info));
    }
}
?>


<?php

//session_start();
////session_cache_limiter('private_no_expire');
require_once('app/model/pegaso.model.php');
require_once('app/fpdf/fpdf.php');
require_once('app/views/unit/commonts/numbertoletter.php');
require_once ('app/model/database.php');
require_once('app/model/wms.model.php');

class wms_controller {
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
        $header = $this->load_page('app/views/sections/s.header.php');
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

    function wms_menu($opc){
        //session_cache_limiter('private_no_expire');
        ob_start();
        if (isset($_SESSION['user'])){
            if(substr($opc,0,1) == 'p'){
                $this->wms_prod($op='');die();
            }elseif(substr($opc,0,1) =='c'){
                $this->wms_comp($op='', $param=substr($opc,1));die();
            }elseif(substr($opc,0,1) == 'a'){
                $this->wms_alma($op='');die();
            }elseif(substr($opc,0,1) == 'm'){
                $this->wms_mov($op='');die();
            }elseif(substr($opc,0,6) == 'newMov'){
                $ver=substr($opc,6);
                $this->wms_newMov($opc='', $ver);die();
            }elseif(substr($opc,0,6) == 'ediMov'){
                $this->wms_newMov($op=substr($opc,7), $ver='');die();
            }elseif (substr($opc, 0,6)=='detCom'){
                $this->wms_detComp($op=substr($opc,7));die();
            }elseif (substr($opc, 0,6)=='detMov'){
                $this->wms_detMov($op=substr($opc, 7));die();
            }
            $pagina = $this->load_template('Menu Almacen');
            //$html = '';//$this->load_page('');
            $table = ob_get_clean();
            $algo = 'Que paso?';
            $pagina = $this->replace_content('/\#CONTENIDO\#/ms', $table, $pagina);
            $this->view_page($pagina);
        } else {
            $e = "Favor de Revisar sus datos";
            header('Location: index.php?action=login&e=' . urlencode($e));
            exit;
        }
    }

    function wms_prod($op){
        //session_cache_limiter('private_no_expire');
        if (isset($_SESSION['user'])) {
            $data = new wms;
            $pagina = $this->load_template('Productos');
            $html = $this->load_page('app/views/pages/almacenes/p.productos.php');
            ob_start();
            $info = $data->productos($op);
            include 'app/views/pages/almacenes/p.productos.php';
            $table = ob_get_clean();
            $pagina = $this->replace_content('/\#CONTENIDO\#/ms', $table, $pagina);
            $this->view_page($pagina);
            
        } else {
            $e = "Favor de Iniciar Sesión";
            header('Location: index.php?action=login&e=' . urlencode($e));
            exit;
        }
    }

    function actProd($cve, $lg, $an, $al, $p, $ou){
        if (isset($_SESSION['user'])) {
            $data = new wms;
            $exec=$data->actProd($cve, $lg, $an, $al, $p, $ou);
            return $exec;
        }
    }

    function wms_comp($op, $param){
        //session_cache_limiter('private_no_expire');
        if (isset($_SESSION['user'])) {
            $data = new wms;
            $pagina = $this->load_template('Componentes');
            $html = $this->load_page('app/views/pages/almacenes/p.componentes.php');
            ob_start();
            $info = $data->componentes($op, $param);
            $compP= $data->componentes($op=" WHERE STATUS = 'Activo' and ID_TIPO=2", $param='');
            $alm =  $data->almacenes($op= " WHERE STATUS = 'Activo'");
            $tc  =  $data->tipoComp('componente');
            $prod=  $data->productos($op= " WHERE STATUS = 'Alta' and TIPO_INT = 'Lote'");
            include 'app/views/pages/almacenes/p.componentes.php';
            $table = ob_get_clean();
            $pagina = $this->replace_content('/\#CONTENIDO\#/ms', $table, $pagina);
            $this->view_page($pagina);
            
        } else {
            $e = "Favor de Iniciar Sesión";
            header('Location: index.php?action=login&e=' . urlencode($e));
            exit;
        }
    }

    function addComp($et, $desc, $selT, $lg, $an, $al, $alm, $ob, $fact){
        if(isset($_SESSION['user'])){
            $data = new wms;
            $exec=$data->addComp($et, $desc, $selT, $lg, $an, $al, $alm, $ob, $fact);
            return $exec;
        }
    }

    function cpComp($cns, $can, $id, $s, $f, $sp){
        if(isset($_SESSION['user'])){
            $data = new wms;
            $exec=$data->cpComp($cns, $can, $id, $s, $f, $sp);
            return $exec;
        }
    }
    

    function wms_alma($op){
        //session_cache_limiter('private_no_expire');
        if (isset($_SESSION['user'])) {
            $data = new wms;
            $pagina = $this->load_template('Componentes');
            $html = $this->load_page('app/views/pages/almacenes/p.alma.php');
            ob_start();
            $alm=$data->almacenes($op);
            $info=$data->componentes($op, $param='');
            //$tc=$data->tipoComp('componente');
            include 'app/views/pages/almacenes/p.alma.php';
            $table = ob_get_clean();
            $pagina = $this->replace_content('/\#CONTENIDO\#/ms', $table, $pagina);
            $this->view_page($pagina);
            
        } else {
            $e = "Favor de Iniciar Sesión";
            header('Location: index.php?action=login&e=' . urlencode($e));
            exit;
        }
    }


    function wms_mov($op){
        //session_cache_limiter('private_no_expire');
        if (isset($_SESSION['user'])) {
            $data = new wms;
            $pagina = $this->load_template('Componentes');
            $html = $this->load_page('app/views/pages/almacenes/p.movimientos.php');
            ob_start();
            $alm=$data->almacenes($op=" WHERE STATUS = 'Activo'");
            $comp=$data->componentes($op=" WHERE STATUS = 'Activo'", $param='');
            $info=$data->movimientos($op=" ");
            include 'app/views/pages/almacenes/p.movimientos.php';
            $table = ob_get_clean();
            $pagina = $this->replace_content('/\#CONTENIDO\#/ms', $table, $pagina);
            $this->view_page($pagina);       
        } else {
            $e = "Favor de Iniciar Sesión";
            header('Location: index.php?action=login&e=' . urlencode($e));
            exit;
        }
    }

    function wms_newMov($op, $ver){
        //session_cache_limiter('private_no_expire');
        $a='';$datos=array();$compA=array();
        if (isset($_SESSION['user'])) {
            $data = new wms;
            $pagina = $this->load_templateL('Componentes');
            $html = $this->load_page('app/views/pages/almacenes/p.newMov.php');
            ob_start();
            $partidas=array();
            $mov=$op; 
            if($mov!=''){
                $partidas=$data->movimiento($mov);
                $compA = $data->componentes($op=" WHERE STATUS = 'Activo' and ID_TIPO=1 and id_compp = (select max(compp) from ftc_almacen_mov amov where amov.mov =".$mov." ) ", $param='');

            }
            if (substr($ver,0,2)=="v2"){/// se trae solo la relacion de componente primario con componente secundario. Array ( [0] => v2 [1] => t [2] => e [3] => a [4] => 1 [5] => compp [6] => 1 ) 
                $p=explode(":", $ver); $ver=$p[0];$t=$p[2];$al=$p[4];$c=$p[6];
                $a= ' and ID_compP = '.$c;
                $datos = $data->datos($t, $al, $c);
            }
            $alm=$data->almacenes($op=" WHERE STATUS = 'Activo'");
            $compP=$data->componentes($op=" WHERE STATUS = 'Activo' and ID_TIPO=2", $param='');
            if(count($compA) <=0){
                $compA=$data->componentes($op=" WHERE STATUS = 'Activo' and ID_TIPO=1 ".$a, $param='');
            }
            $prod=$data->productos($op= " WHERE STATUS = 'Alta' and TIPO_INT = 'Lote'");
            $uniE=$data->unidades($op="   WHERE STATUS = 1 AND TIPO = 'E' order by factor asc ");
            $uniS=$data->unidades($op="   WHERE STATUS = 1 AND TIPO = 'S' order by factor asc ");
            //print_r($compS);
            include 'app/views/pages/almacenes/p.newMov.php';
            $table = ob_get_clean();
            $pagina = $this->replace_content('/\#CONTENIDO\#/ms', $table, $pagina);
            $this->view_page($pagina);
        } else {
            $e = "Favor de Iniciar Sesión";
            header('Location: index.php?action=login&e=' . urlencode($e));
            exit;
        }   
    }    

    function addMov($tipo, $alm, $compP, $compS, $prod, $uni, $cant, $col, $mov, $pza){
        if($_SESSION['user']){
            $data = new wms;
            $exec = $data->addMov($tipo, $alm, $compP, $compS, $prod, $uni, $cant, $col, $mov, $pza);
            return $exec;
        }
    }

    function cpLin($base, $cs){
        if($_SESSION['user']){
            $data = new wms;
            $exec = $data->cpLin($base, $cs);
            return $exec;
        }
    }

    function canMov($mov, $mot, $t){
        if($_SESSION['user']){
            $data = new wms;
            $exec = $data->canMov($mov, $mot, $t);
            return $exec;
        }
    }

    function delMov($idMov, $tp){
        if($_SESSION['user']){
            $data = new wms;
            $exec = $data->delMov($idMov, $tp);
            return $exec;
        }   
    }

    function asocia($cs, $cp, $t, $e){
        if($_SESSION['user']){
            $data = new wms;
            $exec = $data->asocia($cs, $cp, $t, $e);
            return $exec;
        }       
    }

    function wms_detComp($opc){
        //session_cache_limiter('private_no_expire');
        if (isset($_SESSION['user'])) {
            $data = new wms;
            $pagina = $this->load_templateL('Detalle del componente');
            $html = $this->load_page('app/views/pages/almacenes/p.detComp.php');
            ob_start();
            $info = $data->componentes($op=' where id_comp = '.$opc , $param='');
            $det = $data->detMov($opc);
            //$compP= $data->componentes($op=" WHERE STATUS = 'Activo' and ID_TIPO=2", $param='');
            //$alm =  $data->almacenes($op= " WHERE STATUS = 'Activo'");
            //$tc  =  $data->tipoComp('componente');
            //$prod=  $data->productos($op= " WHERE STATUS = 'Alta' and TIPO_INT = 'Lote'");
            include 'app/views/pages/almacenes/p.detComp.php';
            $table = ob_get_clean();
            $pagina = $this->replace_content('/\#CONTENIDO\#/ms', $table, $pagina);
            $this->view_page($pagina);
            
        } else {
            $e = "Favor de Iniciar Sesión";
            header('Location: index.php?action=login&e=' . urlencode($e));
            exit;
        }
    }

    function wms_detMov($op){
        if (isset($_SESSION['user'])) {
            $data = new wms;
            $pagina = $this->load_templateL('Detalle del componente');
            $html = $this->load_page('app/views/pages/almacenes/p.detMov.php');
            ob_start();
            $info = $data->detalleMov($op);
            //$det = $data->detMov($opc);
            include 'app/views/pages/almacenes/p.detMov.php';
            $table = ob_get_clean();
            $pagina = $this->replace_content('/\#CONTENIDO\#/ms', $table, $pagina);
            $this->view_page($pagina); 
        } else {
            $e = "Favor de Iniciar Sesión";
            header('Location: index.php?action=login&e=' . urlencode($e));
            exit;
        }
    }
}
?>


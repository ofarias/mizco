<?php 

require_once('app/model/pegaso.model.php');
require_once('app/fpdf/fpdf.php');
require_once('app/views/unit/commonts/numbertoletter.php');
require_once 'app/model/model.sql.php';
require_once('app/model/wms.model.php');
require_once('app/model/order.model.php');


class sql_controller {

	function load_template($title = 'Sin Titulo') {
        $pagina = $this->load_page('app/views/master.php');
        $header = $this->load_page('app/views/sections/s.header.php');
        $pagina = $this->replace_content('/\#HEADER\#/ms', $header, $pagina);
        $pagina = $this->replace_content('/\#TITLE\#/ms', $title, $pagina);
        return $pagina;
    }

    function load_templateL($title = 'Sin Titulo') {
        $pagina = $this->load_page('app/views/master.php');
        $header = $this->load_page('app/views/sections/header.php');
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

	function ventas($tipo){
		$data = new intelisis;
		$info = $data->ventas($tipo);
	}

	function cargaXLS(){
		if (isset($_SESSION['user'])) {
            $pagina = $this->load_template('Modifica Usuario');
            $html = $this->load_page('app/views/pages/intelisis/p.cargaXLS.php');
            include 'app/views/pages/intelisis/p.cargaXLS.php';
            $table = ob_get_clean();
            $pagina = $this->replace_content('/\#CONTENIDO\#/ms', $table, $pagina);
            $this->view_page($pagina);
        } else {
            $e = "Favor de Iniciar Sesión";
            header('Location: index.php?action=login&e=' . urlencode($e));
            exit;
        }
	}

	function cargaSQL($files2upload){
        $ids='';
		if (isset($_SESSION['user'])) {            
            $data = new intelisis;
            $wms = new wms;
            $order = new orders;
            $errors=array();
            $valid_formats = array("xls", "XLS", "XLSX", "xlsx");
            $max_file_size = 1024 * 10000; 
            $target_dir="C:/xampp/htdocs/uploads/xls/remisiones/";
            if(!file_exists($target_dir)){
            	mkdir($target_dir, null, true);
            }
            $count = 0;
            $respuesta = 0;
            foreach ($_FILES['files']['name'] as $f => $name) {	
            	$respuesta++;
            	$name = date("Y.m.d_h.i.s_").$name;
            	if ($_FILES['files']['error'][$f] == 4) {
                    continue;
                }
                if ($_FILES['files']['error'][$f] == 0) {
                    if ($_FILES['files']['size'][$f] > $max_file_size) {
                        $message[] = "$name es demasiado grande para subirlo.";
                        continue; 
                    }elseif (!in_array(pathinfo($name, PATHINFO_EXTENSION), $valid_formats)) {
                        $message[] = "$name no es un archivo permitido.";
                        continue; 
                    } else { 
                        if(move_uploaded_file($_FILES["files"]["tmp_name"][$f], $target_dir.$name)){
                           	$count++;
                            $tipo=$wms->valXLS($target_dir.$name);
                            if($tipo['tipo'] == 'Salida Diversa'){
                                $idf=$order->regFile($name, $tipo['tipo']);
                                $res=$data->insertaMovInv($tipo['info']);
                                $regWms=$wms->insertaMovInt($tipo['info'],$tipo['tipo'], $res['movid'], $res['idint']);
                            }elseif($tipo['tipo']=='walmart'){
                                $idf=$order->regFile($name, $tipo['tipo']);
                                $regWms=$wms->insertaVtaInt($tipo['info'],$tipo['tipo'],$idf);
                                $find = $data->findCdn($regWms);
                                $act = $order->actCdn($find);
                                foreach ($regWms['cabecera'] as $k){$ids.=$k->ID_INT_F.',';}
                                    $ids = substr($ids, 0 , strlen($ids)-1);
                                    $regWms=$wms->validaInt($ids);
                                    $valInt=$data->valInt($regWms);
                                    $valWms=$wms->valWms($valInt);
                                //echo 'Cuantos validados: '.count($valWms);
                                foreach($valWms as $insInt){
                                    if($insInt->VAL == 1 and empty($insInt->MOVID)){
                                        $res= $wms->traeDatosInt($insInt->ID_INT_F);
                                        $resInt=$data->insertaVtaInt($res); /// despues de insertar la venta se tiene que actualizar
                                        $valCab = $data->sincCab($insInt->ID_INT_F);
                                        $valCab = $wms->sincCab($valCab);
                                    }
                                }
                                $this->redirect("w");
                            }elseif($tipo['tipo']=='transferencias'){
                                $res= array("docs"=>0, "errors"=>0);
                            }
                            else{
                                $idf=$order->regFile($name, 'ML');
                                $res=$data->insertaVentas($target_dir.$name);
                            }
                    	}
                	}
            	}
        	}
        	$docs=$res['docs'];
        	$errors=$res['errors'];
            echo "Archivos cargados con exito: $count-$respuesta <br/>";
            echo "Remisiones creadas: $docs <br/>";
            echo "Remisiones con Error: $errors <br/>";
            //echo "Errores:";
            $this->cargaXLS();
        } else {
            $e = "Favor de Iniciar Sesión";
            header('Location: index.php?action=login&e=' . urlencode($e));
            exit;
        }	
	}

    function detalleDoc($docp, $docf){
        $data = new intelisis;
        echo '<br/> '.$docf.''.$docp.'<br/>';
        $info= $data->detalleDoc($docp, $docf);
    }

    function docCP(){
        $data= new intelisis;
        $res = $data->docCP();
        return $res;
    }

    function redirect($opc){
        $redireccionar = $opc;
        $html = $this->load_page('app/views/pages/intelisis/p.redirectform.php');
        ob_start();
        include 'app/views/pages/intelisis/p.redirectform.php';
    }

    function OrdenesWalmart(){
        if (isset($_SESSION['user']) and $_SESSION['user']->CR == 5) {
            $orders = new orders;
            $pagina = $this->load_template('Ordenes walmart');
            $html = $this->load_page('app/views/pages/intelisis/p.ordenesWalmart.php');
            ob_start();
            $archivos = $orders->archivos($tipo = '', $param = '');
            //$ordenes = $orders->ordenesWalmart($param='');
            $ordenes = array();
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

    function enviarA($cte){
        $int = new intelisis;
        $info = $int->enviarA($cte, $det='');
        return $info;
    }
}
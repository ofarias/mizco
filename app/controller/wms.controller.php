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

    function wms_menu($opc){
        //session_cache_limiter('private_no_expire');
        ob_start();
        if (isset($_SESSION['user'])){
            if(substr($opc,0,1) == 'p'){
                $this->wms_prod($op=substr($opc,1,2));die();
            }elseif(substr($opc,0,1) =='c'){
                $this->wms_comp($op='', $param=substr($opc,1));die();
            }elseif(substr($opc,0,1) == 'a'){
                $this->wms_alma($op='');die();
            }elseif(substr($opc,0,1) == 'm'){
                $this->wms_mov($op='', $param=substr($opc,1));die();
            }elseif(substr($opc,0,6) == 'newMov'){
                $ver=substr($opc,6);
                $this->wms_newMov($opc='', $ver);die();
            }elseif(substr($opc,0,6) == 'ediMov'){
                $this->wms_newMov($op=substr($opc,7), $ver='');die();
            }elseif (substr($opc, 0,6)=='detCom'){
                $this->wms_detComp($op=substr($opc,7));die();
            }elseif (substr($opc, 0,6)=='detMov'){
                $this->wms_detMov($op=substr($opc, 7), $t=$opc);die();
            }elseif(substr($opc, 0,1) =='r'){
                $this->wms_report($opc='', $param='');
            }elseif (substr($opc,0,1)=='o'){
                $this->wms_ordenes($opc=substr($opc,1));die();
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
        if (isset($_SESSION['user'])) {
            $data = new wms;
            $dataInt = new intelisis;
            $pagina = $this->load_template('Productos');
            $html = $this->load_page('app/views/pages/almacenes/p.productos.php');
            ob_start();
            if(substr($op,0,1) == 'a'){
                $intelisis = $dataInt->prodInt($t = substr($op,1,1));
                $refresh = $data->refresh($intelisis['data']);
                if(count($intelisis['datos']) >0 ){
                    $exist=$data->inExistInt($intelisis['datos']);
                }
            }
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
            if(@$info['tipo']=='x'){
                return $info;
                exit();
            }
            $infoL=$data->compLib($op=" WHERE STATUS = 'Activo' and id_tipo = 2 and Disponible = 'si' ", '');
            $infoT=$data->compLib($op=" WHERE STATUS = 'Activo' and id_tipo = 1 and Disponible = 'si' ", '');
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

    function wms_mov($op, $param){
        if (isset($_SESSION['user'])) {
            $data = new wms; $t='';
            $pagina = $this->load_template('Componentes');
            $html = $this->load_page('app/views/pages/almacenes/p.movimientos.php');
            ob_start();
            //echo 'Valor de parametros '.$param.', fin del valor';
            if(!empty($param)){
                $t=explode(",",$param);
                $t=$t[0]; $t=explode(":",$t); $t=$t[1];
            }
            $info=$data->movimientos($op=" ", $param);
            $alm=$data->almacenes($op=" WHERE STATUS = 'Activo'");
            $comp=$data->componentes($op=" WHERE STATUS = 'Activo'", $param='');
            $usuarios=$data->usuarios($op = " WHERE STATUS = ''");
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
        $a='';$datos=array();$compA=array();$p='';$param='';$fa='';
        if (isset($_SESSION['user'])) {
            $data = new wms;
            $pagina = $this->load_templateL('Componentes');
            $html = $this->load_page('app/views/pages/almacenes/p.newMov.php');
            ob_start();
            $partidas=array();
            $mov=$op; 
            if($mov!=''){
                $tipo = strrpos($op,":")? explode(":",$op):'';
                $mov = $tipo[0];
                $tipo = $tipo[1];
                $partidas=$data->movimiento($mov, $tipo);
                $compA = $data->componentes($op=" WHERE STATUS = 'Activo' and ID_TIPO=1 and id_compp = (select max(compp) from ftc_almacen_mov amov where amov.mov =".$mov." ) ", $param='');
            }
            if (substr($ver,0,2)=="v2"){/// se trae solo la relacion de componente primario con componente secundario. Array ( [0] => v2 [1] => t [2] => e [3] => a [4] => 1 [5] => compp [6] => 1 )
                $p=explode(":", $ver); $ver=$p[0];$t=$p[2];$al=$p[4];$c=$p[6];
                //echo 'tamaño de p:'. count($p);
            if($t=='s'){
                $this->wms_newMovSalMan($p);
                exit();
            }
                $fa=' and ID_ALM = '.$al;
                if(!empty($c)){
                    $a= ' and ID_compP = '.$c.$fa;
                }
                $datos = $data->datos($t, $al, $c);
            }
            $alm=$data->almacenes($op=" WHERE STATUS = 'Activo'");
            $compP=$data->componentes($op=" WHERE STATUS = 'Activo' and ID_TIPO=2 ".$fa, $param='');
            
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

    function wms_newMovSalMan($p){
        $partidas =array();$movimiento=array();
        $mov='';$ver=$p[0];$t=$p[2];$al=$p[4];$cp=@$p[6];$cs=@$p[8];$prod=@$p[10].':'.@$p[11].':'.@$p[12];$pr=@$p[10];$fol=@$p[14]; $ser=@$p[15];
        $data=new wms;
        $pagina = $this->load_templateL('Componentes');
        $html = $this->load_page('app/views/pages/almacenes/p.newMovSal.php');
        ob_start();
        $comp=$data->movs($al, $cp, $cs, $pr);
        if($cp!='' and $cp !='a'){
            $partidas=$data->compSal($cp, $pr);
        }
        if($fol != 'x' and !empty($fol)){
            $movimiento = $data->movSal($fol);
        }
        include 'app/views/pages/almacenes/p.newMovSal.php';
        $table = ob_get_clean();
        $pagina = $this->replace_content('/\#CONTENIDO\#/ms', $table, $pagina);
        $this->view_page($pagina);
    }

    function addMov($tipo, $alm, $compP, $compS, $prod, $uni, $cant, $obs, $mov, $pza, $cat){
        if($_SESSION['user']){
            $data = new wms;
            $exec = $data->addMov($tipo, $alm, $compP, $compS, $prod, $uni, $cant, $obs, $mov, $pza, $cat);
            return $exec;
        }
    }

    function exeSal($datos, $fol){
        if($_SESSION['user']){
            $data = new wms;
            $exec=$data->exeSal($datos, $fol);
            return $exec;
        }
    }

    function finSal($fol){
        if($_SESSION['user']){
            $data = new wms;
            $exec=$data->finSal($fol);
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

    function delMov($idMov, $tp, $e){
        if($_SESSION['user']){
            $data = new wms;
            $exec = $data->delMov($idMov, $tp, $e);
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

    function wms_detMov($op, $t){
        if (isset($_SESSION['user'])) {
            $data = new wms;
            $pagina = $this->load_templateL('Detalle del componente');
            $html = $this->load_page('app/views/pages/almacenes/p.detMov.php');
            ob_start();
            $t = explode(":",$op);
            $info = $data->detalleMov($op=$t[0],$t = $t[1]);
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

    function prodAuto($prod){    
        $data = new wms;
        $exec = $data->prodAuto($prod);
        return $exec;
    }

    function compAuto($comp){    
        $data = new wms;
        $exec = $data->compAuto($comp);
        return $exec;
    }

    function valProd($prod){
        $data = new wms;
        $exec = $data->valProd($prod);
        return $exec;   
    }

    function wms_report($op, $param){
        if (isset($_SESSION['user'])) {
            $data = new wms;
            $pagina = $this->load_templateL('Reportes');
            $html = $this->load_page('app/views/pages/almacenes/p.reportes.php');
            ob_start();
            include 'app/views/pages/almacenes/p.reportes.php';
            $table = ob_get_clean();
            $pagina = $this->replace_content('/\#CONTENIDO\#/ms', $table, $pagina);
            $this->view_page($pagina); 
        } else {
            $e = "Favor de Iniciar Sesión";
            header('Location: index.php?action=login&e=' . urlencode($e));
            exit;
        }
    }

    function report($t, $out){
        if (isset($_SESSION['user'])) {
            $data = new wms;
            ob_start();
            $delim=date('d-m-Y H_i_s');
            $info = $data->infoRep($t, $out);
            if($out=='x' and $t=='pc'){
                $res=$this->repXlsPC($info);
                return $res;
            }elseif ($out == 'p' and $t=='pc') {
                $res=$this->repPdfPC($info, $delim);
                return array('status' => 'ok', 'completa'=>'..\\..\\Reportes_Almacen\\Reporte Productos del Componente'.$delim.'.pdf' );
            }elseif ($out=='x' and $t=='pp'){
                $res=$this->repXlsPP_v2($info, $delim);
                return $res;
            }elseif ($out=='p' and $t=='pp'){
                $res=$this->repPdfPP($info, $delim);
                return array('status' => 'ok', 'completa'=>'..\\..\\Reportes_Almacen\\Reporte Posicion de productos'.$delim.'.pdf' );
                return $res;
            }elseif ($out=='x' and $t=='da') {
                $res=$this->repXlsDa($info, $delim);
                return $res;
            }elseif ($out=='p' and $t=='da') {
                $res=$this->repPdfDa($info, $delim);
                return $res;
            }elseif($out=='x' and $t=='prod'){
                $res=$this->repProd();
                return $res; 
            }
        } else {
            $e = "Favor de Iniciar Sesión";
            header('Location: index.php?action=login&e=' . urlencode($e));
            exit;
        }
    }

    function repXlsPC($info){
        $usuario = $_SESSION['user']->NOMBRE;   
        $xls= new PHPExcel();
        $data = new wms;
        $col = 'A';$ln=10; $i = 0;
            foreach ($info['primary'] as $row) {
                $i++;
                $ln++;
                $xls->setActiveSheetIndex()
                        ->setCellValue($col.$ln,$i)
                        ->setCellValue(++$col.$ln,$row->ETIQUETA)
                        ->setCellValue(++$col.$ln,$row->TIPO)
                        ->setCellValue(++$col.$ln,$row->LARGO.' x '.$row->ANCHO.' x '.$row->ALTO.' '.$row->MEDICION)
                        ->setCellValue(++$col.$ln,$row->ALMACEN)
                        ->setCellValue(++$col.$ln,$row->OBS)
                        ->setCellValue(++$col.$ln,$row->STATUS)
                ;
                $linea = "A".$ln;
                $xls->getActiveSheet()->getStyle("A".$ln.':'.$col.$ln)->applyFromArray(
                    array(
                            'font'=> array(
                                'bold'=>true
                            ),
                            'borders'=>array(
                                'allborders'=>array(
                                    'style'=>PHPExcel_Style_Border::BORDER_THIN
                                )
                            ), 
                            'fill'=>array( 
                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,             
                                    'color'=> array('rgb' => 198, 255, 251)
                            )   
                        )
                    );

                $col="A";
                $ln++;
                $xls->setActiveSheetIndex()
                    ->setCellValue($col.$ln,"Id")
                    ->setCellValue(++$col.$ln,"Etiqueta")
                    ->setCellValue(++$col.$ln,"Descripción")
                    ->setCellValue(++$col.$ln,"Tipo")
                    ->setCellValue(++$col.$ln,"Largo")
                    ->setCellValue(++$col.$ln,"Ancho")
                    ->setCellValue(++$col.$ln,"Alto")
                    ->setCellValue(++$col.$ln,"Almacen")
                    ->setCellValue(++$col.$ln,"Estado")
                    
                ;
                $xls->getActiveSheet()->getStyle("A".$ln.':'.$col.$ln)->applyFromArray(
                    array(
                            'font'=> array(
                                'bold'=>true
                            ),
                            'borders'=>array(
                                'allborders'=>array(
                                    'style'=>PHPExcel_Style_Border::BORDER_THIN
                                )
                            ), 
                            'fill'=>array( 
                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,             
                                    'color'=> array('rgb' => FF0000)
                            )   
                        )
                    );
                $col="A";
                $det=0;
                foreach ($info['secondary'] as $key){
                    if($key->ID_COMPP == $row->ID_COMP){
                        $det++;
                        $ln++;
                        $in=0;$out=0;$ex=0;
                        $exist=$data->exist($key->ID_COMP, 'pc');
                        $xls->setActiveSheetIndex()
                            ->setCellValue($col.$ln,  $key->ID_COMP)
                            ->setCellValue(++$col.$ln,$key->ETIQUETA)
                            ->setCellValue(++$col.$ln,$key->DESC)
                            ->setCellValue(++$col.$ln,$key->TIPO)
                            ->setCellValue(++$col.$ln,$key->LARGO)
                            ->setCellValue(++$col.$ln,$key->ANCHO)
                            ->setCellValue(++$col.$ln,$key->ALTO)
                            ->setCellValue(++$col.$ln,$key->ALMACEN)
                            ->setCellValue(++$col.$ln,$key->STATUS)
                        ;       
                        $col="A";

                        if(count($exist)>0){
                            $ln++;
                            $col="C";
                            $xls->setActiveSheetIndex()
                                ->setCellValue($col.$ln,"Producto")
                                ->setCellValue(++$col.$ln,"Entradas")
                                ->setCellValue(++$col.$ln,"Salidas")
                                ->setCellValue(++$col.$ln,"Existencias")
                            ;
                            $xls->getActiveSheet()->getStyle("C".$ln.':'.$col.$ln)->applyFromArray(
                            array(
                                    'font'=> array(
                                        'bold'=>true
                                    ),
                                    'borders'=>array(
                                        'allborders'=>array(
                                            'style'=>PHPExcel_Style_Border::BORDER_THIN
                                        )
                                    ), 
                                    'fill'=>array( 
                                            'type' => PHPExcel_Style_Fill::FILL_SOLID,             
                                            'color'=> array('rgb' => FFFE00)
                                    )   
                                )
                            );
                            foreach($exist as $mov){
                                $ln++;
                                $col="C";
                                $xls->setActiveSheetIndex()
                                   ->setCellValue($col.$ln,  $mov->PROD)
                                   ->setCellValue(++$col.$ln,$mov->ENTRADAS)
                                   ->setCellValue(++$col.$ln,$mov->SALIDAS)
                                   ->setCellValue(++$col.$ln,$mov->ENTRADAS - $mov->SALIDAS)
                                ;
                                $xls->getActiveSheet()->getStyle("C".$ln.':'.$col.$ln)->applyFromArray(
                                array(
                                        'font'=> array(
                                            'bold'=>true
                                        ),
                                        'borders'=>array(
                                            'allborders'=>array(
                                                'style'=>PHPExcel_Style_Border::BORDER_THIN
                                            )
                                        ), 
                                        'fill'=>array( 
                                                'type' => PHPExcel_Style_Fill::FILL_SOLID,             
                                                'color'=> array('rgb' => FFFFCE)
                                        )   
                                    )
                                );
                                $col="A";
                            }
                        }    
                    }
                }

                if($det== 0){
                    $ln++;
                    $xls->setActiveSheetIndex()
                        ->setCellValue('A'.$ln,"No se encontraron componentes secundarios de este componente")
                    ;
                    $col="A";
                }
            }
            
            $xls->setActiveSheetIndex()
                ->setCellValue('A1', "IMPORTADORA MIZCO SA DE CV")
                ->setCellValue('A2', "Reporte de Posición de productos ")
                //->setCellValue('A3',  "")
                ->setCellValue('A4', "Elaborado por: ")
                ->setCellValue('B4', $usuario)
                ->setCellValue('A5', "Fecha de Elaboracion: ")
                ->setCellValue('B5', date("d-m-Y H:i:s" ) )
                ->setCellValue('A6', "Total Componentes Primarios:")
                ->setCellValue('B6', count($info['primary']))
                ->setCellValue('A7', "Total Componentes Secundarios:")
                ->setCellValue('B7', count($info['secondary']))
                
            ;
            /// CAMBIANDO EL TAMAÑO DE LA LINEA.
            $col = 'A';
            $xls->getActiveSheet()->getColumnDimension($col)->setWidth(15);
            $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(15);
            $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(15);
            $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(20);
            $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(25);
            $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(15);
            $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(20);
            $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(20);
            $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(20);
            $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(20);

            $xls->getActiveSheet()->getColumnDimension('B')->setWidth(20);
            /// Unir celdas
            $xls->getActiveSheet()->mergeCells('A1:O1');
            // Alineando
            $xls->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal('center');
            /// Estilando
            $xls->getActiveSheet()->getStyle('A1')->applyFromArray(
                array('font' => array(
                        'size'=>20,
                    )
                )
            );
            $xls->getActiveSheet()->getStyle('I10:I102')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $xls->getActiveSheet()->mergeCells('A3:F3');
            $xls->getActiveSheet()->getStyle('D3')->applyFromArray(
                array('font' => array(
                        'size'=>15,
                    )
                )
            );

            $xls->getActiveSheet()->getStyle('A3:D3')->applyFromArray(
                array(
                    'font'=> array(
                        'bold'=>true
                    ),
                    'borders'=>array(
                        'allborders'=>array(
                            'style'=>PHPExcel_Style_Border::BORDER_THIN
                        )
                    )
                )
            );
            $ruta='C:\\xampp\\htdocs\\Reportes_Almacen\\';
                if(!file_exists($ruta) ){
                    mkdir($ruta);
                }
                $nom='Productos del componente '.date("d-m-Y H_i_s").'_'.$usuario.'.xlsx';
                $x=PHPExcel_IOFactory::createWriter($xls,'Excel2007');
            /// salida a descargar
                $x->save($ruta.$nom);
                ob_end_clean();
                return array("status"=>'ok',"nombre"=>$nom, "ruta"=>$ruta, "completa"=>'..\\..\\Reportes_Almacen\\'.$nom, "tipo"=>'x');
                
    }

    function repPdfPC($info, $delim){
        $usuario = $_SESSION['user']->NOMBRE;
        $data = new wms;   
        $pdf = new FPDF('P', 'mm', 'Letter');
        $pdf->AddPage();
        $pdf->Image('app/views/images/LOGOSELECT.jpg', 5, 5, 30, 28);
        $pdf->SetFont('Arial', 'B', 10);
        
        $pdf->SetFont('Arial', 'I',10);
        $pdf->Ln(28);
        $pdf->SetX(10);
        $pdf->write(6, "Elaborado por :". $usuario. " el ".date("d-m-Y h:i:s")."\n");
        $pdf->write(6, "Total de componentes Primarios :". count($info['primary'])."\n");
        $pdf->write(6, "Total de componentes Secundarios :". count($info['secondary'])."\n");        

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        foreach ($info['primary'] as $pr) {
            $pdf->Cell(5, 8, $pr->ID_COMP, 1);
            $pdf->Cell(15, 8, $pr->ETIQUETA, 1);
            $pdf->Cell(40, 8, $pr->TIPO, 1);
            $pdf->Cell(30, 8, $pr->LARGO.' x '.$pr->ANCHO.' x '.$pr->ALTO.' '.$pr->MEDICION, 1);
            $pdf->Cell(18, 8, $pr->ALMACEN, 1);
            $pdf->Cell(50, 8, substr($pr->PRODUCTOS, 0 , 25), 1);
            $pdf->Cell(20, 8, $pr->STATUS, 1);
            $pdf->Ln();
            //$pdf->Ln();
            $pdf->SetFont('Arial', 'B', 7);
            $ctr=0;
            foreach($info['secondary'] as $sc){
                if($sc->ID_COMPP == $pr->ID_COMP){
                    if($ctr== 0){
                        $pdf->Cell(5, 8, "Id", 1);
                        $pdf->Cell(15, 8, "Etiqueta", 1);
                        $pdf->Cell(15, 8, "Descripcion", 1);
                        $pdf->Cell(30, 8, "Tipo", 1);
                        $pdf->Cell(10, 8, "Largo", 1);
                        $pdf->Cell(10, 8, "Ancho", 1);
                        $pdf->Cell(10, 8, "Alto", 1);
                        $pdf->Cell(15, 8, "Almacen", 1);
                        $pdf->Cell(15, 8, "Estado", 1);
                        $pdf->Ln();
                        $ctr++;
                    }
                    $pdf->Cell(5, 8,$sc->ID_COMP , 1);
                    $pdf->Cell(15, 8,$sc->ETIQUETA , 1);
                    $pdf->Cell(15, 8,$sc->DESC , 1);
                    $pdf->Cell(30, 8,substr($sc->TIPO,0,18), 1);
                    $pdf->Cell(10, 8,$sc->LARGO  , 1);
                    $pdf->Cell(10, 8,$sc->ANCHO , 1);
                    $pdf->Cell(10, 8,$sc->ALTO , 1);
                    $pdf->Cell(15, 8,$sc->ALMACEN , 1);
                    $pdf->Cell(15, 8,$sc->STATUS , 1);
                    $pdf->Ln();

                    $exist=$data->exist($sc->ID_COMP, 'pc');                    
                    $ctr2=0;
                    $pdf->SetFillColor(255, 253, 200);
                    if (count($exist>0)) {
                        foreach($exist as $ex){
                            if($ctr2== 0){
                                $pdf->cell(5, 8 , "", 0);
                                $pdf->cell(15, 8 , "", 0);
                                $pdf->cell(15, 8 , "", 0);
                                $pdf->Cell(110, 8, "Producto", 1, 0, 'L', true);
                                $pdf->Cell(15, 8, "Entradas", 1, 0, 'L', true);
                                $pdf->Cell(15, 8, "Salidas", 1, 0, 'L', true);
                                $pdf->Cell(20, 8, "Existencias", 1, 0, 'L', true);
                                $pdf->Ln();
                                $ctr2++;
                            }
                            $pdf->cell(5, 8 , "", 0);
                            $pdf->cell(15, 8 , "", 0);
                            $pdf->cell(15, 8 , "", 0);
                            $pdf->Cell(110, 8,substr($ex->PROD, 0, 75) , 1, 0, 'L', true);
                            $pdf->Cell(15, 8,$ex->ENTRADAS , 1, 0, 'L', true);
                            $pdf->Cell(15, 8,$ex->SALIDAS , 1, 0, 'L', true);
                            $pdf->Cell(20, 8,($ex->ENTRADAS-$ex->SALIDAS), 1, 0, 'L', true);
                            $pdf->Ln();

                        }
                    }

                }
            }
        }

        $pdf->SetFont('Arial', 'I',10);
        $pdf->Ln(10);
        //$pdf->SetX(140);
        $pdf->Write(6,"_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_- FIN DEL REPORTE _-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-");
        $pdf->Ln();
        $ruta='C:\\xampp\\htdocs\\Reportes_Almacen\\';
        ob_end_clean();
        $pdf->Output($ruta.'Reporte Productos del Componente'.$delim.'.pdf', 'f');
    }

    //// Degradado el 18 de Julio del 2022
    function repXlsPP($info){
        $usuario = $_SESSION['user']->NOMBRE;   
        $xls= new PHPExcel();
        $data = new wms;
        
        $col='A';$ln=10; $i=0;
            foreach ($info['primary'] as $row) {
                $i++;
                $ln++;
                $xls->setActiveSheetIndex()
                        ->setCellValue($col.$ln,$i)
                        ->setCellValue(++$col.$ln,$row->ID_INT)
                        ->setCellValue(++$col.$ln,$row->DESC)
                        ->setCellValue(++$col.$ln,$row->LARGO.' x '.$row->ANCHO.' x '.$row->ALTO)
                        ->setCellValue(++$col.$ln,'Master: '.$row->UNIDAD_ORIG)
                        ->setCellValue(++$col.$ln,'Tipo: '.$row->TIPO_INT)
                        ->setCellValue(++$col.$ln,$row->STATUS)
                ;
                $linea = "A".$ln;
                $xls->getActiveSheet()->getStyle("A".$ln.':'.$col.$ln)->applyFromArray(
                    array(
                            'font'=> array(
                                'bold'=>true
                            ),
                            'borders'=>array(
                                'allborders'=>array(
                                    'style'=>PHPExcel_Style_Border::BORDER_THIN
                                )
                            ), 
                            'fill'=>array( 
                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,             
                                    'color'=> array('rgb' => 198, 255, 251)
                            )   
                        )
                    );

                $col="A";
                $ln++;
                $xls->setActiveSheetIndex()
                    ->setCellValue($col.$ln,"")
                    ->setCellValue(++$col.$ln,"Almacen")
                    ->setCellValue(++$col.$ln,"Linea")
                    ->setCellValue(++$col.$ln,"Tarima")
                    ->setCellValue(++$col.$ln,"Entradas")
                    ->setCellValue(++$col.$ln,"Salidas")
                    ->setCellValue(++$col.$ln,"Existencias")
                ;
                $xls->getActiveSheet()->getStyle("B".$ln.':'.$col.$ln)->applyFromArray(
                    array(
                            'font'=> array(
                                'bold'=>true
                            ),
                            'borders'=>array(
                                'allborders'=>array(
                                    'style'=>PHPExcel_Style_Border::BORDER_THIN
                                )
                            ), 
                            'fill'=>array( 
                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,             
                                    'color'=> array('rgb' => FF0000)
                            )   
                        )
                    );
                $col="A";
                $det=0;
                foreach ($info['secondary'] as $key){

                    if($key->ID_PROD == $row->ID_PINT){
                        $det++;
                        $ln++;
                        $in=0;$out=0;$ex=0;
                        $exist=$key->ENTRADAS-$key->SALIDAS;
                        if($exist!=0){
                            $xls->setActiveSheetIndex()
                                ->setCellValue($col.$ln, '')
                                ->setCellValue(++$col.$ln,$key->ALMACEN)
                                ->setCellValue(++$col.$ln,$key->COMPP)
                                ->setCellValue(++$col.$ln,$key->COMPS)
                                ->setCellValue(++$col.$ln,$key->ENTRADAS)
                                ->setCellValue(++$col.$ln,$key->SALIDAS)
                                ->setCellValue(++$col.$ln,number_format($exist))
                            ;
                            $xls->getActiveSheet()->getStyle("B".$ln.':'.$col.$ln)->applyFromArray(
                            array(
                                    'font'=> array(
                                        'bold'=>true
                                    ),
                                    'borders'=>array(
                                        'allborders'=>array(
                                            'style'=>PHPExcel_Style_Border::BORDER_THIN
                                        )
                                    ), 
                                    'fill'=>array( 
                                            'type' => PHPExcel_Style_Fill::FILL_SOLID,             
                                            'color'=> array('rgb' => dbfbff  )
                                    )   
                                )
                            );       
                        }else{
                            $ln--;
                        }
                        $col="A";
                    }
                }
                if($det== 0){
                    $ln++;
                    $xls->setActiveSheetIndex()
                        ->setCellValue('A'.$ln,"No se encontraron componentes secundarios de este componente")
                    ;
                    $col="A";
                }
            }
            
            $xls->setActiveSheetIndex()
                ->setCellValue('A1', "IMPORTADORA MIZCO SA DE CV")
                ->setCellValue('A2', "Reporte de Posición de productos ")
                //->setCellValue('A3',  "")
                ->setCellValue('A4', "Elaborado por: ")
                ->setCellValue('B4', $usuario)
                ->setCellValue('A5', "Fecha de Elaboracion: ")
                ->setCellValue('B5', date("d-m-Y H:i:s" ) )
                ->setCellValue('A6', "Total Componentes Primarios:")
                ->setCellValue('B6', count($info['primary']))
                ->setCellValue('A7', "Total Componentes Secundarios:")
                ->setCellValue('B7', count($info['secondary']))
                
            ;
            /// CAMBIANDO EL TAMAÑO DE LA LINEA.
            $col = 'A';
            $xls->getActiveSheet()->getColumnDimension($col)->setWidth(15);
            $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(15);
            $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(15);
            $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(20);
            $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(25);
            $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(15);
            $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(20);
            $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(20);
            $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(20);
            $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(20);

            $xls->getActiveSheet()->getColumnDimension('B')->setWidth(20);
            /// Unir celdas
            $xls->getActiveSheet()->mergeCells('A1:O1');
            // Alineando
            $xls->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal('center');
            /// Estilando
            $xls->getActiveSheet()->getStyle('A1')->applyFromArray(
                array('font' => array(
                        'size'=>20,
                    )
                )
            );
            $xls->getActiveSheet()->getStyle('I10:I102')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $xls->getActiveSheet()->mergeCells('A3:F3');
            $xls->getActiveSheet()->getStyle('D3')->applyFromArray(
                array('font' => array(
                        'size'=>15,
                    )
                )
            );

            $xls->getActiveSheet()->getStyle('A3:D3')->applyFromArray(
                array(
                    'font'=> array(
                        'bold'=>true
                    ),
                    'borders'=>array(
                        'allborders'=>array(
                            'style'=>PHPExcel_Style_Border::BORDER_THIN
                        )
                    )
                )
            );
            $ruta='C:\\xampp\\htdocs\\Reportes_Almacen\\';
                if(!file_exists($ruta) ){
                    mkdir($ruta);
                }
                $nom='Posición de productos '.date("d-m-Y H_i_s").'_'.$usuario.'.xlsx';
                $x=PHPExcel_IOFactory::createWriter($xls,'Excel2007');
            /// salida a descargar
                $x->save($ruta.$nom);
                ob_end_clean();
                return array("status"=>'ok',"nombre"=>$nom, "ruta"=>$ruta, "completa"=>'..\\..\\Reportes_Almacen\\'.$nom, "tipo"=>'x');
                
    }

    function repXlsPP_v2($info){
        $usuario = $_SESSION['user']->NOMBRE;   
        $xls= new PHPExcel();
        $data = new wms;
        $col='A';$ln=10; $i=0;
        $ln++;
        $xls->setActiveSheetIndex()
            ->setCellValue($col.$ln,"")
            ->setCellValue(++$col.$ln,"Almacen")
            ->setCellValue(++$col.$ln,"Linea")
            ->setCellValue(++$col.$ln,"Tarima")
            ->setCellValue(++$col.$ln,"Modelo")
            ->setCellValue(++$col.$ln,"UPC")
            ->setCellValue(++$col.$ln,"Existencias")
            ->setCellValue(++$col.$ln,"Asignado")
            ->setCellValue(++$col.$ln,"Reservado")
            ->setCellValue(++$col.$ln,"Disponible")
            ->setCellValue(++$col.$ln,"Descripción")
        ;
            foreach ($info['primary'] as $row) {
                $i++;
                //$ln++;
                $col= 'A';
                $tot_asig=$row->ASIG; /// el producto 1 ves 
                //// Almacen / Linea / Tarima / Modelo.
                $det=0;
                foreach ($info['secondary'] as $key){ /// secondary es la linea por lo cual vuelve a traer el producto
                    if($key->ID_PROD == $row->ID_PINT){
                        $det++;
                        $ln++;
                        $in=0;$out=0;$ex=0;
                        $exist=$key->ENTRADAS-$key->SALIDAS;
                        $disp =$exist - $key->PENDIENTES;
                        if($exist!=0){
                            if($disp > 0 and $tot_asig > 0 ){
                                if ($tot_asig <= $disp){
                                    $asig=$tot_asig; 
                                }else{
                                    $asig = $disp;
                                }
                                $tot_asig = $tot_asig - $asig;
                            }else{ $asig = 0;}
                            $xls->setActiveSheetIndex()
                                    ->setCellValue($col.$ln,$i)
                                    ->setCellValue(++$col.$ln,$key->ALMACEN)
                                    ->setCellValue(++$col.$ln,$key->PRIMARIO)
                                    ->setCellValue(++$col.$ln,$key->SECUNDARIO)
                                    ->setCellValue(++$col.$ln,$row->ID_INT)
                                    //->setCellValue(++$col.$ln,$row->LARGO.' x '.$row->ANCHO.' x '.$row->ALTO)
                                    ->setCellValue(++$col.$ln,$row->UPC)
                                    //->setCellValue(++$col.$ln,'Master: '.$row->UNIDAD_ORIG)
                                    //->setCellValue(++$col.$ln,$row->STATUS)
                                    //->setCellValue(++$col.$ln,$key->ENTRADAS)
                                    //->setCellValue(++$col.$ln,$key->SALIDAS)
                                    ->setCellValue(++$col.$ln,($exist))
                                    ->setCellValue(++$col.$ln,($asig))
                                    ->setCellValue(++$col.$ln,($key->PENDIENTES)) /// Reservado
                                    ->setCellValue(++$col.$ln,($disp - $asig)) /// Disponible
                                    ->setCellValue(++$col.$ln,$row->DESC)
                            ;
                            /*
                            $xls->getActiveSheet()->getStyle("B".$ln.':'.$col.$ln)->applyFromArray(
                            array(
                                    'font'=> array(
                                        'bold'=>true
                                    ),
                                    'borders'=>array(
                                        'allborders'=>array(
                                            'style'=>PHPExcel_Style_Border::BORDER_THIN
                                        )
                                    ), 
                                    'fill'=>array( 
                                            'type' => PHPExcel_Style_Fill::FILL_SOLID,             
                                            'color'=> array('rgb' => dbfbff  )
                                    )   
                                )
                            );
                            */       
                        }else{
                            $ln--;
                        }
                        $col="A";
                    }
                }
                if($det== 0){
                    //$ln++;
                    //$xls->setActiveSheetIndex()
                    //   ->setCellValue('A'.$ln,"No se encontraron componentes secundarios de este componente")
                    //;
                    //$col="A";
                }
            }
            
            $xls->setActiveSheetIndex()
                ->setCellValue('A1', "IMPORTADORA MIZCO SA DE CV")
                ->setCellValue('A2', "Reporte de Posición de productos ")
                //->setCellValue('A3',  "")
                ->setCellValue('A4', "Elaborado por: ")
                ->setCellValue('B4', $usuario)
                ->setCellValue('A5', "Fecha de Elaboracion: ")
                ->setCellValue('B5', date("d-m-Y H:i:s" ) )
                ->setCellValue('A6', "Total Componentes Primarios:")
                ->setCellValue('B6', count($info['primary']))
                ->setCellValue('A7', "Total Componentes Secundarios:")
                ->setCellValue('B7', count($info['secondary']))
                
            ;
            /// CAMBIANDO EL TAMAÑO DE LA LINEA.
            $col = 'A';
            $xls->getActiveSheet()->getColumnDimension($col)->setWidth(15);
            $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(15);
            $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(15);
            $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(20);
            $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(25);
            $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(15);
            $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(20);
            $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(20);
            $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(20);
            $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(20);

            $xls->getActiveSheet()->getColumnDimension('B')->setWidth(20);
            /// Unir celdas
            $xls->getActiveSheet()->mergeCells('A1:O1');
            // Alineando
            $xls->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal('center');
            /// Estilando
            $xls->getActiveSheet()->getStyle('A1')->applyFromArray(
                array('font' => array(
                        'size'=>20,
                    )
                )
            );
            $xls->getActiveSheet()->getStyle('I10:I102')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $xls->getActiveSheet()->mergeCells('A3:F3');
            $xls->getActiveSheet()->getStyle('D3')->applyFromArray(
                array('font' => array(
                        'size'=>15,
                    )
                )
            );

            $xls->getActiveSheet()->getStyle('A3:D3')->applyFromArray(
                array(
                    'font'=> array(
                        'bold'=>true
                    ),
                    'borders'=>array(
                        'allborders'=>array(
                            'style'=>PHPExcel_Style_Border::BORDER_THIN
                        )
                    )
                )
            );
            $ruta='C:\\xampp\\htdocs\\Reportes_Almacen\\';
                if(!file_exists($ruta) ){
                    mkdir($ruta);
                }
                $nom='Posición de productos '.date("d-m-Y H_i_s").'_'.$usuario.'.xlsx';
                $x=PHPExcel_IOFactory::createWriter($xls,'Excel2007');
            /// salida a descargar
                $x->save($ruta.$nom);
                ob_end_clean();
                return array("status"=>'ok',"nombre"=>$nom, "ruta"=>$ruta, "completa"=>'..\\..\\Reportes_Almacen\\'.$nom, "tipo"=>'x');
                
    }

    function repPdfPP($info, $delim){
        $usuario = $_SESSION['user']->NOMBRE;
        $data = new wms;   
        $pdf = new FPDF('P', 'mm', 'Letter');
        $pdf->AddPage();
        $pdf->Image('app/views/images/LOGOSELECT.jpg', 5, 5, 30, 28);
        
        $pdf->SetFont('Arial', 'I',10);
        $pdf->Ln(28);
        $pdf->SetX(10);
        $pdf->write(6, "Elaborado por :". $usuario. " el ".date("d-m-Y h:i:s")."\n");
        $pdf->write(6, "Total de Productos: ". count($info['primary'])."\n");
        $pdf->write(6, "Total de componentes: ". count($info['secondary'])."\n");        

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        foreach ($info['primary'] as $pr) {
            $pdf->Cell(25, 8, $pr->ID_INT, 1);
            $pdf->Cell(80, 8, substr($pr->DESC,0, 60), 1);
            $pdf->Cell(30, 8, $pr->LARGO.' x '.$pr->ANCHO.' x '.$pr->ALTO.' '.$pr->MEDICION, 1);
            $pdf->Cell(20, 8, $pr->UNIDAD_ORIG, 1);
            $pdf->Cell(20, 8, $pr->TIPO_INT, 1);
            $pdf->Cell(20, 8, $pr->STATUS, 1);
            $pdf->Ln();
            //$pdf->Ln();
            $pdf->SetFont('Arial', 'B', 7);
            $ctr=0;
            foreach($info['secondary'] as $sc){
                if($sc->ID_PROD == $pr->ID_PINT){
                    $exist=$sc->ENTRADAS-$sc->SALIDAS;
                    if($exist!=0){
                        if($ctr== 0){
                                $pdf->SetFillColor(177, 255, 150);
                                $pdf->cell(15, 8 , "", 0);
                                $pdf->cell(25, 8 , "Almacen", 1, 0, 'L', true);
                                $pdf->cell(25, 8 , "Linea", 1, 0, 'L', true);
                                $pdf->cell(25, 8 , "Tarima", 1, 0, 'L', true);
                                $pdf->Cell(15, 8, "Entradas", 1, 0, 'C', true);
                                $pdf->Cell(15, 8, "Salidas", 1, 0, 'C', true);
                                $pdf->Cell(20, 8, "Existencias", 1, 0, 'C', true);
                                $pdf->Ln();
                            $ctr++;
                        }
                        $pdf->SetFillColor(228, 255, 219);
                        $pdf->Cell(15, 8,"" , 0);
                        $pdf->Cell(25, 8,substr($sc->ALMACEN,0,15), 1);
                        $pdf->Cell(25, 8,substr($sc->COMPP,0,15), 1);
                        $pdf->Cell(25, 8,substr($sc->COMPS,0,15), 1);
                        $pdf->Cell(15, 8,number_format($sc->ENTRADAS,0),1,0,'R',true);
                        $pdf->Cell(15, 8,number_format($sc->SALIDAS,0),1,0,'R',true);
                        $pdf->Cell(20, 8,number_format($exist,0), 1,0,'R',true);
                        $pdf->Ln();
                    }else{
                        $pdf->cell(15, 8 , "Sin existencias", 0);
                    }
                }
            }
        }

        $pdf->SetFont('Arial', 'I',10);
        $pdf->Ln(10);
        //$pdf->SetX(140);
        $pdf->Write(6,"_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-FIN DEL REPORTE _-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-");
        $pdf->Ln();
        $ruta='C:\\xampp\\htdocs\\Reportes_Almacen\\';
        ob_end_clean();
        $pdf->Output($ruta.'Reporte Posicion de productos'.$delim.'.pdf', 'f');
    }


    function repProd(){
        $usuario = $_SESSION['user']->NOMBRE;   
        $xls= new PHPExcel();
        $data = new wms;
        $col='A';$ln=10; $i=0;
        $ln++;
        
        $col='A';
        $info=$data->productos($op='');
            foreach ($info as $row) {
                $dif=$row->DISP-$row->DISP_ALM;
                $i++;
                $ln++;
                $xls->setActiveSheetIndex()
                        ->setCellValue($col.$ln,$i)
                        ->setCellValue(++$col.$ln,$row->ID_INT)
                        ->setCellValue(++$col.$ln,$row->DESC)
                        ->setCellValue(++$col.$ln,$row->TIPO_INT)
                        ->setCellValue(++$col.$ln,$row->STATUS)
                        ->setCellValue(++$col.$ln,$row->ALMACEN)
                        ->setCellValue(++$col.$ln,$row->EXI)
                        ->setCellValue(++$col.$ln,$row->RES)
                        ->setCellValue(++$col.$ln,$row->REM)
                        ->setCellValue(++$col.$ln,$row->DISP)
                        ->setCellValue(++$col.$ln,$row->ING)
                        ->setCellValue(++$col.$ln,$row->OUT)
                        ->setCellValue(++$col.$ln,$row->DISP_ALM)
                        ->setCellValue(++$col.$ln,$dif)
                ;
                $linea = "A".$ln;
                //$xls->getActiveSheet()->getStyle("A".$ln.':'.$col.$ln)->applyFromArray(
                //    array(
                //            'font'=> array(
                //                'bold'=>true
                //            ),
                //            'borders'=>array(
                //                'allborders'=>array(
                //                    'style'=>PHPExcel_Style_Border::BORDER_THIN
                //                )
                //            ), 
                //            'fill'=>array( 
                //                    'type' => PHPExcel_Style_Fill::FILL_SOLID,             
                //                    'color'=> array('rgb' => 198, 255, 251)
                //            )   
                //        )
                //    );
                $col="A";
                //$xls->getActiveSheet()->getStyle("B".$ln.':'.$col.$ln)->applyFromArray(
                //    array(
                //            'font'=> array(
                //                'bold'=>true
                //            ),
                //            'borders'=>array(
                //                'allborders'=>array(
                //                    'style'=>PHPExcel_Style_Border::BORDER_THIN
                //                )
                //            ), 
                //            'fill'=>array( 
                //                    'type' => PHPExcel_Style_Fill::FILL_SOLID,             
                //                    'color'=> array('rgb' => FF0000)
                //            )   
                //        )
                //    );
                //$col="A";
                $det=0;
                
            }
        $ln=10;
            $xls->setActiveSheetIndex()
                ->setCellValue($col.$ln,"")
                ->setCellValue(++$col.$ln,"")
                ->setCellValue(++$col.$ln,"")
                ->setCellValue(++$col.$ln,"")
                ->setCellValue(++$col.$ln,"")
                ->setCellValue(++$col.$ln,"Info Intelisis (".$row->FECHA.')')
                ->setCellValue(++$col.$ln,"")
                ->setCellValue(++$col.$ln,"")
                ->setCellValue(++$col.$ln,"")
                ->setCellValue(++$col.$ln,"")
                ->setCellValue(++$col.$ln,"Info Almacen")
            ;
        $col='A';
        $ln++;
            $xls->setActiveSheetIndex()
                ->setCellValue($col.$ln,"")
                ->setCellValue(++$col.$ln,"Clave")
                ->setCellValue(++$col.$ln,"Descripción")
                ->setCellValue(++$col.$ln,"Tipo")
                ->setCellValue(++$col.$ln,"Estatus")
                ->setCellValue(++$col.$ln,"Almacen")
                ->setCellValue(++$col.$ln,"Existencia")
                ->setCellValue(++$col.$ln,"Reservado")
                ->setCellValue(++$col.$ln,"Remisionado")
                ->setCellValue(++$col.$ln,"Disponible")
                ->setCellValue(++$col.$ln,"Entradas")
                ->setCellValue(++$col.$ln,"Salidas")
                ->setCellValue(++$col.$ln,"Disponible")
                ->setCellValue(++$col.$ln,"Diferencia")
            ;
            
            $xls->setActiveSheetIndex()
                ->setCellValue('A1', "IMPORTADORA MIZCO SA DE CV")
                ->setCellValue('A2', "Reporte Existencia de productos Intelisis vs Almacen")
                //->setCellValue('A3',  "")
                ->setCellValue('A4', "Elaborado por: ")
                ->setCellValue('B4', $usuario)
                ->setCellValue('A5', "Fecha de Elaboracion: ")
                ->setCellValue('B5', date("d-m-Y H:i:s" ) )
                ->setCellValue('A6', "Total Productos:")
                ->setCellValue('B6', count($info))
            ;
            /// CAMBIANDO EL TAMAÑO DE LA LINEA.
            $col = 'A';
            $xls->getActiveSheet()->getColumnDimension($col)->setWidth(4);
            $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(10);
            $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(35);
            $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(10);
            $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(10);
            $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(10);
            $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(10);
            $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(10);
            $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(10);
            $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(10);
            $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(10);
            $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(10);
            $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(10);
            $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(10);


            $xls->getActiveSheet()->getColumnDimension('B')->setWidth(20);
            /// Unir celdas
            $xls->getActiveSheet()->mergeCells('A1:O1');
            $xls->getActiveSheet()->mergeCells('F10:J10');
            $xls->getActiveSheet()->mergeCells('K10:M10');
            // Alineando
            $xls->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal('center');
            $xls->getActiveSheet()->getStyle('F10')->getAlignment()->setHorizontal('center');
            $xls->getActiveSheet()->getStyle('K10')->getAlignment()->setHorizontal('center');
            /// Estilando
            $xls->getActiveSheet()->getStyle('A1')->applyFromArray(
                array('font' => array(
                        'size'=>20,
                    )
                )
            );
            $xls->getActiveSheet()->getStyle('I10:I102')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            //$xls->getActiveSheet()->mergeCells('A3:F3');
            $xls->getActiveSheet()->getStyle('D3')->applyFromArray(
                array('font' => array(
                        'size'=>15,
                    )
                )
            );
            /// Bordes
            $xls->getActiveSheet()->getStyle('F10:M10')->applyFromArray(
                array(
                    'font'=> array(
                        'bold'=>true
                    ),
                    'borders'=>array(
                        'allborders'=>array(
                            'style'=>PHPExcel_Style_Border::BORDER_THIN
                        )
                    )
                )
            );
            $xls->getActiveSheet()->getStyle('A11:N11')->applyFromArray(
                array(
                    'font'=> array(
                        'bold'=>true
                    ),
                    'borders'=>array(
                        'allborders'=>array(
                            'style'=>PHPExcel_Style_Border::BORDER_THIN
                        )
                    )
                )
            );
            $ruta='C:\\xampp\\htdocs\\Reportes_Almacen\\';
                if(!file_exists($ruta) ){
                    mkdir($ruta);
                }
                $nom='Existencia de Productos '.date("d-m-Y H_i_s").'_'.$usuario.'.xlsx';
                $x=PHPExcel_IOFactory::createWriter($xls,'Excel2007');
            /// salida a descargar
                $x->save($ruta.$nom);
                ob_end_clean();
                return array("status"=>'ok',"nombre"=>$nom, "ruta"=>$ruta, "completa"=>'..\\..\\Reportes_Almacen\\'.$nom, "tipo"=>'x');
    }


    function wms_ordenes($op){
        if($_SESSION['user']){
            $param = $op;
            $data = new wms;
            $data_i = new intelisis;
            $pagina = $this->load_template('Reportes');
            $html = $this->load_page('app/views/pages/almacenes/p.monitorOrdenes.php');
            ob_start();
            $opc = explode(":", $op);
            if(strrpos($op, 'all') or $op=='all'){
                $lt = $_SESSION['user']->CR;
                $pagina = $this->load_template('Reportes');
                $html = $this->load_page('app/views/pages/almacenes/p.monitorOrdenesAll.php');
                $ordenes = $data->ordenesAll($op);
                include 'app/views/pages/almacenes/p.monitorOrdenesAll.php';
                $table = ob_get_clean();
                $pagina = $this->replace_content('/\#CONTENIDO\#/ms', $table, $pagina);
                $this->view_page($pagina); 
                die();
            }
            if(count($opc)> 1){
                if($opc[3] != '0'){
                    $op1=$opc[3]; $op0=1;
                    $info=$data_i->documentos(1, $opc[3] , $opc[1], $opc[2]);
                    $ins= $data->insOrdenes($info);
                }else{
                    $op0= 0;
                }
            }else{$op0=0;}
            $docs = $data_i->documentos(0, '', '', '');
            if($op0 == 0){
                $html = $this->load_page('app/views/pages/almacenes/p.monitorOrdenesInt.php');
                ob_start();
                include 'app/views/pages/almacenes/p.monitorOrdenesInt.php';
                $table = ob_get_clean();
                $pagina = $this->replace_content('/\#CONTENIDO\#/ms', $table, $pagina);
                $this->view_page($pagina); 
                die;
            }
            $ordenes = $data->ordenes($op = ' and id_status != 9', $param);
            //$a = $data->actualizaCodigo();
            $correos= $data->correos2('A', 't');
            $opcion= $data->correos2('O', 'o');
            include 'app/views/pages/almacenes/p.monitorOrdenes.php';
            $table = ob_get_clean();
            $pagina = $this->replace_content('/\#CONTENIDO\#/ms', $table, $pagina);
            $this->view_page($pagina); 
        } else {
            $e = "Favor de Iniciar Sesión";
            header('Location: index.php?action=login&e=' . urlencode($e));
            exit;
        }
    }

    function saveOrder($file, $fileName){
        if (isset($_SESSION['user'])) {
            $data = new wms;
            ob_start();
            $reg=$data->saveOrder($file, $fileName, $ido=0);
        }else{
            $e = "Favor de Iniciar Sesión";
            header('Location: index.php?action=login&e=' . urlencode($e));
            exit;
        }
    }

    function detOrden($id_o, $t, $param, $out){
        if($_SESSION['user']){
            $data = new wms;
            if($t=='p'){/// que es t lo define el usuario ? 
                $p = 'app/views/pages/almacenes/p.detOrdenProd.php';
                $data_i = new intelisis;
                $infoInt = $data_i->infoInt($id_o);
                $sinc = $data->sincOrd($infoInt, "p");
            }elseif($t=='s'){
                $p = 'app/views/pages/almacenes/p.detOrdenSurt.php';
            }else{
                $p = 'app/views/pages/almacenes/p.detOrden.php';
            }
            $pagina = $this->load_templateL('Reportes');
            $html = $this->load_page($p);
            ob_start();
            $cabecera =$data->datOrden($id_o);
            $orden = $data->orden($id_o, $t, $param);
            $persona = $data->perSurt($id_o, $t, $param);
            if($out=='i'){/// la salida es la impresion.
                $this->impOrden($cabecera, $orden, $param);
            }
            include $p;
            $table = ob_get_clean();
            $pagina = $this->replace_content('/\#CONTENIDO\#/ms', $table, $pagina);
            $this->view_page($pagina); 

        } else {
            $e = "Favor de Iniciar Sesión";
            header('Location: index.php?action=login&e=' . urlencode($e));
            exit;
        }
    }

    function aOC($ord){
        if($_SESSION['user']){
            $data = new wms;
            $exec=$data->aOC($ord);
            return $exec;
        }
    }

    function delComp($id, $t){
        if($_SESSION['user']){
            $data = new wms;
            $exec=$data->delComp($id,$t);
            return $exec;
        }
    }

    function envMail($correos, $msg, $archivos, $ids){
        if($_SESSION['user']){        
            $data = new wms;
            //echo 'Se envian los archvivos: '.$archivos.' a los correos: '.$correos.' con el mensaje: '.$msg;
            $_SESSION['correos']= $correos;
            $_SESSION['archivos']= $archivos;
            $_SESSION['correosP']= $data->correos($opc= "where tipo = 'P' and status= 1");
            $_SESSION['msg']= $msg;
            $res= include 'app/mailer/send.OC.php';   ///  se incluye la classe Contrarecibo
            if($res['status']=='ok'){
                $upd=$data->actStatus($tabla=1, $tipo='Orden', $sub='Envío', $ids, $obs='');
            }
            return;
        }
    }

    function cargaOrdenes($files2upload){
        if (isset($_SESSION['user'])) {            
            $data = new wms;
            $valid_formats = array("xlsx", "XLSX");
            $max_file_size = (1024*1024) * 20; 
            $target_dir="C:\\xampp\\htdocs\\Cargas Ordenes\\";
            if(!file_exists($target_dir)){
                mkdir($target_dir);
            }
            $count = 0;
            $respuesta = 0;$message=array();
            foreach ($_FILES['files']['name'] as $f => $name) { 
                if ($_FILES['files']['error'][$f] == 4) {
                    continue; // Skip file if any error found
                }
                if ($_FILES['files']['error'][$f] == 0) {
                    if ($_FILES['files']['size'][$f] > $max_file_size) {
                        $message[] = "$name es demasiado grande para subirlo.";
                        continue; // Skip large files
                    }elseif (!in_array(pathinfo($name, PATHINFO_EXTENSION), $valid_formats)) {
                        $message[] = "$name no es un archivo permitido.";
                        continue; // Skip invalid file formats
                    }elseif(file_exists($target_dir.basename($_FILES["files"]["name"][$f]))){
                        $message[] = "$name es un archivo duplicado.";
                        continue; // Skip invalid file formats
                    } else { // No error found! Move uploaded files 
                        $target_file = $target_dir.basename($_FILES["files"]["name"][$f]);
                        $uploadOk =0;
                        if(move_uploaded_file($_FILES["files"]["tmp_name"][$f], $target_file)) {
                        //echo "El Archivo: ". basename( $_FILES["files"]["name"][$f]). " se ha cargado.<p>";
                            $this->saveOrder($target_file, basename($_FILES["files"]["name"][$f]), $ido=0);
                        } else {
                            echo "Ocurrio un problema al subir su archivo, favor de revisarlo.";
                        }
                    }
                }
            }
            for ($i=0; $i <count($message) ; $i++) { 
                echo '<p><font color="purpple">'.$message[$i].'</font></p>';
            }
            $this->limpiaForm('wms_menu&opc=o');
        } else {
            $e = "Favor de Iniciar Sesión";
            header('Location: index.php?action=login&e=' . urlencode($e));
            exit;
        }   
    }

    

    function detLinOrd($ord, $prod){
        if($_SESSION['user']){
            $data= new wms;
            $res=$data->detLinOrd($ord, $prod);
            return $res;
        }
    }

    function actProOrd($prod, $oc, $prodn){
        if($_SESSION['user']){
            $data= new wms;
            $res=$data->actProOrd($prod, $oc, $prodn);
            return $res;
        }
    }

    function asgProd($ord, $prod, $pza, $t, $c, $s){
        if($_SESSION['user']){
            $data= new wms;
            $data_i= new intelisis;
            $res=$data->asgProd($ord, $prod, $pza, $t, $c, $s);
            //$sincInt =$data_i->asgLn($res['infoAsig']);
            if($s=='s'){
                foreach ($res["infoAsig"] as $inf){
                    //echo '<br/>Sincroniza el ID_ORDD: '.$inf->ID_ORDD;
                    $this->sincInt($inf->ID_ORDD,"w");
                }
            }
            return $res;
        }
    }

    function asgLn($ln, $c){
        if($_SESSION['user']){
            $data= new wms;
            $data_i= new intelisis;
            $res=$data->asgLn($ln, $c, $o='');
            //$sincInt =$data_i->asgLn($res['infoAsig']);
            return $res;
        }
    }

    function asigCol($ln, $col, $t){
        if($_SESSION['user']){
            $data= new wms;
            $data_i= new intelisis;
            $res=$data->asigCol($ln, $col);
            if($t == 's' ){
                $this->sincInt($ln ,"w");
            }
            return $res;
        }       
    }
    function chgProd($p, $nP, $o, $t){
        if($_SESSION['user']){
            $data= new wms;
            $res=$data->chgProd($p, $nP, $o, $t);
            return $res;
        }    
    }


    function finA($p, $ord, $t){
        if($_SESSION['user']){
            $data= new wms;
            $res=$data->finA($p, $ord, $t);
            return $res;
        }   
    }

    function delOC($id){
        if($_SESSION['user']){
            $data= new wms;
            $res=$data->delOC($id);
            return $res;
        }
    }

    function log($tabla, $id, $tablad){
        if($_SESSION['user']){
            $data= new wms;
            $res=$data->log($tabla, $id, $tablad);
            return $res;
        }   
    }

    function chgFile($file, $name, $ido, $mot){
        if($_SESSION['user']){
            $data = new wms;
            $data->chgFile($file, $name, $ido, $mot);
            $data->saveOrder($file, $fileName=$name, $ido);
            return;
        }    
    }

    function limpiaForm($param){
        $pagina = $this->load_template('Pedidos');
        $redirec = $param;
        $html = $this->load_page('app/views/pages/p.redirectform.wms.php');
        ob_start();
        $response = true;
        include 'app/views/pages/p.redirectform.wms.php';
    }

    function chgComp($idc, $d, $t){
        if($_SESSION['user']){
            $data= new wms;
            $res=$data->chgComp($idc, $d, $t);
            return $res;
        }
    }

    function comPro($prod, $ordd){
        if($_SESSION['user']){
            $data=new wms;
            $res=$data->comPro($prod, $ordd);
            return $res;
        }
    }

    function impOrden($cabecera, $orden, $param){
        $usuario = $_SESSION['user']->NOMBRE;
        $data = new wms;
        $pdf = new FPDF('L', 'mm', 'Letter');
        $pdf->AddPage();
        $pdf->Image('app/views/images/LOGOSELECT.jpg', 5, 5, 30, 28);
        $pdf->SetFont('Arial', 'BI', 8);   
        if($param == ''){
            $cedis = substr(utf8_decode($cabecera->CEDIS),0,100);
        }elseif(!empty($param)){
            $cedis = $param;
        }
        $pdf->Ln(5);
        $pdf->SetX(40);
        $pdf->write(5, "Cliente : ". $cabecera->CLIENTE."");
        $pdf->SetX(150);
        $pdf->write(5, "Archivo : ".$cabecera->ARCHIVO." Orden :". $cabecera->ID_ORD."\n");
        $pdf->SetX(40);
        $pdf->write(5, "Cedis : ". $cedis."");
        $pdf->SetX(150);
        $pdf->write(5, "Fecha Surtido : ". $cabecera->FECHA_CARGA."\n");
        $pdf->SetX(40);
        $pdf->write(5, "Partidas : ". count($orden)."");
        $pdf->SetX(150);
        $pdf->write(5, "Elaborado por :". $usuario. " el ".date("d-m-Y h:i:s")."\n");

        $pdf->Ln();
        $pdf->write(5, "Picking List ");
        $pdf->Ln(8);
        $pdf->SetFont('Arial', 'B', 8);

        $pdf->Cell(28, 4, 'UPC','LRT');
        $pdf->Cell(20, 4, 'Modelo','LRT');
        $pdf->Cell(20, 4, 'Sustituto','LRT');
        $pdf->Cell(20, 4, 'Surtir','LRT',0,'C');
        $pdf->Cell(20, 4, 'Piezas x','LRT',0,'C');
        //$pdf->Cell(25, 4, 'Cajas','LRT');
        $pdf->Cell(20, 4, 'Total de cajas','LRT',0,'C');
        $pdf->Cell(70, 4, 'Ubicacion ','LRT',0,'C');
        $pdf->Cell(40, 4, 'Etiqueta','LRT');
        $pdf->Ln();
        
        $pdf->Cell(28, 4, '','LRB');
        $pdf->Cell(20, 4, 'Original','LRB');
        $pdf->Cell(20, 4, '','LRB');
        $pdf->SetTextColor(30,117,0);
        $pdf->Cell(20, 4, '','LRB',0,'C');
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(20, 4, 'Caja ','LRB',0,'C');
        //$pdf->Cell(25, 4, '','LRB');
        $pdf->Cell(20, 4, '','LRB',0,'C');
        $pdf->Cell(70, 4, 'Cantidad en Piezas','LRB',0,'C');
        $pdf->Cell(40, 4, '','LRB');
        $pdf->Ln();
       
        foreach ($orden as $ord) {
            if($ord->ASIG > 0){
                $componentes=array();$pos= array();$ubicacion='';$ubi=array();
                $componentes=$data->comPro($ord->PROD, $ord->ID_ORDD);
                $pos = $data->posImp($ord->ID_ORDD); /// Obtiene las posiciones de los productos.
                $pres = $data->sustitutos($ord->ID_ORDD);
                $surt= count($pos);$cmpt=count($componentes['datos']);$sust=count($pres);
                //$pdf->Cell(50, 6, $ord->CEDIS, 'LRT');
                $m = (count($pos)>1)? 'LRT':'LRTB';
                $pdf->Cell(28, 6, $ord->UPC, 'LRTB');
                $pdf->Cell(20, 6, $ord->PROD, 'LRTB');/// Producto de la Orden
                if($sust == 0 ){
                    $pdf->Cell(20, 6,'' , 'LRTB'); // Sustituto 
                    $pdf->Cell(20, 6, number_format($ord->ASIG,0), 'LRTB',0,'R');
                    $pdf->Cell(20, 6, number_format($ord->CAJAS,0), 'LRTB',0,'R');
                    $asig = $ord->ASIG;
                }else{
                    $sus=0;
                    foreach ($pres as $ss) {
                        if($sus>0){     
                            $pdf->Cell(28, 6, '', 'LRTB');
                            $pdf->Cell(20, 6, '', 'LRTB');
                        }
                        $pdf->Cell(20, 6, $ss->NUEVO , 'LRTB'); // Sustituto 
                        $pdf->Cell(20, 6, number_format($ss->CANT,0), 'LRTB',0,'R');
                        $pdf->Cell(20, 6, number_format($ord->CAJAS,0), 'LRTB',0,'R');
                        $asig = $ss->CANT;
                        $sus++;
                        $pdf->Ln();
                    }
                }
                $uni = $ord->CAJAS;
                $residuo = fmod($asig,$uni);
                $resi = '';
                $total = bcdiv($asig,$uni,0);
                if($residuo > 0 ){
                    $resi= " + 1C/".$residuo." ";
                    $total +=1;
                }
                //$pdf->Cell(25, 6, number_format($ord->CAJAS,0)."C/".$ord->UNIDAD.$resi , 'LRTB',0,'R');
                $i=0;
                foreach($pos as $pst){
                    $i++;
                    if($pst->COMPONENTES > 1){
                        $ubicacion = ' Lin:  '.$pst->LINEA.'  Tar: '.$pst->TARIMA.'  Cant:  '.number_format($pst->PIEZAS,0).' - '.$pst->PRODUCTO.''."\n";
                    }else{
                        $ubicacion = ' Lin:  '.$pst->LINEA.'  Tar: '.$pst->TARIMA.'  Cant:  '.number_format($pst->PIEZAS,0).' - '.$pst->PRODUCTO.''."\n";
                    }
                    if($i >=1){
                        $ubi[] = ($ubicacion);
                    }
                }
                $pdf->Cell(20, 6, number_format($total,0), 'LRTB',0,'R');
                $pdf->Cell(70, 6, utf8_decode($ubicacion), $m,0,'R');
                $pdf->Cell(40, 6, utf8_decode($ord->ETIQUETA) , 'LRTB');
                $pdf->Ln();
                if($i >= 2){
                    for($l=0; $l < count($ubi)-1 ; $l++) { 
                        $pdf->Cell(28, 4,"",'LB',0,'R');
                        $pdf->Cell(20, 4,"",'B',0,'R');
                        $pdf->Cell(20, 4,"",'B',0,'R');
                        $pdf->Cell(20, 4,"",'B',0,'R');
                        $pdf->Cell(20, 4,"",'B',0,'R');
                        $pdf->Cell(20, 4,"",'B',0,'R');
                        $pdf->Cell(70, 4,$ubi[$l],'LR',0,'R');
                        $pdf->Cell(40, 4,"",'RBT',0,'R');
                        $pdf->Ln();
                    }
                }
            }
        }
        //die;
        $pdf->SetFont('Arial', 'I',10);
        $pdf->Ln(10);
        $pdf->Write(6,"_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_- FIN DEL REPORTE _-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-");
        $pdf->Ln();
        $pdf->Ln();
        $encargado='';
        $persona = $data->perSurt($cabecera->ID_ORD, $t='', $param);
        if(count($persona)>0){foreach($persona as $pers){$encargado=$pers->NOMBRE;}}
        $pdf->Write(6,"Asignado a: ".$encargado);
        $ruta='C:\\xampp\\htdocs\\Reportes_Almacen\\';
        ob_end_clean();
        $pdf->Output($ruta.'Picking list'.$cabecera->ID_ORD.'_'.$param.'.pdf', 'i');
    }

    function surte($surte, $orden, $comps){
        if($_SESSION['user']){
            $data = new wms;
            $res=$data->surte($surte, $orden, $comps);
            return $res;
        }
    }

    function reasig($idcomp, $compp, $comps, $t){
        if($_SESSION['user']){
            $data = new wms;
            $res=$data->reasig($idcomp, $compp, $comps, $t);
            return $res;
        }
    }

    function mapa($opc, $param){
        if($_SESSION['user']){
            $data = new wms;
            $infoA1=$data->mapa($opc=' where alm =  '.$param, $param);
            $uni = $data->unidades(" where status =1 order by factor");
            $pagina = $this->load_template('Reportes');
            $html = $this->load_page('app/views/pages/almacenes/p.mapa.php');
            ob_start();
            include 'app/views/pages/almacenes/p.mapa.php';
            $table = ob_get_clean();
            $pagina = $this->replace_content('/\#CONTENIDO\#/ms', $table, $pagina);
            $this->view_page($pagina); 
        } else {
            $e = "Favor de Iniciar Sesión";
            header('Location: index.php?action=login&e=' . urlencode($e));
            exit;
        }
    }

    function ingMap($comps, $prod, $uni, $cant, $pzas, $ft, $t, $cat){
        if($_SESSION['user']){
            $data = new wms;
            $res=$data->ingMap($comps, $prod, $uni, $cant, $pzas, $ft, $t, $cat);
            return $res;
        }   
    }

    function dispLin($idc){
        if($_SESSION['user']){
            $data = new wms; 
            $res=$data->dispLin($idc);
            return $res;
        }
    }

    function prods($idc){
        if($_SESSION['user']){
            $data = new wms; 
            $res=$data->prods($idc);
            return $res;
        }   
    }

    function reuMap($idc, $opc){
        if($_SESSION['user']){
            $data = new wms; 
            $res=$data->reuMap($idc, $opc);
            return $res;
        }
    }

    function usoComp($idc, $opc){
        if($_SESSION['user']){
            $data = new wms; 
            $res=$data->usoComp($idc, $opc);
            return $res;
        }
    }

    function asiSurt($ord, $cedis, $nombre){
        if($_SESSION['user']){
            $data = new wms;
            $res=$data->asiSurt($ord, $cedis, $nombre);
            return $res;
        }
    }

    function finSurt($ord, $cedis){
        if($_SESSION['user']){
            $data = new wms;
            $res=$data->finSurt($ord, $cedis);
            return $res;
        }    
    }

    function facOrdd($ordd, $uni, $t){
        if($_SESSION['user']){
            $data = new wms;
            $res=$data->facOrdd($ordd, $uni, $t);
            return $res;
        }        
    }

    function correos2($opc, $datos){
        if($_SESSION['user']){
            $data = new wms;
            $res=$data->correos2($opc, $datos);
            return $res;
        }   
    }

    function actCorreo($datos){
        if($_SESSION['user']){
            $data = new wms;
            $res=$data->actCorreo($datos);
            return $res;
        }   
    }

    function posiciones($prod){
        if($_SESSION['user']){
            $data = new wms;
            $res=$data->posiciones($prod);
            return $res;
        }      
    }

    function liberar($movs){
        if($_SESSION['user']){
            $data=new wms;
            $res=$data->liberar($movs);
            return $res;
        }
    }

    function movsProd($prod){
        $usuario = $_SESSION['user']->NOMBRE;   
        $xls= new PHPExcel();
        $data = new wms;
        $info = $data->movsProd($prod);
        $col='A';$ln=13; $i=0;$sali=0; $entr=0; $sali1=0; $entr1=0; $sali2=0; $entr2=0;
        
        $xls->setActiveSheetIndex()
            ->setCellValue('A1', 'Importadora Mizco SA de CV')
            ->setCellValue('A2', 'Reporte de Movimientos por producto')
        ;

        $xls->setActiveSheetIndex()
            ->setCellValue($col.$ln,   'Num')
            ->setCellValue(++$col.$ln, 'Estado')
            ->setCellValue(++$col.$ln, 'Movimiento')
            ->setCellValue(++$col.$ln, 'Tipo' )
            ->setCellValue(++$col.$ln, 'Almacen')
            ->setCellValue(++$col.$ln, 'Orden')
            ->setCellValue(++$col.$ln, 'Producto')
            ->setCellValue(++$col.$ln, 'Descripcion')
            ->setCellValue(++$col.$ln, 'Fecha')
            ->setCellValue(++$col.$ln, 'Usuario')
            ->setCellValue(++$col.$ln, 'Cantidad')
            ->setCellValue(++$col.$ln, 'Unidad')
            ->setCellValue(++$col.$ln, 'Piezas')
            ->setCellValue(++$col.$ln, 'Disponible')            
            ->setCellValue(++$col.$ln, 'Linea')
            ->setCellValue(++$col.$ln, 'Tarima')
            ->setCellValue(++$col.$ln, 'Cliente')
            ->setCellValue(++$col.$ln, 'Cedis')
            ->setCellValue(++$col.$ln, 'Categoria')
            ;
        $col= 'A';
        foreach($info['entradas'] as $ent){
            $ln++;$i++;$entr+=$ent->PIEZAS;
            $entr1 = $ent->ALMACEN == 1? $entr1+$ent->PIEZAS:$entr1+0;
            $entr2 = $ent->ALMACEN == 2? $entr2+$ent->PIEZAS:$entr2+0;

            $xls->setActiveSheetIndex()
                ->setCellValue($col.$ln, $i )
                ->setCellValue(++$col.$ln, $ent->STATUS)
                ->setCellValue(++$col.$ln, $ent->MOV)
                ->setCellValue(++$col.$ln, $ent->TIPO)
                ->setCellValue(++$col.$ln, $ent->ALMACEN)
                ->setCellValue(++$col.$ln, $ent->COLOR)
                ->setCellValue(++$col.$ln, $ent->INTELISIS)
                ->setCellValue(++$col.$ln, $ent->PROD)
                ->setCellValue(++$col.$ln, $ent->FECHA_INGRESO)
                ->setCellValue(++$col.$ln, $ent->USR_ENT)
                ->setCellValue(++$col.$ln, $ent->CANT)
                ->setCellValue(++$col.$ln, $ent->UNIDAD)
                ->setCellValue(++$col.$ln, $ent->PIEZAS)
                ->setCellValue(++$col.$ln, $ent->DISPONIBLE)
                ->setCellValue(++$col.$ln, $ent->LINEA)
                ->setCellValue(++$col.$ln, $ent->TARIMA)
                ->setCellValue(++$col.$ln, '')
                ->setCellValue(++$col.$ln, '')
                ->setCellValue(++$col.$ln, $ent->CATEGORIA)
                ;
            $col='A';
        }

        foreach($info['salidas'] as $sal){
            $ln++;$i++;$sali +=$sal->PIEZAS;
            $sali1 = $sal->ID_ALMACEN == 1? $sali1+$sal->PIEZAS:$sali1+0;
            $sali2 = $sal->ID_ALMACEN == 2? $sali2+$sal->PIEZAS:$sali2+0;

            $xls->setActiveSheetIndex()
                ->setCellValue($col.$ln, $i )
                ->setCellValue(++$col.$ln, $sal->STATUS)
                ->setCellValue(++$col.$ln, $sal->SERIE.'-'.$sal->FOLIO)
                ->setCellValue(++$col.$ln, 'S')
                ->setCellValue(++$col.$ln, $sal->ID_ALMACEN)
                ->setCellValue(++$col.$ln, $sal->ORDEN)
                ->setCellValue(++$col.$ln, $sal->PROD)
                ->setCellValue(++$col.$ln, $sal->DESCR)
                ->setCellValue(++$col.$ln, $sal->FECHA)
                ->setCellValue(++$col.$ln, $sal->NOMBRE)
                ->setCellValue(++$col.$ln, $sal->PIEZAS * -1)
                ->setCellValue(++$col.$ln, 'Pieza')
                ->setCellValue(++$col.$ln, $sal->PIEZAS *-1 )
                ->setCellValue(++$col.$ln, '')
                ->setCellValue(++$col.$ln, $sal->LINEA)
                ->setCellValue(++$col.$ln, $sal->TARIMA)
                ->setCellValue(++$col.$ln, $sal->CLIENTE)
                ->setCellValue(++$col.$ln, $sal->CEDIS)
                ->setCellValue(++$col.$ln, $sal->CATEGORIA)
                ;
            $col='A';
        }

        $xls->getActiveSheet()->getColumnDimension($col)->setWidth(5);
        $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(12);
        $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(5);
        $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(10);
        $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(15);
        $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(15);
        $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(50);
        $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(20);
        $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(50);
        $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(15);
        $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(15);
        $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(12);
        $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(12);
        $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(12);
        $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(20);
        $xls->getActiveSheet()->getColumnDimension(++$col)->setWidth(20);

        $xls->setActiveSheetIndex()
            ->mergeCells('A3:C3')
            ->setCellValue('A3', 'Piezas Entradas: ')
            ->setCellValue('D3', number_format($entr,2))

            ->setCellValue('A4', 'Piezas Almacen 1: ')
            ->setCellValue('D4', number_format($entr1,2))

            ->setCellValue('A5', 'Piezas Almacen 2: ')
            ->setCellValue('D5', number_format($entr2,2))

            ->setCellValue('A7', 'Piezas Salidas: ')
            ->setCellValue('D7', number_format($sali,2))

            ->setCellValue('A8', 'Salidas Almacen 1: ')
            ->setCellValue('D8', number_format($sali1,2))

            ->setCellValue('A9', 'Salidas Almacen 2: ')
            ->setCellValue('D9', number_format($sali2,2))

            ->setCellValue('A11', 'Disponibilidad globla: ')
            ->setCellValue('D11', number_format($entr - $sali,2))

        ;

        /// CAMBIANDO EL TAMAÑO DE LA LINEA.
        $col='A';
        //$xls->getActiveSheet()->getColumnDimension($col)->setWidth(4);
        
        //$xls->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        /// Unir celdas
        $xls->getActiveSheet()->mergeCells('A1:P1');
        // Alineando
        $xls->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal('center');
        /// Estilando
        $xls->getActiveSheet()->getStyle('A1')->applyFromArray(
            array('font' => array(
                    'size'=>20,
                )
            )
        );

        $ruta='C:\\xampp\\htdocs\\Reportes_Almacen\\';
                if(!file_exists($ruta) ){
                    mkdir($ruta);
                }
                $nom='Movimientos del producto '.$prod.'_'.date("d-m-Y H_i_s").'_'.$usuario.'.xlsx';
                $x=PHPExcel_IOFactory::createWriter($xls,'Excel2007');
            /// salida a descargar
                $x->save($ruta.$nom);
                ob_end_clean();
                return array("status"=>'ok',"nombre"=>$nom, "ruta"=>$ruta, "completa"=>'..\\..\\Reportes_Almacen\\'.$nom, "tipo"=>'x');
    }

    function delMovs($alm, $tipo){
        $data= new wms;
        $res = $data->delMovs($alm, $tipo);
        return $res;
    }

    function pxc($ordd){
        $data = new wms;
        $res = $data->pxc($ordd);
        return $res;
        //echo '</br>Se busca coincidencia con la ordd '. $ordd;
    }

    function desc($id){
        $data = new wms;
        $res = $data->desc($id);
        return $res;
    }

    function detOcInt($doc){
        $data_i = new intelisis;
        $data = new wms;
        $datos = $data_i->detDoc($doc);
        $inserta = $data->insDetOcInt($datos);
        //$infoWms = $data->orden($doc); no es necesario por que ya tenemos la orden de compra.
        $infoInt = $data_i->infoInt($doc);/// Obtiene la informacion para actualizar en wms.
        $sinc = $data->sincOrd($infoInt, 'c'); /// sincroniza la informacion de intelisis,solo la cabecera, se utiliza para actualizar el status.
        return $sinc;
    }

    function sincPres($prod){
        $data_i = new intelisis;
        $datos= $data_i->sincPres($prod);
        return $datos;
    }

    function pres($info){
        $data = new wms;
        $res = $data->pres($info);
        return $res;
    }

    function reubPza($datos){
        $data = new wms;
        $res = $data->reubPza($datos);
        return $res;
    }

    function sincInt($idOrdd, $t){
        $data = new wms;
        $data_i = new intelisis;
        $info=$data->sincIntWms($idOrdd);
        if($t == 'i'){// Traemos los valores desde intelisis
            $infInt=$data_i->sincInt($info);
            $sinc=$data->sincInt($info, $infInt);
        }elseif($t== 'w'){/// Enviamos los valores a intelisis
            $sinc=$data_i->sincIntWms($info);
        }
        return $info;   
    }

    function utilOdn($t, $ido, $obs){
        $data = new wms;
        $res = $data->utilOdn($t, $ido, $obs);
        return $res;
    }
}
?>


<?php

require_once 'app/model/database.php';
require_once('app/fpdf/fpdf.php');
require_once('app/views/unit/commonts/numbertoletter.php');
require_once 'app/simplexlsx-master/src/SimpleXLSX.php';
require_once('app/Classes/PHPExcel.php');

/* Clase para hacer uso de database */
class wms extends database {
    /* Comprueba datos de login */
    function productos($op){
        $data = array();
        $this->query="SELECT * FROM FTC_ALMACEN_PRODUCTOS";
        $res=$this->EjecutaQuerySimple();
        while ($tsArray=ibase_fetch_object($res)) {
            $data[]=$tsArray;
        }
        return $data;
    }

    function refresh($intelisis){
        $i=0; $n=0;$a=0;
        foreach($intelisis as $int){
            $prod=strtoupper($int[0]);
            $data = array(); $i++;$upc = $int['DESACodigoBarras'];
            $this->query="SELECT * FROM FTC_ALMACEN_PROD_INT WHERE upper(ID_INT) = '$prod'";
            $res=$this->EjecutaQuerySimple();
            while($tsarray=ibase_fetch_object($res)){$data[]=$tsarray;}
            if(count($data)==0){
                $n++;
                $this->query="INSERT INTO FTC_ALMACEN_PROD_INT (ID_PINT, ID_INT, DESC, PZS_ORIG, LARGO, ANCHO, ALTO, PZS_PALET_O, UNIDAD_ORIG, TIPO_INT, STATUS, UPC) VALUES (null, '$prod' ,'$int[2]', null, null, null, null, null, null, '$int[24]', 'Alta', '$upc')";
                $this->grabaBD();
            }else{
                $a++;
                $this->query="UPDATE FTC_ALMACEN_PROD_INT SET UPC = '$upc' where upper(id_int) = '$prod'";
                $this->queryActualiza();
            }
        }
        echo '<b>Se detectan '.$i.', analizan '.$i.' ingresa '.$n.'</b>';
        echo '<br/><b> Se actualizan: '.$a;
        return;
    }

    function actProd($cve, $lg, $an, $al, $p, $ou){
        $lg = ($lg=='')? 0:$lg; $an = ($an=='')? 0:$an; $al = ($al=='')? 0:$al; $p = ($p=='')? 0:$p; $ou = ($ou=='')? 0:$ou; 
        $volT = 960000;// cambiarla por una variable global.
        if($lg > 0 and $an > 0 and $al > 0 ){
            $vol = $lg * $an * $al;
            $p = $volT / $vol;
        }
        $this->query="UPDATE FTC_ALMACEN_PROD_INT SET largo= $lg, ancho =$an, alto=$al, pzs_palet_o = $p, unidad_orig = $ou where ID_PINT = $cve";
        $res=$this->queryActualiza();
        if($res >= 1){
            $m= 'Se ha actualizado correctamente';
        }else{
            $m= 'Ocurrio un error favor de intentar de nuevo';
        }
        return array("msg"=>$m);
    }

    function componentes($op, $param){
        $data=array();
        $p='';$i=0;$salida='';
        if(empty($param)){
            $f = ' first 150 ';
        }else{
            $f='';
        }
        if($param != ''){
            $param=json_decode($param);
            $f = '';
            foreach ($param as $key => $value) {
                if($key=='t' and $value != 'none'){
                    $p .= ' and ID_TIPO = '.$value.' ';$i++;
                }
                if($key=='a' and $value != 'none'){
                    $p .= ' and ID_ALM = '.$value.' ';$i++;
                }
                if($key=='p' and $value != 'none'){
                    $p .= " and  id_PRODUCTOS in ('".$value."') ";$i++;
                }
                if($key=='e' and $value != 'none'){
                    $p .= " and id_status = ".$value." ";$i++;
                }
                if($key=='as' and $value != 'none'){
                    if($value == 'si'){
                        $p .= " and ID_COMPP  is not null ";$i++;
                    }else{
                        $p .= " and ID_COMPP  is null ";$i++;
                    }
                }
                if($key=='fi' and $value !=""){
                        $p .= " and fecha_i >= '".$value."'";$i++;
                }
                if($key=='ff' and $value !=""){
                        $p .= " and fecha_f <= '".$value."'";$i++;
                }
                if ($key=='out'){
                    $salida = $value;
                }
            }
            if($i > 0){$p=' Where id_comp > 0 '.$p;}
        }
        $this->query="SELECT $f c.*,
        (SELECT coalesce(SUM(piezas),0) FROM FTC_ALMACEN_MOV AM WHERE AM.COMPS = c.ID_COMP and am.tipo='e' and am.status='F' and c.id_tipo = 1 ) AS entradasS, 
        (SELECT coalesce(SUM(piezas),0) FROM FTC_ALMACEN_MOV AM WHERE AM.COMPP = c.ID_COMP and am.tipo='e' and am.status='F' and c.id_tipo = 2) AS entradasP , 
        v.dispONIBLE AS DISP
        FROM FTC_ALMACEN_COMPONENTES c left join ftc_alm_comp_disp v on v.id_c = c.id_comp $op $p order by id_comp desc";
        //echo $this->query;
        $res=$this->EjecutaQuerySimple();
        while ($tsArray=ibase_fetch_object($res)){
            $data[]=$tsArray;
        }
        if($salida == 'ir' or $salida == ''){
            return $data;
        }elseif ($salida == 'x') {
            $r= $this->compExcel($data, $param);
            return $r;
            exit();
        }elseif ($salida == 'p') {
            $this->compPdf($data, $param);
            exit();
        }
    }

    function movs($al, $cp, $cs, $prod){
        $data=array();
        if(!empty($al) and $cp=='a'){
           $consulta="SELECT md.id_compp, (select (etiqueta) from FTC_ALMACEN_COMPONENTES where id_comp =md.id_compp ) as etiqueta from FTC_ALMACEN_MOV_DET md WHERE md.almacen =".$al." and md.disponible > 0  and intelisis =  '$prod' group by md.id_compp"; 
        }elseif(!empty($al) and $cp!='a'){
            $consulta="SELECT md.id_compp, (select (etiqueta) from FTC_ALMACEN_COMPONENTES where id_comp =md.id_compp ) as etiqueta from FTC_ALMACEN_MOV_DET md WHERE md.almacen =".$al." and md.disponible > 0   group by md.id_compp";
        }
        $this->query =$consulta;
        //echo $this->query;
        $res=$this->EjecutaQuerySimple();
        while ($tsArray=ibase_fetch_object($res)) {
            $data[]=$tsArray;
        }
        return $data;
    }

    function movSal($fol){
        $data=array();
        $this->query="SELECT * FROM FTC_ALMACEN_MOV_SALIDA WHERE FOLIO = $fol";
        $rs=$this->EjecutaQuerySimple();
        while($tsArray=ibase_fetch_object($rs)){
            $data[]=$tsArray;
        }
        return $data;
    }

    function finSal($fol){
        $this->query="UPDATE FTC_ALMACEN_MOV_SAL SET STATUS='F' WHERE FOLIO = $fol";
        $this->queryActualiza();
        return array("sta"=>'ok');
    }

    function compSal($cp, $pr){
        $data=array();
        $this->query="SELECT * FROM FTC_ALMACEN_MOV_DET WHERE id_compp = $cp and disponible >0 and INTELISIS = '$pr'";
        $rs=$this->EjecutaQuerySimple();
        while($tsArray=ibase_fetch_object($rs)){
            $data[]=$tsArray;
        }
        return $data;
    }

    function exeSal($datos, $fol){
        $usuario = $_SESSION['user']->ID;
        $ser='A';
        $datos=explode(",", $datos);
        if($fol=='x'){
            $rs=$this->calculaFolio($fol, $ser);
            $fol= $rs;
        }
        for ($i=0; $i < count($datos)-1; $i++) {
            $info=explode(":",$datos[$i]);
            $cant = $info[3];
            $cs=$info[5];
            $cp=$info[7];
            $mov=$info[9];
            $obs=$info[11];
            $this->query="INSERT INTO FTC_ALMACEN_MOV_SAL (ID_MS, ID_COMPS, CANT, ID_ORDD, USUARIO, FECHA, STATUS, ID_MOV, PIEZAS, UNIDAD, ID_COMPP, FOLIO, SERIE, ID_PROD, categoria, OBS) VALUES (NULL, $cs, 0, null, $usuario, current_timestamp, 'P', '$mov', $cant, 1, $cp, $fol, '$ser', (select m.prod from ftc_almacen_mov m where m.id_am = $mov), (SELECT CATEGORIA FROM FTC_ALMACEN_MOV m where m.ID_AM = $mov), '$obs')";
            $this->grabaBD();
        }
        return array("folio"=>$fol);
    }

    function calculaFolio($fol, $ser){
        $this->query="SELECT coalesce(MAX(folio),0) + 1 as folio from ftc_almacen_mov_sal where serie='$ser'";
        $rs=$this->EjecutaQuerySimple();
        $row = ibase_fetch_row($rs);
        return $folio=$row[0];
    }
    
    function compLib($op, $param){
        $data = array();
        $this->query="SELECT * FROM FTC_ALMACEN_COMPONENTES $op ";
        $res=$this->EjecutaQuerySimple();
        while($tsArray=ibase_fetch_object($res)){
            $data[]=$tsArray;
        }
        return $data;
    }

    function compExcel($data){
        $usuario = $_SESSION['user']->NOMBRE;   
        $xls= new PHPExcel();
        //Cabecera:
        $col = 'A'; $ln=10; $i = 0;
        $xls->setActiveSheetIndex()
                ->setCellValue($col.$ln,"Id")
                ->setCellValue(++$col.$ln,"Etiqueta")
                ->setCellValue(++$col.$ln,"Descripción")
                ->setCellValue(++$col.$ln,"Tipo")
                ->setCellValue(++$col.$ln,"Largo")
                ->setCellValue(++$col.$ln,"Ancho")
                ->setCellValue(++$col.$ln,"Alto")
                ->setCellValue(++$col.$ln,"Almacen")
                ->setCellValue(++$col.$ln,"Productos")
                ->setCellValue(++$col.$ln,"Observaciones")
                ->setCellValue(++$col.$ln,"Estado")
                ->setCellValue(++$col.$ln,"Entradas")
                ->setCellValue(++$col.$ln,"Salidas")
                ->setCellValue(++$col.$ln,"Existencias")
        ;
        $col = 'A';$ln=10; $i = 0;$tot=0; $totEnt=0; $totSal=0;
            foreach ($data as $row) {
                $i++;
                $ln++;
                if($row->ID_TIPO == 1){
                        $entradas = $row->ENTRADASS;
                        $salidas = $row->SALIDASS;       
                    }else{
                        $entradas = $row->ENTRADASP;
                        $salidas = $row->SALIDASP;       
                    }
                $xls->setActiveSheetIndex()
                        ->setCellValue($col.$ln,$i)
                        ->setCellValue(++$col.$ln,$row->ETIQUETA)
                        ->setCellValue(++$col.$ln,$row->DESC)
                        ->setCellValue(++$col.$ln,$row->TIPO)
                        ->setCellValue(++$col.$ln,number_format($row->MEDICION=='m'? $row->LARGO/100: $row->LARGO,2).' '.$row->MEDICION)
                        ->setCellValue(++$col.$ln,number_format($row->MEDICION=='m'? $row->ANCHO/100: $row->ANCHO,2).' '.$row->MEDICION)
                        ->setCellValue(++$col.$ln,number_format($row->MEDICION=='m'? $row->ALTO/100: $row->ALTO,2).' '.$row->MEDICION)
                        ->setCellValue(++$col.$ln,$row->ALMACEN)
                        ->setCellValue(++$col.$ln,$row->PRODUCTOS)
                        ->setCellValue(++$col.$ln,$row->OBS)
                        ->setCellValue(++$col.$ln,$row->STATUS)
                        ->setCellValue(++$col.$ln,$entradas)
                        ->setCellValue(++$col.$ln,$salidas)
                        ->setCellValue(++$col.$ln,$entradas - $salidas)
                ;
                $totEnt += $entradas;
                $totSal += $salidas;
                $tot += ($entradas - $salidas);
                $col='A';
            }

            $xls->setActiveSheetIndex()
                ->setCellValue('A1', "IMPORTADORA MIZCO SA DE CV")
                ->setCellValue('A2', "Reporte de Componentes ")
                ->setCellValue('A3',  "")
                ->setCellValue('A4', "Elaborado por: ")
                ->setCellValue('B4', $usuario)
                ->setCellValue('A5', "Fecha de Elaboracion: ")
                ->setCellValue('B5', date("d-m-Y H:i:s" ) )
                ->setCellValue('A6', "Total Componetes:")
                ->setCellValue('B6', count($data))
                ->setCellValue('A7', "Total Piezas ")
                ->setCellValue('B7', "Total Piezas Entradas")   
                ->setCellValue('C7', "Total Piezas Salidas")   
                ->setCellValue('A8', number_format($tot))
                ->setCellValue('B8', number_format($totEnt))
                ->setCellValue('C8', number_format($totSal))
                ->setCellValue('A9', "Filtros aplicados ")
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
            //// Crear una nueva hoja 
                //$xls->createSheet();
            /// Crear una nueva hoja llamada Mis Datos
            /// Descargar
            $ruta='C:\\xampp\\htdocs\\Reportes_Almacen\\';
                if(!file_exists($ruta) ){
                    mkdir($ruta);
                }
                $nom='Reporte Componentes del '.date("d-m-Y H_i_s").'_'.$usuario.'.xlsx';
                //header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                //header("Content-Disposition: attachment;filename=01simple.xlsx");
                //header('Cache-Control: max-age=0');
            /// escribimos el resultado en el archivo;
                $x=PHPExcel_IOFactory::createWriter($xls,'Excel2007');
            /// salida a descargar
                $x->save($ruta.$nom);
                ob_end_clean();
                return array("nombre"=>$nom, "ruta"=>$ruta, "completa"=>'..\\..\\Reportes_Almacen\\'.$nom, "tipo"=>'x');
    }

    function compPdf($data, $param){
        $usuario = $_SESSION['user']->NOMBRE;   
        $pdf = new FPDF('L', 'mm', 'Letter');
        $pdf->AddPage();
        $pdf->Image('app/views/images/LOGOSELECT.jpg', 5, 5, 30, 28);
        $pdf->SetFont('Arial', 'B', 10);
        
        $pdf->SetFont('Arial', 'I',10);
        $pdf->Ln(28);
        $pdf->SetX(10);
        $pdf->write(6, "Elaborado por :". $usuario. " el ".date("d-m-Y h:i:s")."\n");
        $pdf->write(6, "Filtros aplicados:\n");
        $pdf->write(6, "");
        
        $pdf->Ln(28);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(15, 8, "Etiqueta", 1);
        $pdf->Cell(25, 8, "Descripción", 1);
        $pdf->Cell(50, 8, "Tipo", 1);
        $pdf->Cell(42, 8, "Largo x Ancho x Alto", 1);
        $pdf->Cell(15, 8, "Almacen", 1);
        $pdf->Cell(35, 8, "Productos", 1);
        $pdf->Cell(20, 8, "Observaciones", 1);
        $pdf->Cell(15, 8, "Estado", 1);
        $pdf->Cell(15, 8, "Entradas", 1);
        $pdf->Cell(15, 8, "Salidas", 1);
        $pdf->Cell(15, 8, "Existencia", 1);
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 7);
        $entradas=0;$salidas=0;
        foreach ($data as $row) {
            if($row->ID_TIPO == 1){
                    $entradas = $row->ENTRADASS;
                    $salidas = $row->SALIDASS;       
                }else{
                    $entradas = $row->ENTRADASP;
                    $salidas = $row->SALIDASP;       
                }
            $pdf->Cell(15, 8, $row->ETIQUETA, 1);
            $pdf->Cell(25, 8, substr($row->DESC, 0, 15), 1);
            $pdf->Cell(50, 8, substr($row->TIPO,0,50), 1);

            $pdf->Cell(42, 8, 
                (number_format($row->MEDICION=='m'? $row->LARGO/100: $row->LARGO,2).' '.$row->MEDICION).' x '.
                (number_format($row->MEDICION=='m'? $row->ANCHO/100: $row->ANCHO,2).' '.$row->MEDICION).' x '.
                (number_format($row->MEDICION=='m'? $row->ALTO/100: $row->ALTO,2).' '.$row->MEDICION),1 );
            
            $pdf->Cell(15, 8, $row->ALMACEN, 1);
            $pdf->Cell(35, 8, $row->PRODUCTOS, 1);
            $pdf->Cell(20, 8, $row->OBS, 1);
            $pdf->Cell(15, 8, $row->STATUS, 1,0, 'C');
            $pdf->Cell(15, 8, number_format($entradas,0), 1,0, 'R');
            $pdf->Cell(15, 8, number_format($salidas,0), 1,0,'R');
            $pdf->Cell(15, 8, number_format($entradas - $salidas,0), 1,0,'R');

            $pdf->Ln();
        }
       
        $pdf->SetFont('Arial', 'I',10);
        $pdf->Ln(10);
        //$pdf->SetX(140);
        $pdf->Write(6,"_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_- FIN DEL REPORTE _-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-");
        $pdf->Ln();
          
        ob_end_clean();
        $pdf->Output('Prueba.pdf', 'i');

    }

    function almacenes($op){
        $data=array();
        $this->query="SELECT * FROM FTC_ALMACEN $op";
        $res=$this->EjecutaQuerySimple();
        while ($tsArray=ibase_fetch_object($res)) {
            $data[]=$tsArray;
        }
        return $data;
    }

    function tipoComp($op){
        $data = array();
        $this->query="SELECT * FROM FTC_ALMACEN_TIPOS WHERE SUBTIPO = '$op'";
        $res=$this->EjecutaQuerySimple();
        while ($tsArray=ibase_fetch_object($res)) {
            $data[]=$tsArray;
        }
        return $data;
    }

    function addComp($et, $desc, $selT, $lg, $an, $al, $alm, $ob, $fact){
        if($fact=='m'){
            $lg=$lg*100;$an=$an*100;$al=$al*100;
        }
        $this->query="INSERT INTO FTC_ALMACEN_COMP (ID_COMP, TIPO, LARGO, ANCHO, ALTO, ALMACEN, PRODUCTO, OBS, STATUS, DESC, DESC_AUTO, ETIQUETA) 
        VALUES (null, $selT, $lg, $an, $al, $alm, '', '$ob', 1, '$desc', '', '$et')";
        $res=$this->grabaBD();
        if($res >= 1){
            $m= "Se inserto correctamente ";
        }else{
            $m= 'No pudimos insertar el componente';
        }
        return array("msg"=>$m);
    }

    function cpComp($cns, $can, $id, $s, $f, $sp){
        $l='';$n=0;
        $this->query="SELECT * FROM FTC_ALMACEN_COMP WHERE ID_COMP = $id";
        $res=$this->EjecutaQuerySimple();

        while ($tsArray=ibase_fetch_object($res)) {
            $data[]=$tsArray;
        }
        $c=0;
        foreach ($data as $k){
            $etiqueta='';
            for ($i=0; $i <= $can ; $i++) { 
                $etiqueta = $s.$sp.$f;
                if($cns != 'none'){
                    if($cns=='le'){
                        ++$s;
                    }elseif($cns=='nu'){
                        $f++;
                    }elseif($cns == 'am'){
                        $f++; ++$s;
                    }
                }
                $this->query="INSERT INTO FTC_ALMACEN_COMP (ID_COMP, TIPO, LARGO, ANCHO, ALTO, ALMACEN, PRODUCTO, OBS, STATUS, DESC, DESC_AUTO, ETIQUETA) VALUES (null, $k->TIPO, $k->LARGO, $k->ANCHO, $k->ALTO, $k->ALMACEN, '', '$k->OBS', $k->STATUS, '$k->DESC', '', '$etiqueta')";
                $res=$this->grabaBD();
                if($res == 1){
                    $c++;
                }
            }
        }
        return array("msg"=>'Se crearon '.number_format($c,0).' componentes'); 
    }


    function movimientos($op, $param){
        $data = array();
        $p='';$i=0;$salida='';
        //echo 'valor de param: '.$param;
        if(@$param != ''){
            $param=json_decode($param);
            foreach ($param as $key => $value) {
                if($key=='t' and $value != 'none'){
                    $p .= " and ID_TIPO = '".$value."' ";$i++;
                    $tipo = $value;
                }
                if($key=='a' and $value != 'none'){
                    $p .= ' and ID_ALMACEN = '.$value.' ';$i++;
                }
                if($key=='p' and $value != ''){
                    $pro = explode(":", $value);
                    //print_r($pro);
                    $p .= " and  PROD CONTAINING('".trim($pro[0])."') ";$i++;
                }
                if($key=='e' and $value != 'none'){
                    $p .= " and id_status = '".$value."' ";$i++;
                }
                if($key=='us' and $value != 'none'){
                    $p .= " and id_usuario = ".$value;$i++;
                }
                if($key=='fi' and $value !=""){
                    $p .= " and fecha >= '".$value."'";$i++;
                }
                if($key=='ff' and $value !=""){
                    $p .= " and fecha <= '".$value."'";$i++;
                }
                if($key=='cp' and $value !=""){
                    $comp = explode(":", trim($value));
                    $p .= " and (id_compp = ".$comp[3]." or id_comps= ".$comp[3].") ";$i++;
                }
                if ($key=='out'){
                    $salida = $value;
                }
            }
            if($i > 0){$p=' Where id_am > 0 '.$p;}
        }
        
        if(@$tipo=='s'){
            // echo '<br/>El valor del filtro es: '.$p;
            $this->query="SELECT id_ms as mov, 
                                 id_almacen as almacen,
                                 ORDEN as SIST_ORIGEN,
                                 'Salida' as TIPO, 
                                 nombre as USUARIO,
                                 fecha,
                                 status,
                                 '' as HORA_I,
                                 '' as HORA_F,
                                 CANT, 
                                 PROD,
                                 PIEZAS,
                                 LINEA ||'-'|| TARIMA AS COMPONENTE
                          FROM FTC_ALMACEN_MOV_SALIDA $p order by id_ms desc";
            //echo 'Movimientos de salida: '.$this->query;
            $res=$this->EjecutaQuerySimple();
            while ($tsArray=ibase_fetch_object($res)) {
                $data[]=$tsArray;
            }
        }else{
            $this->query="  SELECT first 50 
                                mov, 
                                max(SIST_ORIGEN) AS SIST_ORIGEN, 
                                (select max(nombre) from FTC_ALMACEN a where a.id =  MAX(AM.ID_ALMACEN)) AS ALMACEN, 
                                MAX(TIPO) AS TIPO, 
                                MAX(FECHA) AS FECHA, 
                                MAX(STATUS) AS STATUS, 
                                MIN(HORA_I) AS HORA_I, 
                                MAX(HORA_F) AS HORA_F, 
                                SUM(CANT) AS CANT, 
                                SUM(PIEZAS) AS PIEZAS  , 
                                MAX(usuario) as usuario, 
                                cast( list(DISTINCT prod) as varchar (3000)) as prod, 
                                (SELECT MAX(ETIQUETA) FROM FTC_ALMACEN_COMPONENTES AC WHERE AC.ID_COMP = max(AM.ID_compp) ) as componente 
                            FROM FTC_ALMACEN_MOVIMIENTO AM $op $p  group by mov order by mov desc";
            //echo 'Consulta de movimientos con filtro: '.$this->query;
            $res=$this->EjecutaQuerySimple();
            while ($tsArray=ibase_fetch_object($res)) {
                $data[]=$tsArray;
            }
            $this->query="SELECT first 50
                            FOLIO AS MOV,
                            IIF(max(ID_ORDD) IS NULL, 'Directa', 'Intelisis') as SIST_ORIGEN,
                            (select max(NOMBRE) from FTC_ALMACEN a where A.ID = max(AM.ID_ALMACEN)) as ALMACEN,
                             'Salida' as TIPO,
                             max(FECHA) as FECHA,
                             case max(STATUS)
                                when 'F' then 'Finalizado'
                                when 'C' then 'Cancelado'
                                when 'P' then 'Pendiente'
                                end as STATUS,
                             '' as HORA_I,
                             '' as HORA_F,
                             sum(CANT) as CANT,
                             sum(PIEZAS) as PIEZAS,
                             max(USUARIO) as USUARIO,
                             cast(list(distinct PROD) as varchar(3000)) as PROD,
                            (select max(ETIQUETA) from FTC_ALMACEN_COMPONENTES AC where AC.ID_COMP = max(AM.ID_COMPP)) as COMPONENTE
                        FROM FTC_ALMACEN_MOV_SALIDA AM $op $p
                        group by FOLIO
                        order by FOLIO desc";
            $res=$this->EjecutaQuerySimple();
            while ($tsArray=ibase_fetch_object($res)) {
                $data[]=$tsArray;
            }
        }
        //print_r($data);
        return $data;
    }

    function aOC($ord){
        $this->query="UPDATE FTC_ALMACEN_ORDEN SET status= 2, FECHA_ASIGNA = current_timestamp where id_ord = $ord and status = 1";
        $res=$this->queryActualiza();
        if($res==1){
            $sta='ok'; $msg="Se ha liberado la orden para Asignacion";
        }else{
            $sta='no'; $msg="La Orden esta eliminada y no se puede liberar";
        }
        return array("sta"=>$sta, "msg"=>$msg);
    }

    function unidades($op){
        $data = array();
        $this->query="SELECT * FROM FTC_ALMACEN_UNIDADES $op";
        $res=$this->EjecutaQuerySimple();
        while ($tsArray=ibase_fetch_object($res)) {
            $data[]=$tsArray;
        }
        return $data;
    }

    function  addMov($tipo, $alm, $compP, $compS, $prod, $uni, $cant, $obs, $mov, $pza, $cat){
        $usuario=$_SESSION['user']->ID;
        $sist='php';
        if($mov=='nuevo'){
            $folio = $this->folioMov($mov);
            $id=$folio[1]; $mov=$folio[0];
            $this->query="UPDATE FTC_ALMACEN_MOV SET SIST_ORIGEN= '$sist', ALMACEN=$alm, TIPO='$tipo', USUARIO=$usuario, FECHA=current_timestamp, STATUS='P', HORA_I=current_time, CANT=$cant, PROD =$prod, UNIDAD=$uni, PIEZAS=$pza, MOV=$mov, COMPP=$compP, COMPS=$compS, OBS='$obs', CATEGORIA = $cat WHERE ID_AM=$id";
            $this->queryActualiza();
            $msg='Se ha inserado el movimiento';
        }else{
            $this->query="INSERT INTO FTC_ALMACEN_MOV (ID_AM, SIST_ORIGEN, ALMACEN, TIPO, USUARIO, FECHA, STATUS, USUARIO_ATT, HORA_I, CANT, PROD, UNIDAD, PIEZAS, MOV, COMPP, COMPS, OBS, CATEGORIA) VALUES (null, '$sist', $alm, '$tipo', $usuario, current_timestamp, 'P', 0, current_time, $cant, $prod, $uni, $pza, $mov, $compP, $compS, '$obs', $cat)";
            $res=$this->grabaBD();
            if($res == 1){
                $msg='Se ha inserado el movimiento';
            }
        }
        return array('msg'=>$msg, "mov"=>$mov);
    }

    function canMov($mov, $mot, $t){

        /// Hay que hacer una distincion de para saber que tipo de movimiento es el que se va a eliminar. 

        $this->query="UPDATE FTC_ALMACEN_MOV SET STATUS = upper('$t'), piezas=0, cant=0 WHERE MOV = $mov and coalesce ((select sum(salidas) from ftc_almacen_mov_det where mov = $mov),0) = 0";
        //echo $this->query;
        $this->queryActualiza();
        $this->actStatus($tabla=4, $tipo='Eliminar', $sub='Movimiento', $ids=$mov, $obs=$mot);
        return array("msg"=>'Se ha cancelado el movimiento');
    }

    function cpLin($base, $cs){
        $this->query="INSERT INTO FTC_ALMACEN_MOV (ID_AM, SIST_ORIGEN, ALMACEN, TIPO, USUARIO, FECHA, STATUS, USUARIO_ATT, HORA_I, HORA_F, CANT, PROD, UNIDAD, PIEZAS, MOV, COMPP, COMPS, OBS, CATEGORIA) SELECT NULL, SIST_ORIGEN, ALMACEN, TIPO, USUARIO, current_timestamp, STATUS, USUARIO_ATT, current_time, HORA_F, CANT, PROD, UNIDAD, PIEZAS, MOV, COMPP, '$cs', OBS, CATEGORIA FROM FTC_ALMACEN_MOV WHERE ID_AM = $base and status='P' RETURNING ID_AM, MOV";
        $res=$this->grabaBD();
        $res=ibase_fetch_object($res);
        if($res->ID_AM >0){
            $msg='Se ha insertado correctamente';
        }else{
            $msg='Ocurrio un error en la insercion, comunmente el error es que el movimiento ha sido finalizado, favor de revisar la informacion';
        }
        return array("msg"=>$msg,"mov"=>$res->MOV);
    }

    function folioMov($mov){
        $res=array();
        if($mov == 'nuevo'){
            $this->query="INSERT INTO FTC_ALMACEN_MOV (ID_AM, MOV) VALUES (null, (select coalesce(max(mov),0) from FTC_ALMACEN_MOV)+1)returning MOV, ID_AM";
            $res=$this->grabaBD();
        }
        $res=ibase_fetch_row($res);
        return $res;
    }

    function movimiento($op, $tipo){
        $data=array();
        $this->query=$tipo=='Entrada'? "SELECT * FROM FTC_ALMACEN_MOVIMIENTO WHERE MOV= $op and status != 'Baja' order by id_AM":"SELECT * FROM FTC_ALMACEN_MOV_SALIDA WHERE SERIE = 'A' and FOLIO= $op and status != 'Baja' order by id_ms";
        $res=$this->EjecutaQuerySimple();
        while ($tsArray=ibase_fetch_object($res)) {
            $data[]=$tsArray;
        }
        return $data;
    }

    function detalleMov($op, $t){
        $data=array(); 
        $tabla = ($t == 'Salida')? 'FTC_ALMACEN_MOV_SALIDA':'FTC_ALMACEN_MOVIMIENTO';
        $this->query="SELECT * FROM $tabla WHERE MOV= $op order by id_AM";
        $res=$this->EjecutaQuerySimple();
        while ($tsArray=ibase_fetch_object($res)) {
            $data[]=$tsArray;
        }
        return $data;
    }

    function delMov($idMov, $tp, $e){
        $status = $tp=='del'? 'B':'F';
        if($tp=='del'){
            $this->query="UPDATE FTC_ALMACEN_MOV SET STATUS = '$status', cant=0, piezas=0 where id_AM = $idMov and status='P'";
            $res=$this->queryActualiza();
            if($res == 1){
                $msg = 'Se ha dado de baja la linea';
                $this->actStatus($tabla=4, $tipo='Cancelar', $sub='Movimiento', $ids=$idMov, $obs='Cancelar Documento');
            }else{
                $msg= 'El movimiento parece estar Finalizado y no permite la edicion de lineas.';
            }    
        }elseif ($tp=='end'){
            if($e == 'Entrada'){
                $this->query="UPDATE FTC_ALMACEN_MOV SET STATUS = '$status', HORA_F = current_timestamp  where MOV = (select mov from FTC_ALMACEN_MOV where id_AM = $idMov) and status='P'";
                $res= $this->queryActualiza();
                if($res>=1){
                    /// $this->creaSalida($idMov); comentada el 14 de junio del 2022, ya que al parecer no debe de hacer ninguna salida.... 
                    $msg='Se ha finalizado el Momiemiento, ya puede imprimir el QR';
                }else{
                    $msg='Se ha finalizado el Momiemiento de Salida, ya puede imprimir el QR';
                }
                    
            }elseif($e=='Salida'){
                $this->query="UPDATE FTC_ALMACEN_MOV_SAL SET STATUS = '$status', FECHA = current_timestamp  where FOLIO = $idMov  and Serie = 'A' and status='P'";
                $res= $this->queryActualiza();
                $msg='Se ha finalizado el Momiemiento, ya puede imprimir el QR';
            }
        }
        return array("msg"=>$msg);
    }

    function creaSalida($idMov){
        $data=arary();
        $this->query="SELECT * FROM FTC_ALMACEN_MOV WHERE MOV=(SELECT MOV FROM FTC_ALMACEN_MOV WHERE ID_AM = $idMov) and status ='F' and tipo='s'";
        $res=$this->EjecutaQuerySimple();
        while ($tsArray=ibase_fetch_object($res)) {
            $data[]=$tsArray;
        }
        foreach ($data as $key){
            $this->query="SELECT * FROM FTC_ALMACEN_MOV WHERE ID_AM = $key->ID_AM";
            $this->EjecutaQuerySimple();
            
            $this->query="INSERT INTO FTC_ALMACEN_MOV_SAL (ID_MS, ID_COMPS, CANT, ID_ORDD, USUARIO, FECHA, STATUS, ID_MOV, PIEZAS, UNIDAD, ID_COMPP) VALUES () ";
        }
    }

    function asocia($cs, $cp, $t, $e) {
        if($t == 'm'){
            $cs =substr($cs, 1);
            $param = " in (".$cs.")";
        }else{
            $param = " = ".$cs;
        }
        $this->query="UPDATE FTC_ALMACEN_COMP SET COMPP = $cp where id_comp $param and status = 1";
        echo $this->query;
        $res=$this->queryActualiza();
        if($res >= 1){
            $msg="Se asociaron correctamente ".$res.' componentes';
        }else{
            $msg="No se pudo asociar, posiblemente algunos de los componentes esten llenos o dados de baja, favor de revisar";
        }
        return array("msg"=>$msg);
    }

    function detMov($opc){
        $data =array();
        $this->query="SELECT m.*, (SELECT SUM(PIEZAS) FROM FTC_ALMACEN_MOV_SAL ms WHERE  ms.ID_COMPS = m.id_comps) as salidas FROM FTC_ALMACEN_MOVIMIENTO m WHERE m.ID_COMPP = $opc or m.ID_COMPS = $opc";
        $res=$this->EjecutaQuerySimple();
        while ($tsArray=ibase_fetch_object($res)) {
            $data[]=$tsArray;
        }
        return $data;
    }

    function datos($t, $al, $c){
        if(empty($c)){
            $this->query = "SELECT nombre,  
                                    case '$t' when 'e' then 'Entrada' when 's' then 'Salida' when 'r' then 'Reacomodo' when 't' then 'Traspaso' when 'd' then 'Entrada x Devolucion' when 'm' then 'Merma' end as tipo,
                                    '' as compp
            FROM FTC_ALMACEN where id = $al";
        }else{
            $this->query = "SELECT nombre,  
                                    case '$t' when 'e' then 'Entrada' when 's' then 'Salida' when 'r' then 'Reacomodo' when 't' then 'Traspaso' when 'd' then 'Entrada x Devolucion' when 'm' then 'Merma' end as tipo,
                                    (SELECT ETIQUETA||'--'||TIPO FROM FTC_ALMACEN_COMPONENTES WHERE ID_COMP = $c) as compp
            FROM FTC_ALMACEN where id = $al";
        }
        $res=$this->EjecutaQuerySimple();
        $row=ibase_fetch_object($res);
        return $row;
    }

    function prodAuto($prod){
        $this->query = "SELECT ID_PINT, id_int, desc FROM FTC_ALMACEN_PROD_INT 
                        WHERE (ID_PINT||' '|| ID_INT ||' '|| DESC) CONTAINING '$prod' and status != 'Descontinuado'";
        $result = $this->devuelveAutoProd();
        return $result;
    }

    function compAuto($comp){
        $this->query = "SELECT id_comp, etiqueta, tipo, DESC  FROM FTC_ALMACEN_COMPONENTES 
                        WHERE (ID_COMP||' '|| ETIQUETA ||' '|| TIPO ||' '||DESC) CONTAINING '$comp'";
        $result = $this->devuelveAutoComp();
        return $result;
    }

    function usuarios($op){
        $data=array();
        $this->query="SELECT ID, NOMBRE FROM PG_USERS where USER_ROL = 'almacen'";
        $res=$this->EjecutaQuerySimple();
        while ($tsArray=ibase_fetch_object($res)){
            $data[]=$tsArray;
        }
        return $data;
    }

    function valProd($prod){
        $row=array();
        $prod = explode(":", trim($prod));
        $prod = $prod[2];
        $this->query="SELECT ID_PINT FROM FTC_ALMACEN_PROD_INT where ID_PINT=$prod";
        $res=$this->EjecutaQuerySimple();
        $row = ibase_fetch_row($res);
        if(count($row) > 0){    
            return array("val"=>'ok',"prod"=>$row[0],"msg"=>'Existe el producto');   
        }else{
            return array("val"=>'no',"prod"=>$row[0],"msg"=>'NO Existe el producto');
        }
    }

    function infoRep($t, $out){
        $primary=array();
        $secondary=array();
        if($t=='pc'){
            $this->query="SELECT * FROM FTC_ALMACEN_COMPONENTES WHERE ID_TIPO = 2";
            $res=$this->EjecutaQuerySimple();
            while ($tsArray=ibase_fetch_object($res)) {
                $primary[] =$tsArray; 
            }
            $secondary=array();
            $this->query="SELECT * FROM FTC_ALMACEN_COMPONENTES WHERE ID_TIPO = 1";
            $res=$this->EjecutaQuerySimple();
            while ($tsArray=ibase_fetch_object($res)) {
                $secondary[] =$tsArray; 
            }
        }elseif($t='pp'){
            $this->query="SELECT * FROM FTC_ALMACEN_PRODUCTOS ";
            $res=$this->EjecutaQuerySimple();
            while ($tsArray=ibase_fetch_object($res)){
                $primary[]=$tsArray;
            }
            $secondary=$this->exist($id='', $tipo=$t);            
        }elseif ($t=='da'){
            $this->query="SELECT * FROM FTC_ALMACEN_COMPONENTES ";
        }
        return array("primary"=>$primary, "secondary"=>$secondary);
    }

    function exist($id, $tipo){
        $data=array();
        if($tipo == 'pc'){
            $this->query="SELECT m.almacen, m.id_comps, m.prod, iif(m.id_tipo ='e' or m.id_tipo = 'E', sum(piezas), 0) as entradas,
                        (SELECT SUM(PIEZAS) FROM FTC_ALMACEN_MOV_SAL ms WHERE ms.ID_COMPS = m.id_comps )  as salidas
                        from FTC_ALMACEN_MOVimiento m
                        where id_comps=$id and id_status='F' group by m.id_comps, m.prod, m.id_tipo, m.almacen";
                        //echo $this->query;
        }elseif($tipo == 'pp'){
            $this->query="SELECT m.almacen, m.id_comps, m.compp, m.comps, m.id_prod, m.id_tipo,
                                max(PRIMARIO) as primario, 
                                max(secundario) as secundario, 
                                iif(m.id_tipo = 'e' or m.id_tipo = 'E', sum(piezas), 0) as entradas, 
                                (SELECT coalesce(SUM(PIEZAS), 0) FROM FTC_ALMACEN_MOV_SAL ms WHERE ms.ID_COMPS = m.id_comps and ms.status = 'F' and ms.id_prod = m.id_prod and ms.id_mov = m.id_am )  as salidas,
                                (SELECT coalesce(SUM(PIEZAS), 0) FROM FTC_ALMACEN_MOV_SAL ms WHERE ms.ID_COMPS = m.id_comps and ms.status = 'P' and ms.id_prod = m.id_prod and ms.id_mov = m.id_am )  as pendientes,
                                m.categoria
                        from FTC_ALMACEN_MOVimiento m
                        where id_status='F' group by m.id_comps, m.compp, m.comps, m.id_prod, m.id_tipo, m.almacen, m.fecha, m.secundario, m.color, m.id_am, m.categoria order by  m.fecha asc, m.id_comps asc, m.secundario asc";
        }
        $res=$this->EjecutaQuerySimple();
        while ($tsArray=ibase_fetch_object($res)){
            $data[]=$tsArray;
        }
        return $data;
    }

    function ordenes($op, $param){
        $data=array(); $p='';
        if($_SESSION['user']->NUMERO_LETRAS==9){$p = " and id_status = 3 and asignado > 0 "; 
        }elseif($_SESSION['user']->NUMERO_LETRAS== 1){$p = " and id_status <= 1 ";// Antes de utilizaba para Claudia pero se degrado con la sincronizacion con Intelisis.
        }elseif($_SESSION['user']->NUMERO_LETRAS== 2){$p = " and (id_status <= 1) ";}/// Antes tenia el valor 2 ya que era cuando Claudia los validaba.
        if(empty($param)){
        }else{ 
            $param = explode(":", $param);$op = "";
            if(!empty($param[1])){$p.= " and dia_carga >= '". $param[1]."'";}
            if(!empty($param[2])){$p.= " and dia_carga <= '". $param[2]."'";}
            //$p.= ($param[3]==0)? " ":" and id_status = ". $param[3];
            $p.= ($param[3]=='' or isset($param[3]))? " and (archivo starting with 'Pedido' or archivo starting with 'Salida' or archivo starting with 'Traspaso' )  ":" and archivo starting with '$param[3]' "; /// Forzando a que siempre jale pedidos
        }
        $this->query="SELECT * FROM FTC_ALMACEN_ORDENES WHERE ID_ORD > 0 $op $p";
        $res=$this->EjecutaQuerySimple();
        while ($tsArray=ibase_fetch_object($res)){
            $data[]=$tsArray;
        }
        return $data;
    }

    function saveOrder($file, $fileName, $ido){
        if($xlsx=SimpleXLSX::parse($file)){
            ///$i=0;
            ///$l=0;
            ///$e=0;
            $hoja = $xlsx->sheetName(0);
            if(strtoupper(trim($hoja)) == 'COPPEL'){
                $this->coppel($xlsx, $hoja, $file, $ido);
            }elseif(strtoupper( trim($hoja)) == 'NUEVO WALMART' or trim($hoja) == 'NUEVO LINEAL WM'){
                $this->walmart($xlsx, $hoja, $file, $ido);
            }elseif(strtoupper( trim($hoja)) == 'CIMACO'){
                $this->cimaco($xlsx, $hoja, $file, $ido);
            }elseif(strtoupper( trim($hoja)) == 'SANBORNS'){
                $this->sanborns($xlsx, $hoja, $file, $ido);
            }elseif(strtoupper( trim($hoja)) == 'AL SUPER'){
                $this->alSuper($xlsx, $hoja, $file, $ido);
            }elseif(strtoupper( trim($hoja)) == 'FRESKO'){
                $this->fresko($xlsx, $hoja, $file, $ido);
            }elseif(strtoupper( trim($hoja)) == 'ELEKTRA'){
                $this->elektra($xlsx, $hoja, $file, $ido);
            }elseif(strtoupper(substr(trim($hoja),0,7)) == 'SORIANA'){
                $this->soriana($xlsx, $hoja, $file, $ido);
            }elseif(strtoupper(substr(trim($hoja),0,9))=='HC FULLER'){
                $this->hcfuller($xlsx, substr(trim($hoja),0,9), $file, $ido);
            }elseif(strtoupper(trim($hoja))=='RADIOSHACK'){
                $this->radio($xlsx, $hoja, $file, $ido);
            }elseif(strtoupper(trim($hoja))=='HEB'){
                $this->heb($xlsx, $hoja, $file, $ido);
            }elseif(strtoupper(trim($hoja))=='CONTROL'){
                $this->control($xlsx, $hoja, $file, $ido);
            }elseif(strtoupper(trim($hoja))=='CITY CLUB'){
                $this->city($xlsx, $hoja, $file, $ido);
            }elseif(strtoupper(trim($hoja))=='CHEDRAUI'){
                $this->chedraui($xlsx, $hoja, $file, $ido);
            }elseif(strtoupper(trim($hoja))=='CASA MARCHAND'){
                $this->casaMarchand($xlsx, $hoja, $file, $ido);
            }elseif(strtoupper(trim($hoja))=='OFFICE DEPOT'){
                $this->officeDepot($xlsx, $hoja, $file, $ido);
            }elseif(strtoupper(trim($hoja))=='HEMSA'){
                $this->hemsa($xlsx, $hoja, $file, $ido);
            }elseif(strtoupper(trim($hoja))=='SEARS'){
                $this->sears($xlsx, $hoja, $file, $ido);
            }elseif(strtoupper(trim($hoja))=='HERMANOS BATTA'){
                $this->hermanosBatta($xlsx, $hoja, $file, $ido);
            }elseif(strtoupper(trim($hoja))=='CIRCULO K'){
                $this->hermanosBatta($xlsx, $hoja, $file, $ido);
            }elseif(strtoupper(trim($hoja))=='ANDREA'){
                $this->andrea($xlsx, $hoja, $file, $ido);
            }elseif(strtoupper(trim($hoja))=='COLCHONES Y MUEBLES'){
                $this->colchonesYmuebles($xlsx, $hoja, $file, $ido);
            }elseif(strtoupper(trim($hoja))=='OFFICE MAX'){
                $this->officeMax($xlsx, $hoja, $file, $ido);
            }elseif(strtoupper(trim($hoja))=='PALACIO'){
                $this->palacio($xlsx, $hoja, $file, $ido);
            }elseif(strtoupper(trim($hoja))=='DILTEX SA DE CV'){
                $this->diltex($xlsx, $hoja, $file, $ido);
            }elseif(strtoupper(trim($hoja))=='MIX UP'){
                $this->mixupg($xlsx, $hoja, $file, $ido);
            }
            else{
                $res=$this->intelisis($xlsx, $hoja, $file, $ido);
                if(count($res)<=0 ){
                    echo 'Lo siento no tengo el formato para el cliente: '.$hoja.' favor de revisar el nombre de la hoja';
                    return array("msg"=>"Lo siento no tengo el formato para el cliente: ".$hoja.' favor de revisar el nombre de la hoja');
                }
            }
        }else {
            echo "<h2>No se pudo leer el archvivo $file</h2>";
            echo "<pre>";
            echo "</pre>";
        }
    }

    function coppel($xlsx, $hoja, $file, $ido){
        // coppel se identifica en el valor de la columa "A"
        $usuario=$_SESSION['user']->ID;
        $ln=0;$piezas=0;
        $this->query="INSERT INTO FTC_ALMACEN_ORDEN (ID_ORD,CLIENTE,CEDIS,FECHA_CARGA,FECHA_ASIGNA,FECHA_ALMACEN,FECHA_CARGA_F,FECHA_ASIGNA_F,FECHA_ALMACEN_F,STATUS,NUM_PROD,CAJAS,PRIORIDAD, ARCHIVO, USUARIO, ORIGINAL) VALUES (NULL, '$hoja', '',current_timestamp, null, null, null, null, null, 1, 0, 0, 0, '$file', $usuario, $ido) returning ID_ORD";
        $res=$this->grabaBD();
        $res= ibase_fetch_object($res);
        $idord=@$res->ID_ORD;
        if(@$idord>0){
            foreach ($xlsx->rows() as $key){
                $col='A';$ln++;$nC=0;
                for ($i=0; $i < count($key); $i++) { 
                    //echo '<br/>Columna: '.$col ;
                        if($ln==7 and $key[$nC]!=''){
                            //echo '<br/>Valor de la celda: '.$col.$ln.' = '. $key[$nC].'<br/>';
                            $lnn=0;
                            foreach($xlsx->rows() as $k2){
                                $lnn++;
                                if($lnn >= 10 and $lnn < 65 and $col >='I' and $k2[$nC]!=''){
                                //echo '<br/>En la linea '.$lnn.' Se solicitan '.$k2[$nC].' piezas del producto: '.$k2[1].' modelo: '.$k2[2].' para el Cedis '.$key[$nC].'<br/>';
                                    $piezas += $k2[$nC];
                                    $this->query="INSERT INTO FTC_ALMACEN_ORDEN_DET (ID_ORDD, ID_ORD, PROD, DESCR, PZAS, CAJAS, COLOR, CEDIS, PZAS_SUR, CAJAS_SUR, STATUS, OBS, ORDEN, UPC) VALUES(NULL, $idord, '$k2[3]', '$k2[2]', $k2[$nC], 0, '', '$key[$nC]', 0, 0,1, '', '', $k2[0]) returning ID_ORDD";
                                    $this->grabaBD();
                                }    
                            }
                        }
                    $nC++;
                    $col++;
                }
                $i++;
                //echo '<br/>Valores de la Columna A:'.$key[0].' B: '.$key[1];
            }
        }else{
            echo 'Ocurro un error en la cabecera del archivo, favor de reportar a sistemas al 55 50553392';
        }
            //echo '<br/>Ultima Columna: '.$col.'<br/>';
        return;
    }

    function walmart($xlsx, $hoja, $file, $ido){
        // coppel se identifica en el valor de la columa "A"
        $usuario=$_SESSION['user']->ID;
        $ln=0;$piezas=0;
        $this->query="INSERT INTO FTC_ALMACEN_ORDEN (ID_ORD,CLIENTE,CEDIS,FECHA_CARGA,FECHA_ASIGNA,FECHA_ALMACEN,FECHA_CARGA_F,FECHA_ASIGNA_F,FECHA_ALMACEN_F,STATUS,NUM_PROD,CAJAS,PRIORIDAD, ARCHIVO, USUARIO, ORIGINAL) VALUES (NULL, '$hoja', '',current_timestamp, null, null, null, null, null, 1, 0, 0, 0, '$file', $usuario, $ido) returning ID_ORD";
        $res=$this->grabaBD();
        $res= ibase_fetch_object($res);
        $idord=@$res->ID_ORD;
        if(@$idord>0){
            foreach ($xlsx->rows() as $key){
                $col='A';$ln++;$nC=0;
                for ($i=0; $i < count($key); $i++) { 
                    //echo '<br/>Columna: '.$col ;
                        if($ln==7 and $nC >= 9){
                            //echo '<br/>Valor de la celda: '.$col.$ln.' = '. $key[$nC].'<br/>';
                            $lnn=0;

                            foreach($xlsx->rows() as $k2){
                                $lnn++;
                                if($lnn >= 8 and $lnn <= count($xlsx->rows()) and $nC>=9 and $k2[$nC]!=''){
                                //echo '<br/>En la linea '.$lnn.' Se solicitan '.$k2[$nC].' piezas del producto: '.$k2[1].' modelo: '.$k2[2].' para el Cedis '.$key[$nC].'<br/>';
                                    if(substr($k2[1],0,5)== 'TOTAL'){
                                        //echo 'Es una linea de totales: '.$k2[1].'-'.$nC.'<br/>'; 
                                        $oc = $k2[$nC];
                                        //print_r($odns);
                                        //echo '<br/>'.$oc.'<br/>';
                                        if(count($odns)>0){
                                            //echo 'Id inicial: '.$odns[0].'  id final: '.$odns[count($odns)-1].'<br/>';
                                            $begin=$odns[0]; $last=$odns[count($odns)-1];
                                            $this->query="UPDATE FTC_ALMACEN_ORDEN_DET SET ORDEN ='$oc' where id_ordd >= $begin and  id_ordd <= $last";
                                            $this->EjecutaQuerySimple();
                                        }
                                        //die();
                                        $odns=array();
                                    }elseif (trim($k2[4])=='Total general'){
                                        break;
                                    }elseif ($key[$nC] == 'ASIGNADO') {
                                        break;
                                    }else{
                                    $piezas += $k2[$nC];
                                        if(!empty($k2[3])){
                                            $lin_nwm=$k2[3];
                                        }
                                        $this->query="INSERT INTO FTC_ALMACEN_ORDEN_DET (ID_ORDD, ID_ORD, PROD, DESCR, PZAS, CAJAS, COLOR, CEDIS, PZAS_SUR, CAJAS_SUR, STATUS, OBS, ORDEN, UPC, ITEM, LINEA_NWM, UNIDAD) VALUES
                                        (NULL, $idord, '$k2[4]', '', $k2[$nC], 0, '', '$key[$nC]', 0, 0, 1, '', '','$k2[2]','$lin_nwm','$k2[1]', null) returning ID_ORDD";
                                        $res=$this->grabaBD();
                                        $res=ibase_fetch_object($res);
                                        $res=$res->ID_ORDD;
                                        if($res <= 0){
                                            echo $this->query;
                                            //die();
                                        } else{
                                            $odns[]=$res;
                                        }

                                    }
                                }
                            }

                        }
                    $nC++;
                    $col++;
                }
                //$i++;
                //echo '<br/>Valores de la Columna A:'.$key[0].' B: '.$key[1];
            }
        }else{
            echo 'Ocurro un error en la cabecera del archivo, favor de reportar a sistemas al 55 50553392';
        }
            //echo '<br/>Ultima Columna: '.$col.'<br/>';
            return;
    }

    function cimaco($xlsx, $hoja, $file, $ido){
        // coppel se identifica en el valor de la columa "A"
        $usuario=$_SESSION['user']->ID;
        $ln=0;$piezas=0;
        $this->query="INSERT INTO FTC_ALMACEN_ORDEN (ID_ORD,CLIENTE,CEDIS,FECHA_CARGA,FECHA_ASIGNA,FECHA_ALMACEN,FECHA_CARGA_F,FECHA_ASIGNA_F,FECHA_ALMACEN_F,STATUS,NUM_PROD,CAJAS,PRIORIDAD, ARCHIVO, USUARIO, ORIGINAL) VALUES (NULL, '$hoja', '',current_timestamp, null, null, null, null, null, 1, 0, 0, 0, '$file', $usuario, $ido) returning ID_ORD";
        $res=$this->grabaBD();
        $res= ibase_fetch_object($res);
        $idord=@$res->ID_ORD;
        if(@$idord>0){

            foreach ($xlsx->rows() as $key){
                $col='A';$ln++;
                        if($ln >=6 and $ln<=count($xlsx->rows()) - 1 and $key[6] !=''){
                            //echo '<br/>Valor de la celda: '.$col.$ln.' = '. $key[7].'<br/>';
                            if(is_numeric($piezas)){ $piezas += $key[6];}
                            //echo '<br/>Lee la linea: '.$ln.' Columna: 7<br/> valor de key[7]'.$key[7];
                            $this->query="INSERT INTO FTC_ALMACEN_ORDEN_DET (ID_ORDD, ID_ORD, PROD, DESCR, PZAS, CAJAS, COLOR, CEDIS, PZAS_SUR, CAJAS_SUR, STATUS, OBS, ORDEN, UPC, ITEM, LINEA_NWM, UNIDAD) VALUES (NULL, $idord, '$key[3]', '', $key[6], 0, '$key[5]', '', 0, 0, 1, '', '$key[9]','$key[2]','$key[1]', '', null) returning ID_ORDD";
                            $res=$this->grabaBD();
                            $res=ibase_fetch_object($res);
                            $res=$res->ID_ORDD;
                            if($res <= 0){
                              //  echo $this->query;
                              //  die();
                            } else{
                                $odns[]=$res;
                            }
                        }else{
                            //die('Se lee la linea'.$ln);
                        }
                //}
            }
        }else{
            echo 'Ocurro un error en la cabecera del archivo, favor de reportar a sistemas al 55 50553392';
        }
            //echo '<br/>Ultima Columna: '.$col.'<br/>';
            return;
    }

    function sanborns($xlsx, $hoja, $file, $ido){
        $usuario=$_SESSION['user']->ID;
        $ln=0;$piezas=0;
        $this->query="INSERT INTO FTC_ALMACEN_ORDEN (ID_ORD,CLIENTE,CEDIS,FECHA_CARGA,FECHA_ASIGNA,FECHA_ALMACEN,FECHA_CARGA_F,FECHA_ASIGNA_F,FECHA_ALMACEN_F,STATUS,NUM_PROD,CAJAS,PRIORIDAD, ARCHIVO, USUARIO, ORIGINAL) VALUES (NULL, '$hoja', '',current_timestamp, null, null, null, null, null, 1, 0, 0, 0, '$file', $usuario, $ido) returning ID_ORD";
        $res=$this->grabaBD();
        $res= ibase_fetch_object($res);
        $idord=@$res->ID_ORD;
        if(@$idord>0){
            foreach ($xlsx->rows() as $key){
                $col='A';$ln++;
                        if($ln >= 7 and $ln<=count($xlsx->rows()) - 1 and ($key[6] !='' and $key[1] !='')){
                            $piezas += $key[6];
                            $this->query="INSERT INTO FTC_ALMACEN_ORDEN_DET (ID_ORDD, ID_ORD, PROD, DESCR, PZAS, CAJAS, COLOR, CEDIS, PZAS_SUR, CAJAS_SUR, STATUS, OBS, ORDEN, UPC, ITEM, LINEA_NWM, UNIDAD) VALUES (NULL, $idord, '$key[4]', '$key[3]', $key[6], 0, '', '', 0, 0, 1, '', '$key[10]','$key[1]','$key[2]','', $key[5]) returning ID_ORDD";
                            $res=$this->grabaBD();
                            $res=ibase_fetch_object($res);
                            $res=$res->ID_ORDD;
                            if($res <= 0){
                            
                            } else{
                                $odns[]=$res;
                            }
                        }else{   
                        }
            }
        }else{
            echo 'Ocurro un error en la cabecera del archivo, favor de reportar a sistemas al 55 50553392';
        }
            //echo '<br/>Ultima Columna: '.$col.'<br/>';
            return;
    }

    function alSuper($xlsx, $hoja, $file, $ido){
        $usuario=$_SESSION['user']->ID;
        $ln=0;$piezas=0;
        $this->query="INSERT INTO FTC_ALMACEN_ORDEN (ID_ORD,CLIENTE,CEDIS,FECHA_CARGA,FECHA_ASIGNA,FECHA_ALMACEN,FECHA_CARGA_F,FECHA_ASIGNA_F,FECHA_ALMACEN_F,STATUS,NUM_PROD,CAJAS,PRIORIDAD, ARCHIVO, USUARIO, ORIGINAL) VALUES (NULL, '$hoja', '',current_timestamp, null, null, null, null, null, 1, 0, 0, 0, '$file', $usuario, $ido) returning ID_ORD";
        $res=$this->grabaBD();
        $res= ibase_fetch_object($res);
        $idord=@$res->ID_ORD;
        if(@$idord>0){
            foreach ($xlsx->rows() as $key){
                $col='A';$ln++;
                        if($ln >= 7 and $ln<=count($xlsx->rows()) - 1 and ($key[1] !='' and $key[3] !='')){
                            if(is_numeric($key[4])){$piezas += $key[4];}
                            $this->query="INSERT INTO FTC_ALMACEN_ORDEN_DET (ID_ORDD, ID_ORD, PROD, DESCR, PZAS, CAJAS, COLOR, CEDIS, PZAS_SUR, CAJAS_SUR, STATUS, OBS, ORDEN, UPC, ITEM, LINEA_NWM, UNIDAD) VALUES (NULL, $idord, '$key[1]', '', $key[4], $key[2], '', '', 0, 0, 1, '', '$key[7]','','','', $key[2]) returning ID_ORDD";
                            $res=$this->grabaBD();
                            $res=ibase_fetch_object($res);
                            $res=$res->ID_ORDD;
                            if($res <= 0){
                            
                            } else{
                                $odns[]=$res;
                            }
                        }else{   
                        }
            }
        }else{
            echo 'Ocurro un error en la cabecera del archivo, favor de reportar a sistemas al 55 50553392';
        }
            //echo '<br/>Ultima Columna: '.$col.'<br/>';
            return;
    }

    function fresko($xlsx, $hoja, $file, $ido){
        $usuario=$_SESSION['user']->ID;
        $ln=0;$piezas=0;
        $this->query="INSERT INTO FTC_ALMACEN_ORDEN (ID_ORD,CLIENTE,CEDIS,FECHA_CARGA,FECHA_ASIGNA,FECHA_ALMACEN,FECHA_CARGA_F,FECHA_ASIGNA_F,FECHA_ALMACEN_F,STATUS,NUM_PROD,CAJAS,PRIORIDAD, ARCHIVO, USUARIO, ORIGINAL) VALUES (NULL, '$hoja', '',current_timestamp, null, null, null, null, null, 1, 0, 0, 0, '$file', $usuario, $ido) returning ID_ORD";
        $res=$this->grabaBD();
        $res= ibase_fetch_object($res);
        $idord=@$res->ID_ORD;
        if(@$idord>0){
            foreach ($xlsx->rows() as $key){
                $col='A';$ln++;
                        if($ln >= 5 and $ln<=count($xlsx->rows()) - 1 and ($key[3] !='' and $key[4] !='')){
                            if(is_numeric($key[6])){$piezas+=$key[6];}
                            $this->query="INSERT INTO FTC_ALMACEN_ORDEN_DET (ID_ORDD, ID_ORD, PROD, DESCR, PZAS, CAJAS, COLOR, CEDIS, PZAS_SUR, CAJAS_SUR, STATUS, OBS, ORDEN, UPC, ITEM, LINEA_NWM, UNIDAD) VALUES (NULL, $idord, '$key[2]', '', $key[7], $key[5], '', '', 0, 0, 1, '', '$key[11]','$key[1]','','', $key[4]) returning ID_ORDD";
                            //echo '<br/>'.$this->query.'<br/>';
                            $res=$this->grabaBD();
                            $res=ibase_fetch_object($res);
                            $res=$res->ID_ORDD;
                            if($res <= 0){
                            
                            } else{
                                $odns[]=$res;
                            }
                        }else{   
                        }
            }
        }else{
            echo 'Ocurro un error en la cabecera del archivo, favor de reportar a sistemas al 55 50553392';
        }
            //echo '<br/>Ultima Columna: '.$col.'<br/>';
            return;
    }

    function elektra($xlsx, $hoja, $file, $ido){
        // coppel se identifica en el valor de la columa "A"
        $usuario=$_SESSION['user']->ID;
        $ln=0;$piezas=0;
        $this->query="INSERT INTO FTC_ALMACEN_ORDEN (ID_ORD,CLIENTE,CEDIS,FECHA_CARGA,FECHA_ASIGNA,FECHA_ALMACEN,FECHA_CARGA_F,FECHA_ASIGNA_F,FECHA_ALMACEN_F,STATUS,NUM_PROD,CAJAS,PRIORIDAD, ARCHIVO, USUARIO, ORIGINAL) VALUES (NULL, '$hoja', '',current_timestamp, null, null, null, null, null, 1, 0, 0, 0, '$file', $usuario, $ido) returning ID_ORD";
        $res=$this->grabaBD();
        $res= ibase_fetch_object($res);
        $idord=@$res->ID_ORD;
        $oc = array();
        if(@$idord>0){
            foreach ($xlsx->rows() as $key){
                $col='A';$ln++;$nC=0;$a=0;
                for ($i=0; $i < count($key); $i++) { 
                    //echo '<br/>Columna: '.$col ;
                    if($ln== 4 and $nC >= 7 and $nC<=10){
                        $aoc[]=array($nC=>$key[$nC]);
                        //array_push(array, var)
                    }
                        if($ln==9 and $key[$nC]!=''){
                            //echo '<br/>Valor de la celda: '.$col.$ln.' = '. $key[$nC].'<br/>';
                            $lnn=0;
                            foreach($xlsx->rows() as $k2){
                                $lnn++;
                                if($lnn >= 10 and $lnn <= count($xlsx->rows()) and $nC >=7 and $k2[$nC]!=''){
                                //echo '<br/>En la linea '.$lnn.' Se solicitan '.$k2[$nC].' piezas del producto: '.$k2[1].' modelo: '.$k2[2].' para el Cedis '.$key[$nC].'<br/>';
                                    if($k2[2] != ''){
                                        $piezas += $k2[$nC];
                                        $oc=@$aoc[$a][$nC];
                                        $this->query="INSERT INTO FTC_ALMACEN_ORDEN_DET (ID_ORDD, ID_ORD, PROD, DESCR, PZAS, CAJAS, COLOR, CEDIS, PZAS_SUR, CAJAS_SUR, STATUS, OBS, ORDEN, UPC, ITEM) VALUES(NULL, $idord, '$k2[2]', '', $k2[$nC], 0, '', '$key[$nC]', 0, 0,1, '', '$oc', '$k2[0]', '$k2[1]') returning ID_ORDD";
                                        $this->grabaBD();
                                        $a++;
                                    }
                                }    
                            }
                        }
                    $nC++;
                    $col++;
                }
                $i++;
            }
        }else{
            echo 'Ocurro un error en la cabecera del archivo, favor de reportar a sistemas al 55 50553392';
        }
            //echo '<br/>Ultima Columna: '.$col.'<br/>';
            return;
    }

    function soriana($xlsx, $hoja, $file, $ido){
        $usuario=$_SESSION['user']->ID;
        $ln=0;$piezas=0;
        $this->query="INSERT INTO FTC_ALMACEN_ORDEN (ID_ORD,CLIENTE,CEDIS,FECHA_CARGA,FECHA_ASIGNA,FECHA_ALMACEN,FECHA_CARGA_F,FECHA_ASIGNA_F,FECHA_ALMACEN_F,STATUS,NUM_PROD,CAJAS,PRIORIDAD, ARCHIVO, USUARIO, ORIGINAL) VALUES (NULL, '$hoja', '',current_timestamp, null, null, null, null, null, 1, 0, 0, 0, '$file', $usuario, $ido) returning ID_ORD";
        $res=$this->grabaBD();
        $res= ibase_fetch_object($res);
        $idord=@$res->ID_ORD;
        if(@$idord>0){
            foreach ($xlsx->rows() as $key){
                $col='A';$ln++;$nC=0;
                for ($i=0; $i < count($key); $i++) { 
                        if($ln==3 and $nC >=9 and $nC <= 10){ ///aqui controlamos las columnas de los cedis en este caso solo son las columnas J y K
                            $lnn=0;
                            foreach($xlsx->rows() as $k2){
                                $lnn++;
                                if($lnn >= 4 and $lnn <= count($xlsx->rows()) and $nC>=9 and $k2[$nC]!=''){
                                    if(substr($k2[2],0,6)== 'CODIGO'){
                                        //echo 'Es una linea de Codigo: '.$k2[1].'-'.$nC.'<br/>'; 
                                    }elseif (trim($k2[4])=='Total general'){
                                        break;
                                    }elseif ($key[$nC] == 'ASIGNADO') {
                                        break;
                                    }else{
                                    if(is_numeric($k2[$nC])){$piezas += $k2[$nC];}
                                        if(!empty($k2[5])){
                                            $this->query="INSERT INTO FTC_ALMACEN_ORDEN_DET (ID_ORDD, ID_ORD, PROD, DESCR, PZAS, CAJAS, COLOR, CEDIS, PZAS_SUR, CAJAS_SUR, STATUS, OBS, ORDEN, UPC, ITEM, LINEA_NWM, UNIDAD, CADENA) VALUES
                                            (NULL, $idord, '$k2[5]', '', ($k2[$nC]*$k2[4]), $k2[$nC], '', '$key[$nC]', 0, 0, 1, '', '','$k2[2]','','', $k2[4], '$k2[1]') returning ID_ORDD";
                                            //echo $this->query;
                                            //die();
                                            $res=$this->grabaBD();
                                            $res=ibase_fetch_object($res);
                                            $res=$res->ID_ORDD;
                                            if($res <= 0){
                                                echo $this->query;
                                                //die();
                                            } else{
                                                $odns[]=$res;
                                            }
                                        }

                                    }
                                }
                            }
                        }
                    $nC++;
                    $col++;
                }
            }
        }else{
            echo 'Ocurro un error en la cabecera del archivo, favor de reportar a sistemas al 55 50553392';
        }
            //echo '<br/>Ultima Columna: '.$col.'<br/>';
            return;
    }
    
    function hcfuller($xlsx, $hoja, $file, $ido){
        $usuario=$_SESSION['user']->ID;
        $ln=0;$piezas=0;
        $this->query="INSERT INTO FTC_ALMACEN_ORDEN (ID_ORD,CLIENTE,CEDIS,FECHA_CARGA,FECHA_ASIGNA,FECHA_ALMACEN,FECHA_CARGA_F,FECHA_ASIGNA_F,FECHA_ALMACEN_F,STATUS,NUM_PROD,CAJAS,PRIORIDAD, ARCHIVO, USUARIO, ORIGINAL) VALUES (NULL, '$hoja', '',current_timestamp, null, null, null, null, null, 1, 0, 0, 0, '$file', $usuario, $ido) returning ID_ORD";
        $res=$this->grabaBD();
        $res= ibase_fetch_object($res);
        $idord=@$res->ID_ORD;
        if(@$idord>0){
            foreach ($xlsx->rows() as $key){
                $col='A';$ln++;
                        if($ln >= 7 and $ln<=count($xlsx->rows()) - 1 and ($key[3]!='' and $key[6] !='')){
                            $piezas += $key[6];
                            $this->query="INSERT INTO FTC_ALMACEN_ORDEN_DET (ID_ORDD, ID_ORD, PROD, DESCR, PZAS, CAJAS, COLOR, CEDIS, PZAS_SUR, CAJAS_SUR, STATUS, OBS, ORDEN, UPC, ITEM, LINEA_NWM, UNIDAD) VALUES (NULL, $idord, '$key[3]', '', $key[6], 0, '', '', 0, 0, 1, '', '$key[9]','','$key[4]','', null) returning ID_ORDD";
                            $res=$this->grabaBD();
                        }else{   
                        }
            }
        }else{
            echo 'Ocurro un error en la cabecera del archivo, favor de reportar a sistemas al 55 50553392';
        }
            //echo '<br/>Ultima Columna: '.$col.'<br/>';
            return;
    }

    function radio($xlsx, $hoja, $file, $ido){
        $usuario=$_SESSION['user']->ID;
        $ln=0;$piezas=0;
        $this->query="INSERT INTO FTC_ALMACEN_ORDEN (ID_ORD,CLIENTE,CEDIS,FECHA_CARGA,FECHA_ASIGNA,FECHA_ALMACEN,FECHA_CARGA_F,FECHA_ASIGNA_F,FECHA_ALMACEN_F,STATUS,NUM_PROD,CAJAS,PRIORIDAD, ARCHIVO, USUARIO, ORIGINAL) VALUES (NULL, '$hoja', '',current_timestamp, null, null, null, null, null, 1, 0, 0, 0, '$file', $usuario, $ido) returning ID_ORD";
        $res=$this->grabaBD();
        $res= ibase_fetch_object($res);
        $idord=@$res->ID_ORD;
        if(@$idord>0){
            foreach ($xlsx->rows() as $key){
                $col='A';$ln++;
                        if($ln >= 5 and $ln<=count($xlsx->rows()) - 1 and ($key[3]!='' and $key[8] !='')){
                            if(is_numeric($key[8])){$piezas += $key[8];}
                            $this->query="INSERT INTO FTC_ALMACEN_ORDEN_DET (ID_ORDD, ID_ORD, PROD, DESCR, PZAS, CAJAS, COLOR, CEDIS, PZAS_SUR, CAJAS_SUR, STATUS, OBS, ORDEN, UPC, ITEM ) VALUES (NULL, $idord, '$key[4]', '$key[3]', $key[8], 0, '', '', 0, 0, 1, '', '$key[12]','$key[2]','$key[1]') returning ID_ORDD";
                            $res=$this->grabaBD();
                        }else{   
                        }
            }
        }else{
            echo 'Ocurro un error en la cabecera del archivo, favor de reportar a sistemas al 55 50553392';
        }
            //echo '<br/>Ultima Columna: '.$col.'<br/>';
            return;
    }

    function heb($xlsx, $hoja, $file, $ido){
        $usuario=$_SESSION['user']->ID;
        $ln=0;$piezas=0;
        $this->query="INSERT INTO FTC_ALMACEN_ORDEN (ID_ORD,CLIENTE,CEDIS,FECHA_CARGA,FECHA_ASIGNA,FECHA_ALMACEN,FECHA_CARGA_F,FECHA_ASIGNA_F,FECHA_ALMACEN_F,STATUS,NUM_PROD,CAJAS,PRIORIDAD, ARCHIVO, USUARIO, ORIGINAL) VALUES (NULL, '$hoja', '',current_timestamp, null, null, null, null, null, 1, 0, 0, 0, '$file', $usuario, $ido) returning ID_ORD";
        $res=$this->grabaBD();
        $res= ibase_fetch_object($res);
        $idord=@$res->ID_ORD;
        if(@$idord>0){
            foreach ($xlsx->rows() as $key){
                $col='A';$ln++;
                    if($ln >= 4 and $ln<=count($xlsx->rows()) - 1 and ($key[7]!='' and $key[3] !='')){
                    $piezas += $key[7];
                        $this->query="INSERT INTO FTC_ALMACEN_ORDEN_DET (ID_ORDD, ID_ORD, PROD, DESCR, PZAS, CAJAS, COLOR, CEDIS, PZAS_SUR, CAJAS_SUR, STATUS, OBS, ORDEN, UPC, ITEM, LINEA_NWM, UNIDAD) VALUES (NULL, $idord, '$key[6]', '', $key[8], $key[7], '', '', 0, 0, 1, '', '$key[12]','$key[2]','$key[1]','', $key[3]) returning ID_ORDD";
                            $res=$this->grabaBD();
                    }else{   
                    }
            }
        }else{
            echo 'Ocurro un error en la cabecera del archivo, favor de reportar a sistemas al 55 50553392';
        }
            //echo '<br/>Ultima Columna: '.$col.'<br/>';
            return;
    }

    function control($xlsx, $hoja, $file, $ido){
        $usuario=$_SESSION['user']->ID;
        $ln=0;$piezas=0;
        $this->query="INSERT INTO FTC_ALMACEN_ORDEN (ID_ORD,CLIENTE,CEDIS,FECHA_CARGA,FECHA_ASIGNA,FECHA_ALMACEN,FECHA_CARGA_F,FECHA_ASIGNA_F,FECHA_ALMACEN_F,STATUS,NUM_PROD,CAJAS,PRIORIDAD, ARCHIVO, USUARIO, ORIGINAL) VALUES (NULL, '$hoja', '',current_timestamp, null, null, null, null, null, 1, 0, 0, 0, '$file', $usuario, $ido) returning ID_ORD";
        $res=$this->grabaBD();
        $res= ibase_fetch_object($res);
        $idord=@$res->ID_ORD;
        if(@$idord>0){
            foreach ($xlsx->rows() as $key){
                $col='A';$ln++;
                        if($ln >= 4 and $ln<=count($xlsx->rows()) - 1 and ($key[8] !='' and $key[7] !='')){
                            if(is_numeric($key[9])){$piezas+=$key[9];}
                            $this->query="INSERT INTO FTC_ALMACEN_ORDEN_DET (ID_ORDD, ID_ORD, PROD, DESCR, PZAS, CAJAS, COLOR, CEDIS, PZAS_SUR, CAJAS_SUR, STATUS, OBS, ORDEN, UPC, ITEM, LINEA_NWM, UNIDAD) VALUES (NULL, $idord, '$key[7]', '', $key[9], $key[8], '', '', 0, 0, 1, '', '$key[13]','$key[1]','$key[2]','', $key[6]) returning ID_ORDD";
                            //echo '<br/>'.$this->query.'<br/>';
                            $res=$this->grabaBD();
                            $res=ibase_fetch_object($res);
                            $res=$res->ID_ORDD;
                            if($res <= 0){
                            
                            } else{
                                $odns[]=$res;
                            }
                        }else{   
                        }
            }
        }else{
            echo 'Ocurro un error en la cabecera del archivo, favor de reportar a sistemas al 55 50553392';
        }
        //    //echo '<br/>Ultima Columna: '.$col.'<br/>';
        return;
    }

    function city($xlsx, $hoja, $file, $ido){
        $usuario=$_SESSION['user']->ID;
        $ln=0;$piezas=0;
        $this->query="INSERT INTO FTC_ALMACEN_ORDEN (ID_ORD,CLIENTE,CEDIS,FECHA_CARGA,FECHA_ASIGNA,FECHA_ALMACEN,FECHA_CARGA_F,FECHA_ASIGNA_F,FECHA_ALMACEN_F,STATUS,NUM_PROD,CAJAS,PRIORIDAD, ARCHIVO, USUARIO, ORIGINAL) VALUES (NULL, '$hoja', '',current_timestamp, null, null, null, null, null, 1, 0, 0, 0, '$file', $usuario, $ido) returning ID_ORD";
        $res=$this->grabaBD();
        $res= ibase_fetch_object($res);
        $idord=@$res->ID_ORD;
        if(@$idord>0){
            foreach ($xlsx->rows() as $key){
                $col='A';$ln++;$nC=0;
                for ($i=0; $i < count($key); $i++) { 
                        if($ln==3 and $nC >=9 and $nC <= 10){ ///aqui controlamos las columnas de los cedis en este caso solo son las columnas J y K
                            $lnn=0;
                            foreach($xlsx->rows() as $k2){
                                $lnn++;
                                if($lnn >= 4 and $lnn <= count($xlsx->rows()) and $nC>=9 and $k2[$nC]!=''){
                                    if(substr($k2[2],0,6)== 'CODIGO'){
                                        //echo 'Es una linea de Codigo: '.$k2[1].'-'.$nC.'<br/>'; 
                                    }elseif (trim($k2[4])=='Total general'){
                                        break;
                                    }elseif ($key[$nC] == 'ASIGNADO') {
                                        break;
                                    }else{
                                    if(is_numeric($k2[$nC])){$piezas += $k2[$nC];}
                                        if(!empty($k2[5])){
                                            $this->query="INSERT INTO FTC_ALMACEN_ORDEN_DET (ID_ORDD, ID_ORD, PROD, DESCR, PZAS, CAJAS, COLOR, CEDIS, PZAS_SUR, CAJAS_SUR, STATUS, OBS, ORDEN, UPC, ITEM, LINEA_NWM, UNIDAD, CADENA) VALUES
                                            (NULL, $idord, '$k2[5]', '', $k2[$nC]*$k2[4], $k2[$nC], '', '$key[$nC]', 0, 0, 1, '', '','$k2[2]','','', $k2[4], '$k2[1]') returning ID_ORDD";
                                            //echo $this->query;
                                            //die();
                                            $res=$this->grabaBD();
                                            $res=ibase_fetch_object($res);
                                            $res=$res->ID_ORDD;
                                            if($res <= 0){
                                                echo $this->query;
                                                //die();
                                            } else{
                                                $odns[]=$res;
                                            }
                                        }

                                    }
                                }
                            }
                        }
                    $nC++;
                    $col++;
                }
            }
        }else{
            echo 'Ocurro un error en la cabecera del archivo, favor de reportar a sistemas al 55 50553392';
        }
            //echo '<br/>Ultima Columna: '.$col.'<br/>';
            return;
        return;
    }

    function chedraui($xlsx, $hoja, $file, $ido){
        $usuario=$_SESSION['user']->ID;
        $ln=0;$piezas=0;
        $this->query="INSERT INTO FTC_ALMACEN_ORDEN (ID_ORD,CLIENTE,CEDIS,FECHA_CARGA,FECHA_ASIGNA,FECHA_ALMACEN,FECHA_CARGA_F,FECHA_ASIGNA_F,FECHA_ALMACEN_F,STATUS,NUM_PROD,CAJAS,PRIORIDAD, ARCHIVO, USUARIO, ORIGINAL) VALUES (NULL, '$hoja', '',current_timestamp, null, null, null, null, null, 1, 0, 0, 0, '$file', $usuario, $ido) returning ID_ORD";
        $res=$this->grabaBD();
        $res= ibase_fetch_object($res);
        $idord=@$res->ID_ORD;
        if(@$idord>0){
            foreach ($xlsx->rows() as $key){
                $col='A';$ln++;
                        if($ln >=8 and $ln<=count($xlsx->rows()) - 1 and $key[8] !='' and $key[5] != 'TOTALES'){
                            if(is_numeric($key[8])){$piezas += $key[8];}
                            $this->query="INSERT INTO FTC_ALMACEN_ORDEN_DET (ID_ORDD, ID_ORD, PROD, DESCR, PZAS, CAJAS, COLOR, STATUS, OBS, ORDEN, UPC, ITEM, UNIDAD) VALUES (NULL,    $idord, '$key[5]', '', $key[8], $key[7], '', 1, '', '$key[13]','$key[2]','$key[4]',$key[6]) returning ID_ORDD";
                            //echo $this->query;
                            $res=$this->grabaBD();
                            $res=ibase_fetch_object($res);
                            $res=$res->ID_ORDD;
                            if($res <= 0){
                            } else{
                                $odns[]=$res;
                            }
                        }else{
                        }
            }
        }else{
            echo 'Ocurro un error en la cabecera del archivo, favor de reportar a sistemas al 55 50553392';
        }
            return;
    }

    function casaMarchand($xlsx, $hoja, $file, $ido){
        $usuario=$_SESSION['user']->ID;
        $ln=0;$piezas=0;
        $this->query="INSERT INTO FTC_ALMACEN_ORDEN (ID_ORD,CLIENTE,CEDIS,FECHA_CARGA,FECHA_ASIGNA,FECHA_ALMACEN,FECHA_CARGA_F,FECHA_ASIGNA_F,FECHA_ALMACEN_F,STATUS,NUM_PROD,CAJAS,PRIORIDAD, ARCHIVO, USUARIO, ORIGINAL) VALUES (NULL, '$hoja', '',current_timestamp, null, null, null, null, null, 1, 0, 0, 0, '$file', $usuario, $ido) returning ID_ORD";
        $res=$this->grabaBD();
        $res= ibase_fetch_object($res);
        $idord=@$res->ID_ORD;
        if(@$idord>0){
            foreach ($xlsx->rows() as $key){
                $col='A';$ln++;
                        if($ln >=5 and $ln<=count($xlsx->rows()) - 1 and $key[6] !='' and $key[7]!='SUBTOTAL'){
                            if(is_numeric($key[6])){$piezas += $key[6];}
                            $this->query="INSERT INTO FTC_ALMACEN_ORDEN_DET (ID_ORDD, ID_ORD, PROD, DESCR, PZAS, CAJAS, COLOR, STATUS, OBS, ORDEN, UPC, ITEM, UNIDAD) VALUES (NULL,    $idord, '$key[5]', '', $key[6], 0, '', 1, '', '$key[11]','$key[2]','$key[1]', 0) returning ID_ORDD";
                            //echo $this->query;
                            $res=$this->grabaBD();
                            $res=ibase_fetch_object($res);
                            $res=$res->ID_ORDD;
                            if($res <= 0){
                            } else{
                                $odns[]=$res;
                            }
                        }else{
                        }
            }
        }else{
            echo 'Ocurro un error en la cabecera del archivo, favor de reportar a sistemas al 55 50553392';
        }
            return;
    }

    function officeDepot($xlsx, $hoja, $file, $ido){
        $usuario=$_SESSION['user']->ID;
        $ln=0;$piezas=0;
        $this->query="INSERT INTO FTC_ALMACEN_ORDEN (ID_ORD,CLIENTE,CEDIS,FECHA_CARGA,FECHA_ASIGNA,FECHA_ALMACEN,FECHA_CARGA_F,FECHA_ASIGNA_F,FECHA_ALMACEN_F,STATUS,NUM_PROD,CAJAS,PRIORIDAD, ARCHIVO, USUARIO, ORIGINAL) VALUES (NULL, '$hoja', '',current_timestamp, null, null, null, null, null, 1, 0, 0, 0, '$file', $usuario, $ido) returning ID_ORD";
        $res=$this->grabaBD();
        $res= ibase_fetch_object($res);
        $idord=@$res->ID_ORD;
        if(@$idord>0){
            foreach ($xlsx->rows() as $key){
                $col='A';$ln++;
                        if($ln >= 5 and $ln<=count($xlsx->rows()) - 1 and ($key[7]!='' and $key[6] !='')){
                            if(is_numeric($key[7])){$piezas += $key[7];}
                            $this->query="INSERT INTO FTC_ALMACEN_ORDEN_DET (ID_ORDD, ID_ORD, PROD, DESCR, PZAS, CAJAS, COLOR, CEDIS, PZAS_SUR, CAJAS_SUR, STATUS, OBS, ORDEN, UPC, ITEM ) VALUES (NULL, $idord, '$key[6]', '', $key[7], 0, '', '', 0, 0, 1, '', '$key[11]','$key[0]','$key[1]') returning ID_ORDD";
                            $res=$this->grabaBD();
                        }else{   
                        }
            }
        }else{
            echo 'Ocurro un error en la cabecera del archivo, favor de reportar a sistemas al 55 50553392';
        }
            //echo '<br/>Ultima Columna: '.$col.'<br/>';
            return;
    }

    function hemsa($xlsx, $hoja, $file, $ido){
        $usuario=$_SESSION['user']->ID;
        $ln=0;$piezas=0;
        $this->query="INSERT INTO FTC_ALMACEN_ORDEN (ID_ORD,CLIENTE,CEDIS,FECHA_CARGA,FECHA_ASIGNA,FECHA_ALMACEN,FECHA_CARGA_F,FECHA_ASIGNA_F,FECHA_ALMACEN_F,STATUS,NUM_PROD,CAJAS,PRIORIDAD, ARCHIVO, USUARIO, ORIGINAL) VALUES (NULL, '$hoja', '',current_timestamp, null, null, null, null, null, 1, 0, 0, 0, '$file', $usuario, $ido) returning ID_ORD";
        $res=$this->grabaBD();
        $res= ibase_fetch_object($res);
        $idord=@$res->ID_ORD;
        if(@$idord>0){
            foreach ($xlsx->rows() as $key){
                $col='A';$ln++;
                        if($ln >= 9 and $ln<=count($xlsx->rows()) - 1 and ($key[5]!='' and $key[2] !='')){
                            if(is_numeric($key[4])){$piezas += $key[4];}
                            $this->query="INSERT INTO FTC_ALMACEN_ORDEN_DET (ID_ORDD, ID_ORD, PROD, DESCR, PZAS, CAJAS, COLOR, CEDIS, PZAS_SUR, CAJAS_SUR, STATUS, OBS, ORDEN, UPC, ITEM, UNIDAD ) VALUES (NULL, $idord, '$key[2]', '', $key[5], $key[4], '', '', 0, 0, 1, '', '$key[9]','$key[1]','$key[0]', $key[3]) returning ID_ORDD";
                            $res=$this->grabaBD();
                        }else{   
                        }
            }
        }else{
            echo 'Ocurro un error en la cabecera del archivo, favor de reportar a sistemas al 55 50553392';
        }
            //echo '<br/>Ultima Columna: '.$col.'<br/>';
            return;
    }

    function sears($xlsx, $hoja, $file, $ido){
        $usuario=$_SESSION['user']->ID;
        $ln=0;$piezas=0;
        $this->query="INSERT INTO FTC_ALMACEN_ORDEN (ID_ORD,CLIENTE,CEDIS,FECHA_CARGA,FECHA_ASIGNA,FECHA_ALMACEN,FECHA_CARGA_F,FECHA_ASIGNA_F,FECHA_ALMACEN_F,STATUS,NUM_PROD,CAJAS,PRIORIDAD, ARCHIVO, USUARIO, ORIGINAL) VALUES (NULL, '$hoja', '',current_timestamp, null, null, null, null, null, 1, 0, 0, 0, '$file', $usuario, $ido) returning ID_ORD";
        $res=$this->grabaBD();
        $res= ibase_fetch_object($res);
        $idord=@$res->ID_ORD;
        if(@$idord>0){
            foreach ($xlsx->rows() as $key){
                $col='A';$ln++;
                        if($ln >= 7 and $ln<=count($xlsx->rows()) - 1 and ($key[3]!='' and $key[1] !='')){
                            if(is_numeric($key[4])){$piezas += $key[4];}
                            $this->query="INSERT INTO FTC_ALMACEN_ORDEN_DET (ID_ORDD, ID_ORD, PROD, DESCR, PZAS, CAJAS, COLOR, CEDIS, PZAS_SUR, CAJAS_SUR, STATUS, OBS, ORDEN, UPC, ITEM, UNIDAD ) VALUES (NULL, $idord, '$key[1]', '', $key[3], 0, '', '', 0, 0, 1, '', '$key[6]','','', $key[2]) returning ID_ORDD";
                            $res=$this->grabaBD();
                        }else{   
                        }
            }
        }else{
            echo 'Ocurro un error en la cabecera del archivo, favor de reportar a sistemas al 55 50553392';
        }
            //echo '<br/>Ultima Columna: '.$col.'<br/>';
            return;
    }

    function hermanosBatta($xlsx, $hoja, $file, $ido){
        $usuario=$_SESSION['user']->ID;
        $ln=0;$piezas=0;
        $this->query="INSERT INTO FTC_ALMACEN_ORDEN (ID_ORD,CLIENTE,CEDIS,FECHA_CARGA,FECHA_ASIGNA,FECHA_ALMACEN,FECHA_CARGA_F,FECHA_ASIGNA_F,FECHA_ALMACEN_F,STATUS,NUM_PROD,CAJAS,PRIORIDAD, ARCHIVO, USUARIO, ORIGINAL) VALUES (NULL, '$hoja', '',current_timestamp, null, null, null, null, null, 1, 0, 0, 0, '$file', $usuario, $ido) returning ID_ORD";
        $res=$this->grabaBD();
        $res= ibase_fetch_object($res);
        $idord=@$res->ID_ORD;
        if(@$idord>0){
            foreach ($xlsx->rows() as $key){
                $col='A';$ln++;
                        if($ln >= 7 and $ln<=count($xlsx->rows()) - 1 and ($key[3]!='' and $key[6] !='')){
                            $piezas += $key[6];
                            $this->query="INSERT INTO FTC_ALMACEN_ORDEN_DET (ID_ORDD, ID_ORD, PROD, DESCR, PZAS, CAJAS, COLOR, CEDIS, PZAS_SUR, CAJAS_SUR, STATUS, OBS, ORDEN, UPC, ITEM, LINEA_NWM, UNIDAD) VALUES (NULL, $idord, '$key[3]', '', $key[6], 0, '', '', 0, 0, 1, '', '$key[9]','$key[2]','$key[4]','', null) returning ID_ORDD";
                            $res=$this->grabaBD();
                        }else{   
                        }
            }
        }else{
            echo 'Ocurro un error en la cabecera del archivo, favor de reportar a sistemas al 55 50553392';
        }
            //echo '<br/>Ultima Columna: '.$col.'<br/>';
            return;
    }

    function circuloK($xlsx, $hoja, $file, $ido){
        $usuario=$_SESSION['user']->ID;
        $ln=0;$piezas=0;
        $this->query="INSERT INTO FTC_ALMACEN_ORDEN (ID_ORD,CLIENTE,CEDIS,FECHA_CARGA,FECHA_ASIGNA,FECHA_ALMACEN,FECHA_CARGA_F,FECHA_ASIGNA_F,FECHA_ALMACEN_F,STATUS,NUM_PROD,CAJAS,PRIORIDAD, ARCHIVO, USUARIO, ORIGINAL) VALUES (NULL, '$hoja', '',current_timestamp, null, null, null, null, null, 1, 0, 0, 0, '$file', $usuario, $ido) returning ID_ORD";
        $res=$this->grabaBD();
        $res= ibase_fetch_object($res);
        $idord=@$res->ID_ORD;
        if(@$idord>0){
            foreach ($xlsx->rows() as $key){
                $col='A';$ln++;
                        if($ln >= 7 and $ln<=count($xlsx->rows()) - 1 and ($key[3]!='' and $key[6] !='')){
                            $piezas += $key[6];
                            $this->query="INSERT INTO FTC_ALMACEN_ORDEN_DET (ID_ORDD, ID_ORD, PROD, DESCR, PZAS, CAJAS, COLOR, CEDIS, PZAS_SUR, CAJAS_SUR, STATUS, OBS, ORDEN, UPC, ITEM, LINEA_NWM, UNIDAD) VALUES (NULL, $idord, '$key[3]', '', $key[6], 0, '', '', 0, 0, 1, '', '$key[9]','$key[2]','$key[4]','', null) returning ID_ORDD";
                            $res=$this->grabaBD();
                        }else{   
                        }
            }
        }else{
            echo 'Ocurro un error en la cabecera del archivo, favor de reportar a sistemas al 55 50553392';
        }
            //echo '<br/>Ultima Columna: '.$col.'<br/>';
            return;
    }

    function andrea($xlsx, $hoja, $file, $ido){
        $usuario=$_SESSION['user']->ID;
        $ln=0;$piezas=0;
        $this->query="INSERT INTO FTC_ALMACEN_ORDEN (ID_ORD,CLIENTE,CEDIS,FECHA_CARGA,FECHA_ASIGNA,FECHA_ALMACEN,FECHA_CARGA_F,FECHA_ASIGNA_F,FECHA_ALMACEN_F,STATUS,NUM_PROD,CAJAS,PRIORIDAD, ARCHIVO, USUARIO, ORIGINAL) VALUES (NULL, '$hoja', '',current_timestamp, null, null, null, null, null, 1, 0, 0, 0, '$file', $usuario, $ido) returning ID_ORD";
        $res=$this->grabaBD();
        $res= ibase_fetch_object($res);
        $idord=@$res->ID_ORD;
        if(@$idord>0){
            foreach ($xlsx->rows() as $key){
                $col='A';$ln++;
                        if($ln >= 8 and $ln<=count($xlsx->rows()) - 1 and ($key[3]!='' and $key[6] !='')){
                            if(is_numeric($piezas)){$piezas += $key[6];}
                            $this->query="INSERT INTO FTC_ALMACEN_ORDEN_DET (ID_ORDD, ID_ORD, PROD, DESCR, PZAS, CAJAS, COLOR, CEDIS, PZAS_SUR, CAJAS_SUR, STATUS, OBS, ORDEN, UPC, ITEM, LINEA_NWM, UNIDAD) VALUES (NULL, $idord, '$key[3]', '', $key[6], 0, '', '', 0, 0, 1, '', '$key[9]','','$key[2]','', null) returning ID_ORDD";
                            $res=$this->grabaBD();
                        }else{   
                        }
            }
        }else{
            echo 'Ocurro un error en la cabecera del archivo, favor de reportar a sistemas al 55 50553392';
        }
            //echo '<br/>Ultima Columna: '.$col.'<br/>';
            return;
    }

    function colchonesYmuebles($xlsx, $hoja, $file, $ido){
        $usuario=$_SESSION['user']->ID;
        $ln=0;$piezas=0;
        $this->query="INSERT INTO FTC_ALMACEN_ORDEN (ID_ORD,CLIENTE,CEDIS,FECHA_CARGA,FECHA_ASIGNA,FECHA_ALMACEN,FECHA_CARGA_F,FECHA_ASIGNA_F,FECHA_ALMACEN_F,STATUS,NUM_PROD,CAJAS,PRIORIDAD, ARCHIVO, USUARIO, ORIGINAL) VALUES (NULL, '$hoja', '',current_timestamp, null, null, null, null, null, 1, 0, 0, 0, '$file', $usuario, $ido) returning ID_ORD";
        $res=$this->grabaBD();
        $res= ibase_fetch_object($res);
        $idord=@$res->ID_ORD;
        if(@$idord>0){
            foreach ($xlsx->rows() as $key){
                $col='A';$ln++;
                        if($ln >= 7 and $ln<=count($xlsx->rows()) - 1 and ($key[1]!='' and $key[3] !='')){
                            if(is_numeric($key[3])){$piezas += $key[3];}
                            $this->query="INSERT INTO FTC_ALMACEN_ORDEN_DET (ID_ORDD, ID_ORD, PROD, DESCR, PZAS, CAJAS, COLOR, CEDIS, PZAS_SUR, CAJAS_SUR, STATUS, OBS, ORDEN, UPC, ITEM, LINEA_NWM, UNIDAD) VALUES (NULL, $idord, '$key[1]', '', $key[3], 0, '', '', 0, 0, 1, '', '$key[6]','','','', $key[2]) returning ID_ORDD";
                            $res=$this->grabaBD();
                        }else{   
                        }
            }
        }else{
            echo 'Ocurro un error en la cabecera del archivo, favor de reportar a sistemas al 55 50553392';
        }
            //echo '<br/>Ultima Columna: '.$col.'<br/>';
            return;
    }

    function officeMax($xlsx, $hoja, $file, $ido){
        $usuario=$_SESSION['user']->ID;
        $ln=0;$piezas=0;
        $this->query="INSERT INTO FTC_ALMACEN_ORDEN (ID_ORD,CLIENTE,CEDIS,FECHA_CARGA,FECHA_ASIGNA,FECHA_ALMACEN,FECHA_CARGA_F,FECHA_ASIGNA_F,FECHA_ALMACEN_F,STATUS,NUM_PROD,CAJAS,PRIORIDAD, ARCHIVO, USUARIO, ORIGINAL) VALUES (NULL, '$hoja', '',current_timestamp, null, null, null, null, null, 1, 0, 0, 0, '$file', $usuario, $ido) returning ID_ORD";
        $res=$this->grabaBD();
        $res= ibase_fetch_object($res);
        $idord=@$res->ID_ORD;
        if(@$idord>0){
            foreach ($xlsx->rows() as $key){
                $col='A';$ln++;
                        if($ln >= 7 and $ln<=count($xlsx->rows()) - 1 and ($key[1]!='' and $key[3] !='')){
                            if(is_numeric($key[3])){$piezas += $key[3];}
                            $empaque = empty($key[2])? 0:$key[2];
                            $this->query="INSERT INTO FTC_ALMACEN_ORDEN_DET (ID_ORDD, ID_ORD, PROD, DESCR, PZAS, CAJAS, COLOR, CEDIS, PZAS_SUR, CAJAS_SUR, STATUS, OBS, ORDEN, UPC, ITEM, LINEA_NWM, UNIDAD) VALUES (NULL, $idord, '$key[1]', '', $key[3], 0, '', '', 0, 0, 1, '', '$key[6]','','','', $empaque) returning ID_ORDD";

                            $res=$this->grabaBD();
                        }else{   
                        }
            }
        }else{
            echo 'Ocurro un error en la cabecera del archivo, favor de reportar a sistemas al 55 50553392';
        }
            //echo '<br/>Ultima Columna: '.$col.'<br/>';
            return;
    }

    function palacio($xlsx, $hoja, $file, $ido){
        $usuario=$_SESSION['user']->ID;
        $ln=0;$piezas=0;
        $this->query="INSERT INTO FTC_ALMACEN_ORDEN (ID_ORD,CLIENTE,CEDIS,FECHA_CARGA,FECHA_ASIGNA,FECHA_ALMACEN,FECHA_CARGA_F,FECHA_ASIGNA_F,FECHA_ALMACEN_F,STATUS,NUM_PROD,CAJAS,PRIORIDAD, ARCHIVO, USUARIO, ORIGINAL) VALUES (NULL, '$hoja', '',current_timestamp, null, null, null, null, null, 1, 0, 0, 0, '$file', $usuario, $ido) returning ID_ORD";
        $res=$this->grabaBD();
        $res= ibase_fetch_object($res);
        $idord=@$res->ID_ORD;
        if(@$idord>0){
            foreach ($xlsx->rows() as $key){
                $col='A';$ln++;
                        if($ln >= 7 and $ln<=count($xlsx->rows()) - 1 and ($key[3]!='' and $key[6] !='')){
                            $piezas += $key[6];
                            $this->query="INSERT INTO FTC_ALMACEN_ORDEN_DET (ID_ORDD, ID_ORD, PROD, DESCR, PZAS, CAJAS, COLOR, CEDIS, PZAS_SUR, CAJAS_SUR, STATUS, OBS, ORDEN, UPC, ITEM, LINEA_NWM, UNIDAD) VALUES (NULL, $idord, '$key[3]', '', $key[6], 0, '', '', 0, 0, 1, '', '$key[9]','$key[2]','$key[4]','', null) returning ID_ORDD";
                            $res=$this->grabaBD();
                        }else{   
                        }
            }
        }else{
            echo 'Ocurro un error en la cabecera del archivo, favor de reportar a sistemas al 55 50553392';
        }
            //echo '<br/>Ultima Columna: '.$col.'<br/>';
            return;
    }


    function diltex($xlsx, $hoja, $file, $ido){
        $usuario=$_SESSION['user']->ID;
        $ln=0;$piezas=0;
        $this->query="INSERT INTO FTC_ALMACEN_ORDEN (ID_ORD,CLIENTE,CEDIS,FECHA_CARGA,FECHA_ASIGNA,FECHA_ALMACEN,FECHA_CARGA_F,FECHA_ASIGNA_F,FECHA_ALMACEN_F,STATUS,NUM_PROD,CAJAS,PRIORIDAD, ARCHIVO, USUARIO, ORIGINAL) VALUES (NULL, '$hoja', '',current_timestamp, null, null, null, null, null, 1, 0, 0, 0, '$file', $usuario, $ido) returning ID_ORD";
        $res=$this->grabaBD();
        $res= ibase_fetch_object($res);
        $idord=@$res->ID_ORD;
        if(@$idord>0){
            foreach ($xlsx->rows() as $key){
                $col='A';$ln++;
                        if($ln >= 7 and $ln<=count($xlsx->rows()) - 1 and ($key[1] !='' and $key[3] !='')){
                            if(is_numeric($key[4])){$piezas += $key[4];}
                            $this->query="INSERT INTO FTC_ALMACEN_ORDEN_DET (ID_ORDD, ID_ORD, PROD, DESCR, PZAS, CAJAS, COLOR, CEDIS, PZAS_SUR, CAJAS_SUR, STATUS, OBS, ORDEN, UPC, ITEM, LINEA_NWM, UNIDAD) VALUES (NULL, $idord, '$key[1]', '', $key[3], 0, '', '', 0, 0, 1, '', '$key[6]','','','', $key[2]) returning ID_ORDD";
                            $res=$this->grabaBD();
                            $res=ibase_fetch_object($res);
                            $res=$res->ID_ORDD;
                            if($res <= 0){
                            
                            } else{
                                $odns[]=$res;
                            }
                        }else{   
                        }
            }
        }else{
            echo 'Ocurro un error en la cabecera del archivo, favor de reportar a sistemas al 55 50553392';
        }
            //echo '<br/>Ultima Columna: '.$col.'<br/>';
            return;
    }

    ///// mixup1($xlsx, $hoja, $file, $ido)

    function mixup1($xlsx, $hoja, $file, $ido){
        $usuario=$_SESSION['user']->ID;
        $ln=0;$piezas=0;//$tg=0;
        $this->query="INSERT INTO FTC_ALMACEN_ORDEN (ID_ORD,CLIENTE,CEDIS,FECHA_CARGA,FECHA_ASIGNA,FECHA_ALMACEN,FECHA_CARGA_F,FECHA_ASIGNA_F,FECHA_ALMACEN_F,STATUS,NUM_PROD,CAJAS,PRIORIDAD, ARCHIVO, USUARIO, ORIGINAL) VALUES (NULL, '$hoja', '',current_timestamp, null, null, null, null, null, 1, 0, 0, 0, '$file', $usuario, $ido) returning ID_ORD";
        $res=$this->grabaBD();
        $res= ibase_fetch_object($res);
        $idord=@$res->ID_ORD;
        if(@$idord>0){
            foreach ($xlsx->rows() as $key){
                $col='A';$ln++;$nC=0;
                for ($i=0; $i < count($key); $i++) { 
                    if($ln==2){
                        $oc = $key[2];
                    }
                        if( $ln==4 and $nC>= 3){/// por esta linea solo entra una vez.
                            $subLn=$ln+1;
                            $lnn=0;
                            foreach($xlsx->rows() as $k2){ /// k2 es la nueva linea. y $nC es el numero de columnas. 
                                $lnn++;
                                if($lnn >= $subLn and $lnn <= count($xlsx->rows()) and $nC>=3 and $k2[$nC]!=''){
                                    if(strtoupper($k2[1]) =='TOTAL GENERAL'){
                                        $this->query="UPDATE FTC_ALMACEN_ORDEN_DET SET ORDEN ='$oc' where id_ord= $idord";
                                        $this->EjecutaQuerySimple();
                                        break;
                                    }elseif (trim($k2[1])=='Total general'){
                                        break;
                                    }elseif ($key[$nC] == 'ASIGNADO') {
                                        break;
                                    }else{
                                        $piezas += $k2[$nC];
                                        $this->query="INSERT INTO FTC_ALMACEN_ORDEN_DET (ID_ORDD, ID_ORD, PROD, DESCR, PZAS, CAJAS, COLOR, CEDIS, PZAS_SUR, CAJAS_SUR, STATUS, OBS, ORDEN, UPC, ITEM, LINEA_NWM, UNIDAD) VALUES
                                        (NULL, $idord, '$k2[1]', '', $k2[$nC], 0, '', '$key[$nC]', 0, 0, 1, '', '','$k2[0]','','', 1) returning ID_ORDD";
                                        $res=$this->grabaBD();
                                        $res=ibase_fetch_object($res);
                                        $res=$res->ID_ORDD;
                                        if($res <= 0){
                                            echo $this->query;
                                            //die();
                                        } else{
                                            $odns[]=$res;
                                        }

                                    }
                                }
                            }
                        }
                    $nC++;
                    $col++;
                }
            }
        }else{
            echo 'Ocurro un error en la cabecera del archivo, favor de reportar a sistemas al 55 50553392';
        }
            //echo '<br/>Ultima Columna: '.$col.'<br/>';
            return;    
    }

    function mixupg($xlsx, $hoja, $file, $ido){/// Mixup Generico enviado por Claudia el 18 de Febrero 
        $usuario=$_SESSION['user']->ID;
        $ln=0;$piezas=0;
        $this->query="INSERT INTO FTC_ALMACEN_ORDEN (ID_ORD,CLIENTE,CEDIS,FECHA_CARGA,FECHA_ASIGNA,FECHA_ALMACEN,FECHA_CARGA_F,FECHA_ASIGNA_F,FECHA_ALMACEN_F,STATUS,NUM_PROD,CAJAS,PRIORIDAD, ARCHIVO, USUARIO, ORIGINAL) VALUES (NULL, '$hoja', '',current_timestamp, null, null, null, null, null, 1, 0, 0, 0, '$file', $usuario, $ido) returning ID_ORD";
        $res=$this->grabaBD();
        $res= ibase_fetch_object($res);
        $idord=@$res->ID_ORD;
        if(@$idord>0){
            foreach ($xlsx->rows() as $key){
                $col='A';$ln++;
                        if($ln >= 7 and $ln<=count($xlsx->rows()) - 1 and ($key[3] !='' and $key[6] !='')){
                            if(is_numeric($key[6])){$piezas+=$key[6];}
                            $this->query="INSERT INTO FTC_ALMACEN_ORDEN_DET (ID_ORDD, ID_ORD, PROD, DESCR, PZAS, CAJAS, COLOR, CEDIS, PZAS_SUR, CAJAS_SUR, STATUS, OBS, ORDEN, UPC, ITEM, LINEA_NWM, UNIDAD) VALUES (NULL, $idord, '$key[3]', '', $key[6], 0, '', '', 0, 0, 1, '', '$key[9]','$key[2]','','', 0) returning ID_ORDD";
                            //echo '<br/>'.$this->query.'<br/>';
                            $res=$this->grabaBD();
                            $res=ibase_fetch_object($res);
                            $res=$res->ID_ORDD;
                            if($res <= 0){
                            
                            } else{
                                $odns[]=$res;
                            }
                        }else{   
                        }
            }
        }else{
            echo 'Ocurro un error en la cabecera del archivo, favor de reportar a sistemas al 55 50553392';
        }
            //echo '<br/>Ultima Columna: '.$col.'<br/>';
            return;   
    }

    function intelisis($xlsx, $hoja, $file, $ido){
        $usuario=$_SESSION['user']->ID;$odns=array();
        $ln=0;$piezas=0;
        //if($hoja == 'Hoja1'){$hoja='Intelisis'}
        $this->query="INSERT INTO FTC_ALMACEN_ORDEN (ID_ORD,CLIENTE,CEDIS,FECHA_CARGA,FECHA_ASIGNA,FECHA_ALMACEN,FECHA_CARGA_F,FECHA_ASIGNA_F,FECHA_ALMACEN_F,STATUS,NUM_PROD,CAJAS,PRIORIDAD, ARCHIVO, USUARIO, ORIGINAL) VALUES (NULL, '$hoja', '',current_timestamp, null, null, null, null, null, 1, 0, 0, 0, '$file', $usuario, $ido) returning ID_ORD";
        $res=$this->grabaBD();
        $res= ibase_fetch_object($res);
        $idord=@$res->ID_ORD;
        if(@$idord>0){
            foreach ($xlsx->rows() as $key){
                $col='A';$ln++;
                        if($ln == 1){$orden=substr($key[1],12);}
                        if($ln == 2){$clie=substr($key[1],6, strpos($key[1],",")-6 );}
                        if($ln >= 6 and $ln<=count($xlsx->rows())-1 and ($key[0]!='' and $key[3] !='')){
                            if(is_numeric($key[0])){$piezas += $key[0];}
                            $upc = substr($key[4],1);
                            $this->query="INSERT INTO FTC_ALMACEN_ORDEN_DET (ID_ORDD, ID_ORD, PROD, DESCR, PZAS, CAJAS, COLOR, CEDIS, PZAS_SUR, CAJAS_SUR, STATUS, OBS, ORDEN, UPC, ITEM, LINEA_NWM, UNIDAD) VALUES (NULL, $idord, '$key[3]', '', $key[0], 0, '', '', 0, 0, 1, '', '$orden','$upc','','', 1) returning ID_ORDD";
                            $res=$this->grabaBD();
                            $res=ibase_fetch_object($res);
                            $res=$res->ID_ORDD;
                            if($res <= 0){
                                         
                            } else{
                                $odns[]=$res;
                            }
                        }else{
                            //echo "no valida la linea ".$ln.'<br/>';
                        }
            }

            $this->query="UPDATE FTC_ALMACEN_ORDEN SET CLIENTE = '$clie' where id_ord = $idord";
            $this->queryActualiza();
        }else{
            echo 'Ocurro un error en la cabecera del archivo, favor de reportar a sistemas al 55 50553392';
        }
        return $odns;
    }

    function insOrdenes($info){
        $n=0; 
        foreach($info as $i){
            $n++;
            $usuario=$_SESSION['user']->ID;
            $id = $i['ID']; $cliente = $i['Cliente']; $mov = trim($i['Mov']); $movId = $i['MovID']; $oc = $i['OrdenCompra'];$nombre=$i['nombre']; $staInt=$i['Estatus']; $suc = $i[1]; $idInt = $i['ID'];
            //echo 'Sucursal: '.$suc;
            //print_r($i); 
            //die; 
            $this->query="SELECT * FROM FTC_ALMACEN_ORDEN WHERE ID_ORD = $id";
            $res=$this->EjecutaQuerySimple();
            if(!ibase_fetch_row($res)){
                $this->query="INSERT INTO FTC_ALMACEN_ORDEN (ID_ORD, idCliente, CLIENTE,CEDIS,FECHA_CARGA,FECHA_ASIGNA,FECHA_ALMACEN,FECHA_CARGA_F,FECHA_ASIGNA_F,FECHA_ALMACEN_F,STATUS,NUM_PROD,CAJAS,PRIORIDAD, ARCHIVO, USUARIO, ORIGINAL, MOV, Movid, STA_INT, ID_INT) VALUES ($id, '$cliente', '$nombre', '$suc',current_timestamp, null, null, null, null, null, 1, 0, 0, 0, '$mov'||'$movId', '$usuario', '$oc', '$mov','$movId', '$staInt', $idInt ) returning ID_ORD ";
                    $res=$this->grabaBD();
                if(ibase_fetch_object($res)){
                    /// insertamos los detalles del documento;
                    // tenemos que traerlo de intelisis
                    /// o mejor lo insertamos cuando lo abra el usuario....
                }       
            }
        }
        return;
    }

    function insDetOcInt($datos){
        foreach($datos as $d){
            $id = $d['ID']; $prod=trim(strtoupper($d['Articulo']));$desc=trim($d['descr']); $cant=$d['CantidadInventario']; $part=$d['Renglon']; $upc=trim($d['upc']); $caja = $d['Factor'];
            $this->query="SELECT * FROM FTC_ALMACEN_ORDEN_DET WHERE ID_ORD = $id and PARTIDA =$part";
            $res=$this->EjecutaQuerySimple();
            if(!ibase_fetch_object($res)){
                $this->query="INSERT INTO FTC_ALMACEN_ORDEN_DET (ID_ORDD, ID_ORD, PARTIDA, PROD, DESCR, PZAS, CAJAS, COLOR, CEDIS, PZAS_SUR, CAJAS_SUR, STATUS, OBS, ORDEN, UPC, ITEM, LINEA_NWM, UNIDAD) VALUES (NULL, $id, $part,'$prod', '$desc', $cant, $caja, '', (select cedis from FTC_ALMACEN_ORDEN where id_ord = $id ), 0, 0, 1, '', '','$upc','','', 1) returning ID_ORDD";
                $res=$this->grabaBD();
                $res=ibase_fetch_object($res);
                $res=$res->ID_ORDD;
            }
        }
        return;
    }

    function datOrden($id_o){
        $this->query="SELECT * FROM FTC_ALMACEN_ORDENES WHERE ID_ORD = $id_o";
        $res=$this->EjecutaQuerySimple();
        $row=ibase_fetch_object($res);
        return $row;
    }

    function orden($id_o, $t, $param){
        $data= array(); $p=''; $pnd =array();
        if($t == 'd'){
            $this->query="SELECT * FROM FTC_ALMACEN_ORDENES_DETALLES where id_ord=$id_o";
        }elseif($t == 'p'){
            $this->query="SELECT id_Ord, id_ordd, prod, descr, pzas, cedis, orden, upc, item, PROD_SKU, pzas_sur, status, id_status, asig, (SELECT FIRST 1 ART||':'||cast(CantidadReservada * FACTOR as int)||' ('|| cast(CantidadReservada as int) ||' de '|| cast(factor as int) ||')' as intelisis  FROM FTC_ALMACEN_SIN_PAR_INT i WHERE i.ID_ORDD = d.ID_ORDD order by ID_SINC desc)
                from ftc_almacen_ordenes_detalles d where id_ord = $id_o";
        }elseif($t == 's'){
            if(!empty($param)){
                $p= " and cedis = '".$param."'";
            }
            $this->marcaOrden($id_o);
            $this->query="SELECT ID_ORDD,UPC, ITEM, PROD, DESCR, PZAS, ASIG, CAJAS, UNIDAD, PROD_SKU, orden, cedis,PZAS_SUR, CAJAS_SUR, status, ETIQUETA, id_status FROM FTC_ALMACEN_ORDENES_DETALLES WHERE ID_ORD = $id_o and id_status >=3 $p";
        }
        $res=$this->EjecutaQuerySimple();
        while ($tsArray=ibase_fetch_object($res)) {
            $data[]=$tsArray;
        }
        if($t == 's'){
            $i=0;$s=0;
            $this->query="SELECT * FROM FTC_ALMACEN_ORDEN_DET WHERE ID_ORD=$id_o and (ASIG - PZAS_SUR)>0 ";
            $rs=$this->EjecutaQuerySimple();
            while($tsArray=ibase_fetch_object($rs)){
                $pnd[]=$tsArray;
            }
            foreach($pnd as $d){
                //$res=$this->surteAuto($d);
                $dataOCCHG = array();
                $st = array();
                $usuario = $_SESSION['user']->ID;
                $prod = $d->PROD;
                $this->query = "SELECT * FROM FTC_ALMACEN_OC_CHG WHERE id_ordd = $d->ID_ORDD and base = '$prod'";
                $res=$this->EjecutaQuerySimple();
                while($tsArray=ibase_fetch_object($res)){
                    $dataOCCHG[] = $tsArray;
                }
                if(count($dataOCCHG) > 0){
                    foreach ($dataOCCHG as $k){
                        $surt = $k->SURTIDAS; /// hay que revisar este impacto
                        $asig = $k->CANT - $surt;
                        $prodS = $k->NUEVO; // Producto a surtir.
                        //$prod
                        if(($asig-$surt) <= 0 ){ /// lo asignado menos lo surtido nos da 0 quiere decir que ya se surtio o no hay nada que hacer.
                            $s++;
                        }else{ /// aqui si tenemos que aisgnar el producto a la linea.
                            $i++;
                            $categoria = 'Primera'; /// hay que hacer una funcion que traiga la categoria segun el cliente. pero la debemos de pasar al inicio de esta funcion.
                            $cat = 1;
                            $this->query="SELECT * FROM FTC_ALMACEN_MOV_DET WHERE INTELISIS = '$prodS' and disponible > 0 and categoria = '$categoria' order by fecha_ingreso asc, ID_AM ASC";
                            //echo '<br/> Busqueda por componente: '.$this->query;
                            $res=$this->EjecutaQuerySimple();
                            while($tsArray=ibase_fetch_object($res)){
                                $st[]=$tsArray;
                            }
                            if(count($st)>0){ /// si hay producto disponible.
                                foreach($st as $ms){
                                    $disp=$ms->DISPONIBLE; 
                                    if($disp >= $asig){ 
                                        $pzas = $asig;    
                                    }else{
                                        $pzas = $disp;    
                                    }
                                    $this->query="INSERT INTO FTC_ALMACEN_MOV_SAL (ID_MS, ID_COMPS, CANT, ID_ORDD, USUARIO, FECHA, STATUS, ID_MOV, PIEZAS, UNIDAD, ID_COMPP, ID_PROD, categoria) VALUES (NULL, $ms->ID_COMPS, 0, $d->ID_ORDD, $usuario, current_timestamp, 'P', $ms->ID_AM, $pzas, 1, (SELECT COMPP FROM FTC_ALMACEN_COMP WHERE ID_COMP = $ms->ID_COMPS), 
                                        (select m.prod from ftc_almacen_mov m where m.id_am = $ms->ID_AM),
                                         $cat)";
                                    //echo '<br/> Insercion del movimiento de Salida: '.$this->query;
                                    $this->grabaBD();
                                    $this->query="UPDATE FTC_ALMACEN_ORDEN_DET SET PZAS_SUR = (PZAS_SUR + $pzas) where id_ordd = $d->ID_ORDD";
                                    //echo '<br/> Actualizacion del detalle de la orden: '.$this->query;
                                    $this->queryActualiza();
                                    $this->query="UPDATE FTC_ALMACEN_OC_CHG SET SURTIDAS = SURTIDAS + $pzas where id_chg = $k->ID_CHG";
                                    //echo '<br/> Actualizacion en la tabla de cambios(Asignaciones): '.$this->query;
                                    $this->queryActualiza();
                                    $asig=$asig-$pzas;
                                    if($asig == 0){
                                        break;
                                    }
                                }
                                unset($st);
                            }else{
                                echo 'No hay producto Disponible';
                            }
                        }
                    }
                }
                unset($dataOCCHG);
            }
        }
        if($t=='s'){
            $data=array();
            if(!empty($param)){
                $p= " and cedis = '".$param."'";
            }
            $this->query="SELECT ID_ORDD,UPC, ITEM, PROD, DESCR, PZAS, ASIG, CAJAS, UNIDAD, PROD_SKU, orden, cedis,PZAS_SUR, CAJAS_SUR, status, ETIQUETA, id_status FROM FTC_ALMACEN_ORDENES_DETALLES WHERE ID_ORD = $id_o and id_status >=3 $p";
            $res=$this->EjecutaQuerySimple();
            while($tsArray=ibase_fetch_object($res)){
                $data[]=$tsArray;
            }
        }
        return $data;
    }

    function marcaOrden($id_o){
        $this->query="UPDATE FTC_ALMACEN_ORDEN SET NUM_PROD = NUM_PROD + 1 WHERE ID_ORD = $id_o";
        $this->queryActualiza();

        $this->query="UPDATE FTC_ALMACEN_ORDEN SET FECHA_ALMACEN = current_timestamp where id_ord = $id_o and FECHA_ALMACEN is null";
        $this->queryActualiza();

        return;
    }

    function iniciaSurt($id_o){
        $this->query="UPDATE FTC_ALMACEN_ORDEN SET FECHA_CARGA_F = current_timestamp WHERE ID_ORD = $id_o and FECHA_CARGA_F is null";
        $this->queryActualiza();

        $this->query="UPDATE FTC_ALMACEN_ORDEN SET IMP = IMP +1 WHERE ID_ORD = $id_o";
        $this->queryActualiza();
        return;
    }

    function surteAuto($d){/// se degraga y se incluye en el codigo de las ordenes para mayor velocidad 
        $data = array();
        $usuario = $_SESSION['user']->ID;
        $prod = $d->PROD;
        $surt = $d->PZAS_SUR;
        $asig = $d->ASIG - $surt;
        if( ($asig-$surt) <= 0 ){
            return;
        }
        // buscamos todas las tarimas donde el producto esta Disponible, por fecha de ingreso
        $this->query="SELECT * FROM FTC_ALMACEN_MOV_DET WHERE INTELISIS = '$prod' and disponible > 0 order by fecha_ingreso asc";
        $res=$this->EjecutaQuerySimple();
        while($tsArray=ibase_fetch_object($res)){
            $data[]=$tsArray;
        }
        //echo 'Se encontro: '.count($data);
        if(count($data)>0){/// si se encuentra el producto, entonces se inicia la asignacion y los movimientos de salida.
            //echo 'entro:'.count($data);
            //die();
            foreach($data as $ms){
            //print_r($ms);
                $disp=$ms->DISPONIBLE; 
                if($disp >= $asig){ ///si lo disponible es mayor a lo asignado, entonces descontamos todo lo necesario e igualamos a 0 lo necesario 
                    $pzas = $asig;    
                }else{/// cuando es mayor lo necesario a la asignacion.
                    $pzas = $disp;    
                }
                $this->query="INSERT INTO FTC_ALMACEN_MOV_SAL (ID_MS, ID_COMPS, CANT, ID_ORDD, USUARIO, FECHA, STATUS, ID_MOV, PIEZAS, UNIDAD, ID_COMPP, ID_PROD) VALUES (NULL, $ms->ID_COMPS, 0, $d->ID_ORDD, $usuario, current_timestamp, 'P', $ms->ID_AM, $pzas, 1, (SELECT COMPP FROM FTC_ALMACEN_COMP WHERE ID_COMP = $ms->ID_COMPS), (select m.prod from ftc_almacen_mov m where m.id_am = $ms->ID_AM))";
                $this->grabaBD();
                $this->query="UPDATE FTC_ALMACEN_ORDEN_DET SET PZAS_SUR = (PZAS_SUR + $pzas) where id_ordd = $d->ID_ORDD";
                $this->queryActualiza();
                $asig=$asig-$pzas;
                if($asig == 0){
                    break;
                }
            }
        }else{
            //echo 'No hay productos para surtir';
        }
        return;
    }


    function delComp($id, $t){
        if($t==1){
            $this->query="UPDATE FTC_ALMACEN_COMP SET STATUS = 9 WHERE ID_COMP = $id and (SELECT coalesce(COUNT(*),0) FROM FTC_ALMACEN_MOV am WHERE am.comps=$id and am.status='F' )=0 and tipo=$t";
            $res= $this->queryActualiza();
            if($res == 1 ){
                $msg= 'Se ha eliminado correctamente'; $sta='ok';
            }else{
                $msg= 'No se ha podido eliminar ya que el componente tiene movimientos'; $sta='no';
            }
        }elseif($t==2){
            $this->query="UPDATE FTC_ALMACEN_COMP SET STATUS = 9 WHERE ID_COMP = $id and (SELECT coalesce(COUNT(*),0) FROM FTC_ALMACEN_COMP WHERE COMPP= $id and status !=9)=0 and tipo=$t";
            $res= $this->queryActualiza();
            if($res == 1 ){
                $msg= 'Se ha eliminado correctamente'; $sta='ok';
            }else{
                $msg= 'No se ha podido eliminar ya que el componente tiene Componentes Secundarios (Tarimas) asociados'; $sta='no';
            }
        }elseif($t == 3 ){
            $this->query="UPDATE FTC_ALMACEN_COMP SET STATUS = 9 WHERE ID_COMP = $id and (SELECT coalesce(COUNT(*),0) FROM FTC_ALMACEN_COMP WHERE COMPP= $id and status !=9)= 0 and tipo=$t";
            $res= $this->queryActualiza();
            if($res == 1 ){
                $msg= 'Se ha eliminado correctamente'; $sta='ok';
            }else{
                $msg= 'No se ha podido eliminar ya que el componente tiene Componentes Secundarios (Tarimas) asociados'; $sta='no';
            }
        }
        return array("msg"=>$msg, "status"=>$sta);
    }

    function actualizaCodigo(){/// funcion degragada, borrar al cierre, tambien la tabla
        $data=array();
        $this->query="SELECT * FROM SP_MIZCO_INFORMACIONALMACENES WHERE STATUS IS NULL ";
        $res=$this->EjecutaQuerySimple();
        while ($tsArray=ibase_fetch_object($res)) {
            $data[]=$tsArray;
        }
        if(count($data)>0){
            foreach ($data as $k) {
                $clave = $k->CVE;
                $codigos=explode(";",$k->CODIGO_X_CADENA);
                for ($i=0; $i < count($codigos) ; $i++){ 
                    $datos=explode(":", $codigos[$i]);
                    if(count($datos)>1){
                        $clie=$datos[0];$codigo=$datos[1];
                        $this->query="INSERT INTO FTC_ALMACEN_SKU (ID_S, ID_PINT, CLIENTE, SKU) VALUES (NULL, (SELECT ID_PINT FROM FTC_ALMACEN_PROD_INT WHERE ID_INT = '$clave'),'$clie', '$codigo' )";
                        $res=$this->grabaBD();
                        if($res==1){
                            $this->query="UPDATE SP_MIZCO_INFORMACIONALMACENES SET STATUS = 1 WHERE ID=$k->ID";
                            $this->queryActualiza();
                        }
                    }
                }
            }    
        }
        return;
    }

    function asgProd($ord, $prod, $pza, $t, $c, $s){
        $infoAsig=array();
        if($t=='m'){
            $res=$this->asgMultiple($ord, $prod);
            return $res;
        }
        $data=array();$msg='Se han asignado '.$pza.' del producto '.$prod;
        if($t=='q'){
            $this->query="SELECT * FROM FTC_ALMACEN_ORDEN_DET WHERE ID_ORD = $ord and PROD = trim('$prod') and status < 5";
            $res=$this->EjecutaQuerySimple();
            while($tsArray=ibase_fetch_object($res)){
                $data[]=$tsArray;
            }
            foreach ($data as $k) {
                $this->limpiaAsig($k->ID_ORDD);               
            }
            $this->query="UPDATE FTC_ALMACEN_ORDEN_DET SET ASIG = 0, status=1 where PROD = trim('$prod') and id_ord = $ord and status < 5";
            $this->queryActualiza();
        }
        unset($data);
        $data = array();
        $this->query="SELECT * FROM FTC_ALMACEN_ORDEN_DET WHERE PROD = '$prod' and id_ord = $ord and status < 5";
        $res=$this->EjecutaQuerySimple();
        while ($tsArray=ibase_fetch_object($res)) {
            $data[]=$tsArray;            
        }        
        if($t== 'q'){
            $msg="Se han quitado ".$pza." del producto ".$prod;
            $pza=$s - $pza;
            //$this->actStatus()
            //return;
        }
        //$pza=$s - $pza;
        //echo 'Piezas a repartir:'.$pza.'<br/>'; 
        foreach ($data as $d){
            $pen = $d->PZAS - $d->ASIG;
            //echo '<br/>Pendiente: '.$pen.'<br/>'; 
            if($pen <= $pza){
                $pza=$pza-$pen;
                //    echo '<br/>Se asignan '.$pen.' de '.$pen.' teniendo '.$pza.' piezas pendientes por asignar al siguiente renglon';
                $this->query="UPDATE FTC_ALMACEN_ORDEN_DET SET ASIG = $pen where id_ordd = $d->ID_ORDD";
            }else{
                //    echo '<br/>Se asignan el residuo parcial '.$pza.' y se marca como 0 las piezas';
                $this->query="UPDATE FTC_ALMACEN_ORDEN_DET SET ASIG = $pza where id_ordd = $d->ID_ORDD";
                $pza=0;
            }
            $res=$this->queryActualiza();
        }
        $this->actStaOD('', $prod, $ord, 'm', 'a');

        $this->query="SELECT chg.*, (SELECT PARTIDA FROM FTC_ALMACEN_ORDEN_DET WHERE ID_ORDD = chg.id_ordd) as RENGLON FROM FTC_ALMACEN_OC_CHG chg WHERE chg.ID_ORD = $ord";
        $result=$this->EjecutaQuerySimple();
        while($tsArray=ibase_fetch_object($result)){
            $infoAsig[]=$tsArray;
        }
        return array("status"=>'ok', "msg"=>$msg, "infoAsig"=>$infoAsig);
    }

    function asgMultiple($ord, $prod){
        $prod = explode(":", $prod);$usuario=$_SESSION['user']->ID; $infoAsig=array();
        for ($i=0; $i < count($prod); $i++){ 
            $data=array();
                $this->query="SELECT * FROM FTC_ALMACEN_ORDEN_DET WHERE ID_ORD = $ord and PROD = '$prod[$i]' and status < 5";
                $res=$this->EjecutaQuerySimple();
                while($tsArray=ibase_fetch_object($res)){
                    $data[]=$tsArray;
                }
                foreach ($data as $k){
                    $id_ordd = $k->ID_ORDD; $cant = $k->PZAS; $ln = $k->ID_ORDD; $c=$k->PZAS;
                    $this->limpiaAsig($id_ordd);
                    $this->query="SELECT * FROM FTC_ALMACEN_OC_CHG WHERE id_ordd = $ln and nuevo = (SELECT PROD FROM FTC_ALMACEN_ORDEN_DET WHERE ID_ORDD = $ln)";
                    $res=$this->EjecutaQuerySimple();
                    if($row=ibase_fetch_object($res)){
                        $this->query="UPDATE FTC_ALMACEN_OC_CHG SET CANT = $c , color='', FECHA = current_timestamp, tipo = 'A' where id_ordd = $ln and nuevo = (SELECT PROD FROM FTC_ALMACEN_ORDEN_DET WHERE ID_ORDD = $ln) RETURNING id_chg ";
                        $res=$this->grabaBD();
                        $idchg = ibase_fetch_object($res)->ID_CHG; $tipo = ' Actualización ';
                    }else{
                        $this->query = "INSERT INTO FTC_ALMACEN_OC_CHG (id_chg, id_ord, id_ordd, BASE, NUEVO, CANT, FECHA, USUARIO, STATUS, TIPO) 
                                    VALUES (null, (select id_ord from FTC_ALMACEN_ORDEN_DET where id_ordd = $ln), $ln ,(select prod from FTC_ALMACEN_ORDEN_DET where id_ordd = $ln),(select prod from FTC_ALMACEN_ORDEN_DET where id_ordd = $ln), $c, current_timestamp, '$usuario',0, 'D' )";
                        $res=$this->grabaBD();
                        $idchg = ibase_fetch_object($res)->ID_CHG; $tipo = ' Creación ';
                    }
                    $this->query="INSERT INTO FTC_ALMACEN_LOG (id_log, usuario, tipo, subtipo, tabla, fecha, status, id, Obs) 
                                    VALUES (null, $usuario, 'Orden Detalle', 'Cambio Presentacion'||'$tipo', 1, current_timestamp, 0, $ln, 'Asignacion de Colores del Producto '||(SELECT PROD FROM FTC_ALMACEN_ORDEN_DET WHERE ID_ORDD = $ln)||' -- '||(SELECT PROD FROM FTC_ALMACEN_ORDEN_DET WHERE ID_ORDD = $ln)||' cant: '||$c)";
                    $this->grabaBD();
                    $this->query="UPDATE FTC_ALMACEN_ORDEN_DET d SET ASIG = (SELECT SUM(ch.CANT) FROM FTC_ALMACEN_OC_CHG ch WHERE ch.ID_ORDD = d.id_ordd) where d.ID_ORDD = $ln";
                    $res=$this->queryActualiza();
                    /*
                    $this->query="INSERT INTO FTC_ALMACEN_OC_CHG (id_chg, id_ord, id_ordd, BASE, NUEVO, CANT, FECHA, USUARIO, STATUS, TIPO) 
                                VALUES (null, $ord, $id_ordd, '$prod[$i]', '$prod[$i]', $cant, current_timestamp, $usuario, 0, 'M')";
                    $this->grabaBD();
                    $this->query="UPDATE FTC_ALMACEN_ORDEN_DET SET ASIG = PZAS, status= 2 WHERE ID_ORD = $ord and PROD = '$prod[$i]'";
                    $this->queryActualiza();
                    */
                }
            unset($data);

            $this->query="SELECT chg.*, (SELECT PARTIDA FROM FTC_ALMACEN_ORDEN_DET WHERE ID_ORDD = chg.id_ordd) as RENGLON FROM FTC_ALMACEN_OC_CHG chg WHERE chg.ID_ORD = $ord and base = '$prod[$i]'";
            $result=$this->EjecutaQuerySimple();
            while($tsArray=ibase_fetch_object($result)){
                $infoAsig[]=$tsArray;
            }
        }
        return array("infoAsig"=>$infoAsig);
    }

    function limpiaAsig($ordd){
        $this->query="UPDATE FTC_ALMACEN_ORDEN_DET SET ASIG = 0 WHERE ID_ORDD = $ordd";
        $this->queryActualiza();
        $this->query="UPDATE FTC_ALMACEN_OC_CHG SET CANT = 0 WHERE ID_ORDD = $ordd";
        $this->queryActualiza();
        return;
    }

    function detLinOrd($ord, $prod){
        $data=array();
        $this->query="SELECT ID_ORDD, ID_ORD, PROD, PZAS, CAJAS, COLOR, CEDIS, PZAS_SUR, CAJAS_SUR, STATUS, OBS, ORDEN, UPC, ITEM, LINEA_NWM, UNIDAD, CADENA, ASIG, ETIQUETA FROM FTC_ALMACEN_ORDEN_DET WHERE PROD = '$prod' and id_ord = $ord";
        $res=$this->EjecutaQuerySimple();
        while ($tsArray=ibase_fetch_object($res)) {
            $data[]=$tsArray;
        }
        return array("status"=>'ok', "datos"=>$data);
    }

    function actProOrd($prod, $oc, $prodn){
        $this->query="UPDATE FTC_ALMACEN_ORDEN_DET SET PROD = '$prodn', DESCR = (SELECT DESCR FROM FTC_ALMACEN_PROD_INT WHERE ID_INT = '$prodn') where PROD = '$prod' and id_ord = $oc";
        $res=$this->queryActualiza();
        if($res >=1){
            $this->query="SELECT FIRST 1 * FROM FTC_ALMACEN_PROD_INT WHERE ID_INT = '$prodn'";
            $r=$this->EjecutaQuerySimple();
            $data= ibase_fetch_object($r);
        }
        return array("status"=>'ok', "prod"=>$data->ID_INT, "desc"=>$data->DESC);
    }

    function asgLn($ln, $c, $org){
        $sta='ok'; $msg="Se actualizo correctamente".$org; $data=array();$infoAsig=array(); $pres=array(); $asigNow=0;
        $usuario= $_SESSION['user']->ID;
        $campo =  $org=='I'? 'chg.BASE':'chg.NUEVO';
        $this->query="SELECT chg.*, coalesce((SELECT SUM(CANT) FROM FTC_ALMACEN_OC_CHG sm where base = (SELECT PROD FROM FTC_ALMACEN_ORDEN_DET WHERE ID_ORDD = $ln) and id_ordd =$ln ), 0) as cantAsigNow FROM FTC_ALMACEN_OC_CHG chg WHERE chg.id_ordd = $ln and $campo = (SELECT PROD FROM FTC_ALMACEN_ORDEN_DET WHERE ID_ORDD = $ln)";

        $res=$this->EjecutaQuerySimple();
        while($tsArray= ibase_fetch_object($res)){
            $pres[]=$tsArray;
        }
        if(count($pres)>0){
            foreach ($pres as $pre) {
                $asigNow += $pre->CANTASIGNOW;
                $color = $pre->COLOR;
            }
            if(count($pres)>1 or $asigNow > 0){
                $tipo = ' Actualización '.$org;
            }else{  
                $this->limpiaAsig($ln);
                $this->query="UPDATE FTC_ALMACEN_OC_CHG SET CANT = $c , FECHA = current_timestamp, tipo = 'A' where id_ordd = $ln and nuevo = (SELECT PROD FROM FTC_ALMACEN_ORDEN_DET WHERE ID_ORDD = $ln) and status < 3 RETURNING id_chg ";
                $res=$this->grabaBD();
                $idchg = ibase_fetch_object($res)->ID_CHG; $tipo = ' Actualización '.$org;
            }
        }else{
            $this->query = "INSERT INTO FTC_ALMACEN_OC_CHG (id_chg, id_ord, id_ordd, BASE, NUEVO, CANT, FECHA, USUARIO, STATUS, TIPO, COLOR) 
                        VALUES (null, (select id_ord from FTC_ALMACEN_ORDEN_DET where id_ordd = $ln), $ln ,(select prod from FTC_ALMACEN_ORDEN_DET where id_ordd = $ln),(select prod from FTC_ALMACEN_ORDEN_DET where id_ordd = $ln), $c, current_timestamp, '$usuario',0, 'D','$org') returning id_chg";
            $res=$this->grabaBD();
            $idchg = ibase_fetch_object($res)->ID_CHG; $tipo = ' Creación '.$org;
        }

        $this->query="INSERT INTO FTC_ALMACEN_LOG (id_log, usuario, tipo, subtipo, tabla, fecha, status, id, Obs) 
                        VALUES (null, $usuario, 'Orden Detalle', 'Cambio Presentacion'||'$tipo', 1, current_timestamp, 0, $ln, 'Asignacion de Colores del Producto '||(SELECT PROD FROM FTC_ALMACEN_ORDEN_DET WHERE ID_ORDD = $ln)||' -- '||(SELECT PROD FROM FTC_ALMACEN_ORDEN_DET WHERE ID_ORDD = $ln)||' cant: '||$c)";
        $this->grabaBD();
        $this->query="UPDATE FTC_ALMACEN_ORDEN_DET d SET ASIG = (SELECT SUM(ch.CANT) FROM FTC_ALMACEN_OC_CHG ch WHERE ch.ID_ORDD = d.id_ordd) where d.ID_ORDD = $ln";
        $res=$this->queryActualiza();
        if ($res != 1){
            $sta='no'; $msg="No se actualizo correctamente, quizas la cantidas excede a lo requerido";
        }
        $this->actStaOD($ln, '', '', 'l', 'a');
        ####
        ## Informacion para sincronizar intelisis, obtenemos todo lo asiginado de la orden   
        ####
        $this->query="SELECT chg.*, (SELECT PARTIDA FROM FTC_ALMACEN_ORDEN_DET WHERE ID_ORDD = $ln) as RENGLON FROM FTC_ALMACEN_OC_CHG chg WHERE chg.ID_ORD = (SELECT ID_ORD FROM FTC_ALMACEN_ORDEN_DET WHERE ID_ORDD = $ln)";
        $result=$this->EjecutaQuerySimple();
        while($tsArray=ibase_fetch_object($result)){
            $infoAsig[]=$tsArray;
        }
        return array("status"=>$sta, "msg"=>$msg, "infoAsig"=>$infoAsig);
    }

    function actStaOD($ln, $prod, $oc, $t, $m){
        $data=array();
        if($t == 'l' and $m == 'a'){
            $this->query="SELECT * FROM FTC_ALMACEN_ORDEN_DET WHERE ID_ORDD = $ln and status < 5";
            $res=$this->EjecutaQuerySimple();
            $res=ibase_fetch_object($res);
            if(count($data)>0 ){
                if($res->PZAS == $res->ASIG){
                    $status = 2;
                }elseif ($res->PZAS > $res->ASIG AND $res->ASIG > 0){
                    $status = 5;
                }elseif ($res->ASIG == 0) {
                    $status = 1;
                }
                $this->query="UPDATE FTC_ALMACEN_ORDEN_DET SET STATUS = $status where id_ordd = $ln";
                $this->EjecutaQuerySimple();
            }
        }
        if($t == 'm' and $m == 'a'){
            $this->query="SELECT * FROM FTC_ALMACEN_ORDEN_DET WHERE ID_ORD = $oc and prod = '$prod' and status < 5";
            $res=$this->EjecutaQuerySimple();
            while ($tsArray=ibase_fetch_object($res)) {
                $data[]=$tsArray;
            }
            if(count($data)>0){
                foreach($data as $inf){
                    if($inf->PZAS == $inf->ASIG){
                        $status = 2;
                    }elseif ($inf->PZAS > $inf->ASIG AND $inf->ASIG > 0){
                        $status = 5;
                    }elseif ($inf->ASIG == 0) {
                        $status = 1;
                    }
                    $this->query="UPDATE FTC_ALMACEN_ORDEN_DET SET STATUS = $status where id_ordd = $inf->ID_ORDD";
                    $this->EjecutaQuerySimple();   
                }
            }
        }
        return;
    }

    function chgProd($p, $nP, $o, $t){
        $usuario=$_SESSION['user']->ID;
        $msg="Se ha cambiado el producto"; $sta='ok';$data=array();
        $this->query="SELECT * FROM FTC_ALMACEN_ORDEN_DET WHERE PROD = '$p' and id_ord = $o";
        $r=$this->EjecutaQuerySimple();
        while ($tsArray=ibase_fetch_object($r)) {
            $data[]=$tsArray;
        }
        foreach ($data as $v) {
            $this->query="UPDATE FTC_ALMACEN_ORDEN_DET SET PROD = '$nP', descr = (SELECT DESC FROM FTC_ALMACEN_PROD_INT WHERE ID_INT= '$nP') where prod ='$p' and asig = 0 and id_ordd = $v->ID_ORDD";
            $res=$this->queryActualiza();
            if($res <= 0){
                $msg="No se ha podido cambiar el producto, favor de revisar la informacion.";$sta='no';
            }else{
                $this->query="INSERT INTO FTC_ALMACEN_OC_CHG (id_chg, id_ordd, base, nuevo, cant, color, fecha, usuario, status, tipo) values (null, $v->ID_ORDD, '$p', '$nP', $v->PZAS, '', current_timestamp, $usuario, 0, 'p' )";
                $this->grabaBD();
            }
        }
        return array("msg"=>$msg, "status"=>$sta);
    }
    /* depreciada el 06 de Julio del 2022
    function asigCol($ln, $col){
        $usuario=$_SESSION['user']->ID;
        /// obtenemos los datos originales de la linea.
        echo 'Ln' . $ln . ' Colores : '. print_r($col);
        die();

        $this->query="SELECT * FROM FTC_ALMACEN_ORDEN_DET WHERE ID_ORDD = $ln";
        $r = $this->EjecutaQuerySimple();
        $row=ibase_fetch_object($r);
        // Primero cambiamos el producto: 
        $data =array();
        if(!empty($nP)){
            $this->query="SELECT * FROM FTC_ALMACEN_PROD_INT WHERE ID_INT = '$nP'";
            $res=$this->EjecutaQuerySimple();
            while ($tsArray=ibase_fetch_object($res)) {
                $data[]=$tsArray;
            }
            if(count($data)== 1){
                    $this->query="UPDATE FTC_ALMACEN_ORDEN_DET SET PROD = '$nP', descr=(SELECT DESC FROM FTC_ALMACEN_PROD_INT WHERE ID_INT = '$nP') where id_ordd = $ln";
                    $result=$this->queryActualiza();
                    if($result <= 0){
                        $msg="No se ha podido cambiar el producto, favor de revisar la informacion.";$sta='no';
                    }else{
                        $this->query="INSERT INTO FTC_ALMACEN_OC_CHG (id_chg, id_ordd, base, nuevo, cant, color, fecha, usuario, status, tipo) values (null, $ln, '$row->PROD', '$nP', $row->PZAS, '', current_timestamp, $usuario, 0, 'p' )";
                        $this->grabaBD();
                    }
            }
        }
        $c=0;$pzas=0;
        for ($i=0; $i < count($col) ; $i++){ 
            $val=explode(":", $col[$i]);
            if($val[1] > 0 ){
                $c++;
                $pzas += $val[1];
                switch ($val[0]) {
                    case 'az':
                        $color = "Azul";
                        break;
                    case 'bl':
                        $color = "Blanco";
                        break;
                    case 'ng':
                        $color = "Negro";
                        break;
                    case 'ro':
                        $color = "Rosa";
                        break;
                    case 'rj':
                        $color = "Rojo";
                        break;
                    case 'gr':
                        $color = "Gris";
                        break;
                    case 'vd':
                        $color = "Verde";
                        break;
                    default:
                        $color = '';
                        break;
                }
                /// echo '<p>Color:'.$val[0]." Cantidad:".$val[1].'</p>';
                $this->query ="INSERT INTO FTC_ALMACEN_OC_CHG (id_chg, id_ordd, cant, color, fecha, usuario, status, tipo) VALUES (null, $ln, $val[1], '$color', current_timestamp, $usuario, 0 , 'c') ";
                $this->grabaBD();
            }
        }
        if($c>1){
            $this->query="UPDATE FTC_ALMACEN_ORDEN_DET SET COLOR='Mixto', ASIG=$pzas, status= 2 where id_ordd = $ln";
            $this->queryActualiza();
        }elseif($c==1){
            $this->query="UPDATE FTC_ALMACEN_ORDEN_DET SET COLOR='$color', ASIG=$pzas, status= 2 where id_ordd = $ln";
            $this->queryActualiza();
        }

        return array("status"=>'ok', "msg"=>'Se ha actualizado la informacion');
    }*/

    function asigCol($ln, $col){
        $usuario=$_SESSION['user']->ID; $data=array();
        $col= substr($col, 0 , -1);
            $info = explode("|",$col);
            $this->limpiaAsig($ln);
            for ($i=0; $i < count($info) ; $i++){
                @$inf=explode(":",$info[$i]);
                if(isset($inf)){
                    $np = trim($inf[0]); $cant = $inf[1];
                    /// validamos si existe para hacer los cambios 
                    $this->query="SELECT * FROM FTC_ALMACEN_OC_CHG WHERE id_ordd = $ln and nuevo = '$np'";
                    $res=$this->EjecutaQuerySimple();
                    if($row=ibase_fetch_object($res)){
                        $this->query="UPDATE FTC_ALMACEN_OC_CHG SET CANT = $cant , FECHA = current_timestamp, tipo = 'A' where id_ordd = $ln and nuevo = '$np' and status < 6 RETURNING ID_CHG ";
                        $this->queryActualiza();
                        @$idchg = ibase_fetch_object($res)->ID_CHG; $tipo = ' Actualización ';
                    }else{
                        $this->query="INSERT INTO FTC_ALMACEN_OC_CHG (id_chg, id_ord, id_ordd, BASE, NUEVO, CANT, COLOR, FECHA, USUARIO, STATUS, TIPO) 
                                        VALUES (NULL, (SELECT ID_ORD FROM FTC_ALMACEN_ORDEN_DET WHERE ID_ORDD = $ln) , $ln, (SELECT PROD FROM FTC_ALMACEN_ORDEN_DET WHERE ID_ORDD = $ln), '$np', $cant, '', current_timestamp, $usuario, 0, 'P') RETURNING id_chg";
                        $res=$this->grabaBD();
                        $idchg = ibase_fetch_object($res)->ID_CHG; $tipo = ' Creación ';
                    }

                    $this->query="INSERT INTO FTC_ALMACEN_LOG (id_log, usuario, tipo, subtipo, tabla, fecha, status, id, Obs) 
                                    values (null, $usuario, 'Orden Detalle', 'Cambio Presentacion'||'$tipo', 1, current_timestamp, 0, $ln, 'Asignacion de Colores del Producto '||(SELECT PROD FROM FTC_ALMACEN_ORDEN_DET WHERE ID_ORDD = $ln)||' -- '||'$np'||' cant: '||$cant)";
                    $this->grabaBD();

                    $this->query="UPDATE FTC_ALMACEN_ORDEN_DET d SET ASIG = (SELECT SUM(ch.CANT) FROM FTC_ALMACEN_OC_CHG ch WHERE ch.ID_ORDD = d.id_ordd) where d.ID_ORDD = $ln and d.status < 6 ";
                    $this->queryActualiza();
                }
            }
        $this->query="SELECT chg.*, (SELECT PARTIDA FROM FTC_ALMACEN_ORDEN_DET WHERE ID_ORDD = $ln) as RENGLON FROM FTC_ALMACEN_OC_CHG chg WHERE chg.ID_ORD = (SELECT ID_ORD FROM FTC_ALMACEN_ORDEN_DET WHERE ID_ORDD = $ln)";
        $r=$this->EjecutaQuerySimple();
        while($tsArray=ibase_fetch_object($r)){
            $data[]=$tsArray;
        }
        if(!empty($nP)){
            return array("status"=>'ok', "msg"=>'Se ha actualizado la informacion', "infoAsig"=>$data);
        }
        return array("status"=>'ok', "msg"=>'Se ha actualizado la informacion', "infoAsig"=>$data);
    }

    function finA($p, $ord, $t){
        $val=1;$sta='ok';$msg="Se ha finalizado correctamente";
        if($t == 'o'){
            $param = " WHERE ID_ORD = $ord group by id_ord";
            //$campos = " sum(pzas) as piezas, sum(asig) as asignado ";
            $param2 = " WHERE ID_ORD = $ord ";
            $param3 = $param2;
            /// cambiamos el status de la orden a Asignado. 
            $this->query="UPDATE FTC_ALMACEN_ORDEN SET STATUS = 3, FECHA_ASIGNA_F = current_timestamp  WHERE STATUS = 1 AND ID_ORD = $ord";
            //echo $this->query;
            $this->queryActualiza();
            $tabla = 1; $tipo='Orden';
        }elseif($t=='l'){
            $param = " WHERE PROD = '$p' and id_ord = $ord group by Prod, id_ord";
            //$campos = "pzas as piezas, asig as asignado";
            $param2 = " WHERE PROD = '$p' and id_ord = $ord ";
            $param3 = " WHERE BASE = '$p' and id_ord = $ord ";
            $tabla=2; $tipo='Orden Detalle';
        }elseif($t== 'lin'){
            $param2 = " , OBS = '".$p."' WHERE ID_ORDD = ".$ord;
            $tabla=1; $tipo='Orden';
            $param3 = " WHERE BASE = '$p' and id_ord = $ord ";
        }
        //$this->query= "SELECT id_ord, sum(pzas) as piezas, sum(asig) as asignado  FROM FTC_ALMACEN_ORDEN_DET $param";
        //$res=$this->EjecutaQuerySimple();
        //$orden=ibase_fetch_object($res);
        //$val = ($orden->PIEZAS==$orden->ASIGNADO)? 1:0;
        if($val == 1){
            $this->query="UPDATE FTC_ALMACEN_ORDEN_DET SET STATUS = 6 $param2";
            $res=$this->queryActualiza();
            $this->query="UPDATE FTC_ALMACEN_OC_CHG SET STATUS = 6 $param3";
            $this->actStatus($tabla, $tipo, "Asignación", ','.$ord, $p);
        }else{
            $sta='no';$msg="Error faltan partidas por asignar";
        }
        return array("status"=>$sta, "msg"=>$msg);
    }

    function delOC($id){
        $mov=0; $sta='no'; $msg="No se encontro el resgitro para elminarlo";
        $this->query="SELECT o.* , (SELECT count(id_log) FROM FTC_ALMACEN_LOG l where tabla= 1 and l.id = o.id_ord) as Logs FROM FTC_ALMACEN_ORDEN o  WHERE ID_ORD = $id";
        $r=$this->EjecutaQuerySimple();
        $row = ibase_fetch_object($r);
        $this->query="UPDATE FTC_ALMACEN_ORDEN SET STATUS= 9, ARCHIVO = ARCHIVO||'_old'||$id WHERE ID_ORD = $id and (status=1 or status = 2 or status = 3 )";
        $res=$this->queryActualiza();
        if($res==1){
            $sta= 'ok';
            if($row->STATUS == 1 or $row->STATUS==2){
                $msg="Se ha eliminado el archivo y sus referencias.";
            }elseif($row->LOGS > 0 and $row->STATUS==3){
                $msg="El registro tiene ".$row->LOGS." movimientos";
                $sta= 'ok';
                $mov= $row->LOGS;
                $this->query="UPDATE FTC_ALMACEN_ORDEN_DET SET ASIG = 0 WHERE ID_ORD = $id";
                $this->queryActualiza();
            }
            $file = $row->ARCHIVO;
            rename($file, $file.'_old'.$row->ID_ORD);
            $this->actStatus($tabla=1, $tipo='Eliminar', $sub='Orden', ','.$id, $obs='Eliminacion de Orden de compra');
        }
        return array("status"=>$sta,"msg"=>$msg, "mov"=>$mov);
    }

    function correos($opc){
        $data= array();
        $this->query="SELECT * FROM FTC_ALMACEN_EMAIL $opc";
        $res=$this->EjecutaQuerySimple();
        while ($tsArray=ibase_fetch_object($res)) {
            $data[]=$tsArray;
        }
        return $data;
    }

    function actStatus($tabla, $tipo, $sub, $ids, $obs){
        $usuario=$_SESSION['user']->ID;
        $ids=explode(",", substr($ids, 1));
        for ($i=0; $i < count($ids) ; $i++) { 
            $this->query="INSERT INTO FTC_ALMACEN_LOG (id_log, usuario, tipo, SUBTIPO, tabla, fecha, status, id, obs) VALUES (null, $usuario, '$tipo', '$sub', $tabla, current_timestamp, 0, $ids[$i], '$obs')";
            $this->queryActualiza();
        }
        return;
    }

    function log($tabla, $id, $tablad){
        $data=array();
        $this->query="SELECT * from FTC_ALMACEN_LOGS WHERE (tabla = $tabla or tabla = $tablad) and id = $id";
        $res=$this->EjecutaQuerySimple();
        while($tsArray = ibase_fetch_object($res)){
            $data[]=$tsArray;
        }

        return array("logs"=>count($data),"datos"=>$data);
    }

    function chgFile($file, $name, $ido, $mot){
        $this->query="UPDATE FTC_ALMACEN_ORDEN SET STATUS = 8 where ID_ORD = $ido";
        $res=$this->queryActualiza();
        if($res == 1){
            $this->actStatus(1, 'Orden', 'Reemplazar Archivo', ','.$ido, $mot);
        }
        return;
    }

    function chgComp($idc, $d, $t){
        $sta="no"; $msg=""; $campo = ""; $titulo="";
        if($t == 'd'){
            $campo= 'Desc'; $titulo=" la Descripción";
        }elseif($t == 'o'){
            $campo = 'Obs'; $titulo=" las Observaciones";
        }
        $this->query="UPDATE FTC_ALMACEN_COMP SET $campo = '$d' where id_comp=$idc";
        $r=$this->queryActualiza();
        if($r == 1){
            $sta='ok'; $msg="Se ha cambiado ".$titulo; 
        }
        return array("sta"=>$sta, "msg"=>utf8_encode($msg));
    }

    function comPro($prod, $ordd){
        $data=array(); $dataChg=array();
        //echo '<br/> Producto: '.$prod.' ordd: '.$ordd;
        $this->query="SELECT * FROM FTC_ALMACEN_OC_CHG WHERE BASE = '$prod' and id_ordd = $ordd";
        $res=$this->EjecutaQuerySimple();
        while ($tsArray=ibase_fetch_object($res)) {
            $dataChg[]= $tsArray;
        }
        if(count($dataChg)>0){

        }else{
            //echo 'Viene directo';
        }

        $this->query="SELECT first 2 m.ID_AM, m.SIST_ORIGEN, m.ID_ALMACEN, m.ALMACEN, m.ID_TIPO, m.TIPO, m.ID_USUARIO, m.FECHA, m.ID_STATUS, m.STATUS, m.USUARIO_ATT, m.HORA_I, m.HORA_F, m.CANT, m.ID_PROD, m.ID_UNIDAD, m.UNIDAD, m.PIEZAS, m.MOV, m.ID_COMPP, m.COMPP, m.ID_COMPS, m.COMPS, m.COLOR, m.PRIMARIO, m.SECUNDARIO
        , (select
                coalesce( sum(ms.piezas), 0) from ftc_almacen_mov_sal ms where ms.id_mov = m.id_am
                and status= 'F' 
            ) as salidas, 
            piezas - ((select
                coalesce( sum(ms.piezas), 0) from ftc_almacen_mov_sal ms where ms.id_mov = m.id_am
                and status= 'F' 
            )+(select
                coalesce( sum(ms.piezas), 0) from ftc_almacen_mov_sal ms where ms.id_mov = m.id_am
                and status= 'P' 
            )
            ) as piezas_a
            from ftc_almacen_movimiento m
            where
                m.id_prod = (select p.id_pint from ftc_almacen_prod_int p where p.id_int='$prod')
            and
                m.id_status = 'F'
            and
                m.piezas -
                (
                    (select
                        coalesce( sum(ms.piezas), 0) from FTC_ALMACEN_MOV_sal ms where ms.id_mov = m.id_am
                        and status= 'F'
                    ) +
                    (select
                        coalesce( sum(ms.piezas), 0) from FTC_ALMACEN_MOV_sal ms where ms.id_mov = m.id_am
                        and status= 'P' 
                    )
                )
            > 0 
            order by M.HORA_F asc, ID_AM ASC";
        //echo 'Busca el producto en el almacen: '.$this->query; // solo enuentra las 2 primeras lineas donde hay producto obtiene las posiciones
        //die;
        $res=$this->EjecutaQuerySimple();
        while($tsArray=ibase_fetch_object($res)){
            $data[]=$tsArray;
        }
        $sta=count($data)>0? 'ok':'no';
        $pos=array();
        $this->query="SELECT * FROM FTC_ALMACEN_MOV_SALIDA WHERE ID_ORDD=$ordd and (status= 'P' or status = 'F')"; /// obtiene las posiciones del surtido de productos.
        $res=$this->EjecutaQuerySimple();
        while($tsArray=ibase_fetch_object($res)){
            $pos[]=$tsArray;
        }
        $sta= count($pos)>0? 'ok':'no';
        //$pos= $this->posiciones($ordd);
        return array("status"=>$sta,"datos"=>$data, "posiciones"=>$pos);
    }

    /*function posiciones($ordd){
        $data=array();
        $this->query="SELECT * FROM FTC_ALMACEN_MOV_SALIDA WHERE ID_ORDD=$ordd and (status= 'P' or status = 'F')";
        $res=$this->EjecutaQuerySimple();
        while($tsArray=ibase_fetch_object($res)){
            $data[]=$tsArray;
        }
        return $data;
    }*/

    function posImp($ordd){
        $data=array();
        $this->query="SELECT LINEA, PRODUCTO, SUM(PIEZAS) as piezas, TARIMA , COUNT(*) AS COMPONENTES FROM FTC_ALMACEN_MOV_SALIDA WHERE ID_ORDD=$ordd and (status= 'P' or status = 'F') group by LINEA, TARIMA, PRODUCTO";
        $res=$this->EjecutaQuerySimple();
        while($tsArray=ibase_fetch_object($res)){
            $data[]=$tsArray;
        }
        return $data;
    }

    function surte($surte, $ordd, $comps){
        $usuario=$_SESSION['user']->ID;
        $row = array(); $disp=0;$sta='no'; $msg="No hay producto Disponible, intente otro componente";
        // surte es el numero de movimiento de entrada en la tabla de Movimientos. 
        // ordd es la linea del detalle de la Orden de compra.
        // comps es la referencia al componente de donde se va a sacar el productos. 
        /// Obtenemos las piezas necesarias:
        $this->query ="SELECT * FROM FTC_ALMACEN_ORDEN_DET where id_ordd = $ordd";
        $res=$this->EjecutaQuerySimple();
        @$row=ibase_fetch_object($res);
            $srt= $row->PZAS_SUR;
            $pen=$row->ASIG - $row->PZAS_SUR;
        /// Revisamos las existencias actuales antes de la afectacion:
        $this->query="SELECT *  FROM FTC_ALMACEN_MOV_DET WHERE ID_AM=$surte and disponible > 0 ";
        $res=$this->EjecutaQuerySimple();
        $row2=ibase_fetch_object($res);
        if(@count($row)>0){
            $disp=$row2->DISPONIBLE;
        }
        /// Validamos la cantidad y la sobrante la surtimos. 
        //echo 'Pendiente: '.$pen.' Disponible: '.$disp; 
        
        if($pen > 0 and $disp > 0){/// Si hacen falta se asignan las pendientes
            if($disp >= $pen){/// si la existencia disponible es igual o mayor a la necesaria, se aplica todo y se crea un movimiento de salida por el pendiente.
                $surt= $pen;
            }elseif($disp < $pen){
                $surt= $disp;
            }   

            $this->query="INSERT INTO FTC_ALMACEN_MOV_SAL (ID_MS, ID_COMPS, CANT, ID_ORDD, USUARIO, FECHA, STATUS, ID_MOV, PIEZAS, UNIDAD, ID_COMPP, id_prod) VALUES (NULL, $comps, 0, $ordd, $usuario, current_timestamp, 'P', $surte, $surt, 1, (SELECT COMPP FROM FTC_ALMACEN_COMP WHERE ID_COMP = $comps), (select m.prod from ftc_almacen_mov m where m.id_am = $surte )) returning ID_MS";
            $rs=$this->grabaBD();
            if($rs > 0){
                $this->query="UPDATE FTC_ALMACEN_ORDEN_DET SET PZAS_SUR = (PZAS_SUR + $surt) where id_ordd = $ordd";
                $rs=$this->queryActualiza();
                if($rs == 1){
                    $sta= 'ok';$msg="Se ha surtido el producto";
                }
            }
        }
        return array("status"=>$sta, "msg"=>$msg, "pzas"=>$surt, "srt"=>($srt+$surt), "pnd"=>$pen-$surt);
        /// No importa si faltan o quedan pendientes, solo importa cuanta cantidad se surtio para poder restar de la asignada. 
    }

    function reasig($idcomp, $compp, $comps, $t){
        $data=array(); $data2=array();$sta='ok';$msg="Se ha reubicado el componente correctamente";
        if($t==1){
            $this->reasigTarima($idcomp, $comps, 'Componente ', 'Tarima');
        }else{
            //echo 'idcomp original '.$idcomp.' componente destino '.$compp.' componente secundario '.$comps.' t = '.$t;
            //die();
            $this->query="SELECT * FROM FTC_ALMACEN_COMPP WHERE ID_COMP = $idcomp ";
            $res=$this->EjecutaQuerySimple();
            $rowO=ibase_fetch_object($res);
            $need=$rowO->COMPS - $rowO->COMPS_DISP; 
            $this->query="SELECT * FROM FTC_ALMACEN_COMPP WHERE ID_COMP = $compp";
            $res=$this->EjecutaQuerySimple();
            $rowD=ibase_fetch_object($res);
            $disp=$rowD->COMPS_DISP;
            
            if($disp >= $need){
                $this->query="SELECT * FROM FTC_ALMACEN_COMP WHERE COMPP = $idcomp and status = 1 ";
                $res=$this->EjecutaQuerySimple();
                while ($tsArray=ibase_fetch_object($res)) {
                    $data2[]=$tsArray;
                }
                foreach($data2 as $o){
                    $this->query="SELECT first 1 * FROM FTC_ALMACEN_COMPS WHERE COMPP = $rowD->ID_COMP and disp='si'";
                    $r=$this->EjecutaQuerySimple();
                    $row3=ibase_fetch_object($r);
                    $this->reasigTarima($o->ID_COMP, $row3->ID_COMP,'Componente ', 'Linea');
                }
            }else{
                $sta='No';$msg="No se pude por que el Disponible es menor al requerido, seleccione otro componente";
            }
        }
        return array("status"=>$sta, "msg"=>$msg, "idc"=>$idcomp);
    }

    function reasigTarima($idcompO, $idcompD, $t, $c){
        /// trar el ultimo movimiento de la tarima valida. 
        $data = array();
        $this->query="SELECT * FROM FTC_ALMACEN_MOV_EXI WHERE TARIMA = $idcompO and disp = 'si'";
        $res=$this->EjecutaQuerySimple();
        while($tsArray=ibase_fetch_object($res)){
            $data[]=$tsArray;
        }
        if(count($data)> 0){
            foreach($data as $mov){
                $this->query="UPDATE FTC_ALMACEN_MOV SET COMPS = $idcompD, COMPP = (SELECT COMPP FROM FTC_ALMACEN_COMP WHERE ID_COMP= $idcompD) where id_am = $mov->ID_AM";
                $this->queryActualiza();
                $this->query="UPDATE FTC_ALMACEN_MOV_SAL SET ID_COMPS = $idcompD, ID_COMPP = (SELECT COMPP FROM FTC_ALMACEN_COMP WHERE ID_COMP= $idcompD) where id_mov = $mov->ID_AM ";
                $this->queryActualiza();
                $this->actStatus($tabla=3, $tipo='Reacomodo', $sub=$c, $ids=$mov->ID_AM, $obs=$t.' Origen: '.$idcompO.' Destino: '.$idcompD);
            }
        }
        return;
    }

    function mapa($opc, $param){
        $data=array();$sec=array();$row=array();
        //$this->query="SELECT * from ftc_almacen_compp $opc";
        //$this->query="SELECT c.*, iif(char_length(c.etiqueta) = 5, substring(c.etiqueta from 1 for 1), '' ) as letra,  char_length(c.etiqueta) from ftc_almacen_comp c where c.almacen = 1 and c.status < 8 and c.tipo = 2";
        $this->query="SELECT c.*,
                        case char_length(c.etiqueta)
                            --when 2 then substring(c.etiqueta from 1 for 1)
                            when 3 then substring(c.etiqueta from 1 for 1)
                            when 4 then substring(c.etiqueta from 1 for 1)
                            when 5 then substring(c.etiqueta from 1 for 1)
                            when 6 then substring(c.etiqueta from 1 for 1)
                            when 7 then substring(c.etiqueta from 1 for 2)
                            when 8 then substring(c.etiqueta from 1 for 2)
                            when 9 then substring(c.etiqueta from 1 for 2)
                            when 10 then substring(c.etiqueta from 1 for 2)
                            else ''
                            end as letra,
                        char_length(c.etiqueta)
                        from
                        ftc_almacen_comp c where c.almacen = $param and c.status < 8 and c.tipo = 2 order by id_comp";
        //echo $this->query;
        $res=$this->EjecutaQuerySimple();
        while($tsArray=ibase_fetch_object($res)){
            $data[]=$tsArray;
        }

        $this->query="SELECT * FROM FTC_ALMACEN_COMPS $opc";
        $res=$this->EjecutaQuerySimple();
        while($tsArray=ibase_fetch_object($res)){
            $sec[]=$tsArray;
        }
        $this->query="SELECT MAX(COMPS) as tarimas FROM FTC_ALMACEN_COMPP $opc";
        $res=$this->EjecutaQuerySimple();
        $row = ibase_fetch_object($res);
        return array("datos"=>$data, "tarimas"=>$row->TARIMAS, "sec"=>$sec);
    }

    function ingMap($comps, $prod, $uni, $cant, $pzas, $ft , $t, $cat){
        $mov='nuevo';
        $folio=$this->folioMov($mov);
        if($t=='l'){
            $cxt=$cant;
            $this->query="SELECT * FROM FTC_ALMACEN_COMPS WHERE DISP='si' and COMPP=$comps";
            $res=$this->EjecutaQuerySimple();
            while($tsArray=ibase_fetch_object($res)){
                $data[]=$tsArray;
            }
            $i=0;
            foreach($data as $d){
                $i++;
                $cxt=$cxt - $ft;
                if($cxt < 0){
                    return $res;
                }
                if($cxt > $ft or $cxt == 0){
                    $cant=$ft;
                }else{
                    $cant=$cxt;
                }
                $res=$this->ingMapTar($d->ID_COMP, $prod, $uni, $cant, $pzas, $folio, $cat);
            }
        }elseif($t=='t'){
            $res=$this->ingMapTar($comps, $prod, $uni, $cant, $pzas, $folio, $cat);
        }
        $this->query="DELETE FROM FTC_ALMACEN_MOV WHERE MOV = $folio[0] and prod is null ";
        $this->grabaBD();
        return $res;
    }

    function ingMapTar($comps, $prod, $uni, $cant, $pzas, $folio, $cat){
        $val = $this->dispComps($comps);
        if($val == 'no'){
            return array("status"=>'no', "msg"=>'No esta dispobible la tarima, favor de actualizar');            
        }    
        $usuario=$_SESSION['user']->ID;$sist='php';$prod = explode(":",$prod );$sta='ok';
        $prod = $prod[0]; $id=$folio[1]; $mov=$folio[0];
        $this->query="INSERT INTO FTC_ALMACEN_MOV (ID_AM, SIST_ORIGEN, ALMACEN, TIPO, USUARIO, FECHA, STATUS, USUARIO_ATT, HORA_I, HORA_F, CANT, PROD, UNIDAD, PIEZAS, MOV, COMPP, COMPS, COLOR, CATEGORIA) VALUES (
            null, 
            '$sist', 
            (SELECT ALMACEN FROM FTC_ALMACEN_COMP WHERE ID_COMP = $comps), 
            'E', 
            $usuario, 
            current_timestamp, 
            'F', 
            0,
            current_timestamp,
            current_timestamp, 
            $cant,
            (SELECT ID_PINT FROM FTC_ALMACEN_PROD_INT where ID_INT ='$prod'), 
            $uni, 
            $cant * $uni,
            $mov, 
            (SELECT COMPP FROM FTC_ALMACEN_COMP WHERE ID_COMP = $comps), 
            $comps, 
            '',
            $cat) returning id_am";
            $res=$this->grabaBD();
            @$mov= ibase_fetch_object($res)->ID_AM;
            if(@$mov > 0){$sta=='ok';}
            $msg='Se ha inserado el movimiento';
        return array('status'=>$sta, 'msg'=>$msg, "mov"=>$mov);
    }

    function dispComps($idc){
        $this->query="SELECT * FROM FTC_ALMACEN_COMPS WHERE ID_comp = $idc";
        $res=$this->EjecutaQuerySimple();
        $row=ibase_fetch_object($res);
        return $row->DISP;
    }

    function dispLin($idc){
        $this->query="SELECT COMPS_DISP FROM FTC_ALMACEN_COMPP WHERE ID_COMP = $idc";
        $res=$this->EjecutaQuerySimple();
        $row=ibase_fetch_object($res);
        return array("status"=>'ok', "disp"=>$row->COMPS_DISP);
    }

    function prods($idc){
        $data=array();
        $this->query="SELECT * FROM FTC_ALMACEN_MOV_DET WHERE disponible > 0 and id_comps = $idc";
        $res=$this->EjecutaQuerySimple();
        while($tsArray=ibase_fetch_object($res)){
            $data[]=$tsArray;
        }
        return array("datos"=>$data);
    }

    function reuMap($idc, $opc){
        $data= array();
        $this->query="SELECT * FROM FTC_ALMACEN_COMP where status = 7";
        $res=$this->EjecutaQuerySimple();
        while($tsArray=ibase_fetch_object($res)){
            $data[]=$tsArray;
        }
        if(count($data) >0){
            if($opc == 5){
                foreach($data as $d){}
                    $this->query="SELECT * FROM FTC_ALMACEN_COMP WHERE ID_COMP= $idc";
                    $res=$this->EjecutaQuerySimple();
                    while($tsArray=ibase_fetch_object($res)){
                        $comp[]=$tsArray;
                    }
                    foreach($comp as $cmp){}
                    if($d->TIPO == 1 and $cmp->TIPO == 1){
                        $this->reasigTarima($idcompO=$d->ID_COMP, $idcompD=$idc, $t='Reubicacion', $c='Tarima');
                    }elseif($d->TIPO == 2 and $cmp->TIPO == 2){
                        $this->reasig($idcomp=$d->ID_COMP, $compp=$idc, $comps=0, $t='l');   
                    }else{
                        return array("status"=>'ok',"msg"=>'Solo se permite la reubicación del mismo tipo');
                    }
                $this->query="UPDATE FTC_ALMACEN_COMP SET STATUS = 1 WHERE ID_COMP = $d->ID_COMP";
                $this->EjecutaQuerySimple();    
                return array("status"=>'ok', "msg"=>'Se ha cambiado correctamente el componente, actualice con F5 para ver el resultado');
            }elseif($opc == 9){
                foreach($data as $d){}
                $this->query="UPDATE FTC_ALMACEN_COMP SET STATUS= 1 WHERE ID_COMP= $d->ID_COMP";
                $this->queryActualiza();
                return array("status"=>'c', "msg"=>'Se ha cancelado la reubicacion', "idc"=>$d->ID_COMP, "tipo"=>$d->TIPO);
            }
            return array("status"=>'no', "msg"=>'Existe un componente pendiente de copiar', "comp"=>$data);
        }else{
            if($opc == 0){ 
                $this->query="UPDATE FTC_ALMACEN_COMP SET STATUS = 7 WHERE ID_COMP = $idc";
                $res=$this->queryActualiza();
                if($res == 1){
                    return array("status"=>'ok', "msg"=>'Se selecciono el origen, ahora debe seleccionar el destino.');
                }
            }else{
                return array("status"=>'ok', "msg"=>"Seleccione primero el componente origen");
            }
        }
    }

    function usoComp($idc, $opc){
        $this->query="SELECT * FROM FTC_ALMACEN_COMP WHERE ID_COMP = $idc";
        $res=$this->EjecutaQuerySimple();
        $row=ibase_fetch_object($res);
        if($row->TIPO == 1){
            $tabla = 'FTC_ALMACEN_COMPS';
        }elseif($row->TIPO == 2){
            $tabla = 'FTC_ALMACEN_COMPP';
        }
        $this->query="SELECT * FROM $tabla WHERE ID_COMP =$idc";
        $res=$this->EjecutaQuerySimple();
        $row2=ibase_fetch_object($res);
        if($row2->DISP=='si'){
            switch ($opc) {
                case 'D':
                    $sta = 4;
                    break;
                case 'R':
                    $sta = 5;
                    break;
                case 'E':
                    $sta = 6;
                    break;
                case 'I':
                    $sta = 7;
                    break;
                case 'P':
                    $sta = 1;
                    break;                    
                default:
                    $sta =0;
                    break;
            }
            $this->query="UPDATE FTC_ALMACEN_COMP SET STATUS = $sta WHERE ID_COMP = $idc or COMPP=$idc";
            $this->queryActualiza();
            return array("status"=>'ok', "msg"=>'Se ha actualizado el componente');
        }else{
            return array("status"=>'ok', "msg"=>'El componente no esta disponible, favor de actualizar la pantalla con F5');
        }
    }

    function asiSurt($ido, $cedis, $nombre){
        $usuario=$_SESSION['user']->ID;
        $this->query="INSERT INTO FTC_ALMACEN_ASI_SURT (id_surt, nombre, fecha, usuario, id_ord, cedis, cliente) VALUES (null, '$nombre', current_timestamp, $usuario, $ido, '$cedis', (SELECT CLIENTE FROM FTC_ALMACEN_ORDEN WHERE ID_ORD =$ido ))";
        $this->grabaBD();
        return array("status"=>'ok', "msg"=>'Se ha insertado correctamente');
    }

    function perSurt($ido, $t, $cedis){
        $data=array();
        $this->query="SELECT first 1 NOMBRE FROM FTC_ALMACEN_ASI_SURT WHERE ID_ORD = $ido and cedis ='$cedis' order by fecha desc";
        $res=$this->EjecutaQuerySimple();
        while($tsArray=ibase_fetch_object($res)){
            $data[]=$tsArray;
        }
        return $data;
    }

    function histPerSurt($ido, $t, $cedis){
        $data=array();
        $this->query="SELECT * FROM FTC_ALMACEN_ASI_SURT WHERE ID_ORD = $ido and cedis ='$cedis' order by fecha desc";
        $res=$this->EjecutaQuerySimple();
        while($tsArray=ibase_fetch_object($res)){
            $data[]=$tsArray;
        }
        return $data;   
    }

    function finSurt($ord, $cedis){
        $this->query="UPDATE FTC_ALMACEN_ORDEN_DET set status = 7 WHERE ID_ORD = $ord and cedis = '$cedis'";
        $this->queryActualiza();
        $this->query="UPDATE FTC_ALMACEN_ORDEN_DET set status = 7 where ID_ORD = $ord and (select o.cliente from FTC_ALMACEN_ORDEN o where o.id_ord = $ord) ='Salida Diversa'";
        $this->queryActualiza();
        $this->finSurtOrd($ord);
        $registra = $this->actStatus( 1, 'Fin', 'Surtido', $ids=','.$ord, 'Finaliza Surtido'); ///actStatus($tabla, $tipo, $sub, $ids, $obs)
        return array("msg"=>'Se ha Finalizado la orden del cedis '.$cedis);
    }

    function finSurtOrd($ord){
        $data=array();
        $this->query="SELECT STATUS, id_ordd FROM FTC_ALMACEN_ORDEN_DET WHERE ID_ORD = $ord";
        $res=$this->EjecutaQuerySimple();
        while($tsArray=ibase_fetch_object($res)){
            $data[]=$tsArray;
        }
        $ordenes = count($data);
        $fin=0;
        foreach($data as $d){
            if($d->STATUS == 7){
                $fin++;
            }
            $this->query="UPDATE FTC_ALMACEN_MOV_SAL SET STATUS = 'F' WHERE ID_ORDD = $d->ID_ORDD and status = 'P'";
            $this->queryActualiza();
        }
        if($fin == $ordenes){
            $this->query="UPDATE FTC_ALMACEN_ORDEN SET STATUS = 5, fecha_almacen_F = current_timestamp WHERE ID_ORD = $ord";
            $this->queryActualiza();
            $this->actStatus($tabla=1, $tipo='Surtido', "Orden", ','.$ord, $p='Final de surtido de la Orden');
        }
        return;
    }

    function facOrdd($ordd, $uni, $t){
        if($t=='u'){
            $this->query="UPDATE FTC_ALMACEN_ORDEN_DET SET UNIDAD = $uni, CAJAS=(ASIG / $uni) WHERE ID_ORDD = $ordd";
        }elseif($t=='e'){
            $this->query="UPDATE FTC_ALMACEN_ORDEN_DET SET ETIQUETA = '$uni' WHERE ID_ORDD = $ordd";
        }
        $this->queryActualiza();
        return array("sta"=>'ok');
    }

    function correos2($opc, $datos){
        $data=array();
        if($opc=='A'){
            $this->query="SELECT * FROM FTC_ALMACEN_EMAIL WHERE STATUS=1";
        }elseif($opc=='O'){
            $this->query="SELECT * FROM FTC_ALMACEN_EMAIL WHERE STATUS=1 and TIPO ='O'";
        }
        $rs=$this->EjecutaQuerySimple();
        while($tsArray=ibase_fetch_object($rs)){
            $data[]=$tsArray;
        }
        return $data;
    }

    function actCorreo($datos){
        //print_r($datos);
        for ($i=0; $i < count($datos); $i++) { 
            //echo '<br/>'.$i; 
            if($i%2==0 or $i ==0){
                if($i==0 and $datos[$i]=='add'){
                    $usuario=$_SESSION['user']->ID;
                    $this->query="INSERT INTO FTC_ALMACEN_EMAIL (ID_EMAIL, correo, nombre, TIPO, status, usuario, ALTA) VALUES (NULL,'$datos[2]', '$datos[1]', 'O', 1, $usuario, current_timestamp)";
                    $this->grabaBD();
                    return array("sta"=>'ok', "msg"=>'Se ha dado de alta el correo '.$datos[2]);
                    break;
                }else{   
                $j=$i+1; $id=$datos[$i]; $sta = $datos[$j];
                    if($sta!='Z' and $sta !=''){
                        $this->query="UPDATE FTC_ALMACEN_EMAIL SET TIPO='$sta' WHERE ID_EMAIL= $id";
                        $this->queryActualiza();
                    }
                }
            }
        }
        die();
    }

    function inExistInt($datos){
        $usuario =$_SESSION['user']->ID;
        //Array ( [0] => 2 IN 1-SP [Articulo] => 2 IN 1-SP [1] => iAL ECM:1, Existencas: 20.00, Reservado: 0.00, Remisionado:0.00; [disponible] => AL ECM:1, Existencias: 20.00, Reservado: 0.00, Remisionado:0.00; ) 
        foreach($datos as $int){
            //print_r($int);
            $ex = explode(",",$int[1]);
            $almacen=explode(":",$ex[0]);
            $almacen = $almacen[0];
            $existencias =explode(":",$ex[1]);
            $existencias = $existencias[1];
            $reservado =explode(":",$ex[2]);
            $reservado = $reservado[1];
            $remisionado =explode(":",$ex[3]);
            $remisionado = $remisionado[1];
            $this->query="INSERT INTO FTC_ALMACEN_EXI_INT (ID, ID_PINT, ID_INT, ALMACEN, DISPONIBLE, RESERVADO, REMISIONADO, EXISTENCIA, FECHA, USUARIO, STATUS) VALUES (NULL, '$int[0]', (SELECT ID_PINT FROM FTC_ALMACEN_PROD_INT WHERE upper(ID_INT) = upper('$int[0]')), '$almacen', $existencias, $reservado, $remisionado, ($existencias + $reservado), current_timestamp, $usuario, 1 )";
            $this->grabaBD();
        }
    }

    function posiciones($prod, $t){
        $data=array();
        if($t=='a'){
            $this->query="  SELECT LINEA,TARIMA,SUM(DISPONIBLE) AS DISPONIBLE ,ID_COMPS,CATEGORIA, '' as ID_AM, ALMACEN,SUM(PIEZAS) AS PIEZAS,SUM(PIEZAS_SAL) as PIEZAS_SAL,SUM(PIEZAS_SURT) AS PIEZAS_SURT FROM FTC_ALMACEN_MOV_DET WHERE id_PROD = $prod group by Almacen, linea, tarima, id_comps, categoria order by linea asc, tarima asc ";
        }else{
            $this->query="SELECT * FROM FTC_ALMACEN_MOV_DET WHERE id_PROD = $prod order by fecha_ingreso asc, id_am asc ";
        }
        $res=$this->EjecutaQuerySimple();
        while($tsArray=ibase_fetch_object($res)){
            $data[]=$tsArray;
        }
        return array("status"=>'ok', "datos"=>$data);
    }

    function liberar($movs){ /// Hacer pruebas de la liberacion 
        $movs = substr($movs, 3);
        $this->query="UPDATE FTC_ALMACEN_ORDEN_DET SET PZAS_SUR = PZAS_SUR - (SELECT PIEZAS FROM FTC_ALMACEN_MOV_SAL WHERE ID_MS=$movs and (status ='P' or STATUS ='F')) WHERE ID_ORDD = (SELECT ID_ORDD FROM FTC_ALMACEN_MOV_SAL WHERE ID_MS=$movs and (STATUS ='P' OR STATUS='F'))";
        $res=$this->queryActualiza();

        $this->query="UPDATE FTC_ALMACEN_MOV_SAL SET STATUS='C' WHERE ID_MS=$movs and (STATUS ='P' OR STATUS='F')";
        $res=$this->queryActualiza();

        $this->query="UPDATE FTC_ALMACEN_OC_CHG SET SURTIDAS = 0  WHERE ID_ORDD = (SELECT ID_ORDD FROM FTC_ALMACEN_MOV_SAL WHERE ID_MS=$movs and STATUS ='C') ";
        $this->queryActualiza();

        if($res == 1){
            $this->actStatus($tabla=6, $tipo='Salida', $sub='Cancelacion', $ids=','.$movs, $obs='Cancelacion de Surtido '.$movs);
        }

        return array("sta"=>'ok');
    }

    function movsProd($prod){
        $entradas = array();$salidas=array();
        $this->query="SELECT * from ftc_almacen_mov_det where intelisis = '$prod' and status !='C'";
        $res=$this->EjecutaQuerySimple();
        while($tsArray=ibase_fetch_object($res)){
            $entradas[]=$tsArray;
        }
        $this->query="SELECT F.*, (SELECT DESC FROM FTC_ALMACEN_PROD_INT P WHERE P.ID_INT = F.PROD ) as descr FROM ftc_almacen_mov_salida F where F.producto = '$prod' and F.status !='C'";
        $res=$this->EjecutaQuerySimple();
        while($tsArray=ibase_fetch_object($res)){
            $salidas[]=$tsArray;
        }
        return array("entradas"=>$entradas, "salidas"=>$salidas);
    }

    function delMovs($alm, $tipo){
        // Hacer respaldo de la Base de datos.
        $usuario=$_SESSION['user']->ID;
        $this->query="INSERT INTO FTC_ALMACEN_LOG (ID_LOG, USUARIO, TIPO,SUBTIPO, TABLA, FECHA, STATUS, ID, OBS ) VALUES (NULL, $usuario,'Eliminar', '$tipo', 8, current_timestamp, 9, 0, 'Eliminacion de $tipo del Almacen $alm' )";
        $this->grabaBD();
        //$eje=date("y");
        //$mes=date("m");
        //$dia=date("d");
        //$ruta="C:\\Program Files (x86)\\Common Files\\Aspel\\Sistemas Aspel\\SAE7.00\\Empresa01\\Datos\\";
        //$bd="SAE70EMPRE01.FDB";
        //$rutaBU="E:\\OneDrive\\Respaldos Aspel\\WMS\\";//E:\Dropbox (Biotecsa SA de CV)\RespaldosCOI
        //$respaldo="Borrado_Almacen_".$alm.'_'.$dia.$mes.$eje.".FDB";
        //(file_exists($rutaBU))? '':mkdir($rutaBU, 0, true);
        //if(file_exists($rutaBU.$respaldo)){ 
        //}else{
        //    copy($ruta.$bd, $rutaBU.$respaldo);
        //    $zip = new ZipArchive();
        //    $zip->open($rutaBU.'/'.$respaldo.'.zip', ZipArchive::CREATE);
        //    $zip->addFile($rutaBU.$respaldo, 'Respaldo de Movimientos Almacen '.$alm.'_'.$dia.$mes.$eje);
        //    $zip->close();
        //    unlink($rutaBU.$respaldo);
        //}

        //if(file_exists($rutaBU.'/'.$respaldo.'.zip')){
            //die('Existe el respaldo, se puede continuar con el eliminado');
            if($tipo=='movs'){
                $this->query="DELETE FROM FTC_ALMACEN_MOV_SAL MS WHERE (SELECT ALMACEN FROM FTC_ALMACEN_MOV M WHERE M.ID_AM = MS.ID_MOV) = $alm";
                $rs=$this->grabaBD();
                $this->query="DELETE FROM FTC_ALMACEN_MOV WHERE ID_AM > 0 AND ALMACEN = $alm";
                $res=$this->grabaBD();     
            }elseif($tipo=='ordenes'){
                $this->query="DELETE FROM FTC_ALMACEN_ORDEN WHERE ID_ORD >=0 ";
                $this->grabaBD();
                $this->query="DELETE FROM FTC_ALMACEN_ORDEN_DET WHERE ID_ORD >= 0";
                $this->grabaBD();
                $this->query="DELETE FROM FTC_ALMACEN_OC_CHG WHERE ID_CHG >= 0";
                $this->grabaBD();
            }elseif($tipo=='comp'){
                $this->query="DELETE FROM FTC_ALMACEN_MOV_SAL MS WHERE (SELECT COMPP FROM FTC_ALMACEN_MOV M WHERE M.ID_AM = MS.ID_MOV) = $alm OR (SELECT COMPS FROM FTC_ALMACEN_MOV M WHERE M.ID_AM = MS.ID_MOV) = $alm";
                $rs=$this->grabaBD();
                $this->query="DELETE FROM FTC_ALMACEN_MOV WHERE COMPP = $alm OR COMPS = $alm";
                $this->grabaBD();
            }
        //}else{
        //    die('No Existe el respaldo, No se puede continuar con el eliminado');
        //}
        die;
    }

    function pxc($ordd){
        $this->query="SELECT first 1 coalesce(unidad, 0) as unidad FROM FTC_ALMACEN_ORDEN_DET WHERE 
            PROD = (SELECT PROD FROM FTC_ALMACEN_ORDEN_DET P WHERE P.ID_ORDD = $ordd) 
            AND 
            CEDIS = (SELECT CEDIS FROM FTC_ALMACEN_ORDEN_DET C WHERE C.ID_ORDD = $ordd)
            AND
            UPC = (SELECT UPC FROM FTC_ALMACEN_ORDEN_DET U WHERE U.ID_ORDD = $ordd)
            AND 
            UNIDAD IS NOT NULL
            ORDER BY ID_ORDD DESC ";
        $res=$this->EjecutaQuerySimple();
        while($tsArray = ibase_fetch_object($res)){
            $data[]=$tsArray;
        }
        foreach($data as $val ){
            $valor = $val->UNIDAD;
        }
        $this->query="UPDATE FTC_ALMACEN_ORDEN_DET SET UNIDAD = $valor, CAJAS=(ASIG / $valor) WHERE ID_ORDD = $ordd";
        //$this->queryActualiza();
        return array("sta"=>'ok', "valor"=>$valor);
    }

    function desc($id){
        $this->query="UPDATE FTC_ALMACEN_PROD_INT SET STATUS = iif(STATUS = 'Alta', 'Descontinuado', 'Alta') where id_pint = $id ";
        $res=$this->queryActualiza();

        return array("status"=>'ok');
    }

    function pres($info){
        $data=array(); $inf = explode("|", $info); $prod = $inf[0]; $ord = $inf[1]; $origen = 'chg';
        $this->query="SELECT c.*, (select id_pint from FTC_ALMACEN_PROD_INT WHERE ID_INT = c.nuevo) as idProd FROM FTC_ALMACEN_OC_CHG c WHERE c.BASE ='$prod' and c.id_ord = $ord";
        //echo $this->query;
        $res=$this->EjecutaQuerySimple();
        while($tsArray=ibase_fetch_object($res)){
            $data[]=$tsArray;
        }
        if(empty($data)){
            $origen = 'Orden';
            $this->query="SELECT prod as nuevo, asig as cant FROM FTC_ALMACEN_ORDEN_DET WHERE ID_ORD = $ord and prod =TRIM('$prod')";
            $res=$this->EjecutaQuerySimple();
            while($tsArray=ibase_fetch_object($res)){
                $data[]=$tsArray;
            }    
        }
        return array("inf"=>'ok', "origen"=>$origen, "datos"=>$data);
    }

    function sustitutos($ordd){
        $data= array();
        $this->query = "SELECT * FROM FTC_ALMACEN_OC_CHG WHERE id_ordd = $ordd and trim(BASE) != TRIM(NUEVO) and cant > 0 ";
        $res=$this->EjecutaQuerySimple();
        while($tsArray=ibase_fetch_object($res)){
            $data[]=$tsArray;
        }
        return $data;
    }

    function reubPza($datos){
        //print_r($datos);
        $usuario=$_SESSION['user']->ID;
        $almacenes= explode("|",substr($datos[0],1));
        $lineas= explode("|",substr($datos[1],1));
        $tarimas= explode("|",substr($datos[2],1));
        $categorias= explode("|",substr($datos[3],1));
        $piezas= explode("|",substr($datos[4],1));
        $comps = explode("|",substr($datos[5],1));
        $movs = explode("|",substr($datos[6],1));
        $prod = $datos[7];
        for ($i=0; $i < count($almacenes) ; $i++){
            $destino=array();
            $origen=$comps[$i];
            $almacen=$almacenes[$i]; $linea=$lineas[$i];  $tarima=$tarimas[$i]; $cant=$piezas[$i]; $categoria=$categorias[$i];$idmov=$movs[$i];
            //echo '<br/> Busca registro'.$i.' Tarima : '.$tarima.' Catidad: '.$cant.' Categoria '.$categoria;
            if(!empty($almacen) and !empty($linea) and !empty($tarima) and !empty($categoria)){   
                $this->query = "SELECT * FROM FTC_ALMACEN_COMPS WHERE COMPP =(SELECT ID_COMP FROM FTC_ALMACEN_COMP c WHERE ALMACEN = $almacen and ETIQUETA = 'LINEA $linea' and status = 1 and compp is null) and replace(ETI, ' ', '') = 'T$tarima' ";
                $res=$this->EjecutaQuerySimple();
                while($tsArray=ibase_fetch_object($res)){
                    $destino[]=$tsArray;
                }
                #### aqui debemos meter de donde se va a sacar y la cantidad a sacar...


                #### Despues ya afectamos.

                if(count($destino) == 1){
                    $this->query="SELECT DISPONIBLE FROM FTC_ALMACEN_MOV_DET WHERE ID_AM = $idmov";
                    $result=$this->EjecutaQuerySimple();
                    $disp = ibase_fetch_object($result)->DISPONIBLE;
                    if(($disp-$cant) >= 0){
                        foreach ($destino as $k) {
                            $idcomps = $k->ID_COMP;  $idcompp=$k->COMPP;
                            $this->query="INSERT INTO FTC_ALMACEN_MOV_SAL (ID_MS, ID_COMPS, CANT, ID_ORDD, USUARIO, FECHA, STATUS, ID_MOV, PIEZAS, UNIDAD, ID_COMPP, SERIE, FOLIO, ID_PROD, CATEGORIA) VALUES (null, (SELECT COMPS FROM FTC_ALMACEN_MOV WHERE ID_AM = $idmov), 0, null, $usuario, current_timestamp, 'F', $idmov, $cant, 1, (SELECT COMPP FROM FTC_ALMACEN_MOV WHERE ID_AM = $idmov), 'R', (SELECT coalesce(MAX(FOLIO), 0) + 1 FROM FTC_ALMACEN_MOV_SAL WHERE SERIE = 'R'), $prod, (SELECT CATEGORIA FROM FTC_ALMACEN_MOV WHERE ID_AM = $idmov) ) RETURNING FOLIO";
                            $res=$this->grabaBD();
                            $folio = ibase_fetch_object($res)->FOLIO;
                            $this->query="INSERT INTO FTC_ALMACEN_MOV (ID_AM, SIST_ORIGEN, ALMACEN, TIPO, USUARIO, FECHA, STATUS, USUARIO_ATT, HORA_I, HORA_F, CANT, PROD, UNIDAD, PIEZAS, MOV, COMPP, COMPS, COLOR, CATEGORIA) VALUES (null, 'Reubicar', $almacen, 'e', $usuario, (select fecha from FTC_ALMACEN_MOV where id_am = $idmov), 'F', null, current_timestamp, current_timestamp, $cant, $prod, 4, $cant, (SELECT COALESCE(MAX(MOV),0 ) + 1 FROM FTC_ALMACEN_MOV), $idcompp, $idcomps, 'R-$folio', $categoria)";
                            $this->grabaBD();
                        }
                    }else{
                        echo '<br/>La cantidad a reubicar es mayor que la dispobible, favor de revisar';
                    }
                }else{
                    echo '<br/>No se encontro el componente destino o se encontro mas de uno con los criterios de busqueda, favor de revisar la informacion';
                }
            }else{
                echo '<br/>NO se eligio el destino correcto.';
            }
            unset($destino);
        }
    }

    function sincOrd($info, $t){ /// en esta funcion solo revisa si hay anomalias en la diferencia de pedidos.
        $errores=0;
        foreach ($info['cabecera'] as $c){ /// Con esta informacion quita todas las CONCLUIDAD, CANCELADAS, CONCLUIDAS FACTURADAS Y CONCLUIDAD VENTA PERDIDA.
            $status = $c['Estatus']; $id=$c['ID'];
            $this->query = "UPDATE FTC_ALMACEN_ORDEN SET STA_INT = '$status' where id_ord = $id";
            $this->EjecutaQuerySimple();
        }
        $infoPart=array();
        if($t == 'p'){
            foreach ($info['partidas'] as $p) {
                $partida = $p['Renglon'];
                $cantidad = $p['CantidadInventario'];
                $prod = $p['Articulo'];
                $asig = $p['CantidadReservada'] * $p['Factor'];
                $this->query="SELECT * FROM FTC_ALMACEN_ORDEN_DET WHERE ID_ORD = $id and PARTIDA = $partida and prod = '$prod' and pzas = $cantidad";
                $res=$this->EjecutaQuerySimple();
                while ($tsArray=ibase_fetch_object($res)) {
                    @$infoPart[]=$tsArray;
                }
                if(count($infoPart)!=1){
                    $this->query="INSERT INTO FTC_ALMACEN_SINC_ORD (ID_SINC, ID_ORD, PARTIDA, PROD, PZAS) VALUES (NULL, $id, $partida, '$prod', $cantidad) returning ID_SINC ";
                    $res=$this->grabaBD();
                    $errores++;
                }else{    
                    foreach ($infoPart as $par) {
                        $ln = $par->ID_ORDD;
                    }
                    $this->regInt($p, $ln); 
                    $this->asgLn($ln, $asig, 'I');
                }
                unset($infoPart);
            }
        }
        return array("errores"=>$errores);
    }

    function ordenesAll($param){
        $data = array();
        if($param != 'all'){
            $param = explode(":", substr($param,1));
            $fi = $param[0];
            $ff = $param[1];
            $i = !empty($fi)? " and dia_carga >= '".$fi."'":'';
            $f = !empty($ff)? " and dia_carga <= '".$ff."'":'';
            $sub = $param[4];
            if($sub == 'c'){
                $fai = $param[0];
                $faf = $param[1];
                $i = !empty($fi)? " and fecha_almacen_f >= '".$fi."'":'';
                $f = !empty($ff)? " and fecha_almacen_f <= '".$ff."'":'';
                $this->query="SELECT * from FTC_ALMACEN_ORDENES WHERE ID_ORD > 0 $i $f";
            }else{
                $this->query="SELECT * from FTC_ALMACEN_ORDENES WHERE ID_ORD > 0 $i $f";
            }
        }else{
            $this->query="SELECT * from FTC_ALMACEN_ORDENES WHERE ID_ORD > 0";
        }
        $res=$this->EjecutaQuerySimple();
        while($tsArray=ibase_fetch_object($res)){
            $data[]=$tsArray;
        }
        return $data;
    }

    function regInt($info, $ln ){
            $id_ord = $info['ID']; $renglon = $info['Renglon']; $cantidad = $info['CantidadInventario']; $articulo = strtoupper( $info['Articulo']); $factor = $info['Factor']; $cantidadInventario = !is_numeric($info['CantidadInventario'])? 0:$info['CantidadInventario']; $cantidadReservada = !is_numeric($info['CantidadReservada'])? 0:$info['CantidadReservada'];
            $this->query="INSERT INTO FTC_ALMACEN_SIN_PAR_INT (ID_SINC, id_ord, ART, RENGLON, CANTIDAD, FACTOR, CantidadInventario, CantidadReservada, ID_ORDD) VALUES (NULL, $id_ord, '$articulo', $renglon, $cantidad, $factor, $cantidadInventario, $cantidadReservada, $ln )";
            $this->grabaBD();
        return;
    }

    function utilOdn($t, $ido, $obs){
        $usr = $_SESSION['user']->ID;
        $this->query= "SELECT * from FTC_ALMACEN_ORDENES WHERE ID_ORD = $ido";
        $res=$this->EjecutaQuerySimple();
        $row = ibase_fetch_row($res);
                
        $this->query="EXECUTE PROCEDURE SP_ALMACEN_LIBERA_ASIGNACION ($ido, $usr, $t, '$obs')";
        //echo $this->query;
        $this->grabaBD();

        return array("status"=> 'ok', "msg"=>'');
    }

    function sincIntWms($idOrdd){
        $data=array();
        $this->query="SELECT c.*, (SELECT PARTIDA FROM FTC_ALMACEN_ORDEN_DET WHERE ID_ORDD = $idOrdd) as Partida, (SELECT MOVID FROM FTC_ALMACEN_ORDEN WHERE ID_ORD = c.id_ord) as movid FROM FTC_ALMACEN_OC_CHG c WHERE c.id_ordd = $idOrdd and c.cant > 0 and c.status <3";
        $res=$this->EjecutaQuerySimple();
        while($tsArray=ibase_fetch_object($res)){
            $data[]=$tsArray;
        }
        return $data;
    }

    function sincInt($info, $infoInt){
        foreach ($info as $inf){
            $id_ordd=$inf->ID_ORDD;
        }
        foreach ($infoInt as $i){
            $cant=$i['Cantidad']; $id=$i['ID']; $renglon=$i['Renglon'];$art=$i['Articulo'];
            $this->limpiaAsig($id_ordd);
            $this->query="UPDATE FTC_ALMACEN_OC_CHG SET CANT = $cant, COLOR = '' WHERE ID_ORDD = (SELECT ID_ORDD FROM FTC_ALMACEN_ORDEN_DET WHERE ID_ORD = $id and PARTIDA = $renglon) and NUEVO = '$art'";
            $this->queryActualiza(); 
        }
        return;
    }

    function valXls($file){
        $data= array();
        $usuario = $_SESSION['user']->NOMBRE;
        $lista = '';
        $inputFileType=PHPExcel_IOFactory::identify($file);
        $objReader=PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel=$objReader->load($file);
        $sheet=$objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow(); 
        $highestColumn = $sheet->getHighestColumn();
        $walmart = ($sheet->getCell('A1')->getValue() == 'WAL * MART' or $sheet->getCell('A1')->getValue() == 'WAL*MART')? 'walmart':'';
        $tipoWM = $sheet->getCell('A3')->getValue() == 'Ref. Documento:'? 'Dev':'pedidos';
        if($walmart == 'walmart'){
            if($tipoWM == 'pedidos'){
                $res=$this->leeWalmart($file);
                return $res;
            }else{
                return array("tipo"=>'DevWM');
            }
        }
        $a = trim($sheet->getCell('A1')->getValue()) == 'SKU' and trim($sheet->getCell('B1')->getValue()) == 'PZ'? 'trans':'';
        if($a == 'trans'){
            $res = $this->leeTransferencia($file);
            return $res;
        }
        $ruta="C:\\xampp\\htdocs\\remisiones\\";
        if(!file_exists($ruta)){mkdir($ruta, null, true);}
        $d=date('s');
        $errors = '';
        $te=0;
        for ($row=2; $row <= $highestRow; $row++){ //10
            $col = 'A';
            //echo 'fecha:'.$sheet->getCell($col.$row)->getValue()+1;
            //$A = date('d/m/Y',PHPExcel_Shared_Date::ExcelToPHP($sheet->getCell($col.$row)->getValue()+1));//FECHA
            $A = date('d/m/Y',PHPExcel_Shared_Date::ExcelToPHP($sheet->getCell($col.$row)->getValue()));//FECHA
            $B = $sheet->getCell(++$col.$row)->getValue();//observacion
            $C = $sheet->getCell(++$col.$row)->getValue();//SKU
            $D = $sheet->getCell(++$col.$row)->getValue();//Piezas
            $E = $sheet->getCell(++$col.$row)->getValue();//Guia Envio
            $F = $sheet->getCell(++$col.$row)->getValue();//Estado Fisico
            $G = $sheet->getCell(++$col.$row)->getValue();//Motivo
            $H = $sheet->getCell(++$col.$row)->getValue();//Solicitud
            $I = $sheet->getCell(++$col.$row)->getValue();//Disponible
            $J = $sheet->getCell(++$col.$row)->getValue();//Disponible
            $K = $sheet->getCell(++$col.$row)->getValue();//Disponible
            $L = $sheet->getCell(++$col.$row)->getValue();//Disponible
            $M = $sheet->getCell(++$col.$row)->getValue();//Disponible
            $N = $sheet->getCell(++$col.$row)->getValue();//Disponible
            if(strpos(($A.$B.$C.$D.$E.$F.$G.$H.$I),"|")){
                    $errors .= $row.',';
                    $te++;
            }else{
                $info[] = $A.'|'.$B.'|'.$C.'|'.$D.'|'.$E.'|'.$F.'|'.$G.'|'.$H.'|'.$I.'|'.$J.'|'.$K.'|'.$N;
                $info1[] = array("FECHA"=>$A,"OBS"=>$B, "SKU"=>$C, "PIEZAS"=>$D, "GUIA"=>$E, "ESTADO"=>$F,"MOTIVO"=>$G, "SOLICITUD"=>$H, "DISP1"=>$I, "DISP2"=>$J, "DISP3"=>$K, "DISP4"=>$L, "DISP5"=>$M, "LINEA"=>$row, "DISP6"=>$N);
            }
        }
        $tipo = (trim($sheet->getCell('B1')->getValue()) == 'Observaciones' and trim($sheet->getCell('C1')->getValue())== 'SKU')? 'Salida Diversa':'Ventas';
        return array("status"=>'ok', "info"=>$info1, "errors"=>$errors, "te"=>$te, 'tipo'=>$tipo);
    }

    function leeWalmart($file){
        $data= array();
        $usuario = $_SESSION['user']->NOMBRE;
        $lista = '';
        $inputFileType=PHPExcel_IOFactory::identify($file);
        $objReader=PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel=$objReader->load($file);
        $sheet=$objPHPExcel->getSheet(0);
        
        $ruta="C:\\xampp\\htdocs\\walmart\\";
        if(!file_exists($ruta)){mkdir($ruta, null, true);}
        $d=date('s');
        $errors = '';
        $te=0;
        $data = array(); 
        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet){
            $row_ctrl = 23;
            $part=array();
            $Hoja = $worksheet->getTitle();
            $orden = $worksheet->getCell("B3")->getValue();
            $suc = $worksheet->getCell("B6")->getValue();
            $fechaOC = $worksheet->getCell("D3")->getValue();
            $fechaCan= $worksheet->getCell("D5")->getValue();
            $suc = $worksheet->getCell("D7")->getValue();
            $depto = $worksheet->getCell("B6")->getValue();
            $comp = $worksheet->getCell("B8")->getValue();
            $deter = $worksheet->getCell("B9")->getValue();
            $prov = $worksheet->getCell("B11")->getValue();
            $arts = $worksheet->getCell("B19")->getValue();
            $monto = $worksheet->getCell("B18")->getValue();
            
            if($worksheet->getCell('A1') != ''){
                $highestRow = $worksheet->getHighestRow(); 
                $highestColumn = $worksheet->getHighestColumn();
                for ($row=$row_ctrl; $row <= $highestRow; $row++){ // Se utiliza para recorrer las partidas
                    $col = 'A';
                    $A = $worksheet->getCell($col.$row)->getValue();//Código EAN/UPC:
                    $B = $worksheet->getCell(++$col.$row)->getValue();//Cómprador
                    $C = $worksheet->getCell(++$col.$row)->getValue();//Vendedor
                    $D = $worksheet->getCell(++$col.$row)->getValue();//Color/Talla
                    $E = $worksheet->getCell(++$col.$row)->getValue();//Precio Unitario
                    $F = $worksheet->getCell(++$col.$row)->getValue();//Medida:
                    $G = $worksheet->getCell(++$col.$row)->getValue();//Total:  
                    $H = $worksheet->getCell(++$col.$row)->getValue();//Cantidad Ordenada:
                    $I = $worksheet->getCell(++$col.$row)->getValue();//No. Paquetes/Empaque:
                    $J = $worksheet->getCell(++$col.$row)->getValue();//Etiqueta:
                    $K = $worksheet->getCell(++$col.$row)->getValue();//Departamento:
                    $L = $worksheet->getCell(++$col.$row)->getValue();//Modelo:
                    $M = $worksheet->getCell(++$col.$row)->getValue();//Precio Vta.
                    $N = $worksheet->getCell(++$col.$row)->getValue();//Info Etiquetas:
                    $O = $worksheet->getCell(++$col.$row)->getValue();//Lugar de Entrega:
                    $P = $worksheet->getCell(++$col.$row)->getValue();//Cantidad:
                    if(strpos(($A.$B.$C.$D.$E.$F.$G.$H.$I.$J.$K.$L.$M.$N.$O.$P),"|")){
                            $errors .= $row.',';
                            $te++;
                    }else{

                        if(trim($A) != trim('Código EAN/UPC:')){
                            $info[] = $A.'|'.$B.'|'.$C.'|'.$D.'|'.$E.'|'.$F.'|'.$G.'|'.$H.'|'.$I.'|'.$J.'|'.$K.'|'.$N.'|'.$O.'|'.$P;
                            $part[] = array("hoja"=>$Hoja,"EAN"=>$A,"COMPRADOR"=>$B, "VENDEDOR"=>$C, "COLOR"=>$D, "PRECIO"=>$E, "MEDIDA"=>$F,"TOTAL"=>$G, "ORDENADA"=>$H, "PAQUETES"=>$I, "ETIQUETA"=>$J, "DEPARTAMENTO"=>$K, "MODELO"=>$L, "PRECIOVTA"=>$M, "INFOETI"=>$N, "LUGARENTREGA"=>$O,"CANTIDAD"=>$P);
                        }
                    }
                }
            }
            $data[]=array("orden"=>$orden, "sucursal"=>$suc, "fechaOc"=>$fechaOC, "fechaCan"=>$fechaCan, "suc"=>$suc, "comp"=>$comp, "hoja"=>$Hoja, "partidas"=>$part, "deter"=>$deter, "prov"=>$prov, "depto"=>$depto, "arts"=>$arts, "monto"=>$monto);
            unset($part);
        }
        return array("status"=>'ok', "info"=>$data, "errors"=>$errors, "te"=>$te, 'tipo'=>'walmart');
    }

    function leeTransferencia($file){
        $data= array();
        $usuario = $_SESSION['user']->NOMBRE;
        $inputFileType=PHPExcel_IOFactory::identify($file);
        $objReader=PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel=$objReader->load($file);
        $sheet=$objPHPExcel->getSheet();
        $highestRow = $sheet->getHighestRow(); 
        $highestColumn = $sheet->getHighestColumn();
        
        $ruta="C:\\xampp\\htdocs\\transferencias\\";
        if(!file_exists($ruta)){mkdir($ruta, null, true);}
        $d=date('s');
        $errors = '';
        $te=0;
        $data = array(); 
        for ($row=2; $row <= $highestRow; $row++){ //10
            $col = 'A';
            $A = $sheet->getCell($col.$row)->getValue();//SKU
            $B = $sheet->getCell(++$col.$row)->getValue();//Piezas
            
            if(strpos(($A.$B),"|")){
                    $errors .= $row.',';
                    $te++;
            }else{
                $info[] = $A.'|'.$B;
                $info1[] = array("articulo"=>$A,"cantidad"=>$B);
            }
        }
        $tipo = 'transferencias';
        $this->insertaTransferencia($info1);
        return array("status"=>'ok', "info"=>$info1, "errors"=>$errors, "te"=>$te, 'tipo'=>$tipo);
    }

    function insertaMovInt($info,  $tipo, $movID, $idint){
        $usuario = $_SESSION['user']->ID;
        $this->query="INSERT INTO FTC_INT_MVI (ID_MVI, id_mov_int, MOV, MOVID, FECHA, ESTATUS, USUARIO, OBS, ALMACEN, CONCEPTO, REFERENCIA, FECHA_ELAB ) VALUES (NULL, $idint, '$tipo', $movID, CURRENT_DATE, '', $usuario, '', '', '', '', current_timestamp) RETURNING ID_MVI";
        $res=$this->grabaBD();
        $id = ibase_fetch_object($res)->ID_MVI; $renglon=0;
        for ($i=0; $i < count($info) ; $i++){ 
            $renglon += 2048; $cant = $info[$i]['PIEZAS']; $alm = 'AL PT';$art = strtoupper($info[$i]['SKU']);$uni = 'PZA'; $factor = 1; $suc = 0; $obs=$info[$i]['OBS']; $guia=$info[$i]['GUIA'];$edo_fis=$info[$i]['ESTADO'];$motivo=$info[$i]['MOTIVO']; $solicitante=$info[$i]['SOLICITUD'];
                if(!empty($art)){
                    $this->query="INSERT INTO FTC_INT_MVI_DET (ID_MVID,ID_MVI,RENGLON,RENGLON_SUB,RENGLON_ID,RENGLON_TIPO,CANTIDAD,ALAMCEN,ARTICULO,UNIDAD,FACTOR,CANTIDAD_INVENTARIO,SUCURSAL,FECHA,USUARIO,ESTATUS,STATUS_WMS,FECHA_ELAB, OBS, GUIA, EDO_FIS, MOTIVO, SOLICITANTE) VALUES (null, $id, $renglon, 0, $i+1,'L', $cant, '$alm', '$art', '$uni', $factor, $cant*$factor, $suc, current_date, $usuario, '', 0, current_timestamp, '$obs', '$guia', '$edo_fis', '$motivo', '$solicitante')";
                    $this->grabaBD();
                }
        }
        return $id; 
    }

    function insertaVtaInt($info,$tipo, $idf){
        $usuario=$_SESSION['user']->ID; $ids=array();$ids='';
        for ($i=0; $i < count($info) ; $i++) { 
            $oc = $info[$i]['orden']; $suc=$info[$i]['sucursal']; $fechaOC=$info[$i]['fechaOc'];$fechaCan=$info[$i]['orden']; $suc=$info[$i]['suc']; $comp=$info[$i]['comp']; $hoja=$info[$i]['hoja']; $cliente = '10002'; $prov = $info[$i]['prov']; $determinante=$info[$i]['deter']; $depto = $info[$i]['depto']; $monto = $info[$i]['monto']; $arts=$info[$i]['arts'];$subDet=$info[$i]['sucursal'];
            //echo '<br/>Hoja u Orden de compra: '.$oc.'<br/>';
            if(!empty($oc)){
                $this->query="INSERT INTO FTC_INT_FACT (ID_INT_F, EMPRESA, MOV, FECHAEMISION, MONEDA, TIPOCAMBIO, USUARIO, ESTATUS, CLIENTE, ALMACEN, ENVIARA, FORMAPAGOTIPO, COMENTARIOS, ORDENCOMPRA, AGENTE, ATENCION, MOVID, OBSERVACIONES, REFERENCIA, LISTAPRECIOSESP, COMPRADOR, PROVEEDOR, DETERMINANTE, DEPTO, MONTO, ARTICULOS, SUB_DETERMINANTE, ID_FILE) VALUES (NULL, 'MIZCO', 'Pedido', NULL, 'Pesos', 1, 'CMARTINEZ', 'SINAFECTAR', '$cliente', 'AL PT', 0, '', '', '$oc', '', '', '', '', '', '', '$comp', '$prov', '$determinante', '$depto', $monto, $arts, '$subDet', $idf) RETURNING id_int_f ";
                $res=$this->grabaBD(); $id = ibase_fetch_object($res)->ID_INT_F; $renglon=0; $rid=0; $ids.=$id.',';
            }
            for ($p=0; $p < count($info[$i]['partidas']); $p++) { 
                $renglon += 2048; $rid++; $almacen='AL PT'; $articulo = $info[$i]['partidas'][$p]['VENDEDOR']; $precio=$info[$i]['partidas'][$p]['PRECIO']; $imp1 =1.16; $unidad=$info[$i]['partidas'][$p]['PAQUETES']; $cant = $info[$i]['partidas'][$p]['ORDENADA'];
                $paquete = explode("/", $unidad);$paquete=$paquete[0]; $comprador=$info[$i]['partidas'][$p]['COMPRADOR'];
                $descExtra=''; $cantInv= $cant; $ordenada = $info[$i]['partidas'][$p]['ORDENADA']; $paquetes = $info[$i]['partidas'][$p]['PAQUETES'];
                $this->query="INSERT INTO FTC_INT_FACT_PAR (ID_INT_FP, ID, RENGLON, RENGLONSUB, RENGLONID, RENGLONTIPO, ALMACEN, CANTIDAD, ARTICULO, PRECIO, IMPUESTO1, UNIDAD, DESCRIPCIONEXTRA, CANTIDADINVENTARIO, ORDENCOMPRA, COMPRADOR, ART_ORIGINAL) VALUES (NULL, $id, $renglon, 0, $rid, 'L', '$almacen', $cant, '$articulo', $precio, $imp1, '$paquete', '$descExtra', $cantInv, $oc, '$comprador', '$articulo')";
                $this->grabaBD();
            }
        }
        $ids = substr($ids, 0 , strlen($ids)-1);
        $this->actDet($ids);
        return $this->validaInt($ids);
    }

    function actDet($ids){
        $data=array();
        $this->query="SELECT * FROM FTC_INT_FACT WHERE ID_INT_F IN ($ids)";
        $res=$this->EjecutaQuerySimple();
        while($tsArray=ibase_fetch_object($res)){
            $data[]=$tsArray;
        }
        if(count($data)>0){
            foreach ($data as $k) {
                $depto = substr($k->PROVEEDOR, strlen($k->PROVEEDOR)-3);
                $this->query="UPDATE FTC_INT_FACT SET ENVIARA = (SELECT ID_INT FROM FTC_INT_DET WHERE DET_WM = DETERMINANTE and depto ='$depto'),  DET_INTELISIS= (SELECT DET_IN FROM FTC_INT_DET WHERE DET_WM = DETERMINANTE and depto ='$depto'), LISTAPRECIOSESP = (SELECT LISTA_INT FROM FTC_INT_DET WHERE DET_WM = DETERMINANTE and depto ='$depto') WHERE id_int_f = $k->ID_INT_F";
                $this->queryActualiza();
            }
        }
        return;
    }

    function insertaTransferencia($data){
        $usuario =$_SESSION['user']->ID;
        $this->query = "INSERT INTO FTC_INT_MVI (ID_MVI, id_mov_int, mov, movid, fecha, estatus, usuario, concepto, Referencia, fecha_elab, id_ord) VALUES (null, null, 'Traspaso', null, current_date, '', $usuario, 'Transferencia','', current_timestamp, null) returning ID_MVI";
        $res=$this->grabaBD();
        $row=ibase_fetch_object($res);
        $id=$row->ID_MVI; $renglon = 0; $renglonid=0;
        for($i=0; $i < count($data); $i++){
            $renglon = $renglon + 2048; $renglonid++;
            $articulo=$data[$i]['articulo'];$cantidad=$data[$i]['cantidad'];
            if(!empty($articulo)){
                $this->query="INSERT INTO FTC_INT_MVI_DET (id_mvid, ID_MVI, renglon , RENGLON_SUB, renglon_id, RENGLON_TIPO, cantidad, alamcen, articulo, unidad, factor, cantidad_Inventario, Sucursal, fecha, usuario, estatus, status_wms, fecha_elab) VALUES (null, $id, $renglon, 0, $renglonid, 'L', $cantidad, 'AL-PT', '$articulo', 'PZA', 1, $cantidad, 0, null, $usuario, '', 0, current_timestamp)";
                $this->grabaBD();
            }
        }
        return;
    }

    function validaInt($ids){
        $determinantes=array();
        $this->query ="SELECT * FROM FTC_INT_FACT WHERE ID_INT_F IN ($ids)";
        $res=$this->EjecutaQuerySimple();
        while($tsArray=ibase_fetch_object($res)){
            $cabecera[] =$tsArray;
        }
        $this->query ="SELECT * FROM FTC_INT_FACT_PAR WHERE ID IN ($ids)";
        $res=$this->EjecutaQuerySimple();
        while($tsArray=ibase_fetch_object($res)){
            $partidas[] =$tsArray;
        }

        foreach ($cabecera as $c) {
            $depto = substr($c->PROVEEDOR, strlen($c->PROVEEDOR)-3);    
            $this->query = "SELECT count(*) as val FROM FTC_INT_DET WHERE DET_WM = '$c->DETERMINANTE' and depto = '$depto'";
            $res=$this->Ejecutaquerysimple();
            $row=ibase_fetch_object($res);
            if($row->VAL== 0){
                $this->query ="INSERT INTO FTC_INT_DET (ID_DET, DET_WM, DEPTO) VALUES (NULL, '$c->DETERMINANTE', '$depto')";
                $this->grabaBD();
            }
        }
        $this->query="SELECT * FROM FTC_INT_DET WHERE det_in is null";
        $res=$this->EjecutaQuerySimple();
        while($tsArray=ibase_fetch_object($res)){
            $determinantes[] =$tsArray;
        }
        return array("cabecera"=>$cabecera, "partidas"=>$partidas, "determinantes"=>$determinantes);
    }

    function valWms($valData){
        $usuario=$_SESSION['user']->ID;$data=array();
        foreach ($valData['valPart'] as $val) {
            $id =  $val['id']; $sta =  $val['val']; $artn = $val['art']; $lista=$val['lista'];
            $this->query="INSERT INTO FTC_INT_FACT_LOG (ID, id_int_f, ID_INT_FP, FECHA_CARGA, STATUS, USUARIO, OBS) VALUES (NULL, (SELECT ID FROM FTC_INT_FACT_PAR WHERE ID_INT_FP = $id), $id, current_timestamp, $sta, $usuario, 'Articulo')";
            $this->grabaBD();
            if(!empty($artn)){
                $this->query="UPDATE FTC_INT_FACT_PAR SET ARTICULO = '$artn', lista = '$lista' where ID_INT_FP = $id";
                $this->queryActualiza();
            }
        }
        foreach ($valData['valCab'] as $valc){
            $idc = $valc['id']; $ea = $valc['suc'];
            if($ea > 0){
                $this->query = "UPDATE FTC_INT_FACT SET ENVIARA = $ea where id_int_f = $idc";
                $this->queryActualiza();
            }
            $this->query="EXECUTE PROCEDURE FTC_INT_VAL_FAT_PART ($idc)";
            $this->grabaBD();
        }

        foreach ($valData['valCab'] as $vc){
            $idc = $vc['id'];
            $this->query = "SELECT (select first 1 status from FTC_INT_FACT_LOG l where l.ID_INT_FP is null and l.id_int_f = f.ID_INT_F order by l.id desc) as val, f.* FROM FTC_INT_FACT f where ENVIARA > 0 and id_int_f = $idc";
            //echo 'consulta de las validaciones: '.$this->query;
            $res=$this->EjecutaQuerySimple();
            while($tsArray=ibase_fetch_object($res)){
                $data[]=$tsArray;
            }
        }
        return $data;
    }

    function traeDatosInt($id){
        $data=array(); $par=array();
        $this->query="SELECT * FROM FTC_INT_FACT WHERE ID_INT_F = $id";
        $res=$this->EjecutaQuerySimple();
        while($tsArray=ibase_fetch_object($res)){
            $data[]=$tsArray;
        }
        $this->query="SELECT * FROM FTC_INT_FACT_PAR WHERE ID = $id";
        $res = $this->EjecutaQuerySimple();
        while($tsArray=ibase_fetch_object($res)){
            $par[]=$tsArray;
        }
        return array("cabeceras"=>$data, "partidas"=>$par);
    }

    function revMovs(){
        $data=array();
        $this->query="SELECT * FROM FTC_INT_MVI WHERE ESTATUS ='CONCLUIDO' AND id_ord is null";
        $res=$this->EjecutaQuerySimple();
        while($tsArray=ibase_fetch_object($res)){
            $data[]=$tsArray;
        }
        if(count($data)>0){
            foreach ($data as $key){
                $this->query="SELECT * FROM FTC_INT_MVI_DET WHERE ID_MVI = $key->ID_MVI";
                $res=$this->EjecutaQuerySimple();
                while($tsArray=ibase_fetch_object($res)){
                    $part[]=$tsArray;
                }
                $this->insertMov($key, $part);
            }
        }
        return $data;
    }

    function revTrans(){
        $data=array();
        $this->query="SELECT m.ID_MVI AS ID_MOV_INT, m.* FROM FTC_INT_MVI m WHERE m.ESTATUS ='' AND m.id_ord is null and m.MOV='Traspaso'";
        $res=$this->EjecutaQuerySimple();
        while($tsArray=ibase_fetch_object($res)){
            $data[]=$tsArray;
        }
        if(count($data)>0){
            foreach ($data as $key){
                $id = $key->ID_MVI;
                $this->query="SELECT * FROM FTC_INT_MVI_DET WHERE ID_MVI = $key->ID_MVI";
                $res=$this->EjecutaQuerySimple();
                while($tsArray=ibase_fetch_object($res)){
                    $part[]=$tsArray;
                }
                $this->insertMov($key, $part);
            }
        }
        if(!empty($id)){
            $this->query ="UPDATE FTC_INT_MVI SET ID_ORD = $id, status_wms = 0 where ID_MVI = $id";
            $this->queryActualiza();
        }
        return $data;    
    }

    function insertMov($cabecera, $partidas){
            $idmiv=$cabecera->ID_MOV_INT; $motivo='';
            $arts = count($partidas);
            $usuario = $_SESSION['user']->ID;
            $this->query="INSERT INTO FTC_ALMACEN_ORDEN ( ID_ORD, IDCLIENTE, CLIENTE, CEDIS, FECHA_CARGA, FECHA_ASIGNA, FECHA_ALMACEN, FECHA_CARGA_F, FECHA_ASIGNA_F, FECHA_ALMACEN_F, STATUS, NUM_PROD, CAJAS, PRIORIDAD, ARCHIVO, USUARIO, ORIGINAL, MOV, MOVID, STA_INT, ID_INT ) VALUES ($idmiv, '', '$cabecera->MOV', '', CURRENT_TIMESTAMP, NULL, NULL, NULL, NULL, NULL, 1, $arts, 0, 0,'$cabecera->MOV'||'$cabecera->MOVID', $usuario,'', '$cabecera->MOV', '$cabecera->MOVID', '$cabecera->ESTATUS', $cabecera->ID_MOV_INT) returning ID_ORD";
            $res=$this->grabaBD();
            $id=ibase_fetch_object($res)->ID_ORD; 
        $i=0;
        foreach ($partidas as $p ){
            $i++; if($i==1){$motivo=$p->MOTIVO;}
            $this->query="INSERT INTO FTC_ALMACEN_ORDEN_DET (ID_ORDD, ID_ORD, PROD, DESCR, PZAS, CAJAS, COLOR, CEDIS, PZAS_SUR, CAJAS_SUR, STATUS, OBS, ORDEN, UPC, ITEM, LINEA_NWM, UNIDAD, CADENA, ASIG, ETIQUETA, PARTIDA) VALUES (NULL, $id, '$p->ARTICULO', (SELECT DESC FROM FTC_ALMACEN_PROD_INT WHERE ID_INT = '$p->ARTICULO'), $p->CANTIDAD, 1, '', '$p->MOTIVO', 0,0,1, '$p->OBS -->'||'$p->MOTIVO -->'||'$p->SOLICITANTE', '', '', '', '', 1, NULL, 0, NULL, $p->RENGLON)";
            $this->grabaBD();
        }
        $this->query ="UPDATE FTC_INT_MVI SET ID_ORD = $id, status_wms = 0 where ID_MOV_INT = $idmiv";
        $this->queryActualiza();
        $this->query ="UPDATE FTC_ALMACEN_ORDEN SET CEDIS = '$motivo' where id_ord = $id";
        $this->queryActualiza();

        return;
    }

    function movsInv(){
        $data = array();
        $this->query="SELECT * FROM FTC_INT_MVI WHERE ID_ORD  IS NULL and mov != 'Traspaso'";
        $res=$this->EjecutaQuerySimple();
        while($tsArray=ibase_fetch_object($res)){
            $data[]=$tsArray;
        }
        return $data;
    }

    function actMov($info){
        for ($i=0; $i < count($info) ; $i++) { 
            $id = $info[$i]['idInt']; $status=$info[$i]['estatus'];
            $this->query="UPDATE FTC_INT_MVI SET ESTATUS = '$status' where id_mov_int = $id";
            $this->queryActualiza();
        }
        return;
    }

    function sincCab($info){
        foreach($info as $inf){
            $idintf= $inf['ReferenciaOrdenCompra'];
            $fpt = $inf['FormaPagoTipo']; $ea = empty($inf['EnviarA'])? 0:$inf['EnviarA']; $mi = $inf['movID']; $a = $inf['Agente']; $o = $inf['Observaciones']; $r = $inf['Referencia']; $lpe=$inf['ListaPreciosEsp']; $fe = $inf['FechaEmision'];
            $fe = $fe->format("d.m.Y"); $detInt = $inf['detInt']; $idInt=$inf['ID'];
            $this->query="UPDATE FTC_INT_FACT SET FECHAEMISION = '$fe', FORMAPAGOTIPO = '$fpt', ENVIARA = $ea, MovID = '$mi', Agente='$a', Observaciones = '$o', Referencia = '$r', ListaPreciosEsp = '$lpe', DET_INTELISIS = '$detInt', ID_INT = '$idInt' WHERE ID_INT_F = $idintf";
            $this->queryActualiza();
            return;
        }
    }

    function esTrans($ord){
        $cab=array(); $par=array();
        $this->query="SELECT * FROM FTC_ALMACEN_ORDEN WHERE ID_ORD = $ord";
        $res=$this->EjecutaQuerySimple();
        $row=ibase_fetch_object($res);
        $mov=$row->ARCHIVO == 'Traspaso'? 'si':'no';
        if($mov == 'si'){
            $this->query="SELECT * FROM FTC_INT_MVI WHERE ID_MVI = $ord";
            $res=$this->EjecutaQuerySimple();
            while ($tsArray=ibase_fetch_object($res)) {
                $cab[]=$tsArray;
            }
            $this->query="SELECT * FROM FTC_ALMACEN_ORDEN_DET WHERE ID_ORD = $ord";
            $res=$this->EjecutaQuerySimple();
            while ($tsArray=ibase_fetch_object($res)) {
                $par[]=$tsArray;
            }
        }
        return array("status"=>$mov, "cab"=>$cab, "par"=>$par);
    }

    function actTrans($id, $info){
        $movId = $info['movid'];$idInt=$info['idint'];
        $this->query =" UPDATE FTC_INT_MVI SET ID_MOV_INT = $idInt, MovID = $movId where id_mvi = $id";
        $this->queryActualiza();
        $this->query ="UPDATE FTC_ALMACEN_ORDEN SET MOVID = $movId, STA_INT = 'SINAFECTAR', ID_INT = $idInt, ARCHIVO = archivo||'$movId' where id_ord = $id";
        $this->queryActualiza();
        
        return;
    }

    function impCont($idc){
        $data=array();
        $this->query="SELECT prod, max(articulo) as articulo, max(producto) as producto, max(tarima) as tarima, sum(disponible) as disponible, max(linea) as linea
                    from FTC_CONTENIDO_COMP
                    where compp = $idc
                    group by prod, comps
                    order by comps";
        $res=$this->EjecutaQuerySimple();
        while($tsArray=ibase_fetch_object($res)){
            $data[]=$tsArray;
        }
        return $data;
    }

}?>



    
    
        
    
    
    
    
    
    
    
    
    
    
    
    
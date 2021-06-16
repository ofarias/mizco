<?php

require_once 'app/model/database.php';
require_once('app/fpdf/fpdf.php');
require_once('app/views/unit/commonts/numbertoletter.php');
require_once 'app/simplexlsx-master/src/SimpleXLSX.php';

/* Clase para hacer uso de database */
class wms extends database {
    /* Comprueba datos de login */
    function productos($op){
        $this->query="SELECT * FROM FTC_ALMACEN_PROD_INT $op";
        $res=$this->EjecutaQuerySimple();
        while ($tsArray=ibase_fetch_object($res)) {
            $data[]=$tsArray;
        }
        return $data;
    }

    function actProd($cve, $lg, $an, $al, $p, $ou){
        $lg = ($lg=='')? 0:$lg; $an = ($an=='')? 0:$an; $al = ($al=='')? 0:$al; $p = ($p=='')? 0:$p; $ou = ($ou=='')? 0:$ou; 
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

        //$this->query="SELECT $f c.*,   
        //    (SELECT coalesce(SUM(piezas),0) FROM FTC_ALMACEN_MOV AM WHERE AM.COMPS = c.ID_COMP and am.tipo='e' and am.status='F' and c.id_tipo = 1 ) AS entradasS, 
        //    (SELECT coalesce(SUM(piezas),0) FROM FTC_ALMACEN_MOV AM WHERE AM.COMPS = c.ID_COMP and am.tipo='s' and am.status='F' and c.id_tipo = 1) AS salidasS, 
        //    (SELECT coalesce(SUM(piezas),0) FROM FTC_ALMACEN_MOV AM WHERE AM.COMPP = c.ID_COMP and am.tipo='e' and am.status='F' and c.id_tipo = 2) AS entradasP, 
        //    (SELECT coalesce(SUM(piezas),0) FROM FTC_ALMACEN_MOV AM WHERE AM.COMPP = c.ID_COMP and am.tipo='s' and am.status='F' and c.id_tipo = 2) AS salidasP
        //FROM FTC_ALMACEN_COMPONENTES c $op $p order by id_comp desc";
        //echo '<p>'.$this->query.'</p>';
        $this->query="SELECT $f c.*,
        (SELECT coalesce(SUM(piezas),0) FROM FTC_ALMACEN_MOV AM WHERE AM.COMPS = c.ID_COMP and am.tipo='e' and am.status='F' and c.id_tipo = 1 ) AS entradasS, 
        (SELECT coalesce(SUM(piezas),0) FROM FTC_ALMACEN_MOV AM WHERE AM.COMPP = c.ID_COMP and am.tipo='e' and am.status='F' and c.id_tipo = 2) AS entradasP 
         FROM FTC_ALMACEN_COMPONENTES c $op $p order by id_comp desc";
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
            //print_r( $param);
            foreach ($param as $key => $value) {
                if($key=='t' and $value != 'none'){
                    $p .= " and ID_TIPO = '".$value."' ";$i++;
                }
                if($key=='a' and $value != 'none'){
                    $p .= ' and ID_ALMACEN = '.$value.' ';$i++;
                }
                if($key=='p' and $value != ''){
                    $pro = explode(":", $value);
                    //print_r($pro);
                    $p .= " and  id_PROD CONTAINING(".trim($pro[2]).") ";$i++;
                }
                if($key=='e' and $value != 'none'){
                    $p .= " and id_status = '".$value."' ";$i++;
                }
                if($key=='us' and $value != 'none'){
                    $p .= " and id_user = ".$value;$i++;
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
        $this->query="SELECT first 50 mov, max(SIST_ORIGEN) AS SIST_ORIGEN, (select max(nombre) from FTC_ALMACEN a where a.id =  MAX(AM.ID_ALMACEN)) AS ALMACEN, MAX(TIPO) AS TIPO, MAX(FECHA) AS FECHA, MAX(STATUS) AS STATUS, MIN(HORA_I) AS HORA_I, MAX(HORA_F) AS HORA_F, SUM(CANT) AS CANT, SUM(PIEZAS) AS PIEZAS  , MAX(usuario) as usuario, cast( list(DISTINCT prod) as varchar (1000)) as prod, (SELECT MAX(ETIQUETA) FROM FTC_ALMACEN_COMPONENTES AC WHERE AC.ID_COMP = max(AM.ID_compp) ) as componente 
        FROM FTC_ALMACEN_MOVIMIENTO AM $op $p  group by mov order by mov desc";
        //echo 'Consulta de movimientos con filtro: '.$this->query;
        $res=$this->EjecutaQuerySimple();
        while ($tsArray=ibase_fetch_object($res)) {
            $data[]=$tsArray;
        }
        return $data;
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

    function  addMov($tipo, $alm, $compP, $compS, $prod, $uni, $cant, $col, $mov, $pza){
        $usuario=$_SESSION['user']->ID;
        $sist='php';
        if($mov=='nuevo'){
            $folio = $this->folioMov($mov);
            $id=$folio[1]; $mov=$folio[0]; 
            $this->query="UPDATE FTC_ALMACEN_MOV SET SIST_ORIGEN= '$sist', ALMACEN=$alm, TIPO='$tipo', USUARIO=$usuario, FECHA=current_timestamp, STATUS='P', HORA_I=current_time, CANT=$cant, PROD =$prod, UNIDAD=$uni, PIEZAS=$pza, MOV=$mov, COMPP=$compP, COMPS=$compS, COLOR='$col' WHERE ID_AM=$id";
            $this->queryActualiza();
            $msg='Se ha inserado el movimiento';
        }else{
            $this->query="INSERT INTO FTC_ALMACEN_MOV (ID_AM, SIST_ORIGEN, ALMACEN, TIPO, USUARIO, FECHA, STATUS, USUARIO_ATT, HORA_I, CANT, PROD, UNIDAD, PIEZAS, MOV, COMPP, COMPS, COLOR) VALUES (null, '$sist', $alm, '$tipo', $usuario, current_timestamp, 'P', 0, current_time, $cant, $prod, $uni, $pza, $mov, $compP, $compS, '$col')";
            $res=$this->grabaBD();

            if($res == 1){
                $msg='Se ha inserado el movimiento';
            }
        }
        return array('msg'=>$msg, "mov"=>$mov);
    }

    function canMov($mov, $mot, $t){
        $this->query="UPDATE FTC_ALMACEN_MOV SET STATUS = upper('$t') and piezas=0, cant=0 WHERE MOV = $mov ";
        $this->queryActualiza();
        return array("msg"=>'Se ha cancelado el movimiento');
    }

    function cpLin($base, $cs){
        $this->query="INSERT INTO FTC_ALMACEN_MOV (ID_AM, SIST_ORIGEN, ALMACEN, TIPO, USUARIO, FECHA, STATUS, USUARIO_ATT, HORA_I, HORA_F, CANT, PROD, UNIDAD, PIEZAS, MOV, COMPP, COMPS, COLOR) SELECT NULL, SIST_ORIGEN, ALMACEN, TIPO, USUARIO, current_timestamp, STATUS, USUARIO_ATT, current_time, HORA_F, CANT, PROD, UNIDAD, PIEZAS, MOV, COMPP, '$cs', COLOR FROM FTC_ALMACEN_MOV WHERE ID_AM = $base and status='P' RETURNING ID_AM, MOV";
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

    function movimiento($op){
        $data=array();
        $this->query="SELECT * FROM FTC_ALMACEN_MOVIMIENTO WHERE MOV= $op and status != 'Baja' order by id_AM";
        $res=$this->EjecutaQuerySimple();
        while ($tsArray=ibase_fetch_object($res)) {
            $data[]=$tsArray;
        }
        return $data;
    }

    function detalleMov($op){
        $data=array();
        $this->query="SELECT * FROM FTC_ALMACEN_MOVIMIENTO WHERE MOV= $op order by id_AM";
        $res=$this->EjecutaQuerySimple();
        while ($tsArray=ibase_fetch_object($res)) {
            $data[]=$tsArray;
        }
        return $data;
    }

    function delMov($idMov, $tp){
        $status = $tp=='del'? 'B':'F';
        if($tp=='del'){
            $this->query="UPDATE FTC_ALMACEN_MOV SET STATUS = '$status', cant=0, piezas=0 where id_AM = $idMov and status= 'P'";
            $res=$this->queryActualiza();
            if($res == 1){
                $msg = 'Se ha dado de baja la linea';
            }else{
                $msg= 'El movimiento parece estar Finalizado y no permite la edicion de lineas.';
            }    
        }elseif ($tp=='end') {
            $this->query="UPDATE FTC_ALMACEN_MOV SET STATUS = '$status', HORA_F = current_timestamp  where MOV = (select mov from FTC_ALMACEN_MOV where id_AM = $idMov) and status='P'";
            $res= $this->queryActualiza();
            if($res>=1){
                $msg='Se ha finalizado el Momiemiento, ya puede imprimir el QR';
            }else{
                $msg='Surgio un inconveniente favor de actulizar';
            }
        }
        return array("msg"=>$msg);
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
                        WHERE (ID_PINT||' '|| ID_INT ||' '|| DESC) CONTAINING '$prod' and tipo_int= 'Lote'";
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
            $this->query="SELECT * FROM FTC_ALMACEN_PROD_INT WHERE TIPO_INT='Lote'";
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
        //$this->query="SELECT m.comps, m.prod, iif(m.tipo ='e', sum(piezas), 0) as entradas, iif(m.tipo='s',sum(piezas), 0 ) as salidas from FTC_ALMACEN_MOV m where comps=$id and status ='F' group by m.comps, m.prod, m.tipo";
        if($tipo == 'pc'){
            $this->query="SELECT m.almacen, m.id_comps, m.prod, iif(m.id_tipo ='e', sum(piezas), 0) as entradas,
                        -- iif(m.id_tipo='s',sum(piezas), 0 ) as salidas
                        (SELECT SUM(PIEZAS) FROM FTC_ALMACEN_MOV_SAL ms WHERE ms.ID_COMPS = m.id_comps )  as salidas
                        from FTC_ALMACEN_MOVimiento m
                        where id_comps=$id and id_status='F' group by m.id_comps, m.prod, m.id_tipo, m.almacen";
                        echo $this->query;
        }elseif($tipo == 'pp'){
            $this->query="SELECT m.almacen, m.id_comps, m.compp, m.comps, m.id_prod, m.id_tipo, iif(m.id_tipo ='e', sum(piezas), 0) as entradas, 
            --iif(m.id_tipo='s',sum(piezas), 0 ) as salidas
                        (SELECT SUM(PIEZAS) FROM FTC_ALMACEN_MOV_SAL ms WHERE ms.ID_COMPS = m.id_comps )  as salidas
                        from FTC_ALMACEN_MOVimiento m
                        where id_status='F' group by m.id_comps, m.compp, m.comps, m.id_prod, m.id_tipo, m.almacen order by m.id_comps asc ";
        }
        $res=$this->EjecutaQuerySimple();
        while ($tsArray=ibase_fetch_object($res)){
            $data[]=$tsArray;
        }
        return $data;
    }

    function ordenes($op){
        $data=array();
        $this->query="SELECT * FROM FTC_ALMACEN_ORDENES WHERE ID_ORD >0 $op";
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
            if(strtoupper( trim($hoja)) == 'COPPEL'){
                $this->coppel($xlsx, $hoja, $file, $ido);
            }elseif(strtoupper( trim($hoja)) == 'NUEVO WALMART'){
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
            }
            else{
                echo 'Lo siento no tengo el formato para el cliente: '.$hoja.' favor de revisar el nombre de la hoja';
                return array("msg"=>"Lo siento no tengo el formato para el cliente: ".$hoja.' favor de revisar el nombre de la hoja');
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
                        if($ln >=5 and $ln<=count($xlsx->rows()) - 1 and $key[7] !=''){
                            //echo '<br/>Valor de la celda: '.$col.$ln.' = '. $key[7].'<br/>';
                            $piezas += $key[7];
                            //echo '<br/>Lee la linea: '.$ln.' Columna: 7<br/> valor de key[7]'.$key[7];
                            $this->query="INSERT INTO FTC_ALMACEN_ORDEN_DET (ID_ORDD, ID_ORD, PROD, DESCR, PZAS, CAJAS, COLOR, CEDIS, PZAS_SUR, CAJAS_SUR, STATUS, OBS, ORDEN, UPC, ITEM, LINEA_NWM, UNIDAD) VALUES (NULL,    $idord, '$key[4]', '', $key[7], 0, '$key[6]', '', 0, 0, 1, '', '','$key[5]','$key[3]','', null) returning ID_ORDD";
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
                                            (NULL, $idord, '$k2[5]', '', $k2[$nC], 0, '', '$key[$nC]', 0, 0, 1, '', '','$k2[2]','','', $k2[4], '$k2[1]') returning ID_ORDD";
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
                                            (NULL, $idord, '$k2[5]', '', $k2[$nC], 0, '', '$key[$nC]', 0, 0, 1, '', '','$k2[2]','','', $k2[4], '$k2[1]') returning ID_ORDD";
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
                        if($ln >= 5 and $ln<=count($xlsx->rows()) - 1 and ($key[6]!='' and $key[2] !='')){
                            if(is_numeric($key[6])){$piezas += $key[6];}
                            $this->query="INSERT INTO FTC_ALMACEN_ORDEN_DET (ID_ORDD, ID_ORD, PROD, DESCR, PZAS, CAJAS, COLOR, CEDIS, PZAS_SUR, CAJAS_SUR, STATUS, OBS, ORDEN, UPC, ITEM ) VALUES (NULL, $idord, '$key[2]', '$key[1]', $key[6], 0, '', '', 0, 0, 1, '', '$key[10]','','$key[0]') returning ID_ORDD";
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

    function datOrden($id_o){
        $this->query="SELECT * FROM FTC_ALMACEN_ORDENES WHERE ID_ORD = $id_o";
        $res=$this->EjecutaQuerySimple();
        $row=ibase_fetch_object($res);
        return $row;
    }

    function orden($id_o, $t, $param){
        $data= array(); $p='';
        $this->query="UPDATE FTC_ALMACEN_ORDENES_DETALLES o set o.descr = (SELECT DESC FROM FTC_ALMACEN_PROD_INT WHERE ID_INT = o.PROD) where o.descr='' ";
        $this->queryActualiza();
        if($t == 'd'){
            $this->query="SELECT * FROM FTC_ALMACEN_ORDENES_DETALLES where id_ord=$id_o";
        }elseif($t == 'p'){
            $this->query="SELECT prod, descr, sum(pzas) as pzas, count(cedis) as cedis, max(orden) as orden, upc, item, PROD_SKU, CAST(LIST( DISTINCT color) AS varchar(200)) AS COLOR, sum(PZAS_SUR) as pzas_sur, avg(id_status) as status, sum(asig) as asig
                from ftc_almacen_ordenes_detalles where id_ord = $id_o 
                group by prod, upc, PROD_SKU, descr,  item";
        }elseif($t == 's'){
            if(!empty($param)){
                $p= " and cedis = '".$param."'";
            }
            $this->query="SELECT ID_ORDD,UPC, ITEM, PROD, DESCR, PZAS, ASIG, CAJAS, UNIDAD, PROD_SKU, orden, cedis,PZAS_SUR, CAJAS_SUR, status, ETIQUETA FROM FTC_ALMACEN_ORDENES_DETALLES WHERE ID_ORD = $id_o and id_status >=3 $p";
        }
        $res=$this->EjecutaQuerySimple();
        while ($tsArray=ibase_fetch_object($res)) {
            $data[]=$tsArray;
        }
        return $data;
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
        }
        return array("msg"=>$msg, "status"=>$sta);
    }

    function actualizaCodigo(){
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

    function actProdSku($id_o){
        $this->query="UPDATE FTC_ALMACEN_ORDENES_DETALLES SET PROD = (SELECT ID_INT FROM FTC_ALMACEN_PROD_INT WHERE )";
    }

    function asgProd($ord, $prod, $pza, $t, $c, $s){
        if($t == 'm'){
            $res=$this->asgMultiple($ord, $prod);
            return $res;
        }
        $data=array();$msg='Se han asignado '.$pza.' del producto '.$prod;
        if($t=='q'){
            $this->query="UPDATE FTC_ALMACEN_ORDEN_DET SET ASIG = 0 where PROD = '$prod' and id_ord = $ord";
            $this->queryActualiza();
        }
        $this->query="SELECT * FROM FTC_ALMACEN_ORDEN_DET WHERE PROD = '$prod' and id_ord = $ord";
        $res=$this->EjecutaQuerySimple();
        //echo $this->query;
        while ($tsArray=ibase_fetch_object($res)) {
            $data[]=$tsArray;            
        }        
        if($t== 'q'){
            $msg="Se han quitado ".$pza." del producto ".$prod;
            $pza=$s - $pza;
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
        return array("status"=>'ok', "msg"=>$msg);
    }

    function asgMultiple($ord, $prod){
        $prod = explode(":", $prod);
        for ($i=0; $i < count($prod); $i++) { 
            $this->query="UPDATE FTC_ALMACEN_ORDEN_DET SET ASIG = PZAS, status= 2 WHERE ID_ORD = $ord and PROD = '$prod[$i]'";
            $this->queryActualiza();
        }
        return;
    }

    function detLinOrd($ord, $prod){
        $data=array();
        $this->query="SELECT * FROM FTC_ALMACEN_ORDEN_DET WHERE PROD = '$prod' and id_ord = $ord";
        $res=$this->EjecutaQuerySimple();
        while ($tsArray=ibase_fetch_object($res)) {
            $data[]=$tsArray;
        }
        ///print_r($data);
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

    function actDescr($id_o){
        $this->query="UPDATE FTC_ALMACEN_ORDEN_DET O SET DESCR = (SELECT DESC FROM FTC_ALMACEN_PROD_INT WHERE ID_INT = O.PROD) WHERE DESCR IS NULL AND ID_ORD = $id_o";
        $this->queryActualiza();
        return;
    }

    function asgLn($ln, $c){
        $sta='ok'; $msg="Se actualizo correctamente";
        $this->query="UPDATE FTC_ALMACEN_ORDEN_DET SET ASIG = $c where id_ordd = $ln and pzas >= $c";
        $res= $this->queryActualiza();
        if ($res != 1){
            $sta='no'; $msg="No se actualizo correctamente, quizas la cantidas excede a lo requerido";
        }
        $this->actStaOD($ln, '', '', 'l', 'a');
        return array("status"=>$sta, "msg"=>$msg);
    }

    function actStaOD($ln, $prod, $oc, $t, $m){
        if($t == 'l' and $m == 'a'){
            $this->query="SELECT * FROM FTC_ALMACEN_ORDEN_DET WHERE ID_ORDD = $ln";
            $res=$this->EjecutaQuerySimple();
            $res=ibase_fetch_object($res);
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

        if($t == 'm' and $m == 'a'){
            $data=array();
            $this->query="SELECT * FROM FTC_ALMACEN_ORDEN_DET WHERE ID_ORD = $oc and prod = '$prod'";
            $res=$this->EjecutaQuerySimple();
            while ($tsArray=ibase_fetch_object($res)) {
                $data[]=$tsArray;
            }
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

    function asigCol($nP, $ln, $col){
        $usuario=$_SESSION['user']->ID;
        /// obtenemos los datos originales de la linea.
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
            $this->query="UPDATE FTC_ALMACEN_ORDEN_DET SET COLOR='Mixto', ASIG=$pzas where id_ordd = $ln";
            $this->queryActualiza();
        }elseif($c==1){
            $this->query="UPDATE FTC_ALMACEN_ORDEN_DET SET COLOR='$color', ASIG=$pzas where id_ordd = $ln";
            $this->queryActualiza();
        }

        return array("status"=>'ok', "msg"=>'Se ha actualizado la informacion');
    }

    function finA($p, $ord, $t){
        $val=1;$sta='ok';$msg="Se ha finalizado correctamente";
        if($t == 'o'){
            $param = " WHERE ID_ORD = $ord group by id_ord";
            //$campos = " sum(pzas) as piezas, sum(asig) as asignado ";
            $param2 = " WHERE ID_ORD = $ord ";
            /// cambiamos el status de la orden a Asignado. 
            $this->query="UPDATE FTC_ALMACEN_ORDEN SET STATUS = 3 WHERE STATUS = 1 AND ID_ORD = $ord";
            $this->queryActualiza();
            $tabla = 1; $tipo='Orden';
        }elseif($t=='l'){
            $param = " WHERE PROD = '$p' and id_ord = $ord group by Prod, id_ord";
            //$campos = "pzas as piezas, asig as asignado";
            $param2 = " WHERE PROD = '$p' and id_ord = $ord ";
            $tabla=2; $tipo='Orden Detalle';
        }elseif($t== 'lin'){
            $param2 = " , OBS = '".$p."' WHERE ID_ORDD = ".$ord;
            $tabla=1; $tipo='Orden';
        }
        //$this->query= "SELECT id_ord, sum(pzas) as piezas, sum(asig) as asignado  FROM FTC_ALMACEN_ORDEN_DET $param";
        //$res=$this->EjecutaQuerySimple();
        //$orden=ibase_fetch_object($res);
        //$val = ($orden->PIEZAS==$orden->ASIGNADO)? 1:0;
        if($val == 1){
            $this->query="UPDATE FTC_ALMACEN_ORDEN_DET SET STATUS = 6 $param2";
            $res=$this->queryActualiza();
            $this->actStatus($tabla, $tipo, "Asignación", ','.$ord, $p);
        }else{
            $sta='no';$msg="Error faltan partidas por asignar";
        }
        return array("status"=>$sta, "msg"=>$msg);
    }

    function delOC($id){
        $mov=0; $sta='no'; $msg="no se encontro el resgitro para elminarlo";
        $this->query="SELECT o.* , (SELECT count(id_log) FROM FTC_ALMACEN_LOG l where tabla= 1 and l.id = o.id_ord) as Logs FROM FTC_ALMACEN_ORDEN o  WHERE ID_ORD = $id";
        $r=$this->EjecutaQuerySimple();
        $row = ibase_fetch_object($r);
        $this->query="UPDATE FTC_ALMACEN_ORDEN SET STATUS= 9 WHERE ID_ORD = $id and status=1";
        $res=$this->queryActualiza();
        if($res == 1){
            $sta= 'ok';
            if($row->STATUS == 1){
                $msg="Se ha eliminado el archivo y sus referencias.";
            }elseif($row->LOGS > 0 ){
                $msg="El registro tiene ".$row->LOGS." movimientos";
                $sta= 'ok';
                $mov= $row->LOGS;
            }
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
        //echo '<p>Entro a la actualizacion:</p>';
        for ($i=0; $i < count($ids) ; $i++) { 
            $this->query="INSERT INTO FTC_ALMACEN_LOG (id_log, usuario, tipo, SUBTIPO, tabla, fecha, status, id, obs) VALUES (null, $usuario, '$tipo', '$sub', $tabla, current_timestamp, 0, $ids[$i], '$obs')";
            //echo '<p>Consulta'.$this->query.'<p/>';
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
        $data=array();
        $this->query="SELECT first 4 m.*, (select
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
            order by m.fecha asc";
        $res=$this->EjecutaQuerySimple();
        while($tsArray=ibase_fetch_object($res)){
            $data[]=$tsArray;
        }
        $sta= count($data)>0? 'ok':'no';
        $pos= $this->posiciones($ordd);
        return array("status"=>$sta,"datos"=>$data, "posiciones"=>$pos);
    }

    function posiciones($ordd){
        $data=array();
        $this->query="SELECT * FROM FTC_ALMACEN_MOV_SALIDA WHERE ID_ORDD=$ordd and status= 'P ' or status = 'F'";
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

            $this->query="INSERT INTO FTC_ALMACEN_MOV_SAL (ID_MS, ID_COMPS, CANT, ID_ORDD, USUARIO, FECHA, STATUS, ID_MOV, PIEZAS, UNIDAD, ID_COMPP) VALUES (NULL, $comps, 0, $ordd, $usuario, current_timestamp, 'P', $surte, $surt, 1, (SELECT COMPP FROM FTC_ALMACEN_COMP WHERE ID_COMP = $comps)) returning ID_MS";
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
        $data=array(); $data2=array();$sta='ok';$msg="Se ha reubcado el componente correctamente";
        if($t==1){
            $this->reasigTarima($idcomp, $comps, 'Componente ', 'Tarima');
        }else{
            $this->query="SELECT * FROM FTC_ALMACEN_COMPP WHERE ID_COMP = $idcomp ";
            $res=$this->EjecutaQuerySimple();
            $rowO=ibase_fetch_object($res);
            $need=$rowO->COMPS - $rowO->COMPS_DISP; 
            $this->query="SELECT * FROM FTC_ALMACEN_COMPP WHERE ID_COMP = $compp";
            $res=$this->EjecutaQuerySimple();
            $rowD=ibase_fetch_object($res);
            $disp=$rowD->COMPS_DISP;
            
            if($disp > $need){
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
        $this->query="SELECT * FROM FTC_ALMACEN_MOV_EXI WHERE TARIMA = $idcompO and disp = 'si'";
        $res=$this->EjecutaQuerySimple();
        while($tsArray=ibase_fetch_object($res)){
            $data[]=$tsArray;
        }
        foreach($data as $mov){
            $this->query="UPDATE FTC_ALMACEN_MOV SET COMPS = $idcompD, COMPP = (SELECT COMPP FROM FTC_ALMACEN_COMP WHERE ID_COMP= $idcompD) where id_am = $mov->ID_AM";
            $this->queryActualiza();
            $this->query="UPDATE FTC_ALMACEN_MOV_SAL SET ID_COMPS = $idcompD, ID_COMPP = (SELECT COMPP FROM FTC_ALMACEN_COMP WHERE ID_COMP= $idcompD) where id_mov = $mov->ID_AM ";
            $this->queryActualiza();
            $this->actStatus($tabla=3, $tipo='Reacomodo', $sub=$c, $ids=$mov->ID_AM, $obs=$t.' Origen: '.$idcompO.' Destino: '.$idcompD);
        }
        return;
    }

    function mapa($opc, $param){
        $data=array();$sec=array();$row=array();
        //$this->query="SELECT * from ftc_almacen_compp $opc";
        //$this->query="SELECT c.*, iif(char_length(c.etiqueta) = 5, substring(c.etiqueta from 1 for 1), '' ) as letra,  char_length(c.etiqueta) from ftc_almacen_comp c where c.almacen = 1 and c.status < 8 and c.tipo = 2";
        $this->query="SELECT c.*,
                        case char_length(c.etiqueta)
                            when 5 then substring(c.etiqueta from 1 for 1)
                            when 6 then substring(c.etiqueta from 1 for 1)
                            when 7 then substring(c.etiqueta from 1 for 2)
                            else ''
                            end as letra,
                        char_length(c.etiqueta)
                        from
                        ftc_almacen_comp c where c.almacen = $param and c.status < 8 and c.tipo = 2";
        $res=$this->EjecutaQuerySimple();
        while($tsArray=ibase_fetch_object($res)){
            $data[]=$tsArray;
        }
        $this->query="SELECT * FROM FTC_ALMACEN_COMPS ";
        $res=$this->EjecutaQuerySimple();
        while($tsArray=ibase_fetch_object($res)){
            $sec[]=$tsArray;
        }
        $this->query="SELECT MAX(COMPS) as tarimas FROM FTC_ALMACEN_COMPP $opc";
        $res=$this->EjecutaQuerySimple();
        $row = ibase_fetch_object($res);
        return array("datos"=>$data, "tarimas"=>$row->TARIMAS, "sec"=>$sec);
    }

    function ingMap($comps, $prod, $uni, $cant, $pzas, $ft , $t){
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
                $res=$this->ingMapTar($d->ID_COMP, $prod, $uni, $cant, $pzas, $folio);
            }
        }elseif($t=='t'){
            $res=$this->ingMapTar($comps, $prod, $uni, $cant, $pzas, $folio);
        }
        $this->query="DELETE FROM FTC_ALMACEN_MOV WHERE MOV = $folio[0] and prod is null ";
        $this->grabaBD();
        return $res;
    }

    function ingMapTar($comps, $prod, $uni, $cant, $pzas, $folio){
        $val = $this->dispComps($comps);
        if($val == 'no'){
            return array("status"=>'no', "msg"=>'No esta dispobible la tarima, favor de actualizar');            
        }    
        $usuario=$_SESSION['user']->ID;$sist='php';$prod = explode(":",$prod );$sta='ok';
        $prod = $prod[0]; $id=$folio[1]; $mov=$folio[0];
        $this->query="INSERT INTO FTC_ALMACEN_MOV (ID_AM, SIST_ORIGEN, ALMACEN, TIPO, USUARIO, FECHA, STATUS, USUARIO_ATT, HORA_I, HORA_F, CANT, PROD, UNIDAD, PIEZAS, MOV, COMPP, COMPS, COLOR) VALUES (
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
            $cant * (SELECT FACTOR FROM FTC_ALMACEN_UNIDADES WHERE ID_UNI = $uni),
            $mov, 
            (SELECT COMPP FROM FTC_ALMACEN_COMP WHERE ID_COMP = $comps), 
            $comps, 
            '') returning id_am";
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
}
?>


<?php

require_once 'app/model/database.php';
require_once('app/fpdf/fpdf.php');
require_once('app/views/unit/commonts/numbertoletter.php');

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
        if($param != ''){
            $param=json_decode($param);
            //print_r( $param);
            foreach ($param as $key => $value) {
                if($key=='t' and $value != 'none'){
                    $p .= ' and ID_TIPO = '.$value.' ';$i++;
                }
                if($key=='a' and $value != 'none'){
                    $p .= ' and ID_ALM = '.$value.' ';$i++;
                }
                if($key=='p' and $value != 'none'){
                    $p .= " and  id_PRODUCTOS CONTAINING('".$value."') ";$i++;
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
        $this->query="SELECT c.*, 
            (SELECT coalesce(SUM(piezas),0) FROM FTC_ALMACEN_MOV AM WHERE AM.COMPS = c.ID_COMP and am.tipo='e' and am.status='F' and c.id_tipo = 1 ) AS entradasS, 
            (SELECT coalesce(SUM(piezas),0) FROM FTC_ALMACEN_MOV AM WHERE AM.COMPS = c.ID_COMP and am.tipo='s' and am.status='F' and c.id_tipo = 1) AS salidasS, 
            (SELECT coalesce(SUM(piezas),0) FROM FTC_ALMACEN_MOV AM WHERE AM.COMPP = c.ID_COMP and am.tipo='e' and am.status='F' and c.id_tipo = 2) AS entradasP, 
            (SELECT coalesce(SUM(piezas),0) FROM FTC_ALMACEN_MOV AM WHERE AM.COMPP = c.ID_COMP and am.tipo='s' and am.status='F' and c.id_tipo = 2) AS salidasP
        FROM FTC_ALMACEN_COMPONENTES c $op $p ";
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
        $this->query="SELECT mov, max(SIST_ORIGEN) AS SIST_ORIGEN, (select max(nombre) from FTC_ALMACEN a where a.id =  MAX(AM.ID_ALMACEN)) AS ALMACEN, MAX(TIPO) AS TIPO, MAX(FECHA) AS FECHA, MAX(STATUS) AS STATUS, MIN(HORA_I) AS HORA_I, MAX(HORA_F) AS HORA_F, SUM(CANT) AS CANT, SUM(PIEZAS) AS PIEZAS  , MAX(usuario) as usuario, cast( list(DISTINCT prod) as varchar (1000)) as prod, (SELECT MAX(ETIQUETA) FROM FTC_ALMACEN_COMPONENTES AC WHERE AC.ID_COMP = max(AM.ID_compp) ) as componente 
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

        $this->query="UPDATE FTC_ALMACEN_MOV SET STATUS = upper('$t') WHERE MOV = $mov ";
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
            $this->query="UPDATE FTC_ALMACEN_MOV SET STATUS = '$status' where id_AM = $idMov and status= 'P'";
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
        if($t = 'm'){
            $cs =substr($cs, 1);
            $param = " in (".$cs.")";
        }else{
            $param = " = ".$cs;
        }
        $this->query="UPDATE FTC_ALMACEN_COMP SET COMPP = $cp where id_comp $param and status = 1";
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
        $this->query="SELECT * FROM FTC_ALMACEN_MOVIMIENTO WHERE ID_COMPP = $opc or ID_COMPS = $opc";
        $res=$this->EjecutaQuerySimple();
        while ($tsArray=ibase_fetch_object($res)) {
            $data[]=$tsArray;
        }
        return $data;
    }

    function datos($t, $al, $c){
        $this->query = "SELECT nombre,  
                                case '$t' when 'e' then 'Entrada' when 's' then 'Salida' when 'r' then 'Reacomodo' when 't' then 'Traspaso' when 'd' then 'Entrada x Devolucion' when 'm' then 'Merma' end as tipo,
                                (SELECT ETIQUETA||'--'||TIPO FROM FTC_ALMACEN_COMPONENTES WHERE ID_COMP = $c) as compp
        FROM FTC_ALMACEN where id = $al";
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
            $this->query="SELECT m.almacen, m.id_comps, m.prod, iif(m.id_tipo ='e', sum(piezas), 0) as entradas, iif(m.id_tipo='s',sum(piezas), 0 ) as salidas
                        from FTC_ALMACEN_MOVimiento m
                        where id_comps=$id and id_status='F' group by m.id_comps, m.prod, m.id_tipo, m.almacen";
                        echo $this->query;
        }elseif($tipo == 'pp'){
            $this->query="SELECT m.almacen, m.id_comps, m.compp, m.comps, m.id_prod, m.id_tipo, iif(m.id_tipo ='e', sum(piezas), 0) as entradas, iif(m.id_tipo='s',sum(piezas), 0 ) as salidas
                        from FTC_ALMACEN_MOVimiento m
                        where id_status='F' group by m.id_comps, m.compp, m.comps, m.id_prod, m.id_tipo, m.almacen order by m.id_comps asc ";
        }
        $res=$this->EjecutaQuerySimple();
        while ($tsArray=ibase_fetch_object($res)){
            $data[]=$tsArray;
        }
        return $data;
    }
}
?>


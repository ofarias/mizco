<?php

require_once 'app/model/database.php';
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
        $p='';$i=0;
        if($param != ''){
            $param=json_decode($param);
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
        return $data;
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


    function movimientos($op){
        $data = array();
        $this->query="SELECT mov, max(SIST_ORIGEN) AS SIST_ORIGEN, (select max(nombre) from FTC_ALMACEN a where a.id =  MAX(AM.ID_ALMACEN)) AS ALMACEN, MAX(TIPO) AS TIPO, MAX(FECHA) AS FECHA, MAX(STATUS) AS STATUS, MIN(HORA_I) AS HORA_I, MAX(HORA_F) AS HORA_F, SUM(CANT) AS CANT, SUM(PIEZAS) AS PIEZAS  , MAX(usuario) as usuario, cast( list(prod) as varchar (500)) as prod, (SELECT MAX(ETIQUETA) FROM FTC_ALMACEN_COMPONENTES AC WHERE AC.ID_COMP = max(AM.ID_compp) ) as componente FROM FTC_ALMACEN_MOVIMIENTO AM $op where status !='B' and status != 'C' group by mov ";
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

    function canMov($mov, $mot){
        $this->query="UPDATE FTC_ALMACEN_MOV SET STATUS = 'C' WHERE MOV = $mov and status != 'F'";
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
            if($res==1){
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
}
?>


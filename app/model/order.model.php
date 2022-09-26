<?php

require_once 'app/model/database.php';
require_once('app/fpdf/fpdf.php');
require_once('app/views/unit/commonts/numbertoletter.php');
require_once 'app/simplexlsx-master/src/SimpleXLSX.php';
require_once('app/Classes/PHPExcel.php');

/* Clase para hacer uso de database */
class orders extends database {

    function regFile($name, $tipo){
        $usuario=$_SESSION['user']->ID;
        $this->query="INSERT INTO FTC_INT_MEDIA (ID_F, ARCHIVO, FECHA, STATUS, USUARIO, TIPO) 
                            VALUES (NULL, '$name', current_timestamp, 0, $usuario, '$tipo') RETURNING id_f;";
        $res=$this->grabaBD();
        $idf = ibase_fetch_object($res)->ID_F;
        echo '<br/> Numero de archivo'.$idf;
        //die;
        return $idf;
    }

    function ordenesWalmart($param){
        $data = array(); $p='';
        if(!empty($param)){
            $p = " and id_file = $param";
        }
        $this->query ="SELECT f.*, (SELECT m.ARCHIVO FROM FTC_INT_MEDIA m WHERE m.ID_F = f.id_file) AS ARCHIVO, (SELECT m.STATUS FROM FTC_INT_MEDIA m WHERE m.ID_F = f.id_file) AS F_STATUS FROM FTC_INT_FACT f where f.id_int_f > 0 $p";
        $res=$this->EjecutaQuerySimple();
        while($tsarray=ibase_fetch_object($res)){
            $data[]=$tsarray;
        }
        return $data;
    }    

    function datosOrden($idint){
        $data = array(); $part =array();
        $this->query ="SELECT * FROM FTC_INT_FACT WHERE ID_INT_F = $idint";
        $res=$this->Ejecutaquerysimple();
        while($tsarray=ibase_fetch_object($res)){
            $data[]=$tsarray;
        }

        $this->query="SELECT * FROM FTC_INT_FACT_PAR WHERE ID = $idint";
        $res=$this->EjecutaQuerySimple();
        while($tsarray=ibase_fetch_object($res)){
            $part[]=$tsarray;
        }
        return array("cabecera"=>$data, "partidas"=>$part);
    }

    function asigDet($idwms, $det){
        for ($i=0; $i < count($det)-1 ; $i++){ 
            $deter=$det['datos'][$i]['id']; $lpe =$det['datos'][$i]['ListaPreciosEsp']; $nombre = $det['datos'][$i]['nombre']; $ftp=$det['datos'][$i]['formaPago'];
            $this->query ="UPDATE FTC_INT_FACT SET ENVIARA = $deter, LISTAPRECIOSESP='$lpe', DET_INTELISIS = '$nombre', FORMAPAGOTIPO = '$ftp' where id_int_f = $idwms";
           $this->queryActualiza();
        }
        return;
    }

    function actDetWms($cte, $det, $comp, $determinante){
        for ($i=0; $i < count($determinante)-1 ; $i++){ 
            $deter=$determinante['datos'][$i]['id']; $lpe =$determinante['datos'][$i]['ListaPreciosEsp']; $nombre = $determinante['datos'][$i]['nombre']; $ftp=$determinante['datos'][$i]['formaPago'];
        }
        $this->query="UPDATE FTC_INT_FACT SET ENVIARA = $det, LISTAPRECIOSESP='$lpe', DET_INTELISIS = '$nombre', FORMAPAGOTIPO = '$ftp' where cliente = '$cte' and comprador = '$comp'";
        $this->queryActualiza();
        return;
    }

    function articulos($idwms){
        $data=array();
        $this->query="SELECT fp.*, (SELECT FIRST 1 val_articulo FROM FTC_INT_VAL_PART V WHERE V.id_int_fp = fp.id_int_fp) as validacion , (SELECT ListaPreciosEsp FROM ftc_int_fact WHERE id_int_f = fp.id ) as listaOrden from ftc_int_fact_par fp where id = $idwms";
        //echo '<br/>'.$this->query;
        $res=$this->EjecutaQuerySimple();
        while($tsarray=ibase_fetch_object($res)){
            $data[]=$tsarray;
        }
        return array("status"=>'ok', "datos"=>$data);
    }

    function archivos($tipo, $param){
        $data=array();
        if(empty($tipo) and empty($param)){
            $p = " and status = 0 ";
        }elseif($tipo == 's' and $param != ''){
            $p = " and status = $param ";
        }else{
            $p = '';
        }
        $this->query="SELECT * FROM FTC_INT_MEDIA WHERE TIPO = 'walmart' $p";
        $res=$this->Ejecutaquerysimple();
        while($tsarray=ibase_fetch_object($res)){
            $data[]=$tsarray;
        }
        return $data;
    }

    function chgSta($file, $sta){
        $sta= $sta=='can'? 9:1;
        $this->query="UPDATE FTC_INT_MEDIA SET STATUS = $sta where id_f = $file";
        $this->queryActualiza();
        return array("status"=>'ok');
    }

}?>



    
    
        
    
    
    
    
    
    
    
    
    
    
    
    
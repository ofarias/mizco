<?php 

abstract class sqlbasetest {

    //private static $usr = "php_mizco";
    //private static $pwd = "4phP2018+";
    private static $usr = "sa";
    private static $pwd = "genseg01+";
    
    private $cnx;
    protected $query;
    //private $host = "localhost";
    private $host = '192.168.0.184\\';
    //private $dataBase="MizcoPruebas";
    //private $host = "ofa.dyndns.org:c:\\ftcData\\SAE\\MIZCOSAE.FDB"

    private function AbreCnx(){
        $connectionInfo= array("Database"=>'Mizco', "UID"=>'sa',"PWD"=>'genseg01+',"CharacterSet"=>'UTF-8');
        //$connectionInfo= array("Database"=>'MizcoPruebas', "UID"=>'php_mizco',"PWD"=>'4phP2018+',"CharacterSet"=>'UTF-8');
        $this->cnx = sqlsrv_connect($this->host, $connectionInfo);
        if(!$this->cnx){   
            echo "Fallo la conexion";
            die(print_r(sqlsrv_errors(), true));
        }
    }
    
    private function CierraCnx() {
        sqlsrv_close($this->cnx);
    }

    #Ejecuta un query simple del tipo INSERT, DELETE, UPDATE
    protected function EjecutaQuerySimple() {
        $this->AbreCnx();
        $rs=sqlsrv_query($this->cnx, $this->query);
        if($rs === false) {
           die( print_r( sqlsrv_errors(), true) );
        }
        return $rs;
        unset($this->query);
        $this->CierraCnx();
    }

     protected function grabaBD(){
            $this->AbreCnx();
            $rs = sqlsrv_query($this->cnx, $this->query);
            unset($this->query);
            $this->CierraCnx();
            return $rs;
        }

    #Ejecuta query de tipo SELECT

    protected function QueryObtieneDatos() {
        $this->AbreCnx();
        //echo $this->query;
        $rs = sqlsrv_query($this->cnx, $this->query);
        return $this->sqlsrv_fetch_array($rs);
        //var_dump($rs);
        //echo $this->query;
        unset($this->query);
        $this->CierraCnx();
    }

    protected function QueryObtieneDatosN() {
        $this->AbreCnx();
        //echo $this->query;
        $rs = sqlsrv_query($this->cnx, $this->query);
        return $rs;
        //var_dump($rs);
        //echo $this->query;
        unset($this->query);
        $this->CierraCnx();
    }

    protected function QueryDevuelveAutocomplete() {
        $this->AbreCnx();
        $rs = sqlsrv_query($this->cnx, $this->query);
        while ($row = sqlsrv_fetch_object($rs)) {
            $row->CLAVE = htmlentities(stripcslashes($row->CLAVE));
            $row->NOMBRE = htmlentities(stripcslashes($row->NOMBRE));
            //$row_set[] = $row->CLAVE;
            $row_set[] = $row->CLAVE . " : " . $row->NOMBRE;
        }
        return $row_set;
        unset($this->query);
        $this->CierraCnx();
    }

    protected function QueryDevuelveAutocompleteP() {
        $this->AbreCnx();
        $rs = sqlsrv_query($this->cnx, $this->query);
        while ($row = sqlsrv_fetch_object($rs)) {
            $row->CVE_ART = htmlentities(stripcslashes($row->CVE_ART));
            $row->DESCR = htmlentities(stripcslashes($row->DESCR));
            //$row_set[] = $row->CLAVE;
            $row_set[] = $row->CVE_ART . " : " . $row->DESCR;
        }
        return $row_set;
        unset($this->query);
        $this->CierraCnx();
    }

    #Obtiene la cantidad de filas afectadas en BD

    function NumRows($result) {
        if (!is_resource($result))
            return false;
        return sqlsrv_fetch_array($result);
    }

    #Regresa arreglo de datos asociativo, para mejor manejo de la informacion
    #Comprueba si es un recurso el cual se compone de

    function FetchAs($result) {
        if (!is_resource($result))
            return false;
        return sqlsrv_fetch_object($result); //cambio de fetch_assoc por fetch_row
    }


    
}

?>
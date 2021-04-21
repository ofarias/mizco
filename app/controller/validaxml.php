<?php

require_once('lib/nusoap.php');


$oSoapClient = new nusoap_client('http://serviciosweb.soriana.com/RecibeCfd/wseDocRecibo.asmx?wsdl', true);


$xml = file_get_contents('./xml/IMI161007SY7FF00144.xml');
//parametros a enviar, deben ser en array
$param = array('XMLCFD' => $xml);

$oSoapClient->loadWSDL();
//en call colocamos el nombre del metodo a usar
$respuesta = $oSoapClient->call("RecibeCFD", $param);
if ($oSoapClient->fault) {
    echo 'No se pudo completar la operación ' . $oSoapClient->getError();
    die();
} else { // No
    $sError = $oSoapClient->getError();
    if ($sError) {
        echo 'Error!:' . $sError;
    }
}
echo '<br>';
echo '<pre>';
print_r($respuesta);
echo '</pre>';

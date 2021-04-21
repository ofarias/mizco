<?php
    require_once('app/mailer/class.phpmailer.php'); // Contiene las funciones para envio de correo
    require_once('app/mailer/class.smtp.php'); // Envia correos mediante servidores SMTP
    
    $mail = new PHPMailer(true); // Se crea una instancia de la clase phpmailer
    $mail->IsSMTP(); // Establece el tipo de mensaje html
    $info=$_SESSION['info'];
    $mensaje = "<p>";
    $contacto = "";
    $asunto = "Solicitud de Factura Walmart: ";//.$folio;  
    $mensaje.= "<p>Se ha generado una solicitud de facturacion del portal apolo para Walmart ventas en linea.</p>";
    $i=0; $b='';
    foreach ($info as $k){
        $i++;
        $mensaje.="<b>Factura ".$i.": </b>".$k->ARCHIVO;
        $mensaje.="<p><b>RFC CLiente: </b>".$k->RCF_CLIENTE."</p>";
        $mensaje.="<p><b>Pais Cliente: </b>".$k->CLAVE_PAIS.' ('.utf8_decode($k->PAIS).')<p/>';
        $mensaje.="<p><b>Forma de Pago: </b>".$k->FORMA_PAGO.' ('.utf8_decode($k->FORMA_PAGO_DES).')<p>';
        $mensaje.="<p><b>Metodo de Pago: </b>".$k->METODO_PAGO.' ('.utf8_decode($k->METODO_PAGO_DESC).')<p>';
        $mensaje.="<p><b>Uso de CFDI: </b>".$k->USO_CFDI.' ('.$k->USO_CFDI_DESC.')<p>';
        $mensaje.="<p><b>Total en Letras: </b>".' ('.$k->IMP_LETRAS.')<p>';
        $mensaje.="<p><b>Partidas </b></p>";
        foreach ($info as $p){
            if($p->ARCHIVO == $k->ARCHIVO){
                $mensaje.="<table border='1'>
                            <thead>
                                <tr>
                                    <th>Cantidad</th>
                                    <th>Unitario</th>
                                    <th>Producto</th>
                                    <th>Descripcion</th>
                                    <th>SubTotal</th>
                                    <th>Importe</th>
                                    <th>Unidad Desc</th>
                                    <th>Clave Unidad</th>
                                    <th>No. Identificador</th>
                                    <th>Clave Prod/Serv</th>
                                    <th>Descuento</th>
                                    <th>SubTotal</th>
                                    <th>IVA</th>
                                    <th>Total</th>
                                <tr>
                            </thead
                            <tbody>
                                <tr>
                                    <td align='center'>".$p->CANTIDAD."</td>
                                    <td align='center'>".number_format($p->UNITARIO,2)."</td>
                                    <td align='center'>".$p->PRODUCTO."</td>
                                    <td align='center'>".$p->DESCRIPCION."</td>
                                    <td align='center'>".number_format($p->SUBTOTAL,2)."</td>
                                    <td align='center'>".number_format($p->IMPORTE,2)."</td>
                                    <td align='center'>".$p->UNIDAD_DESC."</td>
                                    <td align='center'>".$p->UNIDAD_CLAVE."</td>
                                    <td align='center'>".$p->NO_IDEN."</td>
                                    <td align='center'>".$p->CLAVE_PROD."</td>
                                    <td align='center'>".number_format($p->DESCUENTO,2)."</td>
                                    <td align='center'>".number_format($p->SUBTOTAL,2)."</td>
                                    <td align='center'>".number_format($p->IVA,2)."</td>
                                    <td align='center'>".number_format(($p->IVA + $p->SUBTOTAL) - $p->DESCUENTO,2)."</td>
                                </tr>
                            </tbody>
                           </table>";
            }
        }
    }

    $mensaje.= "<p>Gracias por su atencion<br/></p>";
    $correo='genseg@hotmail.com';//'esther@selectsound.com.mx';
    try {
        $mail->Username   = "facturacion@ftcenlinea.com";  // Nombre del usuario SMTP
        $mail->Password   = "elPaso35+";            // Contraseña del servidor SMTP
        $mail->AddAddress($correo);      //Direccion a la que se envia
        $mail->AddAddress('genseg@hotmail.com');
        $mail->SetFrom('facturacion@ftcenlinea.com' , "Servicio de Informacion FTC"); // Esccribe datos de contacto
        $mail->Subject = $asunto;
        $mail->AltBody = 'Para ver correctamente este mensaje, por favor usa un manejador de correo con compatibilidad HTML !'; // optional - MsgHTML will create an alternate automatically
        //$mail->AddAttachment(realpath('C:\\xampp\\htdocs\\Aperak\\Aperak_WS_'.$folio.'.pdf'),'Aperak_WS_'.$folio.'.pdf','base64','application/pdf');
        //$mail->AddAttachment(realpath('C:\\xampp\\htdocs\\Aperak\\Aperak_WS_'.$folio.'.xml'),'Aperak_WS_'.$folio.'.xml');
        $mail->MsgHTML($mensaje);
        $mail->Send();
     } catch (phpmailerException $e) {
        echo $e->errorMessage(); //Pretty error messages from PHPMailer
     } catch (Exception $e) {
        echo $e->getMessage(); //Boring error messages from anything else!
     }
 ?>
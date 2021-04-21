<?php
    require_once('app/mailer/class.phpmailer.php'); // Contiene las funciones para envio de correo
    require_once('app/mailer/class.smtp.php'); // Envia correos mediante servidores SMTP
    
     $mail = new PHPMailer(true); // Se crea una instancia de la clase phpmailer
     $mail->IsSMTP(); // Establece el tipo de mensaje html
 
     
     $exec = $_SESSION['exec'];
     $titulo = $_SESSION['titulo'];
     $mensaje = "<p>";
     $contacto = "";
     foreach ($exec as $data):
 
        $correo = $data->MAIL;
         if (strpos($correo, "@")>1){
             $mensaje = 'Tipo de documento : ' . $data->TIPO;
             $mensaje.= '<br />Documento : ' . $data->DOCUMENTO;
             $mensaje.= '<br />Beneficiario : ' . $data->BENEFICIARIO;
             $contacto = $data->BENEFICIARIO; 
            $mensaje.= '<br />Fecha documento : ' . $data->FECHA_DOC;
             $mensaje.= '<br />Vencimiento : ' . $data->VENCIMIENTO;
             $promesaPago = $data->PROMESA_PAGO;
             $mensaje.= '<br />Fecha Promesa de pago : ' . $data->PROMESA_PAGO;
             $mensaje.= '<br />Monto : ' . number_format($data->MONTO, 2, '.', ',').'</p>';
         } else {
             echo "No se ha localizado el correo electr&oacute;nico a quien enviar. No se va a enviar el correo.";
             return;
         }
     endforeach;
 
     $asunto = "Contrarecibo Ferretera Pegaso.";  
     $mensaje.= "<p>Le informamos que hemos creado una cuenta por pagar a nombre de $contacto con una fecha estiamda de pago del $promesaPago.</p>";
     $mensaje.= "<p>Gracias por su confianza<br />Atentamente Ferretera Pegaso</p>";
     try {
         
         $mail->Username   = "cxpferreterapegaso@gmail.com";  // Nombre del usuario SMTP
         $mail->Password   = "genseg89+";            // Contraseña del servidor SMTP
         $mail->AddAddress($correo, $contacto);      //Direccion a la que se envia
         $mail->SetFrom('cxpferreterapegaso@gmail.com' , "Pegaso. Cuentas por pagar"); // Esccribe datos de contacto
         $mail->Subject = $asunto;
         $mail->AltBody = 'Para ver correctamente este mensaje, por favor usa un manejador de correo con compatibilidad HTML !'; // optional - MsgHTML will create an alternate automatically
         $mail->MsgHTML($mensaje);
         $mail->Send();
     } catch (phpmailerException $e) {
        echo $e->errorMessage(); //Pretty error messages from PHPMailer
     } catch (Exception $e) {
        echo $e->getMessage(); //Boring error messages from anything else!
     }
 ?>
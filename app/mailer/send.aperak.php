<?php
    require_once('app/mailer/class.phpmailer.php'); // Contiene las funciones para envio de correo
    require_once('app/mailer/class.smtp.php'); // Envia correos mediante servidores SMTP
    
    $mail = new PHPMailer(true); // Se crea una instancia de la clase phpmailer
    $mail->IsSMTP(); // Establece el tipo de mensaje html
    $folio=$_SESSION['folio'];
    $mensaje = "<p>";
    $contacto = "";
    $asunto = "Aperak: ".$folio;  
    $mensaje.= "<p>Le informamos el Resultado del WebService Con Soriana.</p>";
    $mensaje.= "<p>Gracias por su confianza<br/></p>";
    $correo='genseg@hotmail.com';//'esther@selectsound.com.mx';
    try {
        $mail->Username   = "facturacion@ftcenlinea.com";  // Nombre del usuario SMTP
        $mail->Password   = "elPaso35+";            // Contraseña del servidor SMTP
        $mail->AddAddress($correo);      //Direccion a la que se envia
        $mail->AddAddress('esther@selectsound.com.mx');
        $mail->SetFrom('facturacion@ftcenlinea.com' , "Servicio de Informacion FTC"); // Esccribe datos de contacto
        $mail->Subject = $asunto;
        $mail->AltBody = 'Para ver correctamente este mensaje, por favor usa un manejador de correo con compatibilidad HTML !'; // optional - MsgHTML will create an alternate automatically
        $mail->AddAttachment(realpath('C:\\xampp\\htdocs\\Aperak\\Aperak_WS_'.$folio.'.pdf'),'Aperak_WS_'.$folio.'.pdf','base64','application/pdf');
        $mail->AddAttachment(realpath('C:\\xampp\\htdocs\\Aperak\\Aperak_WS_'.$folio.'.xml'),'Aperak_WS_'.$folio.'.xml');
        $mail->MsgHTML($mensaje);
        $mail->Send();
     } catch (phpmailerException $e) {
        echo $e->errorMessage(); //Pretty error messages from PHPMailer
     } catch (Exception $e) {
        echo $e->getMessage(); //Boring error messages from anything else!
     }
 ?>
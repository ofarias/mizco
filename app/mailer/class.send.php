<?php
    require_once('class.phpmailer.php'); // Contiene las funciones para envio de correo
    require_once('class.smtp.php'); // Envia correos mediante servidores SMTP
    $mail = new PHPMailer(true); // Se crea una instancia de la clase phpmailer
    $mail->IsSMTP(); // Establece el tipo de mensaje html
	$asunto = "Contrarecibo Ferretera Pegaso.";
	$mensaje = "<p>Le informamos que hemos creado una cuenta por pagar a nombre de $beneficiario con una fecha estiamda de pago del $promesaPago.</p>";
	$mensaje.= "<p>Gracias por su confianza<br />Atentamente Ferretera Pegaso</p>"
    try {
        $mail->Host       = "smtp.googlemail.com";// Establece servidor SMTP
        $mail->SMTPDebug  = 0;                    // enables SMTP debug information (for testing)
        $mail->SMTPAuth   = true;                 // enable SMTP authentication
        $mail->SMTPSecure = "tls";                // sets the prefix to the servier
        $mail->Port       = 587;                  // Establece el puerto por defecto del servidor SMTP
        $mail->Username   = "cxpferreterapegaso@gmail.com";  // Nombre del usuario SMTP
        $mail->Password   = "genseg89+";          // Contraseña del servidor SMTP
//        $mail->AddReplyTo("prga0@tsmi.com.mx", ""); //Responder a
        $mail->AddAddress($correo, $contacto); //Direccion a la que se envia
        $mail->SetFrom('cxpferreterapegaso@gmail.com' , $pseudofrom); // Esccribe datos de contacto
        $mail->Subject = $asunto;
        $mail->AltBody = 'Para ver correctamente este mensaje, por favor usa un manejador de correo con compatibilidad HTML !'; // optional - MsgHTML will create an alternate automatically
        $mail->MsgHTML($mensaje);
        $mail->Send();
?>
        <script language = "javascript" type = "text/javascript">
            <!--
            setTimeout("window.close();", 100);
            -->
        </script>
<?php
    }
    catch (phpmailerException $e) {
       echo $e->errorMessage(); //Pretty error messages from PHPMailer
    }
    catch (Exception $e) {
       echo $e->getMessage(); //Boring error messages from anything else!
    }
?>
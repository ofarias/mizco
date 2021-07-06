<?php
    require_once('app/mailer/class.phpmailer.php'); // Contiene las funciones para envio de correo
    require_once('app/mailer/class.smtp.php'); // Envia correos mediante servidores SMTP
    
    $mail = new PHPMailer(true); // Se crea una instancia de la clase phpmailer
    $status=array("status"=>'ok');
    $mail->IsSMTP(); // Establece el tipo de mensaje html
    $correos  = explode(",", $_SESSION['correos']);
    $correosP =$_SESSION['correosP'];
    $archivos = explode(",", substr($_SESSION['archivos'],1));
    $msg      = $_SESSION['msg'];
    $mensaje = "<p>";
    $asunto = " Envio de Ordenes de compra: ";  
    $mensaje.= "<p>".$msg.".</p>";
    $mensaje.= "<p>Gracias <br/></p>";
    try {
        $mail->SMTPSecure = 'tls'; 
        $mail->Host = 'smtp.gmail.com'; 
        $mail->Port = '587'; 
        $mail->Username   = "oc.selectsound@gmail.com";  // Nombre del usuario SMTP
        $mail->Password   = "genseg21+";            // Contraseña del servidor SMTP
        if(count($correos)>1){
            echo 'Obtiene correos';
            for ($i=0; $i < count($correos); $i++) { 
                $mail->AddAddress($correos[$i]);
            }
        }
        foreach ($correosP as $key) {
            $mail->AddAddress($key->CORREO, $key->NOMBRE);
        }
        $mail->SetFrom('oc.selectsound@gmail.com' , "Servicio de Informacion de Ordenes de compra SelectSound "); // Esccribe datos de contacto
        $mail->Subject = $asunto;
        $mail->AltBody = 'Para ver correctamente este mensaje, por favor usa un manejador de correo con compatibilidad HTML !'; // optional - MsgHTML will create an alternate automatically
        for ($i=0; $i < count($archivos); $i++) { 
            $mail->AddAttachment(realpath('C:\\xampp\\htdocs\\Cargas Ordenes\\'.$archivos[$i]), $archivos[$i],'base64','application/excel'); 
        }
        $mail->MsgHTML(utf8_decode($mensaje));
        $mail->Send();
     } catch (phpmailerException $e) {
        echo $e->errorMessage(); //Pretty error messages from PHPMailer
        $status = array("status"=>'no');
     } catch (Exception $e) {
        echo $e->getMessage(); //Boring error messages from anything else!
        $status = array("status"=>'no');
     }
     return $status;
 ?>
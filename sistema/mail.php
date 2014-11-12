<?php
//header("Content-Type: text/plain; charset=ISO-8859-1");
header('Content-Type: text/html; charset=UTF-8'); 
require("../mvc/apps/phpMailer/PHPMailerAutoload.php");
error_reporting(-1);
date_default_timezone_set('America/Mexico_City');setlocale(LC_ALL, "es_MX");

class mail
{
public $para = "";
public $mensaje="";
public $asunto="";
private $mail;
	
	
 
 
public function __construct()
  {
    $this->asunto="Requisición de acceso";
	$this->mail=new PHPMailer;
	$this->mail->isSMTP();                                      // Set mailer to use SMTP
$this->mail->Host = "smtp.gmail.com";  // Specify main and backup SMTP servers
$this->mail->SMTPAuth = true;                               // Enable SMTP authentication
$this->mail->Username = 'tnt.galicia@gmail.com';                 // SMTP username
$this->mail->Password = 'GARE900811';                           // SMTP password
$this->mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
$this->mail->Port = 465;                                   // TCP port to connect to

$this->mail->From = 'sistema@arualmr.com';
$this->mail->FromName = 'Sistema ARUAL Medicina de Reanimación';
//$this->mail->addAddress('esteban.galicia@axa-assistance.com.mx');     // Add a recipient
//$this->mail->addAddress('ellen@example.com');               // Name is optional
//$this->mail->addReplyTo($this->mail->Username, 'Sistema ARUAL');
// $this->mail->addCC('cc@example.com');
// $this->mail->addBCC('bcc@example.com');

$this->mail->WordWrap = 50;                                 // Set word wrap to 50 characters
// $this->mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
// $this->mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
$this->mail->isHTML(true);                                  // Set email format to HTML

$this->mail->Subject = '';
$this->mail->Body    = 'SinNada';
$this->mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
  } 
  
public function gen_uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}  
  
	public function enviar(){
	$this->mail->Subject=$this->asunto;
	$this->mail->Body=$this->mensaje;
	$this->mail->AltBody=$this->mensaje;
	$this->mail->addAddress($this->para);
	$this->mail->CharSet = 'UTF-8';
		if(($this->mail->Body!="")&&($this->para!="")){
			if(!$this->mail->send()) {
				echo 'error';
				echo 'Mailer Error: ' . $this->mail->ErrorInfo;
			} else {
				return '1';
			}
		} 
	}
}
// //MOSTRANDO EL USO
// $eMail = new mail();
// $eMail->para="esteban.galicia@axa-assistance.com.mx";
// // $eMail->mensaje=$eMail->gen_uuid();
// $mensaje=file_get_contents('../pages/emailAcceso.html');
// $eMail->mensaje=str_ireplace('{GUI}',$eMail->gen_uuid(),$mensaje); 
// echo $eMail->enviar();
 
?>
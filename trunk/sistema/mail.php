<?php
//header("Content-Type: text/plain; charset=ISO-8859-1");
header('Content-Type: text/html; charset=UTF-8'); 
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
	try {
$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
$cabeceras .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
$cabeceras .= 'From: Sistema ARUAL <sistemaArual@arualmr.com>' . "\r\n"; 
 
// enviamos el correo!
		if(($this->mensaje!="")&&($this->para!="")){
			if(!mail($this->para, $this->asunto, $this->mensaje, $cabeceras)) {
				echo 'error';
			} else {
				return '1';
			}
		} 
	
	} catch (Exception $e) {
	  echo $e->getMessage(); //Boring error messages from anything else!
	}
}

}
// //MOSTRANDO EL USO
 // $eMail = new mail();
 // $eMail->para="esteban.galicia@axa-assistance.com.mx";
// // // $eMail->mensaje=$eMail->gen_uuid();
 // $mensaje=file_get_contents('../pages/emailAcceso.html');
 // $eMail->mensaje=str_ireplace('{GUI}',$eMail->gen_uuid(),$mensaje); 
 // echo $eMail->enviar();
 
?>
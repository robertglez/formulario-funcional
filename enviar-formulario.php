<?php
require 'phpmailer/Exception.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if($_POST==null){
    header("Location: ".$_SERVER['HTTP_REFERER']);
    exit(); // Evitamos el acceso directo a esta url
}

//Funcion que formatea el mensaje de error en formato json y sale del script
function Error($mensaje){
	echo '{"status": "401", "mensaje": "'.$mensaje.'"}'; 
  	exit;
}

//Funcion para validar los datos
function ValDato($dato){
  	$dato = trim($dato);        //Eliminar espacios a los lados
  	$dato = strip_tags($dato);//Eliminar etiquetas HTML
  	return $dato;
}

// Recolección y validación de valores del formulario
if(isset($_POST['nombre']))
  	$nombre = ValDato($_POST['nombre']);

if(isset($_POST['apellidos']))
  	$apellidos = ValDato($_POST['apellidos']);

if(isset($_POST['email']))
  	$email = ValDato($_POST['email']);

if(isset($_POST['telefono']))
  	$telefono = ValDato($_POST['telefono']);

if(isset($_POST['comentarios']))
  	$comentarios = ValDato($_POST['comentarios']);

//Se utiliza la funcion filter_var para validar el correo
if(!filter_var($email, FILTER_VALIDATE_EMAIL))
  	Error('El correo no es válido.');

//Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
	//Server settings
	//$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
	$mail->isSMTP();                                            //Send using SMTP
	$mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
	$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
	$mail->Username   = 'TU_EMAIL@gmail.com';                     //SMTP username
	$mail->Password   = 'TU_CONTRASEÑA';                               //SMTP password
	$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
	$mail->Port       = 587;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

	//Recipients
	$mail->setFrom('TU_EMAIL@gmail.com', 'TU_NOMBRE');
	$mail->addAddress('E-MAIL_DESTINO');               // Name is optional
	//$mail->addReplyTo('E-MAIL_AL_QUE_RESPONDER', 'NOMBRE_DEL_EMAIL_AL_QUERESPONDER');
	//$mail->addCC('E-MAIL_EN_COPIA');
	//$mail->addBCC('E-MAIL_EN_COPIA_OCULTA');


	//Content
	$mail->isHTML(true);                                  //Set email format to HTML
	$mail->Subject = "Formulario de registro de " . $email;
	$mail->Body    = "Nombre: " . $nombre .
					 "<br>Apellidos: " . $apellidos .
					 "<br>Email: " . $email .
					 "<br>Teléfono: " . $telefono .
					 "<br>Comentarios: " . $comentarios;
	//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

	$mail->send();
	  echo '{"status": "200", "mensaje": "OK"}';  // Retorno 200 - OK
} catch (Exception $e) {
	Error("El mensaje no ha podido ser enviado");
}



?>

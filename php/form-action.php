<?php
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	require 'Exception.php';
	require 'PHPMailer.php';
	require 'SMTP.php';

	if(empty($_POST["name"]) or empty($_POST["email"]) or empty($_POST["message"]) or empty($_POST["tel"]) or !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
			echo "<script>alert('Error, probá otra vez!');window.location.replace('http://example.com//index.html');</script>";
			//print_r(error_get_last());
			die();
	}

	$name = $_POST["name"];
	$email = $_POST["email"];
	$tel = $_POST["tel"];
	$message = $_POST["message"];

	//----------------------------Empieza CAPTCHA----------------------------
	$secretKey = "YOUR-SECRET-KEY";
	$responseKey = $_POST["g-recaptcha-response"];
	$userIP = $_SERVER["REMOTE_ADDR"];
	$url = "https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$responseKey."&remoteip=".$userIP."";

	$response = file_get_contents($url);
	$response = json_decode($response);

	if($response->success){

	//----------------------------SI EL CAPTCHA TIENE ÉXITO, PROSIGUE//----------------------------

	$emailBody = "Nombre: ".$name." | E-mail: ".$email." | Teléfono: ".$tel." | Mensaje: ".$message;

	//----------------------------Empieza PHPMailer----------------------------------------------------
	$mail = new PHPMailer(true);
	try{
		$mail->setFrom($email, $name.' | Example Landing Formulario de Contacto');
		$mail->addAddress('example.com', 'Example');
		$mail->Subject = "Contacto | Landing";
		$mail->Body = $emailBody;
		$mail->isSMTP();
   		$mail->Host = //Here goes the server address (example 'smtp.gmail.com').
   		$mail->SMTPAuth = TRUE;
  		$mail->SMTPSecure = 'tls';
   		$mail->Username = 'YOUR E-MAIL';
   		$mail->Password = 'YOUR PASSWORD';
   		$mail->CharSet = 'UTF-8';
   		$mail->Port = 587;if($mail->send()){
			echo "<script>window.location.replace('http://example.com//gracias.html');</script>";
			die();
		}else{
			echo "<script>alert('Error, probá otra vez!');window.location.replace('http://example.com//index.html');</script>";
			//print_r(error_get_last());
			die();
		}
	}catch (Exception $e){
		echo $e->errorMessage();
	}catch (\Exception $e){
		echo $e->getMessage();
	}
	//----------------------------Termina PHPMailer----------------------------------------------------
	
	//----------------------------Continua condicional CAPTCHA----------------------------
	}else{
		echo "<script>alert('Por favor, completá el CAPTCHA.');window.location.replace('http://example.com//index.html');</script>";
			//print_r(error_get_last());
			die();
	}
?>
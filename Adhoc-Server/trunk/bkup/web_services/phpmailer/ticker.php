<?php

include_once("../class.phpmailer.php");
//include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

$mail             = new PHPMailer();

$body             = $mail->getFile('contents.html');
$body             = eregi_replace("[\]",'',$body);

$mail->IsSMTP();
$mail->SMTPAuth   = true;                  // enable SMTP authentication
//$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
$mail->Host       = "smtp.tickerfriends.com";      // sets GMAIL as the SMTP server
$mail->Port       = 25;                   // set the SMTP port for the GMAIL server

$mail->Username   = "donotreply@tickerfriends.com";  // GMAIL username
$mail->Password   = "rushiraj";            // GMAIL password

$mail->AddReplyTo("hiren.vekariya@etatvasoft.com","Hiren Vekariya");

$mail->From       = "donotreply@tickerfriends.com";
$mail->FromName   = "donotreply@tickerfriends.com";

$mail->Subject    = "PHPMailer Test Subject";

//$mail->Body       = "Hi,<br>This is the HTML BODY<br>";                      //HTML Body
$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
$mail->WordWrap   = 50; // set word wrap

$mail->MsgHTML($body);

$mail->AddAddress("hiren.vekariya@etatvasoft.com", "Hiren Vekariya");

//$mail->AddAttachment("images/phpmailer.gif");             // attachment

$mail->IsHTML(true); // send as HTML

if(!$mail->Send()) {
  echo "Mailer Error: " . $mail->ErrorInfo;
} else {
  echo "Message sent!";
}
?>

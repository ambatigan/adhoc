<?php 

function send_mail($to, $subject, $body, $attach_array = array()) {
    require("class.phpmailer.php");
    $mailer = new PHPMailer();
    $mailer->IsSMTP();
    $mailer->Host = 'smtp.gmail.com';
    $mailer->Port = 465;
    $mailer->SMTPSecure = "ssl";                 // sets the prefix to the servier
	/*$ccArr = explode(";",CC_EMAIL);
	foreach($ccArr as $cc){
		if(!empty($cc)){
			$mailer->AddCC($cc);
		}
	}*/	
    $mailer->SMTPAuth = TRUE;
    $mailer->Username = 'test.acc8888@gmail.com';  // Change this to your gmail adress
    $mailer->Password = 'tatva8888';  // Change this to your gmail password
    $mailer->From = FROM_EMAIL;  // This HAVE TO be your gmail adress
    $mailer->FromName = FROM_NAME; // This is the from name in the email, you can put anything you like here
    $mailer->Body = $body;
    $mailer->Subject = $subject;
	/*Modified by Badani*/
	if(!empty($attach_array))
	{
		foreach($attach_array as $key=>$val)
			$mailer->AddAttachment($val);// attachment
	}
	
    $mailer->IsHTML(true);
    //$mailer->to = $to;
    $mailer->AddAddress($to);  // This is where you put the email adress of the person you want to mail
    if (!$mailer->Send()) {
        //echo "Message was not sent<br/ >";
        //echo "Mailer Error: " . $mailer->ErrorInfo;
    }
}
?>
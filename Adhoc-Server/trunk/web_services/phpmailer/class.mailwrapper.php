<?php

include_once('class.phpmailer.php');

class MailWrapper extends PHPMailer {

    function __construct($mail_method=null){
        $mail_method =($mail_method==null)?'smtp':$mail_method;
        if($mail_method == 'smtp'){
          $this->IsSMTP();
          $this->Host       = "192.168.10.2";//"smtpout.secureserver.net";//"mail.tatvasoft.com";//"mail.etatvasoft.com"; "smtpout.secureserver.net";// SMTP server
          $this->SMTPAuth  = true;
          $this->Username  = "mahesh.satdev@sparsh.com";//"isha.shukla@tatvasoft.com";//"admin@tatvasoft.com";//"isha.shukla@etatvasoft.com";
          $this->Password  = "mahesh123";//"";
          $this->Port       = 25;
        }elseif($mail_method == 'sendmail'){
          $this->IsSendmail();
        }elseif($mail_method == 'gmail'){
          $this->IsSMTP();
          $this->SMTPAuth   = true;                  // enable SMTP authentication
          $this->SMTPSecure = "ssl";                 // sets the prefix to the servier
          $this->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
          $this->Port       = 465;                   // set the SMTP port

          $this->Username   = "tatvasoft@gmail.com";  // GMAIL username
          $this->Password   = "tatva1234";            // GMAIL password
        }

        //setting default fromname we can always change it to anything
        //why here is just because so that we dont need to change setting in core phpmailer classes while copying to other project
        $this->From       = "isha.shukla@etatvasoft.com";
        $this->FromName   = "Tester";
    }

    /*
    This is basic wrapper for preparing html body and can be changed as per requirement
     for e.g.
     adding path of filename
     , managing html_string rather than file
     ,filtering some specific tags of html or some text is prohibited
     ,or just pass type of mail n all replacements are listed here etc..
    */
    function prepareHTMLBody($filename,$options=array()){
        $filepath = dirname(dirname(__FILE__)).'/emailtemplate/';
        $body  = $this->getFile($filepath.$filename);
        return eregi_replace("[\]",'',$body);
    }

}
?>
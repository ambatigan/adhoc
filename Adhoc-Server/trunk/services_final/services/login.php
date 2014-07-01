<?php


include('config.php');

include_once(SITE_PATH . 'services_jenil/nusoap/nusoap.php');
include_once(SITE_PATH . 'services_jenil/dbcredentials.php');
include_once(SITE_PATH . 'services_jenil/mysql.php');

$server = new nusoap_server();
$server->xml_encoding = "UTF-8";
$server->soap_defencoding = "UTF-8";
$server->configureWSDL('hellowsdl2', 'urn:hellowsdl2');

// Register the method to expose
$server->wsdl->addComplexType(
    'msgdata',
    'complexType',
    'array',
    'all',
    ''
);



$server->wsdl->addComplexType(
 'login',
 'complexType',
 'array',
 'all',
 ''
);



//loading messages for PDF//
function login($username,$password)
{
	 $query = sprintf("select * from user where username= '$username' and password= '$password' ");

	 $result = mysql_query($query) or throw_ex();
	 if(mysql_num_rows($result)==0)
	 {
	 	return false;
	 }

  	$res = mysql_query($query);
  
    if(mysql_fetch_assoc($res))
    {
		$message = 'Login success';
    }
	else
	{
		$message = 'Invalid username or password';
	}
    header('Content-type: application/json');
    return json_encode(array($message));

}

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);
exit();
?>
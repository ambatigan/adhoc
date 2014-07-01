<?php
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database*/
define('DB_NAME', 'scanner_app');

/** MySQL database username */
define('DB_USER', 'phpteam');

/** MySQL database password */
define('DB_PASSWORD', 'phpteam');

/** MySQL hostname */
define('DB_HOST', 'localhost');

define('SITE_URL',  '/scanner_app/web_services/');
define('SITE_PATH', $_SERVER["DOCUMENT_ROOT"] . SITE_URL);
//define('PRODUCT_IMG_PATH','http://'.$_SERVER["HTTP_HOST"].IMG_URL);

define('IMG_URL',  '/scanner_app/uploads/products/');
define('PRODUCT_IMG_PATH','http://'.$_SERVER["HTTP_HOST"].IMG_URL);

define('SMTP_AUTH',true);
define('SMTP_SECURE',false);
define('SMTP_PORT',25);
define('SMTP_USER','rushiraj.jhala@etatvasoft.com');
define('SMTP_PASS','rushiraj123');
define('SMTP_HOST','smtpout.secureserver.net');
define('SMTP_FROM_NAME','donotreply@huurre.com');
define('CRON_SECOND','10000000000');


define('REG_USER','registration@tickerfriends.com');
define('REG_PASS','rushiraj');
define('ADDR_EMAIL','adr@tickerfriends.com');
define('TATVA_EMAIL','pl3@tatvasoft.com');
?>
 <?php

// ** MySQL settings - You can get this info from your web host ** //
error_reporting(1);

define("DB_HOST","localhost");//192.168.10.2

define("DB_USER_NAME","phpteam");//phpteam
define("DB_PASSWORD","phpteam");//phpteam

define("DB_NAME","aviary");

define('SITE_URL',  '/aviary_app/');
define('SITE_PATH', $_SERVER["DOCUMENT_ROOT"] . SITE_URL);

define('ADMIN_PATH', $_SERVER["DOCUMENT_ROOT"] . ADMIN_URL);

 

    define('ADMIN_URL', SITE_URL . 'admin/');
    define('ADMIN_PATH', $_SERVER["DOCUMENT_ROOT"] . ADMIN_URL);
    define('VIEW_URL', SITE_URL . 'view/');
    define('VIEW_PATH', SITE_PATH . 'view/');
    define('CSS_PATH', VIEW_URL . 'css/');
    define('PLUGINS_PATH', VIEW_URL . 'plugins/');
    define('IMAGES_PATH', VIEW_URL . 'images/');
    define('JS_PATH', VIEW_URL . 'js/');
    define('LANGUAGE_PATH', SITE_PATH.'languages/');
    define('ADMIN_LANGUAGE_PATH', LANGUAGE_PATH.'admin/');
    define('INCLUDE_PATH', SITE_PATH.'include/');
    define('COMMON_CORE_PATH', SITE_PATH. 'core_application/');
    define('COMMON_CORE_MODEL', SITE_PATH . 'model/');
    define('COMMON_CORE_COMPONENT', SITE_PATH . 'component/');
    define('COMMON_CORE_PHPMAILER', SITE_PATH . 'phpmailer/');
    define('ADMIN_CONTROLLER_PATH', ADMIN_PATH . 'controller/');
    define('ADMIN_VIEW_PATH', VIEW_PATH.'admin/');
    define('COMMON_JS_PATH', 'common/');
    define('UPLOADED_STUFF_PATH', SITE_PATH . 'uploads/');
    define('UPLOADED_STUFF_URL', SITE_URL . 'uploads/');
    define('AUDIO_PATH', UPLOADED_STUFF_PATH . 'audio/');
    define('AUDIO_URL', UPLOADED_STUFF_URL . 'audio/');
    define("DATE_FORMAT", "Y-m-d");
    define("TIME_FORMAT", "H:i:s");
    define("FROM_EMAIL", "info@greetingapp.com"); 
    define('LIBRARIES_URL',SITE_URL.'libraries/');
    define('LIBRARIES_PATH',SITE_PATH.'libraries/');
    define('TWILIO_LIBRARY_PATH',LIBRARIES_PATH.'twilio/twilio-php/'); 
    define('TWILIO_AccountSid','AC511fdb5297decc80b0a5dcfc67162935'); 
    define('TWILIO_AuthToken','100708da81fc7c11a2e44b500c973e87');    
    define('ENC_KEY','ENC_KEY_GREETING_APP_!@#');  
?>
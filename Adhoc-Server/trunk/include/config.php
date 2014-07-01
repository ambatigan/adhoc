<?php
//error_reporting(0);
//error_reporting(E_ALL & ~E_NOTICE);// & ~E_NOTICE & ~E_WARNING
error_reporting(E_ALL & ~E_NOTICE);
//ini_set('display_errors', 'on');
session_start();

// Send Notfication MAIL Limit defind as - 10
define('GSM_USERS_MAIL_LIMIT',1000);
define("ADMIN_DEFAULT_LANGUAGE_ID", "1");
define("ADMIN_DEFAULT_LANGUAGE_NAME", "ENGLISH");


// for Live defines the database username and password.
/*define("DB_HOST","ec2-54-221-222-67.compute-1.amazonaws.com");//192.168.10.2
define("DB_USER_NAME","root");//phpteam
define("DB_PASSWORD","digbicks22!");//phpteam by renuka*/

define("DB_HOST","localhost");//192.168.10.2
define("DB_USER_NAME","root");//phpteam
define("DB_PASSWORD","");
define("DB_NAME","aviary_app_final");
define("GOOGLE_API_KEY", "AIzaSyBAMrn5TGe-FXGPo76F38fR3IdxHZIH0pI"); // Live : Place your Google API Key

//paths of Application
define('SITE_URL', '/');
define('SITE_HOST', 'http://'.$_SERVER["HTTP_HOST"].SITE_URL);
define('SITE_PATH', $_SERVER["DOCUMENT_ROOT"] . SITE_URL);

define('ADMIN_URL', SITE_URL . 'admin/');
define('ADMIN_PATH', $_SERVER["DOCUMENT_ROOT"] . ADMIN_URL);
define('VIEW_URL', SITE_URL . 'view/');
define('VIEW_PATH', SITE_PATH . 'view/');
define('CSS_PATH', VIEW_URL . 'css/');
define('JS_PATH', VIEW_URL . 'js/');
define('LANGUAGE_PATH', SITE_PATH . 'languages/');
define('ADMIN_LANGUAGE_PATH', LANGUAGE_PATH . 'admin/');
define('INCLUDE_PATH', SITE_PATH . 'include/');
define('COMMON_CORE_PATH', SITE_PATH . 'core_application/');
define('COMMON_CORE_MODEL', SITE_PATH . 'model/');
define('COMMON_CORE_COMPONENT', SITE_PATH . 'component/');
define('ADMIN_CONTROLLER_PATH', ADMIN_PATH . 'controller/');
define('ADMIN_VIEW_PATH', VIEW_PATH . 'admin/');
define('COMMON_JS_PATH', 'common/');
define('UPLOADED_STUFF_PATH', SITE_PATH . 'uploads/');
define('UPLOADED_STUFF_URL', SITE_URL . 'uploads/');
define('ICON_LOGO_PATH', UPLOADED_STUFF_PATH . 'products/icon_logo/');
define('PRODUCT_IMAGE_PATH', UPLOADED_STUFF_PATH.'products/');
define('PRODUCT_IMAGE_URL', SITE_HOST.'uploads/products/');
define('TMP_PATH', SITE_PATH . '/temp/');
define("ADMIN_IMAGE_URL", SITE_URL . "images/theme_images/");
define("ADMIN_IMAGE_PATH",SITE_PATH."images/theme_images/");
define('LIBRARIES_URL', SITE_URL . 'libraries/');
define('LIBRARIES_PATH', SITE_PATH . 'libraries/');
define("IMAGE_EXTENSIONS", "jpeg,jpg,png,gif");
define("VIDEO_EXTENSIONS", "avi,wmv,flv,mp4");
define("FILE_EXTENSIONS", "html,htm");
define("MYSQL_DATE_FORMAT", "%Y-%m-%d");
define("MYSQL_DATE_FORMAT_WHOLE", "%Y-%m-%d %H:%i:%s");
define("DATE_FORMAT_WHOLE", "Y-m-d H:i:s");
define("DATE_FORMAT", "Y-m-d");
define("DATE_FORMAT_DATEPICKER", "yy-mm-dd");
define("DATE_FORMAT_TIMEPICKER", "hh:mm:ss");
define("ALLOWED_UPLOAD_FILE_TYPES", "");
// for CK Editor library Path ---
define("CK_EDITOR_PATH",LIBRARIES_PATH."ckeditor/");
define("CK_EDITOR_URL",LIBRARIES_URL."ckeditor/");
define("CK_FINDER_PATH",LIBRARIES_PATH."ckfinder/");
define("CK_FINDER_URL",LIBRARIES_URL."ckfinder/");
define("ADMIN_PAGGING_STYLE","3");


  define("USER_IMAGE_PATH",SITE_PATH."uploads/user_image/");
  define("PHOTO_IMAGE_PATH",SITE_PATH."uploads/photo_image/");

  define("PHOTO_IMAGE_URL",SITE_URL."uploads/photo_image/");
  define("USER_IMAGE_URL",SITE_URL."uploads/user_image/");


  define("USER_URL",ADMIN_IMAGE_URL."user/");
  define("USER_PATH",ADMIN_IMAGE_PATH."user/");

  define("PHOTO_URL",ADMIN_IMAGE_URL."photo/");
  define("PHOTO_PATH",ADMIN_IMAGE_PATH."photo/");

  define("ALLOWED_UPLOAD_FILE_TYPES","");
  define("PHOTO_PATH", UPLOADED_STUFF_PATH . "popular_application/");
  define("PHOTO_URL", UPLOADED_STUFF_URL . "popular_application/");
  define("VIDEO_PATH", UPLOADED_STUFF_PATH . "video/");
  define("VIDEO_URL", UPLOADED_STUFF_URL . "video/");
  define('ENC_KEY','ENC_KEY_GREETING_APP_!@#');


?>
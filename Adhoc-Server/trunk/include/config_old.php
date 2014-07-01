<?php

	//error_reporting(E_ALL & ~E_NOTICE);// & ~E_NOTICE & ~E_WARNING
	error_reporting(0);// & ~E_NOTICE & ~E_WARNING
	session_start();
	
	// Email
	define("FROM_EMAIL", "kartik.shukla@sparsh.com");
	define("FROM_NAME", "Kartik Shukla");

	//paths of demo appliation
	define('SITE_URL',  '/Rating/');
	define('SITE_PATH', $_SERVER["DOCUMENT_ROOT"] . SITE_URL);
	define('ADMIN_URL', SITE_URL . 'admin/');
	define('ADMIN_PATH', $_SERVER["DOCUMENT_ROOT"] . ADMIN_URL);
	define('VIEW_URL', SITE_URL . 'view/');
	define('VIEW_PATH', SITE_PATH . 'view/');
	define('CSS_PATH', VIEW_URL . 'css/');
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
	define('TMP_PATH', SITE_PATH . '/temp/');
	define("ADMIN_IMAGE_URL",SITE_URL."images/");

	define('LIBRARIES_URL',SITE_URL.'libraries/');
	define('LIBRARIES_PATH',SITE_PATH.'libraries/');
	define("FCK_EDITOR_PATH",LIBRARIES_PATH."fckeditor/");
	define("FCK_EDITOR_URL",LIBRARIES_URL."fckeditor/");
    define("FCK_EDITOR_HEIGHT", "500");
	define("MPDF_PATH",$_SERVER["DOCUMENT_ROOT"].LIBRARIES."/mpdf/mpdf.php");

    //kartik added
    define("BRAND_IMAGE_PATH", UPLOADED_STUFF_PATH . "brand/");
    define("BRAND_IMAGE_RESIZE_PATH", BRAND_IMAGE_PATH ."brand_resize/");
    define("BRAND_IMAGE_URL", UPLOADED_STUFF_URL . "brand/");
    define("BRAND_IMAGE_RESIZE_URL", BRAND_IMAGE_URL ."brand_resize/");

    define("BANNER_IMAGE_PATH", UPLOADED_STUFF_PATH . "banner/");
    define("BANNER_IMAGE_RESIZE_PATH", BANNER_IMAGE_PATH ."banner_resize/");
    define("BANNER_IMAGE_URL", UPLOADED_STUFF_URL . "banner/");
    define("BANNER_IMAGE_RESIZE_URL", BANNER_IMAGE_URL ."banner_resize/");
    define(PRODUCT_IMAGES, "product_images");
    define("PRODUCT_IMAGE_PATH", UPLOADED_STUFF_PATH . PRODUCT_IMAGES . "/");
    define("PRODUCT_IMAGE_URL", UPLOADED_STUFF_URL . PRODUCT_IMAGES . "/");
    define("PRODUCT_IMAGE_RESIZE_PATH", PRODUCT_IMAGE_PATH . PRODUCT_IMAGES . "_resize/");
    define("PRODUCT_IMAGE_RESIZE_URL", PRODUCT_IMAGE_URL . PRODUCT_IMAGES . "_resize/");
    define("PROMOTIONAL_CATEGORY_IMAGE_PATH", UPLOADED_STUFF_PATH . "promotional_category/");
    define("PROMOTIONAL_CATEGORY_IMAGE_URL", UPLOADED_STUFF_URL . "promotional_category/");
    
    define("CATEGORY_IMAGE_PATH", UPLOADED_STUFF_PATH . "category/");
    define("CATEGORY_IMAGE_RESIZE_PATH", CATEGORY_IMAGE_PATH ."category_resize/");
    define("CATEGORY_IMAGE_URL", UPLOADED_STUFF_URL . "category/");
    define("CATEGORY_IMAGE_RESIZE_URL", CATEGORY_IMAGE_URL ."category_resize/");
    
    /* for category hover image */
    define("CATEGORY_HOVER_IMAGE_PATH", UPLOADED_STUFF_PATH . "category_hover/");
    define("CATEGORY_HOVER_IMAGE_RESIZE_PATH", CATEGORY_HOVER_IMAGE_PATH ."category_hover_resize/");
    define("CATEGORY_HOVER_IMAGE_URL", UPLOADED_STUFF_URL . "category_hover/");
    define("CATEGORY_HOVER_IMAGE_RESIZE_URL", CATEGORY_HOVER_IMAGE_URL ."category_hover_resize/");
    
    
    define("BUSINESS_PROMO_IMAGE_WIDTH", "350");
	define("BUSINESS_PROMO_IMAGE_HEIGHT", "350");
    
    
    define("BUSINESS_IMAGE_PATH", UPLOADED_STUFF_PATH . "business/");
    
    define("BUSINESS_IMAGE_RESIZE_PATH", BUSINESS_IMAGE_PATH ."business_resize/");
    define("BUSINESS_IMAGE_URL", UPLOADED_STUFF_URL . "business/");
    define("BUSINESS_IMAGE_RESIZE_URL", BUSINESS_IMAGE_URL ."business_resize/");
    
    
    define("BUSINESS_PROMO_IMAGE_PATH", UPLOADED_STUFF_PATH . "business_promo/");
    define("BUSINESS_PROMO_IMAGE_RESIZE_PATH", BUSINESS_PROMO_IMAGE_PATH ."business_promo_resize/");
    define("BUSINESS_PROMO_IMAGE_URL", UPLOADED_STUFF_URL . "business_promo/");
    define("BUSINESS_PROMO_IMAGE_RESIZE_URL", BUSINESS_PROMO_IMAGE_URL ."business_promo_resize/");
    
    

    define("ADMIN_IMAGE_HEIGHT", "105");
    define("ADMIN_IMAGE_WIDTH", "160");
    
    define("ADMIN_BANNER_IMAGE_HEIGHT", "44");
    define("ADMIN_BANNER_IMAGE_WIDTH", "320");
    
	define("ADMIN_PROMO_IMAGE_HEIGHT", "250");
    define("ADMIN_PROMO_IMAGE_WIDTH", "300");
    
    
    
    define("LIST_IMAGE_HEIGHT", "120");
    define("LIST_IMAGE_WIDTH", "120");
    define("DETAILS_IMAGE_HEIGHT", "254");
    define("DETAILS_IMAGE_WIDTH", "240");
    define("DETAILS_SMALL_IMAGE_HEIGHT", "59");
    define("DETAILS_SMALL_IMAGE_WIDTH", "67");
    define("HOT_DEALS_IMAGE_HEIGHT", "190");
    define("HOT_DEALS_IMAGE_WIDTH", "242");
    define("CART_IMAGE_HEIGHT", "70");
    define("CART_IMAGE_WIDTH", "70");
    define("PROMO_CAT_IMAGE_HEIGHT", "146");
    define("PROMO_CAT_IMAGE_WIDTH", "120");
    define("BANNER_IMAGE_HEIGHT", "75");
    define("BANNER_IMAGE_WIDTH", "220");
    define("CLEARANCE_IMAGE_HEIGHT", "139");
    define("CLEARANCE_IMAGE_WIDTH", "98");
    define("CATEGORY_IMAGE_HEIGHT", "117");
    define("CATEGORY_IMAGE_WIDTH", "232");
    

    define("BUSINESS_IMAGE_WIDTH", "350");
	define("BUSINESS_IMAGE_HEIGHT", "350");
    
    
	
	
	
    define("CATEGORY_HOVER_IMAGE_HEIGHT", "117");
    define("CATEGORY_HOVER_IMAGE_WIDTH", "232");
    
    //end

	//defines the database username and password.
	define("DB_HOST","localhost");//192.168.10.2
    define("DB_USER_NAME","root");//phpteam
    define("DB_PASSWORD","");//phpteam
	define("DB_NAME","rating_app");
	define("ADMIN_PAGGING_STYLE","3");
	define("REQUIRED_FIELD","<label class=\"required\">*</label>");

   /*  for FRONT */
   	define('FRONT_URL', SITE_URL . 'front/');
	define('FRONT_PATH', $_SERVER["DOCUMENT_ROOT"] . FRONT_URL);
	define('FRONT_VIEW_PATH', VIEW_PATH.'front/');
	define('FRONT_CONTROLLER_PATH', FRONT_PATH.'controller/');
	define("FRONT_IMAGE_URL",SITE_URL."images/");
    define("FRONT_PAGGING_STYLE","3");
	define('FRONT_LANGUAGE_PATH', LANGUAGE_PATH.'front/');
    define("IMAGE_EXTENSIONS", "jpeg,jpg,gif");
	define("VIDEO_EXTENSIONS", "avi,wmv,flv,mp4");
    define('IMAGE_RESIZE_PATH', '/images/');
	define('UPLOAD_PATH', SITE_PATH . 'uploads/');
	define('UPLOAD_URL',  '/uploads/');
	define("DATE_FORMAT","Y-m-d");
	define("DATE_FORMAT_WHOLE","Y-m-d H:i:s");
    define("FILE_IMAGE_PATH", UPLOAD_PATH . "file/");
    define("FILE_IMAGEMAP_PATH", UPLOAD_PATH . "mapfile/");
    define("LOGO_PATH", UPLOAD_PATH . "logo/");
    define("FILE_EXTENSIONS", "html,htm");
	define("MYSQL_DATE_FORMAT","%Y-%m-%d");
	define("MYSQL_DATE_FORMAT_WHOLE","%Y-%m-%d %H:%i:%s");

    define("ALLOWED_UPLOAD_FILE_TYPES","");
	define("PHOTO_PATH", UPLOADED_STUFF_PATH . "popular_application/");
	define("PHOTO_URL", UPLOADED_STUFF_URL . "popular_application/");
	define("VIDEO_PATH", UPLOADED_STUFF_PATH . "video/");
	define("VIDEO_URL", UPLOADED_STUFF_URL . "video/");

    // User types
    define("ADMIN_ID","1");
    define("MANAGER_ID","2");
    define("PUBLISHER_ID","3");
    //
    define("DATE_FORMAT", "Y-m-d");
    define("DATE_FORMAT_DATEPICKER", "yy-mm-dd");
    define("DATE_FORMAT_TIMEPICKER", "hh:mm:ss");
    define("DATE_FORMAT_WHOLE", "Y-m-d H:i:s");
    //Commission types
    define("CPA_ID","1");
    define("REVSHARE_ID","2");
    define("HYBRID_ID","3");
    define("CPL_ID","4");

    define('SMTP_AUTH',true);
define('SMTP_SECURE',false);
define('SMTP_PORT',25);
define('SMTP_USER','rushiraj.jhala@etatvasoft.com');
define('SMTP_PASS','rushiraj123');
define('SMTP_HOST','smtpout.secureserver.net');
define('SMTP_FROM_NAME','donotreply@timetracking.com');
define('CRON_SECOND','10000000000');


define('REG_USER','registration@tickerfriends.com');
define('REG_PASS','rushiraj');
define('ADDR_EMAIL','adr@tickerfriends.com');
define('TATVA_EMAIL','pl3@tatvasoft.com');

?>
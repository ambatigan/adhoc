<?php


	require_once(COMMON_CORE_PATH."class.database.php");
	require_once(INCLUDE_PATH."table_vars.php");
	require_once(COMMON_CORE_PATH."class.common.php");
	require_once(COMMON_CORE_MODEL."config_vars.class.php");
	//require_once(COMMON_CORE_MODEL."left_menu.class.php");
	include_once(INCLUDE_PATH."logged_in_user.php");

	$dbAccess = new DBAccess(DB_HOST,DB_USER_NAME,DB_PASSWORD,DB_NAME);
  	$commonFunction = new CommonFunction;
	$objConfigVARS = new config_vars($dbAccess);

        /****** Generating the dynamic menu End ********/
        /********************************** Language Settings ******************************/
    //get Site languages
    $sitelanguages = $commonFunction->getSiteLanguages();

    //get current set language
    if(isset($_GET['lang']) && $_GET['lang']!=''){
       $lang =$_GET['lang'];
    } else if(isset($_COOKIE["cur_lang"]) && $_COOKIE["cur_lang"]!=''){
       $lang = $_COOKIE["cur_lang"];
    } else {
      $lang = 'en';
    }
    //$lang = (isset($_GET['lang']) && $_GET['lang']!='')?$_GET['lang']:'en';
    if ( isset( $lang) && $lang!='') {

        //check language
        if(isset($sitelanguages) && $sitelanguages != ''){
          foreach($sitelanguages as $sitelang_val){
             if($lang == $sitelang_val['lng_code']){
                 $cur_lang_id =  $sitelang_val['id'];
             }
          }
        }

		setcookie( "cur_lang", $lang,time()+3600);
		$_COOKIE["cur_lang"] =  $lang;
	}

    $cur_lang = $_COOKIE["cur_lang"];

    /********************************** End Language Settings ******************************/
        
	$CONFIG_VARS = $objConfigVARS->selectGlobalVariablesArray();
	define("RECORDS_PER_PAGE",$CONFIG_VARS['var_rows_per_page']);
	define("GOOGLE_ANALYTIC_CODE",$CONFIG_VARS['google_analytic_code']);

	define("PROJECT_NAME","Project Name");
	define("NO_OF_WHATSNEW_ITEMS",$CONFIG_VARS['no_of_whats_new_item']);
	define("NO_OF_BRANDS_ON_HOME_PAGE",$CONFIG_VARS['brands_on_home_page']);

?>
<?php
    if($_REQUEST['language_id']!="" && isset($_REQUEST['language_id']))
     {
       if(isset($_SESSION['language_id']))
       {
         unset($_SESSION['language_id']);
       }
       $_SESSION['language_id']= $_REQUEST['language_id'];
       //print_r($_REQUEST); exit;
     }
	 
	/****** Including Language File ********/
	
	$lang = (isset($_SESSION['domain_name']))?$_SESSION['domain_name']:'eng';
    if(!file_exists(FRONT_LANGUAGE_PATH.$lang.'/index.php'))
	{
	   	$lang = 'eng';
	}
    if(!isset($_SESSION['language_id']))
    {
        $_SESSION['language_id'] = 1;
    }
    //$lang_folder = 'eng';
	include_once(FRONT_LANGUAGE_PATH.$lang.'/index.php');

	/****** Including Language File ********/

	/****** Including Controller File ********/
	$page = (isset($_REQUEST['page']) && $_REQUEST['page']!='')?$_REQUEST['page']:'login';
	if(!file_exists(ADMIN_CONTROLLER_PATH . $page . '.php'))
	{
  		$page = 'home';
	}

    $login_not_required_pages = array('login');
    $admin_pages = array('cms_list','add_cms','language_list','add_language','language_var_list','add_language_var','company_list','add_company','settings_mgmt');
    if (isset($_GET) && !empty($_GET)) {
        foreach ($_GET as $k => $d) {
            $get[] = $k;
            $get[] = $d;
        }
        $get_ = implode(',', $get);
    }

    if (logged_in_user::id() == 0 && !in_array($page, $login_not_required_pages)) {
        $commonFunction->Redirect('./?page=login&action=view&back_url=' . $get_);
    }

    /***********************************************/
    // Include Push Notification Class
    /***********************************************/

    include_once 'apns/apns.php';

    function action_message($colspan=1)
    {
        if(isset($_SESSION['message']))
        {
          $string='';
          $string .= '<div class="msg-content-box">';
          $string .= '<div class="alert alert-success">';
          $string .= '<button type="button" class="close" data-dismiss="alert">x</button>'.$_SESSION['message'];
          $string .= '</div>';
          $string .= '</div>';
          unset($_SESSION['message_type']);
          unset($_SESSION['message']);
          echo $string;
        }
    }

    if(in_array($page,$admin_pages) && logged_in_user::user_type() == 2)
    {
        $commonFunction->Redirect('./?page=home'); exit;
    }

	include(ADMIN_CONTROLLER_PATH . $page . '.php');
	/****** Including Controller File ********/

	/****** Including Master Page File ********/
    $page_array_master= array('get_project','get_user');

    if(!in_array($page,$page_array_master))
	include_once(ADMIN_VIEW_PATH."master_page.php");

?>
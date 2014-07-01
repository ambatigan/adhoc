<?php
if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'logout')
{
    logged_in_user::logout();
    $commonFunction->Redirect('?page=login&action=view');
    exit;
}
if(logged_in_user::id() != 0)
{
    $commonFunction->Redirect('?page=home&action=view');
    exit;
}

if (logged_in_user::id())
{   //echo "fgfg";die;
	$commonFunction->Redirect('?page=home');
	exit;
}
$title = 'Login';
require_once(COMMON_CORE_MODEL."users.class.php");

$action = isset($_REQUEST['action'])?$_REQUEST['action']:'';

// creating object of user class
$obj_user= new users();


foreach($obj_user as $k=>$d)
{
	if($k!='db')
	{
		$obj_user->$k =(isset($_REQUEST[$k]) && $_REQUEST[$k] != '')?$_REQUEST[$k]:$objCms->$k;
	}
}
//login clicked
if(isset($_POST['LoginFormSubmit']))
{
	//print_r($_POST);echo 'here'; exit;
	$validateData['required'] = array('user_name'=>"Username is required",
									   'password'=>"Password is required" );
	$errorMsg = $commonFunction->validate_form($_POST, $validateData);


	if(count($errorMsg) == 0)
    {
       // echo md5("myfreeapp%"); echo "<br><br>";
		$were=" WHERE  user_name = '".mysql_real_escape_string($obj_user->user_name)."' and password = '".mysql_real_escape_string(md5($obj_user->password)   )."' AND user_type = 'administrator' ";
//echo $were;
//exit;
		if($userDetails = $obj_user->select(null,$were))
		{
			$obj = new logged_in_user();
			$obj->set_id($userDetails[0][id]);
			$obj->set_username($userDetails[0][user_name]);
			$obj->set_name($userDetails[0][name]);

			if(isset($_REQUEST['back_url']) && $_REQUEST['back_url']!=''&& $obj_user->user_type=='1')
			{
				$back_url = explode(',', $_REQUEST['back_url']);
				$cnt=1;
				foreach($back_url as $k=>$d)
				{
					if($cnt%2==1)
					{
						$back .= $d."=";
					}
					elseif($cnt%2==0)
					{
						$back .= $d."&";
					}
					$cnt++;
				}
				$back = rtrim($back, '&');
				$commonFunction->Redirect('?'.$back);
			}
			else
			{
				$commonFunction->Redirect('./?page=home');
				exit;
			}

		}
		else
		{

			$errorMsg[] ="Invalid username/password";
		}
	}
}
// Forgate Passwords //
/*if(isset($_REQUEST['ajax']) && $_REQUEST['ajax']==1)
{

	$obj_user = new user();
	$email = $_REQUEST['email_id'];

	$result = $obj_user->check_user_availibility($email);
	// print_r($result[0][email_address]); exit;
	if(count($result) != false)
	{

		$password = $commonFunction->GenratePassword();
		$ret_details= array();
		$ret_details = $obj_user->update_pass_byemail($password,$email);
		//echo $password; exit;
		$vars_email = Array('recipient_email' => $result[0][email_address],
                                                'recipient_name' => $result[0][first_name].' '.$result[0][last_name],
                                                'cc_email' => '',
                                                'cc_name' => '',
                                                'mail_vars' => Array
		(
                                                        'firstname' => $result[0][first_name],
                                                        'lastname' => $result[0][last_name],
                                                        'username' => $result[0][user_name],
                                                        'password' => $password
		)
		);

		$commonFunction->mailing('forget_password', $vars_email);
		echo "Password Sent Successfully";
		echo "<script>document.getElementById('email').value='';</script>";

		exit;

	}
	else
	{
		echo index_lang::translate("Email does not exist.");  exit;

	}
}*/

?>
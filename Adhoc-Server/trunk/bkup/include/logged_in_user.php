<?php
class logged_in_user
{
	public static function id()
	{
	if(isset($_SESSION['logged_in_user_id']))
		return $_SESSION['logged_in_user_id'];

	else
		return 0;
	}

    public static function front_id()
	{
	if(isset($_SESSION['logged_in_user_front_id']))
		return $_SESSION['logged_in_user_front_id'];

	else
		return 0;
	}

	public function set_id($id)
	{                          
		$_SESSION['logged_in_user_id'] = $id;
	}
    //
    public function set_user_type($id)
	{
		$_SESSION['logged_in_user_type'] = $id;
	}
     public static function user_type()
	{
    	if(isset($_SESSION['logged_in_user_type']))
    		return $_SESSION['logged_in_user_type'];

    	else
    		return 0;
	}

    public function set_front_id($id)
	{
		$_SESSION['logged_in_user_front_id'] = $id;
	}

	public static function username()
	{
	if(isset($_SESSION['logged_in_user_username']))
		return $_SESSION['logged_in_user_username'];
	else
		return 0;
	}

    public static function front_username()
	{
	if(isset($_SESSION['logged_in_user_front_username']))
		return $_SESSION['logged_in_user_front_username'];
	else
		return 0;
	}


	public function set_username($username)
	{
		$_SESSION['logged_in_user_username'] = $username;
	}

    	public function set_front_username($username)
	{
		$_SESSION['logged_in_user_front_username'] = $username;
	}


	public static function front_firstname()
	{
	if(isset($_SESSION['logged_in_user_front_firstname']))
		return $_SESSION['logged_in_user_front_firstname'];
	else
		return 0;
	}

    public static function firstname()
	{
	if(isset($_SESSION['logged_in_user_firstname']))
		return $_SESSION['logged_in_user_firstname'];
	else
		return 0;
	}

	public function set_name($username)
	{
		$_SESSION['logged_in_user_name'] = $username;
	}

    public function set_front_firstname($username)
	{
		$_SESSION['logged_in_user_front_firstname'] = $username;
	}


	public static function lastname()
	{
	if(isset($_SESSION['logged_in_user_lastname']))
		return $_SESSION['logged_in_user_lastname'];
	else
		return 0;
	}

    public static function front_lastname()
	{
	if(isset($_SESSION['logged_in_user_front_lastname']))
		return $_SESSION['logged_in_user_front_lastname'];
	else
		return 0;
	}

	public function set_lastname($username)
	{
		$_SESSION['logged_in_user_lastname'] = $username;
	}

    	public function set_front_lastname($username)
	{
		$_SESSION['logged_in_user_front_lastname'] = $username;
	}

	public static function email()
	{
    	if(isset($_SESSION['logged_in_user_email']))
    		return $_SESSION['logged_in_user_email'];
    	else
    		return '';
	}

    public static function front_email()
	{
    	if(isset($_SESSION['logged_in_user_front_email']))
    		return $_SESSION['logged_in_user_front_email'];
    	else
    		return '';
	}
     //
     public static function company()
	{
    	if(isset($_SESSION['logged_in_user_company']))
    		return $_SESSION['logged_in_user_company'];
    	else
    		return '';
	}
     public function set_user_company($company)
	{
		$_SESSION['logged_in_user_company'] = $company;
	}


	public function set_email($email)
	{
		$_SESSION['logged_in_user_email'] = $email;
	}

    	public function set_front_email($email)
	{
		$_SESSION['logged_in_user_front_email'] = $email;
	}

    public static function front_company()
	{
      	if(isset($_SESSION['logged_in_user_front_company']))
      		return $_SESSION['logged_in_user_front_company'];
      	else
      		return 0;
	}

        public function set_front_company($points)
	{
		$_SESSION['logged_in_user_front_company'] = $points;
	}






	public static function logout()
	{
		unset($_SESSION['merchant_id']);
		unset($_SESSION['offline_merchant_name']);
		unset($_SESSION['rand_num']);
		unset($_SESSION['logged_in_user_id']);
		unset($_SESSION['logged_in_user_username']);
		unset($_SESSION['logged_in_user_email']);
		unset($_SESSION['logged_in_user_display_name']);
		unset($_SESSION['logged_in_user_company']);
		unset($_SESSION['logged_in_user_type']);
		unset($_SESSION['logged_in_user_lastname']);
		unset($_SESSION['logged_in_user_firstname']);

	}


    public static function front_logout()
	{

		unset($_SESSION['logged_in_user_front_id']);
		unset($_SESSION['logged_in_user_front_username']);
		unset($_SESSION['logged_in_user_front_email']);
		unset($_SESSION['logged_in_user_front_lastname']);
		unset($_SESSION['logged_in_user_front_firstname']);
		unset($_SESSION['logged_in_user_front_company']);

	}
}
/*
echo logged_in_user::id();
echo logged_in_user::id();
$obj1 = new logged_in_user();
$obj1->set_id(1);
*/

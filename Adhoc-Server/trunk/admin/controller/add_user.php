<?php
$action = isset($_GET['action']) ? $_GET['action'] : '';

if($_GET['action']=='edit' && $_GET['id']!=''){
    $heading = "User Management";
    $title = "Edit User";
}
else{
    $heading = "User Management";
    $title = "Add User";
}

require_once(COMMON_CORE_MODEL . "users.class.php");
$objUser = new users();
$objUser->id = (isset($_GET['id']) && $_GET['id'] != '' && is_numeric($_GET['id'])) ? htmlspecialchars($_GET['id'], ENT_QUOTES, 'UTF-8') : $objUser->id;
$user = array();

///******* Edit Case *********//
if ($objUser->id != '' && $action == 'edit')
{
    $objUser->selectById();  
    if ($objUser->id == "")
    {
    	$_SESSION['message_type'] = 'error';
        $_SESSION['message'] = "Record does not exist";
        $objUser->commonFunction->Redirect('./?page=users_listing');
    }
    else
    {
        $user = (array) $objUser;   
    }
} 

//echo "<pre>"; print_r($user);exit;

///******* Insert / Update Case *********//
if (isset($_POST['addEditUserSubmit']))
{
    //echo "<pre>"; print_r($_POST); exit;
    
    foreach ($objUser as $k => $d)
    {
        if($k!='password'){
            $objUser->$k = (isset($_POST[$k])) ? htmlspecialchars($_POST[$k], ENT_QUOTES, 'UTF-8') : $objUser->$k;
        }
    }

    $objUser->password = (isset($_POST['password']) && $_POST['password'] != '') ? md5($_POST['password']) : $objUser->password;
    
    if($_GET['action']=='edit' && $_GET['id']!='')
    {
         $validateData['required'] = array('user_name' => 'Please provide username',
                                           'email' => 'Please provide email',

                                           );
    }
    else
    {
         $validateData['required'] = array('user_name' => 'Please provide username',
                                           'password' => 'Please provide password',
                                           'confirmpassword' => 'Please provide confirm password',
                                           'email' => 'Please provide email',
                                           );
    }
    
    $validateData['email'] = array('email' => 'Please enter a valid e-mail address');

    $errorMsg = $commonFunction->validate_form($_POST, $validateData);

    if(!ctype_alnum($_POST['user_name'])){
        $errorMsg[] = "Please enter alpha-numeric values for username.";
    }
    
    if(preg_match('/\s/',$_POST['user_name'])==1){
        $errorMsg[] = "Please enter username without space";
    }

    if(strlen($_POST['user_name'])>25){
       $errorMsg[] = "Username should contain atmost 25 characters"; 
    }
    

    if($_POST['password']!=''){
        if(strlen($_POST['password'])<6 || strlen($_POST['password'])>25){
           $errorMsg[] = "Password should contain atleast 6 characters and atmost 25 characters"; 
        }
    }

    if($_POST['confirmpassword']!=''){
        if(strlen($_POST['confirmpassword'])<6 || strlen($_POST['confirmpassword'])>25){
           $errorMsg[] = "Confirm Password should contain atleast 6 characters and atmost 25 characters"; 
        }
    }
    if ($_POST['password'] != $_POST['confirmpassword']) 
    {
       $errorMsg[] = "Password does not match confirm password";
    }
    
    if($_POST['first_name']!='' && !ctype_alpha($_POST['first_name'])){
       $errorMsg[] = "Please enter a valid first name"; 
    }
    
    if($_POST['last_name']!='' && !ctype_alpha($_POST['last_name'])){
       $errorMsg[] = "Please enter a valid last name"; 
    }

    $user = (array) $objUser;
    // print_r($user);exit;
     
    if (count($errorMsg) == 0) {
        if($objUser->insertUpdate($objUser->id) == false)
        { 
            $errorMsg[] = $objUser->error_message;
        } 
        else
        {
            if (!isset($objUser->id))
            {
               $_SESSION['message_type'] = 'success';
               $_SESSION['message'] = "User successfully added";
               $commonFunction->Redirect('./?page=users_listing');
               exit;
            }
            else 
            {
               $_SESSION['message_type'] = 'success';
               $_SESSION['message'] = "User successfully updated";
               $commonFunction->Redirect('./?page=users_listing');
               exit;
            }
        }
    }
}
if ($objUser->id != '' && $action == 'statusupdate')
{
                echo $currentStatus = $_REQUEST['status'];
                if($currentStatus == 'Unblock'){
                $currentStatus = 'Inactive';
                $message = "User Blocked successfully";
                } else {
                $currentStatus = 'Active';
                $message = "User Unblock successfully";
                }

                $commentId = $_REQUEST['id'];
                $photo = $objUser->updateStatus($currentStatus,$commentId);
                $_SESSION['message_type'] = 'success';
               $_SESSION['message'] = $message;
               $commonFunction->Redirect('./?page=users_listing');
               exit;

}
///******* Insert / Update Case *********//
?>